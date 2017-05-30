<?php
/* MN */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class MobileApiController extends AppController {
    public $components = array('AndroidResponse', 'Notifications', 'Functions', 'PushNotifications');
    public $uses       = array('User', 'Order', 'Driver', 'DriverTracking', 'Orderstatus', 'City');
    
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
        $response = array();
        if ($this->request->is('post')) {
            if (empty($this->request->data)) {
                $data = $this->request->input('json_decode', true);
                $this->request->data = $data;
            }
            switch (trim($this->request->data['action'])) {

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
                            $response['success']    = 0;
                            $response['message']    = 'Driver Already Loggedin';
                            break;
                        }
                        if ($getDriver['Driver']['status'] != 'Active') {
                            $response['success']    = 0;
                            $response['message']    = 'Your account deactivated';
                            break;
                        }
                        if ($getDriver['Vehicle']['id'] == '') {
                            $response['success']    = 0;
                            $response['message']    = 'Vehicle not registered';
                            break;
                        }
                        $getDriver['Driver']['id']             = $getDriver['Driver']['id'];
                        $getDriver['Driver']['device_id']      = $this->request->data['device_id'];
                        $getDriver['Driver']['is_logged']      = 1;
                        $getDriver['Driver']['device_name']    = strtoupper($this->request->data['device_name']);
                        $getDriver['Driver']['driver_status']  = 'Available';

                        $drive = $this->Driver->save($getDriver);

                        if ($drive['User']['role_id'] == '5' && $drive['Driver']['is_logged'] == 1) {

                            $response['success']         = '1';
                            $response['driverid']        = $getDriver['Driver']['id'];
                            $response['driverName']      = $getDriver['Driver']['driver_name'];
                            $response['currency']        = $this->siteSetting['Country']['currency_symbol'];

                            $driverImage = (!empty($driver['Driver']['image'])) 
                                            ? $this->siteUrl.'/driversImage/'.$driver['Driver']['image'] 
                                            : $this->siteUrl.'/driversImage/no-photo.png';

                            $response['driverImage']     = $driverImage;
                            $response['driverStatus']    = 'Available';
                            $response['message'] = 'login successfully';
                            
                            $message = 'Driver : '.$getDriver['Driver']['driver_name']." loggedin";
                            $this->Notifications->pushNotification($message, 'FoodOrderAdmin');
                            $this->Notifications->pushNotification($message, 'Restaurant_'.$drive['Driver']['store_id']);
                            
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

                        if($driver['Driver']['device_name'] != 'ANDROID') {
                            $imageSrc = str_replace(" ","+",$base);
                        } else {
                            $base = explode('\n', $base);
                            $imageSrc = '';
                            foreach ($base as $key => $value) {
                                $imageSrc .= stripslashes($value);
                            }
                        }

                        /*$filePath      = ROOT.DS.'app'.DS."tmp".DS.'mobilePhoto.txt';
                        $file = fopen($filePath,"a+");
                        fwrite($file, PHP_EOL.'Driver Image---->'.$base.PHP_EOL);
                        fclose($file);*/
                        
                        // Get file name posted from Android App
                        $fileId = $driver['Driver']['id'].time().'.png';
                        $filename = APP.'webroot/driversImage/'.$fileId;
                        // Decode Image
                        $binary = base64_decode(trim($imageSrc));
                        header('Content-Type: bitmap; charset=utf-8');
                        
                        $file = fopen($filename, 'wb+');
                        // Create File
                        fwrite($file, $binary);
                        fclose($file);
                        #Save Driver Image
                        
                        $driverImage['Driver']['id']    = $driver['Driver']['id'];
                        $driverImage['Driver']['image'] = $fileId;
                        $this->Driver->save($driverImage);
                        
                        $response['success'] = 1;
                        $response['message'] = 'Image uploaded successfully!';
                        $response['driverImage'] = $this->siteUrl.'/driversImage/'.$fileId;
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'Image not upload!';
                    }
                break;
                    
                case 'DriverDetails':
                    
                    $driverId = $this->request->data['driverid'];
                    $driver   = $this->Driver->findById($driverId);
                                            
                    if (is_array($driver) && $driver['User']['role_id'] == 5) {

                        $driverImage = (!empty($driver['Driver']['image'])) 
                                            ? $this->siteUrl.'/driversImage/'.$driver['Driver']['image'] 
                                            : $this->siteUrl.'/driversImage/no-photo.png';

                        $response['success']        = 1;
                        $response['DriverName']     = $driver['Driver']['driver_name'];
                        $response['DriverMail']     = $driver['Driver']['driver_email'];
                        $response['DriverMobile']   = $driver['Driver']['driver_phone'];
                        $response['driverImage']   = $driverImage;

                    } else {
                        $response['success']        = 0;
                        $response['message']        = 'Unknown driver';
                    }
                break;

                case 'DriverUpdate':
                    $driver['Driver']['id']             = $this->request->data['driverid'];
                    $driver['Driver']['driver_name']    = $this->request->data['driverName'];
                    $driver['Driver']['driver_email']   = $this->request->data['driverMail'];
                    $driver['Driver']['driver_phone']   = $this->request->data['driverMobile'];
                    
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
                    
                    $latitude  = $this->request->data('latitude');
                    $longitude = $this->request->data('longitude');
                    
                    $driverId  = $this->request->data['driverid'];
                    
                    if ($this->request->data['driverid']) {

                        $driverTrack = $this->DriverTracking->findByDriverId($driverId);

                        $tracking['id']        = ($driverTrack['DriverTracking']['id'] != '') ? $driverTrack['DriverTracking']['id'] : '';
                        $tracking['driver_id'] = $driverId;
                        $tracking['driver_latitude']  = $latitude;
                        $tracking['driver_longitude'] = $longitude;
                        
                        $trackResult = $this->DriverTracking->save($tracking);

                        $response['success'] = ($trackResult['DriverTracking']['id'] != '') ? 1 : 0;
                    } else {
                        $response['success'] = 0;
                    }
                break;
                    
                case 'DriverStatus':
                
                    $driverId  = $this->request->data('driverid');
                    $status    = $this->request->data('status');
                    $driver    = $this->Driver->findById($driverId);

                    if (!empty($driver)) {

                        $driver['Driver']['driver_status'] = $status;

                        $this->Driver->save($driver);

                        $response['success'] = 1;
                        $response['message'] = 'Status changed';

                        if (strtolower($status) == 'end of shift') {
                            $message = $status.' for '.$driver['Driver']['driver_name'];
                        } elseif (strtolower($status) == 'on break') {
                            $message = $driver['Driver']['driver_name']." is On a break";
                        } else {
                            $message = $driver['Driver']['driver_name']." is ".$status;
                        }

                        $this->Notifications->pushNotification($message, 'FoodOrderAdmin');
                        $this->Notifications->pushNotification($message, 'Restaurant_'.$driver['Driver']['store_id']);
                        
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'Status is not change';
                    }
                break;
                    
                case 'OrderStatus':
                
                    $driverId  = $this->request->data('driverid');
                    $orderId   = $this->request->data('orderid');
                    $status    = (strtolower(trim($this->request->data('status'))) == 'reject') 
                                ? 'Accepted' : $this->request->data('status');
                    $latitude  = $this->request->data('latitude');
                    $longitude = $this->request->data('longitude');
                    
                    if ($driverId == '' || $orderId == '' || $status == '') {
                        $response['success'] = 0;
                        $response['message'] = 'Missing arugument';
                    }

                    $ordStatus = $this->Order->findById($orderId);
                    
                    $orders['Orderstatus']['id']                = '';
                    $orders['Orderstatus']['status']            = $status;
                    $orders['Orderstatus']['order_id']          = $orderId;
                    $orders['Orderstatus']['driver_id']         = $driverId;
                    $orders['Orderstatus']['driver_latitude']   = $latitude;
                    $orders['Orderstatus']['driver_longitude']  = $longitude;
                    

                    if ($status != 'Accepted') {
                        $this->Orderstatus->save($orders);
                    } else {
                        $this->Orderstatus->deleteAll(array('Orderstatus.order_id'=>$orderId));
                    }
                    
                    $track     = $this->DriverTracking->findByDriverId($driverId);
                    
                    if (!empty($track)) {
                        $track['DriverTracking']['order_id'] = ($status != 'Delivered') ? $orderId : '';
                        $this->DriverTracking->save($track);
                    }
                    if (!empty($ordStatus)) {
                        $ordStatus['Order']['driver_id'] = ($status == 'Accepted') ? '' : $driverId;
                        $ordStatus['Order']['status']    = $status;
                        //Driver's Offer Price
                        if (!empty($this->request->data['driverOffer'])) {
                            $ordStatus['Order']['driver_offer'] = $this->request->data['driverOffer'];
                        }

                        if (!empty($this->request->data['image']) && $status == 'Delivered') {
                            // Get image string posted from Android App
                            $base = $this->request->data['image'];

                            if($ordStatus['Driver']['device_name'] != 'ANDROID') {
                                $imageSrc = str_replace(" ","+",$base);
                            } else {
                                $base = explode('\n', $base);
                                $imageSrc = '';
                                foreach ($base as $key => $value) {
                                    $imageSrc .= stripslashes($value);
                                }
                            }

                            // Get file name posted from Android App
                            $fileId = 'Order_signature'.$orderId.'.png';
                            $filename = APP.'webroot/OrderProof/'.$fileId;
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
                        $response['message'] = 'Order Status Change Successfully';
                        
                        $driverDetail = $this->Driver->findById($driverId);

                        $status = ($status == 'Collected') ? 'Picked up' : $status;
                        
                        //Push Notification
                        $message = ($status == 'Accepted') ? 
                            $ordStatus['Order']['ref_number'].' is rejected by '.$driverDetail['Driver']['driver_name'] :
                            $ordStatus['Order']['ref_number']." - Order status changed to ".$status;
                        
                        $this->Notifications->pushNotification($message, 'FoodOrderAdmin');
                        $this->Notifications->pushNotification($message, 'Restaurant_'.$ordStatus['Order']['store_id']);
                        $customerMessage = $ordStatus['Order']['ref_number'].' a été '. $status;
                            $this->Notifications->pushNotification($customerMessage, 'FoodCustomer_'.$ordStatus['Order']['customer_id']);

                        // Store Owner App Message
                        if ($ordStatus['Store']['is_logged'] == 1) {
                          $deviceId = $ordStatus['Store']['device_id'];
                          $gcm      = $this->AndroidResponse->sendOrderByGCM(
                                                    array('message' => $message),$deviceId);

                        }
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'Order Status Change Failed';
                        
                    }
                break;
                    
                case 'CompletedOrders':
                    
                    $status   = 'Delivered';

                    $driverId = $this->request->data('driverid');
                    $date     = $this->request->data('date');
                    $deliverDate  = date('Y-m-d', strtotime($this->request->data('date'))); 
                    
                    $orderCondition = array(
                                        'conditions' => array(
                                            'Order.driver_id' => $driverId,
                                            'Order.status' => $status),
                                        'order' => array('Order.id' => 'DESC'));

                    if ($deliverDate != '1970-01-01') {
                        $orderCondition['conditions']['Order.updated LIKE'] = $deliverDate.'%';  
                    }  
                                
                    $order    = $this->Order->find('all', $orderCondition);
                    
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


                            $orderDetails[$key]['StoreName']              = stripslashes($value['Store']['store_name']);
                            $orderDetails[$key]['SourceAddress']          = $storeAddress;
                            $orderDetails[$key]['SourceLatitude']         = $value['Order']['source_latitude'];
                            $orderDetails[$key]['SourceLongitude']        = $value['Order']['source_longitude'];
                            $orderDetails[$key]['DestinationAddress']     =
                                ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') ?
                                                                            $value['Order']['address'].', '.
                                                                            $value['Order']['city_name'].', '.
                                                                            $value['Order']['location_name'].', '.
                                                                            $this->siteSetting['Country']['country_name'] : 
                                                                            $value['Order']['google_address'];
                            $orderDetails[$key]['LandMark']               = $value['Order']['landmark'];
                            $orderDetails[$key]['DestinationLatitude']    = $value['Order']['destination_latitude'];
                            $orderDetails[$key]['DestinationLongitude']   = $value['Order']['destination_longitude'];
                            $orderDetails[$key]['OrderDate']              = $value['Order']['delivery_date'];
                            $orderDetails[$key]['OrderTime']              =
                                ($value['Order']['assoonas'] == 'Later') ? $value['Order']['delivery_time'] : 'ASAP';
                            $orderDetails[$key]['OrderPrice']             = $value['Order']['order_grand_total'];
                            $orderDetails[$key]['OrderId']                = $value['Order']['id'];
                            $orderDetails[$key]['OrderGenerateId']        = $value['Order']['ref_number'];
                            $orderDetails[$key]['OrderStatus']            = $value['Order']['status'];
                            $orderDetails[$key]['CustomerName']           = $value['Order']['customer_name'];
                            $orderDetails[$key]['PaymentType']            = $value['Order']['payment_type'];
                        }
                        
                        $response['success'] = 1;
                        $response['orders']  = $orderDetails;
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'No record(s) found';
                    }
                break;
                    
                case 'DriverAcceptedOrders':
                
                    $status   = array('Driver Accepted', 'Collected');

                    $driverId = $this->request->data('driverid');
                    $order    = $this->Order->find('all', array(
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

                            $orderDetails[$key]['StoreName']              = stripslashes($value['Store']['store_name']);
                            $orderDetails[$key]['SourceAddress']          = $storeAddress;
                            $orderDetails[$key]['SourceLatitude']         = $value['Order']['source_latitude'];
                            $orderDetails[$key]['SourceLongitude']        = $value['Order']['source_longitude'];
                            $orderDetails[$key]['DestinationAddress']     =
                                ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') ?
                                                                            $value['Order']['address'].', '.
                                                                            $value['Order']['city_name'].', '.
                                                                            $value['Order']['location_name'].', '.
                                                                            $this->siteSetting['Country']['country_name'] : 
                                                                            $value['Order']['google_address'];
                            $orderDetails[$key]['LandMark']               = $value['Order']['landmark'];
                            $orderDetails[$key]['DestinationLatitude']    = $value['Order']['destination_latitude'];
                            $orderDetails[$key]['DestinationLongitude']   = $value['Order']['destination_longitude'];
                            $orderDetails[$key]['OrderDate']              = $value['Order']['delivery_date'];
                            $orderDetails[$key]['OrderTime']              =
                                ($value['Order']['assoonas'] == 'Later') ? $value['Order']['delivery_time'] : 'ASAP';
                            $orderDetails[$key]['OrderPrice']             = $value['Order']['order_grand_total'];
                            $orderDetails[$key]['OrderId']                = $value['Order']['id'];
                            $orderDetails[$key]['OrderGenerateId']        = $value['Order']['ref_number'];
                            $orderDetails[$key]['OrderStatus']            = $value['Order']['status'];
                            $orderDetails[$key]['CustomerName']           = $value['Order']['customer_name'];
                            $orderDetails[$key]['PaymentType']            = $value['Order']['payment_type'];
                        }
                        $response['success'] = 1;
                        $response['orders']  = $orderDetails;
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'No record(s) found';
                    }
                break;

                case 'WaitingOrderCount':
                    $status      = 'Waiting';

                    $driverId    = $this->request->data('driverid');
                    if ($driverId != '') {
                        $count       = $this->Order->find('count', array(
                                            'conditions' => array(
                                                'Order.driver_id' => $driverId,
                                                'Order.status' => $status)));
                        $response['success'] = ($count > 0) ? 1 : 0;
                        $response['waitingCount']   = $count;
                        if ($count == 0)
                            $response['message'] = 'No record(s) found';
                        break;
                    } else { 
                        $response['success'] = 0;
                        $response['message'] = 'Invalid driver';
                    }
                break;

                case 'WaitingOrders':
                
                    $status   = 'Waiting';

                    $driverId = $this->request->data('driverid');
                    $order    = $this->Order->find('all', array(
                                            'conditions' => array(
                                                        'Order.driver_id' => $driverId,
                                                        'Order.status' => $status),
                                            'order' => 'Order.id Desc'));

                    if (!empty($order)) {
                        $orderDetails = array();
                        foreach ($order as $key => $value) {

                            $datetime1 = new DateTime(date('Y-m-d G:i:s'));
                            $datetime2 = new DateTime($value['Order']['updated']);
                            $interval  = $datetime1->diff($datetime2);
                            $hour      = $interval->format('%H');
                            $min       = $interval->format('%I');
                            $sec       = $interval->format('%S');
                            $day       = $interval->format('%D');

                            if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
                                $storeAddress = $value['Store']['street_address'] . ', ' .
                                                  $this->storeCity[$value['Store']['store_city']] . ', ' .
                                                  $value['Store']['post_code'] . ', ' .
                                                  $this->siteSetting['Country']['country_name'];
                            } else {
                                $storeAddress = $value['Store']['address'];
                            }


                            $orderDetails[$key]['StoreName']              = stripslashes($value['Store']['store_name']);
                            $orderDetails[$key]['SourceAddress']          = $storeAddress;
                            $orderDetails[$key]['SourceLatitude']         = $value['Order']['source_latitude'];
                            $orderDetails[$key]['SourceLongitude']        = $value['Order']['source_longitude'];
                            $orderDetails[$key]['DestinationAddress']     =
                                ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') ?
                                                                            $value['Order']['address'].', '.
                                                                            $value['Order']['city_name'].', '.
                                                                            $value['Order']['location_name'].', '.
                                                                            $this->siteSetting['Country']['country_name'] : 
                                                                            $value['Order']['google_address'];
                            $orderDetails[$key]['LandMark']               = $value['Order']['landmark'];
                            $orderDetails[$key]['DestinationLatitude']    = $value['Order']['destination_latitude'];
                            $orderDetails[$key]['DestinationLongitude']   = $value['Order']['destination_longitude'];
                            $orderDetails[$key]['OrderDate']              = $value['Order']['delivery_date'];
                            $orderDetails[$key]['OrderTime']              =
                                ($value['Order']['assoonas'] == 'Later') ?
                                    $value['Order']['delivery_time'] : 'ASAP';
                            $orderDetails[$key]['OrderPrice']             = $value['Order']['order_grand_total'];
                            $orderDetails[$key]['OrderId']                = $value['Order']['id'];
                            $orderDetails[$key]['OrderGenerateId']        = $value['Order']['ref_number'];
                            $orderDetails[$key]['OrderStatus']            = $value['Order']['status'];
                            $orderDetails[$key]['CustomerName']           = $value['Order']['customer_name'];
                            $orderDetails[$key]['PaymentType']            = $value['Order']['payment_type'];
                            $orderDetails[$key]['Day']                    = $day;
                            $orderDetails[$key]['Hour']                   = $hour;
                            $orderDetails[$key]['Min']                    = $min;
                            $orderDetails[$key]['Sec']                    = $sec;
                        }
                        $response['success'] = 1;
                        $response['orders']  = $orderDetails;
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'No record(s) found';
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


                        $orderDet['orderId']                = $orderDetails['Order']['ref_number'];
                        $orderDet['customerName']           = stripslashes($orderDetails['Order']['customer_name']);
                        $orderDet['customerAddress'] = ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') ?
                                                                $orderDetails['Order']['address'].', '.
                                                                $orderDetails['Order']['city_name'].', '.
                                                                $orderDetails['Order']['location_name'].', '.
                                                                $this->siteSetting['Country']['country_name'] : 
                                                                $orderDetails['Order']['google_address'];


                        $orderDet['tax']                    = $orderDetails['Order']['tax_amount'];
                        $orderDet['offer']                  = $orderDetails['Order']['offer_amount'];
                        $orderDet['total']                  = $orderDetails['Order']['order_grand_total'];
                        $orderDet['status']                 = $orderDetails['Order']['status'];
                        $orderDet['OrderId']                = $orderDetails['Order']['id'];
                        $orderDet['subTotal']               = $orderDetails['Order']['order_sub_total'];
                        $orderDet['LandMark']               = $orderDetails['Order']['landmark'];
                        $orderDet['OrderDate']              = $orderDetails['Order']['delivery_date'];
                        $orderDet['OrderTime']              = $orderDetails['Order']['delivery_time'];
                        $orderDet['tipAmount']              = $orderDetails['Order']['tip_amount'];
                        $orderDet['orderMenu']              = stripslashes_deep($orderDetails['ShoppingCart']);
                        $orderDet['StoreName']              = stripslashes($orderDetails['Store']['store_name']);
                        $orderDet['OrderPrice']             = $orderDetails['Order']['order_grand_total'];
                        $orderDet['PaymentType']            = $orderDetails['Order']['payment_type'];
                        $orderDet['CustomerName']           = $orderDetails['Order']['customer_name'];
                        $orderDet['taxPercentage']          = $orderDetails['Order']['tax_percentage'];
                        $orderDet['customerEmail']          = $orderDetails['Order']['customer_email'];
                        $orderDet['customerPhone']          = $orderDetails['Order']['customer_phone'];
                        $orderDet['SourceAddress']          = $storeAddress;
                        $orderDet['voucherAmount']          = $orderDetails['Order']['voucher_amount'];
                        $orderDet['deliveryCharge']         = $orderDetails['Order']['delivery_charge'];
                        $orderDet['SourceLatitude']         = $orderDetails['Order']['source_latitude'];
                        $orderDet['OrderGenerateId']        = $orderDetails['Order']['ref_number'];
                        $orderDet['offerPercentage']        = $orderDetails['Order']['offer_percentage'];
                        $orderDet['SourceLongitude']        = $orderDetails['Order']['source_longitude'];
                        $orderDet['voucherPercentage']      = $orderDetails['Order']['voucher_percentage'];
                        $orderDet['DestinationLatitude']    = $orderDetails['Order']['destination_latitude'];
                        $orderDet['DestinationLongitude']   = $orderDetails['Order']['destination_longitude'];
                        $response = $orderDet;
                    } else {
                        $response['success'] = '0';
                        $response['message'] = 'There is no order(s)!';
                    }
                break;
                
                case 'OrderDisclaim':
                
                    $orderId    = $this->request->data['orderid'];
                    $driverId   = $this->request->data['driverid'];
                    $latitude   = $this->request->data('latitude');
                    $longitude  = $this->request->data('longitude');

                    $orderDetails = $this->Order->findById($orderId);

                    //Push Notification
                    $message = $orderDetails['Order']['ref_number'].' is rejected by '.$orderDetails['Driver']['driver_name'];
                    $this->Notifications->pushNotification($message, 'FoodOrderAdmin');
                    $this->Notifications->pushNotification($message, 'Restaurant_'.$orderDetails['Order']['store_id']);

                    
                    $order['id']        = $orderId;
                    $order['status']    = 'Accepted';
                    $order['driver_id'] = 0;
                    
                    $this->Order->save($order);
                    
                    $orderStatus['Orderstatus']['id']         = '';
                    $orderStatus['Orderstatus']['order_id']   = $orderId;
                    $orderStatus['Orderstatus']['driver_id']  = $driverId;
                    $orderStatus['Orderstatus']['status']     = 'Accepted';
                    $orderStatus['Orderstatus']['driver_latitude']   = $latitude;
                    $orderStatus['Orderstatus']['driver_longitude']  = $longitude;

                    $this->Orderstatus->save($orderStatus);
                    $response['success'] = '1';
                    
                break;
                  
                case 'DriverLogOut':
                
                    $driverId = $this->request->data['driverid'];
                    $message = array('message'=>'logout', 'OrderDetails'=>'');
                    if ($driverId != '') {
                        $driver = $this->Driver->findById($driverId);
                        if ($this->request->data['from'] == 'site') {
                            $gcm    = (trim($driver['Driver']['device_name']) == 'ANDROID') ?
                                            $this->AndroidResponse->sendOrderByGCM($message, $driver['Driver']['device_id']) : 
                                            $this->PushNotifications->notificationIOS('logout', $driver['Driver']['device_id']);
                        }
                        $driver['Driver']['is_logged'] = '0';
                        $driver['Driver']['driver_status']  = 'Offline';
                        $driver['Driver']['device_id']      = '';
                        $this->DriverTracking->deleteAll(array('DriverTracking.driver_id'=>$driver['Driver']['id']));
                        $driverLogout = $this->Driver->save($driver);
                        
                        $response['success'] = '1';
                        $response['message'] = 'Successfully logout ';
                        
                        $message = $driver['Driver']['driver_name']." loggedout";
                        $this->Notifications->pushNotification($message, 'FoodOrderAdmin');
                        $this->Notifications->pushNotification($message, 'Restaurant_'.$driver['Driver']['store_id']);
                    } else {
                        $response['success'] = '0';
                        $response['message'] = 'Try Again..!';
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
                    
            }
        } else {
            $response['success'] = '0';
            $response['message'] = 'Invalid request';
        }
        die(json_encode($response));
    }
}