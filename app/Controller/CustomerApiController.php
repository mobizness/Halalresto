<?php
/* MN */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::import('Controller', 'Commons');

class CustomerApiController extends AppController {
    public $components = array('Functions', 'Mailchimp', 'Twilio', 'Updown',
                                'Googlemap', 'Stripe', 'Notifications', 'AndroidResponse');
    public $uses      = array('User','Order', 'Driver','DriverTracking','Orderstatus', 'MailContent',
                                'State','City', 'Location', 'AssignOrder', 'Customer', 'Notification',
                                'StripeCustomer', 'CustomerAddressBook', 'Review', 'Product', 'Store',
                                'DeliveryLocation', 'Category', 'Deal', 'ProductDetail', 'Storeoffer',
                                  'DeliveryTimeSlot', 'ShoppingCart', 'Voucher', 'Cuisine', 'StoreCuisine',
                                  'Mainaddon', 'Subaddon', 'ProductAddon', 'WalletHistory'
                                );
    
    public function beforeFilter() {
        
        $this->Auth->allow('request', 'signUpMail', 'walletAddMoney');
        parent::beforeFilter();

        $this->storeState = $this->State->find('list', array(
                                                'fields' => array('id', 'state_name')));
        $this->storeCity = $this->City->find('list', array(
                            'fields' => array('City.id', 'City.city_name')));

        if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {
            $this->storeLocation = $this->Location->find('list', array(
                                        'fields' => array('id','zip_code')));
        } else {
            $this->storeLocation = $this->Location->find('list', array(
                                        'fields' => array('id','area_name')));
        }

        $this->storeLocations = $this->Location->find('list', array(
                                        'fields' => array('id','zip_code')));

        $this->set(compact('storeCity', 'storeArea', 'cityId', 'areaId'));
    }

    public function index() {
        
    }
    
