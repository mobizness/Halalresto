<?php

/* MN */

App::uses('AppController', 'Controller');
App::import('Controller', 'Commons');

class CheckoutsController extends AppController {

    var $helpers = array('Html', 'Session', 'Javascript', 'Ajax', 'Common');
    public $uses = array('CustomerAddressBook', 'State', 'ShoppingCart', 'StripeCustomer', 'DeliveryLocation',
        'Storeoffer', 'City', 'Location', 'Store', 'StoreTiming', 'Voucher', 'Order', 'Customer');
    public $components = array('Updown', 'Stripe', 'Functions', 'Googlemap');

    public function beforeFilter() {

        $this->Auth->allow(array('*'));
        parent::beforeFilter();

        $customerState = $this->State->find('list', array(
            'conditions' => array('State.country_id' => $this->siteSetting['Sitesetting']['site_country']),
            'fields' => array('id', 'state_name')));

        $customerCity = $this->City->find('list', array(
            'fields' => array('City.id', 'City.city_name')));

        $customerArea = $this->Location->find('list', array(
            'fields' => array('id', 'area_name')));

        $customerAreaCode = $this->Location->find('list', array(
            'fields' => array('id', 'zip_code')));

        $this->storeId = $storeId = $this->Session->read("storeId");

        $lastsessionid = $this->Session->read("preSessionid");
        $this->SessionId = (!empty($lastsessionid)) ? $lastsessionid : $this->Session->id();

        $this->set(compact('customerState', 'customerCity', 'customerArea', 'customerAreaCode', 'storeId'));
    }

