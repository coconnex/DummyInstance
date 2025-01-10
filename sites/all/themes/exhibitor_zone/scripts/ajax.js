const ajax = class {
    method;
    url;
    data;
    response;
    header;
    content_type;
    response_type;

    constructor(url, data, method = 'POST', request_data_type = 'JSON', response_data_type = 'JSON') {
        this.method = method;
        this.url = url;
        this.response_type = response_data_type;

        // console.log(this.method);
        // console.log(this.url);
        // console.log(response_data_type);

        // NOTE: Add content types if needed according to the data you need to pass. At the moment json is implemented.
        if(request_data_type.toLowerCase().trim() === 'json'){
            this.header = {
                "Content-Type": "application/json",
            };
            this.data = JSON.stringify(data);
        }
    }

    async postcall(){
        let response = {};
        let result;
        try {
            const response = await fetch(this.url,
                {
                    method: this.method,
                    headers: this.header,
                    body: this.data
                }
            );
            if(response.ok){
                if(this.response_type.toLowerCase().trim() === 'json'){
                    result = await response.json();
                    // console.log("Success:", result);
                }
                if(this.response_type.toLowerCase().trim() === 'text'){
                    result = await response.text();
                    // console.log("Success:", result);
                }

                return result;
            }else{
                response.status = false;
                response.message = "Error encountered";
                return response;
            }
        } catch (error) {
            console.error("Error:", error);
            response.status = false;
            response.message = error;
            return response;
        }
    }
    async getResponse(){
        let response = '';
        if(this.method === "POST"){
            response = await this.postcall();
        }else{

        }
        return response;
    }
}

