const fpControls = class {
    navButton;
    container;
    section;
    itemElemsContainer;
    itemZoomInElem;
    itemZoomOutElem;
    itemResetElem;
    zoomInCallback;
    zoomOutCallback;
    resetCallback;
    toggleMode = true;

    constructor(_toggleMode = true) {
        this.toggleMode = _toggleMode;
        this.init();
    }

    init() {
        this.setControls();
        this.setEvents();
    }

    setControls() {
        this.section = document.querySelector("#fp_controls_section");
        this.container = this.section.querySelector("#fp_controls_container");
        this.navButton = this.container.querySelector("#fp_controls_nav_button");
        this.itemElemsContainer = this.container.querySelector("#fp_controls_items");
        this.itemZoomInElem = this.itemElemsContainer.querySelector("#fp_control_item_zmin");
        this.itemZoomOutElem = this.itemElemsContainer.querySelector("#fp_control_item_zmout");
        this.itemResetElem = this.itemElemsContainer.querySelector("#fp_control_item_reset");
        if (this.toggleMode) {
            this.section.classList.add('fp_controls_toggle_mode');
        } else {
            this.show();
        }
    }

    setEvents() {
        if (this.navButton && this.toggleMode) {
            this.navButton.addEventListener("pointerdown", (event) => { this.toggleNav() });
        }

        if (this.itemZoomInElem) {
            this.itemZoomInElem.addEventListener("pointerdown", (event) => { this.zoomInHandler() });
        }

        if (this.itemZoomOutElem) {
            this.itemZoomOutElem.addEventListener("pointerdown", (event) => { this.zoomOutHandler() });
        }

        if (this.itemResetElem) {
            this.itemResetElem.addEventListener("pointerdown", (event) => { this.resetHandler() });
        }
    }

    toggleNav() {
        if (this.itemElemsContainer) {
            if (this.itemElemsContainer.classList.contains('fp_control_items_show')) {
                this.hide();
            } else {
                this.show();
            }
        }
    }

    show() {
        if (this.itemElemsContainer) {
            this.itemElemsContainer.classList.remove('fp_control_items_hide');
            this.itemElemsContainer.classList.add('fp_control_items_show');
            this.navButton.classList.add('fp_controls_nav_button_active');
        }
    }

    hide() {
        if (this.itemElemsContainer) {
            this.itemElemsContainer.classList.remove('fp_control_items_show');
            this.navButton.classList.remove('fp_controls_nav_button_active');
            this.itemElemsContainer.classList.add('fp_control_items_hide');
        }
    }

    zoomInHandler() {
        if (this.zoomInCallback instanceof Function) this.zoomInCallback();
    }

    zoomOutHandler() {
        if (this.zoomOutCallback instanceof Function) this.zoomOutCallback();
    }

    resetHandler() {
        if (this.resetCallback instanceof Function) this.resetCallback();
    }
}

function doZoomIn() {
    let z = new zoomable(fp.fpDiv);
    z.zoomHandler(0.1);
    // console.log("in zoom in");
}

function doZoomOut() {
    let z = new zoomable(fp.fpDiv);
    z.zoomHandler(-0.1);
    // console.log("in zoom out");
}

function doReset() {
    m = new DOMMatrix()
    fp.fpDiv.style.transform = m.toString();
    return;
}

let z_incr = 0;
let ssFpControls;
window.addEventListener("load", (event) => {
    ssFpControls = new fpControls(false);
    ssFpControls.zoomInCallback = doZoomIn;
    ssFpControls.zoomOutCallback = doZoomOut;
    ssFpControls.resetCallback = doReset;
})