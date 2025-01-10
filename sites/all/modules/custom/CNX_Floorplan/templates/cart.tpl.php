<div class="modal fade cart_modal" id="cart" aria-hidden="true" aria-labelledby="cart_label" tabindex="-2">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header ">
                <i class="fas fa-cart-shopping fa-2x" style="margin-right: 15px;"></i>
                <h5 class="modal-title" id="cart_label"> Your Selection</h5>
                <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="content">
                <ul id="cart_items"></ul>
                <div id="cart_msg">Please add items by clicking on stands on the floor plan</div>
            </div>
            <div class="modal-footer">
                <button class="btn bg-dark w-100 text-white mt-2" id="btn_book">
                    Book now
                </button>
            </div>
        </div>
    </div>
</div>