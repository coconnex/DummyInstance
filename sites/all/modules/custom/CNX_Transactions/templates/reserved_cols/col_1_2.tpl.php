<div class="col-6 col-xxl-5 d-flex justify-content-end reserve-stand-timer">
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
                        <button type="button" id="contract_creation_btn" class="common_btn_sm" onclick="check_selected_stand_for_contracts('NO')">Book Now</button>
                    </div>
                </div>

            </div>