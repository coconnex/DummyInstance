const fpToolBar = class {
    mainContainer;
    infoContainer;
    searchContainer;
    minicartContainer;
    cart_count;
    cart_total;
    search_tags;
    search;
    cart;
    stand_info;
    search_button;

    constructor() {
        this.init();
    }

    init() {
        this.mainContainer = document.querySelector("#fp_toolbar_container");
        this.infoContainer = this.mainContainer.querySelector("#fp_toolbar_info_container");
        this.searchContainer = this.mainContainer.querySelector("#fp_toolbar_search_container");
        this.search_button = this.mainContainer.querySelector("#search_button");
        this.minicartContainer = this.mainContainer.querySelector("#fp_toolbar_minicart_container");
        this.cart_count = this.minicartContainer.querySelector("span#cart_count");
        this.cart_total = this.minicartContainer.querySelector("span#cart_total");
        this.search_tags = this.searchContainer.querySelector("div#search_tags");

        this.setSearch();
        this.setCart();
        this.setStandInfo();
        this.setEvents();
    }

    setSearch() {
        this.search = new search_stands(this);
        this.search.post_search_callback = this.setSearchSummary;
    }

    setCart(cartElem) {
        this.cart = new cart(cartElem, this);
        this.cart.post_cart_callback = this.setCartSummary;
        return this.cart;
    }

    setStandInfo() {
        this.stand_info = new stand_info();
    }

    setSearchSummary(summary) {

        while (this.invoker.search_tags.firstChild) {
            this.invoker.search_tags.removeChild(this.invoker.search_tags.lastChild);
        }
        if (summary.chx_avai_stands != "") {
            this.invoker.addChip(summary.chx_avai_stands, "chx_avai_stands");
        }
        if (summary.txtstandno != "") {
            this.invoker.addChip(summary.txtstandno, "txtstandno");
        }
        if (summary.txtexhibname != "") {
            this.invoker.addChip(summary.txtexhibname, "txtexhibname");
        }
        if (summary.txtarea != "") {
            this.invoker.addChip(summary.txtarea, "txtarea");
        }
        let txtcounts = summary.counts + " stands found";
        if (summary.counts == 1) {
            txtcounts = summary.counts + " stand found";
        }
        this.invoker.addCountsChip(txtcounts, "txtcounts");

        return;
    }

    addChip(chip_value, chip_id) {
        let chipelem = document.createElement("div");
        chipelem.classList.add("chip");
        chipelem.setAttribute("data-mdb-chip-init", "");
        chipelem.setAttribute("data-mdb-close", "true");

        chipelem.appendChild(document.createTextNode(chip_value));
        let i_tag = document.createElement("i");
        i_tag.setAttribute("id", chip_id);
        i_tag.setAttribute("val", chip_value);
        i_tag.classList.add("close", "fas", "fa-times");
        i_tag.addEventListener('pointerdown', (e) => {
            this.chipHandler(e)
            e.stopPropagation();
        });
        chipelem.appendChild(i_tag);

        this.search_tags.appendChild(chipelem);

        const chip = new mdb.Chip(chipelem);
        return;
    }

    addCountsChip(chip_value, chip_id) {
        let chipelem = document.createElement("div");
        chipelem.id = "counts_chip";
        chipelem.classList.add("chip");
        chipelem.setAttribute("data-mdb-chip-init", "");
        chipelem.setAttribute("data-mdb-close", "true");

        chipelem.appendChild(document.createTextNode(chip_value));

        this.search_tags.appendChild(chipelem);

        const chip = new mdb.Chip(chipelem);
        return;
    }

    chipHandler(event) {
        let obj = eval("this.search." + event.target.id);
        if (obj.type == "checkbox") {
            obj.checked = false;
        } else {
            obj.value = "";
        }

        let retval = this.search.doSearch();
        this.search_tags.removeChild(event.target.parentNode);
        this.search_tags.removeChild(this.search_tags.lastChild);
        if (retval.counts > -1) {
            let txtcounts = retval.counts + " stands found";
            if (retval.counts == 1) {
                txtcounts = retval.counts + " stand found";
            }
            this.addCountsChip(txtcounts, "txtcounts");
        }

        if (this.search_tags.childNodes.length === 1 && this.search_tags.lastChild.id === "counts_chip")
            this.search_tags.removeChild(this.search_tags.lastChild);



        return;
    }

    setCartSummary(summary) {
        this.invoker.cart_count.innerHTML = summary.counts;
        this.invoker.cart_total.innerHTML = summary.totals.toLocaleString('en-GB', { style: 'currency', currency: 'GBP', minimumFractionDigits: 0 });
        return;
    }

    setEvents() {
        if (this.infoContainer instanceof HTMLDivElement) {
            this.infoContainer.addEventListener('pointerdown', (event) => { this.infoClickHandler() });
        }

        if (this.searchContainer instanceof HTMLDivElement) {
            this.searchContainer.addEventListener('pointerdown', (event) => { this.searchClickHandler() });
        }

        if (this.search_button instanceof HTMLDivElement) {
            this.search_button.addEventListener('pointerdown', (event) => { this.searchClickHandler() });
        }

        if (this.minicartContainer instanceof HTMLDivElement) {
            this.minicartContainer.addEventListener('pointerdown', (event) => { this.miniCartClickHandler() });
        }

    }

    infoClickHandler() {
        this.stand_info.reset();
        this.stand_info.show();
    }

    searchClickHandler() {
        this.search.show();
    }

    miniCartClickHandler() {
        this.cart.show();
    }

    showModal(modalId) {
        const modalElem = document.querySelector('#' + modalId);
        const modal = new mdb.Modal(modalElem);
        modal.show();
    }
}