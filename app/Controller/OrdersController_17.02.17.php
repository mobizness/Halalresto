<?php

/* janakiraman */

App::uses('AppController','Controller');
App::uses('CakeEmail', 'Network/Email');
App::import('Controller', 'Commons');

class OrdersController extends AppController {
    
  var $helpers = array('Html', 'Session', 'Javascript', 'Ajax', 'Common');

  public $uses    = array('Order','ShoppingCart', 'CustomerAddressBook', 'StripeCustomer',
                          'Storeoffer', 'State', 'City', 'Location', 'Store',
                          'ProductDetail', 'Orderstatus', 'Notification', 'StripeRefund',
                          'DeliveryLocation', 'Voucher', 'Customer', 'WalletHistory',
                          'Driver');

  public $components = array('Stripe', 'Googlemap', 'AndroidResponse', 'Twilio',
                            'Notifications', 'PushNotifications');

  public function beforeFilter() {
    parent::beforeFilter();

    $states = $this->State->find('list', array(
                          'conditions' => array('State.country_id' => $this->siteSetting['Sitesetting']['site_country']),
                          'fields' => array('id', 'state_name')));
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
    $this->set(compact('states'));
  }

  /**
   * OrderController::admin_index()
   * 
   * @return void
   */
  public function admin_index() {
    $order_list = $this->Order->find('all', array(
                        'conditions' => array('Order.status' => 'Pending'),
                        'order' => array('Order.id DESC')));

    /*$pendingStatus = array('Pending' => 'En attente', 'Accepted' => 'Accepté', 'Failed' => 'Annulé');
    $acceptStatus  = array('Pending' => 'Accepté', 'Failed' => 'Annulé', 'Delivered' => 'Livré');*/


    $status = array('Pending' => 'En attente', 'Accepted' => 'Accepté',
                    'Failed' => 'Annulé', 'Delivered' => 'Livré');
    

    $this->set(compact('order_list', 'status'));
  }

  // Admin Collection Orders
  public function admin_collectionOrders() {

    $order_list = $this->Order->find('all', array(
                        'conditions' => array('Order.status' => 'Accepted',
                                              'Order.order_type' => 'Collection'),
                        'order' => array('Order.id DESC')));

    $status = array('Pending' => 'Accepté', 'Failed' => 'Annulé', 'Delivered' => 'Livré');

    $this->set(compact('order_list', 'status'));
  }

  // Admin failed Orders list
  public function admin_failedOrderDetail(){
      $order_list = $this->Order->find('all', array(
                            'conditions' => array('Order.status' => 'Failed'),
                            'order' => array('Order.id DESC')));
      $status = array('Pending' => 'Pending', 'Accepted' => 'Accepted',
                      'Failed' => 'Failed', 'Delivered' => 'Delivered');
      $this->set(compact('order_list', 'status'));
  }

  // Admin Dispatch Order
  public function admin_order() {

    $statuss = array('Delivered','Pending','Failed', 'Deleted');

    $location = $this->Location->find('list', array(
                                    'fields' => array('id', 'area_name')));

    $city = $this->City->find('list', array(
                                    'fields' => array('id', 'city_name')));

    $orderList = $this->Order->find('all', array(
                        'conditions' => array('Order.order_type' => 'Delivery',
                              'NOT'  => array('Order.status' => $statuss)),
                        'order' => array('Order.id DESC')));

    // echo "<pre>";print_r($orderList);echo "</pre>";exit; 

    foreach ($orderList as $key => $value) {
      if ($orderList[$key]['Order']['status'] == 'Waiting') {
        $orderList[$key]['Order']['status'] = 'En attente';
      } elseif ($orderList[$key]['Order']['status'] == 'Accepted') {
        $orderList[$key]['Order']['status'] = 'Accepté';
      } elseif ($orderList[$key]['Order']['status'] == 'Driver Accepted') {
        $orderList[$key]['Order']['status'] = 'Coursier Accepté';
      } elseif ($orderList[$key]['Order']['status'] == 'Collected') {
        $orderList[$key]['Order']['status'] = 'Emporté';
      } else {
        $orderList[$key]['Order']['status'] = $orderList[$key]['Order']['status'];
      }
    }
    
    
    $status = array('Failed' => 'Annulé', 'Delivered' => 'Livré');

    $this->set(compact('orderList', 'location', 'city', 'status'));
  }

  //Report Management Process
  public function admin_reportIndex($storeId = null, $rangeId = null, $driverId = null) {
    
      list($order_list, $driverList) = $this->orderFilterReport($storeId, $rangeId, $driverId);

      $range = array('0' => 'Tout', '1' => 'Aujourd’hui', '2' => 'Cette semaine',
                      '3' => 'Ce mois', '4' => 'Cette semaine');

      $stores = $this->Store->find('list', array(
                                //'conditions'  =>  array('Store.status !=' => 3),
                                'conditions'  =>  array('Store.status' => 1),
                                'fields'      =>  array('Store.id', 'Store.store_name')));

      $this->request->data['Store']['StoreOrder']  = $storeId;
      $this->request->data['Store']['StoreRange']  = $rangeId;
      $this->request->data['Store']['StoreDriver'] = $driverId;

      
      $this->set(compact('order_list', 'stores', 'range', 'driverList'));     
  }

  // Report Management Order View
  public function admin_reportOrderView($id = null) {
    if (!empty($id)){
      $orders_list = $this->Order->find('first', array(
                              'conditions' => array('Order.id' => $id,
                                                    'Order.status' => array('Delivered', 'Failed'))));
      if (empty($orders_list)) {
          $this->render('/Errors/error400');
      }

      $cities = $this->City->find('list', array(
                      'conditions' => array('City.state_id' => $orders_list['Store']['store_state']),
                      'fields' => array('id', 'city_name')));

      $location = $this->Location->find('list', array(
                      'conditions' => array('Location.city_id' => $orders_list['Store']['store_city']),
                      'fields' => array('id', 'area_name')));

      $payments = array('Card', 'Wallet');
      $this->set(compact('orders_list', 'cities', 'location', 'payments'));
    } else {
      $this->redirect(array('controller' => 'Orders', 'action' => 'reportIndex'));
    }
  }

  // Order view Process
  public function admin_orderView($id = null) {
    
    if(!empty($id)){
      $order_detail = $this->Order->find('first', array(
                              'conditions' => array('Order.id' => $id)));
      if (empty($order_detail)) {
          $this->render('/Errors/error400');
      }

      $cities = $this->City->find('list', array(
                      'conditions' => array('City.state_id' => $order_detail['Store']['store_state']),
                      'fields' => array('id', 'city_name')));

      $location = $this->Location->find('list', array(
                      'conditions' => array('Location.city_id' => $order_detail['Store']['store_city']),
                      'fields' => array('id', 'area_name')));
      
      $payments = array('Card', 'Wallet');
      $this->set(compact('order_detail', 'cities', 'location', 'payments'));

    } else {
       $this->redirect(array('controller' => 'Orders', 'action' => 'index'));
    }
  }

