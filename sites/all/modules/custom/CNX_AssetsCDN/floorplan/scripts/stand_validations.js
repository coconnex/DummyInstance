const stand_validations = class {
    stand;
    all_packages;
    actions;
    response;
    self_info;


    constructor(stand, self_info) {
        this.stand = stand;
        this.self_info = self_info;
        this.all_packages = products;
        this.response = "NONE";
    }

    validate() {
        let response = {};
        response.success = 1;
        response.message = "Ok";
        if(this.validateSTD()){

            if(this.validatePrime()){
                response.message = "PRIME";
                return response;
            }

            if(this.validateAVA()){

                if(this.validateZone()){
                }else{
                    // console.log("out ZONE");
                    response.success = 0;
                    response.message = "XZONE";
                }
            }else{
                response.success = 0;
                response.message = "XAVA";
            }
        }else{
            response.success = 0;
            response.message = "XSTD";
        }
        return response;
    }

    validateSTD() {
        if(this.stand.getAttribute("type") === "STD"){
            return true;
        }
        return false;
    }

    validateAVA() {
        if(this.stand.getAttribute("sts") === "AVA"){
            return true;
        }
        return false;
    }

    validatePrime() {
        if(this.stand.hasAttribute("pcat")){
            if(this.stand.getAttribute("pcat") === "PRM"){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    validateZone() {
        let self_zone = this.self_info.get('zone_urn');

        if(self_zone > 0){
            if(this.stand.hasAttribute("z")){
                let zoneid = this.stand.getAttribute("z");
                if(zoneid){
                    if(self_zone != zoneid){
                        return false;
                    }
                }
            }
        }
        return true;
    }

}