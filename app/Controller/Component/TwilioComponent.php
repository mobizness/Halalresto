<?php

App::import('Vendor', 'Twilio.twilio/Services/Twilio');

class TwilioComponent extends Component{
	
	protected $_twilio;
	
	protected $_from;
	
	function __construct()
		{	
			//load config
			//Configure::load('Twilio.Twilio');

			//get settings from config
			$AccountSid  = Configure::read('Twilio.AccountSid');
			$AuthToken 	 = Configure::read('Twilio.AuthToken');
			$this->_from = Configure::read('Twilio.from');
			
			//initialize the client
			//$this->_twilio = new TwilioRestClient($this->account_sid, $this->auth_token);
			$this->_twilio = new Services_Twilio($AccountSid, $AuthToken);
		}

	
	public function sendSingleSms($to, $message) {

		$this->__construct();
		
		$sms = $this->_twilio->account->sms_messages->create(
		    $this->_from, // From this number
		    $to, // To this number
		    $message
		);

		$responseMessage = (isset($sms->body)) ? $sms->body : $sms->message;
		$filePath      = ROOT.DS.'app'.DS."tmp".DS.'twilioSms.txt';
        $file = fopen($filePath,"a+");
        fwrite($file, PHP_EOL.'Message---->'.$message.PHP_EOL.'Response---->'.$responseMessage.PHP_EOL);
        fclose($file);
	}
	
	public function sendMultipleSms($to = array(), $message) {

		$people = array(
        	$to => "Name 1",
        	$to => "Name 2",
        	$to => "Name 3",
    	);
    	
		foreach ($people as $number => $name) {
	 
	        $sms = $this->_twilio->account->sms_messages->create(
	            $this->_from, 
	            $number,
	 
	            // the sms body
	            "Hi $name, this is a test!"
	        );
	 
	        echo "Sent message to $name <br />";
	    }
				
	}

}