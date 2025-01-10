const myStandTabsController = class {
    tabNavsElem;
    tabElems;
    basePath;
    clickedTab;
    constructor(basePath = "/mystands/") {
        this.basePath = basePath;
        this.tabNavsElem = document.querySelector(".mystand_tab .tabs-container .tab");
        if (this.tabNavsElem instanceof HTMLDivElement) {
            let _tabEelems = this.tabNavsElem.querySelectorAll(".tablinks");
            if (_tabEelems.length > 0) {
                this.tabElems = _tabEelems;
                this.setTabEvents();
            }
        }
    }

    setTabEvents() {
        for (let i = 0; i < this.tabElems.length; i++) {
            let tabElem = this.tabElems.item(i);
            if (tabElem.hasAttribute('status')) {
                let status = tabElem.getAttribute('status').trim();
                if (status) {
                    tabElem.addEventListener('click', (event) => {
                        this.tabOnClickHandler(status) });
                }
            }
        }
    }

    tabOnClickHandler(status) {
        if(status == this.clickedTab){
            return false;
        }else{
            this.clickedTab = status;
            window.location.replace(this.basePath + status);
        }
        
    }
}
let mstc;
window.addEventListener('load', (event) => {
    if (document.querySelector(".mystand_tab .tabs-container .tab") instanceof HTMLDivElement) {
        mstc = new myStandTabsController();
    }
})