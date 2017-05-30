<option value=""> <?php echo __('Select Area / Zipcode'); ?></option> <?php

if(isset($locations) && !empty($locations)) {
	foreach($locations as $key=>$value) { ?>
        <option value="<?php echo $key; ?>"><?php echo $value; ?></option> <?php
    }
}