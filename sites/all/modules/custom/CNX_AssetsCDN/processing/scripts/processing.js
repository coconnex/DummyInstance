let modalElem;
let modal;
let messagecontent;

const processing = {

    show(message) {
        messagecontent = modalElem.querySelector('#message_container');
        messagecontent.innerHTML = message;
        modal.show();
    },

    hide() {
        modal.hide();
    },
}

window.addEventListener("load", (event) => {

    modalElem = document.querySelector('#processing_popup_modal');
    modal = new mdb.Modal(modalElem);
    // processing.show('<h4>Please wait...</h4><br/><h5>Your order cancellation request is being proccessed.</h5>');

    // setTimeout(() => {
    //     processing.hide();
    // }, 1000);
})
