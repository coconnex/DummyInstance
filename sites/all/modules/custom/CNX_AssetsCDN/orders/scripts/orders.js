let cnclSwitch;
let cnclContainer;

function toggleCancelledOrders(onload = false) {
    if (cnclContainer instanceof HTMLDivElement && cnclSwitch instanceof HTMLInputElement) {
        if ((cnclSwitch.checked === true && onload === true) || (cnclSwitch.checked === false && onload === false)) {
            cnclContainer.classList.remove('invisible');
            cnclContainer.classList.add('visible');
        } else {
            cnclContainer.classList.add('invisible');
            cnclContainer.classList.remove('visible');
        }
    }
}

window.addEventListener('load', (event) => {
    cnclSwitch = document.querySelector('#show_cancelled_switch');
    cnclContainer = document.querySelector('#cancelled_orders_container');
    toggleCancelledOrders(true);
    if (cnclSwitch instanceof HTMLInputElement) {
        cnclSwitch.addEventListener('pointerdown', (event) => { toggleCancelledOrders() });

    }
})