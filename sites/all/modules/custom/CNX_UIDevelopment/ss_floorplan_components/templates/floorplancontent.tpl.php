<!--****************** Toolbar Begins ***********************-->
<section id="ss_floorplan_toolbar_content">
    <div id="fp_toolbar_container" class="container-md">
        <div class="row d-flex" id="fp_toolbar_row">
            <div id="fp_toolbar_info_container" class="col-3 text-white d-flex align-items-center pt-2 pb-2">
                <div id="fb_toolbar_info_content" class="d-flex flex-column col-12">
                    <div id="fb_toolbar_info" class="h4 text-center text-truncate">Information</div>
                    <div id="fb_toolbar_info_summary" class="d-flex flex-row">
                        <div title="Status" class="col-4 badge rounded-pill badge-light me-1 fs-6 text-truncate">Status</div>
                        <div title="Zones" class="col-4 badge rounded-pill badge-light me-1 fs-6 text-truncate">Zones</div>
                        <div title="Actions" class="col-4 badge rounded-pill badge-light fs-6 text-truncate">Actions</div>
                    </div>
                </div>
            </div>
            <div id="fp_toolbar_search_container" class="col-6 d-flex align-items-center">
                <div id="fp_toolbar_search_content" class="col-12 d-flex flex-row">
                    <div class="col-11">Stand Name, Number & Area </div>
                    <div class="col-1 border-0">
                        <i class="fas fa-search fa-lg text-black"></i>
                    </div>
                </div>
            </div>
            <div title="Added 1 for £6,000" id="fp_toolbar_minicart_container" class="col-3 d-flex flex-row align-items-center">
                <div class="d-flex flex-row align-items-center me-auto ms-auto">
                    <div class="me-4">
                        <i class="fas fa-cart-shopping fa-3x text-white"></i>
                        <span class="badge rounded-pill badge-notification bg-secondary">1</span>
                    </div>
                    <div title="Reserve for £6,000" class="text-white h5 text-end overflow-hidden">
                        Reserve £6,000
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Toolbar Modals Begin -->
<section id="fp_toolbar_info_modal_section">
    <!-- *********************legends modal start*********************************** -->
    <div class="row">
        <div class="modal fade" id="fp_toolbar_info_modal" aria-hidden="true" aria-labelledby="fp_toolbar_info_modal_label" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalToggleLabel3">Stand Information
                        </h5>
                        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex">
                        <ul id="legends_status" class="list-group list-group-light col-4">
                            <li class="list-group-item border-0 h4">Status</li>
                            <li class="list-group-item border-0">
                                <i class="far fa-circle fa-lg" style="color:black;"></i>
                                <span class="legend">Available</span>
                                <span class="legend_item_count">(99)</span>
                            </li>
                            <li class="list-group-item border-0">
                                <i class="fas fa-circle fa-lg" style="color:Green"></i>
                                <span class="legend">Reserved</span>
                                <span class="legend_item_count">(99)</span>
                            </li>
                            <li class="list-group-item border-0">
                                <i class="fas fa-circle fa-lg" style="color:Purple"></i>
                                <span class="legend">Contracted</span>
                                <span class="legend_item_count">(99)</span>
                            </li>
                            <li class="list-group-item border-0">
                                <i class="fas fa-circle fa-lg" style="color:Grey"></i>
                                <span class="legend">Blocked</span>
                                <span class="legend_item_count">(99)</span>
                            </li>
                        </ul>
                        <ul id="legends_zones" class="list-group list-group-light col-4">
                            <li class="list-group-item border-0 h4">Zones</li>
                            <li class="list-group-item border-0">
                                <i class="fas fa-circle fa-lg" style="color:Green"></i>
                                <span class="legend">Home</span>
                                <span class="legend_item_count">(9)</span>
                            </li>
                            <li class="list-group-item border-0">
                                <i class="fas fa-circle fa-lg" style="color:Green"></i>
                                <span class="legend">Fashion</span>
                                <span class="legend_item_count">(20)</span>
                            </li>
                            <li class="list-group-item border-0">
                                <i class="fas fa-circle fa-lg" style="color:Green"></i>
                                <span class="legend">Furniture</span>
                                <span class="legend_item_count">(40)</span>
                            </li>
                        </ul>
                        <ul id="legends_actions" class="list-group list-group-light col-4">
                            <li class="list-group-item border-0 h4">Actions</li>
                            <li class="list-group-item border-0">
                                <i class="fas fa-circle fa-lg" style="color:Pink"></i>
                                <span class="legend">Searched</span>
                                <span class="legend_item_count">(20)</span>
                            </li>
                            <li class="list-group-item border-0">
                                <i class="fas fa-table-cells fa-lg" style="color:green"></i>
                                <span class="legend">Waitlisted</span>
                                <span class="legend_item_count">(20)</span>
                            </li>
                        </ul>
                        <!--div class="row">
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <div class="pb-3">
                                <h4>Status</h4>
                            </div>
                            <p class="d-flex text-black"><span class="mystand_available"></span> &nbsp; Available</p>
                            <p class="d-flex text-black"><span class="mystand_blocked"></span> &nbsp; Blocked</p>
                            <p class="d-flex text-black"><span class="mystand_reserved"></span> &nbsp; Reserved</p>
                            <p class="d-flex text-black"><span class="mystand_booked"></span> &nbsp; Booked</p>
                            <p class="d-flex text-black"><span class="mystand_waitinglist"></span> &nbsp; Waiting List</p>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <div class="pb-3">
                                <h4>Zones</h4>
                            </div>
                            <p class="d-flex text-black"><i class="fas fa-circle legends_circle"></i>&nbsp; Available</p>
                            <p class="d-flex text-black"><i class="fas fa-circle legends_circle"></i>&nbsp;Blocked</p>
                            <p class="d-flex text-black"><i class="fas fa-circle legends_circle"></i>&nbsp; Reserved</p>
                            <p class="d-flex text-black"><i class="fas fa-circle legends_circle"></i>&nbsp; Booked</p>
                            <p class="d-flex text-black"><i class="fas fa-circle legends_circle"></i>&nbsp; Waiting List</p>

                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <div class="pb-3">
                                <h4>Actions</h4>
                            </div>
                        </div>
                    </div-->
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- *********************legends modal end*********************************** -->
</section>
<section id="fp_toolbar_search_modal_section">
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

</section>
<section id="fp_toolbar_cart_modal_section">
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
</section>
<!-- Toolbar Modals End -->
<!--****************** Toolbar Ends ***********************-->

<!--****************** Floorplan Controls Begins ***********************-->
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

<!--****************** Floorplan Controls Ends ***********************-->