const stand_actions = class {
    stand;
    all_packages;
    filtered_packages;
    action;
    self_info;

    constructor(stand) {
        this.stand = stand;
        this.all_packages = products;
        this.action = "NONE";
        this.filtered_packages = new Map();
        this.self_info = new Map();

        this.populateSelfInfo();
        this.process();
    }

    process(){
        let obj_valid = new stand_validations(this.stand, this.self_info);
        let response = obj_valid.validate();
        if(response.success == 1){
            if(response.message == "PRIME"){
                let pop = new popup(this.stand, 'PRIME');
            }else{
                let stand_urn = this.stand.getAttribute("id");
                let obj_packages = new stand_packages(stand_urn);
                this.filtered_packages = obj_packages.get();
                let pop = new popup(this.stand, 'CART', this.filtered_packages);
            }
        }else{
            if(response.success == 0){
                if(response.message == "XZONE"){
                    alert("Please select a stand from "+ this.self_info.get('zone_name') +" section.");
                    return;
                }
            }
        }
    }

    populateSelfInfo(){
        let self_zone = 0;
        let company_type = "";
        let zone_name = "";
        let zone_key = "";

        if (this.all_packages.hasOwnProperty("CONFIG")) {
            if (this.all_packages.CONFIG.hasOwnProperty("zone_urn")) {
                self_zone = this.all_packages.CONFIG.zone_urn;
                if(self_zone == "" || self_zone == null){
                    self_zone = 0;
                }
            }
            if (this.all_packages.CONFIG.hasOwnProperty("zone_name")) {
                zone_name = this.all_packages.CONFIG.zone_name;
                if(zone_name == "" || zone_name == null){
                    zone_name = "";
                }
            }
            if (this.all_packages.CONFIG.hasOwnProperty("zone_key")) {
                zone_key = this.all_packages.CONFIG.zone_key;
                if(zone_key == "" || zone_key == null){
                    zone_key = "";
                }
            }
            if (this.all_packages.CONFIG.hasOwnProperty("company_type")) {
                company_type = this.all_packages.CONFIG.company_type;
                if(company_type == "" || company_type == null){
                    company_type = "";
                }
            }
        }
        this.self_info.set('zone_urn',self_zone);
        this.self_info.set('company_type',company_type);
        this.self_info.set('zone_name',zone_name);
        this.self_info.set('zone_key',zone_key);
        return;
    }



}