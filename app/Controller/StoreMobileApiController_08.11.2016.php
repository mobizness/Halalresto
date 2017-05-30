<?php

/* Janakiraman */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
class StoreMobileApiController extends AppController{
    public $components = array('AndroidResponse', 'Functions', 'Googlemap', 'Twilio', 'PushNotifications');
    public $uses = array('User', 'Order', 'Notification', 'State', 'City', 'Location', 'Store', 'Driver',
                        'DriverTracking');

    public function beforeFilter() {
        
        $this->Auth->allow('request');
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

        if ($this->request->is('post')) {
            if (empty($this->request->data)) {
                $data = $this->request->input('json_decode', true);
                $this->request->data = $data;
            }
            switch (trim($this->request->data['action'])) {
                case 'StoreLogin':
                    $this->request->data['User']['username'] = $this->request->data['username'];
                    $this->request->data['User']['password'] = $this->request->data['password'];
                    $this->request->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
                    $store = $this->User->find('first', array(
                                    'conditions' => array(
                                    'User.username' => $this->request->data['User']['username'],
                                    'User.password' => $this->request->data['User']['password']),
                                    'Store.status !=' => 3));
                    if(!empty($store)){
                        $getData             = $store['Store']['id'];
                        $store_name          = $store['Store']['store_name'];
                        $user_id             = $store['Store']['user_id'];
                        $this->request->data['Store']['id'] =  $getData ;
                        $this->request->data['Store']['device_id'] = $this->request->data['device_id'] ;
                        $this->request->data['Store']['device_name'] = $this->request->data['device_name'];
                        $this->request->data['Store']['is_logged'] = 1;
                        $this->Store->save($this->request->data['Store']);
                        $response['success'] = '1';
                        $response['id']      = $getData ;
                        $response['storename']  = $store_name ;
                        $response['message'] = 'Login Sucessfully ';

                    } else {
                        $response['success'] = '0';
                        $response['message'] = 'Please Check your user name and password';
                    }
                break;
                case 'forgotmail':

                    $userData = $this->User->find('first', array(
                                           'conditions' =>array(
                                               'User.username' => trim($this->request->data['forgetemail']),
                                               'Store.status' => 1,
                                               'User.role_id' => 3)));

                    if(!empty($userData)){
                       $newRegisteration = $this->Notification->find('first', array(
                                            'conditions'=>array('Notification.title =' => 'Reset password')));

                       $toemail      = $this->request->data['forgetemail'];
                       $source       = $this->siteUrl.'/siteicons/logo.png';
                       $storeEmail   = $this->siteSetting['Sitesetting']['admin_email'];
                       $siteName     = $this->siteSetting['Sitesetting']['site_name'];
                       $storename    = $userData['Store']['store_name'];
                       $tmpPassword  = $this->Functions->createTempPassword(7);

                       $datas['User']['password']=$this->Auth->Password($tmpPassword);
                       $datas['User']['id'] = $userData['User']['id'];

                        if ($this->User->save($datas['User'],null,null)){
                            if($newRegisteration){
                                $forgetpasswordContent = $newRegisteration['Notification']['content'];
                                $forgetpasswordsubject = $newRegisteration['Notification']['subject'];
                            }

                            $mailContent = $forgetpasswordContent;
                            $siteUrl = $this->siteUrl.'/store';
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
                                                    'source'=>$source,
                                                    'storename'=>$siteName));
                            if($email->send()){
                                // Forget Sms
                                $storeMessage = "We've received a request to change your password. Use this password ".$tmpPassword." to login to your account and update it ASAP. Thanks '.$this->siteSetting['Sitesetting']['site_name'].'";
                                $toStoreNumber = '+'.$this->siteSetting['Country']['phone_code'].$userData['Store']['contact_phone'];
                                $storeSms     = $this->Twilio->sendSingleSms($toStoreNumber, $storeMessage);

                                $response['success'] = '1';
                                $response['message'] = 'Email has been sent successfully';
                            }
                        }
                    } else {
                        $response['success'] = '0';
                        $response['message'] = 'You are not authorized';
                    }
                break;

                case 'StoreDetail':
                    $id    = $this->request->data['user_id'];                    
                    $store = $this->Store->find('first', array(
                                    'conditions' => array('Store.id'=>$id)));
                    if(!empty($store)){
                        $response['id']          = $store['Store']['id'];
                        $response['name']        = $store['Store']['contact_name'] ;
                        $response['email']       = $store['Store']['contact_email'];
                        $response['phone']       = $store['Store']['contact_phone']; 
                        $response['success'] = '1';

                    } else {
                        $response['success'] = '0';
                        $response['message'] = 'Please Check your user name and password';
                    }
                break;

                case 'EditStoreDetail':
                    if(!empty($this->request->data['user_id'])) {
                        $this->request->data['Store']['id']             = $this->request->data['user_id'];
                        $this->request->data['Store']['contact_name']   = $this->request->data['name'];
                        $this->request->data['Store']['contact_email']  = $this->request->data['email'];
                        $this->request->data['Store']['contact_phone']  = $this->request->data['phone'];
                        $this->Store->save($this->request->data['Store']);

                        $response['success'] = '1';
                        $response['message'] = 'Profile Updated successfully';

                    } else {
                        $response['success'] = '0';
                        $response['message'] = 'Please Check your user name and password';
                    }
                break; 

                case 'pendingList':
                    $store_id                = $this->request->data['user_id'];
                    if ($store_id != '') {
                        $store = $this->Store->findById($store_id);
                        if(!empty($store)) {
                            $this->Order->recursive = 0;
                            $order_list = $this->Order->find('all', array(
                                'fields'=>array(
                                    'ref_number',
                                    'customer_name',
                                    'customer_phone',
                                    'created',
                                    'order_type',
                                    'delivery_time'
                                ),
                                'conditions' => array(
                                    'Order.store_id'=>$store_id,
                                    'Order.status' => 'Pending'
                                ),
                                'order' => array(
                                    'Order.id DESC'
                                )
                            ));

                            if (!empty($order_list)) {
                                foreach ($order_list as $key => $value) {
                                    $getData[$key]['id']        = $value['Order']['id'];                          
                                    $getData[$key]['orderId']   = $value['Order']['ref_number'];
                                    $getData[$key]['name']      = $value['Order']['customer_name'];
                                    $getData[$key]['date']      = $value['Order']['created'];
                                    $getData[$key]['orderType'] = $value['Order']['order_type'];
                                    $getData[$key]['phonenumber'] = $value['Order']['customer_phone'];
                                    $getData[$key]['deliverytime'] = $value['Order']['delivery_time'];
                                }

                                $response['pendinglist'] = $getData;
                                $response['success'] = '1';
                                $response['message'] = 'Successfully';
                            } else {
                                $response['success'] = '0';
                                $response['message'] = 'No Record Found';
                            }
                        } else {
                            $response['success'] = '0';
                            $response['message'] = 'No Record Found';
                        }
                    } else {
                        $response['success'] = '0';
                        $response['message'] = 'No Record Found';
                    }
                break;

                case 'acceptList':
                    $store_id                = $this->request->data['user_id'];
                    if ($store_id != '') {
                        $store = $this->Store->findById($store_id);
                        if(!empty($store)) {
                            $this->Order->recursive = 0;
                            $status = array('Delivered','Pending','Failed');
                            $order_list = $this->Order->find('all', array(
                                'conditions' => array(
                                    'Order.store_id'=>$store_id,
                                    'NOT' => array(
                                        'Order.status' => $status
                                    )
                                ),
                                'order' => array(
                                    'Order.id DESC'
                                )
                            ));

                            if (!empty($order_list)) {
                                foreach ($order_list as $key => $value){
                                    $getData[$key]['id']        = $value['Order']['id'];            
                                    $getData[$key]['orderId']   = $value['Order']['ref_number'];
                                    $getData[$key]['status']    = $value['Order']['status'];
                                    $getData[$key]['username']  = $value['Order']['customer_name'];
                                    $getData[$key]['phone']     = $value['Order']['customer_phone'];
                                    $getData[$key]['date']      = $value['Order']['delivery_date'];
                                    $getData[$key]['orderType']     = $value['Order']['order_type'];
                                    $getData[$key]['deliverytime']  = $value['Order']['delivery_time'];
                                    $getData[$key]['deriverId']     = ($value['Driver']['id']) ? $value['Driver']['id'] : '' ;
                                    $getData[$key]['deriverName']   = ($value['Driver']['driver_name']) ? $value['Driver']['driver_name'] : '';
                                }
                                 $response['acceptList'] = $getData;
                                 $response['success'] = '1';
                                 $response['message'] = 'Successfully';
                            } else {
                                $response['success'] = '0';
                                $response['message'] = 'No Record Found';
                            }
                        } else {
                            $response['success'] = '0';
                            $response['message'] = 'No Record Found';
                        }
                    } else {
                        $response['success'] = '0';
                        $response['message'] = 'No Record Found';
                    }
                break;

                case 'orderStatusChange':           
                    $order_id   = $this->request->data['order_id'];
                    $orderStatus = $this->request->data['status'];

					$orderDetail = $this->Order->findById($order_id);
                    if ($order_id != '') {
                        $this->request->data['Order']['id']      = $order_id;                       
                        if($orderStatus == 'Accept') {
                            $orderStatus = 'Accepted';
                            $this->request->data['Order']['id']   = $order_id;  
                            $this->request->data['Order']['status']  = 'Accepted';
                            $this->Order->save($this->request->data['Order']);

                        } elseif($orderStatus == 'Delivered'){
                            $this->request->data['Order']['id']    = $order_id;
                            $this->request->data['Order']['status']  = 'Delivered';
                            $this->request->data['Order']['payment_method']  = 'paid';
                            $this->Order->save($this->request->data['Order']);

                        }else {
                            $this->request->data['Order']['id']      = $order_id;  
                            $this->request->data['Order']['status']  = 'Failed';
                            $this->request->data['Order']['failed_reason']  = $this->request->data['cancel_reason'] ;
                            $this->Order->save($this->request->data['Order']);
                        }

                        
						//Cuctomer SMS
                        /*$customerMessage  = 'Congratulations! Your order '.$orderDetail['Order']['ref_number'].' succesfully accepted by '.
						$orderDetail['Store']['store_name'].'. Your order will be delivered by '.
						$orderDetail['Order']['delivery_date']. ' at '.$orderDetail['Order']['delivery_time'].'. Thanks '.$this->siteSetting['Sitesetting']['site_name'];
                        $toCustomerNumber = '+'.$this->siteSetting['Country']['phone_code'].$orderDetail['Customer']['customer_phone'];
                        $customerSms      = $this->Twilio->sendSingleSms($toCustomerNumber, $customerMessage);*/
                        $response['success'] = '1';
                        $response['message'] = 'Status has been Changed';
                    } else {
                        $response['success'] = '0';
                    }
                break;

                case 'assignOrder':

                    $orderId   = $this->request->data['order_id'];
                    $driverId  = $this->request->data['driver_id'];
                    $orders    = $this->Order->findById($orderId);

                    if ($orderId != '' && $driverId != '') {

                        $driverDetails = $this->Driver->find('first',array(
                                    'conditions' => array('Driver.id' => $driverId,
                                                        'User.role_id'=>'5')));

                        if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
                            if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {

                                $storeAddress = $orders['Store']['street_address'] . ', ' .
                                    $this->storeCity[$orders['Store']['store_city']] . ', ' .
                                    $this->storeState[$orders['Store']['store_state']] . ' ' .
                                    $this->storeLocation[$orders['Store']['store_zip']] . ', ' .
                                    $this->siteSetting['Country']['country_name'];


                            } else {
                                $storeAddress = $orders['Store']['street_address'] . ', ' .
                                    $this->storeLocation[$orders['Store']['store_zip']] . ', ' .
                                    $this->storeCity[$orders['Store']['store_city']] . ', ' .
                                    $this->storeState[$orders['Store']['store_state']] . ', ' .
                                    $this->siteSetting['Country']['country_name'];
                            }
                        } else {
                            $storeAddress = $orders['Store']['address'];
                        }


                        $orderDetails['paymentType']            = $orders['Order']['payment_type'];
                        $orderDetails['CustomerName']           = $orders['Order']['customer_name'];

                        $orderDetails['StoreName']              = stripslashes($orders['Store']['store_name']);
                        $orderDetails['SourceAddress']          = $storeAddress;
                        $orderDetails['SourceLatitude']         = $orders['Order']['source_latitude'];
                        $orderDetails['SourceLongitude']        = $orders['Order']['source_longitude'];
                        $orderDetails['DestinationAddress']     =
                            ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') ?
                                $orders['Order']['address'].', '.
                                $orders['Order']['location_name'].', '.
                                $orders['Order']['city_name'].', '.
                                $orders['Order']['state_name'] : $orders['Order']['google_address'];

                        $orderDetails['LandMark']               = $orders['Order']['landmark'];
                        $orderDetails['DestinationLatitude']    = $orders['Order']['destination_latitude'];
                        $orderDetails['DestinationLongitude']   = $orders['Order']['destination_longitude'];
                        $orderDetails['OrderDate']              = $orders['Order']['delivery_date'];
                        $orderDetails['OrderTime']              = $orders['Order']['delivery_time_slot'];
                        $orderDetails['OrderPrice']             = $orders['Order']['order_grand_total'];
                        $orderDetails['OrderId']                = $orders['Order']['id'];
                        $orderDetails['OrderGenerateId']        = $orders['Order']['ref_number'];
                        
                        $distance                               = $this->Googlemap->getDrivingDistance(
                                                                    $orderDetails['SourceLatitude'],
                                                                    $orderDetails['SourceLongitude'],
                                                                    $orderDetails['DestinationLatitude'],
                                                                    $orderDetails['DestinationLongitude']);
                        $orderDetails['Distance']               = $distance['distanceText'];

                        $count  = $this->Order->find('count', array(
                                    'conditions' => array(
                                        'Order.driver_id' => $driverId,
                                        'Order.status' => 'Waiting')));
                                    
                        $orderDetails['waitingCount'] = $count + 1; 


                        if (!empty($driverDetails)) {

                            $deviceId      = $driverDetails['Driver']['device_id'];
                            $ordDetails = json_encode($orderDetails);

                            $message    = 'Une nouvelle commande a été passée - '.$orders['Order']['ref_number'];
                            $driverMessage = array('OrderDetails' => $ordDetails,'message'    => $message);
                            $gcm    = (trim($driverDetails['Driver']['device_name']) == 'ANDROID') ?
                                                $this->AndroidResponse->sendOrderByGCM($driverMessage, $deviceId) :
                                                $this->PushNotifications->notificationIOS($message, $deviceId);
                            

                            $orderStatus['id']          = $orderId;
                            $orderStatus['status']      = 'Waiting';
                            $orderStatus['driver_id']   = $driverId;
                            
                            $this->Order->save($orderStatus);
                                          
                            $gcm = json_decode($gcm, true);
			    			// Driver SMS
                            $driverMessage  = 'Dear '.$driverDetails['Driver']['driver_name'].',pick up ';
                            $driverMessage .= ($orders['Order']['payment_method'] != 'paid') ? 'COD' : 'PAID';
                            $driverMessage .= ' order '.$orders['Order']['ref_number'].' from '.$orders['Order']['customer_name'];
                            $driverMessage .=   ','.$orders['Order']['address'].','.$orders['Order']['landmark'].
                                                ','.$orders['Order']['location_name'].','.$orders['Order']['city_name'].
                                                ','.$orders['Order']['city_name'];

                            $driverMessage .= '. '.$orders['Order']['order_type'].' due on '.$orders['Order']['delivery_date'].' at '.
                                                $orders['Order']['delivery_time_slot'].'. Thanks '.$this->siteSetting['Sitesetting']['site_name'];
                            $toDriverNumber = '+'.$this->siteSetting['Country']['phone_code'].$driverDetails['Driver']['driver_phone'];
                            // $driverSms      = $this->Twilio->sendSingleSms($toDriverNumber, $driverMessage);
                        }

                        $response['success'] = '1';
                        $response['message'] = 'order has been assigned';
                    } else {
                        $response['success'] = '0';
                    }
                break;

