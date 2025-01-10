const moveable = class {
    target;
    move = false;
    moveToX = 0;
    moveToY = 0;
    ptype = 'mouse';
    constructor(target) {
        this.target = target;
    }

    bindEvents() {

        this.target.addEventListener("pointerdown", (event) => this.targetPointerDown(event));

        this.target.addEventListener("mouseup", (event) => this.targetPointerUp(event));
        this.target.addEventListener("mousemove", (event) => this.targetPointerMove(event));

        this.target.addEventListener("touchend", (event) => this.targetPointerUp(event));
        this.target.addEventListener("touchmove", (event) => this.targetPointerMove(event));
    }
    start(x, y){

        this.move = true;
        this.moveToX = x;
        this.moveToY = y;
    }
    doMove(x, y){
        if (this.move) {
            let m = this.getTargetMatrix();
            if (!(m instanceof DOMMatrix)) {
                m = new DOMMatrix();
            }
            m.e += (x - this.moveToX);
            m.f += (y - this.moveToY);
            this.target.style.transform = m.toString();
            this.moveToX = x;
            this.moveToY = y;
        }
    }
    end(){
        this.move = false;
        this.moveToX = 0;
        this.moveToY = 0;
    }
    targetPointerDown(event) {
        let point;
        this.ptype = event.pointerType;
        point = this.getPointerPoint(event);
        this.start(point.x, point.y);
    }

    targetPointerMove(event) {
        let point;
        point = this.getPointerPoint(event);
        this.doMove(point.x, point.y);
    }

    targetPointerUp(event) {
        this.end();
    }

    getPointerPoint(event){
        let point = new DOMPoint();
        switch(this.ptype){
            case "mouse":
                point.x = event.clientX;
                point.y = event.clientY;
                break;
            case "pen":
                point.x = event.clientX;
                point.y = event.clientY;
                break;
            case "touch":
                const _touches = event.changedTouches;
                if(_touches){
                    point.x = _touches[_touches.length-1].clientX;
                    point.y = _touches[_touches.length-1].clientY;
                }else{
                    point.x = event.clientX;
                    point.y = event.clientY;
                }
                break;
        }
        return point;
    }

    getTargetMatrix() {
        let t = this.target.style.transform;
        if (t) {
            let matches = t.match(/matrix\(.*\)/);
            if (matches.length === 1) {
                if (matches[0]) {
                    let m = new DOMMatrix(matches[0]);
                    if (m) {
                        return m;
                    }
                }
            }
        }
        return null;
    }
};
window.addEventListener("load", (event) => {
    let moveableElems = document.getElementsByClassName("moveable");
    if (moveableElems.length > 0) {
        for (let i = 0; i < moveableElems.length; i++) {
            let moveableElem = moveableElems[i];
            let clsMoveable = new moveable(moveableElem);
            clsMoveable.bindEvents();
        }
    }
});
