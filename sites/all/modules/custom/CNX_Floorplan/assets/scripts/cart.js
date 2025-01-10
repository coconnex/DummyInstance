let fpCart;
const cart = class {
    target;
    items = new Map();
    cartElem;
    itemsElem;
    contentElem;
    footerElem;
    declineReservationElem;
    declineReservationCallBack;
    msgElem;
    statusElem;
    statusMsgElem;
    rvdElem;
    opnElem;
    rvdCntElem;
    opnCntElem;
    bookButton;
    startPosition = "TR";
    maxOptions = 25;
    rvdCnt = 0;
    opnCnt = 0;
    cartCnt = 0;
    constructor(target) {
        this.target = target;
        this.cartElem = document.querySelector("div#cart");
        this.barElem = document.querySelector("div#cart > div#bar");
        this.bookButton = document.querySelector("div#cart > div#bar > input#btn_book");
        this.itemsElem = document.querySelector("div#cart > div#content > ul#cart_items");
        this.msgElem = document.querySelector("div#cart > div#content > div#cart_msg");
        this.contentElem = document.querySelector("div#cart > div#content");
        this.footerElem = document.querySelector("div#cart > div#footer");
        this.declineReservationElem = document.querySelector("div#cart > div#footer > div#decline_reservation");
        this.statusElem = document.querySelector("div#cart > div#bar > #status");
        this.statusMsgElem = document.querySelector("div#cart > div#bar > #status > #status_msg");;
        this.rvdElem = document.querySelector("div#cart > div#bar > #status > #rvd");
        this.opnElem = document.querySelector("div#cart > div#bar > #status > #opn");
        this.rvdCntElem = document.querySelector("div#cart > div#bar > #status > #rvd > #rvd_cnt");
        this.opnCntElem = document.querySelector("div#cart > div#bar > #status > #opn > #opn_cnt");

        this.setStartPosition();
        this.refreshCount();
    }
    setStartPosition() {
        this.cartElem.style.display = "unset";
        this.setAllignment(this.target, this.startPosition);
    }
    setAllignment(target, pos = "TR", bounds = { "l": 0, "t": this.cartElem.parentNode.getBoundingClientRect().top, "w": window.innerWidth, "h": window.innerHeight }) {
        let tl = utils.getAllignment(target, pos, bounds);
        target.style.left = tl.left;
        target.style.top = tl.top;
    }
    addItem(item) {
        if (this.opnCnt >= this.maxOptions) {
            alert("This stand option cannot be added. Maximum of " + this.maxOptions + " options is allowed.");
            return false;
        }
        if (item instanceof cartItem) {
            this.items.set(item.urn, item);
            this.appendItemControls(item);
            this.itemsElem.appendChild(item.element);
            this.refreshCount();
            this.expand();
        }
    }
    removeAllItems(){
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
            aj = new ajax("cart/removeitem",cart_item);
            // aj = new ajax("cart/removeitem",cart_item,"POST", "json", "text");
            aj.getResponse().then(response => {
                // Handle ajax response here.
                item.target.setAttribute("fill", "#FFFFFF");
                item.target.setAttribute("opacity", 0.01);
                if (response.hasOwnProperty("status")) {
                    if(response.status > 0){
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
            // if (item.status === "RVD") {
            //     if (!confirm("If you cancel this reservation we will be unable to guarantee your stand.\nAre you sure you want to cancel this option?")) {
            //         return false;
            //     }
            // }
            // if (item.target) {
            //     let tAtrrs = item.target.attributes;
            //     if (tAtrrs.length > 0) {
            //         let clr = (item.status === "RVD") ? "#000000" : "#FFFFFF";
            //         item.element.setAttribute("fill", clr);
            //         if (item.status === "OPN") {
            //             item.target.setAttribute("sts", "AVA");
            //             item.target.setAttribute("fill", "#FFFFFF");
            //         }
            //         item.target.setAttribute("opacity", 0.01);
            //     }
            // }
            // this.itemsElem.removeChild(item.element);
            // this.items.delete(item.urn);
            // if (item.status === "RVD") {
            //     item.element.classList.add('cancelled');
            //     item.cancelled = true;
            // }
            this.refreshCount();
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
    shiftItemUp(item) {
        alert("shift up comming soon");
    }
    shiftItemDn(item) {
        alert("shift down comming soon");
    }
    toggleVisibility() {
        let cList = this.target.classList;
        let fndVisCls = false;
        for (let i = 0; i < cList.length; i++) {
            if (cList[i] === "show") {
                cList.remove("show");
                cList.add("hide");
                fndVisCls = true;
                break;
            }
            if (cList[i] === "hide") {
                cList.remove("hide");
                cList.add("show");
                fndVisCls = true;
                break;
            }
        }
        if (!fndVisCls) cList.add("show");
    }
    expand() {
        this.target.classList.add("show");
    }
    collapse() {
        this.target.classList.add("hide");
    }
    bestFit() {
        if (window.innerWidth < this.target.offsetWidth) {
            this.target.style.width = window.innerWidth + "px";
            this.setAllignment(this.target, "TR");
        }
    }
    appendItemControls(item) {
        if (item.element instanceof HTMLLIElement) {
            let crtlElem = document.createElement("div");
            crtlElem.classList.add("item-cntrl");

            let crtlUpElem = document.createElement("div");
            crtlUpElem.classList.add("item-cntrl-up");
            crtlUpElem.innerHTML = "<div>&uarr;</div>";
            crtlElem.appendChild(crtlUpElem);

            let crtlDnElem = document.createElement("div");
            crtlDnElem.classList.add("item-cntrl-dn");
            crtlDnElem.innerHTML = "<div>&darr;</div>";
            crtlElem.appendChild(crtlDnElem);

            let crtlDelElem = document.createElement("div");
            crtlDelElem.setAttribute("title", "Remove");
            crtlDelElem.classList.add("item-cntrl-del");
            crtlDelElem.innerHTML = "<div>&#9747;</div>";
            crtlElem.appendChild(crtlDelElem);

            let crtlRvkElem = document.createElement("div");
            crtlRvkElem.setAttribute("title", "Revoke");
            crtlRvkElem.classList.add("item-cntrl-rvk");
            crtlRvkElem.innerHTML = "<div>&#8634;</div>";
            crtlElem.appendChild(crtlRvkElem);

            item.element.appendChild(crtlElem);

            crtlDelElem.addEventListener("click", (event) => this.itemRemoveHandler(this, item, event));
            crtlUpElem.addEventListener("click", (event) => this.itemShiftUpHandler(this, item, event))
            crtlDnElem.addEventListener("click", (event) => this.itemShiftDnHandler(this, item, event));
            crtlRvkElem.addEventListener("click", (event) => this.itemRevokeHandler(this, item, event));
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
        console.log(chkOutForm);
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
    itemShiftUpHandler(instance, item, event) {
        instance.shiftItemUp(item);
        event.preventDefault();
    }
    itemShiftDnHandler(instance, item, event) {
        instance.shiftItemDn(item);
        event.preventDefault();
    }
    itemRevokeHandler(instance, item, event) {
        instance.revokeItem(item);
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
    resize(instance, event) {
        instance.bestFit();
    }
    barClick() {
        fpCart.toggleVisibility();
    }
    bookClick(instance, event) {
        console.log("book clicked");
        instance.checkout();
    }
    refreshCount() {
        // let opnCnt = 0;
        // let rvdCnt = 0;
        let standCount = 0;
        let msg = "<p>Please click on a preferred stand on the floor plan to select your stand package and add to cart</p>";
        for (const [key, value] of this.items) {
            // if (value.status === "OPN") opnCnt += 1;
            // if (value.status === "RVD" && value.cancelled === false) {
            //     rvdCnt += 1;
            //     msg = "<p>We reserved stand " + value.name + " for the upcoming Top Drawer Spring 2025 show, which is the same stand you selected for the last show.</p>" + msg;
            // }
            standCount++;
        }
        // this.rvdElem.style.display = (rvdCnt <= 0) ? 'none' : '';
        // this.opnElem.style.display = (opnCnt <= 0) ? 'none' : '';
        // this.statusMsgElem.style.display = (rvdCnt <= 0 && opnCnt <= 0) ? '' : 'none';

        // this.rvdCntElem.innerText = rvdCnt;
        // this.opnCntElem.innerText = opnCnt;

        // this.rvdCnt = rvdCnt;
        // this.opnCnt = opnCnt;
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

