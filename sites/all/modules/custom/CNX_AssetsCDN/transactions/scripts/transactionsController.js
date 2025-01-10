let req_cnl_modal_content;
let req_cnl_modal;

function getpackages(){
    const stand_refs = document.getElementsByName('stand_ref');
    for (let i = 0; i < stand_refs.length; i++) {
        // console.log(stand_refs[i].value);
        let obj_packages = new stand_packages(stand_refs[i].value);
        let filtered_packages = obj_packages.get();
        // console.log(filtered_packages);

        const sel_packages = document.getElementsByName('sel_package');
        const selects = document.getElementsByName('shell_scheme');
        for (let i = 0; i < selects.length; i++) {
            for (let [key, obj] of filtered_packages) {
                if(obj.package_type == "PROD"){
                    let option = document.createElement('option');
                    option.setAttribute('value', key);
                    // console.log(sel_packages[i].value);
                    // console.log(obj.urn);
                    if(sel_packages[i].value == obj.urn){
                        option.setAttribute('selected', 'selected');
                    }
                    option.appendChild(document.createTextNode(obj.description + ' (' + obj.currency + ' ' + obj.unit_price + '/sqm)'));
                    selects[i].appendChild(option);
                }
            }
        }
    }
}

function updateTransaction(stand_transaction_id){

    const selected = document.getElementById("package"+stand_transaction_id);
    const packageselected = selected.value;
    const quant = document.getElementById("quantity"+stand_transaction_id);
    const quantity = quant.value;
    const trans = document.getElementById("stand_transaction_id"+stand_transaction_id);
    const transaction_id = trans.value;
    const stand_ref = document.getElementById("stand_ref"+stand_transaction_id);
    const stand_ref_id = stand_ref.value;
    const sel_package = document.getElementById("sel_package"+stand_transaction_id);
    const sel_package_id = sel_package.value;
    const fees_total = document.getElementById("fees_total"+stand_transaction_id);
    const fees_total_val = fees_total.value;

    // let rawdata = products;
    let obj_packages = new stand_packages(stand_ref_id);
    let filtered_packages = obj_packages.get();
    let jsonpackage = {};
    for (let [key, obj] of filtered_packages) {
        // let obj = filtered_packages[key];
        // console.log(obj);
        // console.log(packageselected);
        if(obj.urn === packageselected){
            if(obj.short_key == 'SPC' && quantity<24){
                alert("This stand with area less than 24sqm cannot be added for "+obj.description+".");
                document.getElementById("package"+stand_transaction_id).value = sel_package_id;
                return false;
            }
            jsonpackage.quantity = quantity;
            jsonpackage.description = obj.description;
            jsonpackage.transaction_id = transaction_id;
            jsonpackage.package_key = obj.short_key;
            jsonpackage.total = obj.unit_price * quantity;
            jsonpackage.rate = obj.unit_price;
            jsonpackage.product_ref = obj.urn;
        }
    }
    // console.log(jsonpackage);
    if(jsonpackage){
        aj = new ajax("/transaction/update",jsonpackage);

        aj.getResponse().then(response => {
            // Handle ajax response here.
            if (response.hasOwnProperty("status")) {
                if(response.status > 0){
                    alert("Package updated successfully.");
                    document.getElementById("reserved_sub_total"+stand_transaction_id).innerHTML = (jsonpackage.total).toLocaleString();
                    const total = parseInt(jsonpackage.total,10) + parseInt(fees_total_val,10);
                    document.getElementById("reserved_total"+stand_transaction_id).innerHTML = (total).toLocaleString();
                    document.getElementById("sel_package"+stand_transaction_id).value = jsonpackage.product_ref;
                }else{
                    alert("Error encountered while updating package.");
                }
            }else{
                alert("Error encountered while updating package.");
            }
        });
    }

}

function check_selected_stand_for_contracts(checkExist = 'YES'){
    document.getElementById("contract_creation_btn").setAttribute('disabled', '');
    const actual_action = 'CNT_CREATE';
    const processing_request_message = set_processing_request_message(actual_action);
    processing.show(processing_request_message);
    if(checkExist == 'NO'){
        document.getElementById("reserved_stand_form").submit();
    }else if(checkExist == 'YES'){
        var len = document.querySelectorAll('input[type="checkbox"]:checked').length;
        if (len <= 0) {
          alert("To proceed with creating the contract, please choose at least one stand.");
          document.getElementById("contract_creation_btn").removeAttribute('disabled');
        } else {
          document.getElementById("reserved_stand_form").submit();
        }
    }
}