    /**
     * Request from android 
     * @param action
     * @return response->success, response->message
     */
    public function request() {
        $this->autoLayout = false;


        $addressMode = $this->siteSetting['Sitesetting']['address_mode'];
        $siteCountry  = $this->siteSetting['Sitesetting']['site_country'];
        
        if ($this->request->is('post')) {

            $storeId = (isset($this->request->data['storeId'])) ? $this->request->data['storeId'] : '';

            if (empty($this->request->data)) {
                $data = $this->request->input('json_decode', true);
                $this->request->data = $data;
            }
            switch (trim($this->request->data['action'])) {

                case 'CustomerLogin':
                    
                    $this->request->data['User']['username'] = $this->request->data['username'];
                    $password = $this->request->data['password'];

                    $this->request->data['User']['password'] = AuthComponent::password($password);

                    $customer = $this->User->find('first', array(
                                    'conditions' => array(
                                        'User.username' => $this->request->data['User']['username'],
                                        'User.password' => $this->request->data['User']['password'],
                                        'Customer.status' => 1,
                                        'User.role_id' => 4)));


                    if (!empty($customer)) {
                        if ($customer['Customer']['status'] != 1) {
                            $response['success']    = 0;
                            $response['message']    = 'Your account deactivated';
                            break;
                        }

                        $customer['Customer']['device_id']      = $this->request->data['device_id'];
                        $customer['Customer']['is_logged']      = 1;
                        $customer['Customer']['device_name']    = strtoupper($this->request->data['device_name']);

                        $customerDetails = $this->Customer->save($customer, null, null);

                        if ($customerDetails['Customer']['is_logged'] == 1) {

                            $response['success']        = 1;
                            $response['customerId']     = $customerDetails['Customer']['id'];
                            $response['firstName']      = $customerDetails['Customer']['first_name'];
                            $response['lastName']       = $customerDetails['Customer']['last_name'];
                            $response['email']          = $customerDetails['Customer']['customer_email'];
                            $customerImage              = (!empty($customerDetails['Customer']['image'])) 
                                                            ? $this->siteUrl.'/Customers/'.$customerDetails['Customer']['image'] 
                                                            : '';
                            
                            $response['image']          = $customerImage;
                            $response['customerPhone']  = $customerDetails['Customer']['customer_phone'];
                            $response['message']        = 'login successfully';
                            break;
                        } else {
                            $response['success'] = 0;
                            $response['message'] = 'Invalid username and password';
                            break;
                        }
                        
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'Incorrect username and password';
                        break;
                    }
                break;

                case 'CustomerSignUp':

                    $customerSignUp['User']['password']            = $this->request->data['password'];
                    $customerSignUp['Customer']['last_name']       = $this->request->data['last_name'];
                    $customerSignUp['Customer']['first_name']      = $this->request->data['first_name'];
                    $customerSignUp['Customer']['customer_email']  = $this->request->data['customer_email'];
                    $customerSignUp['Customer']['customer_phone']  = $this->request->data['customer_phone'];

                    $CustomerExist = $this->User->find('first', array(
                                    'conditions' => array('User.role_id' => 4,
                                          'User.username' => trim($customerSignUp['Customer']['customer_email']),
                                      'NOT' => array('Customer.status' => 3))));
                    $StoreExists = $this->User->find('first', array(
                                    'conditions' => array('User.role_id' => 3,
                                                'User.username' => trim($customerSignUp['Customer']['customer_email']),
                                            'NOT' => array('Store.status' => 3))));

                    if (!empty($CustomerExist) || !empty($StoreExists)) {

                        $response['success'] = 0;
                        $response['message'] = 'Email already exists!';

                    } else {
                        $customerSignUp['User']['role_id']  = 4;
                        $customerSignUp['User']['username'] = $customerSignUp['Customer']['customer_email'];
                        $customerSignUp['User']['password'] = $this->Auth->password($customerSignUp['User']['password']);

                        $this->User->save($customerSignUp['User'],null,null);
                        $customerSignUp['Customer']['user_id'] = $this->User->id;
                        $this->Customer->save($customerSignUp['Customer'],null,null);

                        $this->signUpMail($this->Customer->id);
                        
                        $response['success'] = 1;
                        $response['message'] = 'You have successfully registered an account. An email has been sent with further instructions!';
                    }
                break;

                case 'SocialLogin':

                    $customerSignUp['Customer']['last_name']       = $this->request->data['last_name'];
                    $customerSignUp['Customer']['first_name']      = $this->request->data['first_name'];
                    $customerSignUp['Customer']['customer_email']  = $this->request->data['customer_email'];

                    $customer   = $this->User->find('first', array(
                                        'conditions' => array('User.role_id' => 4,
                                                'User.username' => trim($customerSignUp['Customer']['customer_email']),
                                        'NOT' => array('Customer.status' => 3))));
                    $StoreExists = $this->User->find('first', array(
                                        'conditions' => array('User.role_id' => 3,
                                                'User.username' => trim($customerSignUp['Customer']['customer_email']),
                                        'NOT' => array('Store.status' => 3))));

                    if (!empty($StoreExists)) {

                        $response['success'] = 0;
                        $response['message'] = 'Login failed, unauthorized!';

                    } else {

                        if (empty($customer)) {

                            $customerSignUp['User']['role_id']  = 4;
                            $customerSignUp['User']['username'] = $customerSignUp['Customer']['customer_email'];
                            $tmpPassword = $this->Functions->createTempPassword(7);

                            $this->request->data['User']['password'] = $this->Auth->Password($tmpPassword);
                            $this->User->save($customerSignUp['User'],null,null);

                            $customerSignUp['Customer']['user_id'] = $this->User->id;
                            $this->Customer->save($customerSignUp['Customer'],null,null);

                            $this->signUpMail($this->Customer->id, $tmpPassword);
                            $customer = $this->Customer->findById($this->Customer->id);
                        }

                        $customer['Customer']['device_id']      = $this->request->data['device_id'];
                        $customer['Customer']['is_logged']      = 1;
                        $customer['Customer']['device_name']    = strtoupper($this->request->data['device_name']);

                        $customerDetails = $this->Customer->save($customer, null, null);

                        $response['success']        = 1;
                        $response['email']          = $customerDetails['Customer']['customer_email'];
                        $response['lastName']       = $customerDetails['Customer']['last_name'];
                        $response['firstName']      = $customerDetails['Customer']['first_name'];
                        $response['customerId']     = $customerDetails['Customer']['id'];
                        $customerImage              = (!empty($customerDetails['Customer']['image'])) 
                                                        ? $this->siteUrl.'/Customers/'.$customerDetails['Customer']['image'] 
                                                        : '';
                        $response['image']          = $customerImage;
                        $response['customerPhone']  = $customerDetails['Customer']['customer_phone'];
                        $response['message']        = 'login successfully';
                    }
                break;

                case 'ForgetPassword':

                    $userData = $this->User->find('first', array(
                                           'conditions' =>array(
                                               'User.username' => $this->request->data['email'],
                                               'Customer.status' => 1,
                                               'User.role_id' => 4)));

                   if(!empty($userData)) {
                       $newRegisteration = $this->Notification->find('first', array(
                                            'conditions'=>array('Notification.title' => 'Reset password')));

                       $toemail      = $this->request->data['email'];
                       $source       = $this->siteUrl.'/siteicons/logo.png';
                       $title        = 'logo.png';

                       $storeEmail   = $this->siteSetting['Sitesetting']['admin_email'];
                       $storename    = $this->siteSetting['Sitesetting']['site_name'];
                       $customerName = $userData['Customer']['first_name'];
                       $tmpPassword  = $this->Functions->createTempPassword(7);

                       $datas['User']['password']   =   $this->Auth->Password($tmpPassword);
                       $datas['User']['id']         =   $userData['User']['id'];

                        if ($this->User->save($datas['User'],null,null)){

                            if($newRegisteration){

                                $forgetpasswordContent = $newRegisteration['Notification']['content'];
                                $forgetpasswordsubject = $newRegisteration['Notification']['subject'];
                            }
                           
                            $mailContent = $forgetpasswordContent;
                            $userID      = $userData['User']['id'];
                            $siteUrl     = $this->siteUrl.'/customer/users/customerlogin/';
                            $mailContent = str_replace("{Customer name}", $customerName, $mailContent);
                            $mailContent = str_replace("{source}", $source, $mailContent);
                            $mailContent = str_replace("{title}", $title, $mailContent);
                            $mailContent = str_replace("{SITE_URL}", $siteUrl, $mailContent);
                            $mailContent = str_replace("{tmpPassword}", $tmpPassword, $mailContent);
                            $mailContent = str_replace("{Store name}", $storename, $mailContent);

                            $email = new CakeEmail();
                            $email->from($storeEmail);
                            $email->to($toemail);
                            $email->subject($forgetpasswordsubject);
                            $email->template('register');
                            $email->emailFormat('html');
                            $email->viewVars(array('mailContent' => $mailContent,'source'=>$source,'storename'=>$storename));

                            if($email->send()){
                                // Forget Sms
                                $customerMessage = "We've received a request to change your password. Use this password ".$tmpPassword." to login to your account and update it ASAP. Thanks ".$this->siteSetting['Sitesetting']['site_name'];
                                    $toCustomerNumber = '+'.$this->siteSetting['Country']['phone_code'].$userData['Customer']['customer_phone'];
                                $customerSms         = $this->Twilio->sendSingleSms($toCustomerNumber, $customerMessage);
                                $response['success'] = 1;
                                $response['message'] = 'Email has been sent successfully';
                           }
                        }
                   } else {
                        $response['success'] = 0;
                        $response['message'] = 'You are not register customer';
                   }
                break;

                case 'MyAccount':

                    $customerId = $this->request->data['customer_id'];
                    $this->Customer->recursive = 0;
                    $customerDetails = $this->Customer->find('first', array(
                                                      'conditions' => array('User.role_id' => 4,
                                                                  'Customer.id' => $customerId,
                                                              'NOT' => array('Customer.status' => 3))));

                    if (is_array($customerDetails) && $customerDetails['User']['role_id'] == 4) {

                        switch (trim($this->request->data['page'])) {

                            case 'CustomerDetails':
                                $response['success']        = '1';
                                $response['customerId']     = $customerDetails['Customer']['id'];
                                $response['firstName']      = $customerDetails['Customer']['first_name'];
                                $response['lastName']       = $customerDetails['Customer']['last_name'];
                                $response['email']          = $customerDetails['Customer']['customer_email'];
                                $response['newsletter']     = $customerDetails['Customer']['news_letter_option'];
                                $customerImage              = (!empty($customerDetails['Customer']['image'])) 
                                                                ? $this->siteUrl.'/Customers/'.$customerDetails['Customer']['image'] 
                                                                : '';


                                $response['customerDetails'] = $customerDetails;
                                $response['image']          = $customerImage;
                                $response['customerPhone']  = $customerDetails['Customer']['customer_phone'];
                            break;

                            case 'getStripeCard':
                                $cardDetails = array();
                                $stripeCards = $this->StripeCustomer->find('all', array(
                                                        'conditions'=>array(
                                                                'StripeCustomer.customer_id' => $customerId)));

                                foreach ($stripeCards as $key => $value) {
                                    $cardDetails[] = $value['StripeCustomer'];
                                }

                                if (!empty($cardDetails)) {
                                    $response['success']    = 1;
                                    $response['cardDetails'] = $cardDetails;
                                } else {
                                    $response['success']    = 0;
                                    $response['message']    = 'No records found';
                                }
                            break;

                            case 'SavedCardsList':
                                $stripeCards = $this->StripeCustomer->find('all',array(
                                                        'conditions'=>array('StripeCustomer.customer_id' => $customerId)));

                                if (!empty($cardDetails)) {
                                    $response['success']    = 1;
                                    $response['savedCards'] = $stripeCards;
                                } else {
                                    $response['success']    = 0;
                                    $response['message']    = 'No records found';
                                }
                            break;

                            case 'SavedCardDelete':
                                $stripeId   = $this->request->data['stripeId'];
                                $stripeCard = $this->StripeCustomer->find('first', array(
                                                        'conditions' => array('StripeCustomer.customer_id' => $customerId,
                                                                            'StripeCustomer.id' => $stripeId)));
                                if (!empty($stripeCard)) {
                                    $this->StripeCustomer->delete($stripeId);
                                    $response['success'] = 1;
                                    $response['message'] = 'Successfully deleted card';
                                } else {
                                    $response['success'] = 0;
                                    $response['message'] = 'Failed';
                                }
                            break;

                            case 'ChangePassword':
                                $password        = $this->request->data['password'];
                                $currentPassword = $this->request->data['currentPassword'];

                                if (!empty($password)) {
                                        $customerDetails['User']['password'] = AuthComponent::password($password);
                                        $this->User->save($customerDetails['User']);
                                        $response['success'] = 1;
                                        $response['message'] = 'Password successfully changed';
                                } else {
                                    $response['success'] = 0;
                                    $response['message'] = 'Missing Parameters';
                                }
                            break;

                            case 'ChangeUserEmail':
                                $newEmail = trim($this->request->data['customer_email']);
                                $customerExist = $this->User->find('first', array(
                                                      'conditions' => array('User.role_id' => 4,
                                                                  'User.username' => $newEmail,
                                                              'NOT' => array('Customer.status' => 3))));
                                $storeExists = $this->User->find('first', array(
                                                      'conditions' => array('User.role_id' => 3,
                                                                  'User.username' => $newEmail,
                                                              'NOT' => array('Store.status' => 3))));

                                if (!empty($customerExist) || !empty($storeExists)) {
                                    $response['success'] = 0;
                                    $response['message'] = 'Email Already Exists';
                                } else {

                                    $oldEmail = $customerDetails['User']['username'];

                                    $customerDetails['User']['username']           = $newEmail;
                                    $customerDetails['Customer']['status']         = 2;
                                    $customerDetails['Customer']['customer_email'] = $newEmail;

                                    $this->User->save($customerDetails['User'], null, null);
                                    $this->Customer->save($customerDetails['Customer'], null, null);
                                    $newChangedUser = $this->Notification->find('first',array(
                                                                  'conditions'=>array('Notification.title'=>'Changed new user email')));

                                    if($newChangedUser){

                                        $newUserContent = $newChangedUser['Notification']['content'];
                                        $newUsersubject = $newChangedUser['Notification']['subject'];
                                    }

                                    $adminEmail   = $this->siteSetting['Sitesetting']['admin_email'];
                                    $source       = $this->siteUrl.'/siteicons/logo.png';
                                    $mailContent  = $newUserContent;
                                    $userID       = $this->Customer->id;
                                    $siteUrl      = $this->siteUrl;
                                    $activation   = $this->siteUrl. '/users/activeLink/'.$userID;
                                    $customerName = $customerDetails['Customer']['first_name'];
                                    $store_name   = $this->siteSetting['Sitesetting']['site_name'];

                                    $mailContent  = str_replace("{firstname}", $customerName, $mailContent);
                                    $mailContent  = str_replace("{activation}", $activation, $mailContent);
                                    $mailContent  = str_replace("{SITE_URL}", $siteUrl, $mailContent);
                                    $mailContent  = str_replace("{store name}",$store_name, $mailContent);

                                    $email        = new CakeEmail();
                                    $email->from($adminEmail);
                                    $email->to($newEmail);
                                    $email->subject($newUsersubject);
                                    $email->template('register');
                                    $email->emailFormat('html');
                                    $email->viewVars(array('mailContent' => $mailContent,
                                                          'source'     => $source,
                                                          'storename'  => $store_name));
                                    $email->send();

                                    $oldChangedUser = $this->Notification->find('first',array(
                                                        'conditions'=>array('Notification.title'=>'Changed old user email')));
                                    if($oldChangedUser){

                                        $oldUserContent = $oldChangedUser['Notification']['content'];
                                        $oldUsersubject = $oldChangedUser['Notification']['subject'];
                                    }

                                    $mailContent  = $oldUserContent;
                                    $userID       = $this->Customer->id;


                                    $mailContent  = str_replace("{firstname}", $customerName, $mailContent);
                                    $mailContent  = str_replace("{SITE_URL}", $siteUrl, $mailContent);
                                    $mailContent  = str_replace("{store name}",$store_name, $mailContent);
                                    $mailContent  = str_replace("{email}",$newEmail, $mailContent);
                                    $email        = new CakeEmail();
                                    $email->from($adminEmail);
                                    $email->to($oldEmail);
                                    $email->subject($oldUsersubject);
                                    $email->template('register');
                                    $email->emailFormat('html');
                                    $email->viewVars(array('mailContent' => $mailContent,
                                                            'source'     => $source,
                                                            'storename'  => $store_name));
                                    $email->send();

                                    $response['success'] = 1;
                                    $response['message'] = 'Successfully user email has been changed';
                                }
                            break;

                            case 'ProfileUpdate':

                                $customer['Customer']['id']             = $customerId;
                                $customer['Customer']['last_name']      = $this->request->data['last_name'];
                                $customer['Customer']['first_name']     = $this->request->data['first_name'];
                                $customer['Customer']['customer_phone'] = $this->request->data['phone'];
                                $this->Customer->save($customer, null, null);

                                $response['success']  = 1;
                                $response['message']  = 'Your detail has been updated';
                            break;

                            case 'ProfileImageUpload':

                                if (!empty($this->request->data['image'])) {
                                    // Get image string posted from Android App
                                    $base = $this->request->data['image'];

                                    if($this->request->data['device'] != 'ANDROID') {
                                        $imageSrc = str_replace(" ","+",$base);
                                    } else {
                                        $base = explode('\n', $base);
                                        $imageSrc = '';
                                        foreach ($base as $key => $value) {
                                            $imageSrc .= stripslashes($value);
                                        }
                                    }

                                    // Get file name posted from Android App
                                    $fileId = $customerDetails['Customer']['first_name'].time().'.jpg';
                                    $filename = WWW_ROOT."Customers/".$fileId;
                                    // Decode Image
                                    $binary = base64_decode(trim($imageSrc));
                                    header('Content-Type: bitmap; charset=utf-8');
                                    
                                    $file = fopen($filename, 'wb+');
                                    // Create File
                                    fwrite($file, $binary);
                                    fclose($file);
                                    #Save Driver Image
                                    
                                    $customerImage['Customer']['id']    = $customerDetails['Customer']['id'];
                                    $customerImage['Customer']['image'] = $fileId;
                                    $this->Customer->save($customerImage, null, null);

                                    $customerImage = (!empty($fileId)) ? $this->siteUrl.'/Customers/'.$fileId : '';

                                    $response['success'] = 1;
                                    $response['image']   = $customerImage;
                                    $response['message'] = 'Image uploaded successfully!';

                                } else {
                                    $response['success'] = 0;
                                    $response['message'] = 'Image not upload!';
                                }
                            break;

                            case 'AddressBookList':

                                if (!empty($this->request->data['view'])) {
                                    $pageView = 'checkout';
                                    $addressBook = $this->addressBook($customerId, $pageView);
                                } else {
                                    $addressBook = $this->addressBook($customerId);
                                }
                                
                                if (!empty($addressBook)) {
                                    $response['success']      = 1;
                                    $response['addressBook']  =  $addressBook;
                                } else {
                                    $response['success']      = 0;
                                    $response['message']      = 'No records found';
                                }
                            break;

                            case 'AddressBookAdd':

                                $addressTitle   = $this->request->data['address_title'];
                                $addressBook    = $this->CustomerAddressBook->find('first', array(
                                                        'conditions' => array(
                                                                'CustomerAddressBook.customer_id' => $customerId,
                                                                'CustomerAddressBook.address_title' => trim($addressTitle)
                                                        )));

                                if(!empty($addressBook)) {
                                    $response['success']  = 0;
                                    $response['message']  = 'Address Book Already Exists';
                                } else {
                                    $this->request->data['status'] = 1;
                                    $this->CustomerAddressBook->save($this->request->data,null,null);
                                    $response['success']  = 1;
                                    $response['message']  = 'Your CustomerAddressBook has been updated';
                                }
                            break;

                            case 'AddressBook':

                                $addressBookId  = $this->request->data['addressBookId'];
                                $addressBook    = $this->CustomerAddressBook->find('first',array(
                                                        'conditions'  => array(
                                                                'CustomerAddressBook.id' => $addressBookId,
                                                                'CustomerAddressBook.customer_id' => $customerId)
                                                        ));

                                switch (trim($this->request->data['addressAction'])) {

                                    case 'AddressBookDetails':

                                        $customerCities = $customerLocations = '';

                                        if (!empty($addressBook)) {
                                            $response['success']             = 1;
                                            $response['addressBookDetails']  = $addressBook;

                                        } else {
                                            $response['success'] = 0;
                                            $response['message'] = 'Failed';
                                        }
                                    break;

                                    case 'AddressBookEdit':

                                        $addressTitle   = $this->request->data['address_title'];
                                        $addressBook    = $this->CustomerAddressBook->find('first', array(
                                                                'conditions' => array('CustomerAddressBook.customer_id' => $customerId,
                                                                            'CustomerAddressBook.address_title' => trim($addressTitle),
                                                                    'NOT' => array('CustomerAddressBook.id' => $addressBookId))));  

                                        if(!empty($addressBook)) {
                                            $response['success']  = 0;
                                            $response['message']  = 'Address Book Already Exists';
                                        } else {
                                            $this->request->data['id'] = $addressBookId;
                                            $this->CustomerAddressBook->save($this->request->data,null,null);
                                            $response['success']  = 1;
                                            $response['message']  = 'Your CustomerAddressBook has been updated';
                                        }
                                    break;

                                    case 'AddressBookStatus':

                                        if (!empty($addressBook)) {
                                            if ($addressBook['CustomerAddressBook']['status'] == 1) {
                                                $addressBook['CustomerAddressBook']['status'] = 0;
                                            } else {
                                                $addressBook['CustomerAddressBook']['status'] = 1;
                                            }
                                            $this->CustomerAddressBook->save($addressBook);
                                            $response['success']  = 1;
                                            $response['message']  = 'Successfully status changed';
                                        } else {
                                            $response['success']  = 0;
                                            $response['message']  = 'Failed';
                                        }
                                    break;

                                    case 'AddressBookDelete':

                                        if (!empty($addressBook)) {
                                            $this->CustomerAddressBook->delete($addressBookId);
                                            $response['success']  = 1;
                                            $response['message']  = 'Successfully deleted addressbook';
                                        } else {
                                            $response['success']  = 0;
                                            $response['message']  = 'Failed';
                                        }
                                    break;
                                }
                            break;

                            case 'OrderList':
                                $orderList = array();
                                $this->Order->recursive = 0;
                                $orderLists = $this->Order->find('all',array(
                                                        'conditions'  => array('Order.customer_id' => $customerId,
                                                                'NOT' => array('Order.status' => 'Deleted')),
                                                        'fields' => array('Order.id', 'ref_number', 'order_grand_total',
                                                                          'payment_type', 'payment_method', 'Order.status',
                                                                          'Review.rating'),
                                                        'order'  => array('Order.id DESC')));

                                foreach ($orderLists as $key => $value) {

                                    $value['Order']['rating'] = (!empty($value['Review']['rating'])) ?
                                                                $value['Review']['rating'] : '';
                                    $orderList[$key] = $value['Order'];
                                }

                                if (!empty($orderList)) {
                                    $response['success']    = 1;
                                    $response['orderLists'] = $orderList;
                                } else {
                                    $response['success']    = 0;
                                    $response['message']    = 'No records found';
                                }
                            break;

                            case 'OrderDetail':
                                $orderId      = $this->request->data['orderId'];
                                $orderDetails = $this->Order->find('first',array(
                                                        'conditions'  => array('Order.id' => $orderId,
                                                                                'Order.customer_id' => $customerId,
                                                                'NOT' => array('Order.status' => 'Deleted')),
                                                        'order'  => array('Order.id DESC')));

                                if (!empty($orderDetails)) {

                                    $OrderDetail                    = $orderDetails['Order'];
                                    $OrderDetail['store_name']      = $orderDetails['Store']['store_name'];
                                    $OrderDetail['store_phone']     = $orderDetails['Store']['store_phone'];
                                    $OrderDetail['store_address']   = $orderDetails['Store']['address'];
                                    $OrderDetail['ShoppingCart']    = $orderDetails['ShoppingCart'];

                                    $response['success']    = 1;
                                    $response['OrderDetail'] = $OrderDetail;
                                } else {
                                    $response['success']    = 0;
                                    $response['message']    = 'No records found';
                                }
                            break;

                            case 'OrderReview':
                                $this->Order->recursive = 0;
                                $orderId        = $this->request->data['order_id'];
                                $orderInfo      = $this->Order->find('first', array(
                                                            'conditions' => array('Order.id' => $orderId,
                                                                                  'Order.status' => 'Delivered')));
                                $checkingReview = $this->Review->find('first',array(
                                                            'conditions' => array('Review.order_id'=>$orderId)));

                                if ((!empty($orderInfo)) && (empty($checkingReview))) {
                                    $this->request->data['Review']['rating']      = $this->request->data['rating'];
                                    $this->request->data['Review']['message']     = $this->request->data['message'];
                                    $this->request->data['Review']['store_id']    = $orderInfo['Order']['store_id'];
                                    $this->request->data['Review']['order_id']    = $orderId;
                                    $this->request->data['Review']['customer_id'] = $customerId;

                                    $this->Review->save($this->request->data, null, null);

                                    $response['success']  = 1;
                                    $response['message']  = 'Thank you for your review';

                                } else {
                                    $response['success']  = 0;
                                    $response['message']  = 'Review Already Exsits';
                                }
                            break;

                            case 'TrackOrder':

                                $orderId        = $this->request->data['orderId'];
                                $orderInfo      = $this->Order->find('first', array(
                                                        'conditions' => array('Order.id' => $orderId)));

                                if (!empty($orderInfo)) {
                                    $driverDetails = $this->Driver->findById($orderInfo['Order']['driver_id']);
                                    if (!empty($driverDetails)) {

                                        $storeLatitude      = $orderInfo['Order']['source_latitude'];
                                        $storeLongitude     = $orderInfo['Order']['source_longitude'];
                                        $customerLatitude   = $orderInfo['Order']['destination_latitude'];
                                        $customerLongitude  = $orderInfo['Order']['destination_longitude'];
                                        $driverLatitude     = $driverDetails['DriverTracking']['driver_latitude'];
                                        $driverLongitude    = $driverDetails['DriverTracking']['driver_longitude'];

                                        $response['success']            = 1;
                                        $response['orderId']            = $orderInfo['Order']['id'];
                                        $response['ref_number']         = $orderInfo['Order']['ref_number'];
                                        $response['storeLatitude']      = (!empty($storeLatitude)) ? $storeLatitude : '';
                                        $response['storeLongitude']     = (!empty($storeLongitude)) ? $storeLongitude : '';
                                        $response['driverLatitude']     = (!empty($driverLatitude)) ? $driverLatitude : '';
                                        $response['driverLongitude']    = (!empty($driverLongitude)) ? $driverLongitude : '';
                                        $response['customerLatitude']   = (!empty($customerLatitude)) ? $customerLatitude : '';
                                        $response['customerLongitude']  = (!empty($customerLongitude)) ? $customerLongitude : '';
                                    } else {
                                        $response['success']            = 1;
                                        $response['orderId']            = $orderInfo['Order']['id'];
                                    }
                                } else {
                                    $response['success']    = 0;
                                    $response['message']    = $orderInfo['Order']['id'];
                                }
                            break;

                            case 'MyWallet':

                                $walletHistory = array();

                                $walletHistories = $this->WalletHistory->find('all', array(
                                                                'conditions' => array(
                                                                    'WalletHistory.customer_id' => $customerId),
                                                                'fields' => array('purpose', 'transaction_type',
                                                                                 'transaction_details', 'amount',
                                                                                 'WalletHistory.created'),
                                                                'order' => 'WalletHistory.id Desc'));

                                foreach ($walletHistories as $key => $value) {
                                    $walletHistory[$key] = $value['WalletHistory'];
                                    $dateTime = explode(' ', $value['WalletHistory']['created']);
                                    $walletHistory[$key]['date'] = $dateTime[0];

                                }

                                $response['walletAmount']  = $customerDetails['Customer']['wallet_amount'];

                                if (!empty($walletHistory)) {
                                    $response['success']  = 1;
                                    $response['walletHistory']  = $walletHistory;
                                } else {
                                    $response['success']  = 0;
                                    $response['message']  = 'No records found';
                                }
                            break;

                            case 'MyWalletAddMoney':

                                $amount       = number_format($this->request->data['amount'],2);
                                $stripeCardId = $this->request->data['card_id'];

                                if (empty($stripeCardId)) {

                                    $saveOrNot  = $this->request->data['saveCard'];
                                    $cardNumber = $this->request->data['stripe_cardnumber'];
                                    $stripe['exp_year']         = $this->request->data['stripe_expyear'];
                                    $stripe['exp_month']        = $this->request->data['stripe_expmonth'];
                                    $stripe['customer_id']      = $customerId;
                                    $stripe['card_number']      = substr($cardNumber, -4);
                                    $stripe['customer_name']    = $this->request->data['cardHolderName'];
                                    $stripe['stripe_token_id']  = $stripeToken = $this->request->data['stripe_token'];

                                    $datas    = array("stripeToken" => $stripeToken);
                                    $customer = $this->Stripe->customerCreate($datas);
                                    $stripId  = $stripe['stripe_customer_id'] = (!empty($customer)) ? $customer['stripe_id'] : '';

                                    if ($saveOrNot == 'Yes') {
                                        $this->StripeCustomer->save($stripe, null, null);
                                        $stripeCardId = $this->StripeCustomer->id;

                                    }
                                    
                                } else {
                                    $stripeCard = $this->StripeCustomer->find('first', array(
                                                        'conditions' => array(
                                                                'StripeCustomer.id' => $stripeCardId,
                                                                'StripeCustomer.customer_id' => $customerId)));

                                    if (empty($stripeCard['StripeCustomer']['stripe_customer_id'])) {
                                        $datas    = array("stripeToken" => $stripeCard['StripeCustomer']['stripe_token_id']);
                                        $customer = $this->Stripe->customerCreate($datas);
                                        $stripId  = $stripeCard['StripeCustomer']['stripe_customer_id'] = $customer['stripe_id'];
                                        $this->StripeCustomer->save($stripeCard);
                                    } else {
                                        $stripId = $stripeCard['StripeCustomer']['stripe_customer_id'];
                                    }
                                }

                                $data = array('currency' => $this->siteSetting['Country']['currency_code'],
                                                "amount"   => $amount,
                                                "stripeCustomer" => $stripId);

                                $stripeResponse = $this->Stripe->charge($data);

                                if ($stripeResponse['status'] == "succeeded" && $stripeResponse['stripe_id'] != '') {
                                
                                    // Wallet Amount Added
                                    $customerDetails['Customer']['wallet_amount'] += $amount;
                                    $this->Customer->save($customerDetails, null, null);

                                    // Wallet History
                                    $walletHistory['amount']              = $amount;
                                    $walletHistory['purpose']             = 'Money added in wallet';
                                    $walletHistory['customer_id']         = $customerId;
                                    $walletHistory['transaction_details'] = $stripeResponse['stripe_id'];

                                    $this->WalletHistory->save($walletHistory, null, null);

                                    $response['success']  = 1;
                                    $response['message']  = 'Successfully added amount in your wallet';

                                } else {
                                    $response['success']  = 0;
                                    $response['message']  = 'Some technical problem. Please try again!';
                                }
                            break;

                            case 'CustomerLogOut':
                                $customerDetails['Customer']['is_logged']      = '0';
                                $customerDetails['Customer']['device_id']      = '';

                                $customerLogout = $this->Customer->save($customerDetails);
                                $response['success']  = 1;
                                $response['message']  = 'Successfully customer logged out';
                            break;

                        }
                    } else {
                        $response['success']  = 0;
                        $response['message']  = 'Unknown customer';
                    }
                break;

                case 'HomePageLocation':

                    $id         = $this->request->data['city_id'];
                    $locations  = $stores = array();
                    $storeLists = $this->Product->find('all', array(
                                            'conditions' => array(
                                                                'Store.status' => 1,
                                                                'Store.store_city' => $id,
                                                                'Product.status' => 1,
                                                                'MainCategory.status' => 1,
                                                    'OR' => array('Store.collection' => 'Yes',
                                                                  'Store.delivery'   => 'Yes')),
                                            'group' => array('Store.id')));

                    foreach ($storeLists as $key => $value) {

                        if ($value['Store']['collection'] == 'Yes' ||
                            $value['Store']['delivery'] == 'Yes' &&
                            $value['Store']['delivery_option'] == 'Yes') {
                            $stores[] = $value['Store']['id'];
                        }
                    }

                    $storeList = $this->DeliveryLocation->find('all', array(
                                        'conditions' => array('DeliveryLocation.location_id !=' => '',
                                                              'DeliveryLocation.store_id' => $stores,
                                                              'Location.status' => 1),
                                        'group' => array('DeliveryLocation.location_id')));
                    

                    foreach ($storeList as $key => $value) {
                        if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {
                            $location['id']             = $value['Location']['id'];
                            $location['location_name']  = $value['Location']['area_name'];
                        } else {
                            $location['id']             = $value['Location']['id'];
                            $location['location_name']  = $value['Location']['area_name'];
                        }

                        $locations[] = $location;
                    }

                    $response['success']    = 1;
                    $response['locations']  = (!empty($locations)) ? $locations : '';
                break;

                case 'StoreList':

                    $storeList  = array();
                    $serachAddress = $this->request->data['google_address'];
                    list($getStoreCuisine, $storeList) = $this->searchByAddress($serachAddress);
                    $response['cuisines']   = (!empty($getStoreCuisine)) ? $getStoreCuisine : '';

                    if (empty($storeList)) {
                        $response['success']  = 0;
                        $response['message']  = 'Restaurant is not available';
                    } else {
                        $response['success']    = 1;
                        $response['storeList']  = $storeList;
                    }
                break;

                /*case 'StoreOffer':
                    $today          = date("m/d/Y");
                    $storeOffers    = $this->Storeoffer->find('first', array(
                                                'conditions' => array(
                                                                    'Storeoffer.store_id' => $storeId,
                                                                    'Storeoffer.status' => 1,
                                                                    "Storeoffer.from_date <=" => $today,
                                                                    "Storeoffer.to_date >="   => $today),
                                                'order' => 'Storeoffer.id DESC'));

                    $response['success']      = 1;
                    $response['storeOffers']  = (!empty($storeOffers)) ? $storeOffers : 'No records found';
                break;*/

                case 'StoreItems':

                    $deal = $offer = $cuisines = $offerPercentage = $offerRange = '';
                    $categoryList = array();

                    $serachAddress = $this->request->data['google_address'];

                    $this->Store->recursive = 0;
                    $storeDetails = $this->Store->find('first', array(
                                            'conditions' => array('Store.id'    => $storeId,
                                                                 'Store.status' => 1),
                                            'fields' => array('Store.id', 'collection', 'delivery',
                                                            'delivery_option', 'contact_name', 'contact_phone',
                                                            'contact_email', 'address', 'store_name', 'store_logo',
                                                            'tax', 'minimum_order', 'delivery_charge',
                                                            'estimate_time', 'delivery_distance')
                                            ));

                    if (!empty($storeDetails)) {

                        if ($storeDetails['Store']['collection'] == 'Yes' ||
                            $storeDetails['Store']['delivery'] == 'Yes' &&
                            $storeDetails['Store']['delivery_option'] == 'Yes') {

                            $productList = $this->Product->find('all', array(
                                                'conditions' => array('Product.store_id'    => $storeId,
                                                                      'Product.status'      => 1,
                                                                      'MainCategory.status' => 1,
                                                        'OR' => array('Store.collection' => 'Yes',
                                                                      'Store.delivery'   => 'Yes')),
                                                'order' => array('Product.category_id')));
                            if (!empty($productList)) {

                                $category = array();

                                foreach ($productList as $key => $value) {
                                    if (!in_array($value['Product']['category_id'], $category)) {
                                        $category[] = $value['Product']['category_id'];
                                    }
                                }

                                $categoryLists = $this->Category->find('all', array(
                                                    'conditions' => array('Category.status' => 1,
                                                            'OR' => array('Category.id' => $category)),
                                                    'fields' => array('Category.id', 'Category.category_name')));


                                foreach ($categoryLists as $key => $value) {
                                    $categoryList[] = $value['Category'];
                                }


                                $this->Deal->recursive = 2;
                                $dealProducts = $this->Deal->find('all', array(
                                                    'conditions' => array('Deal.store_id' => $storeId,
                                                        'Deal.status' => 1,
                                                        'MainProduct.status' => 1,
                                                    )));


                                foreach ($dealProducts as $key => $value) {
                                    if (empty($deal)) {
                                        if ($value['MainProduct']['MainCategory']['status'] == 1) {
                                            $deal = 'yes';
                                        }
                                    }
                                }


                                // Store Order / Pre Order
                                $objCommon   = new CommonsController;
                                $currentDate = date('d-m-Y');
                                list($content,
                                        $openCloseStatus,
                                        $firstStatus,
                                        $secondStatus) = $objCommon->getDateTime($storeId, $currentDate);

                                if ($openCloseStatus == 'Open') {
                                    $storeDetails['Store']['status'] = ($firstStatus == 'Open' || $secondStatus == 'Open')
                                            ? 'Order' : 'Pre Order';
                                } else {
                                    $storeDetails['Store']['status'] = 'Pre Order';
                                }

                                $storeOffers    = $this->storeOffer($storeId);

                                if (!empty($storeOffers)) {

                                    $offerPercentage = $storeOffers['Storeoffer']['offer_percentage'];
                                    $offerRange      = $storeOffers['Storeoffer']['offer_price'];

                                    $offer = $offerPercentage.'% OFF today on orders over';
                                    $offer .= ' Rs. ' .$storeOffers['Storeoffer']['offer_price'];

                                }

                                $storeDetails['Store']['offer']           = $offer;
                                $storeDetails['Store']['offerRange']      = $offerRange;
                                $storeDetails['Store']['offerPercentage'] = $offerPercentage;
                                

                                $storeLogoUrl = $this->siteUrl.'/storelogos/';
                                $storeDetails['Store']['store_logo'] = $storeLogoUrl.$storeDetails['Store']['store_logo'];
                                
                                $ratingDetail = $this->Review->find('first', array(
                                                    'conditions'=>array('Review.store_id' => $storeId,
                                                                        'Review.status' => 1),
                                                    'fields' => array('SUM(Review.rating) AS rating',
                                                                    'Count(Review.rating) AS ratingCount')));

                                $storeDetails['Store']['rating'] = (!empty($ratingDetail[0]['ratingCount'])) ?
                                                                        ($ratingDetail[0]['rating']/$ratingDetail[0]['ratingCount'])*20 : 0;
                                // Cuisines list
                                $cuisineName = $this->Cuisine->find('list', array('fields' => array('id','cuisine_name')));
                                $getStoreCuisines = $this->StoreCuisine->find('all', array(
                                                                    'conditions' => array('Cuisine.status' => 1,
                                                                                'StoreCuisine.store_id' => $storeId),
                                                                    'fields' => array('StoreCuisine.store_id',
                                                                                    'StoreCuisine.id', 'StoreCuisine.store_id',
                                                                                    'Cuisine.id', 'Cuisine.cuisine_name'),
                                                                    'order' => array('StoreCuisine.cuisine_id desc'),
                                                                    'group' => array('StoreCuisine.cuisine_id')));

                                foreach ($getStoreCuisines as $key => $value) {
                                    $cuisines .= $value['Cuisine']['cuisine_name'].', ';
                                }

                                $storeDetails['Store']['cuisines'] = rtrim($cuisines, ', ');

                                $response['success']        = 1;
                                $response['deal']           = (!empty($deal)) ? $deal : 'No';
                                $response['categoryList']   = $categoryList;
                                $response['storeDetails']   = (!empty($storeDetails)) ? $storeDetails['Store'] : '';
                            } else {
                                $response['success']  = 0;
                                $response['message']  = 'Restaurant is not available';
                            }
                        } else {
                            $response['success']  = 0;
                            $response['message']  = 'Restaurant is not available';
                        }
                    } else {
                        $response['success']  = 0;
                        $response['message']  = 'Restaurant is not available';
                    }
                break;

                case 'FilterByCategory':

                    $id          = $this->request->data['categoryId'];
                    $searchKey   = isset($this->request->data['searchKey']) ? $this->request->data['searchKey'] : '';
                    $productLists = array();

                    $imageSrc = $this->siteUrl.'/stores/'.$storeId.'/products/home/';

                    $this->Product->recursive = 1;

                    if (!empty($searchKey)) {

                        $productLists = $this->Product->find('all', array(
                                                'conditions' => array(
                                                            'Product.status' => 1,
                                                            'MainCategory.status' => 1,
                                                            'Product.store_id'=> $storeId,
                                                            "Product.product_name LIKE" => "%".$searchKey."%"),
                                                'fields' => array('Product.id', 'product_name', 'product_description',
                                                                  'product_type', 'product_image', 'popular_dish',
                                                                  'spicy_dish', 'product_addons', 'price_option'),
                                                'order' => array('Product.category_id')));
                    } elseif (!empty($id)) {
                        $productLists = $this->Product->find('all', array(
                                                'conditions' => array('Product.category_id' => $id,
                                                                    'Product.status' => 1,
                                                                    'MainCategory.status' => 1,
                                                                    'Product.store_id' => $storeId),
                                                'fields' => array('Product.id', 'product_name', 'product_description',
                                                                  'product_type', 'product_image', 'popular_dish',
                                                                  'spicy_dish', 'product_addons', 'price_option'),
                                                'order' => array('Product.category_id')));
                    } else {
                        $productLists = $this->Product->find('all', array(
                                                'conditions' => array(
                                                                    'Product.status' => 1,
                                                                    'MainCategory.status' => 1,
                                                                    'Product.store_id' => $storeId),
                                                'fields' => array('Product.id', 'product_name', 'product_description',
                                                                  'product_type', 'product_image', 'popular_dish',
                                                                  'spicy_dish', 'product_addons', 'price_option'),
                                                'order' => array('Product.category_id')));
                        
                    }

                    foreach ($productLists as $key => $value) {
                        $value['Product']['product_image'] = $imageSrc.$value['Product']['product_image'];
                        $value['Product']['price']         = $value['ProductDetail'][0]['orginal_price'];
                        $productList[$key] = $value['Product'];
                    }

                    if (!empty($productList)) {
                        $response['success']      = 1;
                        $response['productList']  = $productList;
                    } else {
                        $response['success']  = 0;
                        $response['message']  = 'No records found';
                    }
                break;

                case 'DealProducts' :
                
                    $dealProduct = array();
                    $imageSrc = $this->siteUrl.'/stores/'.$storeId.'/products/home/';

                    $this->Deal->recursive = 2;
                    $dealProducts = $this->Deal->find('all', array(
                                        'conditions' => array('Deal.store_id' => $storeId,
                                            'Deal.status' => 1,
                                            'MainProduct.status' => 1,
                                        )));

                    foreach ($dealProducts as $key => $value) {

                        $deals = array();


                        if ($value['MainProduct']['MainCategory']['status'] == 1) {

                            $deals['productName']        = $value['MainProduct']['product_name'].' + '.
                                                              $value['SubProduct']['product_name'];
                            $deals['addons']             = $value['MainProduct']['product_addons'];
                            $deals['spicyDish']          = $value['MainProduct']['spicy_dish'];
                            $deals['productType']        = $value['MainProduct']['product_type'];
                            $deals['popularDish']        = $value['MainProduct']['popular_dish'];
                            $deals['orginalPrice']       = $value['MainProduct']['ProductDetail'][0]['orginal_price'];
                            $deals['subProductImage']    = $imageSrc.$value['SubProduct']['product_image'];
                            $deals['mainProductImage']   = $imageSrc.$value['MainProduct']['product_image'];
                            $deals['productDescription'] = $value['MainProduct']['product_description'];

                            $dealProduct[] = $deals;
                        }
                    }

                    if (!empty($dealProduct)) {
                        $response['success']      = 1;
                        $response['dealProducts']  = $dealProduct;
                    } else {
                        $response['success']  = 0;
                        $response['message']  = 'No records found';
                    }
                break;

                case 'ProductDetails':
                    $mainAddon = array();
                    $productId      = $this->request->data['productId'];
                    $productDetails = $this->Product->find('first', array(
                                                'conditions' => array('Product.id' => $productId)));

                    $productDetail['Details']  = $productDetails['Product'];
                    $productDetail['variants'] = $productDetails['ProductDetail'];

                    $catId   = $productDetails['Product']['category_id'];
                    $storeId = $productDetails['Product']['store_id'];

                    $this->Subaddon->recursive = 0;

                    $addons = $this->ProductAddon->find('all', array(
                                        'conditions' => array(
                                                'ProductAddon.category_id' => $catId,
                                                'ProductAddon.store_id' => $storeId,
                                                'ProductAddon.product_id' => $productId,
                                                'Subaddon.id !=' => ''),
                                        'group' => array('ProductAddon.mainaddons_id')));

                    foreach ($addons as $key => $value) {
                        $mainAddon[$key]['mainaddons_id']    = $value['Mainaddon']['id'];
                        $mainAddon[$key]['mainaddons_name']  = $value['Mainaddon']['mainaddons_name'];
                        $mainAddon[$key]['mainaddons_count'] = $value['Mainaddon']['mainaddons_count'];
                    }

                    $productDetail['MainAddon'] = $mainAddon;

                    if (!empty($productDetail)) {
                        $response['success']         = 1;
                        $response['productDetails']  = $productDetail;
                    } else {
                        $response['success']        = 0;
                        $response['message']        = 'No records found';
                    }
                break;

                case 'ProductSubAddon':

                    $productAddons = array();

                    $productId          = $this->request->data['productId'];
                    $mainAddonId        = $this->request->data['mainAddonId'];
                    $productDetailId    = $this->request->data['productDetailId'];

                    $addons = $this->ProductAddon->find('all', array(
                                        'conditions' => array(
                                                'ProductAddon.product_id' => $productId,
                                                'ProductAddon.productdetails_id' => $productDetailId,
                                                'ProductAddon.mainaddons_id' => $mainAddonId,
                                                'Subaddon.id !=' => ''),
                                        ));

                    foreach ($addons as $key => $value) {
                        $productAddons[$key]['id']              = $value['Subaddon']['id'];
                        $productAddons[$key]['subaddons_name']  = $value['Subaddon']['subaddons_name'];
                        $productAddons[$key]['subaddons_price'] = $value['ProductAddon']['subaddons_price'];
                    }

                    if (!empty($productAddons)) {
                        $response['success']        = 1;
                        $response['productAddons']  = $productAddons;
                    } else {
                        $response['success']        = 0;
                        $response['message']        = 'No addons found';
                    }
                break;

                case 'storeDealProducts':
                    $this->Deal->recursive = 2;
                    $dealProduct = $this->Deal->find('all', array(
                                        'conditions' => array('Deal.store_id' => $storeId,
                                                        'Deal.status' => 1,
                                                        'MainProduct.status' => 1,
                                                    ),
                                        'order' => array('MainProduct.category_id')));

                    foreach ($dealProduct as $key => $value) {
                        if ($value['MainProduct']['MainCategory']['status'] == 1 &&
                            $value['MainProduct']['SubCategory']['status'] == 1
                        ) {
                            $dealProducts[$key]['Deal']                         = $value['Deal'];
                            $dealProducts[$key]['Store']['store_name']          = $value['Store']['store_name'];
                            $dealProducts[$key]['MainProduct']                  = $value['MainProduct'];
                            $dealProducts[$key]['SubProduct']['product_name']   = $value['SubProduct']['product_name'];
                            $dealProducts[$key]['SubProduct']['product_image']  = $value['SubProduct']['ProductImage'][0]['image_alias'];

                        }
                    }

                    if (!empty($dealProducts)) {
                        $response['success']      = 1;
                        $response['dealProducts']  = $dealProducts;
                    } else {
                        $response['success']  = 0;
                        $response['message']  = 'No records found';
                    }
                break;

                case 'StoreTimeSlot':

                    $type       = $this->request->data['type'];
                    $orderType  = $this->request->data['orderType'];

                    $storeSlots = $this->storeTimeSlots($storeId, $orderType, $type);

                    $response['success']    = 1;
                    $response['storeSlots'] = $storeSlots;
                break;

                case 'StoreReviews':

                    $storeId = $this->request->data['storeId'];
                    $reviews = array();
                    $this->Review->recursive = -1;
                    $storeReviews = $this->Review->find('all',array(
                                              'conditions'=>array('Review.store_id' => $storeId,
                                                                  'Review.status'=>1),
                                              'order'=>array('Review.rating DESC')));
                    
                    if (!empty($storeReviews)) {

                        foreach ($storeReviews as $key => $value) {
                            $reviews[] = $value['Review'];
                        }

                        $response['success']  = 1;
                        $response['Reviews']  = $reviews;

                    } else {
                        $response['success']  = 0;
                        $response['message']  = 'No records found';
                    }

                break;

                case 'CheckOut' :

                    $storeId         = $this->request->data['store_id'];
                    $storeDetails    = $this->Store->findById($storeId);


                    switch (trim($this->request->data['page'])) {

                        case 'DeliveryLocationCheck':

                            $addressBookId    = $this->request->data['addressBookId'];
                            $locationDetails  = $this->CustomerAddressBook->findById($addressBookId);
                            $deliveryAddress  = $locationDetails['CustomerAddressBook']['google_address'];
                            $storeAddress     = $storeDetails['Store']['address'];
                            $deliveryDistance = $storeDetails['Store']['delivery_distance'];

                            $Duration = $this->Googlemap->getDistance($deliveryAddress,$storeDetails['Store']['latitude'],
                                        $storeDetails['Store']['longitude']);

                            //$response['success'] = 1;
                            if ($deliveryDistance < $Duration) {
                                 $response['success'] = 0;
                                $response['message'] = 'Not available';
                            } else {
                                 $response['success'] = 1;
                                $response['message'] = 'Available';
                            }
                        break;

                        case 'RestaurantTimes':

                            $selectDate = $this->request->data['date'];
                            $objCommon  = new CommonsController;
                            list($content,
                                $openCloseStatus,
                                $firstStatus,
                                $secondStatus) = $objCommon->getDateTimeRest($storeId, $selectDate);

                            $content = trim($content, ',');
                            $response['success'] = ($content == 'Closed') ? 0 : 1;
                            $response['message'] = $content;
                        break;

                        case 'ConformOrder':

                            $customerId      = $this->request->data['customer_id'];
                            $customerDetails = $this->Customer->findById($customerId);
                            $paymentMethod   = $this->request->data['paymentMethod'];
                            $order['order_grand_total'] = $amount  = $this->request->data['ordertotalprice'];

                            if ($paymentMethod == 'Wallet') {

                                // Wallet Payment Process
                                if ($customerDetails['Customer']['wallet_amount'] < $amount) {
                                    $response['success']    = 0;
                                    $response['message']    = 'Insufficient balance in wallet';
                                    break;
                                }
                            }

                            $cardFee        = $this->siteSetting['Sitesetting']['card_fee'];
                            $deliveryId     = $this->request->data['delivery_id'];
                            $deliveryDate   = $this->request->data['delivery_date'];

                            $order['store_id']           = $storeId;
                            $order['assoonas']           = $this->request->data['assoonas'];
                            $order['user_type']          = 'Customer';
                            $order['order_type']         = $this->request->data['order_type'];
                            $order['customer_id']        = $customerId;
                            $order['payment_type']       = $paymentMethod;
                            $order['delivery_time']      = $this->request->data['delivery_time'];
                            $order['customer_name']      = $customerDetails['Customer']['first_name']. ' '.
                                                            $customerDetails['Customer']['last_name'];
                            $order['customer_email']     = $customerDetails['Customer']['customer_email'];
                            $order['customer_phone']     = $customerDetails['Customer']['customer_phone'];
                            $order['delivery_charge']    = $this->request->data['delivery_charge'];
                            $order['order_description']  = $this->request->data['order_description'];
                            
                            

                            $tip_amount     = $this->request->data['tipamount'];
                            $offer_amount   = $this->request->data['offer_amount'];
                            $voucher_amount = $this->request->data['voucher_amount'];
                            $transaction_id = $this->request->data['transaction_id'];
                            $offer_percentage = $this->request->data['offer_percentage'];
                            $voucher_percentage = $this->request->data['voucher_percentage'];

                            $deliveryCharge = $this->request->data['delivery_charge'];

                            $order['tip_amount']    = (!empty($tip_amount)) ? $tip_amount: 0;
                            $order['delivery_date'] = ($order['assoonas'] == 'Later') ? 
                                                        date('Y-m-d', strtotime($deliveryDate)) : 
                                                        date('Y-m-d');

                            $order['tax_amount']         = $this->request->data['tax_amount'];
                            $order['voucher_code']       = $this->request->data['voucher_code'];
                            $order['offer_amount']       = (!empty($offer_amount)) ? $offer_amount : 0;
                            $order['voucher_amount']     = (!empty($voucher_amount)) ? $voucher_amount : 0;
                            $order['tax_percentage']     = $this->request->data['tax_percentage'];
                            $order['transaction_id']     = (!empty($transaction_id)) ? $transaction_id : '';
                            $order['order_sub_total']    = $this->request->data['order_sub_total'];
                            $order['offer_percentage']   = (!empty($offer_percentage)) ? $offer_percentage : 0;
                            $order['voucher_percentage'] = (!empty($voucher_percentage)) ? $voucher_percentage : 0;
                            $order['delivery_charge']    = (!empty($deliveryCharge)) ? $deliveryCharge : 0;


                            if (!empty($deliveryId)) {
                                $customerAddress = $this->CustomerAddressBook->find('first', array(
                                                'conditions' => array(
                                                        'CustomerAddressBook.id' => $deliveryId,
                                                        'CustomerAddressBook.customer_id' => $customerId)));

                            }

                            $storeAddress   = $storeDetails['Store']['address'];
                            $sourceLatLong  = $this->Googlemap->getlatitudeandlongitude($storeAddress);
                            $source_lat     = (!empty($sourceLatLong['lat'])) ? $sourceLatLong['lat'] : 0;
                            $source_long    = (!empty($sourceLatLong['long'])) ? $sourceLatLong['long'] : 0;


                            if ($order['order_type'] != 'Collection') {
                                $order['google_address'] = $customerAddress['CustomerAddressBook']['google_address'];
                                $deliveryAddress =  $order['google_address'];
                                $destinationLatLong = $this->Googlemap->getlatitudeandlongitude($deliveryAddress);
                                $order['destination_latitude']  = (!empty($destinationLatLong['lat'])) ? 
                                                                        $destinationLatLong['lat'] : 0;
                                $order['destination_longitude'] = (!empty($destinationLatLong['long'])) ? 
                                                                        $destinationLatLong['long'] : 0;
                            } else {
                                $order['google_address'] = $storeDetails['Store']['address'];
                                $order['destination_latitude']    = $source_lat;
                                $order['destination_longitude']   = $source_long;
                            }

                            $destination_lat  = $order['destination_latitude'];
                            $destination_long = $order['destination_longitude'];
                            $distance = $this->Googlemap->getDrivingDistance($source_lat,$source_long,$destination_lat,$destination_long);

                            $order['distance'] = (isset($distance['distanceText'])) ? $distance['distanceText'] : 0 ;

                            $order['source_latitude']   = $source_lat;
                            $order['source_longitude']  = $source_long;

                            $this->Order->save($order, null, null);

                            $update['ref_number'] = '#ORD00'.$this->Order->id;
                            $update['id'] = $this->Order->id;

                            $this->Order->save($update, null, null);

                            // Shopping Cart Process
                            $cartDetails = json_decode($this->request->data['cartdetails']);
                            foreach ($cartDetails as $key => $value) {

                                $productDetails = $this->ProductDetail->find('first', array(
                                                    'conditions' => array('ProductDetail.id' => $value->menu_id),
                                                    'fields' => array('Product.product_image')));

                                $shoppingCart['id'] = '';
                                $shoppingCart['store_id']            = $storeId;
                                $shoppingCart['order_id']            = $this->Order->id;
                                $shoppingCart['product_id']          = $value->menu_id;
                                $shoppingCart['product_name']        = $value->menu_name;
                                $shoppingCart['product_price']       = $value->Total/$value->quantity;
                                $shoppingCart['subaddons_name']      = $value->Addon_name;
                                $shoppingCart['product_quantity']    = $value->quantity;
                                $shoppingCart['product_total_price'] = $value->Total;
                                $shoppingCart['product_description'] = $value->instruction;

                                $shoppingCart['product_image'] = (isset($productDetails['Product']['product_image'])) ?
                                                    $productDetails['Product']['product_image'] : 'no-image.jpg';

                                $this->ShoppingCart->save($shoppingCart, null, null);
                            }

                            

                            if ($paymentMethod != 'cod') {

                                $orderUpdate['id'] = $this->Order->id;
                                

                                $orderUpdate['payment_method']      = 'paid';
                                $orderUpdate['cardfee_percentage']  = $cardFee;
                                $orderUpdate['cardfee_price']       = $amount * ($cardFee/100);

                                if ($paymentMethod == 'Wallet') {

                                    // Wallet Payment Process
                                    if ($customerDetails['Customer']['wallet_amount'] >= $amount) {
                                        $customerDetails['Customer']['wallet_amount'] -= $amount;
                                        if ($this->Customer->save($customerDetails, null, null)) {

                                            // Wallet History
                                            $walletHistory['amount']        = $amount;
                                            $walletHistory['purpose']       = 'Transaction on order '. $update['ref_number'];
                                            $walletHistory['customer_id']   = $customerId;
                                            $walletHistory['transaction_type']    = 'Debited';
                                            $walletHistory['transaction_details'] = $update['ref_number'];

                                            $this->WalletHistory->save($walletHistory, null, null);
                                        }

                                    }
                                }

                                $this->Order->save($orderUpdate);
                            }

                            // Ordermail and sms
                            if ($_SERVER['HTTP_HOST'] == 'halal-resto.fr') {
                                $this->ordermail($this->Order->id);
                                $this->ordersms($this->Order->id);
                            }

                            $response['success']    = 1;
                            $response['message']    = 'Your order placed successfully';
                            $response['order_id']   = $this->Order->id;
                        break;

                        case 'CardPayment':

                            $amount       = number_format($this->request->data['amount'],2);
                            $customerId   = $this->request->data['customer_id'];
                            $stripeCardId = $this->request->data['card_id'];
                            
                            if (empty($stripeCardId)) {

                                $saveOrNot  = $this->request->data['saveCard'];
                                $cardNumber = $this->request->data['stripe_cardnumber'];
                                $stripe['exp_year']         = $this->request->data['stripe_expyear'];
                                $stripe['exp_month']        = $this->request->data['stripe_expmonth'];
                                $stripe['customer_id']      = $customerId;
                                $stripe['card_number']      = substr($cardNumber, -4);
                                $stripe['customer_name']    = $this->request->data['cardHolderName'];
                                $stripe['stripe_token_id']  = $stripeToken = $this->request->data['stripe_token'];

                                $datas    = array("stripeToken" => $stripeToken);
                                $customer = $this->Stripe->customerCreate($datas);
                                $stripId  = $stripe['stripe_customer_id'] = (!empty($customer)) ? $customer['stripe_id'] : '';

                                if ($saveOrNot == 'Yes') {
                                    $this->StripeCustomer->save($stripe, null, null);
                                    $stripeCardId = $this->StripeCustomer->id;

                                }
                                
                            } else {
                                $stripeCard = $this->StripeCustomer->find('first', array(
                                                    'conditions' => array(
                                                            'StripeCustomer.id' => $stripeCardId,
                                                            'StripeCustomer.customer_id' => $customerId)));

                                if (empty($stripeCard['StripeCustomer']['stripe_customer_id'])) {
                                    $datas    = array("stripeToken" => $stripeCard['StripeCustomer']['stripe_token_id']);
                                    $customer = $this->Stripe->customerCreate($datas);
                                    $stripId  = $stripeCard['StripeCustomer']['stripe_customer_id'] = $customer['stripe_id'];
                                    $this->StripeCustomer->save($stripeCard);
                                } else {
                                    $stripId = $stripeCard['StripeCustomer']['stripe_customer_id'];
                                }
                            }

                            $data = array('currency' => $this->siteSetting['Country']['currency_code'],
                                            "amount"   => $amount,
                                            "stripeCustomer" => $stripId);

                            $stripeResponse = $this->Stripe->charge($data);

                            if ($stripeResponse['status'] == "succeeded" && $stripeResponse['stripe_id'] != '') {

                                $response['success']  = 1;
                                $response['message']  = 'Payment successfully';
                                $response['transaction_id']  = $stripeResponse['stripe_id'];

                            } else {
                                $response['success']  = 0;
                                $response['message']  = 'Some technical problem. Please try again!';
                            }
                        break;
                    }
                break;

                case 'Payment':
                    $customerId  = $this->request->data['customer_id'];

                    $stripeCards = $this->StripeCustomer->find('all', array(
                                                'conditions' => array('StripeCustomer.customer_id' => $customerId)));

                    if (!empty($stripeCards)) {
                        $response['success']      = 1;
                        $response['stripeCards']  = $stripeCards;
                    } else {
                        $response['success']  = 0;
                        $response['message']  = 'No records found';
                    }
                break;

                case 'VoucherAdded':

                    $voucherCode = $this->request->data['voucherCode'];
                    $customerId  = $this->request->data['customer_id'];

                    $Today          = date("m/d/Y");
                    $voucherDetails = $this->Voucher->find('first', array(
                                                'conditions' => array(
                                                        'Voucher.store_id' => $storeId,
                                                        'Voucher.status'   => 1,
                                                        'Voucher.voucher_code' => trim($voucherCode),
                                                        'Voucher.from_date <=' => $Today,
                                                        'Voucher.to_date >='  => $Today)));
                    
                    if (!empty($voucherDetails)) {

                        if ($voucherDetails['Voucher']['type_offer'] == 'single') {
                            $usedCoupon = $this->Order->find('first', array(
                                                'conditions' => array(
                                                            'Order.customer_id'  => $customerId,
                                                            'Order.voucher_code' => $voucherCode,
                                                            'Order.status !='    => 'Failed',
                                                            'Order.store_id'     => $storeId)));
                            if (!empty($usedCoupon)) {
                                $response['success']     = 0;
                                $response['message']     = 'Voucher already used.';
                            } else {
                                $response['success']        = 1;
                                $response['voucherDetails'] = $voucherDetails['Voucher'];
                            }
                        } else {
                            $response['success']        = 1;
                            $response['voucherDetails'] = $voucherDetails['Voucher'];
                        }
                    } else {
                        $response['success']     = 0;
                        $response['message']     = 'Voucher code is not valid.';
                    }
                break;

                case 'DriverToken':
                
                    $driverId  = $this->request->data('driverId');
                    $deviceId    = $this->request->data('deviceId');
                    $driver    = $this->Driver->findById($driverId);
                    if (!empty($driver)) {
                        $driver['Driver']['device_id'] = $deviceId;
                        $this->Driver->save($driver);
                        $response['success'] = 1;
                        $response['message'] = 'device token updated';
                        
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'device token is not change';
                    }
                break;

                case 'Authorized':
                
                    $driverId   = $this->request->data('driverId');
                    $driver    = $this->Driver->findById($driverId);
                    if (!empty($driver)) {
                        if ($driver['Driver']['is_logged'] == 1) {
                            $response['success'] = 1;
                            $response['message'] = 'Authorized Person';
                        } else {
                            $response['success'] = 0;
                            $response['message'] = 'Unauthorized Person';
                        }
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'Missing driver id';
                    }
                break;

                case 'GetLocation':


                    $lat  = $this->request->data['latitude'];
                    $long = $this->request->data['longitude'];

                    $address = $this->Googlemap->getAddress($lat,$long);

                    $response['success'] = '1';
                    $response['address'] = $address;
                break;

            }
        } else {
            $response['success'] = '0';
            $response['message'] = 'Invalid request';
        }
        die(json_encode($response));
    }


