
function updateTransaction(stand_transaction_id){

    const selected = document.getElementById("package"+stand_transaction_id);
    const packageselected = selected.value;
    const quant = document.getElementById("quantity"+stand_transaction_id);
    const quantity = quant.value;
    const trans = document.getElementById("stand_transaction_id"+stand_transaction_id);
    const transaction_id = trans.value;

    let rawdata = products;
    let jsonpackage = {};
    for (let key in rawdata) {
        let obj = rawdata[key];
        if(atob(obj.urn) === packageselected){
            jsonpackage.quantity = quantity;
            jsonpackage.description = obj.description;
            jsonpackage.transaction_id = transaction_id;
            jsonpackage.package_key = obj.short_key;
            jsonpackage.total = obj.unit_price * quantity;
            jsonpackage.rate = obj.unit_price;
            jsonpackage.product_ref = obj.urn;
        }
    }

    if(jsonpackage){
        aj = new ajax("/transaction/update",jsonpackage);

        aj.getResponse().then(response => {
            // Handle ajax response here.
            if (response.hasOwnProperty("status")) {
                if(response.status > 0){
                    alert("Package updated successfully.");
                    document.getElementById("reserved_rate"+stand_transaction_id).innerHTML = Number(jsonpackage.rate);
                    document.getElementById("reserved_total"+stand_transaction_id).innerHTML = (jsonpackage.total).toLocaleString();
                }else{
                    alert("Error encountered while updating package.");
                }
            }else{
                alert("Error encountered while updating package.");
            }
        });
    }

}

function check_selected_stand_for_contracts(){
    var len = document.querySelectorAll('input[type="checkbox"]:checked').length
    if (len <= 0) {
      alert("To proceed with creating the contract, please choose at least one stand.");
    } else {
      document.getElementById("reserved_stand_form").submit();
    }
}

function manage_actions(id){
    let result = confirm("Do you wish to continue with this action? Please confirm.");
    if (result === true) {
        const element = document.getElementById(id);
        let action = id.replace(/[0-9]/g, '');
        const link = element.getAttribute("link");
        const value = element.getAttribute("value");
        const action_link = link;
        let jsondata = {};
        jsondata.transaction_id = value;
        if(action == 'ID_CNT_REQ_CANCEL'){
            var request_cancellation_reason = document.getElementById('request_cancellation_reason').value;
            // console.log(request_cancellation_reason);
            if(request_cancellation_reason != ""){
                jsondata.cancellation_reason = request_cancellation_reason;
            }else{
                alert("Please add reason to submit contract cancellation request");
            }
        }

        if(jsondata){
            aj = new ajax(action_link,jsondata);

            aj.getResponse().then(response => {
                // Handle ajax response here.
                // console.log(response);
                if (response.hasOwnProperty("response_action")) {
                    if(response.response_action == 'show_msg'){
                        alert(response.response_msg);
                        location.reload();
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
                    }else{
                        alert("An error encountered while processing the action, Please try again later.");
                    }
                }else{
                    alert("An error encountered while processing the action, Please try again later..");
                }
            });
        }
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
    const cancel_confirm_btn = document.getElementById('cancel_confirm');
    cancel_confirm_btn.setAttribute('onclick', "javascript: manage_actions('" + id + "');" );

    const modal_content = document.getElementById('cancellation_reason');
    const modal = new mdb.Modal(modal_content);
    modal.show();
}


