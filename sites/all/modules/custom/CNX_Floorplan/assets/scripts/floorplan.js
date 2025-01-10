let fp;
let srch;
let aj;
const floorplan = class {
    fpCotn;
    fpDiv;
    bgDiv;
    exDiv;
    evDiv;
    fgDiv;
    bgImg;
    exSvg;
    evSvg;
    fgImg;
    standsGnode;
    evStandGnode;
    declineReasonsModalElem;
    declineReasonsFrmElem;
    declineReasonsSubmitElem;
    declineReasonsExitElem;
    onMove = false;

    constructor(fpCotn) {
        this.fpCotn = fpCotn;
        this.setElements();
        this.bindEvents();
        this.fitFloorPlan();
        // this.standPointsAll = new Array();
    }

    setElements() {
        this.fpDiv = document.getElementById("floorplan");
        this.bgDiv = document.getElementById("fpbg");
        this.exDiv = document.getElementById("fpex");
        this.fgDiv = document.getElementById("fpfg");
        this.evDiv = document.getElementById("fpev");
        this.declineReasonsModalElem = document.querySelector("#decline_reasons_modal");
        this.declineReasonsFrmElem = document.querySelector("#decline_reasons_modal > #decline_reasons_container > #decline_reasons_content > #frm_decline_reasons");
        this.declineReasonsSubmitElem = document.querySelector("#decline_reasons_submit");
        this.declineReasonsExitElem = document.querySelector("#decline_reasons_modal .modal.close div");
        if (this.bgDiv instanceof HTMLDivElement) {
            let imgElems = this.bgDiv.getElementsByTagName("img");
            if (imgElems.length > 0) {
                this.bgImg = imgElems[0];
            }
            imgElems = null;
        }

        if (this.fgDiv instanceof HTMLDivElement) {
            let imgElems = this.fgDiv.getElementsByTagName("img");
            if (imgElems.length > 0) {
                this.fgImg = imgElems[0];
            }
            imgElems = null;
        }

        if (this.exDiv instanceof HTMLDivElement) {
            let svgElems = this.exDiv.getElementsByTagName("svg");

            if (svgElems.length > 0) {
                this.exSvg = svgElems[0];
            }
            svgElems = null;
        }

        if (this.evDiv instanceof HTMLDivElement) {
            let svgElems = this.evDiv.getElementsByTagName("svg");

            if (svgElems.length > 0) {
                this.evSvg = svgElems[0];
            }
            svgElems = null;
        }

        if (this.exSvg instanceof SVGElement) {
            if (this.exSvg.tagName.toLowerCase() === "svg") {
                this.standsGnode = this.exSvg.getElementById("STAND_SHAPE");
            }
        }

        if (this.evSvg instanceof SVGElement) {
            if (this.evSvg.tagName.toLowerCase() === "svg") {
                this.evStandGnode = this.evSvg.getElementById("standEvent");
            }
        }

    }

    // buildAndBindStandClickEvent() {
    //     if (this.fpDiv instanceof HTMLDivElement) {
    //         this.evDiv = document.createElement("div");
    //         this.evDiv.id = "fpev";

    //         this.evSvg = document.createElement("svg");
    //         for (let i = 0; i < this.exSvg.attributes.length; i++) {
    //             this.evSvg.setAttribute(this.exSvg.attributes[i].name, this.exSvg.attributes[i].value)
    //         }

    //         let evStandPnode = this.standsGnode.parentNode.cloneNode();
    //         evStandPnode.style.height = "inherit";
    //         evStandPnode.style.width = "inherit";
    //         this.evStandGnode = this.standsGnode.cloneNode(true);
    //         this.evStandGnode.style.height = "inherit";
    //         this.evStandGnode.style.width = "inherit";
    //         evStandPnode.appendChild(this.evStandGnode);
    //         this.evSvg.appendChild(evStandPnode);
    //         this.evDiv.appendChild(this.evSvg);
    //         this.fpDiv.appendChild(this.evDiv);
    //     }

    // }

    fitFloorPlan() {
        fpCont = document.getElementById("fp_container");
        if(fpCont){
            let fpmeasures = fpCont.getBoundingClientRect();
            if(fpmeasures.left > 0){
                this.fpCotn.style.width = (window.innerWidth-(fpmeasures.left*2)) + "px";
            }else{
                this.fpCotn.style.width = window.innerWidth + "px";
            }
            this.fpCotn.style.height = window.innerHeight + "px";
        }

        let contractAcceptedDiv = document.getElementById("contract_accepted_floorplan");
        if(contractAcceptedDiv){
            var positionInfo = contractAcceptedDiv.getBoundingClientRect();
            var height = positionInfo.height;
            var width = positionInfo.width;
            this.fpCotn.style.width = width + "px";
            this.fpCotn.style.height = height + "px";
        }
    }

    bindEvents() {
        if (this.evStandGnode instanceof SVGElement) {
            console.log("inside");
            this.evStandGnode.addEventListener("click", (event) =>
                this.standOnClick(this, event), { cancelable: true, bubbles: false }
            );
            this.evStandGnode.addEventListener("mousedown", (event) =>
                this.standMouseDown(this, event), { cancelable: true, bubbles: false }
            );
            this.evStandGnode.addEventListener("mousemove", (event) =>
                this.standMouseMove(this, event), { cancelable: true, bubbles: false }
            );
        }

        if (this.declineReasonsExitElem) {
            this.declineReasonsExitElem.addEventListener("click", (event) => {
                if (event.currentTarget instanceof HTMLElement) {
                    fp.declineReasonsModalElem.classList.remove("show");
                }
            })
        }
        if (this.declineReasonsSubmitElem) {
            this.declineReasonsSubmitElem.addEventListener("click", (event) => {
                if (event.currentTarget instanceof HTMLInputElement) {
                    fp.submitDeclineReasons();
                }
            })
        }
    }

    standMouseDown(event) {
        this.onMove = false;
    }

    standMouseMove(event) {
        this.onMove = true;
    }

    standOnClick(instance, event) {
        console.log("clicked ....");
        if (this.onMove) return;
        // if (!this.addToCart(event.target)) this.removeFromCart(event.target);
        console.log(event.target.getAttribute("sts"));
        let pop = new popup(event.target);
        //event.preventDefault()
    }

    removeFromCart(elem) {
        if (
            elem instanceof SVGPolylineElement &&
            elem.parentNode instanceof SVGElement &&
            elem.parentNode.id === "standEvent"
        ) {
            let key = elem.getAttribute("id");
            let sts = elem.getAttribute("sts").toLowerCase().trim();
            if (sts === "opn" && fpCart.items.has(key)) {
                fpCart.removeItem(key);
            }
        }
    }

    addToCart(elem, product) {
        if (!(fpCart instanceof cart)) return false;
        if (
            elem instanceof SVGPolylineElement &&
            elem.parentNode instanceof SVGElement &&
            elem.parentNode.id === "standEvent"
        ) {
            let key = elem.getAttribute("id");
            if (fpCart.items.has(key)) {
                alert("Item is already present in cart!");
                return false;
            }
            let type = elem.getAttribute("type").toLowerCase().trim();
            let sts = elem.getAttribute("sts").toLowerCase().trim();
            if (type === "std" && sts === "ava") {
                let stand_info = {};
                stand_info.stand_no = elem.getAttribute("no");
                stand_info.stand_height = elem.getAttribute("ht");
                stand_info.stand_opensides = elem.getAttribute("os");
                stand_info.product_ref = product.ref;

                let cart_item = {};
                cart_item.stand_ref_id = key;
                cart_item.product_key = product.key;
                cart_item.product_name = product.name;
                cart_item.description = product.name;
                cart_item.additional_info = stand_info;
                cart_item.quantity = elem.getAttribute("ar");
                cart_item.rate = product.rate;

                aj = new ajax("cart/add",cart_item);
                // aj = new ajax("cart/add",cart_item,"POST", "json", "text");
                let that = this;
                aj.getResponse().then(response => {
                    // Handle ajax response here.
                    console.log(response);
                    if (response.hasOwnProperty("status")) {
                        if(response.status > 0){
                            if (response.hasOwnProperty("payload")) {
                                let payload = response.payload;
                                if (payload.hasOwnProperty("cart_items")) {
                                    fpCart.removeAllItems();
                                    that.loadOrderItemsToCart(payload);
                                    alert("Stand added to cart successfully.");
                                }
                            }

                        }else{
                            alert("Error encountered in adding to cart.");
                        }
                    }else{
                        alert("Error encountered in adding to cart.");
                    }
                });

                return true;
            }
        }
        alert("Clicked stand could not be added!");
        return false;
    }

    getItemElem(cartItem) {
        let itemElem = document.createElement("li");
        itemElem.id = cartItem.urn;
        itemElem.classList.add("cart-item");
        itemElem.classList.add((cartItem.status.toLowerCase() === "rvd") ? "reserved" : "optioned");

        let itemGroupElem = document.createElement("ul");


        let nameElem = document.createElement("li");
        nameElem.classList.add("item-name");
        nameElem.innerHTML = cartItem.name;
        itemGroupElem.appendChild(nameElem);

        let pckgElem = document.createElement("li");
        let pckgHtml = "<div class='item-pckg'>" + cartItem.productName + "</div>";
        pckgHtml += "<div class='item-qty'>";
        pckgHtml += "<span>" + cartItem.quantity + "sqm</span>";
        pckgHtml += "<span class='item-rate'> @&pound;" + cartItem.rate + " per sqm</span>";
        pckgHtml += "</div>";
        pckgElem.innerHTML = pckgHtml;

        itemGroupElem.appendChild(pckgElem);

        // let rateElem = document.createElement("li");
        // rateElem.classList.add("item-rate");
        // rateElem.innerHTML = "&pound;" + cartItem.rate;
        // itemGroupElem.appendChild(rateElem);

        // let qtyElem = document.createElement("li");
        // qtyElem.classList.add("item-qty");
        // qtyElem.innerHTML = cartItem.quantity + " sqm";
        // itemGroupElem.appendChild(qtyElem);

        let rtotElem = document.createElement("div");
        rtotElem.classList.add("item-rowtot");
        rtotElem.innerHTML = "&pound;" + parseFloat(parseFloat((cartItem.rate * cartItem.quantity)).toFixed(2)).toLocaleString("en-GB", { useGrouping: true, }
        );
        itemElem.appendChild(itemGroupElem);
        itemElem.appendChild(rtotElem);
        return itemElem;
    }

    declineReservation(event) {
        let chkOutForm = document.createElement("form");
        chkOutForm.name = "frm_decline_reason";
        chkOutForm.method = "POST";

        let actionField = document.createElement("input");
        actionField.name = "fld_action";
        actionField.type = "hidden";
        actionField.value = "decline_request";
        chkOutForm.appendChild(actionField);

        document.body.appendChild(chkOutForm);
        chkOutForm.submit();
    }

    static submitDeclineReasons(declineReasonsFrm = null) {
        if (!declineReasonsFrm) {
            declineReasonsFrm = this.declineReasonsFrmElem;
        }
        if (declineReasonsFrm
            instanceof HTMLFormElement) {
            let reasonSelectValue = '';
            let reasonTextValue = '';
            let isValid = true;
            let declineFrmElements = declineReasonsFrm.elements;
            for (let i = 0; i < declineFrmElements.length; i++) {
                let elem = declineFrmElements[i];
                if (elem.type != 'hidden' && elem.type != 'button') {
                    if (elem.name === 'decline_reasons_options') reasonSelectValue = elem.value;
                    if (elem.name === 'decline_reasons_text') reasonTextValue = elem.value;
                }
            }
            if (reasonSelectValue.toLowerCase().trim() === 'other' && reasonTextValue.trim() === '') {
                alert("Please type in your reason in the text box");
                isValid = false;
            }
            if (reasonSelectValue.toLowerCase().trim() === '' && reasonTextValue.trim() === '') {
                alert("Please type in your reason in the text box or select one of the reasons pre-listed");
                isValid = false;
            }

            if (isValid) {
                declineReasonsFrm.submit();
            }
        }

    }

    loadOrderItemsToCart(cartobj) {
        // rebookGlobals.zones = [];
        // console.log(cartobj);
        if (cartobj.cart_items instanceof Array) {
            let cartItems = cartobj.cart_items;
            if (cartItems.length > 0) {
                let selectedShapes = [];
                for (let i = 0; i < cartItems.length; i++) {
                    let item = cartItems[i];
                    // if (!rebookGlobals.orderId) {
                    //     rebookGlobals.orderId = item.order_id;
                    // }
                    // let order_item_id = item.order_item_id;
                    // let standId = item.stand_ref_id;
                    // let status = item.status;
                    // let quantity = item.quantity;
                    // let rate = item.unit_price;
                    // let itemType = item.item_type;
                    // let itemProductId = item.product_id;
                    // let itemProductName = item.description;
                    // if (!rebookGlobals.productId && !rebookGlobals.productName && !rebookGlobals.rate) {
                    //     if (itemType.toLowerCase().trim() == 'rebook') {
                    //         rebookGlobals.productId = itemProductId;
                    //         rebookGlobals.productName = itemProductName;
                    //         rebookGlobals.rate = rate;
                    //     }
                    // }
                    let standNum = '';
                    if (item.additional_info) {
                        standNum = item.additional_info.stand_number;
                    }
                    // if ((itemType.toLowerCase().trim() == 'rebook' || itemType.toLowerCase().trim() == 'option') && status.toLowerCase().trim() == 'pending') {
                        let cItem = new cartItem();
                        cItem.id = item.cart_item_id;
                        cItem.urn = item.stand_ref_id;
                        cItem.type = item.item_group_key;
                        cItem.status = item.status;
                        cItem.name = item.additional_info.stand_no;
                        cItem.productId = item.product_key;
                        cItem.productName = item.product_name;
                        cItem.rate = item.rate;
                        cItem.quantity = item.quantity;
                        cItem.target = document.querySelector("#fp_container > #floorplan > #fpev > svg g#standEvent > *[id='" + item.stand_ref_id.trim() + "']");
                        cItem.element = this.getItemElem(cItem);
                        cItem.cancelled = false;
                        fpCart.addItem(cItem);
                        if (cItem.target instanceof SVGElement) {
                            let ops = cItem.target.getAttribute("ops");
                            // if (ops != null && ops === 'SPC') {
                            //     rebookGlobals.isspaceonly = true;
                            // }
                            let zone = cItem.target.getAttribute("zn");
                            // if (!rebookGlobals.zones.includes(zone)) {
                            //     rebookGlobals.zones[i] = zone;
                            // }
                            // cItem.target.setAttribute("sts", cItem.status);
                            let standClr = "var(--cartclr)";
                            // if (cItem.status === "RVD") standClr = "var(--resvclr)";
                            // if (cItem.status === "OPN") standClr = "var(--optnclr)";
                            cItem.target.setAttribute("fill", standClr);
                            cItem.target.setAttribute("opacity", 0.5);
                            selectedShapes.push(cItem.target);
                        }
                    // }
                }
                if (selectedShapes.length > 0) {
                    let z = new zoomable(this.fpDiv);
                    z.autoZoom(selectedShapes);
                }
            }
        }
    }

    loadStandItemsOnFloorplan() {
        // rebookGlobals.zones = [];
        if (cartItems instanceof Array) {
            if (cartItems.length > 0) {
                let selectedShapes = [];
                for (let i = 0; i < cartItems.length; i++) {

                    let item = cartItems[i];
                    let standId = item.stand_ref_id;
                    let status = item.status;
                    let itemType = item.item_type;
                    if ((itemType.toLowerCase().trim() == 'rebook' || itemType.toLowerCase().trim() == 'option') && status.toLowerCase().trim() != 'cancelled') {
                        let cItem = new cartItem();
                        cItem.status = (itemType.toLowerCase().trim() == 'rebook') ? "RVD" : "OPN";
                        cItem.target = document.querySelector("#contract_accepted_floorplan > #floorplan > #fpev > svg g#standEvent > *[id='" + standId.trim() + "']");
                        if (cItem.target instanceof SVGElement) {
                            cItem.target.setAttribute("sts", cItem.status);
                            let standClr = "#FFFFFF";
                            if (cItem.status === "RVD") standClr = "var(--resvclr)";
                            if (cItem.status === "OPN") standClr = "var(--optnclr)";
                            cItem.target.setAttribute("fill", standClr);
                            cItem.target.setAttribute("opacity", 0.5);
                            selectedShapes.push(cItem.target);
                        }
                    }
                }
                if (selectedShapes.length > 0) {
                    let z = new zoomable(this.fpDiv);
                    z.autoZoomScaleLimit = 0.5;
                    z.autoZoom(selectedShapes);
                }
            }

        }

    }

    // fitAvialableZones() {
    //     if (rebookGlobals.zones.length > 0) {
    //         let zoomToStands = []
    //         for (let i = 0; i < rebookGlobals.zones.length; i++) {
    //             let zone = rebookGlobals.zones[i];
    //             let stands = document.querySelectorAll("#fp_container > #floorplan > #fpev > svg g#standEvent > *[zn='" + zone + "']");
    //             //zoomToStands.splice(zoomToStands.length - 1, 0, Array.from(stands));
    //             zoomToStands.push(stands);
    //         }
    //         if (zoomToStands.length > 0) {
    //             let z = new zoomable(this.fpDiv);
    //             z.autoZoomScaleLimit = 0.5;
    //             z.autoZoom(zoomToStands, "rgba(230, 0, 213, 0.8)");
    //         }



    //     }
    // }

    // getStandCoords() {
    //     return this.standPointsAll;
    // }


};



