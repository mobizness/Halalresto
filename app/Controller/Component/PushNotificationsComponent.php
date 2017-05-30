<?php

App::uses('Component', 'Controller');

class PushNotificationsComponent extends Component {

	public function notificationIOS($message, $gcmId, $orderDetails = array()) {
		
		/*$message = 'Hi IOS Mobile';
		$gcmId	 = 'f5393b6397bf12808abcae8f1b5c5f5911809c563e5ccebc2f3247f33a3f5288';*/
		// Provide the Host Information.

		$tHost = 'gateway.sandbox.push.apple.com';

		// $tHost = 'gateway.push.apple.com';

		$tPort = 2195;

		// Provide the Certificate and Key Data.

		$tCert = APP . 'Vendor' . DS . 'development.pem';

		// $tCert = APP . 'Vendor' . DS . 'production.pem';

		// Provide the Private Key Passphrase (alternatively you can keep this secrete

		// and enter the key manually on the terminal -> remove relevant line from code).

		// Replace XXXXX with your Passphrase

		$tPassphrase = '123456';

		// Provide the Device Identifier (Ensure that the Identifier does not have spaces in it).

		// Replace this token with the token of the iOS device that is to receive the notification.

		$tToken = $gcmId; //'26d8144c71be0d1d4c20db66c3c22d646bb3dd654ee46c9fc5170fc6a9755168'; // Gcm id

		// The message that is to appear on the dialog.

		$tAlert = $message; //'Alert';

		// The Badge Number for the Application Icon (integer >=0).

		$tBadge = 1;

		// Audible Notification Option

		$tSound = 'default';

		// The content that is returned by the LiveCode "pushNotificationReceived" message.

		$tPayload = (!empty($orderDetails)) ? $orderDetails : $message;  // Message

		// Create the message content that is to be sent to the device.

		$tBody['aps'] = array (

		'alert' => $tAlert,

		'badge' => $tBadge,

		'sound' => $tSound,

		);

		$tBody ['payload'] = $tPayload;

		// Encode the body to JSON.

		$tBody = json_encode ($tBody);

		// Create the Socket Stream.

		$tContext = stream_context_create ();

		stream_context_set_option ($tContext, 'ssl', 'local_cert', $tCert);

		// Remove this line if you would like to enter the Private Key Passphrase manually.

		stream_context_set_option ($tContext, 'ssl', 'passphrase', $tPassphrase);

		// Open the Connection to the APNS Server.

		$tSocket = stream_socket_client ('ssl://'.$tHost.':'.$tPort, $error, $errstr, 60, STREAM_CLIENT_CONNECT, $tContext);

		//echo "<pre>"; print_r($tSocket);



		// Check if we were able to open a socket.

		if (!$tSocket)

		exit ("APNS Connection Failed: $error $errstr" . PHP_EOL);

		// Build the Binary Notification.

		$tMsg = chr (0) . chr (0) . chr (32) . pack ('H*', $tToken) . pack ('n', strlen ($tBody)) . $tBody;

		// Send the Notification to the Server.

		$tResult = fwrite ($tSocket, $tMsg, strlen ($tMsg));


		

		if ($tResult)

		//echo 'Delivered Message to APNS' . PHP_EOL;
		return '{"success":1}';

		else
		return '{"failed":1}';

		//echo 'Could not Deliver Message to APNS' . PHP_EOL;

		// Close the Connection to the Server.

		fclose ($tSocket);
	}
}