<?php
    // debug($vars,1);
    include "includes.tpl.php";
    include "floorplancontent.tpl.php";
    include "stand_info.tpl.php";
    include "package_detail.tpl.php";
    include "search.tpl.php";
?>

<!-- <div><h5 class=" heading_color">Floor Plan</h5></div> -->
<div id="fp_container">
    <div id="floorplan" class="zoomable moveable">
        <div id="fpbg">
            <img id="img_bg" src="<?php echo $vars['fp_bg_svg']; ?>" />
        </div>
        <div id="fpex">
            <?php echo $vars['fp_ex_svg']; ?>
        </div>
        <div id="fpfg">
            <img id="img_fg" src="<?php echo $vars['fp_fg_svg']; ?>" />
        </div>
        <div id="fpev">
            <?php echo $vars['fp_ev_svg']; ?>
        </div>
    </div>
</div>
<?php
    // include "legends.tpl.php";
?>
<div id="popup_tooltip" class="exhib_popup"></div>
<script>
    let cartobj = JSON.parse('<?php echo $vars['cart'] ?>');
    let products = JSON.parse('<?php echo $vars['products']; ?>');
    let statuses = JSON.parse('<?php echo $vars['statuses']; ?>');
    let stands_count = <?php echo $vars['stands_count']; ?>;
    let is_waitinglist = <?php echo $vars['is_waitinglist']; ?>;
    let is_showstandname = <?php echo $vars['is_showstandname']; ?>;
</script>
<?php
    include "cart.tpl.php";
?>