const moveable = class {
    target;
    move = false;
    moveToX = 0;
    moveToY = 0;
    ptype = 'mouse';
    restrictionRect;
    constructor(target, restrictionRect = null) {
        this.target = target;
        this.restrictionRect = restrictionRect;
    }

    bindEvents() {

        this.target.addEventListener("pointerdown", (event) => this.targetPointerDown(event));

        this.target.addEventListener("mouseup", (event) => this.targetPointerUp(event));
        //this.target.addEventListener("mouseout", (event) => this.targetPointerUp(event));
        this.target.addEventListener("mousemove", (event) => this.targetPointerMove(event));

        this.target.addEventListener("touchend", (event) => this.targetPointerUp(event));
        this.target.addEventListener("touchcancel", (event) => this.targetPointerUp(event));
        this.target.addEventListener("touchmove", (event) => this.targetPointerMove(event));
    }
    start(x, y) {
        this.move = true;
        this.moveToX = x;
        this.moveToY = y;
    }
    doMove(x, y) {

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
            this.applyRestriction();
        }
    }
    end() {
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
        //event.stopPropogation();
    }

    targetPointerUp(event) {
        this.end();
    }

    getPointerPoint(event) {
        let point = new DOMPoint();
        switch (this.ptype) {
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
                if (_touches) {
                    point.x = _touches[_touches.length - 1].clientX;
                    point.y = _touches[_touches.length - 1].clientY;
                } else {
                    point.x = event.clientX;
                    point.y = event.clientY;
                }
                break;
        }
        //console.log(point);
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

    applyRestriction() {
        if (this.restrictionRect instanceof DOMRect) {
            let targetRect = this.target.getBoundingClientRect();
            if (targetRect instanceof DOMRect) {
                let checkLeft = targetRect.x < this.restrictionRect.x;
                let checkTop = targetRect.y < this.restrictionRect.y;
                let checkRight = targetRect.x + targetRect.width > this.restrictionRect.x + this.restrictionRect.width;
                let checkBottom = targetRect.y + targetRect.height > this.restrictionRect.y + this.restrictionRect.height;
                let check = checkLeft || checkTop || checkRight || checkBottom
                if (check) {
                    //this.move = false;
                    let m = new DOMMatrix(this.target.style.transform);
                    if (m instanceof DOMMatrix) {
                        if (checkLeft) {
                            m.e += (this.restrictionRect.x - targetRect.x);
                        }
                        if (checkTop) {
                            m.f += (this.restrictionRect.y - targetRect.y);
                        }
                        if (checkRight) {
                            m.e += ((this.restrictionRect.x + this.restrictionRect.width) - (targetRect.x + targetRect.width + 1));
                        }
                        if (checkBottom) {
                            m.f += ((this.restrictionRect.y + this.restrictionRect.height) - (targetRect.y + targetRect.height + 1));
                        }
                        this.target.style.transform = m.toString();
                    }
                }
            }
        }
    }
};
// window.addEventListener("load", (event) => {
//     let moveableElems = document.getElementsByClassName("moveable");
//     if (moveableElems.length > 0) {
//         for (let i = 0; i < moveableElems.length; i++) {
//             let moveableElem = moveableElems[i];
//             let clsMoveable = new moveable(moveableElem);
//             clsMoveable.bindEvents();
//         }
//     }
// });