    public function index() {

        $minOrderCheck = $this->storeMinOrderCheck($this->storeId);
        $this->layout = 'frontend';
        $today = date("m/d/Y");

        $addressId = $this->request->data('id');


        $storeDetails = $this->Store->findById($this->storeId);

        if ((empty($storeDetails) || empty($minOrderCheck)) || $this->Auth->User('role_id') != 4) {
            $this->redirect(array('controller' => 'searches', 'action' => 'index'));
        }

        $storeOffers = $this->Storeoffer->find('first', array(
            'conditions' => array('Storeoffer.store_id' => $this->storeId,
                'Storeoffer.status' => 1,
                "Storeoffer.from_date <=" => $today,
                "Storeoffer.to_date >=" => $today),
            'order' => 'Storeoffer.id DESC'));

        $shopCartTotal = $this->ShoppingCart->find('first', array(
            'conditions' => array('ShoppingCart.session_id' => $this->SessionId,
                'ShoppingCart.store_id' => $this->storeId),
            'fields' => array('SUM(ShoppingCart.product_total_price) As productTotal'),
            'group' => array('ShoppingCart.store_id')));

        if (!empty($storeOffers)) {
            if ($storeOffers['Storeoffer']['offer_price'] <= $shopCartTotal[0]['productTotal']) {
                $offerDetails['offerPercentage'] = $storeOffers['Storeoffer']['offer_percentage'];
                $offerDetails['storeOffer'] = $shopCartTotal[0]['productTotal'] * (
                        $storeOffers['Storeoffer']['offer_percentage'] / 100);
            }
        }

        $addresses = $this->CustomerAddressBook->find('all', array(
            'conditions' => array('CustomerAddressBook.customer_id' =>
                $this->Auth->User('Customer.id'),
                'CustomerAddressBook.status' => 1)));

        $stripes = $this->StripeCustomer->find('all', array(
            'conditions' => array('StripeCustomer.customer_id' => $this->Auth->User('Customer.id')),
            'order' => 'StripeCustomer.id DESC'));

        foreach ($stripes as $key => $value) {
            $result = $value['StripeCustomer']['card_brand'] . ' - XXXX XXXX XXXX' . $value['StripeCustomer']['card_number'];
            $id = $value['StripeCustomer']['id'];
            $stripeCards[$id] = $result;
        }


        $currentDate = date('d-m-Y');

        $objCommon = new CommonsController;
        list($content,
                $openCloseStatus,
                $firstStatus,
                $secondStatus) = $objCommon->getDateTime($this->storeId, $currentDate);

        $customerDetails = $this->Customer->find('first', array(
            'conditions' => array('Customer.id' => $this->Auth->User('Customer.id')),
            'recursive' => 0));

        $shopCart = $this->ShoppingCart->find('all', array(
            'conditions' => array('ShoppingCart.session_id' => $this->SessionId,
                'ShoppingCart.store_id' => $this->storeId),
            'order' => array('ShoppingCart.store_id')));




        if ($this->request->data['orderTypeCheck'] == 'Delivery') {
            $customerAddress = $this->CustomerAddressBook->find('first', array(
                'fields' => array(
                    'CustomerAddressBook.city_id',
                    'CustomerAddressBook.location_id'
                ),
                'conditions' => array(
                    'CustomerAddressBook.id' => $addressId
                )
            ));
            $cityId = $customerAddress['CustomerAddressBook']['city_id'];
            $areaId = $customerAddress['CustomerAddressBook']['location_id'];
            $DeliveryLocation = $this->DeliveryLocation->find('first', array(
                'fields' => array(
                    'DeliveryLocation.delivery_charge'
                ),
                'conditions' => array(
                    'DeliveryLocation.store_id' => $this->storeId,
                    'DeliveryLocation.city_id' => $cityId,
                    'DeliveryLocation.location_id' => $areaId
                )
            ));
        }

        $taxDetails['deliveryCharge'] = ($this->siteSetting['Sitesetting']['address_mode'] == 'Google') ?
                $storeDetails['Store']['delivery_charge'] :
                $DeliveryLocation['DeliveryLocation']['delivery_charge'];
        if (!empty($storeDetails['Store']['tax'])) {
            $taxDetails['tax'] = $storeDetails['Store']['tax'];
            $taxDetails['taxAmount'] = $shopCartTotal[0]['productTotal'] * (
                    $storeDetails['Store']['tax'] / 100);
        }
        if (isset($this->request->data['tipPercentage']) && $this->request->data['tipPercentage'] != '') {
            $taxDetails['tipPercentage'] = $this->request->data['tipPercentage'];
        }

        $addresslist = $this->CustomerAddressBook->find('list', array(
            'conditions' => array('CustomerAddressBook.customer_id' => $this->Auth->User('Customer.id'),
                'CustomerAddressBook.status' => 1,
                'NOT' => array('CustomerAddressBook.google_address' => '')),
            'fields' => array('CustomerAddressBook.address_title')));

        $this->set('timeContent', $content);
        $this->set(compact('openCloseStatus', 'firstStatus', 'secondStatus', 'customerDetails', 'shopCart', 'taxDetails', 'offerDetails', 'addresslist', 'addresses', 'storeDetails', 'stripeCards'));
        $this->set('currentDate', $currentDate);
    }

    // Min Order Check
    public function storeMinOrderCheck($storeId) {

        $this->ShoppingCart->recursive = 2;
        $storeProduct = $this->ShoppingCart->find('first', array(
            'conditions' => array('ShoppingCart.session_id' => $this->SessionId,
                'ShoppingCart.store_id' => $storeId,
                'ShoppingCart.order_id' => 0),
            'fields' => array('store_id',
                'COUNT(ShoppingCart.store_id) AS productCount',
                'SUM(ShoppingCart.product_total_price) As productTotal'),
            'group' => array('ShoppingCart.store_id')));

        if (empty($storeProduct) || $storeProduct['Store']['minimum_order'] > $storeProduct[0]['productTotal']) {
            return false;
        }
        return true;
    }

    public function customerBookAdd() {

        if ($this->request->is('post') || $this->request->is('put')) {

            $this->CustomerAddressBook->set($this->request->data);
            if ($this->CustomerAddressBook->validates()) {
                $this->request->data['CustomerAddressBook']['customer_id'] = $this->Auth->User('Customer.id');
                $this->request->data['CustomerAddressBook']['status'] = 1;
                $this->CustomerAddressBook->save($this->request->data['CustomerAddressBook']);
            } else {
                $this->CustomerAddressBook->validationErrors;
            }
        }

        $this->Session->setFlash('<p>' . __('Votre carnet d’adresses a été ajouté avec succès', true) . '</p>', 'default', array('class' => 'alert alert-success'));
        $this->redirect(array('controller' => 'checkouts', 'action' => 'index'));
    }

