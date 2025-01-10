<section id="ss_floorplan_toolbar_content">
    <div id="fp_toolbar_container" class="container-md">
        <div class="d-flex" id="fp_toolbar_row">
            <div id="fp_toolbar_info_container" class="col-3 text-white d-flex align-items-center">
                <div id="fb_toolbar_info_content" class="d-flex flex-column col-12">
                    <div id="fb_toolbar_info" class="text-start ms-3 pt-1 pb-1">
                        <i class="fas fa-circle-info fa-3x d-sm-inline d-lg-none"></i>
                        <i class="fas fa-circle-info fa-lg d-none d-lg-inline"></i>
                        <span class="d-none d-lg-inline text-uppercase">Information</span>
                    </div>
                    <div id="fb_toolbar_info_summary" class="text-uppercase pt-1 pb-1 d-none d-lg-flex">
                        <div title="Status" class="col-4 text-truncate d-none d-lg-inline text-center">Status</div>
                        <div class="seprator-border"></div>
                        <div title="Packages" class="col-4 text-truncate d-none d-lg-inline text-center">Packages</div>
                    </div>
                </div>
            </div>
            <div id="fp_toolbar_search_container" class="col-6 d-flex align-items-center">
                <div id="fp_toolbar_search_content" class="col-12 d-flex flex-row">
                    <div class="col-11" id="search_tags"></div>
                    <div class="col-1 border-0" id="search_button">
                        <i class="fas fa-search fa-2x text-white"></i>
                    </div>
                </div>
            </div>
            <div id="fp_toolbar_minicart_container" class="col-3 d-flex flex-row align-items-center">
                <div class="d-flex flex-row col-12 ps-2 pe-2">
                    <div class="d-inline-flex col-6">
                        <i class="fas fa-cart-shopping fa-3x"><span id="cart_count">0</span></i>
                    </div>
                    <div title="Reserve for £6,000" class="text-uppercase text-end overflow-hidden d-none d-lg-inline pe-2 col-6">
                        <p class="mb-0">Reserve</p> <span id="cart_total" class="fs-3 lh-1">£0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- *********************legends modal start*********************************** -->

    <!-- *********************legends modal end*********************************** -->
    <div class="row modal_box mt-5">
        <div class="col-sm-12 col-md-3 col-lg-3"></div>
        <!-- *********************search modal start*********************************** -->
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="modal fade" id="exampleModalToggle1" aria-hidden="true" aria-labelledby="exampleModalToggleLabel1" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalToggleLabel1">What are you looking for?
                            </h5>
                            <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-outline mt-2 mb-3" data-mdb-input-init>
                                <input type="text" id="form12" class="form-control" />
                                <label class="form-label" for="form12">Name: Google</label>
                            </div>
                            <div class="form-outline mt-2 mb-3" data-mdb-input-init>
                                <input type="text" id="form12" class="form-control" />
                                <label class="form-label" for="form12">Number: B200
                                </label>
                            </div>
                            <div class="form-outline mt-2 mb-3" data-mdb-input-init>
                                <input type="text" id="form12" class="form-control" />
                                <label class="form-label" for="form12">Area (+/- 10 sqm): 25</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col-sm-12">
                                <button class="btn bg-secondary text-white w-100 mt-3 mb-3">
                                    Clear all
                                </button>
                                <button class="btn bg-secondary text-white w-100">
                                    Search
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- *********************search modal end*********************************** -->
        <!-- *********************cart modal start*********************************** -->
        <div class="col-sm-12 col-md-3 col-lg-3">
            <div class="modal fade cart_modal" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-2">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header ">
                            <i class="fas fa-cart-shopping fa-2x" style="margin-right: 15px;"></i>
                            <h5 class="modal-title" id="exampleModalToggleLabel2"> Your selections</h5>
                            <button type="button" class="btn-close " data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-outline mt-2 mb-3" data-mdb-input-init>
                                <input type="text" id="form12" class="form-control" />
                            </div>
                            <div class="form-outline mt-2 mb-3" data-mdb-input-init>
                                <input type="text" id="form12" class="form-control" />
                            </div>
                            <div class="form-outline mt-2 mb-3" data-mdb-input-init>
                                <input type="text" id="form12" class="form-control" />
                            </div>
                            <div class="form-outline mt-2 mb-3" data-mdb-input-init>
                                <input type="text" id="form12" class="form-control" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn reserve_btn w-100">
                                Confirm Reserve
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- *********************cart modal end*********************************** -->
    </div>
</section>
<section id="fp_controls_section">
    <div id="fp_controls_container">
        <div id="fp_controls_items" class="fp_control_items_hide">
            <div id="fp_control_item_zmin"><i class="fas fa-magnifying-glass-plus"></i></div>
            <div id="fp_control_item_zmout"><i class="fas fa-magnifying-glass-minus"></i></div>
            <div id="fp_control_item_reset"><i class="fas fa-down-left-and-up-right-to-center"></i></div>
        </div>
        <button id="fp_controls_nav_button" type="button">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</section>