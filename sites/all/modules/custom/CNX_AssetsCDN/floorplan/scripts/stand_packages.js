const stand_packages = class {
    all_packages;
    filtered_packages;
    company_type_packages;
    zone_packages;
    standwise_packages;
    stand_urn;

    constructor(stand_urn = 0) {
        this.all_packages = products;
        this.stand_urn = stand_urn;

        this.filtered_packages = new Map();

        this.company_type_packages = new Map();
        this.zone_packages = new Map();
        this.standwise_packages = new Map();

        this.init();

    }

    init() {
        this.build_company_type_packages();
        this.build_zone_packages();
        this.build_standwise_packages();
    }

    get(){
        let cust_type_restrictions = 0;
        if (this.all_packages.hasOwnProperty("CONFIG")) {
            if (this.all_packages.CONFIG.hasOwnProperty("cust_type_restrictions")) {
                cust_type_restrictions = this.all_packages.CONFIG.cust_type_restrictions;
            }
        }
        if (this.all_packages.hasOwnProperty("DATA")) {
            for (let key in this.all_packages.DATA){
                let obj = this.all_packages.DATA[key];
                if(cust_type_restrictions == 1){
                    if(this.search_company_type_packages(obj.urn) && this.search_zone_packages(obj.urn) && this.search_standwise_packages(obj.urn)){
                        this.filtered_packages.set(obj.urn,obj);
                    }
                }
                if(cust_type_restrictions == 0){
                    if(this.search_zone_packages(obj.urn) && this.search_standwise_packages(obj.urn)){
                        this.filtered_packages.set(obj.urn,obj);
                    }
                }

            }
        }
        return this.filtered_packages;
    }

    search_company_type_packages(pid){
        if(this.company_type_packages.size == 0){
            return true;
        }else{
            if(this.company_type_packages.get(pid)){
                return true;
            }else{
                return false;
            }
        }
    }

    search_zone_packages(pid){
        if(this.zone_packages.size == 0){
            return true;
        }else{
            if(this.zone_packages.get(pid)){
                return true;
            }else{
                return false;
            }
        }
    }

    search_standwise_packages(pid){
        if(this.standwise_packages.size == 0){
            return true;
        }else{
            if(this.standwise_packages.get(pid)){
                return true;
            }else{
                return false;
            }
        }
    }

    build_company_type_packages(){
        if (this.all_packages.hasOwnProperty("CONFIG")) {
            if (this.all_packages.CONFIG.hasOwnProperty("company_type_packages")) {
                for (let key in this.all_packages.CONFIG.company_type_packages){
                    let obj = this.all_packages.CONFIG.company_type_packages[key];
                    this.company_type_packages.set(obj.product_id, obj);
                }
            }
        }
        return;
    }

    build_zone_packages(){
        if (this.all_packages.hasOwnProperty("CONFIG")) {
            if (this.all_packages.CONFIG.hasOwnProperty("zone_packages")) {
                for (let key in this.all_packages.CONFIG.zone_packages){
                    let obj = this.all_packages.CONFIG.zone_packages[key];
                    this.zone_packages.set(obj.product_id, obj);
                }
            }
        }
        return;
    }

    build_standwise_packages(){
        if (this.all_packages.hasOwnProperty("CONFIG")) {
            if (this.all_packages.CONFIG.hasOwnProperty("standwise_packages")) {
                for (let key in this.all_packages.CONFIG.standwise_packages){
                    let obj = this.all_packages.CONFIG.standwise_packages[key];
                    if(this.stand_urn == obj.stand_id){
                        this.standwise_packages.set(obj.product_id, obj);
                    }
                }
            }
        }
        return;
    }


}