function toggleHelp(show = true, event) {
    helpCont = document.getElementById("help_outer");
    if (helpCont) {
        if (!show) {
            helpCont.classList.remove('show');
            helpCont.classList.add('hide');
        }
        else {
            helpCont.classList.remove('hide');
            helpCont.classList.add('show');
        }
    }
}


// function setContractedStands(order_items) {

//     for (let i = 0; i <= order_items.length - 1; i++) {
//         if (order_items[i].item_type == "REBOOK" || order_items[i].item_type == "OPTION") {
//             let stand = document.querySelector("#contract_accepted_floorplan > #fpex > svg g#STANDS > *[id='" + order_items[i].stand_ref_id.trim() + "']");
//             let colour = (order_items[i].item_type == "REBOOK") ? "var(--resvclr)" : "var(--optnclr)";
//             if (stand) {
//                 stand.setAttribute("fill", colour);
//             }
//         }
//     }
// }

window.addEventListener("load", (event) => {


    helpCont = document.getElementById("help_outer");
    if (helpCont) {
        helpContBtn = document.getElementById("help_continue_btn");
        if (helpContBtn) {
            helpContBtn.addEventListener("click", (event) => toggleHelp(false, event), { passive: true })
        }
        helpIcon = document.getElementById("help_icon");
        if (helpIcon) {
            helpIcon.addEventListener("click", (event) => toggleHelp(true, event), { passive: true })
        }
    }

    fpCont = document.getElementById("fp_container");
    if (fpCont) {
        fp = new floorplan(fpCont);
        let cartElem = document.getElementById("cart");
        if (cartElem) {
            fpCart = new cart(cartElem);
            fpCart.declineReservationCallBack = fp.declineReservation;
            fpCart.bindEvents();
            window.addEventListener("resize", (event) => fpCart.resize(fpCart, event));
            fp.loadOrderItemsToCart(cartobj);
            //console.log("FP set");
        }
    }
    if (fpCont || document.querySelector("#contract_accepted > #contract_accepted_floorplan")) {
        fpfitIcon = document.getElementById("fpfit_icon");
        if (fpfitIcon) {
            fpfitIcon.addEventListener("click", (event) => {
                m = new DOMMatrix()
                fp.fpDiv.style.transform = m.toString();
            })
        }
    }
    let declineReasonsSubmitElem = document.querySelector("#decline_reasons_submit");
    let declineReasonsFrmElem = document.querySelector("#frm_decline_reasons");
    if (declineReasonsSubmitElem && declineReasonsFrmElem) {
        declineReasonsSubmitElem.addEventListener("click", (event) => {
            if (event.currentTarget instanceof HTMLInputElement) {
                floorplan.submitDeclineReasons(declineReasonsFrmElem);
            }
        })
    }

    let zoomableElems = document.getElementsByClassName("zoomable");
    if (zoomableElems.length > 0) {
        for (let i = 0; i < zoomableElems.length; i++) {
            let zoomableElem = zoomableElems[i];
            let clsZoomable = new zoomable(zoomableElem);
            clsZoomable.bindEvents();
            //console.log("ZOOM set");
        }
    }

    let contractAcceptedDiv = document.getElementById("contract_accepted_floorplan");
    if (contractAcceptedDiv) {
        fp = new floorplan(contractAcceptedDiv);
        fp.loadStandItemsOnFloorplan();
    }

    let search_cont = document.getElementById("search_container");
    if(search_cont){
        srch = new search();
    }
});

window.addEventListener("resize", (event) => {
    if (fp instanceof floorplan) fp.fitFloorPlan();
});
