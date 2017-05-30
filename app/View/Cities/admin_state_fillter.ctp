<option value=""> <?php echo __('Select State')?></option> <?php
if(isset($State_list) && !empty($State_list)) {
	foreach($State_list as $key=>$value) { ?>
        <option value="<?php echo $key; ?>"><?php echo $value; ?></option> <?php
    }
} ?>