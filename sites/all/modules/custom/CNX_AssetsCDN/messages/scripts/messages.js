const messages = {
    exists: false,
    show(modalId) {
        if (this.exists) {
            const modalElem = document.querySelector('#' + modalId);
            const modal = new mdb.Modal(modalElem)
            modal.show()
        }

    },
}

window.addEventListener("load", (event) => {
    messages.show("messages_modal");
})