<table style="font-size: 8pt;">
    <?php
    foreach($vars as $subarray){
    ?>
    <tr>
        <?php
        if(isset($subarray['col1'])){
        ?>
        <td <?php echo (isset($subarray['col1_attr']) ? "style='".$subarray['col1_attr']."'" : '');?>><?php echo $subarray['col1']; ?></td>
        <?php
        }
        ?>

        <?php
        if(isset($subarray['col2'])){
        ?>
        <td <?php echo (isset($subarray['col2_attr']) ? "style='".$subarray['col2_attr']."'" : '');?>><?php echo $subarray['col2']; ?></td>
        <?php
        }
        ?>

        <?php
        if(isset($subarray['col3'])){
        ?>
        <td><?php echo $subarray['col3']; ?></td>
        <?php
        }
        ?>
    </tr>
    <?php
    }
    ?>
</table>