let fpCart;
const cart = class {
    invoker;
    container;
    items = new Map();
    cartElem;
    itemsElem;
    contentElem;
    msgElem;
    statusElem;
    statusMsgElem;
    bookButton;
    maxOptions = 1;
    cartCnt = 0;
    post_cart_callback;

    constructor(container, invoker = null) {
        this.container = container;
        if (invoker !== null) {
            this.invoker = invoker;
        }
        this.cartElem = document.querySelector("div#cart");
        this.bookButton = document.querySelector("div#cart > div.modal-dialog > div.modal-content > div.modal-footer > button#btn_book");
        this.itemsElem = document.querySelector("div#cart > div.modal-dialog > div.modal-content > div.modal-body > ul#cart_items");
        this.msgElem = document.querySelector("div#cart > div.modal-dialog > div.modal-content > div.modal-body > div#cart_msg");
        this.contentElem = document.querySelector("div#cart > div.modal-dialog > div.modal-content > div#content");

        this.refreshCount();
    }

    addItem(item) {

        // if (this.cartCnt > this.maxOptions) {
        //     alert("This stand option cannot be added. Maximum of " + this.maxOptions + " options is allowed.");
        //     return false;
        // }
        if (item instanceof cartItem) {
            this.items.set(item.urn, item);
            this.appendItemControls(item);
            this.itemsElem.appendChild(item.element);
            // this.refreshCount();
        }
    }
    removeAllItems() {
        for (const [key, value] of this.items) {
            let item = value;
            this.itemsElem.removeChild(item.element);
            this.items.delete(item.urn);
        }
        return;
    }
    removeItem(item) {
        if (item instanceof cartItem) {
            if (!confirm("Are you sure you want to remove the selection from the cart?")) {
                return false;
            }
            let cart_item = {};
            cart_item.cart_item_id = item.id;

            let that = this;
            aj = new ajax("cart/removeitem", cart_item);
            // aj = new ajax("cart/removeitem",cart_item,"POST", "json", "text");
            aj.getResponse().then(response => {
                // HANDLE AJAX RESPONSE HERE.
                item.target.setAttribute("fill", item.baseColor);
                // item.target.setAttribute("opacity", 0.01);
                if (response.hasOwnProperty("status")) {
                    if (response.status > 0) {
                        if (response.hasOwnProperty("payload")) {
                            let payload = response.payload;
                            if (payload.hasOwnProperty("cart_items")) {
                                that.removeAllItems();
                                fp.loadOrderItemsToCart(payload);
                            }
                        }
                    }
                }
            });

        }
    }
    revokeItem(item) {
        if (item instanceof cartItem) {
            if (item.status === "RVD") {
                if (item.target) {
                    let tAtrrs = item.target.attributes;
                    if (tAtrrs.length > 0) {
                        item.target.setAttribute("fill", "var(--resvclr)");
                        item.target.setAttribute("opacity", 0.5);
                    }
                }
                item.element.classList.remove('cancelled');
                item.cancelled = false;
                this.refreshCount();
            }
        }
    }
    show() {
        const modal = new mdb.Modal(this.container);
        modal.show();
    }
    appendItemControls(item) {
        if (item.element instanceof HTMLLIElement) {
            let crtlElem = document.createElement("div");
            crtlElem.classList.add("item-cntrl");

            let crtlDelElem = document.createElement("div");
            crtlDelElem.setAttribute("title", "Remove");
            crtlDelElem.classList.add("item-cntrl-del");
            crtlDelElem.innerHTML = "<div><i class='fas fa-lg fa-circle-xmark'></i></div>";
            crtlElem.appendChild(crtlDelElem);

            item.element.appendChild(crtlElem);

            crtlDelElem.addEventListener("click", (event) => this.itemRemoveHandler(this, item, event));
        }
    }
    checkout() {
        if (this.items.size <= 0) {
            alert("Please add stands to cart");
            return false;
        }
        this.getCheckoutForm();

    }
    getCheckoutForm() {

        let chkOutForm = document.createElement("form");
        chkOutForm.name = "frm_checkout";
        chkOutForm.action = "cart/reserve";
        chkOutForm.method = "POST";

        let itemsField = document.createElement("textarea");
        itemsField.name = "cart_items";
        // itemsField.innerHTML = JSON.stringify(this.getCartItemsForCheckout());
        chkOutForm.appendChild(itemsField);

        let actionField = document.createElement("input");
        actionField.name = "fld_action";
        actionField.type = "hidden";
        actionField.value = "checkout";
        chkOutForm.appendChild(actionField);

        document.body.appendChild(chkOutForm);
        chkOutForm.submit();


    }
    getCartItemsForCheckout() {
        let cItems = [];
        for (const [key, value] of this.items) {
            let item = value;
            let cItem = {
                order_item_id: item.id,
                additional_info: { "stand_number": item.name },
                description: rebookGlobals.productName,
                discount: null,
                item_type: (item.status === "RVD") ? "REBOOK" : "OPTION",
                order_id: rebookGlobals.orderId,
                order_item_id: item.id,
                product_id: rebookGlobals.productId,
                quantity: item.quantity,
                stand_ref_id: item.urn,
                status: (item.status === "RVD" && item.cancelled) ? "CANCELLED" : "PENDING",
                subtotal: parseFloat(parseFloat((item.rate * item.quantity)).toFixed(2)),
                tax: null,
                total: parseFloat(parseFloat((item.rate * item.quantity)).toFixed(2)),
                unit_price: item.rate,
            }
            cItems.push(cItem);
        }
        return cItems;
    }
    itemRemoveHandler(instance, item, event) {
        instance.removeItem(item);
        event.preventDefault();
    }
    bindEvents() {
        if (this.barElem) {
            this.barElem.addEventListener("click", this.barClick);
        }
        if (this.bookButton) {
            this.bookButton.addEventListener("click", (event) => this.bookClick(this, event));
        }
        if (this.declineReservationElem && this.declineReservationCallBack instanceof Function) {
            this.declineReservationElem.addEventListener("click", (event) => this.declineReservationCallBack(event));
        }
    }
    bookClick(instance, event) {
        if (this.items.size > 0) {
            this.bookButton.setAttribute('disabled', '');
        }

        instance.checkout();
    }
    refreshCount() {
        let standCount = 0;
        let standTotals = 0.00;
        let msg = "<p>Please click on a preferred stand on the floor plan to select your stand package and add to cart</p>";
        for (const [key, value] of this.items) {
            standTotals += parseFloat(value.rate) * parseFloat(value.quantity);
            standCount++;
        }
        if (this.msgElem) {
            if (standCount <= 0) {
                this.msgElem.classList.remove('hide');
                this.msgElem.classList.add('show');
                this.msgElem.innerHTML = msg;
            }
            if (standCount > 0) {
                this.msgElem.classList.remove('show');
                this.msgElem.classList.add('hide');
            }
        }
        if (this.post_cart_callback instanceof Function) {
            let cart_summary = {};
            cart_summary.counts = standCount;
            cart_summary.totals = standTotals;
            this.post_cart_callback(cart_summary);
        } else {
            // console.log("cnf");
        }
    }
}

const cartItem = class {
    urn;
    id;
    type;
    status;
    name;
    productId;
    productName;
    rate;
    quantity;
    sequence;
    specs;
    cancelled = false;
    element;
    target;

    constructor() { }
}

