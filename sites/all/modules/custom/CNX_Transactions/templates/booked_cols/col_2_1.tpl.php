  <p class="mystand_card_value"><?php echo $row->description; ?></p>
  <p class="mystand_card_value">​Rate <strong class="ms-5 ps-4 ps-md-5">£<?php echo number_format($row->rate); ?> / M<sup>2</sup></strong></p>
  <div class="verticaly_border d-md-none"></div>
  <p class="mystand_card_value mt-3"> Price (Ex.VAT) <strong class="ms-3 ms-md-2">£<?php echo number_format($row->total); ?></strong>​</p>
  <!-- <p class="mystand_card_value">​</p> -->