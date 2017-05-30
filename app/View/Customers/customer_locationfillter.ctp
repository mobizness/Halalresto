<option value="">  Select Area/Zip</option> <?php
if(isset($location_list) && !empty($location_list)) {
	foreach($location_list as $key=>$value) { ?>
        <option value="<?php echo $key; ?>"><?php echo $value; ?></option> <?php
    }
} ?>