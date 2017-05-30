<?php

/* MN */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::import('Controller', 'Commons');


class MobileApiController extends AppController {

    var $helpers = array('Html', 'Session', 'Javascript', 'Ajax', 'Common');
    public $components = array('Functions', 'Mailchimp', 'Twilio', 'Updown',
        'Googlemap', 'Stripe', 'Notifications', 'PushNotifications', 'AndroidResponse', 'Paypal');
    public $uses = array('User', 'Order', 'Driver', 'DriverTracking', 'Orderstatus', 'MailContent',
        'State', 'City', 'Location', 'AssignOrder', 'Customer', 'Notification',
        'StripeCustomer', 'CustomerAddressBook', 'Review', 'Product', 'Store',
        'DeliveryLocation', 'Category', 'Deal', 'ProductDetail', 'Storeoffer',
        'DeliveryTimeSlot', 'ShoppingCart', 'Voucher', 'Cuisine', 'StoreCuisine',
        'Mainaddon', 'Subaddon', 'ProductAddon', 'WalletHistory', 'ContactUs', 'StoreTiming', 'BookaTable', 'Meat_issuer'
    );

    public function beforeFilter() {
        $this->Auth->allow('request');
        parent::beforeFilter();
        $this->storeCity = $this->City->find('list', array(
            'fields' => array('City.id', 'City.city_name')));
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
        if ($this->request->is('post')) {
            if (empty($this->request->data)) {
                $data = $this->request->input('json_decode', true);
                $this->request->data = $data;
            }

            switch (trim($this->request->data['action'])) {
                case 'customerLogin':
                    if ($this->request->is('post') || $this->request->is('put')) {
                        $this->request->data = $this->request->data["data"];
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
                            $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);
                            $role = array(4);
                            $userData = $this->User->find('first', array(
                                'conditions' => array(
                                    'User.username' => $this->request->data['User']['username'],
                                    'Customer.status' => 1,
                                    'User.password' => $this->request->data['User']['password'],
                                    'User.role_id' => 4)));

                            if (in_array($userData['User']['role_id'], $role)) {

                                $userData["response"]["id"] = "1";
                                $userData["response"]["message"] = "Success";
                                $response = $userData;
                            } else {
                                $response = array('response' => array('id' => 3, 'message' => 'Login Failed, Invalid Username/Password'));
                            }
                        } else {
                            $response = array('response' => array('id' => 3, 'message' => 'Invalid, Parameters'));
                        }
                    }
                    break;

                    
                    
     ///////////////////////////////////////////////////////////////////////////////////////////////////
                case 'StoreList':
                    $this->address = $this->request->data['google_address'];
                    if (empty($this->address)) {
            $this->redirect(array('controller' => 'searches', 'action' => 'index'));
        }

        $this->layout = 'frontend';
        $getStore = '';
        $serachAddress = $this->address;

     $this->Product->recursive = 0;
        $storeLists = $this->Product->query("SELECT Store.id as id FROM products as p LEFT JOIN"
                . " stores as Store ON Store.id = p.store_id LEFT JOIN delivery_locations as dl "
                . "ON dl.store_id = Store.id WHERE p.status = 1 AND Store.status = 1 AND "
                . "(dl.city_id LIKE '%$serachAddress%' "
                . "OR Store.address LIKE '%$serachAddress%' "
                . "OR Store.store_name LIKE '%$serachAddress%')"
                . "OR (Store.meat = 'Yes' AND Store.meat_issuer_name LIKE '%$serachAddress%')"
                . "GROUP BY Store.id");

        foreach ($storeLists as $key => $value) {
            $getStore[$key] = $value;
            $StoreDetails = $this->Store->find('first', array(
                'conditions' => array('Store.id' => $value['Store']['id']),
                'fields' => array('Store.id',
                    'Store.meat_issuer_name',
                    'Store.store_name', 'Store.seo_url',
                    'Store.store_logo', 'Store.street_address',
                    'Store.store_zip', 'Store.store_city',
                    'Store.store_state', 'Store.address',
                    'Store.minimum_order', 'Store.estimate_time',
                    'Store.delivery_distance', 'Store.delivery_charge',
                    'Store.family', 'Store.friends',
                    'Store.couple', 'Store.business', 'Store.collection',
                    'Store.delivery', 'Store.bookatable')));

            $getStore[$key]['Store'] = $StoreDetails['Store'];
            $getStore[$key]['StoreCuisine'] = $StoreDetails['StoreCuisine'];
        }

        if (!empty($getStore)) {
            foreach ($getStore as $key => $val) {
                $deliveryDistance = $val['Store']['delivery_distance'];

                $Duration = $this->Googlemap->getDistance(
                        $serachAddress, $val['Store']['address']
                );
                //if ($deliveryDistance >= $Duration) {
                if (true) {
                    $val['Store']['distance'] = $Duration;
                    $storeDetails[$key] = $val;

                    $objCommon = new CommonsController;
                    $currentDate = date('d-m-Y');
                    list($content,
                            $openCloseStatus,
                            $firstStatus,
                            $secondStatus) = $objCommon->getDateTime($val['Store']['id'], $currentDate);

                    if ($openCloseStatus == 'Open') {
                        $val['Store']['status'] = ($firstStatus == 'Open' || $secondStatus == 'Open') ? 'Order' : 'Pre Order';
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
                            ($ratingDetail[0]['rating'] / $ratingDetail[0]['ratingCount']) * 20 : 0;
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
        foreach ($storeDetails as $keyword => $value) {
            if (trim($value['Store']['status']) == 'Order') {
                $storeList[] = $value;
                $restId[] = $value['Store']['id'];
            }
        }

        foreach ($storeDetails as $keyword => $value) {
            if (trim($value['Store']['status']) == 'Pre Order') {
                $storeList[] = $value;
                $restId[] = $value['Store']['id'];
            }
        }

        $getStoreCuisine = $this->StoreCuisine->find('all', array(
            'conditions' => array('Cuisine.status' => 1,
                'AND' => array('StoreCuisine.store_id' => $restId)),
            'fields' => array('StoreCuisine.store_id',
                'COUNT(StoreCuisine.cuisine_id) AS cuisineCount',
                'StoreCuisine.id', 'StoreCuisine.store_id',
                'Cuisine.id', 'Cuisine.cuisine_name'),
            'order' => array('StoreCuisine.cuisine_id desc'),
            'group' => array('StoreCuisine.cuisine_id')));

                $meat_issuer_name = $this->Meat_issuer->query("SELECT Count(s.id) as total,s.meat_issuer_name as meat_issuer_name,mi.id as id FROM stores as s "
                . "LEFT JOIN meat_issuers as mi ON mi.name = s.meat_issuer_name WHERE mi.status = 1 AND s.status = 1 "
                . "                                   GROUP BY s.meat_issuer_name");
                    
                    
        $storeLists = compact( 'serachAddress', 'storeList', 'cuisineName', 'meat_issuer_name');
        
   
                    if (empty($storeLists)) {
                        $response['success'] = 0;
                        $response['message'] = 'Restaurant is not available';
                    } else {
                        $response['success'] = 1;
                        $response['result'] = $storeLists;
                        //$response['storeList']  = $storeLists;
                    }
                    break;
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                case 'StoreOffer':
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
                break;
                    
                case 'StoreItems':
                   

                    $storeId = $this->request->data['storeId'];
                    
                    $deal = $offer = $cuisines = $offerPercentage = $offerRange = '';
                   

                    $categoryList = array();

                    $serachAddress = $this->request->data['google_address'];

                    $this->Store->recursive = 0;
                    $storeDetails = $this->Store->find('first', array(
                                    'conditions' => array(
                                        'Store.id' => $storeId,
                                        'Store.status' => 1
                                    )
                                ));

                    if (!empty($storeDetails)) {

                        if ($storeDetails['Store']['collection'] == 'Yes' ||
                                $storeDetails['Store']['delivery'] == 'Yes' &&
                                $storeDetails['Store']['delivery_option'] == 'Yes') {

                            $productList = $this->Product->find('all', array(
                                'conditions' => array('Product.store_id' => $storeId,
                                    'Product.status' => 1,
                                    'MainCategory.status' => 1,
                                    'OR' => array('Store.collection' => 'Yes',
                                        'Store.delivery' => 'Yes')),
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
                                            $dealCategory['category_name'] = "Nos offres";
                                            $dealCategory['id'] = "";
                                             $categoryList[] = $dealCategory;
                                        }
                                    }
                                }
                                foreach ($categoryLists as $key => $value) {
                                    $categoryList[] = $value['Category'];
                                }


                                

                                // Store Order / Pre Order
                                $objCommon = new CommonsController;
                                $currentDate = date('d-m-Y');
                                list($content,
                                        $openCloseStatus,
                                        $firstStatus,
                                        $secondStatus) = $objCommon->getDateTime($storeId, $currentDate);

                                if ($openCloseStatus == 'Open') {
                                    $storeDetails['Store']['status'] = ($firstStatus == 'Open' || $secondStatus == 'Open') ? 'Order' : 'Pre Order';
                                } else {
                                    $storeDetails['Store']['status'] = 'Pre Order';
                                }

                                $storeOffers = $this->storeOffer($storeId);

                                if (!empty($storeOffers)) {

                                    $offerPercentage = $storeOffers['Storeoffer']['offer_percentage'];
                                    $offerRange = $storeOffers['Storeoffer']['offer_price'];

                                    $offer = $offerPercentage . '% OFF today on orders over';
                                    $offer .= ' Rs. ' . $storeOffers['Storeoffer']['offer_price'];
                                }

                                $storeDetails['Store']['offer'] = $offer;
                                $storeDetails['Store']['offerRange'] = $offerRange;
                                $storeDetails['Store']['offerPercentage'] = $offerPercentage;


                                $storeLogoUrl = $this->siteUrl . '/storelogos/';
                                $storeDetails['Store']['store_logo'] = $storeLogoUrl . $storeDetails['Store']['store_logo'];

                                $ratingDetail = $this->Review->find('first', array(
                                    'conditions' => array('Review.store_id' => $storeId,
                                        'Review.status' => 1),
                                    'fields' => array('SUM(Review.rating) AS rating',
                                        'Count(Review.rating) AS ratingCount')));

                                $storeDetails['Store']['rating'] = (!empty($ratingDetail[0]['ratingCount'])) ?
                                        ($ratingDetail[0]['rating'] / $ratingDetail[0]['ratingCount']) * 20 : 0;
                                // Cuisines list
                                $cuisineName = $this->Cuisine->find('list', array('fields' => array('id', 'cuisine_name')));
                                $getStoreCuisines = $this->StoreCuisine->find('all', array(
                                    'conditions' => array('Cuisine.status' => 1,
                                        'StoreCuisine.store_id' => $storeId),
                                    'fields' => array('StoreCuisine.store_id',
                                        'StoreCuisine.id', 'StoreCuisine.store_id',
                                        'Cuisine.id', 'Cuisine.cuisine_name'),
                                    'order' => array('StoreCuisine.cuisine_id desc'),
                                    'group' => array('StoreCuisine.cuisine_id')));

                                foreach ($getStoreCuisines as $key => $value) {
                                    $cuisines .= $value['Cuisine']['cuisine_name'] . ', ';
                                }

                                
                                $storeDetails['Store']['cuisines'] = rtrim($cuisines, ', ');
                                
                                
                                                 //////////////////////////////////////////////////////////////////
                       
                             $objCommon = new CommonsController;
                            $days = array('Monday','Tuesday','Wednesday','Thursday',	'Friday', 	'Saturday', 'Sunday');
                            $engDays = array('Mon','Tue','Wed','Thu',	'Fri', 	'Sat', 'Sun');
                            $freDays = array('Lun','Mar','Mer','Jeu',	'Ven', 	'Sam', 'Dim');
                             for( $i = 0; $i<7; $i++ ) {
                                 $j = $i +1;
                                 $selectDate = '05/0'.$j.'/2017';
                                 $day = $days[$i];
                                list($content1[$day],
                                    $openCloseStatus,
                                    $firstStatus,
                                    $secondStatus) = $objCommon->getDateTimeRest($storeId, $selectDate);
                                 $content1[$day] = str_replace($engDays[$i], $freDays[$i] ,$content1[$day]);
                                 if($content1[$day] == '')
                                 {
                                     $content1[$day] = 'Fermé';
                                 }
                            $content1[$day] =  trim($content1[$day], ',');
                                 
                                
                             }
                             
                            $response['timings'] = $content1;
                    ///////////////////////////////////////////////////////////////////////
                                
                                
                                

                                $response['success'] = 1;
                                $response['deal'] = (!empty($deal)) ? $deal : 'No';
                                $response['categoryList'] = $categoryList;
                                $response['storeDetails'] = (!empty($storeDetails)) ? $storeDetails : '';
                            } else {
                                $response['success'] = 0;
                                $response['message'] = 'Restaurant is not available';
                            }
                        } else {
                            $response['success'] = 0;
                            $response['message'] = 'Restaurant is not available';
                        }
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'Restaurant is not available';
                    }

                    break;
                case 'FilterByCategory':

                    $id = $this->request->data['categoryId'];
                    $storeId = $this->request->data['storeId'];
                    if(empty($id))
                    {
                        
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

                            $deals['product_name']        = $value['MainProduct']['product_name'].' + '.
                                                              $value['SubProduct']['product_name'];
                            $deals['product_addons']             = $value['MainProduct']['product_addons'];
                            $deals['spicy_dish']          = $value['MainProduct']['spicy_dish'];
                            $deals['product_type']        = $value['MainProduct']['product_type'];
                            $deals['popular_dish']        = $value['MainProduct']['popular_dish'];
                            $deals['price']       = $value['MainProduct']['ProductDetail'][0]['orginal_price'];
                            $deals['price_option']       = $value['MainProduct']['price_option'];
                            
                            $deals['subProductImage']    = $imageSrc.$value['SubProduct']['product_image'];
                            $deals['product_image']   = $imageSrc.$value['MainProduct']['product_image'];
                            $deals['id']   =            $value['MainProduct']['id'];
                            $deals['product_description'] = $value['MainProduct']['product_description'];

                            $dealProduct[] = $deals;
                        }
                    }

                    if (!empty($dealProduct)) {
                        $response['success']      = 1;
                        $response['productList']  = $dealProduct;
                    } else {
                        $response['success']  = 0;
                        $response['message']  = 'No records found';
                    }
                    }
                    else
                    {
                        
                    $storeId = $this->request->data['storeId'];
                    $searchKey = isset($this->request->data['searchKey']) ? $this->request->data['searchKey'] : '';
                    $productLists = array();
                    $response['id'] = $id;

                    $imageSrc = $this->siteUrl . '/stores/' . $storeId . '/products/home/';


                    $this->Product->recursive = 1;

                    if (!empty($searchKey)) {

                        $productLists = $this->Product->find('all', array(
                            'conditions' => array(
                                'Product.status' => 1,
                                'MainCategory.status' => 1,
                                'Product.store_id' => $storeId,
                                "Product.product_name LIKE" => "%" . $searchKey . "%"),
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
                        if ($value['Product']['product_image'] != "") {
                            $value['Product']['product_image'] = $imageSrc . $value['Product']['product_image'];
                        }
                        $value['Product']['price'] = $value['ProductDetail'][0]['orginal_price'];
                        $productList[$key] = $value['Product'];
                    }

                    if (!empty($productList)) {
                        $response['success'] = 1;
                        $response['productList'] = $productList;
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'No records found';
                    }
                    }
                    break;
                case 'ProductDetails':
                   
                    $productId = $this->request->data['productId'];
                    $productDetails = $this->Product->find('first', array(
                        'conditions' => array('Product.id' => $productId)));

                   $productDetail['Details'] = $productDetails['Product'];
                    $productDetail['variants'] = $productDetails['ProductDetail'];

                    $catId = $productDetails['Product']['category_id'];
                    $storeId = $productDetails['Product']['store_id'];

                    /*$this->Subaddon->recursive = 0;

                    $addons = $this->ProductAddon->find('all', array(
                        'conditions' => array(
                            'ProductAddon.category_id' => $catId,
                            'ProductAddon.store_id' => $storeId,
                            'ProductAddon.product_id' => $productId,
                            'Subaddon.id !=' => '')));

                   */

                    if (!empty($productDetail)) {
                        $response['success'] = 1;
                        $response['productDetails'] = $productDetail;
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'No records found';
                    }
                    break;
                    case 'ProductSubAddon':

                    $productAddons = array();

                    $productId          = $this->request->data['productId'];
                   
                    $productDetailId    = $this->request->data['productDetailId'];

                    $addons = $this->ProductAddon->find('all', array(
                                        'conditions' => array(
                                                'ProductAddon.product_id' => $productId,
                                                'ProductAddon.productdetails_id' => $productDetailId,
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
                case 'DealProducts' :
                     $storeId = $this->request->data['storeId'];
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

                            $deals['product_name']        = $value['MainProduct']['product_name'].' + '.
                                                              $value['SubProduct']['product_name'];
                            $deals['product_addons']             = $value['MainProduct']['product_addons'];
                            $deals['spicy_dish']          = $value['MainProduct']['spicy_dish'];
                            $deals['product_type']        = $value['MainProduct']['product_type'];
                            $deals['popular_dish']        = $value['MainProduct']['popular_dish'];
                            $deals['price']       = $value['MainProduct']['ProductDetail'][0]['orginal_price'];
                            $deals['subProductImage']    = $imageSrc.$value['SubProduct']['product_image'];
                            $deals['product_image']   = $imageSrc.$value['MainProduct']['product_image'];
                            $deals['id']   =            $value['MainProduct']['id'];
                            $deals['product_description'] = $value['MainProduct']['product_description'];

                            $dealProduct[] = $deals;
                        }
                    }

                    if (!empty($dealProduct)) {
                        $response['success']      = 1;
                        $response['productList']  = $dealProduct;
                    } else {
                        $response['success']  = 0;
                        $response['message']  = 'No records found';
                    }
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
                   case 'bookaTable' : 
                    	//$data = $this->Functions->parseSerialize($this->params['data']['formData']);
                        //$this->request->data = $data['data'];

                        if (!empty($this->request->data['BookaTable'])) {
                           
                            $this->BookaTable->save($this->request->data, null, null);
                          
                            $this->request->data['BookaTable']['booking_id'] = '#Book000'.$this->BookaTable->id;
                           
                            $this->BookaTable->save($this->request->data, null, null);
                            
                            $this->bookaTableMail($this->BookaTable->id);
                             
                           
                                $response['success'] = 1;
                                $response['message'] = 'Successfully booked';
                            }
                    
                    
                    break;
                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                case 'CheckOut' :

                    $storeId = $this->request->data['store_id'];
                    $storeDetails = $this->Store->findById($storeId);


                    switch (trim($this->request->data['page'])) {

                       
 
                            case 'deliveryLocation' :

                                $this->storeId = $this->request->data['store_id'];
                            $id = $this->request->data['id'];
                            $orderTypes = $this->request->data['orderTypes'];

                            //$orderTypes = explode(',', $orderTypes);
                            $locationDetails = $this->CustomerAddressBook->findById($id);
                        
                            $this->ShoppingCart->recursive = 0;
                            $shopCartDetails = $this->ShoppingCart->find('first', array(
                                'fields' => array(
                                    'SUM(ShoppingCart.product_total_price) AS Subtotal',
                                    'Store.store_name',
                                    'Store.address',
                                    'Store.delivery_distance',
                                    'Store.delivery_charge',
                                ),
                                'conditions' => array(
                                   
                                    'ShoppingCart.store_id' => $this->storeId
                                )
                            ));
                            
                    
                            
                            
                            
                            if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
                                $deliveryLocationId = $locationDetails['CustomerAddressBook']['google_address'];
                                $storeId = $this->storeId;
                                if ($orderTypes == 'Delivery') {
                                    //$storeAddress[0]['dl']['minimum_order']
                                    $deliveryLocation = $this->DeliveryLocation->query("SELECT * FROM delivery_locations as dl WHERE "
                                            . "dl.store_id = $storeId AND '$deliveryLocationId' LIKE CONCAT('%',city_id,'%') AND status = 1 LIMIT 1");
                                    if (!empty($deliveryLocation)) {
                                        
                                    } else {
                                        $response['success'] = 0;
                                        $response['message'] = 'don not deliver to your address please select another one';
                                        break;
                                    }
                                }
                            } else {
                                 $deliveryChargesTotal = 0;
                                if ($orderTypes == 'Delivery') {
                                    $deliveryAddress = $locationDetails['CustomerAddressBook']['google_address'];
                                    $storeAddress = $shopCartDetails['Store']['address'];
                                    $deliveryDistance = $shopCartDetails['Store']['delivery_distance'];

                                    $storeId = $this->storeId;
                                    $storeAddress = $this->DeliveryLocation->query("SELECT * FROM delivery_locations as dl WHERE ". "dl.store_id = $storeId AND '$deliveryAddress' LIKE CONCAT('%',city_id,'%') AND status = 1 LIMIT 1");

                                    $Duration = $this->Googlemap->getDistance( $deliveryAddress, $storeAddress[0]['dl']['city_id']);


                                    if (empty($storeAddress)) {
                                        //$this->Session->write('customerDeliveryAddress', '');
                                        //$this->Session->write('customerdeliveryAddressCharge', 0);
                                          $response['success'] = 0;
                                        $response['message'] = 'don not deliver to your address please select another one';
                                        break;
                                    } else {
                                        $deliveryDistance = $storeAddress[0]['dl']['estimate_delivery_time'];
                                        if ($deliveryDistance < $Duration) {
                                            $response['success'] = 0;
                                        $response['message'] = 'don not deliver to your address please select another one';
                                        break;
                                        }
                                        
                                        $minOrder = $storeAddress[0]['dl']['minimum_order'];
                                        $deliveryChargesTotal = $storeAddress[0]['dl']['delivery_charge'];
                                      //  if ($shopCartDetails[0]['Subtotal'] < $storeAddress[0]['dl']['minimum_order']) {
                                         //   $deliveryChargesTotal = $storeAddress[0]['dl']['minimum_order'];
                                       // }
                                        //$this->Session->write('customerDeliveryAddress', $deliveryAddress);
                                        //$this->Session->write('customerdeliveryAddressCharge', $storeAddress[0]['dl']['delivery_charge']);
                                        //echo $deliveryChargesTotal;
                                    }
                                }else {
                                    //echo $deliveryChargesTotal;
                                }
                            }
                             $response['success'] = 1;
                             $response['details'] = compact('deliveryChargesTotal', 'deliveryDistance', 'minOrder');
                            
                            
                            break;
    
                        case 'RestaurantTimes':
                             $objCommon = new CommonsController;
                            $days = array('Monday','Tuesday','Wednesday','Thursday',	'Friday', 	'Saturday', 'Sunday');
                            $engDays = array('Mon','Tue','Wed','Thu',	'Fri', 	'Sat', 'Sun');
                            $freDays = array('Lun','Mar','Mer','Jeu',	'Ven', 	'Sam', 'Dim');
                             for( $i = 0; $i<7; $i++ ) {
                                 $j = $i +1;
                                 $selectDate = '05/0'.$j.'/2017';
                                 $day = $days[$i];
                                list($content[$day],
                                    $openCloseStatus,
                                    $firstStatus,
                                    $secondStatus) = $objCommon->getDateTimeRest($storeId, $selectDate);
                                 $content[$day] = str_replace($engDays[$i], $freDays[$i] ,$content[$day]);
                                 if($content[$day] == '')
                                 {
                                     $content[$day] = 'Fermé';
                                 }
                            $content[$day] =  trim($content[$day], ',');
                                 
                                
                             }
                             
                            $response['timings'] = $content;
                           
                            break;
                            
                         case 'ConformOrder':

                            $customerId      = $this->request->data['customer_id'];                  
                            //$storeId      = $this->request->data['storeId'];
                            $customerDetails = $this->Customer->findById($customerId);
                            $addressId      = $this->request->data['Customer']['address_id']; 
                            

                                 

                                    $cardFee = $this->siteSetting['Sitesetting']['card_fee'];

                                    $freeDeliveryCharge = $deliveryCharge = 0;

                                

                                    $payments = array('cod', 'wallet', 'paypal');
                                   

                                    if (!in_array($this->request->data['Order']['paymentMethod'], $payments)) {
                                        
                                        $stripeCard = $this->StripeCustomer->find('first', array(
                                            'conditions' => array(
                                                'StripeCustomer.id' => $this->request->data['Order']['paymentMethod'],
                                                'StripeCustomer.customer_id' => $customerId)));
                                       
                                         
                                    }
                              
                                    $today = date("m/d/Y");
                                    $lastsessionid = $this->Session->read("preSessionid");
                                    $SessionId = (!empty($lastsessionid)) ? $lastsessionid : $this->Session->id();

                                  
                                    $addressDetails = $this->CustomerAddressBook->find('first', array(
                                        'conditions' => array('CustomerAddressBook.id' => $addressId)));
                                    $customerDetails = $this->CustomerAddressBook->find('first', array(
                                        'conditions' => array('CustomerAddressBook.customer_id' => $customerId)));

                                    $order['store_id'] = $storeId;
                                    $order['customer_id'] = $customerId;
                                    $order['user_type'] = 'Customer';
                                    $order['order_description'] = $this->request->data['Order']['order_description'];
                                    $order['order_type'] = $this->request->data['Order']['orderType'];

                                    $storeDetails = $this->Store->findById($storeId);

                                    if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
                                        
                                         
                                        if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {
                                             
                                            
                                            $storeAddress = $storeDetails['Store']['street_address'] . ', ' .
                                                    $this->storeCity[$storeDetails['Store']['store_city']] . ', ' .
                                                    $this->storeState[$storeDetails['Store']['store_state']] . ' ' .
                                                    $this->storeLocation[$storeDetails['Store']['store_zip']] . ', ' .
                                                    $this->siteSetting['Country']['country_name'];
                                           
                                        } else {
                                            $storeAddress = $storeDetails['Store']['street_address'] . ', ' .
                                                    $this->storeLocation[$storeDetails['Store']['store_zip']] . ', ' .
                                                    $this->storeCity[$storeDetails['Store']['store_city']] . ', ' .
                                                    $this->storeState[$storeDetails['Store']['store_state']] . ', ' .
                                                    $this->siteSetting['Country']['country_name'];
                                         
                                        }
                                    } else {
                                        $storeAddress = $storeDetails['Store']['address'];
                                    }

                                    $sourceLatLong = $this->Googlemap->getlatitudeandlongitude($storeAddress);
                                    $source_lat = (!empty($sourceLatLong['lat'])) ? $sourceLatLong['lat'] : 0;
                                    $source_long = (!empty($sourceLatLong['long'])) ? $sourceLatLong['long'] : 0;


                                    $order['customer_name'] = $this->request->data['Customer']['first_name'] . ' ' .
                                            $this->request->data['Customer']['last_name'];
                                    $order['customer_email'] = $this->request->data['Customer']['customer_email'];
                                    $order['customer_phone'] = $this->request->data['Customer']['customer_phone'];

                                    if ($order['order_type'] != 'Collection') {
                                        if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
                                            $order['address'] = $addressDetails['CustomerAddressBook']['address'];
                                            $order['landmark'] = $addressDetails['CustomerAddressBook']['landmark'];
                                            $order['state_name'] = $addressDetails['State']['state_name'];
                                            $order['city_name'] = $addressDetails['City']['city_name'];
                                            $order['location_name'] = $addressDetails['Location']['area_name'];

                                            $deliveryAddress = $order['address'] . ', ' .
                                                    $order['location_name'] . ', ' .
                                                    $order['city_name'] . ', ' .
                                                    $order['state_name'] . ', ' .
                                                    $this->siteSetting['Country']['country_name'];
                                        } else {
                                            $order['google_address'] = $addressDetails['CustomerAddressBook']['google_address'];
                                            $deliveryAddress = $order['google_address'];
                                        }

                                        $destinationLatLong = $this->Googlemap->getlatitudeandlongitude($deliveryAddress);

                                        $order['destination_latitude'] = (!empty($destinationLatLong['lat'])) ? $destinationLatLong['lat'] : 0;
                                        $order['destination_longitude'] = (!empty($destinationLatLong['long'])) ? $destinationLatLong['long'] : 0;
                                    } else {
                                        if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
                                            $order['address'] = $storeDetails['Store']['street_address'];
                                            $order['landmark'] = '';
                                            $order['state_name'] = $this->storeState[$storeDetails['Store']['store_state']];
                                            $order['city_name'] = $this->storeCity[$storeDetails['Store']['store_city']];
                                            $order['location_name'] = $this->storeLocation[$storeDetails['Store']['store_zip']];
                                        } else {
                                            $order['google_address'] = $storeDetails['Store']['address'];
                                        }
                                        $order['destination_latitude'] = $source_lat;
                                        $order['destination_longitude'] = $source_long;
                                    }


                                    $destination_lat = $order['destination_latitude'];
                                    $destination_long = $order['destination_longitude'];
                                    $distance = $this->Googlemap->getDrivingDistance($source_lat, $source_long, $destination_lat, $destination_long);
                                    $order['source_latitude'] = $source_lat;
                                    $order['source_longitude'] = $source_long;

                                    $storeOffers = $this->Storeoffer->find('first', array(
                                        'conditions' => array('Storeoffer.store_id' => $storeId,
                                            'Storeoffer.status' => 1,
                                            "Storeoffer.from_date <=" => $today,
                                            "Storeoffer.to_date >=" => $today),
                                        'order' => 'Storeoffer.id DESC'));

          
                                    // Voucher Calculation
                                    $objCommon = new CommonsController;
                                    $voucherDetail = $objCommon->voucherCodeCheck($storeId, trim($this->request->data['Order']['voucher_code']));
                                    if (!empty($voucherDetail['Voucher'])) {

                                        if ($voucherDetail['Voucher']['offer_mode'] == 'percentage') {
                                            $order['voucher_amount'] = $total[0]['cartSubTotal'] *
                                                    $voucherDetail['Voucher']['offer_value'] / 100;
                                            $order['voucher_percentage'] = $voucherDetail['Voucher']['offer_value'];
                                        } elseif ($voucherDetail['Voucher']['offer_mode'] == 'price') {
                                            $order['voucher_amount'] = $voucherDetail['Voucher']['offer_value'];
                                        } else {
                                            $freeDeliveryCharge = 1;
                                        }
                                        $order['voucher_code'] = $voucherDetail['Voucher']['voucher_code'];
                                    } else {
                                        $order['voucher_amount'] = 0;
                                    }



                                    if ($order['order_type'] != 'Collection') {

                                        if (empty($freeDeliveryCharge)) {
                                            if ($this->siteSetting['Sitesetting']['address_mode'] == 'Google') {
                                                $deliveryCharge = $storeDetails['Store']['delivery_charge'];
                                            } else {
                                                $cityId = $addressDetails['CustomerAddressBook']['city_id'];
                                                $areaId = $addressDetails['CustomerAddressBook']['location_id'];

                                                $DeliveryLocation = $this->DeliveryLocation->find('first', array(
                                                    'fields' => array(
                                                        'DeliveryLocation.delivery_charge'
                                                    ),
                                                    'conditions' => array(
                                                        'DeliveryLocation.store_id' => $storeId,
                                                        'DeliveryLocation.city_id' => $cityId,
                                                        'DeliveryLocation.location_id' => $areaId
                                                    )
                                                ));

                                                $deliveryCharge = (!empty($DeliveryLocation['DeliveryLocation']['delivery_charge'])) ?
                                                        $DeliveryLocation['DeliveryLocation']['delivery_charge'] : '0.00';
                                            }
                                        }
                                    }

                                    $deliveryDate = ($this->request->data['Order']['assoonas'] == 'Later') ? date('Y-m-d', strtotime($this->request->data['Order']['delivery_date'])) : date('Y-m-d');


                                    $order['assoonas'] = $this->request->data['Order']['assoonas'];
                                    $order['delivery_date'] = $deliveryDate;
                                    $order['delivery_time'] = $this->request->data['Order']['delivery_time'];
                                    $order['delivery_charge'] = (!empty($deliveryCharge)) ? $deliveryCharge : 0;
                                    $order['order_sub_total'] = $this->request->data['Order']['order_sub_total'];//$total[0]['cartSubTotal'];
                                    $order['tax_percentage'] = $storeDetails['Store']['tax'];
                                    $order['tax_amount'] = (!empty($storeDetails['Store']['tax'])) ? $total[0]['cartSubTotal'] *
                                            ($storeDetails['Store']['tax'] / 100) : 0;
                                    $order['distance'] = (isset($distance['distanceText'])) ? $distance['distanceText'] : 0;
                                    $order['offer_amount'] = ($storeOffers['Storeoffer']['offer_price'] <= $total[0]['cartSubTotal']) ?
                                            $total[0]['cartSubTotal'] * $storeOffers['Storeoffer']['offer_percentage'] / 100 :
                                            0;
                                    $order['offer_percentage'] = (!empty($order['offer_amount'])) ? $storeOffers['Storeoffer']['offer_percentage'] : 0;

                                 
                                    $tipPrice = $this->request->data['Order']['tip_percentage'];
                                    $order['tip_amount'] = (!empty($tipPrice)) ? $tipPrice : 0;

                                    $order['order_grand_total'] = $order['order_sub_total'] + $order['delivery_charge'] + $order['tax_amount'] +
                                            $order['tip_amount'] - $order['offer_amount'] - $order['voucher_amount'];
                                    $grandTotal = $order['order_grand_total'];

                                    $this->Session->write("paypalOrders", $order);


                                    if ($this->request->data['Order']['paymentMethod'] == 'cod') {


                                        $this->Order->save($order, null, null);

                                        $update['ref_number'] = '#ORD00' . $this->Order->id;
                                        $orderId[] = $update['id'] = $this->Order->id;
                                        $update['payment_type'] = 'cod';

                                        $this->Order->save($update);

                                         // Shopping Cart Process
                            $cartDetails = $this->request->data['cartdetails'];
                            foreach ($cartDetails as $key => $value) {

                                $productDetails = $this->ProductDetail->find('first', array(
                                                    'conditions' => array('ProductDetail.id' => $value['menu_id']),
                                                    'fields' => array('Product.product_image')));

                                $shoppingCart['id'] = '';
                                $shoppingCart['store_id']            = $storeId;
                                $shoppingCart['order_id']            = $this->Order->id;
                                $shoppingCart['product_id']          = $value['menu_id'];
                                $shoppingCart['category_name']         = $value['category_name'];
                                $shoppingCart['product_name']        = $value['menu_name'];
                                $shoppingCart['product_price']       = $value['product_price'];
                                $shoppingCart['subaddons_name']      = $value['Addon_name'];
                                $shoppingCart['product_quantity']    = $value['quantity'];
                                $shoppingCart['product_total_price'] = $value['product_price'] * $value['quantity'];
                                $shoppingCart['product_description'] = $value['instruction'];

                                $shoppingCart['product_image'] = (isset($productDetails['Product']['product_image'])) ?
                                                    $productDetails['Product']['product_image'] : 'no-image.jpg';

                                $this->ShoppingCart->save($shoppingCart, null, null);
                               
                            }
                                        /*$this->ShoppingCart->updateAll(
                                                array('ShoppingCart.order_id' => $this->Order->id), array('ShoppingCart.session_id' => $SessionId,
                                            'ShoppingCart.order_id' => 0,
                                            'ShoppingCart.store_id' => $storeId));*/

                                        /*//Ordermail
                                        if ($_SERVER['HTTP_HOST'] == 'halal-resto.fr') {

                                            $this->ordermail($this->Order->id);
                                            //OrderSms
                                            
                                            $this->ordersms($this->Order->id);
                                        }*/
                                    } else {

                                        $amount = number_format($grandTotal, 2);

                                        if ($this->request->data['Order']['paymentMethod'] == 'wallet') {

                                            $customerDetails = $this->Customer->find('first', array(
                                                'conditions' => array('Customer.id' => $customerId),
                                                'recursive' => 0,
                                                'fields' => array('wallet_amount')));

                                            if ($customerDetails['Customer']['wallet_amount'] >= $amount) {

                                                $customerDetails['Customer']['wallet_amount'] -= $amount;

                                                if ($this->Customer->save($customerDetails, null, null)) {


                                                    $order['payment_type'] = 'Wallet';
                                                    $order['payment_method'] = 'paid';

                                                    $order['cardfee_percentage'] = $cardFee;
                                                    $order['cardfee_price'] = $grandTotal * ($cardFee / 100);

                                                    $this->Order->save($order, null, null);

                                                    $orderUpdate['id'] = $this->Order->id;
                                                    $orderUpdate['ref_number'] = '#ORD00' . $this->Order->id;
                                                    $this->Order->save($orderUpdate, null, null);

                                                    $this->ShoppingCart->updateAll(
                                                            array('ShoppingCart.order_id' => $this->Order->id), array('ShoppingCart.session_id' => $SessionId,
                                                        'ShoppingCart.order_id' => 0,
                                                        'ShoppingCart.store_id' => $storeId));

                                                    // Wallet History
                                                    $walletHistory['amount'] = $amount;
                                                    $walletHistory['purpose'] = 'Transaction on order ' . $orderUpdate['ref_number'];
                                                    $walletHistory['customer_id'] = $customerId;
                                                    $walletHistory['transaction_type'] = 'Debited';
                                                    $walletHistory['transaction_details'] = $orderUpdate['ref_number'];
                                                    $cartDetails = $this->request->data['cartdetails'];
                                                    
                            foreach ($cartDetails as $key => $value) {

                                $productDetails = $this->ProductDetail->find('first', array(
                                                    'conditions' => array('ProductDetail.id' => $value['menu_id']),
                                                    'fields' => array('Product.product_image')));

                                $shoppingCart['id'] = '';
                                $shoppingCart['store_id']            = $storeId;
                                $shoppingCart['order_id']            = $this->Order->id;
                                $shoppingCart['product_id']          = $value['menu_id'];
                                $shoppingCart['category_name']         = $value['category_name'];
                                $shoppingCart['product_name']        = $value['menu_name'];
                                $shoppingCart['product_price']       = $value['product_price'];
                                $shoppingCart['subaddons_name']      = $value['Addon_name'];
                                $shoppingCart['product_quantity']    = $value['quantity'];
                                $shoppingCart['product_total_price'] = $value['product_price'] * $value['quantity'];
                                $shoppingCart['product_description'] = $value['instruction'];

                                $shoppingCart['product_image'] = (isset($productDetails['Product']['product_image'])) ?
                                                    $productDetails['Product']['product_image'] : 'no-image.jpg';

                                $this->ShoppingCart->save($shoppingCart, null, null);
                               
                            }
                                                    $this->WalletHistory->save($walletHistory, null, null);

                                                    /*//Ordermail
                                                    if ($_SERVER['HTTP_HOST'] == 'halal-resto.fr') {
                                                        $this->ordermail($this->Order->id);
                                                        //OrderSms
                                                        $this->ordersms($this->Order->id);
                                                    }*/
                                                }
                                            } else {
                                                   $response['success'] = 0;
                        $response['message'] = 'Insufficient balance in wallet and payment failed';
                                            break; 
                                            }
                                        } elseif ($this->request->data['Order']['paymentMethod'] == 'paypal') {
                                            $response = $this->Paypal->step1($grandTotal);

                                            // echo "<pre>";print_r($response);echo "</pre>";exit;


                                            if ($response == 'FAILURE') {
                                                   $response['success'] = 0;
                        $response['message'] = 'Le paiement a échoué';
                             
                                            }
                                        } else {

                                            if (!empty($this->request->data['Order']['stripetoken'])) {

                                                $datas = array("stripeToken" => $this->request->data['Order']['stripetoken']);
                                                $stripeCustomer = $this->Stripe->customerCreate($datas);
                                                $stripId = $stripeCustomer['stripe_id'];
                                              
                                   $response['stripId'] = $stripId;            
                                         
                                            } else {
                                                // $stripeCard = $this->StripeCustomer->findById($this->request->data['Order']['paymentMethod']);
                                                if (!empty($this->request->data['Order']['stripepayment_id'])) {
                                                    $stripeCard = $this->StripeCustomer->findById($this->request->data['Order']['stripepayment_id']);
                                                } else {
                                                    $stripeCard = $this->StripeCustomer->findById($this->request->data['Order']['paymentMethod']);
                                                }

                                                if (empty($stripeCard['StripeCustomer']['stripe_customer_id'])) {
                                                    $datas = array("stripeToken" => $stripeCard['StripeCustomer']['stripe_token_id']);
                                                    $customer = $this->Stripe->customerCreate($datas);
                                                    $stripId = $stripeCard['StripeCustomer']['stripe_customer_id'] = $customer['stripe_id'];
                                                    $this->StripeCustomer->save($stripeCard);
                                                } else {
                                                    $stripId = $stripeCard['StripeCustomer']['stripe_customer_id'];
                                                }
                                            }
                                            
                                            $data = array('currency' => $this->siteSetting['Country']['currency_code'],
                                                "amount" => $amount,
                                                "stripeCustomer" => $stripId);

                                            $stripeResponse = $this->Stripe->charge($data);
                                           
                                            if ($stripeResponse['status'] == "succeeded" && $stripeResponse['stripe_id'] != '') {

                                                $order['transaction_id'] = $stripeResponse['stripe_id'];
                                                $order['payment_type'] = 'Card';
                                                $order['payment_method'] = 'paid';
                                                $order['cardfee_percentage'] = $cardFee;
                                                $order['cardfee_price'] = $grandTotal * ($cardFee / 100);

                                                $this->Order->save($order, null, null);

                                                $orderUpdate['id'] = $this->Order->id;
                                                $orderUpdate['ref_number'] = '#ORD00' . $this->Order->id;
                                                $this->Order->save($orderUpdate, null, null);

                                                 // Shopping Cart Process
                            $cartDetails = $this->request->data['cartdetails'];
                            foreach ($cartDetails as $key => $value) {

                                $productDetails = $this->ProductDetail->find('first', array(
                                                    'conditions' => array('ProductDetail.id' => $value['menu_id']),
                                                    'fields' => array('Product.product_image')));

                                $shoppingCart['id'] = '';
                                $shoppingCart['store_id']            = $storeId;
                                $shoppingCart['order_id']            = $this->Order->id;
                                $shoppingCart['product_id']          = $value['menu_id'];
                                $shoppingCart['category_name']         = $value['category_name'];
                                $shoppingCart['product_name']        = $value['menu_name'];
                                $shoppingCart['product_price']       = $value['product_price'];
                                $shoppingCart['subaddons_name']      = $value['Addon_name'];
                                $shoppingCart['product_quantity']    = $value['quantity'];
                                $shoppingCart['product_total_price'] = $value['product_price'] * $value['quantity'];
                                $shoppingCart['product_description'] = $value['instruction'];

                                $shoppingCart['product_image'] = (isset($productDetails['Product']['product_image'])) ?
                                                    $productDetails['Product']['product_image'] : 'no-image.jpg';

                                $this->ShoppingCart->save($shoppingCart, null, null);
                               
                            }
                                                /*$this->ShoppingCart->updateAll(
                                                        array('ShoppingCart.order_id' => $this->Order->id), array('ShoppingCart.session_id' => $SessionId,
                                                    'ShoppingCart.order_id' => 0,
                                                    'ShoppingCart.store_id' => $storeId));*/


                                                //Ordermail
                                               /* if ($_SERVER['HTTP_HOST'] == 'halal-resto.fr') {
                                                    $this->ordermail($this->Order->id);
                                                    //OrderSms
                                                    $this->ordersms($this->Order->id);
                                                }*/
                                            } else {

                                                $responseMessage = $stripeResponse;
                                                $filePath = ROOT . DS . 'app' . DS . "tmp" . DS . 'Stripe.txt';
                                                $file = fopen($filePath, "a+");
                                                fwrite($file, PHP_EOL . 'Message---->' . $stripeResponse . PHP_EOL . 'Response---->' . $responseMessage . PHP_EOL);
                                                fclose($file);
                                                 $response['success'] = 0;
                                                $response['message'] = 'Le paiement a échoué';
                                                break;
                                
                                            }
                                        }
                                    }
                          
                            $response['success'] = 1;
                            $response['message'] = 'order successfully submitted';
                                                $response['orderId'] = $this->Order->id;
                                //    $this->changeLocation();
                                  //  $this->Session->write("preSessionid", '');
                                  //  $this->Session->setFlash(__('Your Order Placed Successfully', true), 'default', array('class' => 'alert alert-success'));
                                  //  $this->redirect(array('controller' => 'checkouts', 'action' => 'thanks',$this->Order->id));
                        break;

                        case 'CardPayment':

                            $amount = number_format($this->request->data['amount'], 2);
                            $customerId = $this->request->data['customer_id'];
                            $stripeCardId = $this->request->data['card_id'];

                            if (empty($stripeCardId)) {

                                $saveOrNot = $this->request->data['saveCard'];
                                $cardNumber = $this->request->data['stripe_cardnumber'];
                                $stripe['exp_year'] = $this->request->data['stripe_expyear'];
                                $stripe['exp_month'] = $this->request->data['stripe_expmonth'];
                                $stripe['customer_id'] = $customerId;
                                $stripe['card_number'] = substr($cardNumber, -4);
                                $stripe['customer_name'] = $this->request->data['cardHolderName'];
                                $stripe['stripe_token_id'] = $stripeToken = $this->request->data['stripe_token'];

                                $datas = array("stripeToken" => $stripeToken);
                                $customer = $this->Stripe->customerCreate($datas);
                                $stripId = $stripe['stripe_customer_id'] = (!empty($customer)) ? $customer['stripe_id'] : '';

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
                                    $datas = array("stripeToken" => $stripeCard['StripeCustomer']['stripe_token_id']);
                                    $customer = $this->Stripe->customerCreate($datas);
                                    $stripId = $stripeCard['StripeCustomer']['stripe_customer_id'] = $customer['stripe_id'];
                                    $this->StripeCustomer->save($stripeCard);
                                } else {
                                    $stripId = $stripeCard['StripeCustomer']['stripe_customer_id'];
                                }
                            }