                case 'orderView':
                    $order_id   = $this->request->data['order_id'];
                    $orderInfo  = array();
                    if ($order_id != '') {
                        $order_detail           = $this->Order->findByRefNumber($order_id);
                        $distance = $this->Googlemap->getDrivingDistance(
                                                $order_detail['Order']['source_latitude'],
                                                $order_detail['Order']['source_longitude'],
                                                $order_detail['Order']['destination_latitude'],
                                                $order_detail['Order']['destination_longitude']);
                        if(!empty($order_detail)) {
                            foreach($order_detail['ShoppingCart'] as $key => $value) {
                                $order[$key]['productname'] = $value['product_name'];
                                $order[$key]['quantity'] = $value['product_quantity'];
                                $order[$key]['total'] = $value['product_total_price'];
                                $order[$key]['product_descrption'] = $value['product_description'];
                            }
                                $orderInfo['tax']            = $order_detail['Order']['tax_amount'];
                                $orderInfo['sub_tot']        = $order_detail['Order']['order_sub_total'];
                                $orderInfo['delivery']       = $order_detail['Order']['delivery_charge'];
                                $orderInfo['grand_tot']      = $order_detail['Order']['order_grand_total'];
                                $orderInfo['name']           = $order_detail['Order']['order_grand_total'];
                                $orderInfo['id']             = $order_detail['Order']['id'];
                                $orderInfo['ref_number']     = $order_detail['Order']['ref_number'];
                                $orderInfo['paymentStatus']  = $order_detail['Order']['payment_method'];
                                $orderInfo['orderType']      = $order_detail['Order']['order_type'];
                                $orderInfo['phone']          = $order_detail['Order']['customer_phone'];
                                $orderInfo['name']           = $order_detail['Order']['customer_name'];
                                $orderInfo['mail']           = $order_detail['Order']['customer_email'];
                                $orderInfo['address']        =
                                    ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') ?
                                        $order_detail['Order']['address'].','.
                                        $order_detail['Order']['location_name'].','.
                                        $order_detail['Order']['city_name'].','.
                                        $order_detail['Order']['state_name'] :
                                        $order_detail['Order']['google_address'];
                                $orderInfo['orderdescrption']   = $order_detail['Order']['order_description'];
                                $orderInfo['DeliveryTime']      = $order_detail['Order']['delivery_time'];
                                $orderInfo['Distancevalue']     = $distance['distanceText'];
                                $orderInfo['payment_type']      = $order_detail['Order']['payment_type'];
                                $orderInfo['offer']             = $order_detail['Order']['offer_amount'];
                                $orderInfo['offerPercentage']   = $order_detail['Order']['offer_percentage'];
                                $orderInfo['taxPercentage']     = $order_detail['Order']['tax_percentage'];
                                $orderInfo['voucherPercentage'] = $order_detail['Order']['voucher_percentage'];
                                $orderInfo['voucherAmount']     = $order_detail['Order']['voucher_amount'];
                                $orderInfo['tipAmount']         = $order_detail['Order']['tip_amount'];
                               
                               $orderInfo['productdetail'] = $order;
                               $response['success'] = '1';
                               $response['orderdetail'][] = $orderInfo;
                        } else {
                            $response['success'] = '0';
                            $response['message'] = 'No Records Found';

                        }
                    } else {
                        $response['success'] = '0';
                        $response['message'] = 'No Records Found';
                    }
                break;

