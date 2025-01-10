

const zoomable = class {
    target;
    autoZoomScaleLimit = 0.5;
    scale = 1;
    skipFillColors = ["var(--resvclr)", "var(--optnclr)", "#d4aaff", "#808080"];
    foundOpacity = 0.2;
    pointTestOn = false;

    constructor(target) {
        this.target = target;
    }

    bindEvents() {
        // let m = new DOMMatrix()
        // this.target.style.transform = m.toString();
        this.target.addEventListener("wheel", (event) => this.wheelHandler(event));
        window.addEventListener("keyup", (event) => this.keyupHandler(event));

    }

    wheelHandler(event) {
        let sIncr = (event.deltaY > 0) ? -0.1 : 0.1;
        let scale = this.getTargetScale(sIncr);
        let point = new DOMPoint(event.clientX - this.target.offsetLeft, event.clientY - this.target.offsetTop);
        if (scale !== null) this.zoom(scale, point);
        // console.log(this.target.getBoundingClientRect());
        // console.log(point);
        event.preventDefault()

    }

    keyupHandler(event) {
        if (!(event.key === "+" || event.key === "-")) return false;
        let sIncr;
        if (event.key === "+") sIncr = 0.1;
        if (event.key === "-") sIncr = -0.1;
        let scale = this.getTargetScale(sIncr);
        let point = new DOMPoint(this.target.offsetLeft + (this.target.offsetWidth / 2), this.target.offsetTop + (this.target.offsetHeight / 2));
        if (scale !== null) this.zoom(scale);
        event.preventDefault();
    }

    zoomHandler(z_val) {
        let scale = this.getTargetScale(z_val);
        let point = new DOMPoint(this.target.offsetLeft + (this.target.offsetWidth / 2), this.target.offsetTop + (this.target.offsetHeight / 2));
        if (scale !== null) this.zoom(scale);
        // event.preventDefault();
    }

    zoom(scale, point = null, aPoint = false) {
        if (scale !== null) {
            let zm = null;
            if (point instanceof DOMPoint) {
                zm = this.getTargetZoomMatrix(scale, point, aPoint);
            } else {
                zm = this.getTargetZoomMatrix(scale);
            }
            if (zm) this.target.style.transform = zm;
        }
    }

    getTargetScale(sIncr = 0) {
        let m = this.getTargetMatrix();
        if (m instanceof DOMMatrix) {
            return (parseFloat(m.a) < 1) ? 1 : parseFloat(m.a) + sIncr;
        } else {
            sIncr = (sIncr > 0) ? 1 + sIncr : 1;
        }
        return sIncr;
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

    autoZoom(shapes = [], fndColor = null) {
        let oldM = this.target.style.transform;
        let im = new DOMMatrix();
        this.target.style.transform = im.toString();
        //console.log(this.target.style.transform);
        let b = this.getZoomBounds(shapes, fndColor);
        //console.log("shape bounds");
        //console.log(b);
        if (b) {
            let s = this.getAutoScale(b);
            if (s !== 1) {
                let p = new DOMPoint(b.x + (b.width / 2), b.y + (b.height / 2));
                let ap = new DOMPoint(this.target.offsetLeft + (this.target.offsetWidth / 2), (this.target.offsetTop + 100) + (this.target.offsetHeight / 2));
                //console.log(p);
                //console.log(ap);
                return this.zoom(s, p, ap);
            }
        }
        if (oldM) this.target.style.transform = oldM;
        return null;
    }

    getZoomBounds(shapes, fndColor = null) {
        if (shapes.length > 0) {
            let plotsX = [], plotsY = []
            for (let i = 0; i < shapes.length; i++) {
                let shape = shapes[i];
                if (shape instanceof NodeList) {
                    this.getNodeListBounds(shape, plotsX, plotsY, fndColor);
                } else {
                    this.setPlots(shape, plotsX, plotsY);
                    this.setFoundColor(shape, fndColor);
                }
            }
            return new DOMRect(Math.min(...plotsX), Math.min(...plotsY), Math.max(...plotsX) - Math.min(...plotsX), Math.max(...plotsY) - Math.min(...plotsY));
        }
        return false;
    }

    getNodeListBounds(list, plotsX, plotsY, fndColor = null) {
        for (let i = 0; i < list.length; i++) {
            let shape = list[i];
            this.setPlots(shape, plotsX, plotsY);
            this.setFoundColor(shape, fndColor);
        }
    }

    setPlots(shape, plotsX, plotsY) {
        let cRect = shape.getBoundingClientRect();
        //console.log(cRect);
        plotsX.push(cRect.x);
        plotsX.push(cRect.x + cRect.width);
        plotsY.push(cRect.y);
        plotsY.push(cRect.y + cRect.height);
    }

    setFoundColor(shape, fndColor) {
        if (shape && fndColor) {
            if (!this.skipFillColors.includes(shape.getAttribute("fill"))) {
                shape.setAttribute("fill", fndColor);
                shape.setAttribute("opacity", this.foundOpacity);
            }
        }
    }

    getAutoScale(zoomBounds) {
        if (zoomBounds instanceof DOMRect && this.target) {
            //console.log(subBounds);
            let trgBounds = this.target.getBoundingClientRect();
            //console.log(trgBounds);
            let scaleW = ((trgBounds.width * this.autoZoomScaleLimit) / zoomBounds.width)
            let scaleH = ((trgBounds.height * this.autoZoomScaleLimit) / zoomBounds.height);
            //console.log(scaleW + " : " + scaleH);
            let scale = (scaleH < scaleW) ? scaleH : scaleW;
            if (scale < 0) scale = 1;
            return (scale < 1) ? 1 + scale : scale;
        }
        return 1;
    }

    getTargetZoomMatrix(scale, point = null, aPoint = null) {

        if (scale !== null) {
            this.clearTestPoints();
            this.setTestPoint(point, "rgb(34,139,34,0.8)", this.target.parentNode);
            let cm = new DOMMatrix();
            if (this.getTargetMatrix() instanceof DOMMatrix) {
                cm = this.getTargetMatrix();//KS:Assign transform matrix if present
            }
            let cr = this.target.getBoundingClientRect();//KS:Get current rect of the target object because target's scaling anchor is middle middle
            //console.log("cr");
            //console.log(cr);
            //let cPoint = null
            //KS:Strict if point for scale is outside target bounds
            // if (point instanceof DOMPoint) {
            //     cPoint = new DOMPoint(Math.abs(cr.x) - point.x, Math.abs(cr.y) - point.y);//KS: Transform point to match the current context
            //     if ((cPoint.x <= 0 || cPoint.x >= cr.width) || (cPoint.y <= 0 || cPoint.y >= cr.height)) return null;//KS: Don't zoom if the point provided is out the target bounds
            // }
            let ccp = new DOMPoint(cr.x + (cr.width / 2), cr.y + (cr.height / 2)); //KS:Capture current center

            //KS:Scaling starts
            let sm = cm;
            sm.a = scale;//KS:Add scaleX to matrix
            sm.d = scale;//KS:Add scaleY to matrix

            let se = sm.e + (sm.e * scale);//KS: Predict the translation deltaX due to scaling of applied x translation
            let sf = sm.f + (sm.f * scale);//KS: Predict the translation deltaY due to scaling of applied y translation

            let scp = sm.transformPoint(ccp);//KS: Transform current center to predict post transformation center

            let dscpx = (ccp.x - scp.x)//KS: Calculate deltaX current center from scaled center
            let dscpy = (ccp.y - scp.y)//KS: Calculate deltaY current center from scaled center

            let tx = se + dscpx;//KS: Predict the final x position after scaling. This will help in calcualting translation to align to mouse point
            let ty = sf + dscpy;//KS: Predict the final y position after scaling. This will help in calcualting translation to align to mouse point

            if (point instanceof DOMPoint && aPoint instanceof DOMPoint) {

                let sPoint = sm.transformPoint(point);//KS:Transform Point
                sPoint.x += dscpx //KS:Align to centerX
                sPoint.y += dscpy //KS:Align to centerY
                let sptx = aPoint.x - sPoint.x; //KS: Point deltaX after scaling
                let spty = aPoint.y - sPoint.y; //KS: Point deltaY after scaling
                sm.e = sptx;//KS: Assign transaltionX
                sm.f = spty;//KS: Assign transaltionY
                this.setTestPoint(sPoint, "rgb(0,0,255,0.5)", this.target.parentNode, sm);

            }
            else if (point instanceof DOMPoint) {
                // console.log(point)
                // let cPoint = cm.transformPoint(point);//KS:Transform Point
                // cPoint.x += cPoint.x / 2 //KS:Align to centerX
                // cPoint.y += cPoint.y / 2 //KS:Align to centerY

                // console.log(cPoint)
                // let sPoint = sm.transformPoint(point);//KS:Transform Point
                // sPoint.x += dscpx //KS:Align to centerX
                // sPoint.y += dscpy //KS:Align to centerY
                //     //console.log(sPoint)
                // let sptx = point.x - sPoint.x; //KS: Point deltaX after scaling
                // let spty = point.y - sPoint.y; //KS: Point deltaY after scaling
                //     // sm.e += (((Math.abs(tx) + point.x) - point.x) - ((Math.abs(cr.x) + point.x) - point.x));//KS: Assign transaltionX to keep target in range
                //     // sm.f += (((Math.abs(ty) + point.y) - point.y) - ((Math.abs(cr.y) + point.y) - point.y));//KS: Assign transaltionY to keep target in range
                // sm.e += point.x - ((Math.abs(tx) + point.x) - point.x);
                // sm.f += point.y - ((Math.abs(ty) + point.y) - point.y);
                // sm.e = sptx;
                // sm.f = spty;
            }
            this.setTestPoint(point);
            try {
                return sm.toString()
            } catch (e) {
                return null;
            }

        }
        return null;
    }

    setTestPoint(point, clr = "rgba(255,0,0,0.8)", prnt = null, m) {
        if (!this.pointTestOn) return false;
        let testDiv = document.createElement("div");
        testDiv.classList.add("testdiv");
        testDiv.style.position = "absolute";
        testDiv.style.zIndex = 1000;
        testDiv.style.left = (point.x - 5) + "px";
        testDiv.style.top = (point.y - 5) + "px";
        testDiv.style.borderRadius = "10px 10px";
        testDiv.style.padding = "5px";
        testDiv.style.backgroundColor = clr;
        if (prnt) {
            prnt.appendChild(testDiv);
        } else {
            this.target.appendChild(testDiv);
        }
        if (m instanceof DOMMatrix) {
            testDiv.style.transform = m.toString();
        }
    }

    clearTestPoints() {
        let testDivs = document.querySelectorAll(".testdiv");
        for (let i = 0; i < testDivs.length; i++) {
            let testDiv = testDivs[i];
            testDiv.parentNode.removeChild(testDiv);
        }
    }
}

// window.addEventListener("load", (event) => {
//     let zoomableElems = document.getElementsByClassName("zoomable");
//     if (zoomableElems.length > 0) {
//         for (let i = 0; i < zoomableElems.length; i++) {
//             let zoomableElem = zoomableElems[i];
//             let clsZoomable = new zoomable(zoomableElem);
//             clsZoomable.bindEvents();
//         }
//     }
// });