    // Customer Register Mail
    public function signUpMail($customerId = null, $tmpPassword = null) {

        $customerDetails = $this->Customer->findById($customerId);
        //Mail Processing From Admin To Customer
        $newRegisteration = $this->Notification->find('first', array(
                                    'conditions'=>array('Notification.title'=>'Customer activation')));

        if($newRegisteration) {
            $regContent = $newRegisteration['Notification']['content'];
            $regsubject = $newRegisteration['Notification']['subject'];
        }

        $adminEmail   = $this->siteSetting['Sitesetting']['admin_email'];
        $source       = $this->siteUrl.'/siteicons/logo.png';
        $mailContent  = $regContent;
        $userID       = $this->Customer->id;
        $siteUrl      = $this->siteUrl;
        $activation   = $this->siteUrl. '/users/activeLink/'.$userID;
        $customerName = $customerDetails['Customer']['first_name'];
        $siteName     = $this->siteSetting['Sitesetting']['site_name'];

        $mailContent  = str_replace("{firstname}", $customerName, $mailContent);
        $mailContent  = str_replace("{activation}", $activation, $mailContent);
        $mailContent  = str_replace("{siteUrl}", $siteUrl, $mailContent);
        $mailContent  = str_replace("{store name}",$siteName, $mailContent);

        if (!empty($tmpPassword)) {
            $mailContent .='<p> This is your tmp password:'. $tmpPassword.'</p>';
        }


        $email        = new CakeEmail();
        $email->from($adminEmail);
        $email->to($customerDetails['Customer']['customer_email']);
        $email->subject($regsubject);
        $email->template('register');
        $email->emailFormat('html');
        $email->viewVars(array('mailContent' => $mailContent,
                                'source'     => $source,
                                'storename'  => $siteName));

        $email->send();

        if (!empty($customerDetails['Customer']['customer_phone'])) {

            //Signup Sms
            $customerMessage = 'Thank you for registering with '.$siteName.'.Click on below link to activate your account.'.$activation.'. Thanks '.$siteName;
            $toCustomerNumber = '+'.$this->siteSetting['Country']['phone_code'].$customerDetails['Customer']['customer_phone'];
            $customerSms      = $this->Twilio->sendSingleSms($toCustomerNumber, $customerMessage);
        }

        //Mailchimp Process
        $merge_vars = array(
                'EMAIL' => $customerDetails['Customer']['customer_email'],
                'FNAME' => $customerDetails['Customer']['first_name'],
                'LNAME' => $customerDetails['Customer']['last_name']
              );
        
        $this->Mailchimp->MCAPI($this->mailChimpKey);
        $list = $this->Mailchimp->listSubscribe($this->mailChimpListId, $customerDetails['Customer']['customer_email'], $merge_vars);
        return true;
    }

