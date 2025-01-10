<span class="heading">
    <h5><strong>Items</strong></h5>
</span>

<div class="myorder_card">
  <p class="myorder_card_value">
<?php if(is_array($row->items) && sizeof($row->items) > 0){
     $k = 1;
    foreach($row->items as $item){
      if($item->item_type != 'FEES'){
      ?>
      <strong><?php echo $k.'-'.$item->additional_info->stand_number.' : '.$item->quantity; ?> m</strong><sup>2</sup>
      @£<?php echo $item->unit_price; ?>/m<sup>2</sup>
      <br/>
      <?php
      $k++;
      }
    }
}
?>
</p>
</div>
