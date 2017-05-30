<div class="form-horizontal">
	<div class="form-group">
		<label for="guestCount" class="col-sm-4 control-label name">
				Réserver une table Id : </label>
		<div class="col-sm-7 padding-t-7"> <?php
			echo $bookaTable['BookaTable']['booking_id']; ?>
		</div>
	</div>

	<div class="form-group">
		<label for="guestCount" class="col-sm-4 control-label name">
				<?php echo __('Guest Count'); ?> : </label>
		<div class="col-sm-7 padding-t-7"> <?php
			echo $bookaTable['BookaTable']['guest_count']; ?>
		</div>
	</div>


	<div class="form-group">
		<label for="guestCount" class="col-sm-4 control-label name">
				Nom Client : </label>
		<div class="col-sm-7 padding-t-7"> <?php
			echo $bookaTable['BookaTable']['customer_name']; ?>
		</div>
	</div>


	<div class="form-group">
		<label for="guestCount" class="col-sm-4 control-label name">
				<?php echo __('Email'); ?> : </label>
		<div class="col-sm-7 padding-t-7"> <?php
			echo $bookaTable['BookaTable']['booking_email']; ?>
		</div>
	</div>

	<div class="form-group">
		<label for="guestCount" class="col-sm-4 control-label name">
				Téléphone : </label>
		<div class="col-sm-7 padding-t-7"> <?php
			echo $bookaTable['BookaTable']['booking_phone']; ?>
		</div>
	</div>

	<div class="form-group">
		<label for="guestCount" class="col-sm-4 control-label name">
				Date Réservation : </label>
		<div class="col-sm-7 padding-t-7"> <?php 
			echo date("Y/d/m", strtotime($bookaTable['BookaTable']['booking_date']));?>
		</div>
	</div>

	<div class="form-group">
		<label for="guestCount" class="col-sm-4 control-label name">
				Heure Réservation : </label>
		<div class="col-sm-7 padding-t-7"> <?php
			echo $bookaTable['BookaTable']['booking_time']; ?>
		</div>
	</div>


	<div class="form-group">
		<label for="guestCount" class="col-sm-4 control-label name">
				Statut : </label>
		<div class="col-sm-7 padding-t-7"> <?php
			echo $bookaTable['BookaTable']['status']; ?>
		</div>
	</div> <?php
	if ($bookaTable['BookaTable']['status'] == 'Cancel') { ?>
		<div class="form-group">
			<label for="guestCount" class="col-sm-4 control-label name">
				Cancel reason : </label>
			<div class="col-sm-7 padding-t-7"> <?php
				echo $bookaTable['BookaTable']['cancel_reason']; ?>
			</div>
		</div> <?php
	}
	
	if ($bookaTable['BookaTable']['booking_instruction'] != '') { ?>

		<div class="form-group">
			<label for="guestCount" class="col-sm-4 control-label name">
				Instructions :</label>
			<div class="col-sm-7 padding-t-7"> <?php
				echo $bookaTable['BookaTable']['booking_instruction']; ?>
			</div>
		</div> <?php
	} ?>
</div>