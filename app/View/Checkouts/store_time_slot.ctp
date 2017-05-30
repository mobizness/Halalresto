<option value=""> <?php echo __('Select Time'); ?></option> <?php

if(isset($storeSlots) && !empty($storeSlots)) {
	foreach($storeSlots as $key=>$value) { ?>
        <option value="<?php echo $key; ?>"><?php echo $value; ?></option> <?php
    }
}