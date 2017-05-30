<?php

/* MN */

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class BookaTablesController extends AppController {

	var $helpers = array('Html', 'Session', 'Javascript', 'Ajax', 'Common');

	public $uses = array('City', 'Location', 'Store', 'Product', 'Category', 'ProductDetail',
						'ShoppingCart', 'Storeoffer', 'Deal', 'DeliveryLocation', 'Review', 
						'State', 'Cuisine', 'StoreCuisine', 'Subaddon', 'ProductAddon', 'BookaTable');

	public $components = array('Updown', 'Googlemap', 'Functions', 'Twilio');

	public function beforeFilter() {

		$this->Auth->allow(array('bookaTable'));
		parent::beforeFilter();
	}

    // Booking a table in Restaurant
	public function bookaTable() {
		$data = $this->Functions->parseSerialize($this->params['data']['formData']);
		$this->request->data = $data['data'];

		if (!empty($this->request->data['BookaTable'])) {
			$this->BookaTable->save($this->request->data, null, null);
			$this->request->data['BookaTable']['booking_id'] = '#Book000'.$this->BookaTable->id;
      		$this->BookaTable->save($this->request->data, null, null);
            $this->bookaTableMail($this->BookaTable->id);
			echo 'Success';
		}
		exit();
	}

    // Book a table store index
	public function store_index() {

		$this->layout  = 'assets';
		$bookaTables = $this->BookaTable->find('all', array(
                                'order' => 'BookaTable.id DESC'));

		$status = array('Pending' => 'En attente', 'Approved' => 'Approuvé', 'Cancel' => 'Annulé');
		$this->set(compact('bookaTables', 'status'));

	}


    // Book a table change status 
    public function bookStatus() {

        $bookStatus['id']            = $this->request->data['bookId'];
        $bookStatus['status']        = $this->request->data['status'];
        $bookStatus['cancel_reason'] = $this->request->data['reason'];

        if ($this->BookaTable->save($bookStatus, null, null)) {
            $this->bookaTableMail($this->BookaTable->id);
        }
        
        echo "Success";
        exit();

    }

    public function bookatableDetails() {
        $bookaTable = $this->BookaTable->findById($this->request->data['bookId']);
        $this->set(compact('bookaTable'));
    }

    // Book a table detail mail
	public function bookaTableMail($bookaTableId) {

		$bookTableDeatails = $this->BookaTable->findById($bookaTableId);

		$Customer 	= $bookTableDeatails['BookaTable']['customer_name'];
		$Restaurant = $bookTableDeatails['Store']['store_name'];
		$custMail 	= $bookTableDeatails['BookaTable']['booking_email'];
		$status 	= $bookTableDeatails['BookaTable']['status'];
		$storeMail  = $bookTableDeatails['Store']['contact_email'];
        $custPhone  = $bookTableDeatails['BookaTable']['booking_phone'];
        $storePhone = $bookTableDeatails['Store']['contact_phone'];
        $bookingId  = $bookTableDeatails['BookaTable']['booking_id'];

        $emailinfo = '<table class="container content" align="center" style="margin:0px"><tbody><tr> <td>
            <table class="row note">
                <tbody> <tr> <td class="wrapper last">
                      <p>Cher '.$Customer.',</p>
                      <p> Nous vous remercions d’avoir réservé chez nous. </p>
                      <p>Vous trouverez ci-dessous les détails de votre réservation  ID '.$bookingId.'</p> </td> </tr>
                </tbody>
            </table> 
        </table>';


		$emailinfo .='<table>
					<tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
    					<td style="display:inline-block; width:185px;vertical-align: top;">Nom du Restaurant</td>
    					<td style="display:inline-block; width:50px; text-align:center;">:</td>
    					<td style="display:inline-block; width:350px; color:#ee541e;">'.$Restaurant.'</td>
    				</tr>
                    <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
    					<td style="display:inline-block; width:185px;vertical-align: top;">Réservation id</td>
    					<td style="display:inline-block; width:50px; text-align:center;">:</td>
    					<td style="display:inline-block; width:350px; color:#ee541e;">'.$bookingId.'</td>
    				</tr>
                    <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
    					<td style="display:inline-block; width:185px;vertical-align: top;">Nombre de personnes</td>
    					<td style="display:inline-block; width:50px; text-align:center;">:</td>
    					<td style="display:inline-block; width:350px; color:#ee541e;">'.$bookTableDeatails['BookaTable']['guest_count'].'</td>
    				</tr>
                    <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
    					<td style="display:inline-block; width:185px;vertical-align: top;">Nom</td>
    					<td style="display:inline-block; width:50px; text-align:center;">:</td>
    					<td style="display:inline-block; width:350px; color:#ee541e;">'.$Customer.'</td>
    				</tr> 
                    <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
    					<td style="display:inline-block; width:185px;vertical-align: top;">Email</td>
    					<td style="display:inline-block; width:50px; text-align:center;">:</td>
    					<td style="display:inline-block; width:350px; color:#ee541e;">'.$custMail.'</td>
    				</tr> 
                    <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
    					<td style="display:inline-block; width:185px;vertical-align: top;">Téléphone</td>
    					<td style="display:inline-block; width:50px; text-align:center;">:</td>
    					<td style="display:inline-block; width:350px; color:#ee541e;">'.$custPhone.'</td>
    				</tr>';

		if (!empty($bookTableDeatails['BookaTable']['booking_instruction'])) {
			$emailinfo .='
					<tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
    					<td style="display:inline-block; width:185px;vertical-align: top;">Instruction(s) particulière(s)</td>
    					<td style="display:inline-block; width:50px; text-align:center;">:</td>
    					<td style="display:inline-block; width:350px; color:#ee541e;">'.$bookTableDeatails['BookaTable']['booking_instruction'].'</td>
    				</tr>';
		}

		$emailinfo .='
					<tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
    					<td style="display:inline-block; width:185px;vertical-align: top;">Statut de la réservation</td>
    					<td style="display:inline-block; width:50px; text-align:center;">:</td>
    					<td style="display:inline-block; width:350px; color:#ee541e;">'.$status.'</td>
    				</tr>';


    	if ($status == 'Cancel') {
    		$emailinfo .='
    				<tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
    					<td style="display:inline-block; width:185px;vertical-align: top;">Cancel Reason</td>
    					<td style="display:inline-block; width:50px; text-align:center;">:</td>
    					<td style="display:inline-block; width:350px; color:#ee541e;">'.$bookTableDeatails['BookaTable']['cancel_reason'].'</td>
    				</tr>';
    	}

    	$emailinfo .='</table>';

        // echo "----------".$emailinfo;exit;

    	$mailContent  	 = $emailinfo;
    	$customerSubject = 'Your book a table '.$bookingId.' status '.$status;
    	$storeSubject 	 = $Customer.' book a table '.$bookingId.' details';
        $source	 	  	 = $this->siteUrl.'/siteicons/logo.png';
        $siteName   	 = $this->siteSetting['Sitesetting']['site_name'];


        $email = new CakeEmail();
        $email->from($storeMail);
        $email->to($custMail);
        $email->subject($customerSubject);
        $email->template('register');
        $email->emailFormat('html');
        $email->viewVars(array('mailContent' => $mailContent,
        					   'source' => $source,
                               'storename' => $siteName));

        $email->send();

        if ($status == 'Pending') {

            $storeMessage   = $Customer.' booking a table. Booking id '. $bookingId;
            $tostoreNumber  = '+'.$this->siteSetting['Country']['phone_code'].$storePhone;
            $storeSms       = $this->Twilio->sendSingleSms($tostoreNumber, $storeMessage);

	        $email = new CakeEmail();
	        $email->from($custMail);
	        $email->to($storeMail);
	        $email->subject($storeSubject);
	        $email->template('register');
	        $email->emailFormat('html');
	        $email->viewVars(array('mailContent' => $mailContent,
	        					   'source' => $source,
                                   'storename' => $siteName));

	        $email->send();
	    }


        // Book a table Sms
        if ($status == 'Pending') {
            $customerMessage  = 'Your table booked successfully.';
        } else {
            $customerMessage  = 'Your booked table has been '.strtolower($status);
            $customerMessage .= ($status == 'Cancel') ? 'ed.' : '.';
        }

        if ($status == 'Cancel') {
            $customerMessage .= ' reason : '.$bookTableDeatails['BookaTable']['cancel_reason'].'.';
        }

        $customerMessage .= ' booking id '.$bookingId.'.  Regards '.$siteName.'.';
        
        $toCustomerNumber = '+'.$this->siteSetting['Country']['phone_code'].$custPhone;
        $customerSms      = $this->Twilio->sendSingleSms($toCustomerNumber, $customerMessage);

		return true;
	}
}