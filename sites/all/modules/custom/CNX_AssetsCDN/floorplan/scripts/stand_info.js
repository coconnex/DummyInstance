const stand_info = class {
    container;
    // packageModal;
    status_container;
    zone_container;
    actions_container;
    statuses;
    zones;
    invoker;
    actions;
    info_modal;
    // package_modal;

    constructor(invoker = null) {
        this.container = document.querySelector("#fp_toolbar_info_modal");
        // this.packageModal = document.querySelector("#package_modal");
        this.status_container = this.container.querySelector("ul#legends_status");
        this.zone_container = this.container.querySelector("ul#legends_zone");
        this.actions_container = this.container.querySelector("ul#legends_action");
        this.statuses = statuses;

        this.reset();

        if (invoker !== null) {
            this.invoker = invoker;
        }
        if (is_waitinglist == 0) {
            this.actions_container.querySelector("span#waitlisted_counts").parentNode.style.display = 'none';
        }
        this.info_modal = new mdb.Modal(this.container);
        // this.package_modal = new mdb.Modal(this.packageModal);
        return;
    }

    reset() {
        this.setStatusList();
        this.setZonesList();
        this.setActionsList();
        return;
    }

    setActionsList() {
        let searched_stands = document.querySelectorAll("#fp_container > #floorplan > #fpev > svg g#standEvent > [tfil]");
        let search_counts = this.actions_container.querySelector("span#searched_counts");
        if(searched_stands.length > 0){
            search_counts.parentNode.style.cursor = "pointer";
            search_counts.parentNode.addEventListener('pointerdown', (e) => {
                this.zoomToStands(e,searched_stands);
            });
        }
        search_counts.textContent = "(" + searched_stands.length + ")";

        let waitlisted_stands = document.querySelectorAll("#fp_container > #floorplan > #fpev > svg g#standEvent > *[sts='WAIT']");
        let waitlist_counts = this.actions_container.querySelector("span#waitlisted_counts");
        waitlist_counts.textContent = "(" + waitlisted_stands.length + ")";
        return;
    }

    setZonesList() {
        // REMOVING THE LIST EXCEPT FOR THE FIRST ONE
        while (this.zone_container.children.length > 0) {
            this.zone_container.removeChild(this.zone_container.lastChild);
        }
        let stands = document.querySelectorAll("#fp_container > #floorplan > #fpev > svg g#standEvent > *[z]");
        let zone_ids = [];
        this.zones = [];
        for (let i = 0; i < stands.length; i++) {
            let stand = stands[i];
            let id = stand.getAttribute("z");
            if (typeof zone_ids[id] === 'undefined') {
                // if(zone_ids.includes(id)) {
                let zone = {};
                zone.id = id;
                zone.name = stand.getAttribute("zn");
                zone.colour = stand.getAttribute("zc");

                this.zones.push(zone);
                zone_ids[id] = '';
            }
        }
        zone_ids = [];

        for (let i = 0; i < this.zones.length; i++) {
            let zone = this.zones[i];
            let stands = document.querySelectorAll("#fp_container > #floorplan > #fpev > svg g#standEvent > *[z='" + zone.id + "']");
            let main_li = this.getLegendNode(zone.name, zone.colour, stands.length);
            main_li.style.cursor = "pointer";
            main_li.addEventListener('pointerdown', (e) => {
                this.zoomToStands(e,stands);
            });
            this.zone_container.appendChild(main_li);
        }
        return;
    }

    setStatusList() {
        // REMOVING THE LIST EXCEPT FOR THE FIRST ONE
        while (this.status_container.children.length > 0) {
            this.status_container.removeChild(this.status_container.lastChild);
        }
        for (let key in this.statuses) {
            if (is_waitinglist == 0 && key.toLowerCase().trim() == "wait") {
                continue;
            }
            if (this.statuses.hasOwnProperty(key)) {
                let stands = document.querySelectorAll("#fp_container > #floorplan > #fpev > svg g#standEvent > *[sts='" + key.trim() + "' i]");
                let main_li = this.getLegendNode(this.statuses[key].description, this.statuses[key].back_color, stands.length);
                if (key == "AVA") main_li.style.border = "solid thin #000000;";
                if(key != 'BLK' && stands.length > 0){
                    main_li.style.cursor = "pointer";
                    main_li.addEventListener('pointerdown', (e) => {
                        this.zoomToStands(e,stands);
                    });
                }
                this.status_container.appendChild(main_li);
            }
        }
    }

    zoomToStands(event, stand_node_list){
        let stands_to_zoom = [];

        for (let i = 0; i < stand_node_list.length; i++) {
            let stand = stand_node_list[i];
            stands_to_zoom.push(stand);
        }
        if (stands_to_zoom.length > 0) {
            let z = new zoomable(fp.fpDiv);
            z.autoZoom(stands_to_zoom);
        }
        this.hide();
        return;
    }

    getLegendNode(name, color, itemCount) {
        let main_li = document.createElement("li");
        main_li.classList.add("legend");
        main_li.style.backgroundColor = color;

        let sub_span_1 = document.createElement("span");
        sub_span_1.appendChild(document.createTextNode(name));
        main_li.appendChild(sub_span_1);
        let sub_span_2 = document.createElement("span");
        sub_span_2.classList.add("legend_item_count");
        sub_span_2.appendChild(document.createTextNode(" (" + itemCount + ")"));
        main_li.appendChild(sub_span_2);
        return main_li;
    }

    show() {
        this.info_modal.show();
        // this.package_modal.show();
    }

    hide() {
        this.info_modal.hide();
        // this.package_modal.hide();
    }

}

