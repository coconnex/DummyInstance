 <p class="mystand_card_value"><?php echo $row->additional_info->description; ?></p>
 <p class="mystand_card_value">​Rate £<?php echo number_format($row->additional_info->total / $row->additional_info->quantity); ?> / M<sup>2</sup></p>
 <div class="verticaly_border d-md-none"></div>
 <p class="mystand_card_value"> Price (Ex.VAT)​</p>
 <p class="mystand_card_value"><strong>£<?php echo number_format($row->additional_info->total); ?></strong>​</p>