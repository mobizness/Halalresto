<option value=""> Select Location</option> <?php
if(isset($area_list) && !empty($area_list)) {
    foreach($area_list as $key=>$value) { ?>
        <option value="<?php echo $key; ?>"><?php echo $value; ?></option> <?php
    }
} else {?>
    <option value="<?php echo "0"; ?>"><?php echo "No Location Found"; ?></option> <?php
}?>