                case 'completedOrderFillter':
                    $range = array('ThisWeek','ThisMonth','ThisYear','Today');
                    $this->set('range',$range);
                    $currentDate = date("Y-m-d 23:59:59");
                    $ts = strtotime($currentDate);
                    $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
                    $start_date = date('Y-m-d', $start);
                    $end_date = date('Y-m-d', strtotime('next saturday', $start));
                    $Today= date("Y-m-d");
                    $yesterdayDate = date("Y-m-d",  strtotime("-1 days"));
                    $thismonth        = date('Y-m-d 00:00:01', strtotime("first day of this month"));
                    $lastmonth_start = date('Y-m-d 00:00:01', strtotime("-1 months first day of this month"));
                    $lastmonth_end    = date('Y-m-d 23:59:59', strtotime("-1 months last day of this month"));
                    $thisyear = date('Y')."-01-01 00:00:01";
                    $lastyear = date('Y', strtotime("-1 year"));
                    $lastyearStart=$lastyear."-01-01 00:00:01";
                    $lastyearEnd=$lastyear."-12-31 23:59:59"; 
                    $from_date = $this->request->data['from'];
                    $to_date = $this->request->data['to']; 
                    $store_id                = $this->request->data['user_id'];
                    if ($store_id != '') {
                        $today    = new DateTime(date('Y-m-d G:i:s'));      
                        $first_day_this_month = date('m-01-Y');
                        $last_day_this_month  = date('m-t-Y');         
                        $store = $this->Store->findById($store_id);
                        if($this->request->data['sortby'] == $range[0]) {
                            $this->Order->recursive = 0;
                            $order_list = $this->Order->find('all', array(
                                                        'conditions' => array('Order.store_id'=>$store_id,
                                                        'Order.delivery_date between ? and ?' =>
                                                        array($start_date, $currentDate),
                                                        'Order.status' => 'Delivered'),
                                                        'order' => array('Order.id DESC')));
                            if (!empty($order_list)) {
                                 $this->Order->recursive = 0;
                                foreach ($order_list as $key => $value) {
                                    $getData[$key]['id'] = $value['Order']['id'];
                                    $getData[$key]['order_id'] = $value['Order']['ref_number'];
                                    $getData[$key]['delivered_date'] = $value['Order']['delivery_date'];
                                    $getData[$key]['total'] = $value['Order']['order_grand_total'];
                                }                                    
                                    $response['orderDetail'] = $getData;
                                    $response['success'] = '1';
                            } else {
                                $response['success'] = '0';
                                $response['message'] = 'No Record Found';
                            } 
                            
                        } else if ($this->request->data['sortby'] == $range[1]) {
                            $this->Order->recursive = 0;
                            $order_list = $this->Order->find('all', array(
                                                'conditions' => array('Order.store_id'=>$store_id,
                                                'Order.delivery_date between ? and ?' =>
                                                array($thismonth, $currentDate),                                            
                                                'Order.status' => 'Delivered'),
                                                'order' => array('Order.id DESC')));
                            if (!empty($order_list)) {
                                 $this->Order->recursive = 0;
                                foreach ($order_list as $key => $value) {
                                    $getData[$key]['id'] = $value['Order']['id'];
                                    $getData[$key]['order_id'] = $value['Order']['ref_number'];
                                    $getData[$key]['delivered_date'] = $value['Order']['delivery_date'];
                                    $getData[$key]['total'] = $value['Order']['order_grand_total'];
                                }                                    
                                    $response['orderDetail'] = $getData;
                                    $response['success'] = '1';
                            } else {
                                $response['success'] = '0';
                                $response['message'] = 'No Record Found';
                            }                 
                        } else if($this->request->data['sortby'] == $range[2]){
                            $this->Order->recursive = 0;
                            $order_list = $this->Order->find('all', array(
                                                'conditions' => array('Order.store_id'=>$store_id,
                                                'Order.delivery_date between ? and ?' =>
                                                array($thisyear, $currentDate),                                            
                                                'Order.status' => 'Delivered'),
                                                'order' => array('Order.id DESC')));
                            if (!empty($order_list)) {
                                $this->Order->recursive = 0;
                                foreach ($order_list as $key => $value) {
                                    $getData[$key]['id'] = $value['Order']['id'];
                                    $getData[$key]['order_id'] = $value['Order']['ref_number'];
                                    $getData[$key]['delivered_date'] = $value['Order']['delivery_date'];
                                    $getData[$key]['total'] = $value['Order']['order_grand_total'];
                                }                                   
                                    $response['orderDetail'] = $getData;
                                    $response['success'] = '1';
                            } else {
                                $response['success'] = '0';
                                $response['message'] = 'No Record Found';
                            }  
                        } else if($this->request->data['sortby'] == $range[3]){
                            if(!empty($store)) {
                                $this->Order->recursive = 0;
                                $order_list = $this->Order->find('all', array(
                                                'conditions' => array('Order.store_id'=>$store_id,
                                                'Order.delivery_date' => date('Y-m-d', time()),                                            
                                                'Order.status' => 'Delivered'),
                                                'order' => array('Order.id DESC')));
                              
                                if (!empty($order_list)) {
                                    $this->Order->recursive = 0;
                                    foreach ($order_list as $key => $value) {
                                        $getData[$key]['id'] = $value['Order']['id'];
                                        $getData[$key]['order_id'] = $value['Order']['ref_number'];
                                        $getData[$key]['delivered_date'] = $value['Order']['delivery_date'];
                                        $getData[$key]['total'] = $value['Order']['order_grand_total'];
                                    }
                                    $response['orderDetail'] = $getData;
                                    $response['success'] = '1';
                                } else {
                                    $response['success'] = '0';
                                    $response['message'] = 'No Record Found';
                                }
                            } else {
                                $response['success'] = '0';
                                $response['message'] = 'No Record Found';
                            }
                        } else {
                            $this->Order->recursive = 0;
                            $order_list = $this->Order->find('all', array(
                                                'conditions' => array('Order.store_id'=>$store_id,
                                                'Order.delivery_date between ? and ?' =>
                                                array($from_date, $to_date),                                            
                                                'Order.status' => 'Delivered'),
                                                'order' => array('Order.id DESC')));
                            if (!empty($order_list)) {
                                $this->Order->recursive = 0;
                                foreach ($order_list as $key => $value) {
                                    $getData[$key]['id'] = $value['Order']['id'];
                                    $getData[$key]['order_id'] = $value['Order']['ref_number'];
                                    $getData[$key]['delivered_date'] = $value['Order']['delivery_date'];
                                    $getData[$key]['total'] = $value['Order']['order_grand_total'];
                                }
                                $response['orderDetail'] = $getData;
                                $response['success'] = '1';
                            } else {
                                $response['success'] = '0';
                                $response['message'] = 'No Record Found';
                            }

                        }
                    } else {
                        $response['success'] = '0';
                        $response['message'] = 'No Record Found';
                    }
                break;

