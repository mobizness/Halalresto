<?php

/* MN */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class UsersController extends AppController {

    public $name = 'Users';
    var $helpers = array('Html', 'Session', 'Javascript', 'Ajax', 'Common');
    public $uses = array('User', 'Customer', 'Newsletters_user', 'Newsletter', 'Notification', 'Store', 'ContactUs', 'StoreTiming', 'City');
    public $components = array('Functions', 'Hybridauth', 'Mailchimp', 'Twilio', 'Attempt', 'Nocsrf');
    public $loginAttemptLimit = 3;
    public $loginAttemptDuration = '+20 Min';

    public function beforeFilter() {
        $this->Auth->allow(array('request', 'signup', 'customer_customerLogin', 'storeLogin', 'storeActiveLink',
            'activeLink', 'logout', 'social_login', 'social_endpoint', 'storeSignup', 'contactUs', 'apply'));
        parent::beforeFilter();
    }

    public function index() {
        if ($this->Auth->user('role_id') == '1' || $this->Auth->user('role_id') == "2") {

            $this->set('title_for_layout', 'Admin');
        }
    }

    public function login() {
        $this->redirect(array('controller' => 'searches', 'action' => 'index'));
    }

    public function restaurantsignup() {
        
    }

    /**
     * Displays a view
     * Admin login process
     * @param mixed What page to login
     * @return void
     */
    public function admin_login() {
        if ($this->Auth->loggedIn() && ($this->Auth->user('role_id') == 1 || $this->Auth->user('role_id') == 2)) {
            $this->redirect(array("controller" => "dashboards", "action" => "index", 'admin' => true));
        } else if ($this->Auth->loggedIn()) {
            echo '<h3 class="form-title"> Vous n’êtes pas autorisé à accéder à cette page </h3>
        			<a href="' . $this->siteUrl . '/users/logout/admin"> Cliquez ici pour vous déconnecter  </a>';
            exit();
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            //$this->User->create();
            $this->User->set($this->request->data);
            if ($this->User->validates()) {
                $roles = array(1, 2);

                $userData = $this->User->findByUsername($this->request->data['User']['username']);
                if (in_array($userData['User']['role_id'], $roles)) {
                    if ($this->Auth->login()) {
                        #REmember me            
                        if ($this->request->data['User']['rememberMe'] == 1) {
                            $this->Cookie->write('rememberMe', $this->request->data['User'], true, "12 months");
                        } else {
                            $this->Cookie->delete('rememberMe');
                        }
                        if ($userData['User']['role_id'] == "1") {
                            $this->redirect(array('controller' => 'dashboards', 'action' => 'index', 'admin' => true));
                        } else {
                            $this->redirect(array('controller' => 'Categories', 'action' => 'index', 'admin' => true));
                        }
                    } else {

                        $this->Session->setFlash('<p>' . __('Login failed your Username or Password Incorrect', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                        $this->redirect(array('controller' => 'users', 'action' => 'login', 'admin' => true));
                    }
                } else {
                    $this->Session->setFlash('<p>' . __('Login failed, unauthorized', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                    $this->redirect(array('controller' => 'users', 'action' => 'login', 'admin' => true));
                }
            } else {
                $this->User->validationErrors;
            }
        }
        $this->request->data['User'] = $this->Cookie->read('rememberMe');
        $this->set('title_for_layout', 'Admin');
    }

    public function admin_writerview() {
        $contentWriter = $this->User->find('all', array(
            'conditions' => array('User.role_id' => 2)
                )
        );
        $this->set(compact('contentWriter'));
    }

    public function admin_sendnw() {

        if ($this->request->is('post') || $this->request->is('put')) {
            $body = $this->request->data['Newsletter']['body'];
            $regsubject = $this->request->data['Newsletter']['subject'];
            $this->Newsletter->set($this->request->data);
            $this->Newsletter->save($this->request->data['Newsletter'], null, null);
            $users = $this->Newsletters_user->find('all', array(
                'conditions' => array(
                    'status' => '1'
                )
            ));
            if (sizeof($users) > 0) {
                for ($i = 0; $i < sizeof($users); $i++) {
                    $email_user = $users[$i]['Newsletters_user']['email'];
                    $regContent = '<table class="container content" align="center"> <tbody><tr> <td>
								      <table class="row note">
								            <tbody> <tr> <td class="wrapper last">
								                  <p>Hello,</p>
                                                                                    <p>' . $body . '</p>								                  
                                                                                    </td> </tr>
								            </tbody>
								      </table> </td> </tr>
								</tbody></table>';

                    $adminEmail = $this->siteSetting['Sitesetting']['admin_email'];
                    $source = $this->siteUrl . '/siteicons/logo.png';
                    $mailContent = $regContent;
                    $siteUrl = $this->siteUrl;
                    $store_name = $this->siteSetting['Sitesetting']['site_name'];

                    $email = new CakeEmail();
                    $email->from($adminEmail);
                    $email->to($email_user);
                    $email->subject($regsubject);
                    $email->template('register');
                    $email->emailFormat('html');
                    $email->viewVars(array('mailContent' => $mailContent,
                        'source' => $source,
                        'storename' => $store_name));
                    $email->send();
                }
            }
            $this->Session->setFlash('<p>' . __("Votre lettre d'information a été envoyée", true) . '</p>', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array('controller' => 'Users', 'action' => 'newsletter', 'admin' => true));
        }
    }

    public function admin_newsletter() {
        $newsletters = $this->Newsletter->find('all', array(
            'order' => array(
                'Newsletter.id DESC'
            )
        ));
        $this->set(compact('newsletters'));
    }

    public function admin_userssubscribed() {
       $users = $this->Newsletters_user->find('all', array(
                'conditions' => array(
                    'status' => '1'
                )
            ));
        $this->set(compact('users'));
    }

    public function admin_addcw() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->User->set($this->request->data);
            if ($this->User->validates()) {

                $CustomerExist = $this->User->find('first', array(
                    'conditions' => array('User.role_id' => 2,
                        'User.username' => trim($this->request->data['User']['username'])
                )));

                if (!empty($CustomerExist)) {
                    $this->Session->setFlash('<p>' . __('Email already exists', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {
                    $this->request->data['User']['role_id'] = 2;
                    $this->request->data['User']['username'] = $this->request->data['User']['username'];
                    $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);
                    $this->User->save($this->request->data['User'], null, null);
                    $this->Session->setFlash('<p>' . __('You have successfully registered an account', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'Users', 'action' => 'writerview', 'admin' => true));
                }
            } else {
                $this->Customer->validationErrors;
            }
        }
    }

    public function store_storeLogin() {
        if ($this->Auth->loggedIn() && $this->Auth->user('role_id') == 3) {
            $this->redirect(array("controller" => "dashboards", "action" => "index", 'store' => true));
        } else if ($this->Auth->loggedIn()) {
            echo '<h3 class="form-title"> Vous n’êtes pas autorisé à accéder à cette page </h3>
        			<a href="' . $this->siteUrl . '/users/logout/restaurant"> Cliquez ici pour vous déconnecter  </a>';
            exit();
        }
        if ($this->request->is('post') || $this->request->is('put')) {

            if (isset($this->request->data['Users']['email']) && $this->request->data['Users']['email'] != '') {
                $userData = $this->User->find('first', array(
                    'conditions' => array(
                        'User.username' => trim($this->request->data['Users']['email']),
                        'Store.status' => 1,
                        'User.role_id' => 3)));

                if (!empty($userData)) {
                    $newRegisteration = $this->Notification->find('first', array(
                        'conditions' => array('Notification.title =' => 'Reset password')));

                    $toemail = $this->request->data['Users']['email'];
                    $source = $this->siteUrl . '/siteicons/logo.png';
                    $storeEmail = $this->siteSetting['Sitesetting']['admin_email'];
                    $siteName = $this->siteSetting['Sitesetting']['site_name'];
                    $storename = $userData['Store']['store_name'];
                    $tmpPassword = $this->Functions->createTempPassword(7);

                    $datas['User']['password'] = $this->Auth->Password($tmpPassword);
                    $datas['User']['id'] = $userData['User']['id'];

                    if ($this->User->save($datas['User'], null, null)) {
                        if ($newRegisteration) {
                            $forgetpasswordContent = $newRegisteration['Notification']['content'];
                            $forgetpasswordsubject = $newRegisteration['Notification']['subject'];
                        }

                        $mailContent = $forgetpasswordContent;
                        $siteUrl = $this->siteUrl . '/restaurant';
                        $mailContent = str_replace("{Customer name}", $storename, $mailContent);
                        $mailContent = str_replace("{source}", $source, $mailContent);
                        $mailContent = str_replace("{title}", $siteName, $mailContent);
                        $mailContent = str_replace("{SITE_URL}", $siteUrl, $mailContent);
                        $mailContent = str_replace("{tmpPassword}", $tmpPassword, $mailContent);
                        $mailContent = str_replace("{Store name}", $siteName, $mailContent);

                        $email = new CakeEmail();
                        $email->from($storeEmail);
                        $email->to($toemail);
                        $email->subject($forgetpasswordsubject);
                        $email->template('register');
                        $email->emailFormat('html');
                        $email->viewVars(array('mailContent' => $mailContent,
                            'source' => $source,
                            'storename' => $siteName));

                        // echo "<pre>";print_r($mailContent);echo "</pre>";exit;

                        if ($email->send()) {
                            // Forget Sms
                            $storeMessage = "Nous avons reçu une demande de changement de votre mot de passe. Utilisez ce mot de passe " . $tmpPassword . " pour vous connecter à votre compte et  n’oubliez pas changer votre mot de passe provisoire dès que possible. Merci l’équipe d’ '.$this->siteSetting['Sitesetting']['site_name'].'";
                            $toStoreNumber = '+' . $this->siteSetting['Country']['phone_code'] . $userData['Store']['contact_phone'];
                            $storeSms = $this->Twilio->sendSingleSms($toStoreNumber, $storeMessage);
                            $this->Session->setFlash('<p>' . __('Email has been sent successfully', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                            $this->redirect(array('controller' => 'users', 'action' => 'storeLogin', 'store' => true));
                        }
                    }
                } else {
                    $this->Session->setFlash('<p>' . __('Vous n’êtes pas authorisé', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                    $this->redirect(array('controller' => 'users', 'action' => 'storeLogin', 'store' => true));
                }
            }
            $this->User->set($this->request->data);
            if ($this->User->validates()) {

                $csrfTokens = $this->Session->read('csrfToken');
                if ($this->request->data['User']['token'] != $csrfTokens) {
                    $this->redirect(array('controller' => 'users', 'action' => 'storeLogin', 'store' => true));
                }

                $roles = array(3);
                $userData = $this->User->findByUsername($this->request->data['User']['username']);
                if (in_array($userData['User']['role_id'], $roles)) {
                    if ($userData['Store']['status'] == 1) {
                        if ($this->Attempt->limit('login', $this->loginAttemptLimit)) {
                            if ($this->Auth->login()) {
                                #REmember me
                                if ($this->request->data['User']['rememberMe'] == 1) {
                                    $this->Cookie->write('rememberMe', $this->request->data['User'], true, "12 months");
                                } else {
                                    $this->Cookie->delete('rememberMe');
                                }
                                $this->redirect(array('controller' => 'dashboards', 'action' => 'index', 'store' => true));
                            } else {
                                $this->Attempt->fail('login', $this->loginAttemptDuration);
                                $this->Session->setFlash('<p>' . __('Login failed your Username or Password Incorrect', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('controller' => 'users', 'action' => 'storeLogin', 'store' => true));
                            }
                        } else {
                            $this->Session->setFlash('<p>' . __('Plusieurs tentatives ont échoué !', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                        }
                    } else {
                        $this->Session->setFlash('<p>' . __('Votre compte est en attente d’activation', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                        $this->redirect(array('controller' => 'users', 'action' => 'storeLogin', 'store' => true));
                    }
                } else {
                    $this->Session->setFlash('<p>' . __('Login failed, unauthorized', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                    $this->redirect(array('controller' => 'users', 'action' => 'storeLogin', 'store' => true));
                }
            } else {
                $this->User->validationErrors;
            }
        }
        $this->request->data['User'] = $this->Cookie->read('rememberMe');

        $token = $this->Nocsrf->generate('csrf_token');
        $this->Session->write('csrfToken', $token);
        $this->set(compact('token'));

        $this->set('title_for_layout', 'Restaurant Admin');
    }

    /**
     * Displays a view
     * logout process
     * @param mixed What page to logout
     * @return void
     */
    public function logout() {
        //echo "<pre>";print_r($this->params);exit();
        $role_id = $this->Auth->user("role_id");
        /* if($this->Session->check('Auth.User'))
          $this->loggedUser = $this->Session->read('Auth.User');
          $this -> Session -> setFlash('<p>'.__('Logout Successfully', true).'</p>', 'default',
          array('class' => 'alert alert-success')); */

        //echo "<pre>"; print_r($this->params['pass'][0]);
        if (isset($this->params) && $this->params['pass'][0] == 'admin') {
            $this->Auth->logout();
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'admin' => true));
        } else if (isset($this->params) && $this->params['pass'][0] == 'store') {
            $this->Auth->logout();
            $this->redirect(array('controller' => 'users', 'action' => 'storeLogin', 'store' => true));
        } else if (isset($this->params) && $this->params['pass'][0] == 'customer') {

            $this->Auth->logout();
            $this->redirect(array('controller' => 'users', 'action' => 'customerlogin', 'customer' => true));
        } else if (isset($this->params) && $this->params['pass'][0] == 'customerregister') {

            $this->Auth->logout();
            $this->redirect(array('controller' => 'users', 'action' => 'signup'));
        } else if (isset($this->params) && $this->params['pass'][0] == 'storeSignup') {

            $this->Auth->logout();
            $this->redirect(array('controller' => 'users', 'action' => 'storeSignup'));
        }

        if ($role_id == 1) {
            $this->Session->setFlash('<p>' . __('Logout successfully', true) . '</p>', 'default', array('class' => 'alert alert-success'));
            $this->Auth->logout();
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'admin' => true));
        } else if ($role_id == 3) {
            $this->Session->setFlash('<p>' . __('Logout successfully', true) . '</p>', 'default', array('class' => 'alert alert-success'));
            $this->Auth->logout();
            $this->redirect(array('controller' => 'users', 'action' => 'storeLogin', 'store' => true));
        } else {
            $this->Session->setFlash('<p>' . __('Logout successfully', true) . '</p>', 'default', array('class' => 'alert alert-success'));
            $this->Auth->logout();
            $this->redirect(array('controller' => 'users', 'action' => 'customerlogin', 'customer' => true));
            //$this->redirect(array("controller"=>"homes","action"=>"home"));	
        }
        $this->Session->delete('coupon_code');
    }

    public function admin_adminLogout() {
        $this->Session->setFlash('<p>' . __('Logout successfully', true) . '</p>', 'default', array('class' => 'alert alert-success'));
        $this->Auth->logout();
        $this->redirect(array('controller' => 'users', 'action' => 'login', 'admin' => true));
    }

    /**
     * Displays a view
     * user logout process
     * @param mixed What page to USerlogout
     * @return void
     */
    public function customer_userLogout() {
        $this->Session->setFlash('<p>' . __('Logout successfully', true) . '</p>', 'default', array('class' => 'alert alert-success'));
        $this->Auth->logout();
        $this->Session->write("preSessionid", '');
        session_regenerate_id();
        $this->redirect(array('controller' => 'Users', 'action' => 'customerlogin', 'customer' => true));
    }

    // Store Logout
    public function store_storeLogout() {
        $this->Session->setFlash('<p>' . __('Logout successfully', true) . '</p>', 'default', array('class' => 'alert alert-success'));
        $this->Auth->logout();
        $this->redirect(array('controller' => 'users', 'action' => 'storeLogin', 'store' => true));
    }

    /**
     * UsersController::userRedirect()
     * userdirct process
     * @return void
     */
    public function userRedirect() {
        if ($this->userRoles['admin'] == $this->loggedAdmin['role_id']) {
            //Admin login redirect...
            $this->redirect(array('controller' => 'dashboards', 'action' => 'index', 'admin' => true));
        } else if ($this->userRoles['storeadmin'] == $this->loggedAdmin['role_id']) {
            //StoreAdmin login redirect...
            $this->redirect(array('controller' => 'dashboards', 'action' => 'index', 'storeadmin' => true));
            //$this->redirect($url);
        } else if ($this->userRoles['customers'] == $this->loggedAdmin['role_id']) {
            //Customer login redirect...
            $this->redirect(array('controller' => 'Customers', 'action' => 'myaccount', 'customer' => true));
        } else if ($this->userRoles['staff'] == $this->loggedAdmin['role_id']) {
            //Staff login redirect...
            $this->redirect(array('controller' => 'settings', 'action' => 'general', 'storeadmin' => true));
        } else {
            $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }
    }

    /**
     * UsersController::admin_changepassword()
     * admin change password process
     * @return void
     */
    /* public function admin_changepassword() {
      if($this->request->is('post')) {
      $id          = $this->Auth->User('id');
      $user        = $this->User->find('first', array(
      'conditions' => array('User.id' => $id),
      'fields' 	 => 'password'));

      $password 	 = $user['User']['password'];
      $oldpassword = $this->Auth->password($this->request->data['User']['oldpassword']);
      if($password == $oldpassword) {
      if($this->request->data['User']['newpassword'] == $this->request->data['User']['retypepassword']) {
      $nPassword  = $this->request->data['User']['newpassword'];
      $cPass['User']['id'] = $id;
      $cPass['User']['password'] = $this->Auth->password($nPassword);
      if($this->User->save($cPass['User'], null, null)){
      $this->Session->setFlash('<p>'.__('Votre mot de passe a été modifié avec succès', true).'</p>', 'default',
      array('class' => 'alert alert-success'));
      $this->redirect(array('controller' => 'users','action' => 'logout', 'admin' => false));
      }else {
      $this->Session->setFlash('<p>'.__('Password not Updated', true).'</p>', 'default',
      array('class' => 'alert alert-danger'));
      }
      } else {
      $this->Session->setFlash('<p>'.__('New Password and Retype Password not  Matched', true).'</p>', 'default',
      array('class' => 'alert alert-danger'));
      }
      } else {
      $this -> Session -> setFlash('<p>'.__('L’ancien mot de passe ne correspond pas', true).'</p>', 'default',
      array('class' => 'alert alert-danger'));
      }
      }
      } */

    /**
     * UsersController::signup()
     * Customer Signup Process
     * @return void
     */
    public function signup() {
        $this->layout = 'frontend';

        if ($this->Auth->loggedIn()) {
            echo '<h3 class="form-title"> Vous n’êtes pas autorisé à accéder à cette page </h3>
        			<a href="' . $this->siteUrl . '/users/logout/customerregister"> Cliquez ici pour vous déconnecter  </a>';
            exit();
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            $this->Customer->set($this->request->data);
            $this->User->invalidFields();

            if ($this->Customer->validates()) {
                $CustomerExist = $this->User->find('first', array(
                    'conditions' => array('User.role_id' => 4,
                        'User.username' => trim($this->request->data['Customer']['customer_email']),
                        'NOT' => array('Customer.status' => 3))));
                $StoreExists = $this->User->find('first', array(
                    'conditions' => array('User.role_id' => 3,
                        'User.username' => trim($this->request->data['Customer']['customer_email']),
                        'NOT' => array('Store.status' => 3))));
                if (!empty($CustomerExist) || !empty($StoreExists)) {
                    $this->Session->setFlash('<p>' . __('Email already exists', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {
                    $this->request->data['User']['role_id'] = 4;
                    $this->request->data['User']['username'] = $this->request->data['Customer']['customer_email'];
                    $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);
                    $this->User->save($this->request->data['User'], null, null);
                    $this->request->data['Customer']['user_id'] = $this->User->id;

                    $this->Customer->save($this->request->data['Customer'], null, null);
                    //Mail Processing From Admin To Customer
                    $newRegisteration = $this->Notification->find('first', array(
                        'conditions' => array(
                            'Notification.title' => 'Customer activation')));
                    if ($newRegisteration) {

                        // $regContent = $newRegisteration['Notification']['content'];
                        $regsubject = $newRegisteration['Notification']['subject'];
                    }

                    $regContent = '<table class="container content" align="center"> <tbody><tr> <td>
								      <table class="row note">
								            <tbody> <tr> <td class="wrapper last">
								                  <p>Bonjour {firstname},</p>
								                  <p> ' . htmlentities('L’équipe d’') . '{store name}  vous souhaite la bienvenue en tant que nouveau membre.
								                  <p style="margin-left:80px; font-weight:bold">Veuillez cliquez <a href="{activation}"> ici </a> pour confirmer votre adresse mail : {customerEmail}</p>
								                  <p>Cordialement, </p>
								                  <p>' . htmlentities('L’équipe d’') . ' {store name} </p> </td> </tr>
								            </tbody>
								      </table> </td> </tr>
								</tbody></table>';

                    $adminEmail = $this->siteSetting['Sitesetting']['admin_email'];
                    $source = $this->siteUrl . '/siteicons/logo.png';
                    $mailContent = $regContent;
                    $userID = $this->Customer->id;
                    $siteUrl = $this->siteUrl;
                    $activation = $this->siteUrl . '/users/activeLink/' . $userID;
                    $customerName = $this->request->data['Customer']['first_name'];
                    $store_name = $this->siteSetting['Sitesetting']['site_name'];

                    $mailContent = str_replace("{firstname}", $customerName, $mailContent);
                    $mailContent = str_replace("{customerEmail}", $customerEmail, $mailContent);
                    $mailContent = str_replace("{activation}", $activation, $mailContent);
                    $mailContent = str_replace("{siteUrl}", $siteUrl, $mailContent);
                    $mailContent = str_replace("{store name}", $store_name, $mailContent);
                    $email = new CakeEmail();
                    $email->from($adminEmail);
                    $email->to($this->request->data['Customer']['customer_email']);
                    $email->subject($regsubject);
                    $email->template('register');
                    $email->emailFormat('html');
                    $email->viewVars(array('mailContent' => $mailContent,
                        'source' => $source,
                        'storename' => $store_name));
                    $email->send();

                    //Signup Sms
                    /* $customerMessage = 'Thank you for registering with '.$this->siteSetting['Sitesetting']['site_name'].'.Click on below link to activate your account.'.$activation.'. Thanks '.$this->siteSetting['Sitesetting']['site_name']; */

                    $customerMessage = 'Merci de vous être inscrit sur ' . $this->siteSetting['Sitesetting']['site_name'] . '.Cliquez sur le lien ci-dessous pour activer votre compte.' . $activation . '. Merci l’équipe d’ ' . $this->siteSetting['Sitesetting']['site_name'];

                    $toCustomerNumber = '+' . $this->siteSetting['Country']['phone_code'] . $this->request->data['Customer']['customer_phone'];
                    $customerSms = $this->Twilio->sendSingleSms($toCustomerNumber, $customerMessage);

                    //Mailchimp Process
                    $merge_vars = array(
                        'EMAIL' => $this->request->data['Customer']['customer_email'],
                        'FNAME' => $this->request->data['Customer']['first_name'],
                        'LNAME' => $this->request->data['Customer']['last_name']
                    );

                    $this->Mailchimp->MCAPI($this->mailChimpKey);
                    $list = $this->Mailchimp->listSubscribe($this->mailChimpListId, $this->request->data['Customer']['customer_email'], $merge_vars);

                    $this->Session->setFlash('<p>' . __('You have successfully registered an account. An email has been sent with further instructions', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'Users', 'action' => 'customerlogin', 'customer' => true));
                }
            } else {
                $this->Customer->validationErrors;
            }
        }
        $storeId = ($this->Session->read('storeId')) ? $this->Session->read('storeId') : '';
        $this->set(compact('storeId'));
    }

    /**
     * UsersController::customer_customerlogin()
     * Customer Login Process
     * @return void
     */
    public function customer_customerlogin() {
        $this->layout = 'frontend';

        if (isset($this->request->query['page']) && $this->request->query['page'] == 'checkout') {
            $this->Session->write("redirectpage", 'checkout');
        }
        if ($this->Auth->loggedIn() && $this->Auth->user('role_id') == 4) {
            $this->redirect(array("controller" => "Customers", "action" => "myaccount", 'customer' => true));
        } else if ($this->Auth->loggedIn()) {
            echo '<h3 class="form-title"> Vous n’êtes pas autorisé à accéder à cette page </h3>
	    			<a href="' . $this->siteUrl . '/users/logout/customer"> Cliquez ici pour vous déconnecter  </a>';
            exit();
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if (isset($this->request->data['Users']['email']) && $this->request->data['Users']['email'] != '') {
                $userData = $this->User->find('first', array(
                    'conditions' => array(
                        'User.username' => $this->request->data['Users']['email'],
                        'Customer.status' => 1,
                        'User.role_id' => 4)));

                if (!empty($userData)) {
                    $newRegisteration = $this->Notification->find('first', array(
                        'conditions' => array('Notification.title =' => 'Reset password')));

                    $toemail = $this->request->data['Users']['email'];
                    $source = $this->siteUrl . '/siteicons/logo.png';
                    $title = 'logo.png';

                    $storeEmail = $this->siteSetting['Sitesetting']['admin_email'];
                    $storename = $this->siteSetting['Sitesetting']['site_name'];
                    $customerName = $userData['Customer']['first_name'];
                    $tmpPassword = $this->Functions->createTempPassword(7);
                    $datas['User']['password'] = $this->Auth->Password($tmpPassword);
                    $datas['User']['id'] = $userData['User']['id'];

                    if ($this->User->save($datas['User'], null, null)) {

                        if ($newRegisteration) {

                            $forgetpasswordContent = $newRegisteration['Notification']['content'];
                            $forgetpasswordsubject = $newRegisteration['Notification']['subject'];
                        }

                        $mailContent = $forgetpasswordContent;
                        $userID = $userData['User']['id'];
                        $siteUrl = $this->siteUrl . '/customer/users/customerlogin/';
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
                        $email->viewVars(array('mailContent' => $mailContent,
                            'source' => $source,
                            'storename' => $storename));

                        // echo "<pre>";print_r($mailContent);echo "</pre>";exit;

                        if ($email->send()) {
                            // Forget Sms
                            $customerMessage = "Nous avons reçu une demande de changement de votre mot de passe. Utilisez ce mot de passe " . $tmpPassword . " pour vous connecter à votre compte et  n’oubliez pas changer votre mot de passe provisoire dès que possible. Merci l’équipe d’ '.$this->siteSetting['Sitesetting']['site_name'].'";
                            $toCustomerNumber = '+' . $this->siteSetting['Country']['phone_code'] . $userData['Customer']['customer_phone'];
                            $customerSms = $this->Twilio->sendSingleSms($toCustomerNumber, $customerMessage);
                            $this->Session->setFlash('<p>' . __('Email has been sent successfully', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                            $this->redirect(array('controller' => 'users', 'action' => 'customerlogin', 'customer' => true));
                        }
                    }
                } else {
                    $this->Session->setFlash('<p>' . __('You are not register customer', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                    $this->redirect(array('controller' => 'users', 'action' => 'customerlogin', 'customer' => true));
                }
            }

            $this->User->set($this->request->data);
            if ($this->User->validates()) {

                $csrfTokens = $this->Session->read('csrfToken');
                if ($this->request->data['User']['token'] != $csrfTokens) {
                    $this->redirect(array('controller' => 'users', 'action' => 'customerlogin', 'customer' => true));
                }

                $role = array(4);
                $userData = $this->User->find('first', array(
                    'conditions' => array(
                        'User.username' => $this->request->data['User']['username'],
                        'Customer.status' => 1,
                        'User.role_id' => 4)));

                if (in_array($userData['User']['role_id'], $role)) {
                    if ($this->Attempt->limit('login', $this->loginAttemptLimit)) {
                        $this->Session->write("preSessionid", $this->Session->id());
                        if ($this->Auth->login()) {
                            #REmember me            
                            if ($this->request->data['User']['rememberMe'] == 1) {
                                $this->Cookie->write('rememberMe', $this->request->data['User'], true, "12 months");
                            } else {
                                $this->Cookie->delete('rememberMe');
                            }
                            if ($this->Session->read("redirectpage") == 'checkout') {
                                $this->Session->delete("redirectpage");
                                $this->redirect(array('controller' => 'checkouts', 'action' => 'index', 'customer' => false));
                            }
                            $this->redirect(array('controller' => 'customers', 'action' => 'myaccount'));
                        } else {
                            $this->Attempt->fail('login', $this->loginAttemptDuration);
                            $this->Session->setFlash('<p>' . __('Login failed your Username or Password Incorrect', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                            $this->redirect(array('controller' => 'users', 'action' => 'customerlogin', 'customer' => true));
                        }
                    } else {
                        $this->Session->setFlash('<p>' . __('Plusieurs tentatives ont échoué !', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                    }
                } else {

                    $this->Session->setFlash('<p>' . __('Login failed, unauthorized', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                    $this->redirect(array('controller' => 'users', 'action' => 'customerlogin', 'customer' => true));
                }
            } else {
                $this->User->validationErrors;
            }
        }
        $storeId = ($this->Session->read('storeId')) ? $this->Session->read('storeId') : '';
        $token = $this->Nocsrf->generate('csrf_token');
        $this->Session->write('csrfToken', $token);
        $this->set(compact('token', 'storeId'));
    }

    // Store Change password
    public function store_changePassword() {
        $this->layout = 'assets';
        if ($this->request->is('post') || $this->request->is('put') && $this->Auth->User('role_id') == 3) {
            $new_password = $this->Auth->password($this->request->data['user']['new_pass']);
            $confirm_password = $this->Auth->password($this->request->data['user']['confirm_pass']);
            if ($new_password == $confirm_password) {
                $this->request->data['User']['password'] = $new_password;
                $this->request->data['User']['id'] = $this->Auth->User('id');
                if ($this->User->save($this->request->data['User'], null, null)) {
                    $this->Session->setFlash('<p>' . __('Votre mot de passe a été modifié avec succès', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'dashboards', 'action' => 'index', 'store' => true));
                }
            } else {
                $this->Session->setFlash('<p>' . __('L’ancien mot de passe ne correspond pas', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
            }
        }
    }

    // Admin change password
    public function admin_changePassword() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $new_password = $this->Auth->password($this->request->data['user']['new_pass']);
            $confirm_password = $this->Auth->password($this->request->data['user']['confirm_pass']);
            if ($new_password == $confirm_password) {
                $this->request->data['User']['password'] = $new_password;
                $this->request->data['User']['id'] = $this->Auth->User('id');
                if ($this->User->save($this->request->data['User'], null, null)) {
                    $this->Session->setFlash('<p>' . __('Votre mot de passe a été modifié avec succès', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'dashboards', 'action' => 'index', 'admin' => true));
                }
            } else {
                $this->Session->setFlash('<p>' . __('L’ancien mot de passe ne correspond pas', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
            }
        }
    }

    // Customer Activation
    public function activeLink($id = null) {

        if ($id) {
            $CustomerDetails = $this->Customer->findById($id);

            if (!empty($CustomerDetails)) {
                //if ($CustomerDetails['Customer']['status'] == 0) {

                if ($this->Customer->updateAll(array('Customer.status' => 1), array('Customer.id' => $id))) {
                    $this->Session->setFlash('<p>' . __('Your account is activated', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'users', 'action' => 'customerlogin', 'customer' => true));
                }
                /* } else {
                  $this->Session->setFlash('<p>'.__('Your account is restricted please contact admin .', true).'</p>', 'default',
                  array('class' => 'alert alert-danger'));
                  $this->redirect(array('controller' => 'searches', 'action' => 'index', 'customer' => false));
                  } */
            } else {
                $this->Session->setFlash('<p>' . __('Vous n’êtes pas client membre.', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                $this->redirect(array('controller' => 'users', 'action' => 'signup'));
            }
        }
    }

    //Social login process
    public function social_login($provider) {

        if ($this->Hybridauth->connect($provider)) {

            $this->_successfulHybridauth($provider, $this->Hybridauth->user_profile);
        } else {
            // error
            //$this->Session->setFlash($this->Hybridauth->error);
            $this->redirect($this->Auth->loginAction);
        }
    }

    // Social end point
    public function social_endpoint($provider = null) {
        $this->Hybridauth->processEndpoint();
    }

    // Social login check 
    private function _successfulHybridauth($provider, $incomingProfile) {

        $existingProfile = $this->User->find('first', array(
            'conditions' => array('User.username' => $incomingProfile['User']['email'])));

        if ($existingProfile) {
            $this->_doSocialLogin($existingProfile, true);
        } else {

            $this->request->data['User']['role_id'] = 4;
            $this->request->data['User']['username'] = $incomingProfile['User']['email'];

            $tmpPassword = $this->Functions->createTempPassword(7);
            $this->request->data['User']['password'] = $this->Auth->Password($tmpPassword);


            $this->User->save($this->request->data['User'], null, null);
            $this->request->data['Customer']['user_id'] = $this->User->id;
            $this->request->data['Customer']['first_name'] = $incomingProfile['User']['first_name'];
            $this->request->data['Customer']['last_name'] = $incomingProfile['User']['last_name'];
            $this->request->data['Customer']['customer_email'] = $incomingProfile['User']['email'];

            $this->Customer->save($this->request->data['Customer'], null, null);
            //Mail Processing From Admin To Customer
            $newRegisteration = $this->Notification->find('first', array(
                'conditions' => array(
                    'Notification.title' => 'Customer activation')));
            if ($newRegisteration) {

                $regContent = $newRegisteration['Notification']['content'];
                $regsubject = $newRegisteration['Notification']['subject'];
            }

            $adminEmail = $this->siteSetting['Sitesetting']['admin_email'];
            $source = $this->siteUrl . '/siteicons/logo.png';

            $mailContent = $regContent;
            $userID = $this->Customer->id;
            $siteUrl = $this->siteUrl;
            $activation = $this->siteUrl . '/users/activeLink/' . $userID;
            $customerName = $this->request->data['Customer']['first_name'];
            $store_name = $this->siteSetting['Sitesetting']['site_name'];
            $mailContent = str_replace("{firstname}", $customerName, $mailContent);
            $mailContent = str_replace("{activation}", $activation, $mailContent);
            $mailContent = str_replace("{siteUrl}", $siteUrl, $mailContent);
            $mailContent = str_replace("{store name}", $store_name, $mailContent);
            $mailContent .='<p> This is your tmp password:' . $tmpPassword . '</p>';

            $email = new CakeEmail();
            $email->from($adminEmail);
            $email->to($this->request->data['Customer']['customer_email']);
            $email->subject($regsubject);
            $email->template('register');
            $email->emailFormat('html');
            $email->viewVars(array('mailContent' => $mailContent,
                'source' => $source,
                'storename' => $store_name));
            $email->send();

            //Mailchimp Process
            $merge_vars = array(
                'EMAIL' => $this->request->data['Customer']['customer_email'],
                'FNAME' => $this->request->data['Customer']['first_name'],
                'LNAME' => $this->request->data['Customer']['last_name']
            );
            $this->Mailchimp->MCAPI($this->mailChimpKey);
            $list = $this->Mailchimp->listSubscribe($this->mailChimpListId, $this->request->data['Customer']['customer_email'], $merge_vars);

            $newProfile = $this->User->findById($this->User->id);
            $this->_doSocialLogin($newProfile);
        }
    }

    //Facebook Login
    public function facebook_login() {

        if (!empty($this->request->data('incomingProfile'))) {
            $incomingProfile['User'] = $this->request->data('incomingProfile');
            print($incomingProfile);
            exit();
            $existingProfile = $this->User->find('first', array(
                'conditions' => array('User.username' => $incomingProfile['User']['email'])));

            if ($existingProfile) {
                $this->_doFacebookSocialLogin($existingProfile, true);
            } else {

                $this->request->data['User']['role_id'] = 4;
                $this->request->data['User']['username'] = $incomingProfile['User']['email'];

                $tmpPassword = $this->Functions->createTempPassword(7);
                $this->request->data['User']['password'] = $this->Auth->Password($tmpPassword);


                $this->User->save($this->request->data['User'], null, null);
                $this->request->data['Customer']['user_id'] = $this->User->id;
                $this->request->data['Customer']['first_name'] = $incomingProfile['User']['first_name'];
                $this->request->data['Customer']['last_name'] = $incomingProfile['User']['last_name'];
                $this->request->data['Customer']['customer_email'] = $incomingProfile['User']['email'];

                $this->Customer->save($this->request->data['Customer'], null, null);
                //Mail Processing From Admin To Customer
                $newRegisteration = $this->Notification->find('first', array(
                    'conditions' => array(
                        'Notification.title' => 'Customer activation')));
                if ($newRegisteration) {

                    $regContent = $newRegisteration['Notification']['content'];
                    $regsubject = $newRegisteration['Notification']['subject'];
                }

                $adminEmail = $this->siteSetting['Sitesetting']['admin_email'];
                $source = $this->siteUrl . '/siteicons/logo.png';

                $mailContent = $regContent;
                $userID = $this->Customer->id;
                $siteUrl = $this->siteUrl;
                $activation = $this->siteUrl . '/users/activeLink/' . $userID;
                $customerName = $this->request->data['Customer']['first_name'];
                $store_name = $this->siteSetting['Sitesetting']['site_name'];
                $mailContent = str_replace("{firstname}", $customerName, $mailContent);
                $mailContent = str_replace("{activation}", $activation, $mailContent);
                $mailContent = str_replace("{siteUrl}", $siteUrl, $mailContent);
                $mailContent = str_replace("{store name}", $store_name, $mailContent);
                $mailContent .='<p> This is your tmp password:' . $tmpPassword . '</p>';

                $email = new CakeEmail();
                $email->from($adminEmail);
                $email->to($this->request->data['Customer']['customer_email']);
                $email->subject($regsubject);
                $email->template('register');
                $email->emailFormat('html');
                $email->viewVars(array('mailContent' => $mailContent,
                    'source' => $source,
                    'storename' => $store_name));
                $email->send();

                //Mailchimp Process
                $merge_vars = array(
                    'EMAIL' => $this->request->data['Customer']['customer_email'],
                    'FNAME' => $this->request->data['Customer']['first_name'],
                    'LNAME' => $this->request->data['Customer']['last_name']
                );
                $this->Mailchimp->MCAPI($this->mailChimpKey);
                $list = $this->Mailchimp->listSubscribe($this->mailChimpListId, $this->request->data['Customer']['customer_email'], $merge_vars);

                $newProfile = $this->User->findById($this->User->id);
                echo($this->_doFacebookSocialLogin($newProfile));
            }
        } else {
            echo "Invalid post parameters";
        }
    }

    // login and redirect path
    private function _doFacebookSocialLogin($user, $returning = false) {

        $userDetails = $user['User'];
        $userDetails['Customer'] = $user['Customer'];

        if (!empty($user['Customer']['id'])) {
            $this->Session->write("preSessionid", $this->Session->id());
            if ($this->Auth->login($userDetails)) {
                if ($this->Session->read("redirectpage") == 'checkout') {
                    $this->Session->delete("redirectpage");
                    return "checkouts/index";
                }
            }
        }
        return "users/customerlogin";
    }

    // login and redirect path
    private function _doSocialLogin($user, $returning = false) {

        $userDetails = $user['User'];
        $userDetails['Customer'] = $user['Customer'];

        if (!empty($user['Customer']['id'])) {
            $this->Session->write("preSessionid", $this->Session->id());
            if ($this->Auth->login($userDetails)) {
                if ($this->Session->read("redirectpage") == 'checkout') {
                    $this->Session->delete("redirectpage");
                    $this->redirect(array('controller' => 'checkouts', 'action' => 'index', 'customer' => false));
                }
            } else {
                $this->Session->setFlash(__('Une erreur inconnue est survenu, l’utilisateur n’a pas pu être identifié: '));
            }
        } else {
            $this->Session->setFlash('<p>' . __('Login failed, unauthorized', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
        }
        $this->redirect(array('controller' => 'users', 'action' => 'customerlogin', 'customer' => true));
    }

    /**
     * UsersController::storeSignup()
     * Restaurant Signup Process
     * @return void
     */
    public function storeSignup() {
        $this->layout = 'frontend';

        if ($this->Auth->loggedIn()) {
            echo '<h3 class="form-title"> Vous n’êtes pas autorisé à accéder à cette page </h3>
        			<a href="' . $this->siteUrl . '/users/logout/storeSignup"> Cliquez ici pour vous déconnecter  </a>';
            exit();
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Store->set($this->request->data);
            if ($this->Store->validates()) {

                $CustomerExist = $this->User->find('first', array(
                    'conditions' => array('User.role_id' => 4,
                        'User.username' => trim($this->request->data['User']['username']),
                        'NOT' => array('Customer.status' => 3))));
                $StoreExists = $this->User->find('first', array(
                    'conditions' => array('User.role_id' => 3,
                        'User.username' => trim($this->request->data['User']['username']),
                        'NOT' => array('Store.status' => 3))));
                if (!empty($CustomerExist) || !empty($StoreExists)) {
                    $this->Session->setFlash('<p>' . __('L’email utilisateur existe déjà', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {

                    $storeArrray = $this->request->data;
                    $storeArrray['Store']['seo_url'] = $this->Functions->seoUrl($this->request->data['Store']['store_name']);
                    $storeArrray['User']['role_id'] = 3;
                    $storeArrray['User']['password'] = $this->Auth->password($storeArrray['User']['password']);
                    if ($this->User->save($storeArrray, null, null)) {
                        $storeArrray['Store']['user_id'] = $this->User->id;
                        if ($this->Store->save($storeArrray, null, null)) {
                            $timing['StoreTiming']['store_id'] = $this->Store->id;
                            //Store Timings
                            $Days = array('monday', 'tuesday', 'wednesday', 'thursday',
                                'friday', 'saturday', 'sunday');
                            foreach ($Days as $keyword => $value) {
                                $timing['StoreTiming'][$value . '_firstopen_time'] = '10:00 AM';
                                $timing['StoreTiming'][$value . '_firstclose_time'] = '12:00 PM';
                                $timing['StoreTiming'][$value . '_secondopen_time'] = '02:00 PM';
                                $timing['StoreTiming'][$value . '_secondclose_time'] = '09:00 PM';
                            }
                            $this->StoreTiming->save($timing, null, null);
                            $newRegisteration = $this->Notification->find('first', array(
                                'conditions' => array('Notification.title' => 'Store signup'))
                            );
                            $siteName = $this->siteSetting['Sitesetting']['site_name'];
                            if ($newRegisteration) {
                                $regContent = $newRegisteration['Notification']['content'];
                                $regsubject = $newRegisteration['Notification']['subject'];
                                $regsubject = str_replace("{siteName}", $siteName, $regsubject);
                            }
                            $storeEmail = $this->siteSetting['Sitesetting']['admin_email'];
                            $source = $this->siteUrl . '/siteicons/logo.png';
                            $mailContent = $regContent;
                            $userID = $this->User->id;
                            $siteUrl = $this->siteUrl;
                            $activation = $this->siteUrl . '/users/storeActiveLink/' . $userID;
                            $StoreName = $storeArrray['Store']['store_name'];
                            $site_name = $this->siteSetting['Sitesetting']['site_name'];



                            $mailContent = str_replace("{sellar name}", $StoreName, $mailContent);
                            $mailContent = str_replace("{CLICK_HERE_TO_LOGIN}", $activation, $mailContent);
                            $mailContent = str_replace("{siteUrl}", $siteUrl, $mailContent);
                            $mailContent = str_replace("{SERVER_NAME}", $siteName, $mailContent);
                            $mailContent = str_replace("{store name}", $site_name, $mailContent);

                            $email = new CakeEmail();
                            $email->from($storeEmail);
                            $email->to($storeArrray['User']['username']);
                            $email->subject($regsubject);
                            $email->template('register');
                            $email->emailFormat('html');
                            $email->viewVars(array('mailContent' => $mailContent,
                                'source' => $source,
                                'storename' => $siteName));
                            // echo "<pre>";print_r($mailContent);echo "</pre>";exit();
                            $email->send();
                            $this->Session->setFlash('<p>' . __('Les informations du restaurant ont été enregistrées avec succès', true) . '</p>', 'default', array('class' => 'alert alert-success'));

                            $this->redirect(array('controller' => 'users', 'action' => 'storeLogin', 'store' => true));
                        }
                    }
                }
            } else {
                $this->Store->validationErrors;
            }
        }
        $storeId = ($this->Session->read('storeId')) ? $this->Session->read('storeId') : '';
        $this->set(compact('storeId'));
    }

    // Customer Activation
    public function storeActiveLink($id = null) {

        if ($id) {
            $storeDetails = $this->Store->findByUserId($id);

            if (!empty($storeDetails)) {

                if ($this->Store->updateAll(array('Store.status' => 1), array('Store.user_id' => $id))) {
                    $this->Session->setFlash('<p>' . __('Your account is activated', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'users', 'action' => 'storeLogin', 'store' => true));
                }
            } else {
                $this->Session->setFlash('<p>' . __('Vous n’êtes pas restaurant membre.', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                $this->redirect(array('controller' => 'users', 'action' => 'storeSignup'));
            }
        }
    }

    //Application
    public function apply() {

        $this->layout = 'frontend';

        $this->set('cities', $this->City->find('list', array(
                    'conditions' => array('City.status' => 1),
                    'fields' => array('city_name', 'city_name'))));

        if ($this->request->is('post') || $this->request->is('put')) {

            $this->Customer->set($this->request->data);
            $this->User->invalidFields();

            if ($this->Customer->validates()) {

                $cusFirstName = $this->request->data['Customer']['first_name'];
                $cusLastName = $this->request->data['Customer']['last_name'];
                $cusEmail = $this->request->data['Customer']['customer_email'];
                $custPhone = $this->request->data['Customer']['customer_phone'];
                $cusCity = $this->request->data['Customer']['customer_city'];

                //send mail to halal admin
                $emailinfo .='<table>
							<tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
		    					<td colspan="3" style="display:inline-block; width:100%;vertical-align: top;">Cher Administrateur,</td>		    					
		    				</tr>
		    				<tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
		    					<td colspan="3" style="display:inline-block; width:100%;vertical-align: top;">Vous venez de recevoir une nouvelle candidature.</td>		    					
		    				</tr>
		    				<tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
		    					<td colspan="3" style="display:inline-block; width:100%;vertical-align: top;">' . utf8_encode(htmlentities('En voici les détails:', ENT_QUOTES, "UTF-8")) . '</td>		    					
		    				</tr>
		    				<tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
		    					<td style="display:inline-block; width:185px;vertical-align: top;">Nom</td>
		    					<td style="display:inline-block; width:50px; text-align:center;">:</td>
		    					<td style="display:inline-block; width:350px; color:#ee541e;">' . $cusLastName . '</td>
		    				</tr>	
							<tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
		    					<td style="display:inline-block; width:185px;vertical-align: top;">Prénom</td>
		    					<td style="display:inline-block; width:50px; text-align:center;">:</td>
		    					<td style="display:inline-block; width:350px; color:#ee541e;">' . $cusFirstName . '</td>
		    				</tr>
		                    <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
		    					<td style="display:inline-block; width:185px;vertical-align: top;">Mail</td>
		    					<td style="display:inline-block; width:50px; text-align:center;">:</td>
		    					<td style="display:inline-block; width:350px; color:#ee541e;">' . $cusEmail . '</td>
		    				</tr>
		                    <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
		    					<td style="display:inline-block; width:185px;vertical-align: top;">Téléphone</td>
		    					<td style="display:inline-block; width:50px; text-align:center;">:</td>
		    					<td style="display:inline-block; width:350px; color:#ee541e;">' . $custPhone . '</td>
		    				</tr>
		                    <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
		    					<td style="display:inline-block; width:185px;vertical-align: top;">Ville</td>
		    					<td style="display:inline-block; width:50px; text-align:center;">:</td>
		    					<td style="display:inline-block; width:350px; color:#ee541e;">' . $cusCity . '</td>
		    				</tr>';

                $emailinfo .='</table>';

                $subject = 'Devenez coursier, postulez Application';

                // echo '------'.$emailinfo;exit;

                $adminEmail = $this->siteSetting['Sitesetting']['admin_email'];
                $source = $this->siteUrl . '/siteicons/logo.png';
                $mailContent = $emailinfo;
                $siteUrl = $this->siteUrl;
                $store_name = $this->siteSetting['Sitesetting']['site_name'];
                $email = new CakeEmail();
                $email->from($adminEmail);
                // $email->to('komaleswari.c@roamsoft.in');
                // $email->to('saravanan.j@roamsoft.in');
                $email->to('contact@halal-resto.fr');
                $email->subject($subject);
                $email->template('register');
                $email->emailFormat('html');
                $email->viewVars(array('mailContent' => $mailContent,
                    'source' => $source,
                    'storename' => $store_name));
                $email->send();

                //send mail to delivery boy
                $cusemailinfo .='<table>
							<tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
		    					<td colspan="3" style="display:inline-block; width:100%;vertical-align: top;">Bonjour ' . $cusFirstName . ',</td>		    					
		    				</tr>
		    				<tr style="display:block; width:610px;font:13px Arial, Helvetica, sans-serif;">
		    					<td colspan="3" style="display:inline-block; width:100%;vertical-align: top;">' . utf8_encode(htmlentities('Nous accusons bonne réception de votre candidature chez HALAL RESTO.', ENT_QUOTES, "UTF-8")) . '</td>		    					
		    				</tr>
		    				<tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
		    					<td colspan="3" style="display:inline-block; width:100%;vertical-align: top;">' . utf8_encode(htmlentities('Nous tenons à vous remercier de l’intérêt que vous portez à notre enseigne.', ENT_QUOTES, "UTF-8")) . '</td>		    					
		    				</tr>
		    				<tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
		    					<td colspan="3" style="display:inline-block; width:100%;vertical-align: top;">' . utf8_encode(htmlentities('Votre candidature sera examinée dans un délai maximum de 2 mois. Vous serez informé par mail et/ou téléphone de l’avis rendu.', ENT_QUOTES, "UTF-8")) . '</td>	    					
		    				</tr>
		    				<tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
		    					<td colspan="3" style="display:inline-block; width:100%;vertical-align: top;">' . utf8_encode(htmlentities('N’oubliez pas de consulter fréquemment votre messagerie électronique.', ENT_QUOTES, "UTF-8")) . '</td>	    					
		    				</tr>
		    				<tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
		    					<td colspan="3" style="display:inline-block; width:100%;vertical-align: top;">' . utf8_encode(htmlentities('Cordialement,', ENT_QUOTES, "UTF-8")) . '</td>		    					
		    				</tr>		
								    				
		    				<tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
		    					<td colspan="3" style="display:inline-block; width:100%;vertical-align: top;">' . utf8_encode(htmlentities('L’équipe d’', ENT_QUOTES, "UTF-8")) . '' . $store_name . '</td>		    					
		    				</tr>';

                $cusemailinfo .='</table>';

                $cussubject = 'Devenez coursier, postulez Application';

                // echo '------'.$cusemailinfo;exit;

                $adminEmail = $this->siteSetting['Sitesetting']['admin_email'];
                $source = $this->siteUrl . '/siteicons/logo.png';
                $mailContent = $cusemailinfo;
                $siteUrl = $this->siteUrl;
                $store_name = $this->siteSetting['Sitesetting']['site_name'];
                $email = new CakeEmail();
                $email->from($adminEmail);
                $email->to($cusEmail);
                $email->subject($cussubject);
                $email->template('register');
                $email->emailFormat('html');
                $email->viewVars(array('mailContent' => $mailContent,
                    'source' => $source,
                    'storename' => $store_name));
                $email->send();

                $this->Session->setFlash('<p>' . __('Vous avez postulé avec succès', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                $this->redirect(array('action' => 'apply'));
            } else {
                $this->Customer->validationErrors;
            }
        }
    }

}
