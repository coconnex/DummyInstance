<section id="messages_section">
    <!-- Message Modal frame top -->
    <div class="modal frame fade top" id="messages_modal" tabindex="-1" aria-labelledby="messages_modal_top" aria-hidden="true">
        <div class="modal-dialog modal-frame modal-top">
            <div class="modal-content rounded-0">
                <div class="modal-body py-1">
                    <div class="d-flex-column my-3 ms-auto me-auto col-sm-12 col-md-6 ">
                        <?php foreach ($vars['messages'] as $key => $msgs) {;
                            $color = "";
                            $icon = "";

                            switch ($key) {
                                case "status":
                                    $color = "info";
                                    $icon = "fa-chevron-circle-right";
                                    break;
                                case "error":
                                    $color = "danger";
                                    $icon = "fa-times-circle";
                                    break;
                                case "warning":
                                    $color = "warning";
                                    $icon = "fa-exclamation-triangle";
                                    break;
                            }
                            for ($i = 0; $i < sizeof($msgs); $i++) {
                                $msg = $msgs[$i];

                        ?>
                                <div class="alert alert-<?php echo $color; ?>">
                                    <i class="fas <?php echo $icon; ?> me-3"></i><?php echo $msg; ?>
                                </div>
                        <?php
                            }
                            $i = null;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $vars['script']; ?>
    <!-- Message Modal frame top -->
</section>