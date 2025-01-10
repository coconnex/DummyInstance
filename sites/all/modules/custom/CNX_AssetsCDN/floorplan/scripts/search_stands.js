const search_stands = class {
    invoker;
    container;
    txtexhibname;
    chx_avai_stands;
    txtstandno;
    btnsearch;
    btnsearch_reset;
    txtarea;
    txtarea_from;
    txtarea_to;
    post_search_callback;
    search_layer;
    map_available;
    map_number;
    map_name;
    map_area;
    map_all;
    search_modal;

    constructor(invoker) {
        this.container = document.querySelector("#search_container");
        this.search_layer = document.querySelector("#fp_container > #floorplan > #fpev > svg g#searchlayer");

        if(invoker !== null){
            this.invoker = invoker;
        }
        this.txtexhibname = this.container.querySelector("#txtexhibname");
        this.chx_avai_stands = this.container.querySelector("#chx_avai_stands");
        this.txtstandno = this.container.querySelector("#txtstandno");
        this.btnsearch = this.container.querySelector("#btnsearch");
        this.btnsearch_reset = this.container.querySelector("#btnsearch_reset");
        this.txtarea = this.container.querySelector("#txtarea");
        this.txtarea_from = 0;
        this.txtarea_to = 0;

        let that = this;
        this.btnsearch.addEventListener('click',function(e){
                that.prepareSearch();
            });
        this.btnsearch_reset.addEventListener('click',function(e){
                that.clearSearch();
            });

        if(is_showstandname == 0){
            this.txtexhibname.parentNode.style.display = 'none';
        }

        this.map_available = new Map();
        this.map_number = new Map();
        this.map_name = new Map();
        this.map_area = new Map();
        this.map_all = new Map();
        this.search_modal = new mdb.Modal(this.container);
        return;
    }

    getMinMax(ptsarray){
        let points = {};
        points.min = parseFloat(Math.min.apply(Math,ptsarray));
        points.max = parseFloat(Math.max.apply(Math,ptsarray));
        points.mid = (parseFloat(points.min)+parseFloat(points.max))/2;
        points.dist = parseFloat(points.max)-parseFloat(points.min);

        return points;
    }

    addCircle(stand_points){
        let props = {};
        const arr_points = stand_points.split(/\s+/);
        let stdXPoints = [];
        let stdYPoints = [];
        let xminmax = {};
        let yminmax = {};

        for (let i = 0; i <= arr_points.length-1; i++) {
            let _temp = arr_points[i].split(",");
            let x = parseFloat(_temp[0]);
            let y = parseFloat(_temp[1]);
            stdXPoints.push(x);
            stdYPoints.push(y);
        }
        xminmax = this.getMinMax(stdXPoints);
        yminmax = this.getMinMax(stdYPoints);

        props.x = xminmax.mid;
        props.y = yminmax.mid;
        props.r = (xminmax.dist >= yminmax.dist) ? parseFloat(yminmax.dist)/2 : parseFloat(xminmax.dist)/2;
        props.r = parseFloat(props.r)*0.9;

        var circle = document.createElementNS('http://www.w3.org/2000/svg','circle');
        circle.setAttribute('cx', props.x);
        circle.setAttribute('cy', props.y);
        circle.setAttribute('r', props.r);
        circle.setAttribute('fill',"var(--search_col)");
        circle.setAttribute("stroke-width","41");
        circle.setAttribute('stroke',"var(--search_col)");
        this.search_layer.appendChild(circle);
        return;
    }

    resetElementAndsearch(){

    }

    prepareSearch(){
        let search_summary = this.doSearch();
        this.prepareCallback(search_summary);
    }

    prepareCallback(search_summary){
        if(this.post_search_callback instanceof Function){
            this.post_search_callback(search_summary);
        }else{
            // console.log("snf");
        }
        return;
    }

    doSearch(){

        let stands_count = 0;
        let search_summary = {};
        let searched_shapes = [];

        this.resetSearch();

        // SEARCH ONLY AVAILABLE STANDS
        search_summary.chx_avai_stands = "";
        if(this.chx_avai_stands.checked){
            let stands = document.querySelectorAll("#fp_container > #floorplan > #fpev > svg g#standEvent > *[sts='AVA']");
            for (let i = 0; i < stands.length; i++) {
                let stand = stands[i];
                let standid = stand.getAttribute("id");
                this.map_available.set(standid, stand);
                this.map_all.set(standid, stand);
            }
            search_summary.chx_avai_stands = "AVA";
        }

        // SEARCH ON STAND NUMBER
        if(this.txtstandno.value.trim() != ''){
            let stands = document.querySelectorAll("#fp_container > #floorplan > #fpev > svg g#standEvent > *[no*='" + this.txtstandno.value.trim() + "' i]");
            for (let i = 0; i < stands.length; i++) {
                let stand = stands[i];
                let standid = stand.getAttribute("id");
                this.map_number.set(standid, stand);
                this.map_all.set(standid, stand);
            }
        }
        search_summary.txtstandno = this.txtstandno.value.trim();

        // SEARCH ON EXHIBITOR NAME
        if(this.txtexhibname.value.trim() != ''){
            let stands_ex = document.querySelectorAll("#fp_container > #floorplan > #fpev > svg g#standEvent > *[ex*='" + this.txtexhibname.value.trim() + "' i]");
            for (let i = 0; i < stands_ex.length; i++) {
                let stand = stands_ex[i];
                let standid = stand.getAttribute("id");
                this.map_name.set(standid, stand);
                this.map_all.set(standid, stand);
            }
        }
        search_summary.txtexhibname = this.txtexhibname.value.trim();

        // SEARCH ON AREA
        search_summary.txtarea = "";
        if(this.txtarea.value.trim() != ''){
            if(!isNaN(this.txtarea.value.trim())){
                search_summary.txtarea = this.txtarea.value.trim();
                let area_value = parseFloat(this.txtarea.value.trim());
                this.txtarea_from = (area_value>10) ? area_value-10 : 0;
                this.txtarea_to = area_value+10;
                let that = this;
                let stands_ar = Array.from(document.querySelectorAll("#fp_container > #floorplan > #fpev > svg g#standEvent > [ar]")).filter(that.nodefilter, this);
                for (let i = 0; i < stands_ar.length; i++) {
                    let stand = stands_ar[i];
                    let standid = stand.getAttribute("id");
                    this.map_area.set(standid, stand);
                    this.map_all.set(standid, stand);
                }
            }
        }

        for (const [key, stand] of this.map_all) {
            if(this.search_map_available(key) && this.search_map_number(key) && this.search_map_name(key) && this.search_map_area(key)){
                // maptemp.set(key,stand);
                searched_shapes.push(stand);
                this.addCircle(stand.getAttribute("points"));
                stand.setAttribute("tfil",1);
                stands_count++;
            }
        }

        search_summary.counts = stands_count;
        if (searched_shapes.length > 0) {
            let z = new zoomable(fp.fpDiv);
            z.autoZoom(searched_shapes);
        }
        this.search_modal.hide();
        return search_summary;
    }

    search_map_available(sid){
        if(this.map_available.size == 0){
            return true;
        }else{
            if(this.map_available.get(sid)){
                return true;
            }else{
                return false;
            }
        }
    }

    search_map_number(sid){
        if(this.map_number.size == 0){
            return true;
        }else{
            if(this.map_number.get(sid)){
                return true;
            }else{
                return false;
            }
        }
    }

    search_map_name(sid){
        if(this.map_name.size == 0){
            return true;
        }else{
            if(this.map_name.get(sid)){
                return true;
            }else{
                return false;
            }
        }
    }

    search_map_area(sid){
        if(this.map_area.size == 0){
            return true;
        }else{
            if(this.map_area.get(sid)){
                return true;
            }else{
                return false;
            }
        }
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
        let stands = document.querySelectorAll("#fp_container > #floorplan > #fpev > svg g#standEvent > [tfil]");
        for (let i = 0; i < stands.length; i++) {
            let stand = stands[i];
            let fil = stand.getAttribute("tfil");
            stand.setAttribute("fill",fil);
            stand.removeAttribute("tfil");
        }
        while (this.search_layer.firstChild) {
            this.search_layer.removeChild(this.search_layer.lastChild);
        }
        // CLEARING ALL SEARCH MAPS
        this.map_all.clear();
        this.map_number.clear();
        this.map_name.clear();
        this.map_area.clear();
        this.map_available.clear();
        return;
    }

    clearSearch(){
        this.txtexhibname.value = "";
        this.txtstandno.value = "";
        this.txtarea.value = "";
        this.txtarea_from = 0;
        this.txtarea_to = 0;
        while(this.invoker.search_tags.firstChild) {
            this.invoker.search_tags.removeChild(this.invoker.search_tags.lastChild);
        }
        this.resetSearch();

        return;
    }



    show(){
        // const modal = new mdb.Modal(this.container);
        this.search_modal.show();
    }

}

