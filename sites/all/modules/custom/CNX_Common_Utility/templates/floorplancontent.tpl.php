<div class="min_height_content" id="fp_toolbar_container">
    <div class="row floorplan_toolbar d-flex " id="fp_toolbar_row">
        <div class="col-sm-12 col-md-3 col-lg-3 floorplan_stand_legend p-2 text-end" id="fp_toolbar_legends_container">
            <a href="#exampleModalToggle3" data-mdb-ripple-init data-mdb-modal-init>
                <div id="fb_toolbar_legends_content">
                    <div class="text-white h4 text-center">Information</div>
                    <ul class="list-group list-group-horizontal legends_summary">
                        <li class="text-white text-truncate">Status</li>
                        <li class="text-white text-truncate">Zones</li>
                        <li class="text-white text-truncate">Actions</li>
                    </ul>
                </div>
            </a>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 p-2">
            <a href="#" class="text-black">
                <div class="floorplan_comman_topspace">
                    <div class="input-group rounded justify-content-between">
                        <label for="">Stand Name, Number & Area </label>
                        <a href="#exampleModalToggle1" data-mdb-ripple-init data-mdb-modal-init>
                            <div class="border-0" id="search-addon">
                                <i class="fas fa-search fa-lg text-black"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-12 col-md-3 col-lg-3 p-2 floorplan_cart">
            <div class="d-flex justify-content-around">
                <a href="#exampleModalToggle2" data-mdb-ripple-init data-mdb-modal-init>
                    <i class="fas fa-cart-shopping fa-3x text-white"></i>
                    <span class="badge rounded-pill badge-notification bg-secondary">1</span>
                </a>
                <div class="text-white">
                    <h5>Reserve <br> £6,000</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- *********************legends modal start*********************************** -->
    <div class="row">
        <div class="modal fade" id="exampleModalToggle3" aria-hidden="true" aria-labelledby="exampleModalToggleLabel3" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalToggleLabel3">Stand Information
                        </h5>
                        
                        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex">
                        <ul id="legends_status"class="list-group list-group-light col-4">
                            <li class="list-group-item border-0 h4">Status</li>
                            <li class="list-group-item border-0">
                                <i class="far fa-circle fa-lg" style="color:black;"></i>
                                <span class="legend">Available</span>
                                <span class="legend_item_count">(99)</span>
                            </li>
                            <li class="list-group-item border-0">
                                <i class="fas fa-circle fa-lg" style="color:Green"></i>
                                <span class="legend">Reserved</span>
                                <span class="legend_item_count">(99)</span></li>
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
                        <ul id="legends_zones"class="list-group list-group-light col-4">
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
                        <ul id="legends_actions"class="list-group list-group-light col-4">
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
</div>