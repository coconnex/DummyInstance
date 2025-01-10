const panzoom = class {
    static pntrEvCache = [];
    static zoomDiff = -1;
    _moveable;
    _zoomable;

    constructor(target) {
        this.target = target;
        this._moveable = new moveable(target);
        this._zoomable = new zoomable(target);
    }


    removeFromPointerCache(event) {
        // Remove this event from the target's cache
        const index = panzoom.pntrEvCache.findIndex(
            (cachedEv) => cachedEv.pointerId === event.pointerId,
        );
        panzoom.pntrEvCache.splice(index, 1);
    }

    addToPointerCache(event) {
        panzoom.pntrEvCache.push(event);
    }

    bindEvents() {
        this.target.addEventListener("pointerdown", (event) => this.targetPointerDown(event));
        this.target.addEventListener("pointermove", (event) => this.targetPointerMove(event));
        this.target.addEventListener("pointerup", (event) => this.targetPointerUp(event));
        this.target.addEventListener("pointercancel", (event) => this.targetPointerUp(event));
        this.target.addEventListener("pointerleave", (event) => this.targetPointerUp(event));
        // this.target.addEventListener("pointerout", (event) => this.targetPointerUp(event));
        // this.target.addEventListener("mouseup", (event) => this.targetPointerUp(event));
        // this.target.addEventListener("mousemove", (event) => this.targetPointerMove(event));
        // this.target.addEventListener("touchend", (event) => this.targetPointerUp(event));
        // this.target.addEventListener("touchmove", (event) => this.targetPointerMove(event));
        this._zoomable.bindEvents();
    }

    targetPointerDown(event) {
        if (panzoom.pntrEvCache.length === 2) return;
        this.addToPointerCache(event);
        this._moveable.targetPointerDown(panzoom.pntrEvCache[0]);
    }

    targetPointerUp(event) {
        if (panzoom.pntrEvCache.length === 0) return;
        if (event.pointerId === panzoom.pntrEvCache[0].pointerId) this._moveable.targetPointerUp(event)
        this.removeFromPointerCache(event);
    }

    targetPointerMove(event) {
        if (panzoom.pntrEvCache.length === 0) return;

        const index = panzoom.pntrEvCache.findIndex(
            (cachedEv) => cachedEv.pointerId === event.pointerId,
        );
        if (index >= 0) panzoom.pntrEvCache[index] = event;

        if (panzoom.pntrEvCache.length === 1) {
            if (event.pointerId === panzoom.pntrEvCache[0].pointerId) this._moveable.targetPointerMove(event);
        }

        if (panzoom.pntrEvCache.length > 1) {
            const curZoomDiff = Math.sqrt(Math.pow(panzoom.pntrEvCache[0].clientX - panzoom.pntrEvCache[1].clientX, 2) + Math.pow(panzoom.pntrEvCache[0].clientY - panzoom.pntrEvCache[1].clientY, 2));
            if (panzoom.zoomDiff > 0) {
                let sIncr = 0;
                if (curZoomDiff > panzoom.zoomDiff) sIncr = 0.075;  // The distance between the two pointers has increased
                if (curZoomDiff < panzoom.zoomDiff) sIncr = -0.075; // The distance between the two pointers has decreased
                let scale = this._zoomable.getTargetScale(sIncr);
                let point = new DOMPoint(event.clientX - this.target.offsetLeft, event.clientY - this.target.offsetTop);
                if (scale !== null) this._zoomable.zoom(scale, point);
            }
            panzoom.zoomDiff = curZoomDiff;

        }
    }


}