    // Restaurant list
    public function storesList($cityId = null, $areaId = null) {

        $stores = $storeList = array();

        $storeLists = $this->Product->find('all', array(
                            'conditions' => array(
                                                'Store.status' => 1,
                                                'Store.store_city' => $cityId,
                                                'Product.status' => 1,
                                                'MainCategory.status' => 1,
                                    'OR' => array('Store.collection' => 'Yes',
                                                   'Store.delivery'  => 'Yes')),
                            'group' => array('Store.id')));


        foreach ($storeLists as $key => $value) {

            if ($value['Store']['collection'] == 'Yes' ||
                $value['Store']['delivery'] == 'Yes' &&
                $value['Store']['delivery_option'] == 'Yes') {

                if (!empty($areaId)) {
                    $storeDeliver = $this->DeliveryLocation->find('all', array(
                            'conditions' => array('DeliveryLocation.location_id' => $areaId,
                                                  'Location.status' => 1,
                                                  'DeliveryLocation.store_id' => $value['Store']['id']),
                            'group' => array('DeliveryLocation.store_id')));

                    if (!empty($storeDeliver)) {
                        $stores[] = $value['Store']['id'];
                    } elseif ($value['Store']['collection'] != 'No') {
                        $stores[] = $value['Store']['id'];
                    }
                } else {
                    $stores[] = $value['Store']['id'];
                }
            }
        }

        $this->Store->recursive = 0;
        $storeList = $this->Store->find('all', array(
                            'conditions' => array('Store.id' => $stores),
                            'fields' => array('Store.id', 'Store.store_name', 'store_logo', 'minimum_order'),
                            'group' => array('Store.id')));

        foreach ($storeList as $key => $value) {
            $ratingDetail = $this->Review->find('first', array(
                                'conditions'=>array('Review.store_id' => $value['Store']['id'],
                                                    'Review.status' => 1),
                                'fields' => array('SUM(Review.rating) AS rating',
                                                'Count(Review.rating) AS ratingCount')));

            $storeList[$key] = $value['Store'];

            $storeList[$key]['rating'] = (!empty($ratingDetail[0]['ratingCount'])) ? 
                                                $ratingDetail[0]['rating']/$ratingDetail[0]['ratingCount'] : 0;
        }

        return $storeList;
    }

