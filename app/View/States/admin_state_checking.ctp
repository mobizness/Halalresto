<?php
if(isset($state_detail) && !empty($City_list)) {
    foreach($state_detail as $key=>$value) { ?>
        <option value="<?php echo $key; ?>"><?php echo $value; ?></option> <?php
    }
} else {?>
    <option value="<?php echo "0"; ?>"><?php echo "No State Found"; ?></option> <?php
}?>