  // Confirm Order Process
  public function conformOrder() {

    // echo "<pre>";print_r($this->request->data);echo "</pre>";exit;
    
      $storeId = $this->Session->read("storeId");
      $role    = $this->Auth->User('role_id');

      $cardFee = $this->siteSetting['Sitesetting']['card_fee'];

      $freeDeliveryCharge = $deliveryCharge = 0;

      if (empty($role) || $this->Auth->User('role_id') != 4) {
          $this->redirect(array('controller' => 'searches', 'action' => 'index'));
      }

      $payments   = array('cod', 'wallet');
      $customerId = $this->Auth->User('Customer.id');

       if (!in_array($this->request->data['Order']['paymentMethod'], $payments)) {
          $stripeCard = $this->StripeCustomer->find('first', array(
                              'conditions' => array(
                                        'StripeCustomer.id' => $this->request->data['Order']['paymentMethod'],
                                        'StripeCustomer.customer_id' => $customerId)));
          /*if (empty($stripeCard)) {
              $this->redirect(array('controller' => 'searches', 'action' => 'index'));
          }*/
      }
      
      $today= date("m/d/Y");
      $lastsessionid  = $this->Session->read("preSessionid");
      $SessionId = (!empty($lastsessionid)) ? $lastsessionid : $this->Session->id();

      /*if (!empty($this->request->data['Order']['delivery_id'])) {
        $customerDetails = $this->CustomerAddressBook->find('first', array(
                        'conditions' => array(
                            'CustomerAddressBook.id' => $this->request->data['Order']['delivery_id'],
                            'CustomerAddressBook.customer_id' => $customerId)));
      }*/

      $customerDetails = $this->CustomerAddressBook->find('first', array(
                        'conditions' => array('CustomerAddressBook.customer_id' => $customerId)));

      $order['store_id']            = $storeId;
      $order['customer_id']         = $customerId;
      $order['user_type']           = 'Customer';
      $order['order_description']   = $this->request->data['Order']['order_description'];
      $order['order_type']          = $this->request->data['Order']['orderType'];

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
      
      $sourceLatLong    = $this->Googlemap->getlatitudeandlongitude($storeAddress);
      $source_lat       = (!empty($sourceLatLong['lat'])) ? $sourceLatLong['lat'] : 0;
      $source_long      = (!empty($sourceLatLong['long'])) ? $sourceLatLong['long'] : 0;


      $order['customer_name']       = $this->request->data['Customer']['first_name']. ' '.
                                        $this->request->data['Customer']['last_name'];
      $order['customer_email']      = $this->request->data['Customer']['customer_email'];
      $order['customer_phone']      = $this->request->data['Customer']['customer_phone'];
      
      if ($order['order_type'] != 'Collection') {        
          if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
              $order['address'] = $customerDetails['CustomerAddressBook']['address'];
              $order['landmark'] = $customerDetails['CustomerAddressBook']['landmark'];
              $order['state_name'] = $customerDetails['State']['state_name'];
              $order['city_name'] = $customerDetails['City']['city_name'];
              $order['location_name'] = $customerDetails['Location']['area_name'];

              $deliveryAddress =  $order['address'].', '.
                  $order['location_name'].', '.
                  $order['city_name'].', '.
                  $order['state_name'].', '.
                  $this->siteSetting['Country']['country_name'];
          } else {
              $order['google_address'] = $customerDetails['CustomerAddressBook']['google_address'];
              $deliveryAddress =  $order['google_address'];
          }


        $destinationLatLong = $this->Googlemap->getlatitudeandlongitude($deliveryAddress);

        $order['destination_latitude']    = (!empty($destinationLatLong['lat'])) ? $destinationLatLong['lat'] : 0;
        $order['destination_longitude']   = (!empty($destinationLatLong['long'])) ? $destinationLatLong['long'] : 0;
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
        $order['destination_latitude']    = $source_lat;
        $order['destination_longitude']   = $source_long;
      }


      $destination_lat  = $order['destination_latitude'];
      $destination_long = $order['destination_longitude'];
      $distance = $this->Googlemap->getDrivingDistance($source_lat,$source_long,$destination_lat,$destination_long);
      $order['source_latitude']   = $source_lat;
      $order['source_longitude']  = $source_long;

      $storeOffers = $this->Storeoffer->find('first', array(
                                'conditions' => array('Storeoffer.store_id' => $storeId,
                                          'Storeoffer.status' => 1,
                                           "Storeoffer.from_date <="  => $today,
                                           "Storeoffer.to_date >="    => $today),
                                'order' => 'Storeoffer.id DESC'));

      $total = $this->ShoppingCart->find('first', array(
              'conditions'=>array('ShoppingCart.session_id' => $SessionId,
                                  'ShoppingCart.order_id' => 0,
                                  'ShoppingCart.store_id' => $storeId),
              'fields' => array('SUM(ShoppingCart.product_total_price) AS cartSubTotal')));



      // Voucher Calculation
      $objCommon   = new CommonsController;
      $voucherDetail =  $objCommon->voucherCodeCheck($storeId, trim($this->request->data['Order']['voucher_code']));
      if (!empty($voucherDetail['Voucher'])) {

        if ($voucherDetail['Voucher']['offer_mode'] == 'percentage') {
          $order['voucher_amount']     =  $total[0]['cartSubTotal'] * 
                                            $voucherDetail['Voucher']['offer_value']/100;
          $order['voucher_percentage'] =  $voucherDetail['Voucher']['offer_value'];
        } elseif ($voucherDetail['Voucher']['offer_mode'] == 'price') {
          $order['voucher_amount']     =  $voucherDetail['Voucher']['offer_value'];
        } else {
          $freeDeliveryCharge = 1;
        }
        $order['voucher_code']         =  $voucherDetail['Voucher']['voucher_code'];
      } else {
        $order['voucher_amount']       =  0;
      }