    // Google address based store list
    public function searchByAddress($serachAddress) {

        $getStore       = '';
        $storeLogoUrl = $this->siteUrl.'/storelogos/';

        $this->Product->recursive = 0;
        $storeLists = $this->Product->find('all', array(
                            'conditions' => array(
                                                'Store.status' => 1,
                                                'Product.status' => 1,
                                                'MainCategory.status' => 1,
                                    'OR' => array('Store.collection' => 'Yes',
                                                   'Store.delivery'  => 'Yes')),
                            'group' => array('Store.id'),
                            'fields' => array('Store.id')));

        foreach ($storeLists as $key => $value) {
            $getStore[$key] = $value;

            $StoreDetails = $this->Store->find('first', array(
                                            'conditions' => array('Store.id' => $value['Store']['id']),
                                            'fields' => array('Store.id',
                                                            'Store.store_name', 'Store.store_logo',
                                                            'Store.address', 'Store.minimum_order',
                                                            'Store.estimate_time', 'Store.delivery_distance',
                                                            'Store.delivery_charge','Store.latitude', 'Store.longitude')));

            $getStore[$key]['Store']        = $StoreDetails['Store'];
            $getStore[$key]['StoreCuisine'] = $StoreDetails['StoreCuisine'];

        }

        if (!empty($getStore)) {
            foreach ($getStore as $key => $val) {
                $deliveryDistance = $val['Store']['delivery_distance'];

                $Duration = $this->Googlemap->getDistance(
                        $serachAddress,
                        $val['Store']['latitude'],
                        $val['Store']['longitude']
                );

                if ($deliveryDistance >= $Duration) {
                    $val['Store']['distance'] = $Duration;
                    $storeDetails[$key] = $val;

                    $objCommon = new CommonsController;
                    $currentDate = date('d-m-Y');
                    list($content,
                            $openCloseStatus,
                            $firstStatus,
                            $secondStatus) = $objCommon->getDateTime($val['Store']['id'], $currentDate);

                    if ($openCloseStatus == 'Open') {
                        $val['Store']['status'] = ($firstStatus == 'Open' || $secondStatus == 'Open')
                                ? 'Order' : 'Pre Order';
                        $storeDetails[$key] = $val;
                    } else {
                        $val['Store']['status'] = 'Pre Order';
                        $storeDetails[$key] = $val;
                    }

                    $ratingDetail = $this->Review->find('first', array(
                        'fields' => array(
                            'SUM(Review.rating) AS rating',
                            'Count(Review.rating) AS ratingCount'
                        ),
                        'conditions' => array(
                            'Review.store_id' => $val['Store']['id'],
                            'Review.status' => 1
                        )
                    ));

                    $storeDetails[$key]['Store']['rating'] = (!empty($ratingDetail[0]['ratingCount'])) ?
                        ($ratingDetail[0]['rating']/$ratingDetail[0]['ratingCount'])*20 : 0;
                }
            }
        }

        $cuisineName = $this->Cuisine->find('list', array(
            'fields' => array(
                'id',
                'cuisine_name'
            )
        ));

        $distance = array();
        foreach ($storeDetails as $key => $row) {
            $distance[$key] = $row['Store']['distance'];
        }

        array_multisort($distance, SORT_ASC, $storeDetails);
        foreach($storeDetails as $keyword => $value) {

            $storeOffers = $this->storeOffer($value['Store']['id']);

            $value['Store']['offer']      = (!empty($storeOffers)) ? 'Yes' : 'No';
            $value['Store']['store_logo'] = $storeLogoUrl.$value['Store']['store_logo'];
            $storeCuisines = '';
            foreach ($value['StoreCuisine'] as $key => $val) {
                $storeCuisines .= $cuisineName[$val['cuisine_id']].', ';
            }

            $value['Store']['store_cuisines'] = rtrim($storeCuisines, ', ');
            $storeList[$keyword] = $value['Store'];

            $restId[]      = $value['Store']['id'];

        }


        $getStoreCuisines = $this->StoreCuisine->find('all', array(
                                    'conditions' => array('Cuisine.status' => 1,
                                            'AND' => array('StoreCuisine.store_id' => $restId)),
                                    'fields' => array('StoreCuisine.store_id',
                                                    'COUNT(StoreCuisine.cuisine_id) AS cuisineCount',
                                                    'StoreCuisine.id', 'StoreCuisine.store_id',
                                                    'Cuisine.id', 'Cuisine.cuisine_name'),
                                    'order' => array('StoreCuisine.cuisine_id desc'),
                                    'group' => array('StoreCuisine.cuisine_id')));


        foreach ($getStoreCuisines as $key => $value) {
            $value['Cuisine']['cuisineCount'] = $value[0]['cuisineCount'];
            $getStoreCuisine[$key] = $value['Cuisine'];
        }
        return array($getStoreCuisine, $storeList);
    }

