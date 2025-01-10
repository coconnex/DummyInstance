const search = class {
    target;
    txtexhibname;
    txtstandno;
    btnsearch;
    btnsearch_reset;
    txtarea;
    txtarea_from;
    txtarea_to;
    search_header;
    startPosition;

    constructor() {
        this.target = document.getElementById("search_container");
        this.txtexhibname = document.getElementById("txtexhibname");
        this.txtstandno = document.getElementById("txtstandno");
        this.btnsearch = document.getElementById("btnsearch");
        this.btnsearch_reset = document.getElementById("btnsearch_reset");
        this.search_close = document.getElementById("search_close");
        this.txtarea = document.getElementById("txtarea");
        this.txtarea_from = 0;
        this.txtarea_to = 0;
        this.search_header = document.getElementById("search_header");
        this.startPosition = "TL";

        let that = this;
        this.btnsearch.addEventListener('click',function(e)
            {
                that.doSearch();
            }
        );
        this.btnsearch_reset.addEventListener('click',function(e)
            {
                that.clearSearch();
            }
        );
        this.search_close.addEventListener('click',function(e)
            {
                that.hide();
            }
        );

        this.setAlignment(this.target, this.startPosition);
        this.show();
        return;
    }
    setAlignment(target, pos = "TR", bounds = { "l": 53, "t": parseInt(this.target.parentNode.getBoundingClientRect().top)+parseInt(78), "w": window.innerWidth, "h": window.innerHeight }) {
        let tl = utils.getAllignment(target, pos, bounds);
        target.style.left = tl.left;
        target.style.top = tl.top;
    }
    doSearch(){
        let stands_count = 0;
        this.resetSearch();
        // Search on stand number
        let stands = document.querySelectorAll("#fp_container > #floorplan > #fpex > svg g#STAND_SHAPE > *[no*='" + this.txtstandno.value.trim() + "' i]");
        stands_count += stands.length;
        for (let i = 0; i < stands.length; i++) {
            let stand = stands[i];
            let fil = stand.getAttribute("fill");
            stand.setAttribute("tfil",fil);
            stand.setAttribute("fill","var(--search_col)");
        }

        // Search on exhibitor name
        let stands_ex = document.querySelectorAll("#fp_container > #floorplan > #fpex > svg g#STAND_SHAPE > *[ex*='" + this.txtexhibname.value.trim() + "' i]");
        stands_count += stands_ex.length;
        for (let i = 0; i < stands_ex.length; i++) {
            let stand = stands_ex[i];
            let fil = stand.getAttribute("fill");
            if(!stand.hasAttribute("tfil")){
                stand.setAttribute("tfil",fil);
            }
            stand.setAttribute("fill","var(--search_col)");
        }

        // Search on area
        if(this.txtarea.value.trim() != ''){
            console.log(isNaN(this.txtarea.value));
            if(!isNaN(this.txtarea.value.trim())){
                let area_value = parseFloat(this.txtarea.value.trim());
                this.txtarea_from = (area_value>10) ? area_value-10 : 0;
                console.log(this.txtarea_from);
                this.txtarea_to = area_value+10;
                console.log(this.txtarea_to);
                let that = this;
                let stands_ar = Array.from(document.querySelectorAll("#fp_container > #floorplan > #fpex > svg g#STAND_SHAPE > [ar]")).filter(that.nodefilter, this);
                stands_count += stands_ar.length;
                for (let i = 0; i < stands_ar.length; i++) {
                    let stand = stands_ar[i];
                    let fil = stand.getAttribute("fill");
                    if(!stand.hasAttribute("tfil")){
                        stand.setAttribute("tfil",fil);
                    }
                    stand.setAttribute("fill","var(--search_col)");
                }
            }
        }

        this.showHeaderMessage(stands_count+' stands found');
        return;
    }

    nodefilter(obj){
        if(this.txtarea_from != '' && this.txtarea_to != ''){
            if((parseFloat(obj.getAttribute("ar")) >= parseFloat(this.txtarea_from)) && (parseFloat(obj.getAttribute("ar")) < parseFloat(this.txtarea_to))){
                return true;
            }else{
                return false;
            }
        }else if(this.txtarea_from != ''){
            if(parseFloat(obj.getAttribute("ar")) >= parseFloat(this.txtarea_from)){
                return true;
            }else{
                return false;
            }
        }else if(this.txtarea_to != ''){
            if(parseFloat(obj.getAttribute("ar")) < parseFloat(this.txtarea_to)){
                return true;
            }else{
                return false;
            }
        }
    }
    resetSearch(){
        let stands = document.querySelectorAll("#fp_container > #floorplan > #fpex > svg g#STAND_SHAPE > [tfil]");
        for (let i = 0; i < stands.length; i++) {
            let stand = stands[i];
            let fil = stand.getAttribute("tfil");
            stand.setAttribute("fill",fil);
            stand.removeAttribute("tfil");
        }
        this.showHeaderMessage("Search Stands");
        return;
    }

    clearSearch(){
        this.txtexhibname.value = "";
        this.txtstandno.value = "";
        this.txtarea.value = "";
        this.txtarea_from = 0;
        this.txtarea_to = 0;
        this.resetSearch();
        return;
    }

    showHeaderMessage(header_message){
        while (this.search_header.firstChild) {
            this.search_header.removeChild(this.search_header.firstChild);
        }
        this.search_header.appendChild(document.createTextNode(header_message));
        return;
    }

    hide(){
        this.target.style.display = "none";
        return;
    }

    show(){
        this.target.style.display = "block";
        return;
    }

}