      if ($order['order_type'] != 'Collection') {

        if (empty($freeDeliveryCharge)) {
          if ($this->siteSetting['Sitesetting']['address_mode'] == 'Google') {
              $deliveryCharge = $storeDetails['Store']['delivery_charge'];
          } else {
              $cityId = $customerDetails['CustomerAddressBook']['city_id'];
              $areaId = $customerDetails['CustomerAddressBook']['location_id'];

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

      $deliveryDate = ($this->request->data['Order']['assoonas'] == 'Later')
          ? date('Y-m-d', strtotime($this->request->data['Order']['delivery_date'])) : date('Y-m-d');


      $order['assoonas']            = $this->request->data['Order']['assoonas'];
      $order['delivery_date']       = $deliveryDate;
      $order['delivery_time']       = $this->request->data['Order']['delivery_time'];
      $order['delivery_charge']     = (!empty($deliveryCharge)) ? $deliveryCharge : 0;
      $order['order_sub_total']     = $total[0]['cartSubTotal'];
      $order['tax_percentage']      = $storeDetails['Store']['tax'];
      $order['tax_amount']          = (!empty($storeDetails['Store']['tax'])) ? $total[0]['cartSubTotal'] * 
                                        ($storeDetails['Store']['tax']/100) : 0;
      $order['distance']            = (isset($distance['distanceText'])) ? $distance['distanceText'] : 0 ;
      $order['offer_amount']        = ($storeOffers['Storeoffer']['offer_price'] <= $total[0]['cartSubTotal']) ?
                                      $total[0]['cartSubTotal'] * $storeOffers['Storeoffer']['offer_percentage']/100 :
                                        0;
      $order['offer_percentage']    = (!empty($order['offer_amount'])) ? $storeOffers['Storeoffer']['offer_percentage'] : 0;

      // Tips Calculation in percentage
      /*$tipPrice = '';
      if ($this->request->data['Order']['paymentMethod'] != 'cod' &&
          $this->request->data['Order']['tip_percentage'] != '') {

          $Total = $order['order_sub_total'] + $order['delivery_charge'] +
              $order['tax_amount'] - $order['offer_amount'];
          $tipPercentage = $this->request->data['Order']['tip_percentage'];
          $tipPrice = ($Total * $this->request->data['Order']['tip_percentage']) / 100;
      }
      $order['tip_percentage']      = (!empty($tipPercentage)) ? $tipPercentage : '';
      $order['tip_amount']          = (!empty($tipPrice)) ? $tipPrice : '0.00';*/

      // Tips Calculation in price
      $tipPrice = $this->request->data['Order']['tip_percentage'];
      $order['tip_amount']          = (!empty($tipPrice)) ? $tipPrice : 0;

      $order['order_grand_total']   = $order['order_sub_total'] + $order['delivery_charge'] + $order['tax_amount'] + 
                                      $order['tip_amount'] - $order['offer_amount'] - $order['voucher_amount'];
      $grandTotal = $order['order_grand_total'];
                                      
      $this->Order->save($order, null, null);
      $update['ref_number'] = '#ORD00'.$this->Order->id;
      $orderId[]    = $update['id'] = $this->Order->id;
      $this->Order->save($update);
      $this->ShoppingCart->updateAll(
                    array('ShoppingCart.order_id' => $this->Order->id),
                    array('ShoppingCart.session_id' => $SessionId,
                           'ShoppingCart.order_id' => 0,
                          'ShoppingCart.store_id'   => $storeId));


      if ($this->request->data['Order']['paymentMethod'] == 'cod') {
          $orderUpdate['id'] = $this->Order->id;
          $orderUpdate['payment_type'] = 'cod';
          $this->Order->save($orderUpdate);
          //Ordermail
          if ($_SERVER['HTTP_HOST'] == 'halal-resto.fr') {
          
            $this->ordermail($this->Order->id);
            //OrderSms
            
            $this->ordersms($this->Order->id);
          }
          

      } else {

        $amount = number_format($grandTotal,2);

        if ($this->request->data['Order']['paymentMethod'] == 'wallet') {

          $customerDetails = $this->Customer->find('first', array(
                      'conditions' => array('Customer.id' => $customerId),
                      'recursive' => 0,
                      'fields' => array('wallet_amount')));

          if ($customerDetails['Customer']['wallet_amount'] >= $amount) {

            $customerDetails['Customer']['wallet_amount'] -= $amount;

            if ($this->Customer->save($customerDetails, null, null)) {

                $orderUpdate['id']              = $this->Order->id;
                $orderUpdate['payment_type']    = 'Wallet';
                $orderUpdate['payment_method']  = 'paid';

                $orderUpdate['cardfee_percentage']  = $cardFee;
                $orderUpdate['cardfee_price']       = $grandTotal * ($cardFee/100);
                
                $this->Order->save($orderUpdate);

                // Wallet History
                $walletHistory['amount']              = $amount;
                $walletHistory['purpose']             = 'Transaction on order '. $update['ref_number'];
                $walletHistory['customer_id']         = $customerId;
                $walletHistory['transaction_type']    = 'Debited';
                $walletHistory['transaction_details'] = $update['ref_number'];

                $this->WalletHistory->save($walletHistory, null, null);
                
                //Ordermail
                if ($_SERVER['HTTP_HOST'] == 'halal-resto.fr') {
                  $this->ordermail($this->Order->id);
                  //OrderSms
                  $this->ordersms($this->Order->id);
                }
            }
          } else {
            $this->Session->setFlash(__('Insufficient balance in wallet and payment failed', true),
                                      'default', array('class' => 'alert alert-danger'));
            $this->redirect(array('controller' => 'searches', 'action' => 'index', 'Failed'));
          }

        } else {

          if(!empty($this->request->data['Order']['stripetoken'])) {

              $datas          = array("stripeToken"  => $this->request->data['Order']['stripetoken']);
              $stripeCustomer = $this->Stripe->customerCreate($datas);
              $stripId        = $stripeCustomer['stripe_id']; 

          } else {
              // $stripeCard = $this->StripeCustomer->findById($this->request->data['Order']['paymentMethod']);
              if (!empty($this->request->data['Order']['stripepayment_id'])) {
                $stripeCard = $this->StripeCustomer->findById($this->request->data['Order']['stripepayment_id']);
              } else {
                $stripeCard = $this->StripeCustomer->findById($this->request->data['Order']['paymentMethod']);
              }

              if (empty($stripeCard['StripeCustomer']['stripe_customer_id'])) {
                  $datas    = array("stripeToken"  => $stripeCard['StripeCustomer']['stripe_token_id']);
                  $customer = $this->Stripe->customerCreate($datas);
                  $stripId = $stripeCard['StripeCustomer']['stripe_customer_id'] = $customer['stripe_id'];
                  $this->StripeCustomer->save($stripeCard);

              } else {
                  $stripId = $stripeCard['StripeCustomer']['stripe_customer_id'];
              }
          }

          $data   = array('currency'       => $this->siteSetting['Country']['currency_code'],
                          "amount"         => $amount,
                          "stripeCustomer" => $stripId);
          
          $stripeResponse = $this->Stripe->charge($data);

          if ($stripeResponse['status'] == "succeeded" && $stripeResponse['stripe_id'] != '') {

            $orderUpdate['id']              = $this->Order->id;
            $orderUpdate['transaction_id']  = $stripeResponse['stripe_id'];
            $orderUpdate['payment_type']    = 'Card';
            $orderUpdate['payment_method']  = 'paid';
            $orderUpdate['cardfee_percentage']  = $cardFee;
            $orderUpdate['cardfee_price']       = $grandTotal * ($cardFee/100);

            $this->Order->save($orderUpdate);
            //Ordermail
            if ($_SERVER['HTTP_HOST'] == 'halal-resto.fr') {
              $this->ordermail($this->Order->id);
              //OrderSms
              $this->ordersms($this->Order->id);
            }

          } else {

            $responseMessage = $stripeResponse;
            $filePath      = ROOT.DS.'app'.DS."tmp".DS.'Stripe.txt';
            $file = fopen($filePath,"a+");
            fwrite($file, PHP_EOL.'Message---->'.$stripeResponse.PHP_EOL.'Response---->'.$responseMessage.PHP_EOL);
            fclose($file);

            $orderUpdate['id']            = $this->Order->id;
            $orderUpdate['status']        = 'Failed';
            $orderUpdate['payment_type']  = 'Card';
            $orderUpdate['failed_reason'] = 'Payment failed';
            $this->Order->save($orderUpdate);
            $this->changeLocation();
            $this->Session->setFlash(__('Le paiement a échoué', true),
                                      'default', array('class' => 'alert alert-danger'));
            $this->redirect(array('controller' => 'checkouts', 'action' => 'thanks', 
                                    $this->Order->id));
          }
        }

      }
      $this->changeLocation();
      $this->Session->write("preSessionid",'');
      $this->Session->setFlash(__('Your Order Placed Successfully', true),
                                    'default', array('class' => 'alert alert-success'));
      $this->redirect(array('controller' => 'checkouts', 'action' => 'thanks', 
                            $this->Order->id));
  }

  // Order placed and session regenerate
  public function changeLocation() {

    $this->Session->write("preSessionid",'');
    //$this->Session->write("storeId",'');
    session_regenerate_id();
    return 1;
  }
  
  //Order Sms
  public function ordersms($orderId) {

      $orderDetail = $this->Order->findById($orderId);
      /*$customerMessage = 'Thanks for using '.$this->siteSetting['Sitesetting']['site_name'].' service. Your order '.$orderDetail['Order']['ref_number'].' has been placed . Track your order at '.$this->siteUrl.'.  Regards '.$this->siteSetting['Sitesetting']['site_name'].'.';*/

      $customerMessage = "Merci d'avoir utilisé les services d’".$this->siteSetting['Sitesetting']['site_name'].'. Votre commande '.$orderDetail['Order']['ref_number'].' est passée . Vous pouvez suivre votre commande à '.$this->siteUrl.'.  Merci l’équipe d’ '.$this->siteSetting['Sitesetting']['site_name'].'.';

      $toCustomerNumber = '+'.$this->siteSetting['Country']['phone_code'].$this->Auth->User('Customer.customer_phone');
      $customerSms      = $this->Twilio->sendSingleSms($toCustomerNumber, $customerMessage);

      if ($orderDetail['Store']['sms_option'] == 'Yes' && !empty($orderDetail['Store']['sms_phone'])) {
          $storeMessage  = "Dear ".$orderDetail['Store']['store_name']." you've received a ";
          $storeMessage .= ($orderDetail['Order']['payment_method'] != 'paid') ? 'COD' : 'PAID';
          $storeMessage .= ' order '.$orderDetail['Order']['ref_number'].' from '.$orderDetail['Order']['customer_name'];

          if ($orderDetail['Order']['order_type'] == 'Delivery') {
              if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
                  $storeMessage .= ','.$orderDetail['Order']['address'].','.$orderDetail['Order']['landmark'].
                    ','.$orderDetail['Order']['location_name'].','.$orderDetail['Order']['city_name'].
                    ','.$orderDetail['Order']['city_name'];
              } else {
                  $storeMessage .= ','.$orderDetail['Order']['google_address'];
              }

          }

          $storeMessage .= '. '.$orderDetail['Order']['order_type'].' due on '.$orderDetail['Order']['delivery_date'].' at '.$orderDetail['Order']['delivery_time'].'. Thanks '.$this->siteSetting['Sitesetting']['site_name'].'';
          $toStoreNumber = '+'.$this->siteSetting['Country']['phone_code'].$orderDetail['Store']['sms_phone'];
          $customerSms   = $this->Twilio->sendSingleSms($toStoreNumber, $storeMessage);
      }

      // Store Owner App Message
      if ($orderDetail['Store']['is_logged'] == 1) {
          $deviceId      = $orderDetail['Store']['device_id'];
          $message      = 'Une nouvelle commande a été passée - '.$orderDetail['Order']['ref_number'];

          $gcm = $this->AndroidResponse->sendOrderByGCM(
                  array('message'    => $message),
                          $deviceId);

      }
      return true;
  }

  // Order list management in store
  public function store_index(){
    $this->layout = 'assets';
    $order_list   = $this->Order->find('all', array(
                        'conditions'=>array('Order.store_id' => $this->Auth->User('Store.id'),
                                            'Order.status' => array('Delivered', 'Failed')),
                        'order' => array('Order.id DESC')));

    $this->set(compact('order_list'));
  }

  // Order Reports in store
  public function store_reportOrderView($id = null) {
    $this->layout = 'assets';
    if (!empty($id)){
      $orders_list = $this->Order->find('first', array(
                              'conditions' => array('Order.id' => $id,
                                      'Order.status' => array('Delivered', 'Failed'),
                                      'Order.store_id' => $this->Auth->User('Store.id'))));
      if (empty($orders_list)) {
          $this->render('/Errors/error400');
      }
      $cities = $this->City->find('list', array(
                      'conditions' => array('City.state_id' => $orders_list['Store']['store_state']),
                      'fields' => array('id', 'city_name')));

      $location = $this->Location->find('list', array(
                      'conditions' => array('Location.city_id' => $orders_list['Store']['store_city']),
                      'fields' => array('id', 'area_name')));

      $this->set(compact('orders_list', 'cities', 'location'));
    } else {
      $this->redirect(array('controller' => 'Orders', 'action' => 'reportIndex'));
    }
  }

  // Order list
  public function store_orderIndex() {
    $this->layout = 'assets';
    $id           = $this->Auth->User();
    $order_list = $this->Order->find('all', array(
                            'conditions'=>array('Order.store_id'=>$id['Store']['id'],
                                                'Order.status' => 'Pending'),
                             'order' => array('Order.id DESC')));
    /*$pendingStatus = array('Pending' => 'En attente', 'Accepted' => 'Accepté', 'Failed' => 'Annulé');
    $acceptStatus  = array('Pending' => 'Accepté', 'Failed' => 'Annulé', 'Delivered' => 'Livré');*/
    $status = array('Pending' => 'En attente', 'Accepted' => 'Accepté',
                    'Failed' => 'Annulé', 'Delivered' => 'Livré');
    $this->set(compact('order_list', 'status'));
  }

  // Order detail view
  public function store_orderView($id = null) {
    $this->layout = 'assets';
    if(!empty($id)){
      $order_detail = $this->Order->find('first', array(
                              'conditions' => array('Order.id' => $id,
                                          'Order.store_id' => $this->Auth->User('Store.id'))));
      if (empty($order_detail)) {
          $this->render('/Errors/error400');
      }
      $cities = $this->City->find('list', array(
                      'conditions' => array('City.state_id' => $order_detail['Store']['store_state']),
                      'fields' => array('id', 'city_name')));

      $location = $this->Location->find('list', array(
                      'conditions' => array('Location.city_id' => $order_detail['Store']['store_city']),
                      'fields' => array('id', 'area_name')));
      
      $this->set(compact('order_detail', 'cities', 'location'));
    } else {
       $this->redirect(array('controller' => 'Orders', 'action' => 'index'));
    }
  }

  // Order list 
  public function store_order() {
    $this->layout = 'assets';
    $id = $this->Auth->User();
    
    $statuss = array('Delivered','Pending','Failed','Deleted');

    $location = $this->Location->find('list', array(
                                    'fields' => array('id', 'area_name')));

    $city = $this->City->find('list', array(
                                    'fields' => array('id', 'city_name')));
    
    $orderList = $this->Order->find('all', array(
                        'conditions' => array('Order.store_id'=>$id['Store']['id'],
                                              'Order.order_type' => 'Delivery',
                            'NOT' => array('Order.status' => $statuss)),
                        'order' => array('Order.id DESC')));


    foreach ($orderList as $key => $value) {
      if ($orderList[$key]['Order']['status'] == 'Waiting') {
        $orderList[$key]['Order']['status'] = 'En attente';
      } elseif ($orderList[$key]['Order']['status'] == 'Accepted') {
        $orderList[$key]['Order']['status'] = 'Accepté';
      } elseif ($orderList[$key]['Order']['status'] == 'Driver Accepted') {
        $orderList[$key]['Order']['status'] = 'Coursier Accepté';
      } elseif ($orderList[$key]['Order']['status'] == 'Collected') {
        $orderList[$key]['Order']['status'] = 'Emporté';
      } else {
        $orderList[$key]['Order']['status'] = $orderList[$key]['Order']['status'];
      }
    }
     

    $status = array('Failed' => 'Annulé', 'Delivered' => 'Livré');

    $this->set(compact('orderList', 'location', 'city', 'status'));
  }

  // Order status change
  public function admin_orderStatus() {


    $orderStatusUpdate['id'] = $orderId      = $this->request->data['orderId'];
    $orderStatusUpdate['status']  = $this->request->data['status'];

    $orderDetail = $this->Order->findById($orderId);


    // Driver Notification
    $deviceId = $orderDetail['Driver']['device_id'];

    if (isset($this->request->data['reason'])) {

      //if (!empty($orderDetail['Order']['transaction_id'])) {

        $payments = array('Card', 'Wallet');

        if (in_array($orderDetail['Order']['payment_type'], $payments) &&
            $orderDetail['Order']['payment_method'] == 'paid') {
              $this->admin_refund($orderId, 'failed');
        } elseif ($orderDetail['Order']['payment_type'] == 'cod') {

            $orderStatusUpdate['id'] = $orderId      = $this->request->data['orderId'];
            $orderStatusUpdate['status']  = $this->request->data['status'];
            $orderStatusUpdate['failed_reason']  = $this->request->data['reason'];
            $this->Order->save($orderStatusUpdate);
            $this->admin_sendNotificationCod($orderId);  

        }
      //}

      if (!empty($deviceId)) {

        $driverMessage = 'Disclaim Order '.$orderDetail['Order']['ref_number'];
        $message       = array('message' => $driverMessage,
                                'OrderId' => $orderId,
                                'OrderDetails' => '');

        $gcm    = (trim($orderDetail['Driver']['device_name']) == 'ANDROID') ?
                        $this->AndroidResponse->sendOrderByGCM($message, $deviceId) :
                        $this->PushNotifications->notificationIOS($driverMessage, $deviceId);
        $gcm = json_decode($gcm, true);
      }

      $orderStatusUpdate['failed_reason']  = $this->request->data['reason'];
      $this->Order->save($orderStatusUpdate);
    }

    if ($this->request->data['status'] == 'Delivered') {

        $this->Orderstatus->deleteAll(array('Orderstatus.order_id'=>$orderId));
        $orderStatusUpdate['payment_method']  = 'paid';
        $this->Order->save($orderStatusUpdate);

        if (!empty($deviceId)) {

          $driverMessage = 'Delivered Order '.$orderDetail['Order']['ref_number'];
          $message       = array('message' => $driverMessage,
                                'OrderId' => $orderId,
                                'OrderDetails' => '');

          $gcm    = (trim($orderDetail['Driver']['device_name']) == 'ANDROID') ?
                      $this->AndroidResponse->sendOrderByGCM($message, $deviceId) :
                      $this->PushNotifications->notificationIOS($driverMessage, $deviceId);
          $gcm = json_decode($gcm, true);

        }
    }

    if ($orderStatusUpdate['status'] == 'Accepted') {

        $orderStatusUpdate['driver_id']  = '';
        if ($this->Order->save($orderStatusUpdate)) {

          if ($orderDetail['Driver']['is_logged'] == 1) {

            $driverMessage = 'Disclaim Order '.$orderDetail['Order']['ref_number'];
            $message       = array('message' => $driverMessage,
                                    'OrderId' => $orderId,
                                    'OrderDetails' => '');
            
            $gcm    = (trim($orderDetail['Driver']['device_name']) == 'ANDROID') ?
                            $this->AndroidResponse->sendOrderByGCM($message, $deviceId) :
                            $this->PushNotifications->notificationIOS($driverMessage, $deviceId);
            $gcm = json_decode($gcm, true);
          }

            $customerMessage = 'Votre commande a été acceptée avec succèss par '.
                                $orderDetail['Store']['store_name'].'. Votre commande sera livrée le '.
                                date("m/d/Y", strtotime($orderDetail['Order']['delivery_date'])). ' à '.$orderDetail['Order']['delivery_time'].'. Merci l’équipe d’ '.$this->siteSetting['Sitesetting']['site_name'];

            $toCustomerNumber = '+'.$this->siteSetting['Country']['phone_code'].$orderDetail['Customer']['customer_phone'];
            $customerSms      = $this->Twilio->sendSingleSms($toCustomerNumber, $customerMessage);
            echo 'Success';
        }
    }

    if ($orderStatusUpdate['status'] == 'Deleted') {
        if ($this->Order->save($orderStatusUpdate)) {
          echo 'Success';
        }
    }


    //Send mail to customer when order accepted,failed,delivered
    if ($_SERVER['HTTP_HOST'] == 'halal-resto.fr') {
        
        if ($orderStatusUpdate['status'] == 'Accepted' || $orderStatusUpdate['status'] == 'Delivered') {

          $customer_mail = $orderDetail['Order']['customer_email'];
          $customerName  = $orderDetail['Order']['customer_name'];          
          
          if ($orderStatusUpdate['status'] == 'Failed') {
            $mailContent='<table>
              <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
                    <td colspan="3" style="display:inline-block; width:100%;vertical-align: top;">Hi '.$customerName.',</td>
              <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
                    <td colspan="3" style="display:inline-block; width:100%;vertical-align: top;">We are sorry for the inconvience caused,your Order '.$orderDetail['Order']['ref_number'].' has been '.$orderStatusUpdate['status'].' for the reason '.$orderStatusUpdate['failed_reason'].'.</td></table>'; 
          } else {
            $mailContent='<table>
              <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
                    <td colspan="3" style="display:inline-block; width:100%;vertical-align: top;">Hi '.$customerName.',</td>
              <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
                    <td colspan="3" style="display:inline-block; width:100%;vertical-align: top;">Your Order '.$orderDetail['Order']['ref_number'].' has been '.$orderStatusUpdate['status'].'.</td></table>'; 
          }

          
          
          $customerSubject = 'Order '.$orderStatusUpdate['status'];
          $source        = $this->siteUrl.'/siteicons/logo.png';
          $siteName      = $this->siteSetting['Sitesetting']['site_name'];
          $sitemailId    = $this->siteSetting['Sitesetting']['admin_email'];


          $email = new CakeEmail();
          $email->from($sitemailId);
          $email->to($customer_mail);
          $email->subject($customerSubject);
          $email->template('register');
          $email->emailFormat('html');
          $email->viewVars(array('mailContent' => $mailContent,
                       'source' => $source,
                                 'storename' => $siteName));

          $email->send();     
        }  
        
    }

    // Pusher in Store and Customer
    $message = $orderDetail['Order']['ref_number'].' a été '.$orderStatusUpdate['status'];
    $this->Notifications->pushNotification($message, 'Restaurant_'.$orderDetail['Order']['store_id']);
    $this->Notifications->pushNotification($message, 'FoodCustomer_'.$orderDetail['Order']['customer_id']);
    

    // Store Owner Message
    if ($orderDetail['Store']['is_logged'] == 1) {
      
        $deviceId      = $orderDetail['Store']['device_id'];

        if ($this->request->data['status'] == 'Failed') {
          $message = 'This order '.$orderDetail['Order']['ref_number']. ' will be failed. due to '.$this->request->data['reason'].'.';
        } elseif (!empty($orderDetail['Driver']['device_id']) && $this->request->data['status'] == 'Accepted') {
          $message = 'This order '.$orderDetail['Order']['ref_number']. ' was disclaimed.';
        } else{
           $message = 'This order '.$orderDetail['Order']['ref_number']. ' will be '.strtolower(trim($this->request->data('status'))).'.';
        }

        $gcm = $this->AndroidResponse->sendOrderByGCM(array('message' => $message),$deviceId);
    }
    exit();
  }

  // Order status change
  public function store_orderStatus() {
    
    $orderStatusUpdate['id'] = $orderId = $this->request->data['orderId'];
    $orderStatusUpdate['status']  = $this->request->data['status'];

    $orderDetail = $this->Order->findById($orderId);

	// Driver Notification
    $deviceId = $orderDetail['Driver']['device_id'];


    if (isset($this->request->data['reason'])) {
        if (!empty($deviceId)) {

          $driverMessage = 'Disclaim Order '.$orderDetail['Order']['ref_number'];
          $message       = array('message' => $driverMessage,
                                  'OrderId' => $orderId,
                                  'OrderDetails' => '');
          
          $gcm    = (trim($orderDetail['Driver']['device_name']) == 'ANDROID') ?
                          $this->AndroidResponse->sendOrderByGCM($message, $deviceId) :
                          $this->PushNotifications->notificationIOS($driverMessage, $deviceId);
          $gcm = json_decode($gcm, true);
        }
        $orderStatusUpdate['failed_reason']  = $this->request->data['reason'];
        $this->Order->save($orderStatusUpdate);
    }

    if ($this->request->data['status'] == 'Delivered') {

        $this->Orderstatus->deleteAll(array('Orderstatus.order_id'=>$orderId));
        $orderStatusUpdate['payment_method']  = 'paid';
        $this->Order->save($orderStatusUpdate);

        if (!empty($deviceId)) {

          $driverMessage = 'Delivered Order '.$orderDetail['Order']['ref_number'];
          $message       = array('message' => $driverMessage,
                                'OrderId' => $orderId,
                                'OrderDetails' => '');

          $gcm    = (trim($orderDetail['Driver']['device_name']) == 'ANDROID') ?
                      $this->AndroidResponse->sendOrderByGCM($message, $deviceId) :
                      $this->PushNotifications->notificationIOS($driverMessage, $deviceId);
          $gcm = json_decode($gcm, true);
        }
    }

    if ($orderStatusUpdate['status'] == 'Accepted') {
        $orderStatusUpdate['driver_id']  = '';

        if ($this->Order->save($orderStatusUpdate)) {

          if ($orderDetail['Driver']['is_logged'] == 1) {

            $driverMessage = 'Disclaim Order '.$orderDetail['Order']['ref_number'];
            $message       = array('message' => $driverMessage,
                                    'OrderId' => $orderId,
                                    'OrderDetails' => '');
            
            $gcm    = (trim($orderDetail['Driver']['device_name']) == 'ANDROID') ?
                            $this->AndroidResponse->sendOrderByGCM($message, $deviceId) :
                            $this->PushNotifications->notificationIOS($driverMessage, $deviceId);
            $gcm = json_decode($gcm, true);
          }


            $customerMessage = 'Votre commande a été acceptée avec succèss par '.
                                $orderDetail['Store']['store_name'].'. Votre commande sera livrée le '.
                                date("m/d/Y", strtotime($orderDetail['Order']['delivery_date'])). ' à '.$orderDetail['Order']['delivery_time'].'. Merci l’équipe d’ '.$this->siteSetting['Sitesetting']['site_name'];


            $toCustomerNumber = '+'.$this->siteSetting['Country']['phone_code'].$orderDetail['Customer']['customer_phone'];
            $customerSms      = $this->Twilio->sendSingleSms($toCustomerNumber, $customerMessage);
            echo 'Success';
        }
    }
    if ($orderStatusUpdate['status'] == 'Deleted') {
        if ($this->Order->save($orderStatusUpdate)) {
          echo 'Success';
        }
    }


    //Send mail to customer when order accepted,failed,delivered
    if ($_SERVER['HTTP_HOST'] == 'halal-resto.fr') { 
        
        if ($orderStatusUpdate['status'] == 'Accepted' || $orderStatusUpdate['status'] == 'Delivered' || ($orderStatusUpdate['status'] == 'Failed')) {

          $customer_mail = $orderDetail['Order']['customer_email'];
          $customerName  = $orderDetail['Order']['customer_name'];          
          
          if ($orderStatusUpdate['status'] == 'Failed') {
            $mailContent='<table>
              <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
                    <td colspan="3" style="display:inline-block; width:100%;vertical-align: top;">Hi '.$customerName.',</td>
              <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
                    <td colspan="3" style="display:inline-block; width:100%;vertical-align: top;">We are sorry for the inconvience caused,your Order '.$orderDetail['Order']['ref_number'].' has been '.$orderStatusUpdate['status'].' for the reason '.$orderStatusUpdate['failed_reason'].'.</td></table>'; 
          } else {
            $mailContent='<table>
              <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
                    <td colspan="3" style="display:inline-block; width:100%;vertical-align: top;">Hi '.$customerName.',</td>
              <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
                    <td colspan="3" style="display:inline-block; width:100%;vertical-align: top;">Your Order '.$orderDetail['Order']['ref_number'].' has been '.$orderStatusUpdate['status'].'.</td></table>'; 
          }

          
          
          $customerSubject = 'Order '.$orderStatusUpdate['status'];
          $source        = $this->siteUrl.'/siteicons/logo.png';
          $siteName      = $this->siteSetting['Sitesetting']['site_name'];
          $sitemailId    = $this->siteSetting['Sitesetting']['admin_email'];


          $email = new CakeEmail();
          $email->from($sitemailId);
          $email->to($customer_mail);
          $email->subject($customerSubject);
          $email->template('register');
          $email->emailFormat('html');
          $email->viewVars(array('mailContent' => $mailContent,
                       'source' => $source,
                                 'storename' => $siteName));

          $email->send();     
        }  
        
    }


    // Pusher in Admin side
    $message = $orderDetail['Order']['ref_number'].' a été '.$orderStatusUpdate['status'];
    $this->Notifications->pushNotification($message, 'FoodOrderAdmin');
    $this->Notifications->pushNotification($message, 'FoodCustomer_'.$orderDetail['Order']['customer_id']);
    

    // Store Owner Message
    if ($orderDetail['Store']['is_logged'] == 1) {
      
        $deviceId      = $orderDetail['Store']['device_id'];

        if ($this->request->data['status'] == 'Failed') {
          $message = 'This order '.$orderDetail['Order']['ref_number']. ' will be failed. due to '.$this->request->data['reason'].'.';
        } elseif (!empty($deviceId) && $this->request->data['status'] == 'Accepted') {
          $message = 'This order '.$orderDetail['Order']['ref_number']. ' was disclaimed.';
        } else{
           $message = 'This order '.$orderDetail['Order']['ref_number']. ' will be '.strtolower(trim($this->request->data('status'))).'.';
        }
        $gcm = $this->AndroidResponse->sendOrderByGCM(array('message' => $message),$deviceId);
    }
    exit();
  }

  // Restaurant Pickup order lists
  public function store_collectionOrder(){
      $this->layout = 'assets';
      $id           = $this->Auth->User();
      $order_list = $this->Order->find('all', array(
                            'conditions' => array('Order.status' => 'Accepted',
                                                  'Order.store_id'=>$id['Store']['id'],
                                                  'Order.order_type' => 'Collection'),
                            'order' => array('Order.id DESC')));

      $status = array('Pending' => 'Accepté', 'Failed' => 'Annulé', 'Delivered' => 'Livré');

      $this->set(compact('order_list', 'status'));
  }

  // Reataurant failed order list
  public function store_failedOrderDetail(){
      $this->layout = 'assets';
      $id           = $this->Auth->User();
      $order_list = $this->Order->find('all', array(
                              'conditions' => array('Order.status' => 'Failed',
                                                    'Order.store_id'=>$id['Store']['id']),
                              'order' => array('Order.id DESC')));
      $status = array('Pending' => 'Pending', 'Accepted' => 'Accepted',
                      'Failed' => 'Failed', 'Delivered' => 'Delivered');
      $this->set(compact('order_list', 'status'));
  }

  // Order mail
  public function ordermail($orderId) {

    $datas    = $this->Order->findById($orderId);
    $store_id = $datas['Order']['store_id'];

    // Pusher in Store and Admin
    $message = 'Une nouvelle commande a été passée - '.$datas['Order']['ref_number'];
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
              Nom du Menu</th>
            <th style="font:bold 14px/30px Arial;background:#09a925;color:#ffffff; padding:8px; border:1px solid #d9d9d9;border-right:0;" width="5%">
              Qté</th>
            <th style="font:bold 14px/30px Arial;background:#09a925;color:#ffffff; padding:8px; border:1px solid #d9d9d9;border-right:0;" width="5%">
              Prix</th>
            <th style="font:bold 14px/30px Arial;background:#09a925;color:#ffffff; padding:8px; border:1px solid #d9d9d9;border-right:0;" width="12%">
              Prix Global</th>
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

      $subaddons = '';
      if(!empty($data['subaddons_name'])) {
        $subaddons = '<br>'.$data['subaddons_name'];
      }

      $product_description = '';
      if(!empty($data['product_description'])) {
        $product_description = '<br>'.$data['product_description'];
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
              Sous-Total</td>
          <td style="text-align:right; padding-right:10px;font:16px/30px Arial; border:1px solid #09a925;border-top:0;border-right:0;">'.
            $Currency." ".$datas['Order']['order_sub_total'] .'</td>
      </tr>';


    if ($datas['Order']['offer_amount'] > 0) {
      $name.='<tr>
            <td colspan="4" style="text-align:right; padding-right:10px;font:bold 16px/30px Arial; border:1px solid #09a925;border-right:0;border-top:0;border-left:0;">
                Offre ('.$datas['Order']['offer_percentage'].' %) </td>
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
                T.V.A  ('.$datas['Order']['tax_percentage'].' %)</td>
            <td style="text-align:right; padding-right:10px;font:16px/30px Arial; border:1px solid #09a925;border-top:0;border-right:0;">'.
              $Currency." ".$datas['Order']['tax_amount'] .'</td>
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

    $orderType = ($datas['Order']['order_type'] == 'Collection') ? 'Pickup' : 'Livraison';
    $delTime   = ($datas['Order']['order_type'] == 'Collection') ? 'A emporter Time' : 'Heure de livraison';
    $payment_type   = ($datas['Order']['payment_type'] == 'cod') ? 'paiement espèce' : (($datas['Order']['payment_type'] == 'Card') ? 'Carte bancaire' : $datas['Order']['payment_type']);

    $deldatetime = date('Y/m/d',strtotime($datas['Order']['delivery_date'])).' '.$datas['Order']['delivery_time'];

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
                          <span style="width:45%; display:inline-block;font:15px Arial; margin:5px 0; padding-left:10px;">'.
                            $datas['Order']['ref_number'].'
                          </span> 
                      </div>
                      <div style="width:100%; display:inline-block;">
                        <span style="width:45%; display:inline-block;font:bold 15px Arial; text-align:right; margin:5px 0;">
                          Moyen de paiement :
                        </span>
                        <span style="width:45%; display:inline-block;font:15px Arial; margin:5px 0; padding-left:10px;">'.
                          str_ireplace('cod', 'Cash on delivery', $datas['Order']['payment_type']) .'
                        </span> 
                      </div>
                      <div style="width:100%; display:inline-block;">
                        <span style="width:45%; display:inline-block;font:bold 15px Arial; text-align:right; margin:5px 0;">
                            Type de commande : 
                         </span>
                        <span style="width:45%; display:inline-block;font:15px Arial; margin:5px 0; padding-left:10px;"> '.
                          $orderType.'
                        </span> 
                      </div>  
                      <div style="width:100%; display:inline-block;">
                        <span style="width:45%; display:inline-block;font:bold 15px Arial; text-align:right; margin:5px 0;">
                          '.$delTime.':
                        </span>
                        <span style="width:45%; display:inline-block;font:15px Arial; margin:5px 0; padding-left:10px;"> '.$deldatetime.'
                        </span>
                      </div>
                      '.$order_description.'
                    </div>
                    </div>
                    <div style="width:45%; display:inline-block;border-left:1px dotted #09a925;min-height:200px; padding-left:30px;vertical-align:top;">
                      <div style="width:100%; display:inline-block;">
                        <h3 style="font-family:Arial; color:#09a925;" >
                          Adresse
                        </h3>
                      </div>
                      <div style="width:100%; display:inline-block;">
                        <span style="width:100%; display:inline-block;font:bold 15px Arial;margin:5px 0;">'.
                          $datas['Order']['customer_name'].'
                        </span>';
                      if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
                        $Address .= '<span style="width:100%; display:inline-block;font:15px Arial; margin:5px 0;">'.
                          $datas['Order']['address']." ".
                          $datas['Order']['location_name']. ','.
                          $datas['Order']['city_name'].'
                          </span>
                          <span style="width:100%; display:inline-block;font:15px Arial; margin:5px 0;">'.
                              $datas['Order']['state_name'] . " - " .
                              $this->siteSetting['Country']['country_name'].'
                          </span>';
                      } else {
                          $Address .= '<span style="width:100%; display:inline-block;font:15px Arial; margin:5px 0;">'.
                              $datas['Order']['google_address'].'
                          </span>';
                      }
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

    // echo "<pre>";print_r($mailContent);echo "</pre>";exit;
    
    $email->send();

    if ($datas['Store']['email_order'] == 'Yes' && !empty($datas['Store']['order_email'])) {


      $storemailId   = $datas['Store']['order_email'];
      $mailContent   = $sellerContent;
      $mailContent   = str_replace("{Customer name}", $customerName, $mailContent);
      $mailContent   = str_replace("{source}", $source, $mailContent);
      $mailContent   = str_replace("{Store name}", $storename, $mailContent);
      $mailContent   = str_replace("{orderid}", $datas['Order']['ref_number'], $mailContent);
      $mailContent   = str_replace("{note}", $name, $mailContent);
      $mailContent   = str_replace("{Address}", $Address, $mailContent);
      $mailContent   = str_replace("{SITE_URL}", $siteUrl, $mailContent);
      $sellerSubject = str_replace("[ Order ID ]", $datas['Order']['ref_number'], $sellerSubject);
      $sellerSubject = str_replace("customer name", $customerName, $sellerSubject);
      $storename     = $this->siteSetting['Sitesetting']['site_name'];

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
    // return true;
  }

  // Card Payment refun process
  public function admin_refund($orderId, $failed = null) {

    $orderDetail = $this->Order->find('first', array(
                                  'conditions' => array('Order.id' => $orderId,
                                                        'StripeRefund.id' => '')));
    if (!empty($orderDetail)) {

      if (!empty($orderDetail['Order']['transaction_id'])) {

        if ($orderDetail['Order']['payment_type'] == 'Card' &&
            $orderDetail['Order']['payment_method'] == 'paid' &&
            $orderDetail['Order']['status'] != 'Delivered') {

          $data   = array('refund' => $orderDetail['Order']['transaction_id'],
                          'amount' => $orderDetail['Order']['order_grand_total']*100);
          $stripeResponse = $this->Stripe->refund($data);

          if (is_array($stripeResponse)) {

            $stripeRefund['charge_id']     = $stripeResponse[0]['charge'];
            $stripeRefund['refund_id']     = $stripeResponse[0]['id'];
            $stripeRefund['refund_amount'] = $stripeResponse[0]['amount']/100;
            $stripeRefund['store_id']      = $orderDetail['Order']['store_id'];
            $stripeRefund['order_id']      = $orderDetail['Order']['id'];
            $stripeRefund['customer_id']   = $orderDetail['Order']['customer_id'];

            $this->StripeRefund->save($stripeRefund, null, null);

            $orderStatusUpdate['Order']['id']             = $orderId;
            $orderStatusUpdate['Order']['payment_method'] = 'unpaid';
            $orderStatusUpdate['Order']['status']         = 'failed';
            $this->Order->save($orderStatusUpdate);

            $this->admin_refundNotification($orderId);

            echo 'Success';

          } else {
            echo $stripeResponse;
          }
        }
      } else {

        if ($orderDetail['Order']['payment_type'] == 'Wallet' &&
            $orderDetail['Order']['payment_method'] == 'paid' &&
            $orderDetail['Order']['status'] != 'Delivered') {

            $stripeRefund['charge_id']     = 'GNC'.$orderDetail['Order']['customer_id'].time();
            $stripeRefund['refund_id']     = 'GNC'.time();
            $stripeRefund['refund_amount'] = $orderDetail['Order']['order_grand_total'];
            $stripeRefund['store_id']      = $orderDetail['Order']['store_id'];
            $stripeRefund['order_id']      = $orderDetail['Order']['id'];
            $stripeRefund['customer_id']   = $orderDetail['Order']['customer_id'];

            $this->StripeRefund->save($stripeRefund, null, null);

            $orderStatusUpdate['Order']['id']             = $orderId;
            $orderStatusUpdate['Order']['payment_method'] = 'unpaid';
            $orderStatusUpdate['Order']['status']         = 'failed';
            $this->Order->save($orderStatusUpdate);

            // Customer Wallet Update
            $orderDetail['Customer']['wallet_amount'] += $orderDetail['Order']['order_grand_total'];
            $this->Customer->save($orderDetail['Customer']);

            // Wallet History
            $walletHistory['amount']              = $orderDetail['Order']['order_grand_total'];
            $walletHistory['purpose']             = 'Money refunded in wallet';
            $walletHistory['customer_id']         = $orderDetail['Order']['customer_id'];
            $walletHistory['transaction_details'] = $orderDetail['Order']['ref_number'];

            $this->WalletHistory->save($walletHistory, null, null);

            $this->admin_refundNotification($orderId);
            echo 'Success';

        }

      }
    }
    if (!empty($failed)) {
      return true;
    } else {
      exit();
    }
  }

  // Refund Sms Mail and Notifications for card and wallet
  public function admin_refundNotification($orderId) {

    $orderDetail = $this->Order->find('first', array(
                                  'conditions' => array('Order.id' => $orderId,
                                                        'Order.status' => 'Failed')));

    if (!empty($orderDetail)) {

      $message        = 'Order cancelled - '.$orderDetail['Order']['ref_number'];
      $androidMessage = array('message' => $message);
      $storeDeviceId  = $orderDetail['Store']['device_id'];
      $driverDeviceId = $orderDetail['Driver']['device_id'];
      $customerDevice = $orderDetail['Customer']['device_id'];

      // Store Notification
      if (!empty($storeDeviceId) && $orderDetail['Store']['is_logged'] == 1) {
        $gcm  = $this->AndroidResponse->sendOrderByGCM(array('message'    => $message),
                                                              $deviceId);
      }

      // Driver Notification
      if (!empty($driverDeviceId) && $orderDetail['Driver']['is_logged'] == 1) {
        $gcm  = (trim($orderDetail['Driver']['device_name']) == 'ANDROID') ?
                          $this->AndroidResponse->sendOrderByGCM($androidMessage, $driverDeviceId) :
                          $this->PushNotifications->notificationIOS($message, $driverDeviceId);
      }

      $source           = $this->siteUrl.'/siteicons/logo.png';
      $sitemailId       = $this->siteSetting['Sitesetting']['admin_email'];
      $customerSubject  = 'Order Cancel and Refund';
      $Currency         = $this->siteSetting['Country']['currency_symbol'];
      $customer_mail    = $orderDetail['Order']['customer_email'];
      $storeEmail       = $orderDetail['Store']['order_email'];
      $siteName         = $this->siteSetting['Sitesetting']['site_name'];
      $storename        = $orderDetail['Store']['store_name'];


      $mailContent = '<table class="container content" align="center"><tbody><tr> <td>
                          <table class="row note">
                            <tbody> <tr> <td class="wrapper last">
                                      <p>Cher '.$orderDetail['Order']['customer_name'].',</p>
                                      <p>La commande '.$orderDetail['Order']['ref_number'].' a été annulée, nous procédons à votre  remboursement pour un montant de '. $Currency.' '. $orderDetail['Order']['order_grand_total'].'</p> </td> </tr>
                                </tbody>
                          </table> 
                      </table>';
      /*$mailContent = 'Your order '.$orderDetail['Order']['ref_number'].' has been cancelled and Refund your amount '. $Currency.' '. $orderDetail['Order']['order_grand_total'];*/

      // Customer Refund Sms
      $customerMessage  = $mailContent. ' Regards '.$this->siteSetting['Sitesetting']['site_name'];
      $toCustomerNumber = '+'.$this->siteSetting['Country']['phone_code'].$orderDetail['Order']['customer_phone'];
      $customerSms      = $this->Twilio->sendSingleSms($toCustomerNumber, $customerMessage);

      // Customer Refund Mail
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

      /*$mailContent = 'This order '.$orderDetail['Order']['ref_number'].' has been cancelled and Refund amount '. $Currency.' '. $orderDetail['Order']['order_grand_total'];*/

      $mailContent = 'La commande '.$orderDetail['Order']['ref_number'].' a été annulée, nous procédons à votre  remboursement pour un montant de '. $Currency.' '. $orderDetail['Order']['order_grand_total'].'';

      if ($orderDetail['Store']['email_order'] == 'Yes' && !empty($orderDetail['Store']['order_email'])) {

        // Store Refund Mail
        $email = new CakeEmail();
        $email->from($sitemailId);
        $email->to($storeEmail);
        $email->subject($customerSubject);
        $email->template('ordermail');
        $email->emailFormat('html');
        $email->viewVars(array('mailContent' => $mailContent,
                                'source' => $source,
                                'storename' => $siteName));
        $email->send();
      }

      // Store Refund Sms
      if ($orderDetail['Store']['sms_option'] == 'Yes' && !empty($orderDetail['Store']['sms_phone'])) {
        $storeMessage   = $mailContent. ' Thanks '.$this->siteSetting['Sitesetting']['site_name'];
        $toStoreNumber  = '+'.$this->siteSetting['Country']['phone_code'].$orderDetail['Store']['sms_phone'];
        $storeSms       = $this->Twilio->sendSingleSms($toStoreNumber, $storeMessage);
      }
    }

    return true;
    exit();
  }

  // Refund Sms Mail and Notifications
  public function admin_sendNotificationCod($orderId) {



    $orderDetail = $this->Order->find('first', array(
                                  'conditions' => array('Order.id' => $orderId,
                                                        'Order.status' => 'Failed')));


    if (!empty($orderDetail)) {

      $message        = 'Order cancelled - '.$orderDetail['Order']['ref_number'];
      $androidMessage = array('message' => $message);
      $storeDeviceId  = $orderDetail['Store']['device_id'];
      $driverDeviceId = $orderDetail['Driver']['device_id'];
      $customerDevice = $orderDetail['Customer']['device_id'];

      // Store Notification
      if (!empty($storeDeviceId) && $orderDetail['Store']['is_logged'] == 1) {
        $gcm  = $this->AndroidResponse->sendOrderByGCM(array('message'    => $message),
                                                              $deviceId);
      }

      // Driver Notification
      if (!empty($driverDeviceId) && $orderDetail['Driver']['is_logged'] == 1) {
        $gcm  = (trim($orderDetail['Driver']['device_name']) == 'ANDROID') ?
                          $this->AndroidResponse->sendOrderByGCM($androidMessage, $driverDeviceId) :
                          $this->PushNotifications->notificationIOS($message, $driverDeviceId);
      }

      $source           = $this->siteUrl.'/siteicons/logo.png';
      $sitemailId       = $this->siteSetting['Sitesetting']['admin_email'];
      $customerSubject  = 'Order Cancel';
      $Currency         = $this->siteSetting['Country']['currency_symbol'];
      $customer_mail    = $orderDetail['Order']['customer_email'];
      $storeEmail       = $orderDetail['Store']['order_email'];
      $siteName         = $this->siteSetting['Sitesetting']['site_name'];
      $storename        = $orderDetail['Store']['store_name'];

      $mailContent = '<table class="container content" align="center"><tbody><tr> <td>
                              <table class="row note">
                                <tbody> <tr> <td class="wrapper last">
                                          <p>Cher '.$orderDetail['Order']['customer_name'].',</p>
                                          <p>La commande '.$orderDetail['Order']['ref_number'].' a été annulée</p> </td> </tr>
                                    </tbody>
                              </table> 
                          </table>';

      // Customer Refund Sms
      $customerMessage  = $mailContent. ' Regards '.$this->siteSetting['Sitesetting']['site_name'];
      $toCustomerNumber = '+'.$this->siteSetting['Country']['phone_code'].$orderDetail['Order']['customer_phone'];
      $customerSms      = $this->Twilio->sendSingleSms($toCustomerNumber, $customerMessage);

      // Customer Refund Mail
      $email = new CakeEmail();
      $email->from($sitemailId);
      $email->to($customer_mail);
      $email->subject($customerSubject);
      $email->template('ordermail');
      $email->emailFormat('html');
      $email->viewVars(array('mailContent' => $mailContent,
                              'source' => $source,
                              'storename' => $storename));

      // echo "---------".$mailContent;echo "<br>";

      $email->send();

      $mailContent = 'La commande '.$orderDetail['Order']['ref_number'].' a été annulée';

      if ($orderDetail['Store']['email_order'] == 'Yes' && !empty($orderDetail['Store']['order_email'])) {

        // Store Refund Mail
        $email = new CakeEmail();
        $email->from($sitemailId);
        $email->to($storeEmail);
        $email->subject($customerSubject);
        $email->template('ordermail');
        $email->emailFormat('html');
        $email->viewVars(array('mailContent' => $mailContent,
                                'source' => $source,
                                'storename' => $siteName));
        $email->send();
      }

      // Store Refund Sms
      if ($orderDetail['Store']['sms_option'] == 'Yes' && !empty($orderDetail['Store']['sms_phone'])) {
        $storeMessage   = $mailContent. ' Thanks '.$this->siteSetting['Sitesetting']['site_name'];
        $toStoreNumber  = '+'.$this->siteSetting['Country']['phone_code'].$orderDetail['Store']['sms_phone'];
        $storeSms       = $this->Twilio->sendSingleSms($toStoreNumber, $storeMessage);
      }
    }

    return true;
    exit();
  }

  public function orderFilterReport($storeId = null, $rangeId = null, $driverId = null) {

      $today       = date('D', strtotime( "today" ));
      $thisWeek    = ($today == 'Mon') ? $today : date("Y-m-d", strtotime("last Monday"));
      $thisMonth   = date('Y-m-d', strtotime("first day of this month"));
      $currentDate = date('Y-m-d');
      $thisYear    = date('Y').'-01-01';
      $rangeId     = ($today == 'Mon' && $rangeId == 2) ? 1 : $rangeId;
      $condition   = array('conditions' => array('Order.status' => array('Delivered', 'Failed')),
                            'order' => array('Order.id DESC'));

      switch ($rangeId) {

        // Today
        case '1':
          $condition['conditions']['Order.delivery_date'] = $currentDate;
        break;

        // This Week
        case '2':
          $condition['conditions']['Order.delivery_date between ? and ?'] = array($thisWeek, $currentDate);
        break;

        // This Month
        case '3':
          $condition['conditions']['Order.delivery_date between ? and ?'] = array($thisMonth, $currentDate);
        break;

        // This Year
        case '4':
          $condition['conditions']['Order.delivery_date between ? and ?'] = array($thisYear, $currentDate);
        break;
        
        default:
          
        break;
      }

      if (!empty($driverId)) {
          $condition['conditions']['Order.driver_id'] = $driverId;
      }

      if (!empty($storeId)) {
          $condition['conditions']['Order.store_id'] = $storeId;
          $driverLists = $this->Driver->find('all', array(
                                'fields' => array('id', 'driver_name','driver_phone'),
                                'conditions' => array('NOT' => array('Driver.status' => 'Delete'),
                                  'OR' => array('Driver.store_id' => array($storeId, 0)))));
      } else {
          $driverLists = $this->Driver->find('all', array(
                                'fields' => array('id', 'driver_name', 'driver_phone'),
                                'conditions' => array('NOT' => array('Driver.status' => 'Delete'))));
      }

      foreach ($driverLists as $key => $value) {
          $driverList[$value['Driver']['id']] = $value['Driver']['driver_name'].'/'.$value['Driver']['driver_phone'];
      }

      $this->Order->recursive = 0;
      $order_list = $this->Order->find('all', $condition);

      return array($order_list, $driverList);
  }
} 