function manage_actions(id){
    document.getElementById(id).setAttribute('disabled', '');

    const element = document.getElementById(id);
    let action = id.replace(/[0-9]/g, '');
    const link = element.getAttribute("link");
    const value = element.getAttribute("value");
    const action_link = link;
    const actual_action = action_link.replace("/mystands/action/", "");
    const processing_request_message = set_processing_request_message(actual_action);
    let jsondata = {};
    jsondata.transaction_id = value;
    if(actual_action == 'CNT_REQ_CANCEL' || actual_action == 'ORD_REQ_CANCEL'){
        var request_cancellation_reason = document.getElementById('request_cancellation_reason').value;
        // console.log(request_cancellation_reason);
        if(request_cancellation_reason.trim() != ""){
            $reason = request_cancellation_reason.trim();
            let max_word_length = document.getElementById('max_word_length').value;;
            let reason_word_length = WordCount($reason);
            // alert(reason_word_length);
            if(reason_word_length > max_word_length){
                alert("Sorry! You can only add "+max_word_length+" words.");
                document.getElementById(id).removeAttribute('disabled');
                return false;
            }else{
                jsondata.cancellation_reason = $reason;
            }
        }else{
            alert("Please provide a reason to submit contract cancellation request.");
            document.getElementById(id).removeAttribute('disabled');
            return false;
        }
    }

    let result = false;
    if(actual_action == 'CNT_SIGN'){
        result = true;
    }else{
        result = confirm("Do you wish to continue with this action? Please confirm.");
    }

    if (result === true) {
        if(actual_action == 'CNT_REQ_CANCEL' || actual_action == 'ORD_REQ_CANCEL'){
            req_cnl_modal.hide();

            setTimeout(() => {
                processing.show(processing_request_message);
            }, 300);
        }else{
            processing.show(processing_request_message);
        }

        setTimeout(() => {
            if(jsondata){
                aj = new ajax(action_link,jsondata);

                aj.getResponse().then(response => {
                    // Handle ajax response here.
                    //console.log(response);
                    if (response.hasOwnProperty("response_action")) {

                        if(response.response_action == 'reload'){
                            location.reload();
                        }else if(response.response_action == 'show_msg'){
                            processing.hide();
                            setTimeout(() => {
                                alert(response.response_msg);
                                location.reload();
                            }, 300);
                        }else if(response.response_action == 'redirect'){
                            // alert(response.response_msg);
                            window.location.href = response.response_msg;
                        }else if(response.response_action == 'download'){
                            let fileName = response.response_file;
                            var pdf = response.response_msg;
                            var blob = b64toBlob(pdf);
                            const link = document.createElement("a");
                            link.href = window.URL.createObjectURL(blob);
                            link.download = fileName;
                            link.click();
                            processing.hide();
                            document.getElementById(id).removeAttribute('disabled');
                        }else{
                            processing.hide();
                            setTimeout(() => {
                                alert("An error encountered while processing the action, Please try again later.");
                                document.getElementById(id).removeAttribute('disabled');
                            }, 300);
                        }
                    }else{
                        processing.hide();
                        setTimeout(() => {
                            alert("An error encountered while processing the action, Please try again later.");
                            document.getElementById(id).removeAttribute('disabled');
                        }, 300);
                    }
                });
            }
        }, 1000);
    }else{
        document.getElementById(id).removeAttribute('disabled');
    }
}

function set_processing_request_message(action_link){
    let processing_request_message = "";
    switch(action_link) {
        case "CNT_CREATE":
            processing_request_message = "Your contract creation request is being processed.";
            break;
        case "RVD_CANCEL":
            processing_request_message = "Your stand reservation cancellation request is being processed.";
            break;
        case "CNT_SIGN":
            processing_request_message = "Your contract signing request is being processed.";
            break;
        case 'CNT_REQ_CANCEL':
            processing_request_message = "Your contract cancellation request is being processed.";
            break;
        case 'CNT_PDF_DNWD':
            processing_request_message = "Your download contract PDF request is being processed.";
            break;
        case 'ORD_PDF_DNWD':
            processing_request_message = "Your download contract PDF request is being processed.";
            break;
        case 'ORD_REQ_CANCEL':
            processing_request_message = "Your order cancellation request is being processed.";
            break;
        default:
            processing_request_message = "";
            break;
      }

      return (processing_request_message != "") ? '<h4>Please wait...</h4><br/><h5>'+processing_request_message+'</h5>' : '<h4>Please wait...Processing request.</h4><br/>';
}

function WordCount(str) {
    return str
     .split(' ')
     .filter(function(n) { return n != '' })
     .length;
}

function checkMaxlengthAndRestrict(event, value, maxWordsLength) {
    if (value != undefined && WordCount(value.toString()) > maxWordsLength) {
        event.preventDefault();
    }
}

function b64toBlob(b64Data, contentType='application/pdf', sliceSize=512) {
    const byteCharacters = atob(b64Data);
    const byteArrays = [];

    for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
      const slice = byteCharacters.slice(offset, offset + sliceSize);

      const byteNumbers = new Array(slice.length);
      for (let i = 0; i < slice.length; i++) {
        byteNumbers[i] = slice.charCodeAt(i);
      }

      const byteArray = new Uint8Array(byteNumbers);
      byteArrays.push(byteArray);
    }

    const blob = new Blob(byteArrays, {type: contentType});
    return blob;
  }

function request_cancellation(id){
    req_cnl_modal_content = document.getElementById('cancellation_reason');
    req_cnl_modal = new mdb.Modal(req_cnl_modal_content);
    const textarea = document.getElementById('request_cancellation_reason');
    textarea.value ='';
    const lineHeight = parseInt(getComputedStyle(textarea).lineHeight);
    const rows = textarea.rows;
    textarea.style.height = lineHeight * rows +"px";
    textarea.style.maxHeight = lineHeight * 10+"px";
    const cancel_confirm_btn = document.getElementById('cancel_confirm');
    cancel_confirm_btn.setAttribute('onclick', "javascript: manage_actions('" + id + "');" );

    // const modal_content = document.getElementById('cancellation_reason');
    // const modal = new mdb.Modal(modal_content);
    req_cnl_modal.show();
}

function show_cancellation_reason(id){
    const submitted_cancellation_reason = document.getElementById('submitted_cancellation_reason'+id);
    const submitted_reason = submitted_cancellation_reason.value;

    document.getElementById("whole_submitted_reason").innerHTML = submitted_reason;

    const modal_content = document.getElementById('show_cancellation_reason_popup');
    const modal = new mdb.Modal(modal_content);
    modal.show();
}

window.addEventListener("load", (event) => {
    getpackages();
    req_cnl_modal_content = document.getElementById('cancellation_reason');
    req_cnl_modal = new mdb.Modal(req_cnl_modal_content);
});