    public function customerCardAdd() {

        $data = $this->Functions->parseSerialize($this->params['data']['formData']);
        $this->request->data = $data['data'];
        if (!empty($this->request->data['StripeCustomer'])) {
            $datas = array("stripeToken" => $this->request->data['StripeCustomer']['stripe_token_id']);
            $this->request->data['StripeCustomer']['customer_id'] = $this->Auth->User('Customer.id');
            $this->StripeCustomer->save($this->request->data['StripeCustomer']);
            echo $this->StripeCustomer->id;
        }
        exit();
    }

    public function paymentCard() {

        $orderGrandTotal = $this->request->data['payment'];
        $stripeCards = $this->StripeCustomer->find('all', array(
            'conditions' => array('StripeCustomer.customer_id' => $this->Auth->User('Customer.id')),
            'order' => 'StripeCustomer.id DESC'));
        $customerDetails = $this->Customer->find('first', array(
            'conditions' => array('Customer.id' => $this->Auth->User('Customer.id')),
            'recursive' => 0,
            'fields' => array('wallet_amount')));


        $this->set(compact('stripeCards', 'customerDetails', 'orderGrandTotal'));
    }

    public function cardAdd() {
        
    }

    public function locations() {

        $id = $this->request->data['id'];
        $model = $this->request->data['model'];

        switch (trim($model)) {

            case 'State':
                $locations = $this->State->find('list', array(
                    'conditions' => array('State.country_id' => $id),
                    'fields' => array('id', 'state_name')));
                break;

            case 'City':
                $locations = $this->City->find('list', array(
                    'conditions' => array('City.state_id' => $id),
                    'fields' => array('id', 'city_name')));
                break;

            case 'Location':
                if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {

                    $locations = $this->Location->find('list', array(
                        'conditions' => array('Location.city_id' => $id),
                        'fields' => array('id', 'zip_code')));
                } else {
                    $locations = $this->Location->find('list', array(
                        'conditions' => array('Location.city_id' => $id),
                        'fields' => array('id', 'area_name')));
                }
                break;
        }
        $this->set(compact('model', 'locations'));
    }

    public function orderReview() {

        $storeId = $this->storeId;
        $today = date("m/d/Y");
        $addressId = $this->request->data('id');

        $storeDetails = $this->Store->findById($storeId);
        $storeOffers = $this->Storeoffer->find('first', array(
            'conditions' => array('Storeoffer.store_id' => $storeId,
                'Storeoffer.status' => 1,
                "Storeoffer.from_date <=" => $today,
                "Storeoffer.to_date >=" => $today),
            'order' => 'Storeoffer.id DESC'));
        $shopCartTotal = $this->ShoppingCart->find('first', array(
            'conditions' => array('ShoppingCart.session_id' => $this->SessionId,
                'ShoppingCart.store_id' => $storeId),
            'fields' => array('SUM(ShoppingCart.product_total_price) As productTotal'),
            'group' => array('ShoppingCart.store_id')));
        if (!empty($storeOffers)) {
            if ($storeOffers['Storeoffer']['offer_price'] <= $shopCartTotal[0]['productTotal']) {
                $offerDetails['offerPercentage'] = $storeOffers['Storeoffer']['offer_percentage'];
                $offerDetails['storeOffer'] = $shopCartTotal[0]['productTotal'] * (
                        $storeOffers['Storeoffer']['offer_percentage'] / 100);
            }
        }
        if ($this->request->data['orderTypeCheck'] == 'Delivery') {
            $customerAddress = $this->CustomerAddressBook->find('first', array(
                'fields' => array(
                    'CustomerAddressBook.city_id',
                    'CustomerAddressBook.location_id'
                ),
                'conditions' => array(
                    'CustomerAddressBook.id' => $addressId
                )
            ));
            $cityId = $customerAddress['CustomerAddressBook']['city_id'];
            $areaId = $customerAddress['CustomerAddressBook']['location_id'];
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

            $taxDetails['deliveryCharge'] = ($this->siteSetting['Sitesetting']['address_mode'] == 'Google') ?
                    $storeDetails['Store']['delivery_charge'] :
                    $DeliveryLocation['DeliveryLocation']['delivery_charge'];
        }
        if (!empty($storeDetails['Store']['tax'])) {
            $taxDetails['tax'] = $storeDetails['Store']['tax'];
            $taxDetails['taxAmount'] = $shopCartTotal[0]['productTotal'] * (
                    $storeDetails['Store']['tax'] / 100);
        }
        if (isset($this->request->data['tipPercentage']) && $this->request->data['tipPercentage'] != '') {
            $taxDetails['tipPercentage'] = $this->request->data['tipPercentage'];
        }
        $shopCart = $this->ShoppingCart->find('all', array(
            'conditions' => array('ShoppingCart.session_id' => $this->SessionId,
                'ShoppingCart.store_id' => $storeId),
            'order' => array('ShoppingCart.store_id')));

        $this->set(compact('shopCart', 'offerDetails', 'taxDetails', 'storeDetails'));
    }