    // Restaurant Offer
    public function storeOffer($storeId = null) {

        $today          = date("m/d/Y");
        $this->Storeoffer->recursive = -1;
        $storeOffers    = $this->Storeoffer->find('first', array(
                                    'conditions' => array(
                                                        'Storeoffer.store_id' => $storeId,
                                                        'Storeoffer.status' => 1,
                                                        "Storeoffer.from_date <=" => $today,
                                                        "Storeoffer.to_date >="   => $today),
                                    'order' => 'Storeoffer.id DESC'));
        return $storeOffers;
    }

    // Store Min order check
    public function storeMinOrderCheck($sessionId = null) {

        $this->ShoppingCart->recursive = 2;
        $storeProduct = $this->ShoppingCart->find('all',array(
                                        'conditions' => array('ShoppingCart.session_id' => $sessionId),
                                        'fields' => array('store_id',
                                                         'COUNT(ShoppingCart.store_id) AS productCount',
                                                         'SUM(ShoppingCart.product_total_price) As productTotal'),
                                        'group'=>array('ShoppingCart.store_id')));
        foreach ($storeProduct as $key => $value) {
            if ($value['Store']['minimum_order'] > $value[0]['productTotal']) {
                return false;
            }
        }
        return true;
    }

    // Addressbook list
    public function addressBook($customerId = null, $page = null) {

        if ($page == 'checkout') {
            $addressBook = $this->CustomerAddressBook->find('all',array(
                                'conditions'  => array('CustomerAddressBook.customer_id' => $customerId,
                                                        'CustomerAddressBook.status' => 1),
                                'fields' => array('CustomerAddressBook.id', 'CustomerAddressBook.address_title',
                                                  'CustomerAddressBook.google_address', 'CustomerAddressBook.address_phone',
                                                  'CustomerAddressBook.status')
                                ));
        } else {
            $addressBook = $this->CustomerAddressBook->find('all',array(
                                'conditions'  => array('CustomerAddressBook.customer_id' => $customerId,
                                        'NOT' => array('CustomerAddressBook.status' => 3)),
                                'fields' => array('CustomerAddressBook.id', 'CustomerAddressBook.address_title',
                                                  'CustomerAddressBook.google_address', 'CustomerAddressBook.address_phone',
                                                  'CustomerAddressBook.status')
                                ));
        }

        foreach ($addressBook as $key => $value) {
            $addressBook[$key]  = $value['CustomerAddressBook'];
        }
        return $addressBook;
    }

