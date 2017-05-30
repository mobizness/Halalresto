<?php

/* MN */

App::uses('AppController','Controller');
App::uses('OrdersController', 'Controller');
App::import('Controller', 'Commons');

class AjaxActionController extends AppController {
    
    public $uses = array('User','Order','Driver', 'Orderstatus', 'Store', 'Cuisine',
                         'StoreCuisine', 'Mainaddon', 'ProductAddon', 'StripeCustomer',
                         'MyFavourite');
    
    public $components = array('Googlemap');
        
    var $helpers = array('Html', 'Session', 'Javascript', 'Ajax', 'Common');
    
    public function beforeFilter() {
		$this->Auth->allow(array('*'));
		parent::beforeFilter();
	}
    
    /**
     * Get values via ajax calls with actions
     */
    public function index() {
        
        if ($this->request->is('post')) {
            $this->set('Action', $this->request->data['Action']);
            switch ($this->request->data['Action']) {

                case 'orderManage' :
                    $status = array('Delivered','Pending','Failed');
                    $this->Order->recursive = 0;
                    if ($this->Auth->User('Store.id') != '') {

                        $orders = $this->Order->find('all', array(
                                            'conditions' => array('Order.store_id' => $this->Auth->User('Store.id'),
                                                                  'Order.order_type' => 'Delivery',
                                                            'NOT' => array('Order.status' => $status)),
                                            'fields' => array('Order.status','Driver.driver_name'),
                                            'order' => array('Order.id' => 'DESC')));

                        foreach ($orders as $key => $value) {
                            if ($orders[$key]['Order']['status'] == 'Waiting') {
                                $orders[$key]['Order']['status'] = 'En attente';
                            } elseif ($orders[$key]['Order']['status'] == 'Accepted') {
                                $orders[$key]['Order']['status'] = 'Accepté';
                            } elseif ($orders[$key]['Order']['status'] == 'Driver Accepted') {
                                $orders[$key]['Order']['status'] = 'Coursier Accepté';
                            } elseif ($orders[$key]['Order']['status'] == 'Collected') {
                                $orders[$key]['Order']['status'] = 'Emporté';
                            } else {
                                $orders[$key]['Order']['status'] = $orders[$key]['Order']['status'];
                            }
                        }
                        

                        $ordersComplete = $this->Order->find('all', array(
                                            'conditions' => array('Order.status' => 'Delivered',
                                                                 'Order.order_type' => 'Delivery',),
                                            'limit' => 5,
                                            'order' => array('Order.id' => 'DESC')));
                    } else {

                        $orders = $this->Order->find('all', array(
                                            'conditions' => array('Order.order_type' => 'Delivery',
                                                    'NOT' =>array('Order.status' => $status)),
                                            'fields' => array('Order.status','Driver.driver_name'),
                                            'order' => array('Order.id' => 'DESC')));

                        foreach ($orders as $key => $value) {
                            if ($orders[$key]['Order']['status'] == 'Waiting') {
                                $orders[$key]['Order']['status'] = 'En attente';
                            } elseif ($orders[$key]['Order']['status'] == 'Accepted') {
                                $orders[$key]['Order']['status'] = 'Accepté';
                            } elseif ($orders[$key]['Order']['status'] == 'Driver Accepted') {
                                $orders[$key]['Order']['status'] = 'Coursier Accepté';
                            } elseif ($orders[$key]['Order']['status'] == 'Collected') {
                                $orders[$key]['Order']['status'] = 'Emporté';
                            } else {
                                $orders[$key]['Order']['status'] = $orders[$key]['Order']['status'];
                            }
                        }

                        $ordersComplete = $this->Order->find('all', array(
                                            'conditions' => array('Order.status' => 'Delivered',
                                                                 'Order.order_type' => 'Delivery',),
                                            'limit' => 5,
                                            'order' => array('Order.id' => 'DESC')));
                    }
                    $orders = json_encode($orders);
                    $ordersComplete = json_encode($ordersComplete);
                    echo $orders.'@@@@'.$ordersComplete;
                    exit();
                break;

                case 'TrackingOrder':
                    $Order = new OrdersController();
                    $track = $Order->orderDetails($this->request->data['OrderId']);
                    
                    if (strtolower($track['0']['Statuses']['status']) == 'new') {
                        $drivers = $this->Driver->find('all',array( 
                                'conditions' => array(
                                    'dr_log_in' => '1',
                                    'Drivertracking.order_id' => '0'
                                ),
                                'group' => array(
                                    'Driver.id'
                                )
                            )
                        );
                    }

                    $this->set('orders',$track);
                    if (isset($drivers)) {
                        $this->set(compact('drivers'));
                    }
                break;


                case 'LoadTrackingMap':
                    //$this->Order->recursive = 1;
                    $track = $this->Order->findById($this->request->data['OrderId']);
                    
                    if (strtolower($track['Order']['status']) == 'accepted') {

                        if ($this->Auth->User('role_id') == 1) {

                            $drivers = $this->Driver->find('all', array(
                                    'conditions' => array('Vehicle.vehicle_name !=' => '',
                                                          'Driver.driver_status' => 'Available',
                                        'OR' => array('Driver.store_id' => array($track['Order']['store_id'], 0))),
                                    'group' => array('Driver.id')));
                        } else {

                            $store_id = $this->Auth->user('Store.id');
                            $drivers = $this->Driver->find('all', array(
                                                'conditions' => array('Vehicle.vehicle_name !=' => '',
                                                                      'Driver.driver_status' => 'Available',
                                                                      'Driver.store_id' => $store_id),
                                                'group' => array('Driver.id')));
                        }
                    }
                    
                    if (strtolower($track['Order']['status']) != 'Accepted' 
                        && strtolower($track['Order']['status']) != 'Delivered') {

                        $Driver = $this->Driver->findById($track['Driver']['id']);

                        if (strtolower($track['Order']['status']) == 'Driver Accepted') {
                            $sourceLat      = $track['DriverTracking']['driver_latitude'];
                            $sourceLong     = $track['DriverTracking']['driver_longitude'];
                            $destinationLat = $track['Order']['source_latitude'];
                            $destinationLong = $track['Order']['source_longitude'];
                            $track['distance'] = $this->Googlemap->getDrivingDistance(
                                                        $sourceLat, $sourceLong,
                                                        $destinationLat, $destinationLong
                                                    );
                        } else {
                            $sourceLat      = $track['Order']['source_latitude'];
                            $sourceLong     = $track['Order']['source_longitude'];
                            $destinationLat = $track['Order']['destination_latitude'];
                            $destinationLong = $track['Order']['destination_longitude'];
                            $track['distance'] = $this->Googlemap->getDrivingDistance(
                                                        $sourceLat, $sourceLong,
                                                        $destinationLat, $destinationLong
                                                    );
                        }

                        $this->set(compact('Driver'));
                    }
                    $this->set('orders',$track);
                    if (isset($drivers)) {
                        $this->set(compact('drivers'));
                    }
                break;



                case 'OrderStatus':
                    $orderId    = $this->request->data('orderId');
                    $orderTrack = array();

                    $status = array('Driver Accepted','Collected','Delivered');


                    $this->Orderstatus->recursive = 2;
                    $orderTrack = $this->Orderstatus->find('all', array(
                                        'conditions' => array(
                                            'Orderstatus.order_id' => $orderId,
                                            'Orderstatus.status' => $status
                                        ),
                                        'order' => 'Orderstatus.id ASC',
                                        'group' => 'Orderstatus.status'
                                    ));

                    $this->set(compact('orderTrack'));
                break;

                case 'InitialTracking':
                break;

                case 'showMap':
                    
                    $Address  = $this->request->data['address'];
                    $ResName  = $this->request->data['resname'];                    

                    $mapDetails = $this->Googlemap->getlatitudeandlongitude($Address);
                    $latitude  = $mapDetails['lat'];
                    $longitude = $mapDetails['long'];
                    $this->set(compact('ResName', 'latitude', 'longitude'));
                break;

                case 'showMapEdit':
                    
                    $Address  = $this->request->data['address'];
                    $ResName  = $this->request->data['resname'];
                    $distance = $this->request->data['distance'] * 1000;

                    $mapDetails = $this->Googlemap->getlatitudeandlongitude($Address);
                    $latitude  = $mapDetails['lat'];
                    $longitude = $mapDetails['long'];
                    $this->set(compact('ResName', 'latitude', 'longitude', 'distance'));
                break;

                case 'showMapAdd':
                    $Address  = $this->request->data['address'];
                    $ResName  = (!empty($this->request->data['resname'])) ? $this->request->data['resname'] : '';
                    $distance = (!empty($this->request->data['distance'])) ?
                        $this->request->data['distance'] * 1609.34 : '';

                    $mapDetails = $this->Googlemap->getlatitudeandlongitude($Address);

                    $latitude  = $mapDetails['lat'];
                    $longitude = $mapDetails['long'];
                    $this->set(compact('ResName', 'latitude', 'longitude', 'distance'));
                break;

                case 'filterResult':
                    $this->layout = false;
                    $Address = $this->request->data('address');
                    $cuisineId = $this->request->data('cuisineid');
                    $cuisine = (!empty($cuisineId)) ? $this->request->data('cuisineid') : '';

                    if (!empty($cuisine)) {
                        $cuisineId = explode(',', trim($cuisine, ','));
                        $this->StoreCuisine->recursive = 0;
                        $getStores = $this->StoreCuisine->find('all', array(
                                                    'conditions' => array('Store.status' => 1,
                                                                    'StoreCuisine.cuisine_id' => $cuisineId),
                                                    'fields' => array('StoreCuisine.id',
                                                                        'StoreCuisine.store_id',
                                                                        'Store.seo_url'),
                                                    'group' => array('Store.id')));
                        $stores = array();
                        foreach ($getStores as $key => $val) {
                            $stores[] = $val['StoreCuisine']['store_id'];
                        }


                        $getStore = $this->Store->find('all', array(
                            'fields' => array(
                                'Store.id',
                                'Store.store_name',
                                'Store.store_logo',
                                'Store.seo_url',
                                'Store.street_address',
                                'Store.store_zip',
                                'Store.store_city',
                                'Store.store_state',
                                'Store.address',
                                'Store.minimum_order',
                                'Store.estimate_time',
                                'Store.delivery_distance',
                                'Store.delivery_charge'
                            ),
                            'conditions' => array(
                                'Store.status' => 1,
                                'Store.id' => $stores,
                            ),
                            'group' => array(
                                'Store.id'
                            )
                        ));


                    } else {
                        $getStore = $this->Store->find('all', array(
                            'fields' => array(
                                'Store.id',
                                'Store.store_name',
                                'Store.store_logo',
                                'Store.street_address',
                                'Store.seo_url',
                                'Store.store_zip',
                                'Store.store_city',
                                'Store.store_state',
                                'Store.address',
                                'Store.minimum_order',
                                'Store.estimate_time',
                                'Store.delivery_distance',
                                'Store.delivery_charge'
                            ),
                            'conditions' => array(
                                'Store.status' => 1
                            ),
                            'group' => array(
                                'Store.id'
                            )
                        ));
                    }

                    if (!empty($getStore)) {
                        foreach ($getStore as $key => $val) {
                            $deliveryDistance = $val['Store']['delivery_distance'];

                            $Duration = $this->Googlemap->getDistance(
                                $Address,
                                $val['Store']['address']
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
                    foreach ($storeDetails as $key => $row)
                    {
                        $distance[$key] = $row['Store']['distance'];
                    }
                    array_multisort($distance, SORT_ASC, $storeDetails);
                    foreach($storeDetails as $keyword => $value) {
                        if(trim($value['Store']['status']) == 'Order') {
                            $restaurants[] = $value;
                        }
                    }

                    foreach($storeDetails as $keyword=>$value) {
                        if(trim($value['Store']['status']) == 'Pre Order') {
                            $restaurants[] = $value;
                        }
                    }

                    $this->set('serachAddress', $Address);
                    $this->set('storeList', $restaurants);
                    $this->set(compact('cuisineName'));
                break;

                case 'getDateTime':
                    $storeId = $this->Session->read("storeId");
                    $currentDate = $this->request->data['date'];

                    $objCommon = new CommonsController;
                    list($content,
                        $openCloseStatus,
                        $firstStatus,
                        $secondStatus) = $objCommon->getDateTime($storeId, $currentDate);

                    $this->set('timeContent', $content);
                    $this->set(compact('openCloseStatus', 'firstStatus', 'secondStatus'));
                break;

                case 'getShowAddons':

                    $productId = (!empty($this->request->data['productId'])) ? $this->request->data['productId'] : '';
                    $storeId = $this->request->data['storeId'];
                    $categoryId = $this->request->data['categoryId'];
                    $priceOption = $this->request->data['priceOption'];
                    $menuLength = $this->request->data['menuLength'];
                    $this->Mainaddon->recursive = 2;
                    $addonsList = $this->Mainaddon->find('all', array(
                        'conditions' => array(
                            'Mainaddon.store_id' => $storeId,
                            'Mainaddon.category_id' => $categoryId,
                            'Mainaddon.status' => 1,
                        )
                    ));

                    $editAddonList = $this->ProductAddon->find('all', array(
                        'conditions' => array(
                            'ProductAddon.product_id' => $productId,
                            'ProductAddon.store_id' => $storeId,
                            'ProductAddon.category_id' => $categoryId
                        )
                    ));
                    if (!empty($editAddonList)) {
                        foreach ($editAddonList as $key => $val) {
                            $selectedAddons[] = $val['ProductAddon']['subaddons_id'];
                        }
                    }

                    $this->set(compact('addonsList', 'selectedAddons', 'editAddonList', 'priceOption',
                        'productId', 'menuLength'));
                break;

                case 'getMenuAddons':
                    $productId = $this->request->data['productId'];
                    $productDetailId = $this->request->data['productDetailId'];

                    $addonsDetails = $this->ProductAddon->find('all', array(
                        'conditions' => array(
                            'product_id' => $productId,
                            'productdetails_id' => $productDetailId
                        )
                    ));

                    $i = 0;
                    $j = 0;
                    foreach ($addonsDetails as $key => $val) {
                        if ($val['Mainaddon']['id'] != $i && !empty($val['Subaddon']['id'])) {
                            $mainAddonArray[$j]['mainaddons_id'] = $val['Mainaddon']['id'];
                            $mainAddonArray[$j]['mainaddons_name'] = $val['Mainaddon']['mainaddons_name'];
                            $mainAddonArray[$j]['mainaddons_count'] = $val['Mainaddon']['mainaddons_count'];
                            $i = $val['Mainaddon']['id'];
                            $j++;
                        }
                    }
                    $this->set(compact('mainAddonArray', 'productId', 'productDetailId'));

                break;

				case 'customerOrderManage':

                    $status = array('Deleted','Delivered');
                    $orders = $ordersComplete = array();
                    if ($this->Auth->User('Customer.id') != '') {

                        $this->Order->recursive = 0;
                        $orders = $this->Order->find('list', array(
                                            'conditions' => array('Order.customer_id' => $this->Auth->User('Customer.id'),
                                                            'NOT' => array('Order.status' => $status)),
                                            'fields' => array('Order.status'),
                                            'order' => array('Order.id' => 'DESC')));


                        foreach ($orders as $key => $value) {

                            if($value == 'Pending') {
                                $orders[$key] = 'En attente';
                            }elseif($value == 'Accepted') {
                                $orders[$key] = 'Accepté';
                            }elseif($value == 'Failed') {
                                $orders[$key] = 'Annulé';
                            } else {
                                $orders[$key] = $orders[$key];
                            }
                        }

                        $ordersComplete = $this->Order->find('all', array(
                                            'conditions' => array('Order.status' => 'Delivered',
                                                            'Order.customer_id' => $this->Auth->User('Customer.id')),
                                            'fields' => array('Order.id', 'Order.status', 'Review.id'),
                                            'order' => array('Order.id' => 'DESC')));

                        foreach ($ordersComplete as $key1 => $value1) {

                            if($value1 == 'Delivered') {
                                $ordersComplete[$key1] = 'Livré';
                            } else {
                                $ordersComplete[$key1] = $ordersComplete[$key1];
                            }
                        }

                    }

                    $orders         = json_encode($orders);
                    $ordersComplete = json_encode($ordersComplete);
                    echo $orders.'@@@@'.$ordersComplete;
                break;

                case 'walletCards':
                    $Stripe_detail = $this->StripeCustomer->find('all',array(
                                            'conditions'=>array('StripeCustomer.customer_id'=>$this->Auth->User('Customer.id')),
                                            'order' => 'StripeCustomer.id DESC'));
                    $this->set(compact('Stripe_detail'));
                break;

                case 'myFavourite':    
                    $type                    = $this->request->data['type'];                    
                    $favList['MyFavourite']['store_id']     = $this->request->data['store_id'];
                    $favList['MyFavourite']['customer_id']  = $this->Auth->User('Customer.id');
                    if($type == 'add') {
                        $this->MyFavourite->save($favList); 
                    } elseif ($type == 'remove') {
                        $this->MyFavourite->deleteAll(array(
                            'MyFavourite.store_id'    => $favList['MyFavourite']['store_id'],
                            'MyFavourite.customer_id' => $favList['MyFavourite']['customer_id']
                        ));
                    }
                break;

                case 'delFavourite':
                    $id = $this->request->data['id'];
                    $this->MyFavourite->delete($id);
                break;
            }
        }
    }

    public function getSubAddonsMultiplePrice($productId, $subaddonId, $priceOption) {

        $addonPriceList = ClassRegistry::init('ProductAddon')->find('all', array(
            'conditions' => array(
                'product_id' => $productId,
                'subaddons_id' => $subaddonId,
                'price_option' => $priceOption,
                'NOT' => array(
                    'productdetails_id' => 0
                )
            )
        ));
        return $addonPriceList;
        die();
    }

    public function getSubAddonsList($mainaddonId, $productId, $productDetailId) {

        $subAddonList = ClassRegistry::init('ProductAddon')->find('all', array(
            'conditions' => array(
                'ProductAddon.product_id' => $productId,
                'ProductAddon.mainaddons_id' => $mainaddonId,
                'ProductAddon.productdetails_id' => $productDetailId,
                'NOT' => array(
                    'ProductAddon.productdetails_id' => 0
                )
            )
        ));

        $i = 0;
        $j = 0;
        foreach ($subAddonList as $key => $val) {
            if ($val['Subaddon']['id'] != $i) {
                $subAddonArray[$j]['subaddons_id'] = $val['Subaddon']['id'];
                $subAddonArray[$j]['subaddons_name'] = $val['Subaddon']['subaddons_name'];
                $i = $val['Subaddon']['id'];
                $j++;
            }
        }

        return $subAddonArray;
    }

    public function singleSubAddonsPrice($mainaddonId, $subaddonId, $productId, $productDetailId) {

        $subAddonPrice = ClassRegistry::init('ProductAddon')->find('all', array(
            'conditions' => array(
                'ProductAddon.product_id' => $productId,
                'ProductAddon.mainaddons_id' => $mainaddonId,
                'ProductAddon.subaddons_id' => $subaddonId,
                'ProductAddon.productdetails_id' => $productDetailId,
                'NOT' => array(
                    'ProductAddon.productdetails_id' => 0
                )
            )
        ));

        return $subAddonPrice;
    }

}