    public function deliveryLocation() {


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
                'ShoppingCart.session_id' => $this->SessionId,
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
                    if ($shopCartDetails[0]['Subtotal'] < $storeAddress[0]['dl']['minimum_order']) {
                        echo "<label class='error'>" . __("Your should order above " . $storeAddress[0]['dl']['minimum_order']) . "</label><br>";
                    }
                } else {
                    echo "<label class='error'>" . $shopCartDetails['Store']['store_name'] . " " . __("don't deliver to your address please select another one") . "</label><br>";
                }
            }
        } else {
             $deliveryChargesTotal = 0;
            if ($orderTypes == 'Delivery') {
                $deliveryAddress = $locationDetails['CustomerAddressBook']['google_address'];
                $storeAddress = $shopCartDetails['Store']['address'];
                $deliveryDistance = $shopCartDetails['Store']['delivery_distance'];

                $storeId = $this->storeId;
                $storeAddress = $this->DeliveryLocation->query("SELECT * FROM delivery_locations as dl WHERE "
                        . "dl.store_id = $storeId AND '$deliveryAddress' LIKE CONCAT('%',city_id,'%') AND status = 1 LIMIT 1");

                $Duration = $this->Googlemap->getDistance(
                        $deliveryAddress, $storeAddress[0]['dl']['city_id']
                );

               
                if (empty($storeAddress)) {
                    $this->Session->write('customerDeliveryAddress', '');
                    $this->Session->write('customerdeliveryAddressCharge', 0);
                    echo "fail";
                } else {
                    $deliveryDistance = $storeAddress[0]['dl']['estimate_delivery_time'];
                    if ($deliveryDistance < $Duration) {
                        echo "fail";
                        exit();
                    }
                    if ($shopCartDetails[0]['Subtotal'] < $storeAddress[0]['dl']['minimum_order']) {
                        $deliveryChargesTotal = $storeAddress[0]['dl']['minimum_order'];
                    }
                    $this->Session->write('customerDeliveryAddress', $deliveryAddress);
                    $this->Session->write('customerdeliveryAddressCharge', $storeAddress[0]['dl']['delivery_charge']);
                    echo $deliveryChargesTotal;
                }
            }else {
                echo $deliveryChargesTotal;
            }
        }
        exit();
    }

    // voucherCheck
    public function voucherCheck() {

        $Today = date("m/d/Y");
        $voucherCode = $this->request->data['voucherCode'];
        $storeId = $this->request->data['storeId'];
        $orderType = $this->request->data['orderType'];
        $addressId = $this->request->data['addressId'];

        $voucherDetails = $this->Voucher->find('first', array(
            'conditions' => array(
                'Voucher.store_id' => $storeId,
                'Voucher.voucher_code' => trim($voucherCode),
                'Voucher.from_date <=' => $Today,
                'Voucher.to_date >=' => $Today)));
        if (!empty($voucherDetails)) {

            if ($voucherDetails['Voucher']['type_offer'] == 'single') {
                $usedCoupon = $this->Order->find('first', array(
                    'conditions' => array(
                        'Order.customer_id' => $this->Auth->User('Customer.id'),
                        'Order.voucher_code' => $voucherCode,
                        'Order.status !=' => 'Failed',
                        'Order.store_id' => $storeId)
                ));

                if (!empty($usedCoupon)) {
                    echo 'Failed@@';
                    echo 'Voucher already used.';
                    exit();
                }
            }

            $cartCount = $this->ShoppingCart->find('first', array(
                'conditions' => array('ShoppingCart.session_id' => $this->SessionId,
                    'ShoppingCart.store_id' => $storeId),
                'fields' => array('COUNT(ShoppingCart.store_id) AS productCount',
                    'SUM(ShoppingCart.product_total_price) As productTotal')));

            if ($voucherDetails['Voucher']['offer_mode'] == 'price') {

                if ($cartCount[0]['productTotal'] <= $voucherDetails['Voucher']['offer_value']) {
                    echo 'Failed@@';
                    echo 'Le code Voucher n’est pas valide.';
                } else {

                    echo 'Sucess@@';
                    echo $this->siteSetting['Country']['currency_symbol'] . ' ' . $voucherDetails['Voucher']['offer_value'] . ' ' . __('discount in your total amount') . '.@@||0||' . $voucherDetails['Voucher']['offer_value'] . '||price';
                }
            } elseif ($voucherDetails['Voucher']['offer_mode'] == 'percentage') {

                $percentage = $voucherDetails['Voucher']['offer_value'];
                $amount = $cartCount[0]['productTotal'] * $voucherDetails['Voucher']['offer_value'] / 100;
                echo 'Sucess@@';
                echo $voucherDetails['Voucher']['offer_value'] . '% ' . __('discount in your total amount') . '.@@||' . $percentage .
                '||' . $amount . '||percentage';
            } else {

                if ($voucherDetails['Voucher']['free_delivery'] == $orderType) {

                    echo 'Sucess@@';

                    if ($this->siteSetting['Sitesetting']['address_mode'] == 'Google') {

                        echo 'Free delivery@@||0||' . $voucherDetails['Store']['delivery_charge'] . '||freeDelivery';
                    } else {
                        $customerAddress = $this->CustomerAddressBook->find('first', array(
                            'fields' => array('CustomerAddressBook.city_id',
                                'CustomerAddressBook.location_id'),
                            'conditions' => array('CustomerAddressBook.id' => $addressId)));

                        $cityId = $customerAddress['CustomerAddressBook']['city_id'];
                        $areaId = $customerAddress['CustomerAddressBook']['location_id'];
                        $DeliveryLocation = $this->DeliveryLocation->find('first', array(
                            'fields' => array('DeliveryLocation.delivery_charge'),
                            'conditions' => array('DeliveryLocation.store_id' => $storeId,
                                'DeliveryLocation.city_id' => $cityId,
                                'DeliveryLocation.location_id' => $areaId)));

                        echo 'Free delivery@@||0||' . $DeliveryLocation['DeliveryLocation']['delivery_charge'] . '||freeDelivery';
                    }
                } else {
                    echo 'Failed@@';
                    echo 'Le code Voucher n’est pas valide.';
                }
            }

            exit();
        } else {
            echo 'Failed@@';
            echo 'Le code Voucher n’est pas valide.';
            exit();
        }
    }

    // Checkout Cart
    public function cart() {

        // echo "<pre>";print_r($this->request->data);echo "</pre>";exit;

        $today = date("m/d/Y");


        $total = $this->ShoppingCart->find('all', array(
            'conditions' => array('ShoppingCart.session_id' => $this->SessionId,
                'ShoppingCart.order_id' => 0,
                'ShoppingCart.store_id' => $this->storeId),
            'fields' => array('SUM(ShoppingCart.product_total_price) AS cartTotal')));

        $storeProduct = $this->ShoppingCart->find('all', array(
            'conditions' => array('ShoppingCart.session_id' => $this->SessionId,
                'ShoppingCart.order_id' => 0,
                'ShoppingCart.store_id' => $this->storeId),
            'fields' => array('store_id',
                'COUNT(ShoppingCart.store_id) AS productCount',
                'SUM(ShoppingCart.product_total_price) As productTotal'),
            'group' => array('ShoppingCart.store_id')));

        $store_id = (!empty($storeProduct)) ?
                $storeProduct[0]['ShoppingCart']['store_id'] :
                '';


        $cartCount = $this->ShoppingCart->find('first', array(
            'conditions' => array('ShoppingCart.session_id' => $this->SessionId,
                'ShoppingCart.order_id' => 0,
                'ShoppingCart.store_id' => $this->storeId),
            'fields' => array('store_id',
                'COUNT(ShoppingCart.store_id) AS productCount',
                'SUM(ShoppingCart.product_total_price) As productTotal')));

        $storeCart = $this->ShoppingCart->find('all', array(
            'conditions' => array('ShoppingCart.session_id' => $this->SessionId,
                'ShoppingCart.order_id' => 0,
                'ShoppingCart.store_id' => $this->storeId),
            'order' => array('ShoppingCart.store_id')));

        $cityId = $this->Session->read('Search.city');
        $areaId = $this->Session->read('Search.area');
        $DeliveryLocation = $this->DeliveryLocation->find('first', array(
            'fields' => array(
                'DeliveryLocation.minimum_order',
                'DeliveryLocation.delivery_charge'
            ),
            'conditions' => array(
                'DeliveryLocation.store_id' => $store_id,
                'DeliveryLocation.city_id' => $cityId,
                'DeliveryLocation.location_id' => $areaId
            )
        ));

        echo (!empty($cartCount[0]['productCount'])) ? $cartCount[0]['productCount'] : 0;
        echo '||@@||';
        echo (!empty($total[0][0]['cartTotal'])) ? $total[0][0]['cartTotal'] : 0;
        echo '||@@||';

        //Offer diplay in cart page
        $shopCartTotal = $this->ShoppingCart->find('first', array(
            'conditions' => array('ShoppingCart.session_id' => $this->SessionId,
                'ShoppingCart.store_id' => $this->storeId),
            'fields' => array('SUM(ShoppingCart.product_total_price) As productTotal'),
            'group' => array('ShoppingCart.store_id')));


        $storeOffers = $this->Storeoffer->find('first', array(
            'conditions' => array('Storeoffer.store_id' => $this->storeId,
                'Storeoffer.status' => 1,
                "Storeoffer.from_date <=" => $today,
                "Storeoffer.to_date >=" => $today),
            'order' => 'Storeoffer.id DESC'));

        if (!empty($storeOffers)) {
            if ($storeOffers['Storeoffer']['offer_price'] <= $shopCartTotal[0]['productTotal']) {
                $offerDetails['offerPercentage'] = $storeOffers['Storeoffer']['offer_percentage'];
                $offerDetails['storeOffer'] = $shopCartTotal[0]['productTotal'] * (
                        $storeOffers['Storeoffer']['offer_percentage'] / 100);
            }
        }

        $storeDetails = $this->Store->findById($this->storeId);

        if (!empty($this->request->data['checkdeltype'])) {
            $this->Session->write('deliveryType', $this->request->data['checkdeltype']);
        } else {
            $this->Session->write('deliveryType', 'Delivery');
        }
        // echo "---------<pre>";print_r($offerDetails);echo "</pre>";exit;


        $minimumOrder = $DeliveryLocation['DeliveryLocation']['minimum_order'];
        $this->set('cartTotal', $total[0][0]['cartTotal']);
        $this->set(compact('storeCart', 'storeProduct', 'cartCount', 'minimumOrder', 'offerDetails', 'storeDetails'));
    }

    public function thanks($id = null) {
        $this->layout = 'frontend';

        if (!empty($this->Auth->User('Customer.id'))) {
            $order_detail = $this->Order->find('first', array(
                'conditions' => array('Order.id' => $id,
                    'Order.customer_id' => $this->Auth->User('Customer.id'))));
        } else {
            $order_detail = $this->Order->find('first', array(
                'conditions' => array('Order.id' => $id)));
        }


        $storeId = ($this->Session->read('storeId')) ? $this->Session->read('storeId') : '';

        if (!empty($order_detail)) {
            $this->set(compact('order_detail', 'storeId'));
        } else {
            $this->render('/Errors/error400');
        }
    }

}