    // Order Review page
    public function orderReviews($storeTimeSlots = null, $orderTypes = null) {

        $data   = explode(',', $storeTimeSlots);
        $today  = date("m/d/Y");
        $orderTypes     = explode(',', $orderTypes);

        foreach ($data as $key => $value) {

            $deliverySlot = $this->DeliveryTimeSlot->findById($value);

            if (!empty($deliverySlot)) {

                $deliveryDetails[$key]['store_id'] = $deliverySlot['Store']['id'];
                $deliveryDetails[$key]['store_name'] = $deliverySlot['Store']['store_name'];

                if ($orderTypes[$key] == 'Delivery') {
                    $deliveryDetails[$key]['delivery_charge'] = $deliverySlot['DeliveryTimeSlot']['delivery_charge'];
                }
                $deliveryDetails[$key]['delivery_time_slot'] = $deliverySlot['TimeSlot']['time_from'].
                                            ' TO '.$deliverySlot['TimeSlot']['time_to'];
            }

            $storeOffers = $this->Storeoffer->find('first', array(
                                        'conditions' => array('Storeoffer.store_id' => $deliverySlot['Store']['id'],
                                                            'Storeoffer.status' => 1,
                                                             "Storeoffer.from_date <="  => $today,
                                                             "Storeoffer.to_date >="    => $today),
                                        'order' => 'Storeoffer.id DESC'));

            $storeProduct = $this->ShoppingCart->find('all',array(
                                        'conditions' => array('ShoppingCart.session_id' => $sessionId),
                                        'fields' => array('store_id',
                                                         'SUM(ShoppingCart.product_total_price) As productTotal'),
                                        'group'=>array('ShoppingCart.store_id')));
            
            if (!empty($storeOffers)) {

                if ($storeOffers['Storeoffer']['offer_price'] <= $storeProduct[$key][0]['productTotal']) {
                    $offerDetails[$key]['offerPercentage'] = $storeOffers['Storeoffer']['offer_percentage'];
                    $offerDetails[$key]['storeOffer'] = $storeProduct[$key][0]['productTotal'] *( 
                                                            $storeOffers['Storeoffer']['offer_percentage']/100); 
                }
            }
            if (!empty($deliverySlot['Store']['tax'])) {
                $taxDetails[$key]['store_name'] = $deliverySlot['Store']['store_name'];
                $taxDetails[$key]['tax']        = $deliverySlot['Store']['tax'];
                $taxDetails[$key]['taxAmount']  = $storeProduct[$key][0]['productTotal'] *( 
                                                            $deliverySlot['Store']['tax']/100);
            }
            $offerDetails[$key]['store_id']     = $deliverySlot['Store']['id'];
            $offerDetails[$key]['store_name']   = $deliverySlot['Store']['store_name'];
        }


        $shopCart = $this->ShoppingCart->find('all', array(
                                    'conditions' => array('ShoppingCart.session_id' => $sessionId),
                                    'order' => array('ShoppingCart.store_id')));

        $reviewOrder['shopCart'] = $shopCart;
        $reviewOrder['deliveryDetails'] = $deliveryDetails;
        $reviewOrder['offerDetails'] = $offerDetails;
        $reviewOrder['taxDetails'] = $taxDetails;

        return $reviewOrder;
    }