                case 'availableDriver':
                    $store_id     = $this->request->data['store_id'];
                    $orderDetails = $this->Order->findById($this->request->data['order_id']);
                    if ($orderDetails['Order']['driver_id']) {
                        $response['success'] = '0';
                        $response['message'] = 'Order already register';
                    } else {
                        $drivers = $this->Driver->find('all', array(
                                            'conditions' => array('Vehicle.vehicle_name !=' => '',
                                                                  'Driver.driver_status' => 'Available',
                                                                  'Driver.status' => 'Active',
                                                                  'Driver.store_id' => $store_id
                                                                ),
                                            'group' => array('Driver.id')));
                        $pickup['Latitude']  = $orderDetails['Store']['latitude'];
                        $pickup['Longitude'] = $orderDetails['Store']['longitude'];                        
                        if (!empty($drivers)) {
                            
                            foreach ($drivers as $key => $value) {
                                $distance = $this->Googlemap->getDrivingDistance(
                                                            $value['DriverTracking']['driver_latitude'],
                                                            $value['DriverTracking']['driver_longitude'],
                                                            $pickup['Latitude'],
                                                            $pickup['Longitude']);
                                //if (!empty($distance)) {

                                    $availDrivers[$key]['id']             = $value['Driver']['id'];
                                    $availDrivers[$key]['status']         = $value['Driver']['driver_status'];
                                    $availDrivers[$key]['distance']       = (!empty($distance)) ? 
                                                                            $distance['distanceText'] : 'Out of range';
                                    $availDrivers[$key]['reachtime']      = (!empty($distance)) ? 
                                                                            $distance['durationText'] : 'Out of range';
                                    $availDrivers[$key]['driver_name']    = $value['Driver']['driver_name'];
                                    $availDrivers[$key]['driver_phone']   = $value['Driver']['driver_phone'];
                                    $availDrivers[$key]['vehicle_name']   = $value['Vehicle']['vehicle_name'];

                                //}
                                
                            }

                            $distance = array();
                            foreach ($availDrivers as $key => $row) {
                                $distance[$key] = $row['distance'];
                            }
                            array_multisort($distance, SORT_ASC, $availDrivers);
                            
                            $response['driverDetail'] = ($availDrivers) ? $availDrivers : array();
                            $response['success'] = '1';

                        } else {
                            $response['success'] = '0';
                            $response['message'] = 'No Records Found';
                        }                       
                    }               
                break;

