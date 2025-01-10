const popup = class {
    target;
    standId;
    popblock;
    radiolist;
    rawdata;
    // divrvd;
    // divopt;

    constructor(standelem) {
        this.target = standelem;
        this.rawdata = products;
        console.log(this.rawdata);
        console.log(this.target);
        this.standId = this.target.getAttribute("id");
        this.popblock = document.getElementById("popup_tooltip");

        if(this.target.getAttribute("type") === "STD"){
            if(this.target.getAttribute("sts") === "AVA"){
                this.reset();
                this.setAlignment();
                this.buildAddToCartDiv();
                // this.buildWaitListDiv();
            }
            if(this.target.getAttribute("sts") === "RVD"){
                this.reset();
                this.setAlignment();
                this.buildWaitListDiv();
            }
        }
    }
    setAlignment() {
        let tl = this.target.getBoundingClientRect();
        this.popblock.style.left = (tl.left+tl.width+5)+"px";
        this.popblock.style.top = tl.top+"px";
        this.popblock.style.display = "block";
        return;
    }
    buildWaitListDiv(){
        const subdiv2 = document.createElement("div");
        subdiv2.classList.add("divbutton");

        const button = document.createElement("input");
        button.type = "button";
        button.value = "Add to Waiting List";
        button.name = "btnAdd";
        subdiv2.appendChild(button);
        let that = this;
        button.addEventListener('click',function(e)
            {
                that.addToWaitlist(that.target);
            }
        );
        this.popblock.appendChild(subdiv2);
    }
    buildAddToCartDiv(){
        let count = 0;
        const subdiv1 = document.createElement("div");
        subdiv1.classList.add("divselect");

        

        let select = document.createElement("select");
        select.name = "cmbproduct";
        select.id = "cmbproduct";
        // Adding default option
        let option = document.createElement('option');
        option.setAttribute('value', '');
        option.appendChild(document.createTextNode('Select package'));
        select.appendChild(option);

        // Adding all stand package options
        for (let key in this.rawdata) {
            let obj = this.rawdata[key];
            if(obj.package_type == "PROD"){
                let option = document.createElement('option');
                option.setAttribute('value', key);
                option.appendChild(document.createTextNode(obj.description + ' (' + obj.currency + ' ' + obj.unit_price + '/sqm)'));
                select.appendChild(option);

                count++;
            }
        }
        subdiv1.appendChild(select);
        this.popblock.appendChild(subdiv1);
        // Adding add to cart button
        if(count > 0){
            const subdiv2 = document.createElement("div");
            subdiv2.classList.add("divbutton");

            const button = document.createElement("input");
            button.type = "button";
            button.value = "Add to cart";
            button.name = "btnAdd";
            subdiv2.appendChild(button);
            let that = this;
            button.addEventListener('click',function(e)
                {
                    that.addToCart(that.target);
                }
            );
            this.popblock.appendChild(subdiv2);
        }
        return;
    }
    addToCart(standelem){
        const selected = document.getElementById("cmbproduct");
        const productselected = selected.value;
        if(productselected === ''){
            alert("Please select your stand package to continue");
            return;
        }else{
            let jsonproduct = {};
            for (let key in this.rawdata) {
                let obj = this.rawdata[key];
                console.log(obj);
                if(key == productselected){
                    jsonproduct.key = obj.short_key;
                    jsonproduct.name = obj.description;
                    jsonproduct.rate = obj.unit_price;
                    jsonproduct.ref = obj.urn;
                }
            }
            // console.log(jsonproduct);
            fp.addToCart(standelem, jsonproduct);
            this.hide();
        }
        return;
    }

    addToWaitlist(standelem){

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
                waitlist_item.total = obj.unit_price*standelem.getAttribute("ar");
            // }
        }
        console.log(waitlist_item);
        if(waitlist_item && Object.keys(waitlist_item).length === 0 && waitlist_item.constructor === Object){

            alert("Error encountered while adding to the waiting list. Please try again later.");

        }else{

            aj = new ajax("waitinglist/save",waitlist_item);
            // aj = new ajax("waitinglist/save",waitlist_item,"POST", "json", "text");
            aj.getResponse().then(response => {
                // Handle ajax response here.
                console.log(response);
                if (response.hasOwnProperty("status")) {
                    if(response.status > 0){
                        alert("Stand added to waiting list successfully.");
                        this.hide();
                    }else{
                        alert("Error encountered in adding to the waiting list.");
                        this.hide();
                    }
                }else{
                    alert("Error encountered in adding to the waiting list.");
                    this.hide();
                }
            });

        }
        return;
    }
    hide(){
        this.popblock.style.display = "none";
    }
    reset(){
        while (this.popblock.firstChild) {
            this.popblock.removeChild(this.popblock.firstChild);
        }
        return;
    }
}