    // Order mail
    public function ordermail($orderId) {

        $datas    = $this->Order->findById($orderId);
        $store_id = $datas['Order']['store_id'];

        // Pusher in Store and Admin
        $message = 'New Order Came - '.$datas['Order']['ref_number'];
        $this->Notifications->pushNotification($message, 'FoodOrderAdmin');
        $this->Notifications->pushNotification($message, 'Restaurant_'.$store_id);

        $statusmailCustomer = $this->Notification->find('first',array(
                                  'conditions'=>array('Notification.title =' => 'Order details mail')));

        $statusmailSeller   = $this->Notification->find('first',array(
                                  'conditions'=>array('Notification.title =' => 'Order sellar Mail')));

        $customerContent = $statusmailCustomer['Notification']['content'];
        $customerSubject = $statusmailCustomer['Notification']['subject'];

        $sellerContent = $statusmailSeller['Notification']['content'];
        $sellerSubject = $statusmailSeller['Notification']['subject'];


        $name= '<table width="100%" border="0" style="border-color:#d9d9d9;"  cellspacing="0">
          <tbody><tr style="border-bottom:1px solid #ccc">
                <th style="font:bold 14px/30px Arial;background:#09a925;color:#ffffff; padding:8px; border:1px solid #d9d9d9;border-right:0;" width="3%">
                  S.No</th>
                <th style="font:bold 14px/30px Arial;background:#09a925;color:#ffffff; padding:8px; border:1px solid #d9d9d9;border-right:0;" width="25%">
                  Menu Name</th>
                <th style="font:bold 14px/30px Arial;background:#09a925;color:#ffffff; padding:8px; border:1px solid #d9d9d9;border-right:0;" width="5%">
                  Qty</th>
                <th style="font:bold 14px/30px Arial;background:#09a925;color:#ffffff; padding:8px; border:1px solid #d9d9d9;border-right:0;" width="5%">
                  Price</th>
                <th style="font:bold 14px/30px Arial;background:#09a925;color:#ffffff; padding:8px; border:1px solid #d9d9d9;border-right:0;" width="12%">
                  Total Price</th>
        </tr>';

        $source   = $this->siteUrl.'/siteicons/logo.png';
        $Currency = $this->siteSetting['Country']['currency_symbol'];

        $order_description = '';
        if(!empty($datas['Order']['order_description'])) {
            $order_description = '<div style="width:100%; display:inline-block;">
                            <span style="width:45%; display:inline-block;font:bold 15px Arial; text-align:right; margin:5px 0;">
                              Instructions :
                            </span>
                            <span style="width:45%; display:inline-block;font:15px Arial; margin:5px 0; padding-left:10px;"> '.
                              $datas['Order']['order_description'].'
                            </span>
                          </div>';
         }


        foreach ($datas['ShoppingCart'] as $key => $data) {

            $product_description = '';
            if(!empty($data['product_description'])) {
                $product_description = '<br>'.$data['product_description'];
            }

            $subaddons = '';
            if(!empty($data['subaddons_name'])) {
                $subaddons = '<br>'.$data['subaddons_name'];
            }

          $productSrc = $this->siteUrl.'/stores/'.$store_id.'/products/carts/'.$data['product_image'];

          $serialNo = $key+1;

          $name.='<tr>
                <td style="border:1px solid #09a925;border-top:0;border-right:0;text-align:center; color:#000;font:14px Arial;border-left:0;" >'.$serialNo.'
                <td style="border:1px solid #09a925;border-top:0;border-right:0;text-align:left; color:#000;font:14px Arial"><span style="padding:10px; display:inline-block;">'.
                  $data['product_name'].' '.$subaddons.' '.$product_description.'</span></td>
                <td style="border:1px solid #09a925;border-top:0;border-right:0;text-align:center; color:#000;font:14px Arial" >'.
                  $data['product_quantity'].'</td>
                <td style="border:1px solid #09a925;border-top:0;border-right:0;text-align:right; padding-right:10px; color:#000;font:14px Arial" width="10%">'.
                  $Currency." ".$data['product_price'] .'</td>
                <td style="border:1px solid #09a925;border-top:0;text-align:right; padding-right:10px; color:#000;font:14px Arial;border-right:0;" >'.
                  $Currency." ".$data['product_total_price'] .'</td>
          </tr>'; 
        }

        $name.='<tr>
              <td colspan="4" style="text-align:right; padding-right:10px;font:bold 16px/30px Arial; border:1px solid #09a925;border-right:0;border-top:0;border-left:0;">
                  Sub-Total</td>
              <td style="text-align:right; padding-right:10px;font:16px/30px Arial; border:1px solid #09a925;border-top:0;border-right:0;">'.
                $Currency." ".$datas['Order']['order_sub_total'] .'</td>
          </tr>';


        if ($datas['Order']['offer_amount'] > 0) {
          $name.='<tr>
                <td colspan="4" style="text-align:right; padding-right:10px;font:bold 16px/30px Arial; border:1px solid #09a925;border-right:0;border-top:0;border-left:0;">
                    Offer ('.$datas['Order']['offer_percentage'].' %) </td>
                <td style="text-align:right; padding-right:10px;font:16px/30px Arial; border:1px solid #09a925;border-top:0;border-right:0;">'.
                  $Currency." ".$datas['Order']['offer_amount'] .'</td>
            </tr>';
        }

        if($datas['Order']['voucher_amount'] != 0) {
          $name.='<tr>
                <td colspan="4" style="text-align:right; padding-right:10px;font:bold 16px/30px Arial; border:1px solid #09a925;border-right:0;border-top:0;border-left:0;">
                    Voucher Discount';
              if ($datas['Order']['voucher_percentage'] > 0) {
                  $name.= ' ('.$datas['Order']['voucher_percentage'].' %)';
              }
          $name.= '</td>
                <td style="text-align:right; padding-right:10px;font:16px/30px Arial; border:1px solid #09a925;border-top:0;border-right:0;">'.
                  $Currency." ".$datas['Order']['voucher_amount'] .'</td>
            </tr>';
        }

        if ($datas['Order']['tax_amount'] > 0) {
          $name.='<tr>
                <td colspan="4" style="text-align:right; padding-right:10px;font:bold 16px/30px Arial; border:1px solid #09a925;border-right:0;border-top:0;border-left:0;">
                    Tax ('.$datas['Order']['tax_percentage'].' %)</td>
                <td style="text-align:right; padding-right:10px;font:16px/30px Arial; border:1px solid #09a925;border-top:0;border-right:0;">'.
                  $Currency." ".$datas['Order']['tax_amount'] .'</td>
            </tr>';
        }

        if ($datas['Order']['delivery_charge'] != 0 || $datas['Order']['voucher_code'] != '' && $datas['Order']['voucher_amount'] == 0) {
          $name.='<tr>
                <td colspan="4" style="text-align:right; padding-right:10px;font:bold 16px/30px Arial; border:1px solid #09a925;border-right:0;border-top:0;border-left:0;">
                    Delivery Charge</td>
                <td style="text-align:right; padding-right:10px;font:16px/30px Arial; border:1px solid #09a925;border-top:0;border-right:0;">';
          $name.= ($datas['Order']['delivery_charge'] > 0) ? 
                            $Currency . ' ' . $datas['Order']['delivery_charge'] : 'Free Delivery';
          $name.='</td></tr>';
        }

        if ($datas['Order']['tip_amount'] > 0) {
            $name.='<tr>
              <td colspan="4" style="text-align:right; padding-right:10px;font:bold 16px/30px Arial; border:1px solid #09a925;border-right:0;border-top:0;border-left:0;"> Tip </td>
              <td style="text-align:right; padding-right:10px;font:16px/30px Arial; border:1px solid #09a925;border-top:0;border-right:0;">'.
                $Currency." ".$datas['Order']['tip_amount'] .'</td>
          </tr>';
        }

        $name.='<tr>
              <td colspan="4" style="text-align:right;  padding-right:10px;color:#09a925;font:bold 18px/30px Arial; border:1px solid #09a925;border-right:1px solid #09a925; border-bottom:1px solid #09a925;border-top:0;border-right:0;border-left:0;">
                Total</td>
              <td style="text-align:right; padding-right:10px;color:#09a925;font:bold 18px/30px Arial; border:1px solid #09a925;border-right:1px solid #09a925; border-bottom:1px solid #09a925; border-top:0;border-right:0;">'.
                $Currency." ".$datas['Order']['order_grand_total'].' </td>
          </tr>
        </table>
        </div>';

        $orderType = ($datas['Order']['order_type'] == 'Collection') ? 'Pickup' : $datas['Order']['order_type'];

        $Address .= '<div style="width:100%; display:inline-block; ">
                    <div style="width:100%; display:inline-block;margin-top:20px;">
                      <div style="width:46%; display:inline-block; vertical-align:top; padding-left:20px;">

                        <div style="width:100%; display:inline-block;">
                          <h3 style="font-family:Arial; color:#09a925;" >
                            Here is your order information</th> </h3>
                          <div style="width:100%; display:inline-block;">
                              <span style="width:45%; display:inline-block;font:bold 15px Arial; text-align:right; margin:5px 0;">
                                  Order Number/ID :
                              </span>
                              <span style="width:45%; display:inline-block;font:15px Arial; margin:5px 0; padding-left:10px;">'.
                                $datas['Order']['ref_number'].'
                              </span> 
                          </div>
                          <div style="width:100%; display:inline-block;">
                            <span style="width:45%; display:inline-block;font:bold 15px Arial; text-align:right; margin:5px 0;">
                              Payment Method :
                            </span>
                            <span style="width:45%; display:inline-block;font:15px Arial; margin:5px 0; padding-left:10px;">'.
                              str_ireplace('cod', 'cash on delivery', $datas['Order']['payment_type']) .'
                            </span> 
                          </div>
                          <div style="width:100%; display:inline-block;">
                            <span style="width:45%; display:inline-block;font:bold 15px Arial; text-align:right; margin:5px 0;">
                                Order Type : 
                             </span>
                            <span style="width:45%; display:inline-block;font:15px Arial; margin:5px 0; padding-left:10px;"> '.
                              $orderType.'
                            </span> 
                          </div>  
                          <div style="width:100%; display:inline-block;">
                            <span style="width:45%; display:inline-block;font:bold 15px Arial; text-align:right; margin:5px 0;">
                              '.$orderType.' Time :
                            </span>
                            <span style="width:45%; display:inline-block;font:15px Arial; margin:5px 0; padding-left:10px;"> '.
                              $datas['Order']['delivery_time'].'
                            </span>
                          </div>
                          '.$order_description.'
                        </div>
                        </div>
                        <div style="width:45%; display:inline-block;border-left:1px dotted #09a925;min-height:200px; padding-left:30px;vertical-align:top;">
                          <div style="width:100%; display:inline-block;">
                            <h3 style="font-family:Arial; color:#09a925;" >
                              Address
                            </h3>
                          </div>
                          <div style="width:100%; display:inline-block;">
                            <span style="width:100%; display:inline-block;font:bold 15px Arial;margin:5px 0;">'.
                              $datas['Order']['customer_name'].'
                            </span>';

                        $Address .= '<span style="width:100%; display:inline-block;font:15px Arial; margin:5px 0;">'.
                                  $datas['Order']['google_address'].'
                              </span>';

                        $Address .= ' <span style="width:100%; display:inline-block;font:bold 15px Arial;margin:5px 0;">'.
                                  $datas['Order']['customer_phone'].'</span>
                            </div>
                          </div>
                        </div>';

        $customer_mail = $datas['Order']['customer_email'];
        $customerName  = $datas['Order']['customer_name'];
        $storename     = $datas['Store']['store_name'];
        $sitemailId    = $this->siteSetting['Sitesetting']['admin_email'];

        $mailContent   = $customerContent;
        $siteUrl       = $this->siteUrl;

        $mailContent     = str_replace("{Customer name}", $customerName, $mailContent);
        $mailContent     = str_replace("{source}", $source, $mailContent);
        $mailContent     = str_replace("{Store name}", $storename, $mailContent);
        $mailContent     = str_replace("{orderid}", $datas['Order']['ref_number'], $mailContent);
        $mailContent     = str_replace("{note}", $name, $mailContent);
        $mailContent     = str_replace("{Address}", $Address, $mailContent);
        $mailContent     = str_replace("{SITE_URL}", $siteUrl, $mailContent);
        $customerSubject = str_replace("[with Order ID]", $datas['Order']['ref_number'], $customerSubject);
        $customerSubject = str_replace("store name", $storename, $customerSubject);

        $email = new CakeEmail();
        $email->from($sitemailId);
        $email->to($customer_mail);
        $email->subject($customerSubject);
        $email->template('ordermail');
        $email->emailFormat('html');
        $email->viewVars(array('mailContent' => $mailContent,
                            'source' => $source,
                            'storename' => $storename));

        $email->send();

        if ($datas['Store']['email_order'] == 'Yes' && !empty($datas['Store']['order_email'])) {

          $storemailId = $datas['Store']['order_email'];
          $mailContent = $sellerContent;
          $mailContent   = str_replace("{Customer name}", $customerName, $mailContent);
          $mailContent   = str_replace("{source}", $source, $mailContent);
          $mailContent   = str_replace("{Store name}", $storename, $mailContent);
          $mailContent   = str_replace("{orderid}", $datas['Order']['ref_number'], $mailContent);
          $mailContent   = str_replace("{note}", $name, $mailContent);
          $mailContent   = str_replace("{Address}", $Address, $mailContent);
          $mailContent   = str_replace("{SITE_URL}", $siteUrl, $mailContent);
          $sellerSubject = str_replace("[ Order ID ]", $datas['Order']['ref_number'], $sellerSubject);
          $sellerSubject = str_replace("customer name", $customerName, $sellerSubject);

          $email = new CakeEmail();
          $email->from($customer_mail); 
          $email->to($storemailId);
          $email->subject($sellerSubject);
          $email->template('ordermail');
          $email->emailFormat('html');
          $email->viewVars(array('mailContent' => $mailContent,
                                'source' => $source,
                                'storename' => $storename));
          
          $email->send();
        }
        return true;
    }

    //Order Sms
    public function ordersms($orderId) {

        $orderDetail = $this->Order->findById($orderId);
        
        $customerMessage = 'Thanks for using '.$this->siteSetting['Sitesetting']['site_name'].' service. Your order '.$orderDetail['Order']['ref_number'].' has been placed . Track your order at '.$this->siteUrl.'.  Regards '.$this->siteSetting['Sitesetting']['site_name'].'.';
        $toCustomerNumber = '+'.$this->siteSetting['Country']['phone_code'].$orderDetail['Customer']['customer_phone'];
        $customerSms      = $this->Twilio->sendSingleSms($toCustomerNumber, $customerMessage);

        if ($orderDetail['Store']['sms_option'] == 'Yes' && !empty($orderDetail['Store']['sms_phone'])) {
            $storeMessage  = "Dear ".$orderDetail['Store']['store_name']." you've received a ";
            $storeMessage .= ($orderDetail['Order']['payment_method'] != 'paid') ? 'COD' : 'PAID';
            $storeMessage .= ' order '.$orderDetail['Order']['ref_number'].' from '.$orderDetail['Order']['customer_name'];

            if ($orderDetail['Order']['order_type'] == 'Delivery') {
                $storeMessage .= ','.$orderDetail['Order']['google_address'];
            }

            $storeMessage .= '. '.$orderDetail['Order']['order_type'].' due on '.$orderDetail['Order']['delivery_date'].' at '.$orderDetail['Order']['delivery_time'].'. Thanks '.$this->siteSetting['Sitesetting']['site_name'].'';
            $toStoreNumber = '+'.$this->siteSetting['Country']['phone_code'].$orderDetail['Store']['sms_phone'];
            $customerSms   = $this->Twilio->sendSingleSms($toStoreNumber, $storeMessage);
        }

        // Store Owner App Message
        if ($orderDetail['Store']['is_logged'] == 1) {
          $deviceId      = $orderDetail['Store']['device_id'];
          $message      = 'New order came - '.$orderDetail['Order']['ref_number'];

          /*$gcm = $this->AndroidResponse->sendOrderByGCM(
                  array('message'    => $message),
                          $deviceId);*/

          $androidMessage = array('message' => $message);

          $gcm  = (trim($orderDetail['Store']['device_name']) == 'android') ?
                      $this->AndroidResponse->sendOrderByGCM($androidMessage, $deviceId) :
                      $this->PushNotifications->notificationIOS($message, $deviceId, 'Restaurant','neworder');

        }
        return true;
    }
}