                            $data = array('currency' => $this->siteSetting['Country']['currency_code'],
                                "amount" => $amount,
                                "stripeCustomer" => $stripId);

                            $stripeResponse = $this->Stripe->charge($data);

                            if ($stripeResponse['status'] == "succeeded" && $stripeResponse['stripe_id'] != '') {

                                $response['success'] = 1;
                                $response['message'] = 'Payment successfully';
                                $response['transaction_id'] = $stripeResponse['stripe_id'];
                            } else {
                                $response['success'] = 0;
                                $response['message'] = 'Some technical problem. Please try again!';
                            }
                            break;



                        case 'VoucherAdded':

                            $voucherCode = $this->request->data['voucherCode'];
                            $customerId = $this->request->data['customer_id'];

                            $Today = date("m/d/Y");
                            $voucherDetails = $this->Voucher->find('first', array(
                                'conditions' => array(
                                    'Voucher.store_id' => $storeId,
                                    'Voucher.status' => 1,
                                    'Voucher.voucher_code' => trim($voucherCode),
                                    'Voucher.from_date <=' => $Today,
                                    'Voucher.to_date >=' => $Today)));

                            if (!empty($voucherDetails)) {

                                if ($voucherDetails['Voucher']['type_offer'] == 'single') {
                                    $usedCoupon = $this->Order->find('first', array(
                                        'conditions' => array(
                                            'Order.customer_id' => $customerId,
                                            'Order.voucher_code' => $voucherCode,
                                            'Order.status !=' => 'Failed',
                                            'Order.store_id' => $storeId)));
                                    if (!empty($usedCoupon)) {
                                        $response['success'] = 0;
                                        $response['message'] = 'Voucher already used.';
                                    } else {
                                        $response['success'] = 1;
                                        $response['voucherDetails'] = $voucherDetails['Voucher'];
                                    }
                                } else {
                                    $response['success'] = 1;
                                    $response['voucherDetails'] = $voucherDetails['Voucher'];
                                }
                            } else {
                                $response['success'] = 0;
                                $response['message'] = 'Voucher code is not valid.';
                            }
                            break;
                        case 'StoreTimeSlot':

