

<div id="status_container">
<?php
foreach($vars['statuses'] as $idx => $status){
    ?>
    <div class="floorplan_legends">
    <div class="leg_block" style="background:<?php echo $status->back_color;?>"></div>
    <div  style="color: <?php echo $status->font_color;?>"> <?php echo $status->description;?></div>
    </div>
    <?
}
?>
</div>