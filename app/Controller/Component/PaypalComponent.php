<?php
App::uses('Component', 'Controller');
class PaypalComponent extends Component {

////////////////////////////////////////////////////////////

    public $components = array('Session');

////////////////////////////////////////////////////////////

    public $controller;

////////////////////////////////////////////////////////////

    

//////////////////////////////////////////////////

    public function __construct(ComponentCollection $collection, $settings = array()) {
        $this->controller = $collection->getController();
        parent::__construct($collection, array_merge($this->settings, (array)$settings));
    }

////////////////////////////////////////////////////////////

    public function initialize(Controller $controller) {

        
        $this->version = 64;
        $this->SandboxFlag = true;

        $this->returnURL = '/orders/paymentsuccess';
        $this->cancelURL = '/orderreviews/';
        $this->paymentType = 'Sale';
        $this->currencyCodeType = 'DKK';
        $this->sBNCode = 'PP-ECWizard';
        
        $store_id   = $this->controller->Auth->user("store_id");
        $role_id    = $this->controller->Auth->user("role_id");
        $Sitesetting  = ClassRegistry::init('Sitesetting');

        
        if ($role_id==4) {

            $paypaldetails  = $Sitesetting->find("first",array("conditions"=>array("Sitesetting.id"=>1),
                                                "fields" => array('Sitesetting.paypal_mode','Sitesetting.paypal_test_username',
                                                    'Sitesetting.paypal_test_password','Sitesetting.paypal_test_signature',
                                                    'Sitesetting.paypal_live_username','Sitesetting.paypal_live_password',
                                                    'Sitesetting.paypal_live_signature')));
            $this->returnURL = '/orders/paymentsuccess';
            $this->cancelURL = '/orders/paymentfail';
        }


        if(isset($paypaldetails)) {

            if($paypaldetails['Sitesetting']['paypal_mode'] == 'Live') {
                $this->API_UserName     = $paypaldetails['Sitesetting']['paypal_live_username'];
                $this->API_Password     = $paypaldetails['Sitesetting']['paypal_live_password'];
                $this->API_Signature    = $paypaldetails['Sitesetting']['paypal_live_signature'];

                $this->API_Endpoint = 'https://api-3t.paypal.com/nvp';
                $this->PAYPAL_URL   = 'https://www.paypal.com/webscr?cmd=_express-checkout&token=';
                
            } else {
                $this->API_UserName     = $paypaldetails['Sitesetting']['paypal_test_username'];
                $this->API_Password     = $paypaldetails['Sitesetting']['paypal_test_password'];
                $this->API_Signature    = $paypaldetails['Sitesetting']['paypal_test_signature'];

                $this->API_Endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
                $this->PAYPAL_URL = 'https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=';
            }
	        
	     }
    }

////////////////////////////////////////////////////////////

    public function startup(Controller $controller)
    {
           
    }

////////////////////////////////////////////////////////////

    public function step1($paymentAmount = 0) {
        
        $this->returnURL=$this->controller->siteName.$this->returnURL;
        $this->cancelURL=$this->controller->siteName.$this->cancelURL;
        $resArray = $this->CallShortcutExpressCheckout($paymentAmount);
        
        $ack = strtoupper($resArray['ACK']);       
        if($ack=='SUCCESS' || $ack=='SUCCESSWITHWARNING') {
            
            return $this->controller->redirect($this->PAYPAL_URL . $resArray['TOKEN']);
        } else {
            return $ack;
        }
    }

////////////////////////////////////////////////////////////

    public function CallShortcutExpressCheckout($paymentAmount) {
        $nvpstr = '&PAYMENTREQUEST_0_AMT='. $paymentAmount;
        $nvpstr .= '&PAYMENTREQUEST_0_PAYMENTACTION=' . $this->paymentType;
        $nvpstr .= '&RETURNURL=' . $this->returnURL;
        $nvpstr .= '&CANCELURL=' . $this->cancelURL;
        $nvpstr .= '&PAYMENTREQUEST_0_CURRENCYCODE=' . $this->currencyCodeType;
        $this->Session->write('Shop.Paypal.currencyCodeType', $this->currencyCodeType);
        $this->Session->write('Shop.Paypal.PaymentType', $this->paymentType);
       
        $resArray = $this->hash_call('SetExpressCheckout', $nvpstr);
        $ack = strtoupper($resArray['ACK']);
        if($ack == 'SUCCESS' || $ack == 'SUCCESSWITHWARNING'){
            $token = urldecode($resArray['TOKEN']);
            $this->Session->write('Shop.Paypal.TOKEN', $token);
        }
        return $resArray;
    }

////////////////////////////////////////////////////////////

    public function GetShippingDetails($token) {
        $resArray = $this->hash_call('GetExpressCheckoutDetails', '&TOKEN=' . $token);
        $ack = strtoupper($resArray['ACK']);
        if($ack == 'SUCCESS' || $ack == 'SUCCESSWITHWARNING') {
            $this->Session->write('Shop.Paypal.payer_id', $resArray['PAYERID']);
        }
       
        return $resArray;
    }

////////////////////////////////////////////////////////////

    public function ConfirmPayment($FinalPaymentAmt) {
        $paypal = $this->Session->read('Shop.Paypal');
        $token = urlencode($paypal['TOKEN']);
        $paymentType = urlencode($paypal['PaymentType']);
        $currencyCodeType = urlencode($paypal['currencyCodeType']);
        $payerID = urlencode($paypal['payer_id']);
        $serverName = urlencode($_SERVER['SERVER_NAME']);
        $nvpstr = '&TOKEN=' . $token . '&PAYERID=' . $payerID . '&PAYMENTREQUEST_0_PAYMENTACTION=' . $paymentType . '&PAYMENTREQUEST_0_AMT=' . $FinalPaymentAmt;
        $nvpstr .= '&PAYMENTREQUEST_0_CURRENCYCODE=' . $currencyCodeType . '&IPADDRESS=' . $serverName;

        $resArray = $this->hash_call('DoExpressCheckoutPayment', $nvpstr);

        $ack = strtoupper($resArray['ACK']);
        return $resArray;
    }

////////////////////////////////////////////////////////////

    public function hash_call($methodName, $nvpStr) {

        $nvpreq  = 'METHOD=' . urlencode($methodName) . '&VERSION=' . urlencode($this->version) . '&PWD=' . urlencode($this->API_Password);
        $nvpreq .= '&USER=' . urlencode($this->API_UserName) . '&SIGNATURE=' . urlencode($this->API_Signature) . $nvpStr . '&BUTTONSOURCE=' . urlencode($this->sBNCode);
        
        App::uses('HttpSocket', 'Network/Http');
        $httpSocket = new HttpSocket();

        $response = $httpSocket->post($this->API_Endpoint, $nvpreq);

        $nvpResArray = $this->deformatNVP($response);

        return $nvpResArray;
    }

////////////////////////////////////////////////////////////

    public function deformatNVP($nvpstr) {
        $intial = 0;
        $nvpArray = array();
        while(strlen($nvpstr)) {
            $keypos= strpos($nvpstr, '=');
            $valuepos = strpos($nvpstr, '&') ? strpos($nvpstr, '&') : strlen($nvpstr);
            $keyval = substr($nvpstr, $intial, $keypos);
            $valval = substr($nvpstr, $keypos + 1, $valuepos - $keypos - 1);
            $nvpArray[urldecode($keyval)] = urldecode($valval);
            $nvpstr = substr($nvpstr, $valuepos + 1, strlen($nvpstr));
        }
        return $nvpArray;
    }

////////////////////////////////////////////////////////////

}