<div class="row">

    <div class="modal fade" id="fp_toolbar_info_modal" aria-hidden="true" aria-labelledby="fp_toolbar_info_modal_label" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <div class="tab-section">
                        <ul class="nav-tabs mb-3 d-flex" id="ex-with-icons" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a data-mdb-tab-init class="nav-link active" id="ex-with-icons-tab-1" href="#ex-with-icons-tabs-1" role="tab" aria-controls="ex-with-icons-tabs-1" aria-selected="true">Status Legends</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a data-mdb-tab-init class="nav-link" id="ex-with-icons-tab-2" href="#ex-with-icons-tabs-2" role="tab" aria-controls="ex-with-icons-tabs-2" aria-selected="false">Packages</a>
                            </li>
                        </ul>
                    </div>

                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="tab-content" id="ex-with-icons-content">
                    <div class="tab-pane fade show active" id="ex-with-icons-tabs-1">
                        <div class="modal-body d-flex-column col-12">
                            <div id="legends_status_container" class="legends-container">
                                <div id="legend_status_header">
                                    <div id="legend_status_header_title" class="legend-group-title">Status</div>
                                    <div id="legend_status_header_show_cntrl"></div>
                                </div>
                                <div id="legend_status_content">
                                    <ul id="legends_status" class="legend-flex"></ul>
                                </div>
                            </div>
                            <div id="legends_zone_container" class="legends-container">
                                <div id="legend_zone_header">
                                    <div id="legend_zone_header_title" class="legend-group-title">Zones</div>
                                    <div id="legend_zone_header_show_cntrl"></div>
                                </div>
                                <div id="legend_zone_content">
                                    <ul id="legends_zone" class="legend-flex"></ul>
                                </div>
                            </div>
                            <div id="legends_action_container" class="legends-container">
                                <div id="legend_action_header">
                                    <div id="legend_action_header_title" class="legend-group-title">Actions</div>
                                    <div id="legend_action_header_show_cntrl"></div>
                                </div>
                                <div id="legend_action_content">
                                    <ul id="legends_action" class="legend-flex">
                                        <li class="legend" style="background-color:var(--search_col)">
                                            <span>Searched</span>
                                            <span class="legend_item_count" id="searched_counts">(0)</span>
                                        </li>
                                        <li class="legend" style="background-color:green">
                                            <span>Waitlisted</span>
                                            <span class="legend_item_count" id="waitlisted_counts">(0)</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade p-5" id="ex-with-icons-tabs-2">
                        <?php include_once(dirname(__FILE__) . "/applicablepackagelist.tpl.php") ?>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>