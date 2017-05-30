<?php
/* janakiraman */
App::import('Vendor', 'Mpdf', array('file' => 'mpdf' . DS . 'mpdf.php'));
App::uses('CakeEmail', 'Network/Email');
App::uses('AppController','Controller');

class CustomersController extends AppController {    
  public $helpers    = array('Html','Form', 'Session', 'Javascript');
  public $uses       = array('Customer', 'User', 'CustomerAddressBook', 'City', 'Location', 'State',
                              'Order','ShoppingCart', 'StripeCustomer' ,'ProductImage',  'Notification',
                              'Review', 'WalletHistory');
  public $components = array('Updown','Functions','Mpdf', 'Stripe');
  /**
   * CustomersController::admin_index()
   * Admin View CustomerManagement
   * @return void
   */
  public function admin_index() {
    
  	$Customer_list    = $this->Customer->find('all',array(
                        'conditions' => array('Customer.status !=' => 3),
                        'order'=>array('Customer.id DESC')));

    $this->set(compact('Customer_list'));
  }
   /**
    * CustomersController::admin_index()
    * Admin View Particular Customer Detail
    * @return void
    */
  public function admin_customerIndex() {
    $ids              = $this->params['pass'];
    $id               = $ids[0]; 
    if ($id == null) {
        $this->redirect(array('controller' => 'Customers','action' => 'index'));
    } else {
        $addressbook_list = $this->CustomerAddressBook->find('all',array(
                                                            'conditions'=>array(
                                                            'CustomerAddressBook.customer_id'=>$id)));    
         $this->set("addressbook_list",$addressbook_list);        
    }
  }
  /**
   * CustomersController::admin_add()
   * Admin AddCustomer Process
   * @return void
   */
  public function admin_add(){
    if($this->request->is('post') || $this->request->is('put')) {
      $this->Customer->set($this->request->data);
      if($this->Customer->validates()) {
        $CustomerExist = $this->User->find('first', array(
                                'conditions' => array('User.role_id' => 4,
                                      'User.username' => trim($this->request->data['Customer']['customer_email']),
                                  'NOT' => array('Customer.status' => 3))));
        $StoreExists = $this->User->find('first', array(
                        'conditions' => array('User.role_id' => 3,
                                    'User.username' => trim($this->request->data['Customer']['customer_email']),
                                'NOT' => array('Store.status' => 3))));
        if (!empty($CustomerExist) || !empty($StoreExists)) {

          $this->Session->setFlash('<p>'.__('Already Exists Users', true).'</p>', 'default', 
                                              array('class' => 'alert alert-danger'));
        } else {
            $this->request->data['User']['role_id'] = 4;
            $this->request->data['User']['username'] = $this->request->data['Customer']['customer_email'];
            $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);
            $this->User->save($this->request->data['User'],null,null);
            $this->request->data['Customer']['user_id'] = $this->User->id;
            $this->Customer->save($this->request->data['Customer'],null,null);

            //Mail Processing From Admin To Customer
             $newRegisteration = $this->Notification->find('first',array(
                                      'conditions'=>array('Notification.title'=>'Customer activation')));
            if($newRegisteration){

                // $regContent = $newRegisteration['Notification']['content'];
                $regsubject = $newRegisteration['Notification']['subject'];
            }

            $regContent = '<table class="container content" align="center"> <tbody><tr> <td>
                      <table class="row note">
                            <tbody> <tr> <td class="wrapper last">
                                  <p>Cher {firstname},</p>
                                  <p> '.htmlentities('L’équipe d’').'{store name}  vous souhaite la bienvenue en tant que nouveau membre,
                                  <p>Veuillez cliquez <a href="{activation}"> ici </a> pour confirmer votre adresse email. </p> </td> </tr>
                            </tbody>
                      </table> </td> </tr>
                </tbody></table>';

                
            $adminEmail   = $this->siteSetting['Sitesetting']['admin_email']; 

            $mailContent  = $regContent;
            $userID       = $this->Customer->id;
            $siteUrl      = $this->siteUrl;
            $source	 	    = $this->siteUrl.'/siteicons/logo.png';
            $activation   = $this->siteUrl. '/users/activeLink/'.$userID;
            $customerName = $this->request->data['Customer']['first_name'];
            $store_name   = $this->siteSetting['Sitesetting']['site_name'];
            $mailContent  = str_replace("{firstname}", $customerName, $mailContent);
            $mailContent  = str_replace("{activation}", $activation, $mailContent);
            $mailContent  = str_replace("{siteUrl}", $siteUrl, $mailContent);
            $mailContent  = str_replace("{store name}",$store_name, $mailContent);
            $email        = new CakeEmail();
            $email->from($adminEmail);
            $email->to($this->request->data['Customer']['customer_email']);
            $email->subject($regsubject);
            $email->template('register');
            $email->emailFormat('html');
            $email->viewVars(array('mailContent' => $mailContent,
                                    'source' => $source,
                                    'storename' => $store_name));
            $email->send();

            $this->Session->setFlash('<p>'.__('Users has been saved', true).'</p>', 'default', 
                                                array('class' => 'alert alert-success'));
            $this->redirect(array('controller' => 'Customers','action' => 'index'));
        }
      } else {
          $this->Customer->validationErrors;
      }
    } 
  }
   /**
   * CustomersController::admin_edit()
   * Admin Edit Customer Process
   * @param mixed $id
   * @return void
   */
  public function admin_edit($id = null) {
    if ($this->request->is('post') || $this->request->is('put')) {
      $this->Customer->set($this->request->data);
      if($this->Customer->validates()) {

        $customer = $this->Customer->findById($this->request->data['Customer']['id']);
        $customerEmailCheck = $this->User->find('first', array(
                                    'conditions'=>array('User.role_id' => 4,
                                    'User.username'=>trim($this->request->data['Customer']['customer_email']),
                                     'NOT' => array('User.id' => $customer['User']['id'],
                                                    'Customer.status' => 3))));
        $StoreExists = $this->User->find('first', array(
                        'conditions' => array('User.role_id' => 3,
                                    'User.username' => trim($this->request->data['Customer']['customer_email']),
                                'NOT' => array('Store.status' => 3))));
        if(!empty($customerEmailCheck)  || !empty($StoreExists)) {
            $this->Session->setFlash('<p>'.__('User Email Already Exists', true).'</p>', 'default', 
                                              array('class' => 'alert alert-danger'));
        } else {
          $customerDetails = $this->Customer->findById($this->request->data['Customer']['id']);
          $customerDetails['User']['username'] = trim($this->request->data['Customer']['customer_email']);

          if ($this->User->save($customerDetails['User'], null, null)) {
              $this->Customer->save($this->request->data,null,null);
          }
          $this->Session->setFlash('<p>'.__('Your Customer has been saved', true).'</p>', 'default', 
                                            array('class' => 'alert alert-success'));
          $this->redirect(array('controller' => 'Customers','action' => 'index'));
        }
      } else {
          $this->Customer->validationErrors;
      }
    } 
    $getStateData        = $this->Customer->findById($id);
    $this->request->data = $getStateData;
  }
   /**
   * CustomersController::customer_editaddressbook()
   * Admin Edit Customer AddressBook
   * @return void
   */
  public function admin_editAddressBook($id = null) {
    if ($this->request->is('post') || $this->request->is('put')) {

        $this->CustomerAddressBook->set($this->request->data);
        if($this->CustomerAddressBook->validates()) {
            $address_check   = $this->CustomerAddressBook->find('first',array(
                              'conditions'=>array(
                                          'CustomerAddressBook.address_title'=>
                                                  trim($this->request->data['CustomerAddressBook']['address_title']),
                                          'CustomerAddressBook.customer_id' =>  $this->request->data['CustomerAddressBook']['ids'],
                                  'NOT'=>array('CustomerAddressBook.id'=> $this->request->data['CustomerAddressBook']['id']))));

            if(!empty($address_check)) {
                 $this->Session->setFlash('<p>'.__('Address Book Already Exists', true).'</p>', 'default',
                                              array('class' => 'alert alert-danger'));
            } else {
                 $this->CustomerAddressBook->save($this->request->data,null,null);
                 $this->Session->setFlash('<p>'.__('Your CustomerAddressBook has been saved', true).'</p>', 'default', array('class' => 'alert alert-success'));
                 $this->redirect(array('controller' => 'Customers','action' => 'index'));
            }
        } else {
            $this->CustomerAddressBook->validationErrors;
        }
    }


    $getStateData        = $this->CustomerAddressBook->findById($id);
    $this->request->data = $getStateData;
    $this->set('state_list', $this->State->find('list', array(
                                    'conditions' => array('State.country_id' => $this->siteSetting['Sitesetting']['site_country']),
                                    'fields' => array('id','state_name'))));

    $this->set('city_list', $this->City->find('list', array(
                                      'conditions' => array('City.state_id' => $getStateData['CustomerAddressBook']['state_id']),
                                      'fields' => array('id','city_name'))));
    if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {
      $this->set('location_list', $this->Location->find('list', array(
                                      'conditions' => array('Location.city_id' => $getStateData['CustomerAddressBook']['city_id']),
                                      'fields' =>array('id','zip_code'))));
    } else {
      $this->set('location_list', $this->Location->find('list', array(
                                      'conditions' => array('Location.city_id' => $getStateData['CustomerAddressBook']['city_id']),
                                      'fields' =>array('id','area_name'))));
    }
  }
  /**
   * CustomersController::customer_myaccount()
   * Customer Myaccount Detail
   * @return void
   */
  public function customer_myaccount() {
    $this->layout        = 'frontend';
    if (!empty($this->request->data['Customer']['customer_phone']) && !empty($this->request->data['Customer']['first_name'])) {

      if ($this->Auth->User('role_id') == 4) {
          $this->request->data['User']['id']     = $this->Auth->User('id');
          $this->request->data['Customer']['id'] = $this->Auth->User('Customer.id');

          $allowed_ext = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif');

          if (!empty($this->request->data['Customer']['image']['name'])) {
            $imagesizedata = getimagesize($this->request->data['Customer']['image']['tmp_name']);

            if (!empty($imagesizedata)) {
              if(in_array($this->request->data['Customer']['image']['type'], $allowed_ext)) {
                $logoPath = WWW_ROOT."Customers";
                $flagname = $this->Updown->uploadFile($this->request->data['Customer']['image'],$logoPath);
                $this->request->data['Customer']['image'] = $flagname['refName'];
              } else {
                $this->request->data['Customer']['image'] = $this->request->data['Customer']['org_logo'];
              }
            } else {
              $this->request->data['Customer']['image'] = $this->request->data['Customer']['org_logo'];
            }
          }  else {
               $this->request->data['Customer']['image'] = $this->request->data['Customer']['org_logo'];
          }

          $this->Customer->save($this->request->data, null, null);
          $this->Auth->login();
          $this->Session->setFlash('<p>' . __('Your detail has been updated', true) . '</p>', 'default',
                                                              array('class' => 'alert alert-success'));
          $this->redirect(array('controller' => 'Customers', 'action' => 'myaccount'));
      }
    }

    if(!empty($this->request->data['review']['rating'])){      
      $order_info     = $this->Order->findById($this->request->data['review']['id']);
      $store_id       = $order_info['Store']['id'];
      $order_id       = $order_info['Order']['id'];
      $customer_id    = $order_info['Customer']['id'];
      $checking       = $this->Review->find('all',array(
                              'conditions'=>array(                              
                              'Review.order_id'=>$this->request->data['review']['id'])));
      if(empty($checking)){
        $this->request->data['Review']['id']          = '';
        $this->request->data['Review']['store_id']    = $store_id ;
        $this->request->data['Review']['order_id']    = $order_id ;
        $this->request->data['Review']['customer_id'] = $customer_id ;
        $this->request->data['Review']['rating']      = $this->request->data['review']['rating'] ;
        $this->request->data['Review']['message']     = $this->request->data['review']['message'] ;
        $this->Review->save($this->request->data['Review']);

        $this->Session->setFlash('<p>'.__('Thank you for your review', true).'</p>', 'default', 
                                          array('class' => 'alert alert-success'));

      } else {
            $this->Session->setFlash('<p>'.__('Review Already Exsits', true).'</p>', 'default', 
                                          array('class' => 'alert alert-danger'));
             $this->redirect(array('controller' => 'Customers','action' => 'myaccount'));

      }   

    }    
    $ids                 = $this->Auth->User();
    $getStateData        = $this->User->findById($ids['id']);
    $order_detail        = $this->Order->find('all', array(
                                        'conditions'=> array('Order.customer_id' => $ids['Customer']['id'],
                                                'NOT' => array('Order.status' => 'Deleted')),
                                          'order' => array('Order.id DESC')));

    $addressBook         = $this->CustomerAddressBook->find('all',array(
                                            'conditions'=>array(
                                            'CustomerAddressBook.customer_id'=>$ids['Customer']['id'],
                                            'NOT'=>array('CustomerAddressBook.status'=>3))));
    $Stripe_detail       = $this->StripeCustomer->find('all',array(
                                            'conditions'=>array('StripeCustomer.customer_id'=>$ids['Customer']['id']),
                                            'order' => 'StripeCustomer.id DESC'));
    $this->request->data =  $getStateData;
    $country             =  $this->siteSetting;
    $country_id          =  $country['Sitesetting']['site_country'];
    $state_list          =  $this->State->find('list',array(
                                                'conditions'=>array('State.country_id'=>$country_id),
                                                'fields'=>array('State.id','State.state_name')));

    $walletHistory       = $this->WalletHistory->find('all', array(
                                      'conditions' => array('WalletHistory.customer_id' => $ids['Customer']['id']),
                                      'order' => 'WalletHistory.id Desc'));

    $month  = array("1"=>"Jan", "2"=>"Feb", "3"=>"Mar",
                    "4"=>"Apr", "5"=>"May", "6"=>'Jun',
                    "7"=>"Jul", "8"=>"Aug", "9"=>"Sep",
                    "10"=>"Oct", "11"=>"Nov", "12"=>"Dec");

    $storeId = ($this->Session->read('storeId')) ? $this->Session->read('storeId') : '';

    $this->set(compact('state_list','addressBook','order_detail','Stripe_detail','month','walletHistory','storeId'));
  }

  // Customer Change Username / Email
  public function customer_changeCustomerEmail() {

      if ($this->request->is('post') || $this->request->is('put')) {
          $this->request->data['Customer']['customer_email'] = trim($this->request->data['Customer']['customer_email']);
          $this->Customer->set($this->request->data);
          if($this->Customer->validates()) {
              $CustomerExist = $this->User->find('first', array(
                              'conditions' => array('User.role_id' => 4,
                                          'User.username' => trim($this->request->data['Customer']['customer_email']),
                                      'NOT' => array('Customer.status' => 3))));
              $StoreExists = $this->User->find('first', array(
                              'conditions' => array('User.role_id' => 3,
                                          'User.username' => trim($this->request->data['Customer']['customer_email']),
                                      'NOT' => array('Store.status' => 3))));

              if (!empty($CustomerExist) || !empty($StoreExists)) {
                  $this->Session->setFlash('<p>' . __('Email Already Exists', true) . '</p>', 'default',
                                                              array('class' => 'alert alert-danger'));
                  $this->redirect(array('controller' => 'Customers', 'action' => 'myaccount'));
              } else {
                  $newEmail = $this->request->data['User']['username'] = trim($this->request->data['Customer']['customer_email']);
                  
                  $this->request->data['User']['id']     = $this->Auth->User('id');
                  $this->request->data['Customer']['id'] = $this->Auth->User('Customer.id');
                  $this->request->data['Customer']['status'] = 2;

                  $this->User->save($this->request->data, null, null);
                  $this->Customer->save($this->request->data, null, null);
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
                  $customerName = $this->Auth->User('Customer.first_name');
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

                  $adminEmail   = $this->siteSetting['Sitesetting']['admin_email'];
                  $mailContent  = $oldUserContent;
                  $userID       = $this->Customer->id;
                  $customerName = $this->Auth->User('Customer.first_name');

                  $mailContent  = str_replace("{firstname}", $customerName, $mailContent);
                  $mailContent  = str_replace("{SITE_URL}", $siteUrl, $mailContent);
                  $mailContent  = str_replace("{store name}",$store_name, $mailContent);
                  $mailContent  = str_replace("{email}",$newEmail, $mailContent);
                  $email        = new CakeEmail();
                  $email->from($adminEmail);
                  $email->to($this->Auth->User('Customer.customer_email'));
                  $email->subject($oldUsersubject);
                  $email->template('register');
                  $email->emailFormat('html');
                  $email->viewVars(array('mailContent' => $mailContent,
                                          'source'=>$source,
                                          'storename' => $store_name));
                  $email->send();

                  $this->redirect(array('controller' => 'users', 'action' => 'userLogout', 'customer' => true));
              }
          } else {
              $this->Customer->validationErrors;
          }
      }
      $this->redirect(array('controller' => 'Customers', 'action' => 'myaccount'));
  }

  // Customer Myaccount Card add
  public function customer_customerCardAdd() {
    $this->layout        = 'frontend';

    if (!empty($this->request->data['StripeCustomer'])) {
      
      $this->request->data['StripeCustomer']['customer_id'] = $this->Auth->User('Customer.id');
      $this->StripeCustomer->save($this->request->data['StripeCustomer']);
    }

    $this->Session->setFlash('<p>'.__('Your card has been added successfully', true).'</p>', 'default', 
                                          array('class' => 'alert alert-success'));
    $this->redirect(array('controller' => 'customers', 'action' => 'myaccount'));
  }

  /**
   * CustomersController::customer_editaddressbook()
   * Customer Edit AddressBook
   * @return void
   */
  public function customer_editaddressbook() {
      if ($this->request->is('post') || $this->request->is('put')) {
          $this->CustomerAddressBook->set($this->request->data);
          if($this->CustomerAddressBook->validates()) {

              $customerAddressBook = $this->CustomerAddressBook->find('first', array(
                              'conditions' => array('CustomerAddressBook.id' => 
                                                      $this->request->data['CustomerAddressBook']['id'],
                                              'CustomerAddressBook.customer_id' => $this->Auth->User('Customer.id'))));
              if (!empty($customerAddressBook)) {
                  $this->CustomerAddressBook->save($this->request->data, null, null);
                  $this->Session->setFlash('<p>' . __('Your address book has been updated successfully', true) . '</p>', 'default',
                      array('class' => 'alert alert-success'));
                  $this->redirect(array('controller' => 'customers', 'action' => 'myaccount'));
              }
          } else {
              $this->CustomerAddressBook->validationErrors;
          }
      }
      $id                  = $this->request->data['id'];
      $getStateData        = $this->CustomerAddressBook->findById($id);
      $this->request->data = $getStateData;

      $this->set('state_list', $this->State->find('list', array(
                                          'conditions' => array('State.country_id' => $this->siteSetting['Sitesetting']['site_country']),
                                          'fields' => array('id','state_name'))));

      $this->set('city_list', $this->City->find('list', array(
                                          'conditions' => array('City.state_id' => $getStateData['CustomerAddressBook']['state_id']),
                                          'fields' => array('id','city_name'))));

      if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {
          $this->set('location_list', $this->Location->find('list', array(
                                          'conditions' => array('Location.city_id' => $getStateData['CustomerAddressBook']['city_id']),
                                          'fields' =>array('id','zip_code'))));
      } else {
          $this->set('location_list', $this->Location->find('list', array(
                                          'conditions' => array('Location.city_id' => $getStateData['CustomerAddressBook']['city_id']),
                                          'fields' =>array('id','area_name'))));
      }
  }

  public function customer_editaddresschecking(){

      $title = $this->request->data['title'];
      $ids = (isset($this->request->data['id'])) ? $this->request->data['id'] : 0;

      if (!empty($title)) {
          $address_check = $this->CustomerAddressBook->find('first', array(
              'conditions' => array(
                  'CustomerAddressBook.address_title' => trim($title),
                  'CustomerAddressBook.customer_id' => $this->Auth->User('Customer.id'),
                  'NOT' => array('CustomerAddressBook.id' => $ids))));

          echo (!empty($address_check)) ? 'failed' : 'success';
      }
      exit();
  }

   /**
   * CustomersController::customer_locationfillte()
   * Customer location Fillter
   * @return void
   */
  public function customer_locationFillter(){
    $id 	        = $this->request->data['id'];

    if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {
        $this->set('location_list', $this->Location->find('list', array(
                                        'conditions' => array('Location.city_id' => $id),
                                        'fields' =>array('id','zip_code'))));
    } else {
        $this->set('location_list', $this->Location->find('list', array(
                                        'conditions' => array('Location.city_id' => $id),
                                        'fields' =>array('id','area_name'))));
    }
  }
  
  /**
   * CustomersController::customer_addAddressBook()
   * Customer Add AddressBook
   * @return void
   */
  public function customer_addAddressBook() {
    if ($this->request->is('post') || $this->request->is('put')) {
          $this->CustomerAddressBook->set($this->request->data);
          if($this->CustomerAddressBook->validates()) {

              $address_check   = $this->CustomerAddressBook->find('first',array(
                                        'conditions' => array(
                                              'CustomerAddressBook.address_title'=>
                                              trim($this->request->data['CustomerAddressBook']['address_title']),
                                              'CustomerAddressBook.customer_id' => $this->Auth->User('Customer.id')
                                              )));
              if(!empty($address_check)) {
                   $this->Session->setFlash('<p>'.__('Address Book Already Exists', true).'</p>', 'default', 
                                                array('class' => 'alert alert-danger'));
                   $this->redirect(array('controller' => 'Customers','action' => 'myaccount'));
              } else {


                  $this->request->data['CustomerAddressBook']['customer_id'] = $this->Auth->User('Customer.id');
                  $this->CustomerAddressBook->save($this->request->data['CustomerAddressBook']);

                  $this->Session->setFlash('<p>'.__('Votre carnet d’adresses a été ajouté avec succès', true).'</p>', 'default', 
                                                        array('class' => 'alert alert-success'));
                  $this->redirect(array('controller' => 'Customers', 'action' => 'myaccount'));
              }
          } else {
              $this->CustomerAddressBook->validationErrors;
          }
    }
  }
  /**
   * CustomersController::customer_cityfillter()
   * Customer CityFillter
   * @return void
   */
  public function customer_cityFillter() {
    $id 	    = $this->request->data['id'];
   	$City_list  = $this->City->find('list',array(
                                    'conditions'=>array('City.state_id'=>$id),
                                    'fields'=>array('City.id','City.city_name')));
    $this->set(compact('City_list'));
  }
  /**
   * CustomersController::customer_orderView()
   * Customer OrderView
   * @param mixed $id
   * @return void
   */
  public function customer_orderView($id = null) {
      $this->layout = 'frontend';
      $order_detail = $this->Order->find('first', array(
                          'conditions' => array('Order.id' => $id,
                                      'Order.customer_id' => $this->Auth->User('Customer.id'))));
      $storeId = ($this->Session->read('storeId')) ? $this->Session->read('storeId') : '';

      if (!empty($order_detail)) {
          $this->set(compact('order_detail', 'storeId'));
      } else {
          $this->render('/Errors/error400');
      }
  }

  public function customer_downloadInvoice($id = null) {
      $invoices     = $this->Order->findById($id);
      $addressMode  = $this->siteSetting['Sitesetting']['address_mode'];
      $siteCurrency = $this->siteCurrency;
      $this->Functions->generateOrderPdf($invoices, $invoices['Order']['ref_number'], $addressMode, $siteCurrency);
  }

  // Customer Pdf
  public function customer_pdf($id = null) {
      $order_detail = $this->Order->find('first', array(
                          'conditions' => array('Order.id' => $id,
                                      'Order.customer_id' => $this->Auth->User('Customer.id'))));
      if (empty($order_detail)) {
          $this->layout = 'frontend';
          $this->render('/Errors/error400');
      } else {
        $order_detail['Order']['delivery_time'] = ($order_detail['Order']['assoonas'] == 'Later')
                                                  ? $order_detail['Order']['delivery_time'] : 'ASAP';

      if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
          $address  = $order_detail['Order']['address'].', ';
          $address .= ($order_detail['Order']['landmark']) ? $order_detail['Order']['landmark'] : '';
          $address .= $order_detail['Order']['location_name'].', '.
              $order_detail['Order']['city_name'].', '.
              $order_detail['Order']['state_name'].'.';
      } else {
          $address  = $order_detail['Order']['google_address'];
      }
      $orderType = ($order_detail['Order']['order_type'] == 'Collection') ? 'Pickup' : $order_detail['Order']['order_type'];


      $output ='
        <div style="width:960px;margin:0 auto;">
          <h1 align="center">'.__('Order Invoice').'</h1>
          <table width="100%"  align="center">
            <tr style="display:block; width:100%;">
              <td style="display:inline-block;font:bold 20px/20px Verdana; padding:10px 0px 5px; text-align:left;">
                  '.$order_detail['Order']['ref_number'].'
              </td>
              <td style="display:inline-block;font:bold 20px/20px Verdana; padding:10px 0px 5px; text-align:right;">
                  '.date("Y/m/d",strtotime($order_detail['Order']['created'])).'
              </td>
            </tr>
          </table>
          <table width="100%"  align="center" style="margin:10px 0px; border:10px solid #eee;">
            <tbody >
                <tr style="display:block; width:100%;">
                    <td style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;padding-right:20px; padding-left:20px;">'.__('Customer Name').'</td>
                    <td align="" style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;">: '.$order_detail['Order']['customer_name'].'</td>
                </tr>
                <tr style="display:block; width:100%;">
                    <td style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;padding-right:20px; padding-left:20px;">'.__('Customer Email').'</td>
                    <td align="" style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;">: '. $order_detail['Order']['customer_email'].'
                    </td>
                </tr>
                <tr style="display:block; width:100%;">
                    <td style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;padding-right:20px; padding-left:20px;">'.__('Address').'</td>
                    <td align="" style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;">: '.$address.'
                    </td>
                </tr>
                <tr style="display:block; width:100%;">
                    <td style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;padding-right:20px; padding-left:20px;">'.__('Phone Number').'</td>
                    <td align="" style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;">: '.$order_detail['Order']['customer_phone'].'
                    </td>
                </tr>
                <tr style="display:block; width:100%;">
                    <td style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;padding-right:20px; padding-left:20px;">'.__($orderType).' Date </td>
                    <td align="" style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;">:'.date("Y/m/d",strtotime($order_detail['Order']['delivery_date'])).'
                    </td>
                </tr>
                <tr style="display:block; width:100%;">
                    <td style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;padding-right:20px; padding-left:20px;">'.__($orderType).' Time </td>
                    <td align="" style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;">: '. $order_detail['Order']['delivery_time'].'
                    </td>
                </tr>
                <tr style="display:block; width:100%;">
                    <td style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;padding-right:20px; padding-left:20px;">'.__('Order Type').'</td>
                    <td align="" style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;">: '. __($orderType).'
                    </td>
                </tr>
                <tr style="display:block; width:100%;">
                    <td style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;padding-right:20px; padding-left:20px;">'.__('Payment Method').'</td>
                    <td align="" style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;">: '. __($order_detail['Order']['payment_type']).'
                    </td>
                </tr>';

      $output .= '
            <tr style="display:block; width:100%;">
                <td style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;padding-right:20px; padding-left:20px;">'.__('Payment Status').' </td>
                <td align="" style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;">: ';
                      $output .= ($order_detail["Order"]["payment_method"] == "unpaid") ? 'Not Paid' : $order_detail["Order"]["payment_method"];
                    $output .='</td>
            </tr>';

      $orderStatus = ($order_detail['Order']['status'] == "Collected") ? __('Picked up') : __($order_detail['Order']['status']);

      $output .= '
            <tr style="display:block; width:100%;">
                <td style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;padding-right:20px; padding-left:20px;">'.__('Restaurant').' </td>
                <td align="" style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;">: '.$order_detail['Store']['store_name'].'
                </td>
            </tr>
            <tr style="display:block; width:100%;">
                <td style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;padding-right:20px; padding-left:20px;">'.__('Order Status').' </td>
                <td align="" style="font:14px/17px Verdana;padding:5px 0px 5px;border-bottom:1px solid #ddd;">: '.$orderStatus.'
                </td>
            </tr>';
        $output .='
            </tbody >
        </table>
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody>
                <tr class="striped" bgcolor="#bfbfbf">
                    <th align="left" width="15%" style="padding:5px 0 5px 10px;"> '.__('S.No').'</th>
                    <th align="left" width="30%" style="padding:5px 0 5px 10px;"> '.__('Item Name').'</th>
                    <th align="left" width="10%"  style="padding:5px 0 5px 10px;">'.__('Qty').'</th>
                    <th align="left" width="15%"  style="padding:5px 0 5px 10px;">'.__('Price').'</th>
                    <th align="left" width="15%"  style="padding:5px 0 5px 10px;">'.__('Total Price').'</th>
                </tr>' ;

        foreach ($order_detail['ShoppingCart'] as $key => $value) {
            $sNo = $key+1;

            $output.='<tr style="font:normal 14px Arial">
                    <td style="border-bottom:1px solid #ccc; padding:15px">'. $sNo.'</td>
                    <td style="border-bottom:1px solid #ccc; padding:15px; max-width:100%; display:inline-block; word-wrap:break-word; word-break:break-all;">'.
                        $value['product_name'].'<br>'.
                        $value['subaddons_name'].'<br>'.
                          $value['product_description'];

                    $output.= '</td>
                    <td style="border-bottom:1px solid #ccc; padding:15px">'.	$value['product_quantity'].' </td>
                    <td align="right" style="border-bottom:1px solid #ccc; padding:15px">'. $this->siteCurrency.' '.$value['product_price'].' </td>
                    <td align="right" style="border-bottom:1px solid #ccc; padding:15px">'.$this->siteCurrency.' '.$value['product_total_price'].' </td>
                </tr>' ;
                }
        $output.= '<tr class="odd gradeX">
                    <td colspan="4" align="right"  style="padding:5px 15px 5px 0;">'.__('Sub Total').'</td>
                    <td align="right"  style="padding-right:15px;">'. $this->siteCurrency.' '.$order_detail['Order']['order_sub_total'].'</td>
                </tr>';

        if($order_detail['Order']['offer_amount'] != 0) {
            $output .= '<tr class="odd gradeX">
                    <td colspan="4" align="right"  style="padding:5px 15px 5px 0;">'.__('Offer').' ('.$order_detail['Order']['offer_percentage'].' %)</td>
                    <td align="right" style="padding-right:15px;">' . $this->siteCurrency . ' ' . $order_detail['Order']['offer_amount'] . '</td>
                </tr>';
        }

        if($order_detail['Order']['voucher_amount'] != 0) {
            $output .= '<tr class="odd gradeX">
                    <td colspan="4" align="right"  style="padding:5px 15px 5px 0;">'.__('Voucher Discount');
                if ($order_detail['Order']['voucher_percentage'] > 0) {
                  $output .= ' ('.$order_detail['Order']['voucher_percentage'].' %)';
                }
            $output .= '</td>
                    <td align="right" style="padding-right:15px;">' . $this->siteCurrency . ' ' . $order_detail['Order']['voucher_amount'] . '</td>
                </tr>';
        }

        if($order_detail['Order']['tax_amount'] != 0) {
            $output .= '<tr class="odd gradeX">
                    <td colspan="4" align="right"  style="padding:5px 15px 5px 0;">'.__('Tax').' ('.$order_detail['Order']['tax_percentage'].' %)</td>
                    <td align="right" style="padding-right:15px;">' . $this->siteCurrency . ' ' . $order_detail['Order']['tax_amount'] . '</td>
                </tr>';
        }
        if ($order_detail['Order']['delivery_charge'] != 0 || $order_detail['Order']['voucher_code'] != '' && $order_detail['Order']['voucher_amount'] == 0) {

            $output .= '<tr class="odd gradeX">
                    <td colspan="4" align="right"  style="padding:5px 15px 5px 0;">'.__('Delivery Charge').'</td>
                    <td align="right"  style="padding-right:15px;">';

            $output .= ($order_detail['Order']['delivery_charge'] > 0) ? 
                        $this->siteCurrency . ' ' . $order_detail['Order']['delivery_charge'] : __('Free Delivery');

            $output .= '</td></tr>';
        }

          if($order_detail['Order']['tip_amount'] != 0) {
              $output .= '<tr class="odd gradeX">
                    <td colspan="4" align="right"  style="padding:5px 15px 5px 0;">'.__('Tip').'</td>
                    <td align="right"  style="padding-right:15px;">' . $this->siteCurrency . ' ' . $order_detail['Order']['tip_amount'] . '</td>
                </tr>';
          }

        $output .= ' <tr class="odd gradeX" style="">
                    <td colspan="4" align="right" style="padding:5px 15px 5px 0; border-bottom:1px solid #ccc;"><b> '.__('Grand Total').'</b></td>
                    <td align="right"  style="padding-right:15px; border-bottom:1px solid #ccc;"><b>'.$this->siteCurrency.' '.$order_detail['Order']['order_grand_total'] .'</b></td>
                </tr>
            </tbody>
        </table>';

        // initializing mPDF
        $this->Mpdf->init();


        $mpdf=new mPDF();
        $mpdf->WriteHTML($output);
        $mpdf->Output($order_detail['Order']['ref_number'],"D");
        exit();
      }
  }

  public function customer_deletecard($id = null) {
      $id = $this->request->data['id'];
      if (!empty($id)) {
          $customercard = $this->StripeCustomer->find('first', array(
                              'conditions' => array('StripeCustomer.id' => $id,
                                                   'StripeCustomer.customer_id' => $this->Auth->User('Customer.id'))));
          if (!empty($customercard)) {
              $this->StripeCustomer->delete($id);
              echo "success";
          } 
      }
      exit();
  }

  public function customer_addressbookStatus($id = null){
      $id       = $this->request->data['id'];
      $authuser = $this->Auth->user();
      if ((!empty($id)) && (!empty($authuser))) {
          $Status = $this->CustomerAddressBook->find('first', array(
                          'conditions' => array('CustomerAddressBook.id' => $id,
                                      'CustomerAddressBook.customer_id' => $this->Auth->User('Customer.id'))));
          if (!empty($Status)) {
              if ($Status['CustomerAddressBook']['status'] == 2) {
                  $Status['CustomerAddressBook']['status'] = 1;
              } elseif ($Status['CustomerAddressBook']['status'] == 1) {
                  $Status['CustomerAddressBook']['status'] = 0;
              } elseif ($Status['CustomerAddressBook']['status'] == 0) {
                  $Status['CustomerAddressBook']['status'] = 1;
              } else {
                  $Status['CustomerAddressBook']['status'] = 1;
              }
              $this->CustomerAddressBook->save($Status['CustomerAddressBook']);

              $Status = json_encode($Status);
              $Auth =  json_encode($authuser);
              
              $filePath      = ROOT.DS.'app'.DS."tmp".DS.'curl.txt';
              $file = fopen($filePath,"a+");
              fwrite($file, PHP_EOL.'Address---->'.$Status.PHP_EOL. 'CustomerAuth--------->'.$Auth.PHP_EOL);
              fclose($file);
          }
      } else {
          echo "sorry unable change your status";
      }
      exit();
  }

  public function customer_deleteaddress($id = null){
      $id = $this->request->data['id'];
      if (!empty($id)) {
          $customerAddressBook = $this->CustomerAddressBook->find('first', array(
                          'conditions' => array('CustomerAddressBook.id' => $id,
                                      'CustomerAddressBook.customer_id' => $this->Auth->User('Customer.id'))));

          if (!empty($customerAddressBook)) {
              $this->CustomerAddressBook->delete($id);
              echo "sucess";
          }
      }
      exit();
  }

  // Customer Change Password
  public function customer_changePassword(){
      $this->layout     = 'frontend';
      $id               = $this->Auth->User();
      $user_detail      = $this->User->findById($id['id']);
      $old_password     = $user_detail['User']['password'];
      $check_password   = $this->Auth->password($this->request->data['User']['oldpassword']);
      $newpassword      = $this->Auth->password($this->request->data['User']['newpassword']);
      $confirmpassword  = $this->Auth->password($this->request->data['User']['confirmpassword']);
     if(trim($check_password) == trim($old_password) ) {
          if($newpassword == $confirmpassword && $newpassword != '' && $confirmpassword != '') {
              $this->request->data['User']['id']       = $id['id'];
              $this->request->data['User']['password'] = $newpassword;
              $this->User->save($this->request->data['User'],null,null);
              $this->Session->setFlash('<p>'.__('Successfully change your password', true).'</p>', 'default',
                  array('class' => 'alert alert-success'));
              $this->redirect(array('controller' => 'Customers', 'action' => 'myaccount'));
          } else {
              $this->Session->setFlash('<p>'.__('Check Your Newpassword and Confirmpassword', true).'</p>', 'default',
                  array('class' => 'alert alert-danger'));
              $this->redirect(array('controller' => 'Customers', 'action' => 'myaccount'));
          }
      } else {
          $this->Session->setFlash('<p>'.__('Check Your Old Password', true).'</p>', 'default',
              array('class' => 'alert alert-danger'));
          $this->redirect(array('controller' => 'Customers', 'action' => 'myaccount'));
      }
  }

  public function customer_passchecking(){
      $pass           = $this->request->data['pass'];
      $user_detail    = $this->User->findById($this->Auth->User('id'));
      $old_password   = $user_detail['User']['password'];
      $check_password = $this->Auth->password($pass);
      if(trim($check_password) == trim($old_password) ) {
          echo "sucess";
      } else {
          echo "failed";
      }
      exit();
  }

  // Wallet Add Money
  public function customer_addWalletAmount() {

    if (!empty($this->request->data)) {

        $card   = $this->request->data['Customer']['walletCard'];
        $amount = $this->request->data['Customer']['wallet_amount'];

        if (!empty($this->request->data['StripeCustomer'])) {
            $this->request->data['StripeCustomer']['customer_id'] = $this->Auth->User('Customer.id');
            $this->StripeCustomer->save($this->request->data['StripeCustomer']);
            $card = $this->StripeCustomer->id;
        }

        $stripeCard = $this->StripeCustomer->find('first', array(
                                'conditions' => array('StripeCustomer.id' => $card,
                                                      'StripeCustomer.customer_id' => $this->Auth->User('Customer.id'))));

        if (!empty($stripeCard)) {
          if (empty($stripeCard['StripeCustomer']['stripe_customer_id'])) {

              $datas    = array("stripeToken"  => $stripeCard['StripeCustomer']['stripe_token_id']);
              $customer = $this->Stripe->customerCreate($datas);
              $stripId  = $stripeCard['StripeCustomer']['stripe_customer_id'] = $customer['stripe_id'];
              $this->StripeCustomer->save($stripeCard);

          } else {
              $stripId = $stripeCard['StripeCustomer']['stripe_customer_id'];
          }

          $data   = array('currency'       => $this->siteSetting['Country']['currency_code'],
                          "amount"         => $amount,
                          "stripeCustomer" => $stripId);
          
          $stripeResponse = $this->Stripe->charge($data);
          if ($stripeResponse['status'] == "succeeded" && $stripeResponse['stripe_id'] != '') {
            
            // Wallet Amount Added
            $this->Customer->recursive = 0;
            $customer = $this->Customer->findById($this->Auth->User('Customer.id'));
            $customer['Customer']['wallet_amount'] += $amount;
            $this->Customer->save($customer);

            // Wallet History
            $walletHistory['amount']              = $amount;
            $walletHistory['purpose']             = 'Money added in wallet';
            $walletHistory['customer_id']         = $this->Auth->User('Customer.id');
            $walletHistory['transaction_details'] = $stripeResponse['stripe_id'];

            $this->WalletHistory->save($walletHistory, null, null);

            $this->Session->setFlash('<p>'.__('Successfully added amount in your wallet', true).'</p>',
                                                  'default', array('class' => 'alert alert-success'));
          } else {
            $this->Session->setFlash('<p>'.__('Payment failed', true).'</p>', 'default',
                                                          array('class' => 'alert alert-danger'));
          }
        } else {
          $this->Session->setFlash('<p>'.__('Card is not valid', true).'</p>', 'default',
                                                          array('class' => 'alert alert-danger'));
        }
    }
    $this->redirect(array('controller' => 'customers', 'action' => 'myaccount'));
  }
}