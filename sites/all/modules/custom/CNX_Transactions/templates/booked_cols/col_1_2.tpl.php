<div class="col-5 col-md-6 col-xl-5 d-flex justify-content-end">
                <div class="booking-timer" id="bookingtimer">
                    <div class="row booking-timer-heading">
                            RESERVATION EXPIRING IN
                    </div>
                    <div class="row">
                        <div class="container booking-timer-countdown">
                            <div class="d-flex remaining-time-value">
                                <div class="part-value" id="numdays">00</div>
                                <div class="divider">:</div>
                                <div class="part-value" id="numhrs">00</div>
                                <div class="divider">:</div>
                                <div class="part-value" id="nummin">00</div>
                                <div class="divider">:</div>
                                <div class="part-value" id="numsec">00</div>
                            </div>
                            <div class="d-flex remaining-time-label">
                                <div class="part-label">Days</div>
                                <div class="divider"></div>
                                <div class="part-label">Hrs</div>
                                <div class="divider"></div>
                                <div class="part-label">Mins</div>
                                <div class="divider"></div>
                                <div class="part-label">Secs</div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <?php $remaining_time = $intervals->d.':'.$intervals->h.':'.$intervals->i.':'.$intervals->s; ?>
                        <input type="hidden" id="remaining_booking_time" value="<?php echo $remaining_time; ?>" />
                        <button type="button" class="common_btn_sm" value="<?php echo $row->stand_transaction_id; ?>" link="/mystands/action/CNT_SIGN" onclick="manage_actions('ID_CNT_SIGN<?php echo $row->stand_transaction_id; ?>')">Sign Contract</button>
                    </div>
                </div>

            </div>