                            $type = $this->request->data['type'];
                            $orderType = $this->request->data['orderType'];

                            $storeSlots = $this->storeTimeSlots($storeId, $orderType, $type);

                            $response['success'] = 1;
                            $response['storeSlots'] = $storeSlots;
                            break;
                    }

                    break;
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                case 'customerSignUp':
                    if ($this->request->is('post') || $this->request->is('put')) {
                        $this->request->data = $this->request->data["data"];
                        $this->Customer->set($this->request->data);
                        $this->User->invalidFields();
                        if ($this->Customer->validates()) {
                            //if (!empty($this->request->data["Customer"]) && !empty($this->request->data["User"])){
                            $CustomerExist = $this->User->find('first', array(
                                'conditions' => array('User.role_id' => 4,
                                    'User.username' => trim($this->request->data['Customer']['customer_email']),
                                    'NOT' => array('Customer.status' => 3))));
                            $StoreExists = $this->User->find('first', array(
                                'conditions' => array('User.role_id' => 3,
                                    'User.username' => trim($this->request->data['Customer']['customer_email']),
                                    'NOT' => array('Store.status' => 3))));
                            if (!empty($CustomerExist) || !empty($StoreExists)) {
                                $response = array('response' => array('id' => 3, 'message' => 'Customer already exists'));
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
                                $this->request->data["response"]["id"] = 1;
                                $this->request->data["response"]["message"] = "Success";
                                $response = ($this->request->data);
                            }
                        } else {
                            $response = array('response' => array('id' => 3, 'message' => 'Invalid post parameters'));
                        }
                    }
                    break;
                case 'socialLogin':
                    $this->request->data = $this->request->data["data"];
                    $existingProfile = $this->User->find('first', array(
                        'conditions' => array('User.username' => $this->request->data['User']['email'])));

                    if ($existingProfile) {
                        $existingProfile["response"]["id"] = "1";
                        $existingProfile["response"]["message"] = "Success";
                        $response = ($existingProfile);
                    } else {

                        $this->request->data['User']['role_id'] = 4;
                        $this->request->data['User']['username'] = $this->request->data['User']['email'];

                        $tmpPassword = $this->Functions->createTempPassword(7);
                        $this->request->data['User']['password'] = $this->Auth->Password($tmpPassword);


                        $this->User->save($this->request->data['User'], null, null);
                        $this->request->data['Customer']['user_id'] = $this->User->id;
                        $this->request->data['Customer']['first_name'] = $this->request->data['User']['first_name'];
                        $this->request->data['Customer']['last_name'] = $this->request->data['User']['last_name'];
                        $this->request->data['Customer']['customer_email'] = $this->request->data['User']['email'];

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
                        $newProfile["response"]["id"] = "1";
                        $newProfile["response"]["message"] = "Success";
                        $response = ($newProfile);
                    }
                    break;
                case 'DriverLogin':

                    $this->request->data['User']['username'] = $this->request->data['username'];
                    $this->request->data['User']['password'] = $this->request->data['password'];

                    $this->request->data['User']['password'] = AuthComponent::password($this->data['User']['password']);

                    $driver = $this->User->find('first', array(
                        'conditions' => array(
                            'User.username' => $this->request->data['User']['username'],
                            'User.password' => $this->request->data['User']['password'],
                            'Driver.status !=' => 'Delete')));

                    if (!empty($driver)) {

                        $getDriver = $this->Driver->findByParentId($driver['User']['id']);

                        if ($getDriver['Driver']['is_logged'] == 1) {
                            $response['success'] = 0;
                            $response['message'] = 'Driver Already Loggedin';
                            break;
                        }
                        if ($getDriver['Driver']['status'] != 'Active') {
                            $response['success'] = 0;
                            $response['message'] = 'Your account deactivated';
                            break;
                        }
                        if ($getDriver['Vehicle']['id'] == '') {
                            $response['success'] = 0;
                            $response['message'] = 'Vehicle not registered';
                            break;
                        }
                        $getDriver['Driver']['id'] = $getDriver['Driver']['id'];
                        $getDriver['Driver']['device_id'] = $this->request->data['device_id'];
                        $getDriver['Driver']['is_logged'] = 1;
                        $getDriver['Driver']['device_name'] = strtoupper($this->request->data['device_name']);
                        $getDriver['Driver']['driver_status'] = 'Available';

                        $drive = $this->Driver->save($getDriver);

                        if ($drive['User']['role_id'] == '5' && $drive['Driver']['is_logged'] == 1) {

                            $response['success'] = '1';
                            $response['driverid'] = $getDriver['Driver']['id'];
                            $response['driverName'] = $getDriver['Driver']['driver_name'];
                            $response['currency'] = $this->siteSetting['Country']['currency_symbol'];

                            $driverImage = (!empty($driver['Driver']['image'])) ? $this->siteUrl . '/driversImage/' . $driver['Driver']['image'] : $this->siteUrl . '/driversImage/no-photo.png';

                            $response['driverImage'] = $driverImage;
                            $response['driverStatus'] = 'Available';
                            $response['message'] = 'Connexion réussie';

                            $message = 'Coursier : ' . $getDriver['Driver']['driver_name'] . " connecté";
                            $this->Notifications->pushNotification($message, 'FoodOrderAdmin');
                            $this->Notifications->pushNotification($message, 'Restaurant_' . $drive['Driver']['store_id']);

                            break;
                        } else {
                            $response['success'] = '0';
                            $response['message'] = 'Invalid username and password';
                            break;
                        }
                    } else {
                        $response['success'] = '0';
                        $response['message'] = 'Incorrect username and password';
                        break;
                    }
                    break;

                case 'DriverImageUpload':

                    $driver = $this->Driver->findById($this->request->data('driverid'));
                    if (!empty($this->request->data['image'])) {
                        // Get image string posted from Android App
                        $base = $this->request->data['image'];

                        if ($driver['Driver']['device_name'] != 'ANDROID') {
                            $imageSrc = str_replace(" ", "+", $base);
                        } else {
                            $base = explode('\n', $base);
                            $imageSrc = '';
                            foreach ($base as $key => $value) {
                                $imageSrc .= stripslashes($value);
                            }
                        }

                        /* $filePath      = ROOT.DS.'app'.DS."tmp".DS.'mobilePhoto.txt';
                          $file = fopen($filePath,"a+");
                          fwrite($file, PHP_EOL.'Driver Image---->'.$base.PHP_EOL);
                          fclose($file); */

                        // Get file name posted from Android App
                        $fileId = $driver['Driver']['id'] . time() . '.png';
                        $filename = APP . 'webroot/driversImage/' . $fileId;
                        // Decode Image
                        $binary = base64_decode(trim($imageSrc));
                        header('Content-Type: bitmap; charset=utf-8');

                        $file = fopen($filename, 'wb+');
                        // Create File
                        fwrite($file, $binary);
                        fclose($file);
                        #Save Driver Image

                        $driverImage['Driver']['id'] = $driver['Driver']['id'];
                        $driverImage['Driver']['image'] = $fileId;
                        $this->Driver->save($driverImage);

                        $response['success'] = 1;
                        $response['message'] = 'Image uploaded successfully!';
                        $response['driverImage'] = $this->siteUrl . '/driversImage/' . $fileId;
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'Image not upload!';
                    }
                    break;

                case 'DriverDetails':

                    $driverId = $this->request->data['driverid'];
                    $driver = $this->Driver->findById($driverId);

                    if (is_array($driver) && $driver['User']['role_id'] == 5) {

                        $driverImage = (!empty($driver['Driver']['image'])) ? $this->siteUrl . '/driversImage/' . $driver['Driver']['image'] : $this->siteUrl . '/driversImage/no-photo.png';

                        $response['success'] = 1;
                        $response['DriverName'] = $driver['Driver']['driver_name'];
                        $response['DriverMail'] = $driver['Driver']['driver_email'];
                        $response['DriverMobile'] = $driver['Driver']['driver_phone'];
                        $response['driverImage'] = $driverImage;
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'Unknown driver';
                    }
                    break;

                case 'DriverUpdate':
                    $driver['Driver']['id'] = $this->request->data['driverid'];
                    $driver['Driver']['driver_name'] = $this->request->data['driverName'];
                    $driver['Driver']['driver_email'] = $this->request->data['driverMail'];
                    $driver['Driver']['driver_phone'] = $this->request->data['driverMobile'];

                    if ($driver['Driver']['id'] != '') {
                        if ($this->Driver->save($driver)) {

                            $response['success'] = 1;
                            $response['message'] = 'Updated successfully!';
                        } else {
                            $response['success'] = 0;
                            $response['message'] = 'Details not updated!';
                        }
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'Unknown driver!';
                    }
                    break;

                case 'DriverLocation':

                    $latitude = $this->request->data('latitude');
                    $longitude = $this->request->data('longitude');

                    $driverId = $this->request->data['driverid'];

                    if ($this->request->data['driverid']) {

                        $driverTrack = $this->DriverTracking->findByDriverId($driverId);

                        $tracking['id'] = ($driverTrack['DriverTracking']['id'] != '') ? $driverTrack['DriverTracking']['id'] : '';
                        $tracking['driver_id'] = $driverId;
                        $tracking['driver_latitude'] = $latitude;
                        $tracking['driver_longitude'] = $longitude;

                        $trackResult = $this->DriverTracking->save($tracking);

                        $response['success'] = ($trackResult['DriverTracking']['id'] != '') ? 1 : 0;
                    } else {
                        $response['success'] = 0;
                    }
                    break;

                case 'DriverStatus':

                    $driverId = $this->request->data('driverid');
                    $status = $this->request->data('status');
                    $driver = $this->Driver->findById($driverId);

                    if (!empty($driver)) {

                        $driver['Driver']['driver_status'] = $status;

                        $this->Driver->save($driver);

                        $response['success'] = 1;
                        $response['message'] = 'statut changé';

                        if (strtolower($status) == 'end of shift') {
                            $message = 'Fin de service pour' . $driver['Driver']['driver_name'];
                        } elseif (strtolower($status) == 'on break') {
                            $message = $driver['Driver']['driver_name'] . " est en pause";
                        } elseif (strtolower($status) == 'available') {
                            $message = $driver['Driver']['driver_name'] . " est disponible";
                        } else {
                            $message = $driver['Driver']['driver_name'] . " is est hors ligne";
                        }

                        $this->Notifications->pushNotification($message, 'FoodOrderAdmin');
                        $this->Notifications->pushNotification($message, 'Restaurant_' . $driver['Driver']['store_id']);
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'Status is not change';
                    }
                    break;

                case 'OrderStatus':

                    $driverId = $this->request->data('driverid');
                    $orderId = $this->request->data('orderid');
                    $status = (strtolower(trim($this->request->data('status'))) == 'reject') ? 'Accepted' : $this->request->data('status');
                    $latitude = $this->request->data('latitude');
                    $longitude = $this->request->data('longitude');

                    if ($driverId == '' || $orderId == '' || $status == '') {
                        $response['success'] = 0;
                        $response['message'] = 'Missing arugument';
                    }

                    $ordStatus = $this->Order->findById($orderId);

                    $orders['Orderstatus']['id'] = '';
                    $orders['Orderstatus']['status'] = $status;
                    $orders['Orderstatus']['order_id'] = $orderId;
                    $orders['Orderstatus']['driver_id'] = $driverId;
                    $orders['Orderstatus']['driver_latitude'] = $latitude;
                    $orders['Orderstatus']['driver_longitude'] = $longitude;


                    if ($status != 'Accepted') {
                        $this->Orderstatus->save($orders);
                    } else {
                        $this->Orderstatus->deleteAll(array('Orderstatus.order_id' => $orderId));
                    }

                    $track = $this->DriverTracking->findByDriverId($driverId);

                    if (!empty($track)) {
                        $track['DriverTracking']['order_id'] = ($status != 'Delivered') ? $orderId : '';
                        $this->DriverTracking->save($track);
                    }
                    if (!empty($ordStatus)) {
                        $ordStatus['Order']['driver_id'] = ($status == 'Accepted') ? '' : $driverId;
                        $ordStatus['Order']['status'] = $status;
                        //Driver's Offer Price
                        if (!empty($this->request->data['driverOffer'])) {
                            $ordStatus['Order']['driver_offer'] = $this->request->data['driverOffer'];
                        }

                        if (!empty($this->request->data['image']) && $status == 'Delivered') {
                            // Get image string posted from Android App
                            $base = $this->request->data['image'];

                            if ($ordStatus['Driver']['device_name'] != 'ANDROID') {
                                $imageSrc = str_replace(" ", "+", $base);
                            } else {
                                $base = explode('\n', $base);
                                $imageSrc = '';
                                foreach ($base as $key => $value) {
                                    $imageSrc .= stripslashes($value);
                                }
                            }

                            // Get file name posted from Android App
                            $fileId = 'Order_signature' . $orderId . '.png';
                            $filename = APP . 'webroot/OrderProof/' . $fileId;
                            // Decode Image
                            $binary = base64_decode($imageSrc);
                            header('Content-Type: bitmap; charset=utf-8');

                            $file = fopen($filename, 'wb+');
                            // Create File
                            fwrite($file, $binary);
                            fclose($file);

                            $ordStatus['Order']['payment_method'] = 'paid';
                        }

                        $this->Order->save($ordStatus);

                        $response['success'] = 1;
                        $response['message'] = 'Commande le statut a été changé avec succès';

                        $driverDetail = $this->Driver->findById($driverId);

                        // $status = ($status == 'Collected') ? 'Picked up' : $status;
                        if ($status == 'Collected') {
                            $status = 'emportée';
                        } elseif ($status == 'Waiting') {
                            $status = 'attente';
                        } elseif ($status == 'Delivered') {
                            $status = 'livrée';
                        } elseif ($status == 'Failed') {
                            $status = 'annulée';
                        } elseif ($status == 'Driver Accepted') {
                            $status = 'acceptée par le Coursier';
                        } else {
                            $status = $status;
                        }

                        //Push Notification
                        $message = ($status == 'Accepted') ?
                                $ordStatus['Order']['ref_number'] . ' a été refusé par ' . $driverDetail['Driver']['driver_name'] :
                                $ordStatus['Order']['ref_number'] . " - La commande a été " . $status;

                        $this->Notifications->pushNotification($message, 'FoodOrderAdmin');
                        $this->Notifications->pushNotification($message, 'Restaurant_' . $ordStatus['Order']['store_id']);
                        $customerMessage = $ordStatus['Order']['ref_number'] . ' a été ' . $status;
                        $this->Notifications->pushNotification($customerMessage, 'FoodCustomer_' . $ordStatus['Order']['customer_id']);

                        // Store Owner App Message
                        if ($ordStatus['Store']['is_logged'] == 1) {
                            $deviceId = $ordStatus['Store']['device_id'];
                            $gcm = $this->AndroidResponse->sendOrderByGCM(
                                    array('message' => $message), $deviceId);
                        }
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'Order Status Change Failed';
                    }
                    break;

                case 'CompletedOrders':

                    $status = 'Delivered';

                    $driverId = $this->request->data('driverid');
                    $date = $this->request->data('date');
                    $deliverDate = date('Y-m-d', strtotime($this->request->data('date')));

                    $orderCondition = array(
                        'conditions' => array(
                            'Order.driver_id' => $driverId,
                            'Order.status' => $status),
                        'order' => array('Order.id' => 'DESC'));

                    if ($deliverDate != '1970-01-01') {
                        $orderCondition['conditions']['Order.updated LIKE'] = $deliverDate . '%';
                    }

                    $order = $this->Order->find('all', $orderCondition);

                    if (!empty($order)) {
                        $orderDetails = array();
                        foreach ($order as $key => $value) {
                            if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {

                                $storeAddress = $value['Store']['street_address'] . ', ' .
                                        $this->storeCity[$value['Store']['store_city']] . ', ' .
                                        $value['Store']['post_code'] . ', ' .
                                        $this->siteSetting['Country']['country_name'];
                            } else {
                                $storeAddress = $value['Store']['address'];
                            }


                            $orderDetails[$key]['StoreName'] = stripslashes($value['Store']['store_name']);
                            $orderDetails[$key]['SourceAddress'] = $storeAddress;
                            $orderDetails[$key]['SourceLatitude'] = $value['Order']['source_latitude'];
                            $orderDetails[$key]['SourceLongitude'] = $value['Order']['source_longitude'];
                            $orderDetails[$key]['DestinationAddress'] = ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') ?
                                    $value['Order']['address'] . ', ' .
                                    $value['Order']['city_name'] . ', ' .
                                    $value['Order']['location_name'] . ', ' .
                                    $this->siteSetting['Country']['country_name'] :
                                    $value['Order']['google_address'];
                            $orderDetails[$key]['LandMark'] = $value['Order']['landmark'];
                            $orderDetails[$key]['DestinationLatitude'] = $value['Order']['destination_latitude'];
                            $orderDetails[$key]['DestinationLongitude'] = $value['Order']['destination_longitude'];
                            $orderDetails[$key]['OrderDate'] = $value['Order']['delivery_date'];
                            $orderDetails[$key]['OrderTime'] = ($value['Order']['assoonas'] == 'Later') ? $value['Order']['delivery_time'] : 'ASAP';
                            $orderDetails[$key]['OrderPrice'] = $value['Order']['order_grand_total'];
                            $orderDetails[$key]['OrderId'] = $value['Order']['id'];
                            $orderDetails[$key]['OrderGenerateId'] = $value['Order']['ref_number'];
                            $orderDetails[$key]['OrderStatus'] = $value['Order']['status'];
                            $orderDetails[$key]['CustomerName'] = $value['Order']['customer_name'];
                            $orderDetails[$key]['PaymentType'] = $value['Order']['payment_type'];
                        }

                        $response['success'] = 1;
                        $response['orders'] = $orderDetails;
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'Aucun résultat';
                    }
                    break;

                case 'DriverAcceptedOrders':

                    $status = array('Driver Accepted', 'Collected');

                    $driverId = $this->request->data('driverid');
                    $order = $this->Order->find('all', array(
                        'conditions' => array('Order.driver_id' => $driverId,
                            'AND' => array('Order.status' => $status)),
                        'order' => array('Order.id DESC')));

                    if (!empty($order)) {
                        $orderDetails = array();
                        foreach ($order as $key => $value) {

                            if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
                                $storeAddress = $value['Store']['street_address'] . ', ' .
                                        $this->storeCity[$value['Store']['store_city']] . ', ' .
                                        $value['Store']['post_code'] . ', ' .
                                        $this->siteSetting['Country']['country_name'];
                            } else {
                                $storeAddress = $value['Store']['address'];
                            }

                            $orderDetails[$key]['StoreName'] = stripslashes($value['Store']['store_name']);
                            $orderDetails[$key]['SourceAddress'] = $storeAddress;
                            $orderDetails[$key]['SourceLatitude'] = $value['Order']['source_latitude'];
                            $orderDetails[$key]['SourceLongitude'] = $value['Order']['source_longitude'];
                            $orderDetails[$key]['DestinationAddress'] = ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') ?
                                    $value['Order']['address'] . ', ' .
                                    $value['Order']['city_name'] . ', ' .
                                    $value['Order']['location_name'] . ', ' .
                                    $this->siteSetting['Country']['country_name'] :
                                    $value['Order']['google_address'];
                            $orderDetails[$key]['LandMark'] = $value['Order']['landmark'];
                            $orderDetails[$key]['DestinationLatitude'] = $value['Order']['destination_latitude'];
                            $orderDetails[$key]['DestinationLongitude'] = $value['Order']['destination_longitude'];
                            $orderDetails[$key]['OrderDate'] = $value['Order']['delivery_date'];
                            $orderDetails[$key]['OrderTime'] = ($value['Order']['assoonas'] == 'Later') ? $value['Order']['delivery_time'] : 'ASAP';
                            $orderDetails[$key]['OrderPrice'] = $value['Order']['order_grand_total'];
                            $orderDetails[$key]['OrderId'] = $value['Order']['id'];
                            $orderDetails[$key]['OrderGenerateId'] = $value['Order']['ref_number'];
                            $orderDetails[$key]['OrderStatus'] = $value['Order']['status'];
                            $orderDetails[$key]['CustomerName'] = $value['Order']['customer_name'];
                            $orderDetails[$key]['PaymentType'] = $value['Order']['payment_type'];
                        }
                        $response['success'] = 1;
                        $response['orders'] = $orderDetails;
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'Aucun résultat';
                    }
                    break;

                case 'WaitingOrderCount':
                    $status = 'Waiting';

                    $driverId = $this->request->data('driverid');
                    if ($driverId != '') {
                        $count = $this->Order->find('count', array(
                            'conditions' => array(
                                'Order.driver_id' => $driverId,
                                'Order.status' => $status)));
                        $response['success'] = ($count > 0) ? 1 : 0;
                        $response['waitingCount'] = $count;
                        if ($count == 0)
                            $response['message'] = 'Aucun résultat';
                        break;
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'Invalid driver';
                    }
                    break;

                case 'WaitingOrders':

                    $status = 'Waiting';

                    $driverId = $this->request->data('driverid');
                    $order = $this->Order->find('all', array(
                        'conditions' => array(
                            'Order.driver_id' => $driverId,
                            'Order.status' => $status),
                        'order' => 'Order.id Desc'));

                    if (!empty($order)) {
                        $orderDetails = array();
                        foreach ($order as $key => $value) {

                            $datetime1 = new DateTime(date('Y-m-d G:i:s'));
                            $datetime2 = new DateTime($value['Order']['updated']);
                            $interval = $datetime1->diff($datetime2);
                            $hour = $interval->format('%H');
                            $min = $interval->format('%I');
                            $sec = $interval->format('%S');
                            $day = $interval->format('%D');

                            if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
                                $storeAddress = $value['Store']['street_address'] . ', ' .
                                        $this->storeCity[$value['Store']['store_city']] . ', ' .
                                        $value['Store']['post_code'] . ', ' .
                                        $this->siteSetting['Country']['country_name'];
                            } else {
                                $storeAddress = $value['Store']['address'];
                            }


                            $orderDetails[$key]['StoreName'] = stripslashes($value['Store']['store_name']);
                            $orderDetails[$key]['SourceAddress'] = $storeAddress;
                            $orderDetails[$key]['SourceLatitude'] = $value['Order']['source_latitude'];
                            $orderDetails[$key]['SourceLongitude'] = $value['Order']['source_longitude'];
                            $orderDetails[$key]['DestinationAddress'] = ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') ?
                                    $value['Order']['address'] . ', ' .
                                    $value['Order']['city_name'] . ', ' .
                                    $value['Order']['location_name'] . ', ' .
                                    $this->siteSetting['Country']['country_name'] :
                                    $value['Order']['google_address'];
                            $orderDetails[$key]['LandMark'] = $value['Order']['landmark'];
                            $orderDetails[$key]['DestinationLatitude'] = $value['Order']['destination_latitude'];
                            $orderDetails[$key]['DestinationLongitude'] = $value['Order']['destination_longitude'];
                            $orderDetails[$key]['OrderDate'] = $value['Order']['delivery_date'];
                            $orderDetails[$key]['OrderTime'] = ($value['Order']['assoonas'] == 'Later') ?
                                    $value['Order']['delivery_time'] : 'ASAP';
                            $orderDetails[$key]['OrderPrice'] = $value['Order']['order_grand_total'];
                            $orderDetails[$key]['OrderId'] = $value['Order']['id'];
                            $orderDetails[$key]['OrderGenerateId'] = $value['Order']['ref_number'];
                            $orderDetails[$key]['OrderStatus'] = $value['Order']['status'];
                            $orderDetails[$key]['CustomerName'] = $value['Order']['customer_name'];
                            $orderDetails[$key]['PaymentType'] = $value['Order']['payment_type'];
                            $orderDetails[$key]['Day'] = $day;
                            $orderDetails[$key]['Hour'] = $hour;
                            $orderDetails[$key]['Min'] = $min;
                            $orderDetails[$key]['Sec'] = $sec;
                        }
                        $response['success'] = 1;
                        $response['orders'] = $orderDetails;
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'Aucun résultat';
                    }
                    break;

                case 'OrderDetail':
                    $orderId = $this->request->data['orderid'];
                    if ($orderId != '') {
                        $orderDetails = $this->Order->findById($orderId);
                        $orderDet['success'] = '1';

                        if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
                            $storeAddress = $orderDetails['Store']['street_address'] . ', ' .
                                    $this->storeCity[$orderDetails['Store']['store_city']] . ', ' .
                                    $orderDetails['Store']['post_code'] . ', ' .
                                    $this->siteSetting['Country']['country_name'];
                        } else {
                            $storeAddress = $orderDetails['Store']['address'];
                        }


                        $orderDet['orderId'] = $orderDetails['Order']['ref_number'];
                        $orderDet['customerName'] = stripslashes($orderDetails['Order']['customer_name']);
                        $orderDet['customerAddress'] = ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') ?
                                $orderDetails['Order']['address'] . ', ' .
                                $orderDetails['Order']['city_name'] . ', ' .
                                $orderDetails['Order']['location_name'] . ', ' .
                                $this->siteSetting['Country']['country_name'] :
                                $orderDetails['Order']['google_address'];


                        $orderDet['tax'] = $orderDetails['Order']['tax_amount'];
                        $orderDet['offer'] = $orderDetails['Order']['offer_amount'];
                        $orderDet['total'] = $orderDetails['Order']['order_grand_total'];
                        $orderDet['status'] = $orderDetails['Order']['status'];
                        $orderDet['OrderId'] = $orderDetails['Order']['id'];
                        $orderDet['subTotal'] = $orderDetails['Order']['order_sub_total'];
                        $orderDet['LandMark'] = $orderDetails['Order']['landmark'];
                        $orderDet['OrderDate'] = $orderDetails['Order']['delivery_date'];
                        $orderDet['OrderTime'] = $orderDetails['Order']['delivery_time'];
                        $orderDet['tipAmount'] = $orderDetails['Order']['tip_amount'];
                        $orderDet['orderMenu'] = stripslashes_deep($orderDetails['ShoppingCart']);
                        $orderDet['StoreName'] = stripslashes($orderDetails['Store']['store_name']);
                        $orderDet['OrderPrice'] = $orderDetails['Order']['order_grand_total'];
                        $orderDet['PaymentType'] = $orderDetails['Order']['payment_type'];
                        $orderDet['CustomerName'] = $orderDetails['Order']['customer_name'];
                        $orderDet['taxPercentage'] = $orderDetails['Order']['tax_percentage'];
                        $orderDet['customerEmail'] = $orderDetails['Order']['customer_email'];
                        $orderDet['customerPhone'] = $orderDetails['Order']['customer_phone'];
                        $orderDet['SourceAddress'] = $storeAddress;
                        $orderDet['voucherAmount'] = $orderDetails['Order']['voucher_amount'];
                        $orderDet['deliveryCharge'] = $orderDetails['Order']['delivery_charge'];
                        $orderDet['SourceLatitude'] = $orderDetails['Order']['source_latitude'];
                        $orderDet['OrderGenerateId'] = $orderDetails['Order']['ref_number'];
                        $orderDet['offerPercentage'] = $orderDetails['Order']['offer_percentage'];
                        $orderDet['SourceLongitude'] = $orderDetails['Order']['source_longitude'];
                        $orderDet['voucherPercentage'] = $orderDetails['Order']['voucher_percentage'];
                        $orderDet['DestinationLatitude'] = $orderDetails['Order']['destination_latitude'];
                        $orderDet['DestinationLongitude'] = $orderDetails['Order']['destination_longitude'];
                        $response = $orderDet;
                    } else {
                        $response['success'] = '0';
                        $response['message'] = 'There is no order(s)!';
                    }
                    break;

                case 'OrderDisclaim':

                    $orderId = $this->request->data['orderid'];
                    $driverId = $this->request->data['driverid'];
                    $latitude = $this->request->data('latitude');
                    $longitude = $this->request->data('longitude');

                    $orderDetails = $this->Order->findById($orderId);

                    //Push Notification
                    $message = $orderDetails['Order']['ref_number'] . ' a été refusé par ' . $orderDetails['Driver']['driver_name'];
                    $this->Notifications->pushNotification($message, 'FoodOrderAdmin');
                    $this->Notifications->pushNotification($message, 'Restaurant_' . $orderDetails['Order']['store_id']);


                    $order['id'] = $orderId;
                    $order['status'] = 'Accepted';
                    $order['driver_id'] = 0;

                    $this->Order->save($order);

                    $orderStatus['Orderstatus']['id'] = '';
                    $orderStatus['Orderstatus']['order_id'] = $orderId;
                    $orderStatus['Orderstatus']['driver_id'] = $driverId;
                    $orderStatus['Orderstatus']['status'] = 'Accepted';
                    $orderStatus['Orderstatus']['driver_latitude'] = $latitude;
                    $orderStatus['Orderstatus']['driver_longitude'] = $longitude;

                    $this->Orderstatus->save($orderStatus);
                    $response['success'] = '1';

                    break;

                case 'DriverLogOut':

                    $driverId = $this->request->data['driverid'];
                    $message = array('message' => 'logout', 'OrderDetails' => '');
                    if ($driverId != '') {
                        $driver = $this->Driver->findById($driverId);
                        if ($this->request->data['from'] == 'site') {
                            $gcm = (trim($driver['Driver']['device_name']) == 'ANDROID') ?
                                    $this->AndroidResponse->sendOrderByGCM($message, $driver['Driver']['device_id']) :
                                    $this->PushNotifications->notificationIOS('logout', $driver['Driver']['device_id']);
                        }
                        $driver['Driver']['is_logged'] = '0';
                        $driver['Driver']['driver_status'] = 'Offline';
                        $driver['Driver']['device_id'] = '';
                        $this->DriverTracking->deleteAll(array('DriverTracking.driver_id' => $driver['Driver']['id']));
                        $driverLogout = $this->Driver->save($driver);

                        $response['success'] = '1';
                        $response['message'] = 'Successfully logout ';

                        $message = $driver['Driver']['driver_name'] . " est déconnecté";
                        $this->Notifications->pushNotification($message, 'FoodOrderAdmin');
                        $this->Notifications->pushNotification($message, 'Restaurant_' . $driver['Driver']['store_id']);
                    } else {
                        $response['success'] = '0';
                        $response['message'] = 'Try Again..!';
                    }
                    break;

                case 'DriverToken':

                    $driverId = $this->request->data('driverId');
                    $deviceId = $this->request->data('deviceId');
                    $driver = $this->Driver->findById($driverId);
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

                    $driverId = $this->request->data('driverId');
                    $driver = $this->Driver->findById($driverId);
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
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
                                $response['success'] = '1';
                                $response['customerId'] = $customerDetails['Customer']['id'];
                                $response['firstName'] = $customerDetails['Customer']['first_name'];
                                $response['lastName'] = $customerDetails['Customer']['last_name'];
                                $response['email'] = $customerDetails['Customer']['customer_email'];
                                $response['newsletter'] = $customerDetails['Customer']['news_letter_option'];
                                $customerImage = (!empty($customerDetails['Customer']['image'])) ? $this->siteUrl . '/Customers/' . $customerDetails['Customer']['image'] : '';


                                $response['customerDetails'] = $customerDetails;
                                $response['image'] = $customerImage;
                                $response['customerPhone'] = $customerDetails['Customer']['customer_phone'];
                                break;

                            case 'getStripeCard':
                                $cardDetails = array();
                                $stripeCards = $this->StripeCustomer->find('all', array(
                                    'conditions' => array(
                                        'StripeCustomer.customer_id' => $customerId)));

                                foreach ($stripeCards as $key => $value) {
                                    $cardDetails[] = $value['StripeCustomer'];
                                }

                                if (!empty($cardDetails)) {
                                    $response['success'] = 1;
                                    $response['cardDetails'] = $cardDetails;
                                } else {
                                    $response['success'] = 0;
                                    $response['message'] = 'No records found';
                                }
                                break;

                            case 'SavedCardsList':
                                $stripeCards = $this->StripeCustomer->find('all', array(
                                    'conditions' => array('StripeCustomer.customer_id' => $customerId)));

                                if (!empty($cardDetails)) {
                                    $response['success'] = 1;
                                    $response['savedCards'] = $stripeCards;
                                } else {
                                    $response['success'] = 0;
                                    $response['message'] = 'No records found';
                                }
                                break;

                            case 'SavedCardDelete':
                                $stripeId = $this->request->data['stripeId'];
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
                                $password = $this->request->data['password'];
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

                                    $customerDetails['User']['username'] = $newEmail;
                                    $customerDetails['Customer']['status'] = 2;
                                    $customerDetails['Customer']['customer_email'] = $newEmail;

                                    $this->User->save($customerDetails['User'], null, null);
                                    $this->Customer->save($customerDetails['Customer'], null, null);
                                    $newChangedUser = $this->Notification->find('first', array(
                                        'conditions' => array('Notification.title' => 'Changed new user email')));

                                    if ($newChangedUser) {

                                        $newUserContent = $newChangedUser['Notification']['content'];
                                        $newUsersubject = $newChangedUser['Notification']['subject'];
                                    }

                                    $adminEmail = $this->siteSetting['Sitesetting']['admin_email'];
                                    $source = $this->siteUrl . '/siteicons/logo.png';
                                    $mailContent = $newUserContent;
                                    $userID = $this->Customer->id;
                                    $siteUrl = $this->siteUrl;
                                    $activation = $this->siteUrl . '/users/activeLink/' . $userID;
                                    $customerName = $customerDetails['Customer']['first_name'];
                                    $store_name = $this->siteSetting['Sitesetting']['site_name'];

                                    $mailContent = str_replace("{firstname}", $customerName, $mailContent);
                                    $mailContent = str_replace("{activation}", $activation, $mailContent);
                                    $mailContent = str_replace("{SITE_URL}", $siteUrl, $mailContent);
                                    $mailContent = str_replace("{store name}", $store_name, $mailContent);

                                    $email = new CakeEmail();
                                    $email->from($adminEmail);
                                    $email->to($newEmail);
                                    $email->subject($newUsersubject);
                                    $email->template('register');
                                    $email->emailFormat('html');
                                    $email->viewVars(array('mailContent' => $mailContent,
                                        'source' => $source,
                                        'storename' => $store_name));
                                    $email->send();

                                    $oldChangedUser = $this->Notification->find('first', array(
                                        'conditions' => array('Notification.title' => 'Changed old user email')));
                                    if ($oldChangedUser) {

                                        $oldUserContent = $oldChangedUser['Notification']['content'];
                                        $oldUsersubject = $oldChangedUser['Notification']['subject'];
                                    }

                                    $mailContent = $oldUserContent;
                                    $userID = $this->Customer->id;


                                    $mailContent = str_replace("{firstname}", $customerName, $mailContent);
                                    $mailContent = str_replace("{SITE_URL}", $siteUrl, $mailContent);
                                    $mailContent = str_replace("{store name}", $store_name, $mailContent);
                                    $mailContent = str_replace("{email}", $newEmail, $mailContent);
                                    $email = new CakeEmail();
                                    $email->from($adminEmail);
                                    $email->to($oldEmail);
                                    $email->subject($oldUsersubject);
                                    $email->template('register');
                                    $email->emailFormat('html');
                                    $email->viewVars(array('mailContent' => $mailContent,
                                        'source' => $source,
                                        'storename' => $store_name));
                                    $email->send();

                                    $response['success'] = 1;
                                    $response['message'] = 'Successfully user email has been changed';
                                }
                                break;

                            case 'ProfileUpdate':

                                $customer['Customer']['id'] = $customerId;
                                $customer['Customer']['last_name'] = $this->request->data['last_name'];
                                $customer['Customer']['first_name'] = $this->request->data['first_name'];
                                $customer['Customer']['customer_phone'] = $this->request->data['phone'];
                                $this->Customer->save($customer, null, null);

                                $response['success'] = 1;
                                $response['message'] = 'Your detail has been updated';
                                break;

                            case 'ProfileImageUpload':

                                if (!empty($this->request->data['image'])) {
                                    // Get image string posted from Android App
                                    $base = $this->request->data['image'];

                                    if ($this->request->data['device'] != 'ANDROID') {
                                        $imageSrc = str_replace(" ", "+", $base);
                                    } else {
                                        $base = explode('\n', $base);
                                        $imageSrc = '';
                                        foreach ($base as $key => $value) {
                                            $imageSrc .= stripslashes($value);
                                        }
                                    }

                                    // Get file name posted from Android App
                                    $fileId = $customerDetails['Customer']['first_name'] . time() . '.jpg';
                                    $filename = WWW_ROOT . "Customers/" . $fileId;
                                    // Decode Image
                                    $binary = base64_decode(trim($imageSrc));
                                    header('Content-Type: bitmap; charset=utf-8');

                                    $file = fopen($filename, 'wb+');
                                    // Create File
                                    fwrite($file, $binary);
                                    fclose($file);
                                    #Save Driver Image

                                    $customerImage['Customer']['id'] = $customerDetails['Customer']['id'];
                                    $customerImage['Customer']['image'] = $fileId;
                                    $this->Customer->save($customerImage, null, null);

                                    $customerImage = (!empty($fileId)) ? $this->siteUrl . '/Customers/' . $fileId : '';

                                    $response['success'] = 1;
                                    $response['image'] = $customerImage;
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
                                    $response['success'] = 1;
                                    $response['addressBook'] = $addressBook;
                                } else {
                                    $response['success'] = 0;
                                    $response['message'] = 'No records found';
                                }
                                break;

                            case 'AddressBookAdd':

                                $addressTitle = $this->request->data['address_title'];
                                $addressBook = $this->CustomerAddressBook->find('first', array(
                                    'conditions' => array(
                                        'CustomerAddressBook.customer_id' => $customerId,
                                        'CustomerAddressBook.address_title' => trim($addressTitle)
                                )));

                                if (!empty($addressBook)) {
                                    $response['success'] = 0;
                                    $response['message'] = 'Address Book Already Exists';
                                } else {
                                    $this->request->data['status'] = 1;
                                    $this->CustomerAddressBook->save($this->request->data, null, null);
                                    $response['success'] = 1;
                                    $response['message'] = 'Your CustomerAddressBook has been updated';
                                }
                                break;

                            case 'AddressBook':

                                $addressBookId = $this->request->data['addressBookId'];
                                $addressBook = $this->CustomerAddressBook->find('first', array(
                                    'conditions' => array(
                                        'CustomerAddressBook.id' => $addressBookId,
                                        'CustomerAddressBook.customer_id' => $customerId)
                                ));

                                switch (trim($this->request->data['addressAction'])) {

                                    case 'AddressBookDetails':

                                        $customerCities = $customerLocations = '';

                                        if (!empty($addressBook)) {
                                            $response['success'] = 1;
                                            $response['addressBookDetails'] = $addressBook;
                                        } else {
                                            $response['success'] = 0;
                                            $response['message'] = 'Failed';
                                        }
                                        break;

                                    case 'AddressBookEdit':

                                        $addressTitle = $this->request->data['address_title'];
                                        $addressBook = $this->CustomerAddressBook->find('first', array(
                                            'conditions' => array('CustomerAddressBook.customer_id' => $customerId,
                                                'CustomerAddressBook.address_title' => trim($addressTitle),
                                                'NOT' => array('CustomerAddressBook.id' => $addressBookId))));

                                        if (!empty($addressBook)) {
                                            $response['success'] = 0;
                                            $response['message'] = 'Address Book Already Exists';
                                        } else {
                                            $this->request->data['id'] = $addressBookId;
                                            $this->CustomerAddressBook->save($this->request->data, null, null);
                                            $response['success'] = 1;
                                            $response['message'] = 'Your CustomerAddressBook has been updated';
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
                                            $response['success'] = 1;
                                            $response['message'] = 'Successfully status changed';
                                        } else {
                                            $response['success'] = 0;
                                            $response['message'] = 'Failed';
                                        }
                                        break;

                                    case 'AddressBookDelete':

                                        if (!empty($addressBook)) {
                                            $this->CustomerAddressBook->delete($addressBookId);
                                            $response['success'] = 1;
                                            $response['message'] = 'Successfully deleted addressbook';
                                        } else {
                                            $response['success'] = 0;
                                            $response['message'] = 'Failed';
                                        }
                                        break;
                                }
                                break;

                            case 'OrderList':
                                $orderList = array();
                                $this->Order->recursive = 0;
                                $orderLists = $this->Order->find('all', array(
                                    'conditions' => array('Order.customer_id' => $customerId,
                                        'NOT' => array('Order.status' => 'Deleted')),
                                    'fields' => array('Order.id', 'ref_number', 'order_grand_total',
                                        'payment_type', 'payment_method', 'Order.status',
                                        'Review.rating'),
                                    'order' => array('Order.id DESC')));

                                foreach ($orderLists as $key => $value) {

                                    $value['Order']['rating'] = (!empty($value['Review']['rating'])) ?
                                            $value['Review']['rating'] : '';
                                    $orderList[$key] = $value['Order'];
                                }

                                if (!empty($orderList)) {
                                    $response['success'] = 1;
                                    $response['orderLists'] = $orderList;
                                } else {
                                    $response['success'] = 0;
                                    $response['message'] = 'No records found';
                                }
                                break;

                            case 'OrderDetail':
                                $orderId = $this->request->data['orderId'];
                                $orderDetails = $this->Order->find('first', array(
                                    'conditions' => array('Order.id' => $orderId,
                                        'Order.customer_id' => $customerId,
                                        'NOT' => array('Order.status' => 'Deleted')),
                                    'order' => array('Order.id DESC')));

                                if (!empty($orderDetails)) {

                                    $OrderDetail = $orderDetails['Order'];
                                    $OrderDetail['store_name'] = $orderDetails['Store']['store_name'];
                                    $OrderDetail['store_phone'] = $orderDetails['Store']['store_phone'];
                                    $OrderDetail['store_address'] = $orderDetails['Store']['address'];
                                    $OrderDetail['ShoppingCart'] = $orderDetails['ShoppingCart'];

                                    $response['success'] = 1;
                                    $response['OrderDetail'] = $OrderDetail;
                                } else {
                                    $response['success'] = 0;
                                    $response['message'] = 'No records found';
                                }
                                break;

                            case 'OrderReview':
                                $this->Order->recursive = 0;
                                $orderId = $this->request->data['order_id'];
                                $orderInfo = $this->Order->find('first', array(
                                    'conditions' => array('Order.id' => $orderId,
                                        'Order.status' => 'Delivered')));
                                $checkingReview = $this->Review->find('first', array(
                                    'conditions' => array('Review.order_id' => $orderId)));

                                if ((!empty($orderInfo)) && (empty($checkingReview))) {
                                    $this->request->data['Review']['rating'] = $this->request->data['rating'];
                                    $this->request->data['Review']['message'] = $this->request->data['message'];
                                    $this->request->data['Review']['store_id'] = $orderInfo['Order']['store_id'];
                                    $this->request->data['Review']['order_id'] = $orderId;
                                    $this->request->data['Review']['customer_id'] = $customerId;

                                    $this->Review->save($this->request->data, null, null);

                                    $response['success'] = 1;
                                    $response['message'] = 'Thank you for your review';
                                } else {
                                    $response['success'] = 0;
                                    $response['message'] = 'Review Already Exsits';
                                }
                                break;

                            case 'TrackOrder':

                                $orderId = $this->request->data['orderId'];
                                $orderInfo = $this->Order->find('first', array(
                                    'conditions' => array('Order.id' => $orderId)));

                                if (!empty($orderInfo)) {
                                    $driverDetails = $this->Driver->findById($orderInfo['Order']['driver_id']);
                                    if (!empty($driverDetails)) {

                                        $storeLatitude = $orderInfo['Order']['source_latitude'];
                                        $storeLongitude = $orderInfo['Order']['source_longitude'];
                                        $customerLatitude = $orderInfo['Order']['destination_latitude'];
                                        $customerLongitude = $orderInfo['Order']['destination_longitude'];
                                        $driverLatitude = $driverDetails['DriverTracking']['driver_latitude'];
                                        $driverLongitude = $driverDetails['DriverTracking']['driver_longitude'];

                                        $response['success'] = 1;
                                        $response['orderId'] = $orderInfo['Order']['id'];
                                        $response['ref_number'] = $orderInfo['Order']['ref_number'];
                                        $response['storeLatitude'] = (!empty($storeLatitude)) ? $storeLatitude : '';
                                        $response['storeLongitude'] = (!empty($storeLongitude)) ? $storeLongitude : '';
                                        $response['driverLatitude'] = (!empty($driverLatitude)) ? $driverLatitude : '';
                                        $response['driverLongitude'] = (!empty($driverLongitude)) ? $driverLongitude : '';
                                        $response['customerLatitude'] = (!empty($customerLatitude)) ? $customerLatitude : '';
                                        $response['customerLongitude'] = (!empty($customerLongitude)) ? $customerLongitude : '';
                                    } else {
                                        $response['success'] = 1;
                                        $response['orderId'] = $orderInfo['Order']['id'];
                                    }
                                } else {
                                    $response['success'] = 0;
                                    $response['message'] = $orderInfo['Order']['id'];
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

                                $response['walletAmount'] = $customerDetails['Customer']['wallet_amount'];

                                if (!empty($walletHistory)) {
                                    $response['success'] = 1;
                                    $response['walletHistory'] = $walletHistory;
                                } else {
                                    $response['success'] = 0;
                                    $response['message'] = 'No records found';
                                }
                                break;

                            case 'MyWalletAddMoney':

                                $amount = number_format($this->request->data['amount'], 2);
                                $stripeCardId = $this->request->data['card_id'];

                                if (empty($stripeCardId)) {

                                    $saveOrNot = $this->request->data['saveCard'];
                                    $cardNumber = $this->request->data['stripe_cardnumber'];
                                    $stripe['exp_year'] = $this->request->data['stripe_expyear'];
                                    $stripe['exp_month'] = $this->request->data['stripe_expmonth'];
                                    $stripe['customer_id'] = $customerId;
                                    $stripe['card_number'] = substr($cardNumber, -4);
                                    $stripe['customer_name'] = $this->request->data['cardHolderName'];
                                    $stripe['stripe_token_id'] = $stripeToken = $this->request->data['stripe_token'];
                                    
                                    $stripe['card_id']  = $this->request->data['id_card'];
                                    $stripe['card_brand']  = $this->request->data['card_brand'];
                                    $stripe['card_type']  = $this->request->data['card_type'];
                                    $stripe['country']  = $this->request->data['country'];
                                    $datas = array("stripeToken" => $stripeToken);
                                    $customer = $this->Stripe->customerCreate($datas);
                                    $stripId = $stripe['stripe_customer_id'] = (!empty($customer)) ? $customer['stripe_id'] : '';

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
                                        $datas = array("stripeToken" => $stripeCard['StripeCustomer']['stripe_token_id']);
                                        $customer = $this->Stripe->customerCreate($datas);
                                        $stripId = $stripeCard['StripeCustomer']['stripe_customer_id'] = $customer['stripe_id'];
                                        $this->StripeCustomer->save($stripeCard);
                                    } else {
                                        $stripId = $stripeCard['StripeCustomer']['stripe_customer_id'];
                                    }
                                }
                                $response['stripId'] = $stripId;
                                $data = array('currency' => $this->siteSetting['Country']['currency_code'],
                                    "amount" => $amount,
                                    "stripeCustomer" => $stripId);

                                $stripeResponse = $this->Stripe->charge($data);
                                $response['stripeResponse'] = $stripeResponse;
                                if ($stripeResponse['status'] == "succeeded" && $stripeResponse['stripe_id'] != '') {
                                    $response['stripeResponse'] = $stripeResponse;
                                    // Wallet Amount Added
                                    $customerDetails['Customer']['wallet_amount'] += $amount;
                                    $this->Customer->save($customerDetails, null, null);

                                    // Wallet History
                                    $walletHistory['amount'] = $amount;
                                    $walletHistory['purpose'] = 'Money added in wallet';
                                    $walletHistory['customer_id'] = $customerId;
                                    $walletHistory['transaction_details'] = $stripeResponse['stripe_id'];

                                    $this->WalletHistory->save($walletHistory, null, null);

                                    $response['success'] = 1;
                                    $response['message'] = 'Successfully added amount in your wallet';
                                } else {
                                    $response['success'] = 0;
                                    $response['message'] = 'Some technical problem. Please try again!';
                                }
                                break;

                            case 'CustomerLogOut':
                                $customerDetails['Customer']['is_logged'] = '0';
                                $customerDetails['Customer']['device_id'] = '';

                                $customerLogout = $this->Customer->save($customerDetails);
                                $response['success'] = 1;
                                $response['message'] = 'Successfully customer logged out';
                                break;
                        }
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'Unknown customer';
                    }
                    break;
                case "facebookLogin":
                    //Facebook Login

                    if (!empty($this->request->data('email'))) {
                        // email: email, username: Fname + " " + Lname, role_id: roleId, first_name: Fname, last_name: Lname
                        $incomingProfile['User']["email"] = $this->request->data('email');
                        $incomingProfile['User']["first_name"] = $this->request->data('email');
                        $incomingProfile['User']["last_name"] = $this->request->data('email');
                        $incomingProfile['User']["role_id"] = $this->request->data('role_id');
                 
                        $existingProfile = $this->User->find('first', array(
                            'conditions' => array('User.username' => $incomingProfile['User']['email'])));
                        if ($existingProfile) {
                            $response['message'] = $this->_doFacebookSocialLogin($existingProfile, true);
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
                            $response['message'] = ($this->_doFacebookSocialLogin($newProfile));
                        }
                    } else {
                        $response['message'] = "Invalid post parameters";
                    }
                    break;
            }
        } else {
            $response['success'] = '0';
            $response['message'] = 'Invalid request';
        }
        die(json_encode($response));
    }

    // Restaurant Offer
    public function storeOffer($storeId = null) {

        $today = date("m/d/Y");
        $this->Storeoffer->recursive = -1;
        $storeOffers = $this->Storeoffer->find('first', array(
            'conditions' => array(
                'Storeoffer.store_id' => $storeId,
                'Storeoffer.status' => 1,
                "Storeoffer.from_date <=" => $today,
                "Storeoffer.to_date >=" => $today),
            'order' => 'Storeoffer.id DESC'));
        return $storeOffers;
    }

    // Addressbook list
    public function addressBook($customerId = null, $page = null) {

        if ($page == 'checkout') {
            $addressBook = $this->CustomerAddressBook->find('all', array(
                'conditions' => array('CustomerAddressBook.customer_id' => $customerId,
                    'CustomerAddressBook.status' => 1),
                'fields' => array('CustomerAddressBook.id', 'CustomerAddressBook.address_title',
                    'CustomerAddressBook.google_address', 'CustomerAddressBook.address_phone',
                    'CustomerAddressBook.status')
            ));
        } else {
            $addressBook = $this->CustomerAddressBook->find('all', array(
                'conditions' => array('CustomerAddressBook.customer_id' => $customerId,
                    'NOT' => array('CustomerAddressBook.status' => 3)),
                'fields' => array('CustomerAddressBook.id', 'CustomerAddressBook.address_title',
                    'CustomerAddressBook.google_address', 'CustomerAddressBook.address_phone',
                    'CustomerAddressBook.status')
            ));
        }

        foreach ($addressBook as $key => $value) {
            $addressBook[$key] = $value['CustomerAddressBook'];
        }
        return $addressBook;
    }

    // login and redirect path
    public function _doFacebookSocialLogin($user, $returning = false) {

        $userDetails = $user['User'];
        $userDetails['Customer'] = $user['Customer'];

        if (!empty($user['Customer']['id'])) {
            $this->Session->write("preSessionid", $this->Session->id());
            if ($this->Auth->login($userDetails)) {
                if ($this->Session->read("redirectpage") == 'checkout') {
                    $this->Session->delete("redirectpage");
                    return "/checkouts/index";
                }
            }
        }
        return "/customer/Customers/myaccount";
    }
     // Order mail
    public function ordermail($orderId) {

        $datas = $this->Order->findById($orderId);
        $store_id = $datas['Order']['store_id'];

        // Pusher in Store and Admin
        $message = 'Une nouvelle commande a été passée - ' . $datas['Order']['ref_number'];
        $this->Notifications->pushNotification($message, 'FoodOrderAdmin');
        $this->Notifications->pushNotification($message, 'Restaurant_' . $store_id);

        $statusmailCustomer = $this->Notification->find('first', array(
            'conditions' => array('Notification.title =' => 'Order details mail')));

        $statusmailSeller = $this->Notification->find('first', array(
            'conditions' => array('Notification.title =' => 'Order sellar Mail')));

        $customerContent = $statusmailCustomer['Notification']['content'];
        $customerSubject = $statusmailCustomer['Notification']['subject'];

        $sellerContent = $statusmailSeller['Notification']['content'];
        $sellerSubject = $statusmailSeller['Notification']['subject'];


        $name = '<table width="100%" border="0" style="border-color:#d9d9d9;"  cellspacing="0">
      <tbody><tr style="border-bottom:1px solid #ccc">
            <th style="font:bold 14px/30px Arial;background:#90BC37;color:#ffffff; padding:8px; border:1px solid #d9d9d9;border-right:0;" width="3%">
              S.No</th>
            <th style="font:bold 14px/30px Arial;background:#90BC37;color:#ffffff; padding:8px; border:1px solid #d9d9d9;border-right:0;" width="25%">
              Nom du Menu</th>
            <th style="font:bold 14px/30px Arial;background:#90BC37;color:#ffffff; padding:8px; border:1px solid #d9d9d9;border-right:0;" width="5%">
              Qté</th>
            <th style="font:bold 14px/30px Arial;background:#90BC37;color:#ffffff; padding:8px; border:1px solid #d9d9d9;border-right:0;" width="5%">
              Prix</th>
            <th style="font:bold 14px/30px Arial;background:#90BC37;color:#ffffff; padding:8px; border:1px solid #d9d9d9;border-right:0;" width="12%">
              Prix Global</th>
    </tr>';

        $source = $this->siteUrl . '/siteicons/logo.png';
        $Currency = $this->siteSetting['Country']['currency_symbol'];

        $order_description = '';
        if (!empty($datas['Order']['order_description'])) {
            $order_description = '<div style="width:100%; display:inline-block;">
                        <span style="width:45%; display:inline-block;font:bold 15px Arial; text-align:right; margin:5px 0;">
                           Instructions :
                        </span>
                        <span style="width:45%; display:inline-block;font:15px Arial; margin:5px 0; padding-left:10px;"> ' .
                    $datas['Order']['order_description'] . '
                        </span>
                      </div>';
        }

        foreach ($datas['ShoppingCart'] as $key => $data) {

            $subaddons = '';
            if (!empty($data['subaddons_name'])) {
                $subaddons = '<br>' . $data['subaddons_name'];
            }

            $product_description = '';
            if (!empty($data['product_description'])) {
                $product_description = '<br>' . $data['product_description'];
            }

            $productSrc = $this->siteUrl . '/stores/' . $store_id . '/products/carts/' . $data['product_image'];

            $serialNo = $key + 1;

            $name.='<tr>
            <td style="border:1px solid #09a925;border-top:0;border-right:0;text-align:center; color:#000;font:14px Arial;border-left:0;" >' . $serialNo . '
            <td style="border:1px solid #09a925;border-top:0;border-right:0;text-align:left; color:#000;font:14px Arial"><span style="padding:10px; display:inline-block;">' .
                    $data['product_name'] . ' ' . $subaddons . ' ' . $product_description . '</span></td>
            <td style="border:1px solid #09a925;border-top:0;border-right:0;text-align:center; color:#000;font:14px Arial" >' .
                    $data['product_quantity'] . '</td>
            <td style="border:1px solid #09a925;border-top:0;border-right:0;text-align:right; padding-right:10px; color:#000;font:14px Arial" width="10%">' .
                    $Currency . " " . $data['product_price'] . '</td>
            <td style="border:1px solid #09a925;border-top:0;text-align:right; padding-right:10px; color:#000;font:14px Arial;border-right:0;" >' .
                    $Currency . " " . $data['product_total_price'] . '</td>
      </tr>';
        }

        $name.='<tr>
          <td colspan="4" style="text-align:right; padding-right:10px;font:bold 16px/30px Arial; border:1px solid #09a925;border-right:0;border-top:0;border-left:0;">
              Sous-Total</td>
          <td style="text-align:right; padding-right:10px;font:16px/30px Arial; border:1px solid #09a925;border-top:0;border-right:0;">' .
                $Currency . " " . $datas['Order']['order_sub_total'] . '</td>
      </tr>';


        if ($datas['Order']['offer_amount'] > 0) {
            $name.='<tr>
            <td colspan="4" style="text-align:right; padding-right:10px;font:bold 16px/30px Arial; border:1px solid #09a925;border-right:0;border-top:0;border-left:0;">
                Offre (' . $datas['Order']['offer_percentage'] . ' %) </td>
            <td style="text-align:right; padding-right:10px;font:16px/30px Arial; border:1px solid #09a925;border-top:0;border-right:0;">' .
                    $Currency . " " . $datas['Order']['offer_amount'] . '</td>
        </tr>';
        }

        if ($datas['Order']['voucher_amount'] != 0) {
            $name.='<tr>
            <td colspan="4" style="text-align:right; padding-right:10px;font:bold 16px/30px Arial; border:1px solid #09a925;border-right:0;border-top:0;border-left:0;">
                Voucher Discount';
            if ($datas['Order']['voucher_percentage'] > 0) {
                $name.= ' (' . $datas['Order']['voucher_percentage'] . ' %)';
            }
            $name.= '</td>
            <td style="text-align:right; padding-right:10px;font:16px/30px Arial; border:1px solid #09a925;border-top:0;border-right:0;">' .
                    $Currency . " " . $datas['Order']['voucher_amount'] . '</td>
        </tr>';
        }

        if ($datas['Order']['tax_amount'] > 0) {
            $name.='<tr>
            <td colspan="4" style="text-align:right; padding-right:10px;font:bold 16px/30px Arial; border:1px solid #09a925;border-right:0;border-top:0;border-left:0;">
                T.V.A  (' . $datas['Order']['tax_percentage'] . ' %)</td>
            <td style="text-align:right; padding-right:10px;font:16px/30px Arial; border:1px solid #09a925;border-top:0;border-right:0;">' .
                    $Currency . " " . $datas['Order']['tax_amount'] . '</td>
        </tr>';
        }

        if ($datas['Order']['delivery_charge'] != 0 || $datas['Order']['voucher_code'] != '' && $datas['Order']['voucher_amount'] == 0) {
            $name.='<tr>
            <td colspan="4" style="text-align:right; padding-right:10px;font:bold 16px/30px Arial; border:1px solid #09a925;border-right:0;border-top:0;border-left:0;">
                Frais de livraison</td>
            <td style="text-align:right; padding-right:10px;font:16px/30px Arial; border:1px solid #09a925;border-top:0;border-right:0;">';
            $name.= ($datas['Order']['delivery_charge'] > 0) ?
                    $Currency . ' ' . $datas['Order']['delivery_charge'] : 'Free Delivery';
            $name.='</td></tr>';
        }

        if ($datas['Order']['tip_amount'] > 0) {
            $name.='<tr>
          <td colspan="4" style="text-align:right; padding-right:10px;font:bold 16px/30px Arial; border:1px solid #09a925;border-right:0;border-top:0;border-left:0;"> Tip </td>
          <td style="text-align:right; padding-right:10px;font:16px/30px Arial; border:1px solid #09a925;border-top:0;border-right:0;">' .
                    $Currency . " " . $datas['Order']['tip_amount'] . '</td>
      </tr>';
        }

        $name.='<tr>
          <td colspan="4" style="text-align:right;  padding-right:10px;color:#09a925;font:bold 18px/30px Arial; border:1px solid #09a925;border-right:1px solid #09a925; border-bottom:1px solid #09a925;border-top:0;border-right:0;border-left:0;">
            Total</td>
          <td style="text-align:right; padding-right:10px;color:#09a925;font:bold 18px/30px Arial; border:1px solid #09a925;border-right:1px solid #09a925; border-bottom:1px solid #09a925; border-top:0;border-right:0;">' .
                $Currency . " " . $datas['Order']['order_grand_total'] . ' </td>
      </tr>
    </table>
    </div>';

        $orderType = ($datas['Order']['order_type'] == 'Collection') ? 'Pickup' : 'Livraison';
        $delTime = ($datas['Order']['order_type'] == 'Collection') ? 'A emporter Time' : 'Heure de livraison';
        $payment_type = ($datas['Order']['payment_type'] == 'cod') ? 'paiement espèce' : (($datas['Order']['payment_type'] == 'Card') ? 'Carte bancaire' : $datas['Order']['payment_type']);

        $deldatetime = date('Y/m/d', strtotime($datas['Order']['delivery_date'])) . ' ' . $datas['Order']['delivery_time'];

        $Address .= '<div style="width:100%; display:inline-block; ">
                <div style="width:100%; display:inline-block;margin-top:20px;">
                  <div style="width:46%; display:inline-block; vertical-align:top; padding-left:20px;">

                    <div style="width:100%; display:inline-block;">
                      <h3 style="font-family:Arial; color:#09a925;" >
                        Voici les informations de votre commande :</th> </h3>
                      <div style="width:100%; display:inline-block;">
                          <span style="width:45%; display:inline-block;font:bold 15px Arial; text-align:right; margin:5px 0;">
                              Numéro de commande/ID :
                          </span>
                          <span style="width:45%; display:inline-block;font:15px Arial; margin:5px 0; padding-left:10px;">' .
                $datas['Order']['ref_number'] . '
                          </span> 
                      </div>
                      <div style="width:100%; display:inline-block;">
                        <span style="width:45%; display:inline-block;font:bold 15px Arial; text-align:right; margin:5px 0;">
                          Moyen de paiement :
                        </span>
                        <span style="width:45%; display:inline-block;font:15px Arial; margin:5px 0; padding-left:10px;">' .
                str_ireplace('cod', __('Cash on delivery'), $datas['Order']['payment_type']) . '
                        </span> 
                      </div>
                      <div style="width:100%; display:inline-block;">
                        <span style="width:45%; display:inline-block;font:bold 15px Arial; text-align:right; margin:5px 0;">
                            Type de commande : 
                         </span>
                        <span style="width:45%; display:inline-block;font:15px Arial; margin:5px 0; padding-left:10px;"> ' .
                $orderType . '
                        </span> 
                      </div>  
                      <div style="width:100%; display:inline-block;">
                        <span style="width:45%; display:inline-block;font:bold 15px Arial; text-align:right; margin:5px 0;">
                          ' . $delTime . ':
                        </span>
                        <span style="width:45%; display:inline-block;font:15px Arial; margin:5px 0; padding-left:10px;"> ' . $deldatetime . '
                        </span>
                      </div>
                      ' . $order_description . '
                    </div>
                    </div>
                    <div style="width:45%; display:inline-block;border-left:1px dotted #09a925;min-height:200px; padding-left:30px;vertical-align:top;">
                      <div style="width:100%; display:inline-block;">
                        <h3 style="font-family:Arial; color:#09a925;" >
                          Adresse
                        </h3>
                      </div>
                      <div style="width:100%; display:inline-block;">
                        <span style="width:100%; display:inline-block;font:bold 15px Arial;margin:5px 0;">' .
                $datas['Order']['customer_name'] . '
                        </span>';
        if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
            $Address .= '<span style="width:100%; display:inline-block;font:15px Arial; margin:5px 0;">' .
                    $datas['Order']['address'] . " " .
                    $datas['Order']['location_name'] . ',' .
                    $datas['Order']['city_name'] . '
                          </span>
                          <span style="width:100%; display:inline-block;font:15px Arial; margin:5px 0;">' .
                    $datas['Order']['state_name'] . " - " .
                    $this->siteSetting['Country']['country_name'] . '
                          </span>';
        } else {
            $Address .= '<span style="width:100%; display:inline-block;font:15px Arial; margin:5px 0;">' .
                    $datas['Order']['google_address'] . '
                          </span>';
        }
        $Address .= ' <span style="width:100%; display:inline-block;font:bold 15px Arial;margin:5px 0;">' .
                $datas['Order']['customer_phone'] . '</span>
                        </div>
                      </div>
                    </div>';

        $customer_mail = $datas['Order']['customer_email'];
        $customerName = $datas['Order']['customer_name'];
        $storename = $datas['Store']['store_name'];
        $sitemailId = $this->siteSetting['Sitesetting']['admin_email'];

        $mailContent = $customerContent;
        $siteUrl = $this->siteUrl;

        $mailContent = str_replace("{Customer name}", $customerName, $mailContent);
        $mailContent = str_replace("{source}", $source, $mailContent);
        $mailContent = str_replace("{Store name}", $storename, $mailContent);
        $mailContent = str_replace("{orderid}", $datas['Order']['ref_number'], $mailContent);
        $mailContent = str_replace("{note}", $name, $mailContent);
        $mailContent = str_replace("{Address}", $Address, $mailContent);
        $mailContent = str_replace("{SITE_URL}", $siteUrl, $mailContent);
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
            $mailContent = str_replace("{Customer name}", $customerName, $mailContent);
            $mailContent = str_replace("{source}", $source, $mailContent);
            $mailContent = str_replace("{Store name}", $storename, $mailContent);
            $mailContent = str_replace("{orderid}", $datas['Order']['ref_number'], $mailContent);
            $mailContent = str_replace("{note}", $name, $mailContent);
            $mailContent = str_replace("{Address}", $Address, $mailContent);
            $mailContent = str_replace("{SITE_URL}", $siteUrl, $mailContent);
            $sellerSubject = str_replace("[ Order ID ]", $datas['Order']['ref_number'], $sellerSubject);
            $sellerSubject = str_replace("customer name", $customerName, $sellerSubject);
            $storename = $this->siteSetting['Sitesetting']['site_name'];

            $email = new CakeEmail();
            $email->from($customer_mail);
            $email->to($storemailId);
            $email->subject($sellerSubject);
            $email->template('ordermail');
            $email->emailFormat('html');
            $email->viewVars(array('mailContent' => $mailContent,
                'source' => $source,
                'storename' => $storename));

            // echo "<pre>";print_r($mailContent);echo "</pre>";exit;

            $email->send();
        }
        // return true;
    }
