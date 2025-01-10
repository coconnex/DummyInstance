const fpToolBar = class {
    mainContainer;
    infoContainer;
    searchContainer;
    minicartContainer;

    constructor() {
        this.init();
    }

    init() {
        this.mainContainer = document.querySelector("#fp_toolbar_container");
        this.infoContainer = this.mainContainer.querySelector("#fp_toolbar_info_container");
        this.searchContainer = this.mainContainer.querySelector("#fp_toolbar_search_container");
        this.minicartContainer = this.mainContainer.querySelector("#fp_toolbar_minicart_container");
        this.setEvents();
    }

    setEvents() {
        if (this.infoContainer instanceof HTMLDivElement) {
            this.infoContainer.addEventListener('pointerdown', (event) => { this.infoClickHandler() });
        }

        if (this.searchContainer instanceof HTMLDivElement) {
            this.searchContainer.addEventListener('pointerdown', (event) => { this.searchClickHandler() });
        }

        if (this.minicartContainer instanceof HTMLDivElement) {
            this.minicartContainer.addEventListener('pointerdown', (event) => { this.miniCartClickHandler() });
        }
    }

    infoClickHandler() {
        this.showModal('fp_toolbar_info_modal');
    }

    searchClickHandler() {
        this.showModal('fp_toolbar_info_modal');
    }

    miniCartClickHandler() {
        this.showModal('fp_toolbar_info_modal');
    }

    showModal(modalId) {
        const modalElem = document.querySelector('#' + modalId);
        const modal = new mdb.Modal(modalElem)
        modal.show()
    }
}

let ssFpToolBar;
window.addEventListener("load", (event) => {
    ssFpToolBar = new fpToolBar();
})