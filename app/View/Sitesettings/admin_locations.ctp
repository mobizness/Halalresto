<option value=""> Select <?php echo $model; ?></option> <?php

if(isset($locations) && !empty($locations)) {
	foreach($locations as $key=>$value) { ?>
        <option value="<?php echo $key; ?>"><?php echo $value; ?></option> <?php
    }
}