                case 'StoreLogout':
                    $store_id                = $this->request->data['user_id'];
                    if ($store_id != '') {
                        $store = $this->Store->findById($store_id);
                        if(!empty($store)) {
                            $this->request->data['Store']['id'] = $this->request->data['user_id'] ;
                            $this->request->data['Store']['is_logged'] = 0;
                            $this->request->data['Store']['device_id'] = '';
                            $this->Store->save($this->request->data['Store']);
                            $response['success'] = '1';
                            $response['message'] = 'logout Successfully';

                        }
                    } else {
                        $response['success'] = '0';
                        $response['message'] = 'Try Again..!';
                    }
                break;

                case 'changePassword':
                    $store_id     = $this->request->data['userid'];
                    $newpassword  = $this->request->data['newpassword'];
                    $oldPassword  = $this->Auth->password($this->request->data['oldpassword']);

                    if ($store_id != '') {

                        $store = $this->Store->findById($store_id);

                        if($oldPassword == $store['User']['password']) {
                            $store['User']['password'] = $this->Auth->password($newpassword);
                            if ($this->User->save($store)) {
                                $response['success'] = '1';
                                $response['message'] = 'Changed password successfully';
                            }
                        } else {
                            $response['success'] = '0';
                            $response['message'] = 'Old password not matched';
                        }
                    } else {
                        $response['success'] = '0';
                        $response['message'] = 'Try Again..!';
                    }
                break;
            }
        } else {
             $response['success'] = '0';
             $response['message'] = 'Incorrect username and password';


        }
         die(json_encode($response));
    }
   
} 