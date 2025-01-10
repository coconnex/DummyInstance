const utils = class {
    static getAllignment(target, pos = "TR", bounds = { "l": 0, "t": 0, "w": window.innerWidth, "h": window.innerHeight }) {
        let left, top;
        let posTop = bounds.t;
        let posRht = bounds.w - target.offsetWidth
        let posBot = bounds.h - target.offsetHeight;
        let posLft = bounds.l;
        let posMdW = (bounds.w / 2) - (target.offsetWidth / 2);
        let posMdH = (bounds.h / 2) - (target.offsetHeight / 2);
        switch (pos) {
            case "BL":
                left = posLft;
                top = posBot;
                break;
            case "BM":
                left = posMdW
                top = posBot;
                break;
            case "BR":
                left = posRht;
                top = posBot;
                break;
            case "ML":
                left = posLft;
                top = posMdH;
                break;
            case "MM":
                left = posMdW
                top = posMdH;
                break;
            case "MR":
                left = posRht;
                top = posMdH;
                break;
            case "TL":
                left = posLft;
                top = posTop;
                break;
            case "TM":
                left = posMdW;
                top = posTop;
                break;
            case "TR":
            default:
                left = posRht;
                top = posTop;
                break
        }
        return { "left": left + "px", "top": top + "px" }
    }
}