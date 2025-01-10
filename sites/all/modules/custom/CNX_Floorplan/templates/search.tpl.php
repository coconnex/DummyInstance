<div class="modal fade" id="search_container" aria-hidden="true" aria-labelledby="search_container_label" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="search_container_label">What are you looking for?
                </h5>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-check form-switch" style="padding-left: 0em;">
                    <input class="form-check-input" type="checkbox" role="switch" id="chx_avai_stands" checked />
                    <label class="form-check-label" for="chx_avai_stands">Search only available stands</label>
                </div>
                <div class="form-outline mt-2 mb-3" data-mdb-input-init>
                    <input type="text" name="txtexhibname" id="txtexhibname" class="form-control" />
                    <label class="form-label" for="form12">Name</label>
                </div>
                <div class="form-outline mt-2 mb-3" data-mdb-input-init>
                    <input type="text" name="txtstandno" id="txtstandno" class="form-control" />
                    <label class="form-label" for="form12">Number</label>
                </div>
                <div class="form-outline mt-2 mb-3" data-mdb-input-init>
                    <input type="text" name="txtarea" id="txtarea" class="form-control" />
                    <label class="form-label" for="form12">Area (+/- 10 sqm)</label>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-sm-12">
                    <button class="btn bg-dark w-100 text-white mt-2" name="btnsearch" id="btnsearch">
                        Search
                    </button>
                    <button class="btn btn-tertiary w-100 text-black mt-2" name="btnsearch_reset" id="btnsearch_reset">
                        Clear all
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>