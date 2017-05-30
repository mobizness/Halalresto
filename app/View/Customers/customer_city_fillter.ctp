<option value=""> <?php echo __('Select City'); ?> </option> <?php
if(isset($City_list) && !empty($City_list)) {
	foreach($City_list as $key=>$value) { ?>
        <option value="<?php echo $key; ?>"><?php echo $value; ?></option> <?php
    }
} ?>