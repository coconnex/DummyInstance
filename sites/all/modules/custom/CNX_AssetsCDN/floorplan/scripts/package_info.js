const packageInfo = {
    containerId: "package_detail_model",
    iframeElem:null,
    iframeId: "package_detail_iframe",
    closeButton: null,
    documentContainer: null,
    modal: null,
    setModal: function (containerId = null) {
        let container = document.querySelector('#' + ((containerId) ? containerId : this.containerId));
        this.documentContainer = container.querySelector('.package-details');
        this.closeButton = container.querySelector('.modal-header button');
        this.modal = new mdb.Modal(container);        
    },
    show: function (source, title) {
       
        if (this.modal && source) {
            if (this.documentContainer instanceof HTMLDivElement) {
                let packageName = document.getElementById('packagr_name');
                packageName.innerText = title;
                this.addIFrame(source);                
                this.modal.show();
            }
        }
    },    
    addIFrame(source){
        this.removeIFrame();
        if(this.documentContainer){
            this.iframeElem = document.createElement('iframe');
            this.iframeElem.id = this.iframeId;
            this.iframeElem.classList.add('w-100');
            this.iframeElem.src = source;   
            this.documentContainer.appendChild(this.iframeElem);
        }
    },
    removeIFrame(){
        if(this.iframeElem instanceof HTMLIFrameElement){
            this.iframeElem.parentNode.removeChild(this.iframeElem)
        }
    }

}

window.addEventListener("load", (event) => {
    packageInfo.setModal();
})