//Order Sms
    public function ordersms($orderId) {

        $orderDetail = $this->Order->findById($orderId);
        /* $customerMessage = 'Thanks for using '.$this->siteSetting['Sitesetting']['site_name'].' service. Your order '.$orderDetail['Order']['ref_number'].' has been placed . Track your order at '.$this->siteUrl.'.  Regards '.$this->siteSetting['Sitesetting']['site_name'].'.'; */

        $customerMessage = "Merci d'avoir utilisé les services d’" . $this->siteSetting['Sitesetting']['site_name'] . '. Votre commande ' . $orderDetail['Order']['ref_number'] . ' est passée . Vous pouvez suivre votre commande à ' . $this->siteUrl . '.  Merci l’équipe d’ ' . $this->siteSetting['Sitesetting']['site_name'] . '.';

        $toCustomerNumber = '+' . $this->siteSetting['Country']['phone_code'] . $this->Auth->User('Customer.customer_phone');
        $customerSms = $this->Twilio->sendSingleSms($toCustomerNumber, $customerMessage);

        if ($orderDetail['Store']['sms_option'] == 'Yes' && !empty($orderDetail['Store']['sms_phone'])) {
            $storeMessage = "Dear " . $orderDetail['Store']['store_name'] . " you've received a ";
            $storeMessage .= ($orderDetail['Order']['payment_method'] != 'paid') ? 'COD' : 'PAID';
            $storeMessage .= ' order ' . $orderDetail['Order']['ref_number'] . ' from ' . $orderDetail['Order']['customer_name'];

            if ($orderDetail['Order']['order_type'] == 'Delivery') {
                if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
                    $storeMessage .= ',' . $orderDetail['Order']['address'] . ',' . $orderDetail['Order']['landmark'] .
                            ',' . $orderDetail['Order']['location_name'] . ',' . $orderDetail['Order']['city_name'] .
                            ',' . $orderDetail['Order']['city_name'];
                } else {
                    $storeMessage .= ',' . $orderDetail['Order']['google_address'];
                }
            }

            $storeMessage .= '. ' . $orderDetail['Order']['order_type'] . ' due on ' . $orderDetail['Order']['delivery_date'] . ' at ' . $orderDetail['Order']['delivery_time'] . '. Thanks ' . $this->siteSetting['Sitesetting']['site_name'] . '';
            $toStoreNumber = '+' . $this->siteSetting['Country']['phone_code'] . $orderDetail['Store']['sms_phone'];
            $customerSms = $this->Twilio->sendSingleSms($toStoreNumber, $storeMessage);
        }

        // Store Owner App Message
        if ($orderDetail['Store']['is_logged'] == 1) {
            $deviceId = $orderDetail['Store']['device_id'];
            $message = 'Une nouvelle commande a été passée - ' . $orderDetail['Order']['ref_number'];

            $gcm = $this->AndroidResponse->sendOrderByGCM(
                    array('message' => $message), $deviceId);
        }
        return true;
    }
     public function getStoreOpenCloseTime($storeid, $date) {

        $day = strtolower(date("l", strtotime($date)));
        $storeDetails = $this->StoreTiming->find('first', array(
            'fields' => array(
                'id',
                $day . '_firstopen_time',
                $day . '_firstclose_time',
                $day . '_secondopen_time',
                $day . '_secondclose_time',
                $day . '_status'
            ),
            'conditions' => array(
                'store_id' => $storeid
            )
        ));
         
        $firstOpenTime = $storeDetails['StoreTiming'][$day . '_firstopen_time'];
        $firstCloseTime = $storeDetails['StoreTiming'][$day . '_firstclose_time'];
        list($storeDetails['StoreTiming']['first_status'],
                $storeDetails['StoreTiming']['ffirst_open_time'],
                $storeDetails['StoreTiming']['ffirst_close_time'],
                $storeDetails['StoreTiming']['fsec_open_time'],
                $storeDetails['StoreTiming']['fsec_close_time']) = $this->
                openandcloseDelivery($firstOpenTime, $firstCloseTime);

        $secondOpenTime = $storeDetails['StoreTiming'][$day . '_secondopen_time'];
        $secondCloseTime = $storeDetails['StoreTiming'][$day . '_secondclose_time'];
        list($storeDetails['StoreTiming']['second_status'],
                $storeDetails['StoreTiming']['sfirst_open_time'],
                $storeDetails['StoreTiming']['sfirst_close_time'],
                $storeDetails['StoreTiming']['ssec_open_time'],
                $storeDetails['StoreTiming']['ssec_close_time']) = $this->
                openandcloseDelivery($secondOpenTime, $secondCloseTime);

        return array(
            $storeDetails['StoreTiming'][$day . '_status'],
            $storeDetails['StoreTiming']['ffirst_open_time'],
            $storeDetails['StoreTiming']['ffirst_close_time'],
            $storeDetails['StoreTiming']['fsec_open_time'],
            $storeDetails['StoreTiming']['fsec_close_time'],
            $storeDetails['StoreTiming']['sfirst_open_time'],
            $storeDetails['StoreTiming']['sfirst_close_time'],
            $storeDetails['StoreTiming']['ssec_open_time'],
            $storeDetails['StoreTiming']['ssec_close_time'],
            $storeDetails['StoreTiming']['first_status'],
            $storeDetails['StoreTiming']['second_status']);
    }

     public function getDateTimeRest($storeid, $postDate) {

        $currentday = date('d-m-Y');
        $storeid = (!empty($_POST['storeid'])) ? $_POST['storeid'] : $storeid;
        $date = (!empty($postDate)) ? $postDate : $currentday;
        $day = date("D", strtotime($date));
        $content = '';
         
        list($openStatus,
                $ffirst_open_time,
                $ffirst_close_time,
                $fsec_open_time,
                $fsec_close_time,
                $sfirst_open_time,
                $sfirst_close_time,
                $ssec_open_time,
                $ssec_close_time,
                $firstStatus,
                $secondStatus) = $this->getStoreOpenCloseTime($storeid, $date);

        if (!empty($sfirst_open_time))
            $yy_sfirst_open_time = strtotime($sfirst_open_time);
        if (!empty($sfirst_close_time))
            $yy_sfirst_close_time = strtotime($sfirst_close_time);
        if (!empty($ffirst_open_time))
            $yy_ffirst_open_time = strtotime($ffirst_open_time);
        if (!empty($ffirst_close_time))
            $yy_ffirst_close_time = strtotime($ffirst_close_time);
        if (!empty($fsec_open_time))
            $yy_fsec_open_time = strtotime($fsec_open_time);
        if (!empty($fsec_close_time))
            $yy_fsec_close_time = strtotime($fsec_close_time);
        if (!empty($ssec_open_time))
            $yy_ssec_open_time = strtotime($ssec_open_time);
        if (!empty($ssec_close_time))
            $yy_ssec_close_time = strtotime($ssec_close_time);

        if ($openStatus == 'Open') {
            #Current date calculation
            if ($currentday == $date) {
                $minute = date("i");
                $modul = $minute % 5;

                if ($modul != 0) {
                    if ($modul == 1)
                        $minute = $minute + 4;
                    if ($modul == 2)
                        $minute = $minute + 3;
                    if ($modul == 3)
                        $minute = $minute + 2;
                    if ($modul == 4)
                        $minute = $minute + 1;
                }
                $nowtime = strtotime(date("h", time()) . ":" . $minute . " " . date("A", time()));
                ;

                if ($nowtime == '') {
                    $nowtime = time();
                }
                if ($nowtime > $yy_fsec_open_time) {
                    $yy_fsec_open_time = $nowtime;
                }
                if ($nowtime > $yy_ssec_open_time) {
                    $yy_ssec_open_time = $nowtime;
                }
            }

            if (!empty($yy_sfirst_open_time) && !empty($yy_sfirst_close_time)) {
                for ($i = $yy_sfirst_open_time; $i <= $yy_sfirst_close_time; $i++) {

                    $i = (($i == $yy_sfirst_open_time)) ? $i = ($i + (45 * 60)) : $i = ($i + (15 * 60));

                    if ($i > $yy_sfirst_close_time)
                        $i = $yy_sfirst_close_time;



                    $content .= $day . '  - ' . date("h:i A", $i) . ',';
                }
            }

            #Second Open time
            if (isset($yy_ffirst_open_time) && !empty($yy_ffirst_open_time) && isset($yy_ffirst_close_time) &&
                    !empty($yy_ffirst_close_time)
            ) {
                for ($i = $yy_ffirst_open_time; $i <= $yy_ffirst_close_time; $i++) {
                    $i = (($i == $yy_ffirst_open_time)) ? $i = ($i + (45 * 60)) : $i = ($i + (15 * 60));

                    if ($i > $yy_ffirst_close_time)
                        $i = $yy_ffirst_close_time;

                    $content .= $day . '  - ' . date("h:i A", $i) . ',';
                }
            }

            #Third Open time
            if (isset($yy_fsec_open_time) && !empty($yy_fsec_open_time) && isset($yy_fsec_close_time) &&
                    !empty($yy_fsec_close_time)
            ) {
                for ($i = $yy_fsec_open_time; $i <= $yy_fsec_close_time; $i++) {
                    $i = (($i == $yy_fsec_open_time)) ? $i = ($i + (45 * 60)) : $i = ($i + (15 * 60));

                    if ($i > $yy_fsec_close_time)
                        $i = $yy_fsec_close_time;

                    $content .= $day . '  - ' . date("h:i A", $i) . ',';
                }
            }

            #Fourth Open time
            if (isset($yy_ssec_open_time) && !empty($yy_ssec_open_time) && isset($yy_ssec_close_time) &&
                    !empty($yy_ssec_close_time)
            ) {
                for ($i = $yy_ssec_open_time; $i <= $yy_ssec_close_time; $i++) {
                    $i = (($i == $yy_ssec_open_time)) ? $i = ($i + (45 * 60)) : $i = ($i + (15 * 60));

                    if ($i > $yy_ssec_close_time)
                        $i = $yy_ssec_close_time;
                    $content .= $day . '  - ' . date("h:i A", $i) . ',';
                }
            }
        } else {
            $content = 'Closed';
        }
        return array($content, $openStatus, $firstStatus, $secondStatus);
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
    	$storeSubject 	 = $Customer.' réservation '.$bookingId.' details';
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
            $customerMessage  = 'Votre reservation a été pris en compte.';
        } else {

            if($status == 'Approved') {
                $customerMessage  = 'Votre réservation a été acceptée';
            } elseif ($status == 'Cancel') {
                $customerMessage  = 'Votre réservation a été annulée';
            } else {
                $customerMessage  = 'Votre réservation a été '.strtolower($status);
            }
            
            // $customerMessage  = 'Your booked table has been '.strtolower($status);
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
