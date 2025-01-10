const popup = class {
    target;
    targetExpo;
    standId;
    popblock;
    popHeader;
    radiolist;
    rawdata;
    mode;
    packages;
    selectFill = "#bff8f9";
    selectCssPatt = /:root[\s]*\{(.*)--selectedclr:(.*)[;|\}]/g;
    summaryDiv;
    sumMsg;
    sumDtLink;
    link;
    description;
    showPackageDetails;


    constructor(standelem, mode, packages) {
        this.target = standelem;
        this.rawdata = products;
        this.packages = packages;
        // console.log(this.packages);
        this.mode = mode;

        this.standId = this.target.getAttribute("id");
        this.popblock = document.getElementById("popup_tooltip");
        if (this.popblock instanceof HTMLDivElement) {
            if (this.cssStyleExists(this.selectCssPatt)) this.selectFill = "var(--selectedclr)";
            this.clearAllSelected();
            this.setSelected();
            this.popblock.style.transform = "matrix(1, 0, 0, 1, 0, 0)";
            this.popHeader = null;
            this.reset();
            this.buildBody();

            if (this.mode == 'PRIME') {
                this.buildPrimeStandDiv();
            }
            if (this.mode == 'CART') {
                this.buildAddToCartDiv();
            }
            this.rePosition()
        }
    }

    setSelected() {
        if (this.standId) {
            this.targetExpo = document.querySelector("div#floorplan div#fpex g#STAND_SHAPE *[id='" + this.standId + "']");
        }
        if (this.targetExpo) {
            if (this.targetExpo.hasAttribute("fill")) {
                let standfill = this.targetExpo.getAttribute("fill");
                this.targetExpo.setAttribute("prevfill", standfill);
            }
            this.targetExpo.setAttribute("fill", this.selectFill);
            this.targetExpo.setAttribute("selected", true);
        }
    }

    clearSelected(selTarget = null) {
        if (selTarget === null) selTarget = this.targetExpo;
        if (selTarget.hasAttribute("selected")) selTarget.removeAttribute("selected");
        if (selTarget.hasAttribute("prevfill")) {
            let prevfill = selTarget.getAttribute("prevfill");
            selTarget.setAttribute("fill", prevfill);
            selTarget.removeAttribute("prevfill");
        }
    }

    clearAllSelected() {
        let selectedTargets = document.querySelectorAll("div#floorplan div#fpex g#STAND_SHAPE *[selected='true']");
        for (let i = 0; i < selectedTargets.length; i++) {
            let selTarget = selectedTargets[i];
            this.clearSelected(selTarget);
        }
    }

    setAlignment() {
        let tl = this.target.getBoundingClientRect();
        this.popblock.style.left = (tl.left + tl.width + 5) + "px";
        let scrollpos = document.documentElement.scrollTop || document.body.scrollTop;
        let topDistance = tl.top + scrollpos;
        this.popblock.style.top = topDistance + "px";
        this.popblock.style.display = "block";
        return;
    }
    rePosition() {
        if (popup_moveable) {
            popup_moveable.applyRestriction();
        }
    }
    buildWaitListDiv() {

        this.updateHeaderText();
        this.setAlignment();

        const subdiv2 = document.createElement("div");
        subdiv2.classList.add("divbutton");
        let that = this;

        const button = document.createElement("input");
        button.type = "button";
        button.value = "Add to Waiting List";
        button.name = "btnAdd";
        button.setAttribute("class", "btn bg-dark w-100 text-white mt-2");
        subdiv2.appendChild(button);

        button.addEventListener('click', function (e) {
            that.addToWaitlist(this, that.target);
        }
        );
        // this.popblock.style.height = "85px";
        this.popblock.appendChild(subdiv2);
    }
    buildAddToCartDiv() {
        // this.reset();
        this.updateHeaderText();
        this.setAlignment();

        let count = 0;
        const subdiv1 = document.createElement("div");
        subdiv1.classList.add("divselect");
        let that = this;

        let select = document.createElement("select");
        select.name = "cmbproduct";
        select.id = "cmbproduct";
        // Adding default option
        let option = document.createElement('option');
        option.setAttribute('value', '');
        option.appendChild(document.createTextNode('Select package'));
        select.appendChild(option);

        // Adding filtered stand package options
        for (let [key, obj] of this.packages) {
            // console.log(key);
            // console.log(obj);
            if (obj.package_type == "PROD") {
                let option = document.createElement('option');
                option.setAttribute('value', key);
                option.appendChild(document.createTextNode(obj.description + ' (' + obj.currency + ' ' + obj.unit_price + '/'));
                option.appendChild(document.createTextNode('\u33A1'));
                option.appendChild(document.createTextNode(')'));
                if(obj.short_key == 'SPC' && this.target.getAttribute("ar")< 24) {}
                else{
                    select.appendChild(option);
                }
            }
            count++;
        }
        subdiv1.appendChild(select);
        this.popblock.appendChild(subdiv1);
        select.addEventListener('change', (event) => {
            if (event.target.value) {
                this.setPackageSummary(event.target.value);
            } else {
                document.getElementById('summary_msg_div').innerHTML = 'Please select a package before adding the stand to the cart.';
                this.sumDtLink.innerHTML = '';
            }
        })


        //Adding summary div

        this.summaryDiv = document.createElement('div');
        this.summaryDiv.id = "summary_div";
        this.summaryDiv.classList.add('summary-div');
        this.sumMsg = document.createElement('div');
        this.sumMsg.id = "summary_msg_div";
        this.sumMsg.classList.add('summary-msg-div');
        this.sumMsg.innerHTML = 'Please select a package before adding the stand to the cart.';
        this.summaryDiv.appendChild(this.sumMsg);
        this.sumDtLink = document.createElement('div');
        this.sumDtLink.id = "summary_dt_link_div";
        this.sumDtLink.classList.add('summary-dt-link-div');
        this.summaryDiv.appendChild(this.sumDtLink);
        this.popblock.appendChild(this.summaryDiv);

        // Adding add to cart button
        if (count > 0) {
            const subdiv2 = document.createElement("div");
            subdiv2.classList.add("divbutton");

            const button = document.createElement("input");
            button.type = "button";
            button.value = "Add to cart";
            button.name = "btnAdd";
            button.setAttribute("class", "btn bg-dark w-100 text-white mt-2");
            subdiv2.appendChild(button);

            button.addEventListener('click', function (e) {
                that.addToCart(this, that.target);
            }
            );
            // this.popblock.style.height = "120px";
            this.popblock.appendChild(subdiv2);

        }
        return;
    }
    setPackageSummary(selectedPackage) {
        let selectedPackageInfo = this.packages.get(selectedPackage);
        if (selectedPackageInfo.summary) {
            this.sumMsg.innerHTML = selectedPackageInfo.summary;
        }else{
            this.sumMsg.innerHTML = selectedPackageInfo.description+'<div class="unit-price">'+selectedPackageInfo.currency+ ' '+selectedPackageInfo.unit_price+'/m<sup>2</sup></div>';

        }
        if (selectedPackageInfo.details_link) {
            this.link = selectedPackageInfo.details_link;
            this.description = selectedPackageInfo.description;

            this.sumDtLink.removeEventListener('click', this.showPackageDetails);
            this.sumDtLink.innerText = "View Details";

            this.showPackageDetails = (event) => {
                packageInfo.show(this.link, this.description);
            };
            this.sumDtLink.addEventListener('click', this.showPackageDetails);
        }else{
            this.sumDtLink.innerText = '';
        }

    }
    addToCart(btn, standelem) {
        btn.setAttribute('disabled', '');
        const selected = document.getElementById("cmbproduct");
        const productselected = selected.value;
        if (productselected === '') {
            btn.removeAttribute('disabled');
            alert("Please select your stand package to continue");

            return;
        } else {
            if (stands_count == 0) {

                let jsonproduct = {};
                for (let [key, obj] of this.packages) {
                    // console.log(obj);
                    if (key == productselected) {
                        jsonproduct.key = obj.short_key;
                        jsonproduct.name = obj.description;
                        jsonproduct.rate = obj.unit_price;
                        jsonproduct.ref = obj.urn;
                    }
                }
                // console.log(jsonproduct);
                this.clearSelected();
                fp.addToCart(standelem, jsonproduct);
                this.hide();
            } else {
                btn.removeAttribute('disabled');
                alert("You already have stand(s) reserved or booked.\nYou can book a single stand.");
                this.clearSelected();
                this.hide();
                return;
            }
        }
        return;
    }

    addToWaitlist(btn, standelem) {
        btn.setAttribute('disabled', '');
        let waitlist_item = {};
        for (let key in this.rawdata) {
            let obj = this.rawdata[key];
            // TODO: Use a for loop to get the default package whcih then needs to be populated for passing. Add for loop in the below line with the if condition
            // if(obj.default == 1)
            waitlist_item.product_key = key;
            waitlist_item.product_name = obj.description;
            waitlist_item.description = obj.description;
            waitlist_item.stand_ref = standelem.getAttribute("id");
            waitlist_item.stand_no = standelem.getAttribute("no");
            waitlist_item.quantity = standelem.getAttribute("ar");
            waitlist_item.rate = obj.unit_price;
            waitlist_item.total = obj.unit_price * standelem.getAttribute("ar");
            // }
        }
        if (waitlist_item && Object.keys(waitlist_item).length === 0 && waitlist_item.constructor === Object) {
            btn.removeAttribute('disabled');
            alert("Error encountered while adding to the waiting list. Please try again later.");

        } else {

            aj = new ajax("waitinglist/save", waitlist_item);
            // aj = new ajax("waitinglist/save",waitlist_item,"POST", "json", "text");
            aj.getResponse().then(response => {
                // Handle ajax response here.
                if (response.hasOwnProperty("status")) {
                    if (response.status > 0) {
                        alert("Stand added to waiting list successfully.");
                        standelem.setAttribute("fill", "url(#criscross)");
                        standelem.setAttribute("opacity", "0.5");
                        standelem.setAttribute("sts", "WAIT");
                        this.hide();
                    } else {
                        alert("Error encountered in adding to the waiting list.");
                        this.hide();
                    }
                } else {
                    alert("Error encountered in adding to the waiting list.");
                    this.hide();
                }
            });
        }
        return;
    }

    updateHeaderText(_header_text = "") {
        if (document.getElementById("header_text_span")) {
            document.getElementById("header_text_span").parentNode.removeChild(document.getElementById("header_text_span"));
        }

        let header_text = (_header_text == "") ? "Stand:" : _header_text;

        const header_text_span = document.createElement("span");
        header_text_span.id = "header_text_span";
        header_text_span.appendChild(document.createTextNode(header_text));

        const standno_span = document.createElement("span");
        // standno_span.id = "standno_span";
        standno_span.classList.add("standno_span");
        header_text = this.target.getAttribute("no") + " (" + this.target.getAttribute("ar") + " m";
        standno_span.appendChild(document.createTextNode(header_text));

        const sup_node = document.createElement("sup");
        sup_node.appendChild(document.createTextNode("2"));
        standno_span.appendChild(sup_node);

        standno_span.appendChild(document.createTextNode(")"));
        header_text_span.appendChild(standno_span);

        this.popHeader.appendChild(header_text_span);
        return;
    }

    buildBody() {
        const popup_close = document.createElement("div");

        this.popHeader = document.createElement("div");
        this.popHeader.id = 'header_text_div';

        const span_close = document.createElement("span");
        span_close.classList.add("popup_close");
        const closex = document.createTextNode('\u2715');
        span_close.appendChild(closex);

        const hr_line = document.createElement("hr");
        hr_line.classList.add("hr_line");

        popup_close.appendChild(this.popHeader);
        popup_close.appendChild(span_close);
        popup_close.appendChild(hr_line);

        let that = this;
        span_close.addEventListener('click', function (e) {
            that.clearSelected();
            that.hide();
        }
        );
        this.popblock.appendChild(popup_close);
        return;
    }
    hide() {
        this.popblock.style.display = "none";
    }
    show() {
        this.popblock.style.display = "block";
        return;
    }
    reset() {
        // while (this.popblock.firstChild) {
        //     this.popblock.removeChild(this.popblock.firstChild);
        // }
        // REMOVING THE LIST OF NODES EXCEPT FOR THE FIRST ONE
        // while (this.popblock.children.length > 1) {
        // REMOVING ALL THE NODES WITHIN this.popblock
        while (this.popblock.children.length) {
            this.popblock.removeChild(this.popblock.lastChild);
        }
        return;
    }

    buildPrimeStandDiv() {
        // this.reset();
        this.updateHeaderText();
        this.setAlignment();

        const email_address = "topdrawerexhibitors@clarionevents.com";

        const subdiv1 = document.createElement("div");
        subdiv1.classList.add("divselect");

        let interest_div = document.createElement("div");
        interest_div.appendChild(document.createTextNode("Interested in this stand?"));
        interest_div.classList.add("interest_div");

        let message_span = document.createElement("div");
        message_span.appendChild(document.createTextNode("Please email us on "));
        message_span.id = "primeproduct";

        let mailto_span = document.createElement("a");
        mailto_span.setAttribute("href", "mailto:" + email_address);
        mailto_span.appendChild(document.createTextNode(email_address));
        message_span.appendChild(mailto_span);

        subdiv1.appendChild(interest_div);
        subdiv1.appendChild(message_span);

        this.popblock.appendChild(subdiv1);

        return;
    }

    addToPrimeLead(btn, standelem) {
        alert('Add To Prime Lead')
    }

    cssStyleExists(pattern) {
        let docStyles = document.querySelectorAll("style");
        for (let i = 0; i < docStyles.length; i++) {
            let style = docStyles[i];
            let cssText = style.innerText;
            if (cssText.match(pattern)) return true;
        }
        return false;
    }
}
let popup_moveable;
window.addEventListener("load", (event) => {
    let p = document.getElementById("popup_tooltip");
    let fptb = document.getElementById("fp_toolbar_container");
    if (p instanceof HTMLDivElement) {
        if (typeof moveable === 'function') {
            let t = 0;
            let l = 0;
            if (fptb instanceof HTMLDivElement) {
                let fptbRect = fptb.getBoundingClientRect();
                if (fptbRect instanceof DOMRect) t = fptbRect.y + fptbRect.height;
            }
            let restrictRect = new DOMRect(l, t, window.innerWidth, window.innerHeight - t);
            popup_moveable = new moveable(p, restrictRect);
            popup_moveable.bindEvents();
        }
    }
});


