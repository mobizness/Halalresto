<?php

/* MN */

App::uses('AppController', 'Controller');
App::import('Controller', 'Commons');


class SearchesController extends AppController {

	var $helpers = array('Html', 'Session', 'Javascript', 'Ajax', 'Common');

	public $uses = array('City', 'Location', 'Store', 'Product', 'Category', 'ProductDetail',
						'ShoppingCart', 'Storeoffer', 'Deal', 'DeliveryLocation', 'Review', 
						'State', 'Cuisine', 'StoreCuisine', 'Subaddon', 'ProductAddon');

	public $components = array('Updown', 'Googlemap');

	public function beforeFilter() {

		$this->Auth->allow(array('*'));
		parent::beforeFilter();

		$storeState = $this->State->find('list', array(
								'conditions' => array('State.country_id' => $this->siteSetting['Country']['id']),
								'fields' => array('State.id', 'State.state_name')));

		$this->storeCity = $storeCity = $this->City->find('list', array(
															'fields' => array('City.id', 'City.city_name')));
		if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {
			$this->storeArea = $storeArea = $this->Location->find('list', array(
		     												'fields' => array('id','zip_code')));
		} else {
			$this->storeArea = $storeArea = $this->Location->find('list', array(
		     												'fields' => array('id','area_name')));
		}

		$lastsessionid  = $this->Session->read("preSessionid");
		$this->SessionId = (!empty($lastsessionid)) ? $lastsessionid : $this->Session->id();

		$this->cityId  = $cityId = $this->Session->read('Search.city');
		$this->areaId  = $areaId = $this->Session->read('Search.area');
		$this->address = $this->Session->read('Search.googleAddress');

		$this->storeId = ($this->Session->read('storeId')) ? $this->Session->read('storeId') : '';

		$this->set(compact('storeCity', 'storeArea', 'cityId', 'areaId', 'storeState'));
	}

	public function index() {

		$this->layout = 'frontend';
		$cityList = array();
		
		if ($this->cityId != '') {
			
			$cityDetails = $this->City->findById($this->cityId);
			if (!empty($this->areaId)) {

				$areaDetails = $this->Location->findById($this->areaId);
				$this->redirect($this->siteUrl.'/city/'.$cityDetails['City']['city_name'].'/'.
																$areaDetails['Location']['area_name'].'/'.
																$this->cityId.'/'.
																$this->areaId);
			} else {
				$this->redirect($this->siteUrl.'/city/'.$cityDetails['City']['city_name'].'/'.
																$this->cityId);
			}
			$this->redirect(array('controller' => 'searches', 'action' => 'stores', $this->cityId, $this->areaId));
		}

		if ($this->address != '') {
			$this->redirect(array('controller' => 'searches', 'action' => 'searchByAddress'));
		}

		$this->changeLocation();

		if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
			if (!empty($this->request->data['Search']['city'])) {

				$cityId = $this->request->data['Search']['city'];
				$areaId = $this->request->data['Search']['area'];

				$this->Session->write('Search.city', $cityId);
				$this->Session->write('Search.area', (isset($areaId)) ? $areaId : '');

				$cityDetails = $this->City->findById($cityId);

				if (!empty($areaId)) {

					$areaDetails = $this->Location->findById($areaId);

					$this->redirect($this->siteUrl.'/city/'.$cityDetails['City']['city_name'].'/'.
							$areaDetails['Location']['area_name'].'/'.
							$cityId.'/'.
							$areaId);
				} else {
					$this->redirect($this->siteUrl.'/city/'.$cityDetails['City']['city_name'].'/'.
							$cityId);
				}
			}
		} else {
			if (!empty($this->request->data['Search']['address'])) {
				$searchAddress = $this->request->data['Search']['address'];
				$this->Session->write('Search.googleAddress', $searchAddress);
				$this->redirect($this->siteUrl.'/address');
			}
		}


		$siteCountry = $this->siteSetting['Sitesetting']['site_country'];
		$cityLists 	 = $this->City->find('list', array(
								'conditions' => array('City.country_id' => $siteCountry,
													'City.status' => 1),
								'fields' => array('City.id', 'City.city_name')));

		foreach ($cityLists as $key => $value) {

			$storeDetails = $this->Product->find('first', array(
									'conditions' => array(
														'Store.status' => 1,
														'Store.store_city' => $key,
														'Product.status' => 1,
														'MainCategory.status' => 1,
										'OR' => array('Store.collection' => 'Yes',
												  	  'Store.delivery'	 => 'Yes')),
									
									));

            $deliveryCity = $this->DeliveryLocation->find('all', array(
                'fields' => array(
                    'DeliveryLocation.city_id',
                    'City.city_name'
                ),
                'conditions' => array(
                    'DeliveryLocation.store_id' => $storeDetails['Store']['id']
                ),
                'group' => array(
                    'DeliveryLocation.city_id'
                )
            ));

			if (!empty($storeDetails)) {
				$cityList[$key] = $value;
                if (!empty($deliveryCity)) {
                    foreach ($deliveryCity as $k => $v) {
                        $cityList[$v['DeliveryLocation']['city_id']] = $v['City']['city_name'];
                    }
                }
			}
		}

		$this->set(compact('cityList'));
	}


	public function storesList($cityId = null, $areaId = null) {

		$stores = array();

		/*$storeLists = $this->Product->find('all', array(
							'conditions' => array(
												'Store.status' => 1,
												'Store.store_city' => $cityId,
												'Product.status' => 1,
												'MainCategory.status' => 1,
									'OR' => array('Store.collection' => 'Yes',
										  	  	   'Store.delivery'	 => 'Yes')),
							'group' => array('Store.id')));*/
        $storeLists = $this->DeliveryLocation->find('all', array(
            'conditions' => array(
                'Store.status' => 1,
                'OR' => array(
                    'Store.collection' => 'Yes',
                    'Store.delivery' => 'Yes',
                    'DeliveryLocation.city_id' => $cityId,
                    'Store.store_city' => $cityId,
                    'DeliveryLocation.location_id' => $areaId,
                    'Store.store_zip' => $areaId,
                )
            ),
            'group' => array(
                'Store.id'
            )
        ));

		foreach ($storeLists as $key => $value) {

			if ($value['Store']['collection'] == 'Yes' ||
				$value['Store']['delivery'] == 'Yes' &&
				$value['Store']['delivery_option'] == 'Yes') {

				if (!empty($areaId)) {
					$storeDeliver = $this->DeliveryLocation->find('all', array(
                        'conditions' => array(
                            'DeliveryLocation.location_id' => $areaId,
                            'Location.status' => 1,
                            'DeliveryLocation.store_id' => $value['Store']['id']
                        ),
                        'group' => array(
                            'DeliveryLocation.store_id'
                        )
                    ));

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

		return $stores;
	}

	public function stores($cityName, $cityId, $areaName, $areaId) {

		$this->layout = 'frontend';
		$orderSuccess = '';
		$stores = array();
		
		if ($this->cityId != $cityId || $this->areaId != $areaId) {
			$this->redirect(array('controller' => 'searches', 'action' => 'index'));
		}

		$stores = $this->storesList($this->cityId, $this->areaId);

		//$this->Store->recursive = 0;
		$storeLists = $this->Store->find('all', array(
							'conditions' => array('Store.id' => $stores),
							'fields' 	 => array('Store.id',
							                	  'Store.store_name', 'Store.seo_url',
							                	  'Store.store_logo', 'Store.street_address',
							                	  'Store.store_zip', 'Store.store_city',
							                	  'Store.store_state', 'Store.address',
							                	  'Store.minimum_order', 'Store.estimate_time',
							                	  'Store.delivery_distance', 'Store.delivery_charge'),
							'group' 	 => array('Store.id')));

		foreach ($storeLists as $key => $value) {

			$ratingDetail = $this->Review->find('first', array(
                              	'conditions'=>array('Review.store_id' => $value['Store']['id'],
                              						'Review.status' => 1),
								'fields' => array('SUM(Review.rating) AS rating',
												'Count(Review.rating) AS ratingCount')));

			 // Pre Order or Order
            $objCommon = new CommonsController;
			$currentDate = date('d-m-Y');

			list($content,
					$openCloseStatus,
					$firstStatus,
					$secondStatus) = $objCommon->getDateTime($value['Store']['id'], $currentDate);

			if ($openCloseStatus == 'Open') {
				$value['Store']['status'] = ($firstStatus == 'Open' || $secondStatus == 'Open')
						? 'Order' : 'Pre Order';
			} else {
				$value['Store']['status'] = 'Pre Order';
			}


			$storeList[$key]['Store']   		= $value['Store'];
			$storeList[$key]['StoreCuisine'] 	= $value['StoreCuisine'];

			$storeList[$key]['Store']['rating'] = (!empty($ratingDetail[0]['ratingCount'])) ? 
												$ratingDetail[0]['rating']/$ratingDetail[0]['ratingCount'] * 20 : 0;

            

            $deliveryLocation = $this->DeliveryLocation->find('first', array(
								                'fields' => array(
								                    'DeliveryLocation.minimum_order',
								                    'DeliveryLocation.delivery_charge'
								                ),
								                'conditions' => array(
								                    'DeliveryLocation.store_id' => $value['Store']['id'],
								                    'DeliveryLocation.city_id' => $cityId,
								                    'DeliveryLocation.location_id' => $areaId
								                )
								            ));

           
            $storeList[$key]['Store']['DeliveryLocation'] = $deliveryLocation['DeliveryLocation'];
		}
		
		if (empty($storeList)) {
			$this->Session->setFlash('<p>'.__('Store is not available', true).'</p>', 'default', 
                                          array('class' => 'alert alert-danger'));
			$this->redirect(array('controller' => 'searches', 'action' => 'index'));

		}


		$cuisineName = $this->Cuisine->find('list', array(
            								'fields' => array('id','cuisine_name')));

        $serachAddress = $this->storeCity[$this->cityId];
        $serachAddress .= (!empty($this->areaId)) ? ' '.$this->storeArea[$this->areaId] : '';


		$getStoreCuisine = $this->StoreCuisine->find('all', array(
            						'conditions' => array('Cuisine.status' => 1,
                 							'AND' => array('StoreCuisine.store_id' => $stores)),

						            'fields' => array('StoreCuisine.store_id',
						            				'COUNT(StoreCuisine.cuisine_id) AS cuisineCount',
						            				'StoreCuisine.id', 'StoreCuisine.store_id',
						            				'Cuisine.id', 'Cuisine.cuisine_name'),
						            'order' => array('StoreCuisine.cuisine_id desc'),
						           	'group' => array('StoreCuisine.cuisine_id')));




		$this->set(compact('storeList', 'orderSuccess', 'getStoreCuisine', 'cuisineName', 'serachAddress'));

	}

	public function searchByAddress() {
		
		if (empty($this->address)) {
			$this->redirect(array('controller' => 'searches', 'action' => 'index'));
		}

		$this->layout 	= 'frontend';
		$getStore 	  	= '';
		$serachAddress  = $this->address;

		$this->Product->recursive = 0;
		$storeLists = $this->Product->find('all', array(
							'conditions' => array(
												'Store.status' => 1,
												'Product.status' => 1,
												'MainCategory.status' => 1,
									'OR' => array('Store.collection' => 'Yes',
										  	  	   'Store.delivery'	 => 'Yes')),
							'group' => array('Store.id'),
							'fields' => array('Store.id')));


		foreach ($storeLists as $key => $value) {
			$getStore[$key] = $value;

			$StoreDetails = $this->Store->find('first', array(
											'conditions' => array('Store.id' => $value['Store']['id']),
											'fields' => array('Store.id',
											                'Store.store_name', 'Store.seo_url',
											                'Store.store_logo', 'Store.street_address',
											                'Store.store_zip', 'Store.store_city',
											                'Store.store_state', 'Store.address',
											                'Store.minimum_order', 'Store.estimate_time',
											                'Store.delivery_distance', 'Store.delivery_charge')));

			$getStore[$key]['Store']   		= $StoreDetails['Store'];
			$getStore[$key]['StoreCuisine'] = $StoreDetails['StoreCuisine'];

		}

        /*$getStore = $this->Store->find('all', array(
            'fields' => array(
                'Store.id',
                'Store.store_name',
                'Store.seo_url',
                'Store.store_logo',
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
				'Store.status' => 1
			),
			'group' => array(
				'Store.id'
			)
		));*/
		
		if (!empty($getStore)) {
			foreach ($getStore as $key => $val) {
				$deliveryDistance = $val['Store']['delivery_distance'];

				$Duration = $this->Googlemap->getDistance(
						$serachAddress,
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
            if(trim($value['Store']['status']) == 'Order') {
                $storeList[] = $value;
                $restId[] 	   = $value['Store']['id'];
            }
        }

        foreach($storeDetails as $keyword=>$value) {
            if(trim($value['Store']['status']) == 'Pre Order') {
                $storeList[] = $value;
                $restId[] 	   = $value['Store']['id'];
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

        $this->set(compact('getStoreCuisine', 'serachAddress', 'storeList', 'cuisineName'));
		$this->render('stores');
	}

	public function storeitems($storename, $storeId) {
		$storeId = base64_decode($storeId);
		$this->layout = 'frontend';

		if (!is_numeric($storeId)) {
			$this->redirect(array('controller' => 'searches', 'action' => 'index'));
		}

		if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
			$cityId = $this->Session->read('Search.city');
			$areaId = $this->Session->read('Search.area');

			if (isset($cityId) && empty($cityId)) {
				$storeSession = $this->Store->find('first', array(
						'conditions' => array('Store.id' => $storeId,
								'Store.status' => 1)));
				if (!empty($storeSession)) {
					$this->Session->write('Search.city', $storeSession['Store']['store_city']);
					$cityId = $this->Session->read('Search.city');
				}
			}

            $storeDetails = $this->Store->find('first', array(
                'conditions' => array(
                    'Store.id' => $storeId,
                    'Store.status' => 1
                )
            ));
		} else {
            $storeDetails = $this->Store->find('first', array(
                'conditions' => array(
                    'Store.id' => $storeId,
                    'Store.status' => 1
                )
            ));
        }
        if (!empty($storeDetails) && $this->siteSetting['Sitesetting']['address_mode'] == 'Google') {
            $deliveryDistance = $storeDetails['Store']['delivery_distance'];

            $Duration = $this->Googlemap->getDistance(
                $this->address,
                $storeDetails['Store']['address']
            );

            if ($deliveryDistance >= $Duration) {
                $storeDetails['Store']['distance'] = $Duration;
            }
        }

        foreach ($storeDetails['StoreCuisine'] as $k => $v) {
            $cuisineId[] = $v['cuisine_id'];
        }
        $storeDetails['Cuisines'] = $this->Cuisine->find('all', array(
            'fields' => array(
                'cuisine_name'
            ),
            'conditions' => array(
                'id' => $cuisineId
            )
        ));

		$ratingDetail = $this->Review->find('first', array(
								'conditions'=> array('Review.store_id' => $storeId,
													'Review.status' => 1),
								'fields' => array('SUM(Review.rating) AS rating',
													'Count(Review.rating) AS ratingCount')));


        $storeDetails['Store']['rating'] = (!empty($ratingDetail[0]['ratingCount'])) ?
				$ratingDetail[0]['rating']/$ratingDetail[0]['ratingCount']*20 : 0;

	
		if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
	        $storeDetails['DeliveryLocation'] = $this->DeliveryLocation->find('first', array(
	            'fields' => array(
	                'DeliveryLocation.minimum_order',
	                'DeliveryLocation.delivery_charge'
	            ),
	            'conditions' => array(
	                'DeliveryLocation.store_id' => $storeId,
	                'DeliveryLocation.city_id' => $cityId,
	                'DeliveryLocation.location_id' => $areaId
	            )
	        ));

			if (empty($storeDetails)) {
				$this->redirect(array('controller' => 'searches', 'action' => 'index'));
			}
		}

		if ($storeDetails['Store']['collection'] == 'Yes' ||
			$storeDetails['Store']['delivery'] == 'Yes' &&
			$storeDetails['Store']['delivery_option'] == 'Yes') {

			$productList = $this->Product->find('all', array(
								'conditions' => array('Product.store_id' => $storeId,
									'Product.status' => 1,
									'MainCategory.status' => 1,
									'OR' => array('Store.collection' => 'Yes',
										'Store.delivery'	 => 'Yes')),
								'order' => array('Product.category_id')));
			if (empty($productList)) {
				$this->redirect(array('controller' => 'searches', 'action' => 'index'));
			}

			$mainCategory = array();

			foreach ($productList as $key => $value) {
				if (!in_array($value['Product']['category_id'], $mainCategory)) {
					$mainCategory[] = $value['Product']['category_id'];
				}
			}

			$mainCategoryList = $this->Category->find('all', array(
				'conditions' => array('Category.status' => 1,
									'Category.parent_id' => 0,
					'OR' => array('Category.id' => $mainCategory))));


			$this->Deal->recursive = 2;
			$dealProducts = $this->Deal->find('all', array(
				'conditions' => array('Deal.store_id' => $storeId,
					'Deal.status' => 1,
					'MainProduct.status' => 1,
				)));

			foreach ($dealProducts as $key => $value) {
				if ($value['MainProduct']['MainCategory']['status'] == 1) {
					$dealProduct[] = $value;
				}
			}
		}

		$today 	= date("m/d/Y");
		$storeOffers = $this->Storeoffer->find('first', array(
							'conditions' => array(
												'Storeoffer.store_id' => $storeId,
												'Storeoffer.status' => 1,
												"Storeoffer.from_date <=" => $today,
												"Storeoffer.to_date >="   => $today),
							'order' => 'Storeoffer.id DESC'));

		$metaTitle          = $storeDetails['Store']['meta_title'];
		$metakeywords       = $storeDetails['Store']['meta_keywords'];
		$metaDescriptions   = $storeDetails['Store']['meta_description'];


		$guests = $times = array();
		for ($i=1; $i <= 20; $i++) {
			$guests[$i] = $i;
		}

		$times = $this->Updown->bookaTableTimes();


		//echo "<pre>"; print_r($storeDetails); die();

		$this->Session->write('storeId', $storeId);
		
		$this->set(compact('storeList', 'productList', 'storeDetails', 'mainCategoryList',
					 	'storeId', 'dealProduct', 'metaTitle', 'metakeywords', 'metaDescriptions',
					 	'storeOffers', 'dealProducts', 'guests', 'times'));

	}

	public function productdetails() {

		$this->Product->recursive = 2 ;
		$id 	= $this->request->data['id'];
		$productDetails = $this->Product->find('first', array(
									'conditions' => array('Product.id' => $id)));
		$this->set(compact('productDetails'));
	}

	public function variantDetails() {

		$id 	= $this->request->data['id'];
		$productVariantDetails = $this->ProductDetail->find('first', array(
									'conditions' => array('ProductDetail.id' => $id)));
		$this->set(compact('productVariantDetails'));
	}


	public function cartProduct() {

		$id 		= $this->request->data['id'];
		$quantity 	= $this->request->data['quantity'];
		$mainAddon 	= $productName = $productPrice = '';
		$addons 	= (isset($this->request->data['subaddons'])) ? rtrim($this->request->data['subaddons'], ',') : '';
		$addons 	= explode(',', $addons);
		
		$this->ProductDetail->recursive = 2;
		$productDetails = $this->ProductDetail->find('first', array(
										'conditions' => array('ProductDetail.id' => $id)));
		
		$productPrice = $productDetails['ProductDetail']['orginal_price'];

		if (!empty($addons[0])) {

			if ($productDetails['Product']['price_option'] != 'single') {
				$productName  = $productDetails['ProductDetail']['sub_name'];
			}

			if (!empty($addons[1])) {
				$productName  = $productDetails['ProductDetail']['sub_name'];
				$productName  .= ' (';
				foreach ($addons as $key => $value) {
					$subaddonDetails[] = $this->ProductAddon->find('first', array(
											'conditions' => array('ProductAddon.subaddons_id' => $value,
																	'ProductAddon.productdetails_id' => $id)));
				}

				foreach ($subaddonDetails as $key => $value) {

					$nextValue = $key+1;

	                if (!empty($value['Subaddon']['subaddons_name'])) {
	                    if ($value['Mainaddon']['mainaddons_name'] != $mainAddon) {
	                        $productName .= $value['Mainaddon']['mainaddons_name'] . ' (';
	                        $mainAddon = $value['Mainaddon']['mainaddons_name'];
	                    }

	                    $productName .= $value['Subaddon']['subaddons_name'] . ' (';
	                    $productName .= ($value['ProductAddon']['subaddons_price'] != 0) ?
	                        $value['ProductAddon']['subaddons_price'] :
	                        'Free';

	                    $productPrice += ($value['ProductAddon']['subaddons_price'] != 0) ?
	                        $value['ProductAddon']['subaddons_price'] :
	                        0;
	                    $productName .= '), ';

	                    if (isset($subaddonDetails[$nextValue]['Mainaddon']['mainaddons_name']) &&
	                    		$subaddonDetails[$nextValue]['Mainaddon']['mainaddons_name'] != $mainAddon) {
	                        $productName = rtrim($productName, ', ');
	                        $productName .= ') + ';
	                    }
	                }

				}
				$productName = rtrim($productName, ' + ').')';
			}

			$shopCart 	 = $this->ShoppingCart->find('first', array(
									'conditions' => array('ShoppingCart.session_id' => $this->SessionId,
														  'ShoppingCart.subaddons_name' => trim($productName),
														  'ShoppingCart.order_id' => 0,
														  'ShoppingCart.product_id' => $id)));
		} else {

			$shopCart 	 = $this->ShoppingCart->find('first', array(
									'conditions' => array('ShoppingCart.session_id' => $this->SessionId,
														  'ShoppingCart.order_id' => 0,
														  'ShoppingCart.product_id' => $id)));
		}

		if (empty($shopCart)) {

			$deal = $this->Deal->findByMainProduct($productDetails['ProductDetail']['product_id']);
			
			$shoppingCart['product_id'] 	= $productDetails['ProductDetail']['id'];
			$shoppingCart['session_id'] 	= $this->SessionId;
			$shoppingCart['product_image']	= (isset($productDetails['Product']['product_image'])) ?
													$productDetails['Product']['product_image'] : 'no-image.jpg';
			if (!empty($addons[0])) {
				// Ternery Operator using product name (Deal or not)
				$shoppingCart['product_name']		 = (!empty($deal) && $deal['Deal']['status'] != 0) ?
						$productDetails['Product']['product_name'].' '.
						'[Deal :: '.$deal['SubProduct']['product_name'].']' :
						$productDetails['Product']['product_name'];
			} else {
				$shoppingCart['product_name']		 = (!empty($deal) && $deal['Deal']['status'] != 0) ?
						$productDetails['Product']['product_name'].'::'.$productDetails['ProductDetail']['sub_name'].
						'[Deal :: '.$deal['SubProduct']['product_name'].']' :
						$productDetails['Product']['product_name'].'::'.$productDetails['ProductDetail']['sub_name'];
			}

			// $shoppingCart['product_description'] = '';
			$shoppingCart['subaddons_name']		 = $productName;
			$shoppingCart['product_price'] 		 = $productPrice;
			$shoppingCart['category_name'] 		 = $productDetails['Product']['MainCategory']['category_name'];
			$shoppingCart['product_quantity'] 	 = $quantity;
			$shoppingCart['store_id']			 = $productDetails['Product']['store_id'];
			$shoppingCart['product_total_price'] = $shoppingCart['product_price'] * $quantity;

			if ($this->ShoppingCart->save($shoppingCart, null, null)) {
				echo 'Success';
			}

		} else {

			$productQuantity = $shopCart['ShoppingCart']['product_quantity'] + $quantity;

			$shoppingCart = $shopCart;
			$shoppingCart['ShoppingCart']['product_quantity'] 	 = $productQuantity;
			$shoppingCart['ShoppingCart']['product_total_price'] = $shoppingCart['ShoppingCart']['product_price'] * $productQuantity;

			if ($this->ShoppingCart->save($shoppingCart, null, null)) {
				echo 'Success';
			}
		}
		exit();
	}

	public function cart() {

		$total = $this->ShoppingCart->find('all', array(
								'conditions'=>array('ShoppingCart.session_id' => $this->SessionId,
													'ShoppingCart.order_id' => 0,
													'ShoppingCart.store_id' => $this->storeId),
								'fields' => array('SUM(ShoppingCart.product_total_price) AS cartTotal')));

		$storeProduct = $this->ShoppingCart->find('all',array(
        						'conditions' => array('ShoppingCart.session_id' => $this->SessionId,
        											  'ShoppingCart.order_id' => 0,
        											  'ShoppingCart.store_id' => $this->storeId),
        						'fields' => array('store_id',
        										 'COUNT(ShoppingCart.store_id) AS productCount',
        										 'SUM(ShoppingCart.product_total_price) As productTotal'),
        						'group'=>array('ShoppingCart.store_id')));

		$store_id = (!empty($storeProduct)) ?
					$storeProduct[0]['ShoppingCart']['store_id'] : 
					'';


		$cartCount = $this->ShoppingCart->find('first',array(
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

        $minimumOrder = $DeliveryLocation['DeliveryLocation']['minimum_order'];
		$this->set('cartTotal', $total[0][0]['cartTotal']);
		$this->set(compact('storeCart','storeProduct', 'cartCount', 'minimumOrder'));
	}


	public function descriptionAdd() {

		$id 	= $this->request->data['id'];
		$description 	= $this->request->data['productDescription'];

		$shopCartDetails = $this->ShoppingCart->findById($id);
		$shopCartDetails['ShoppingCart']['product_description'] = $description;
		if ($this->ShoppingCart->save($shopCartDetails, null, null)) {
			echo 'success';
		}
		exit();

	}

	public function deleteCart() {

		$id 	= $this->request->data['id'];
		$this->ShoppingCart->delete($id);
		exit(); 
	}

	public function qtyUpdate() {

		$id 	= $this->request->data['id'];
		$type 	= $this->request->data['type'];

		$shopCart = $this->ShoppingCart->findById($id);

		if ($type == 'increment') {
			$shopCart['ShoppingCart']['product_quantity'] += 1;
		} else {
			$shopCart['ShoppingCart']['product_quantity'] -= 1;
		}

		$shopCart['ShoppingCart']['product_total_price'] = $shopCart['ShoppingCart']['product_price'] * $shopCart['ShoppingCart']['product_quantity'];

		if ($shopCart['ShoppingCart']['product_quantity'] > 0) {
			$this->ShoppingCart->save($shopCart, null, null);
		}
		
		exit();
	}


	public function changeLocation() {

		$location 	= (isset($this->request->data['location'])) ? $this->request->data['location'] : '';
		$this->ShoppingCart->deleteAll(array("session_id"=> $this->SessionId,
											'ShoppingCart.order_id' => 0));
		$this->Session->write("preSessionid",'');
		session_regenerate_id();

		if (!empty($location)) {
			$this->Session->write("Search.city",'');
			$this->Session->write("Search.area",'');
			$this->Session->write("Search.googleAddress",'');
			exit();
		}
		return 1;
	}

	public function locations() {

		$id 	= $this->request->data['id'];
		$model 	= $this->request->data['model'];
		$stores = array();

        $storeLists = $this->DeliveryLocation->find('all', array(
            'conditions' => array(
                'Store.status' => 1,
                'OR' => array(
                    'Store.collection' => 'Yes',
                    'Store.delivery' => 'Yes',
                    'DeliveryLocation.city_id' => $id,
                    'Store.store_city' => $id,
                )
            ),
            'group' => array(
                'Store.id'
            )
        ));

		foreach ($storeLists as $key => $value) {

			if ($value['Store']['collection'] == 'Yes' ||
				$value['Store']['delivery'] == 'Yes' &&
				$value['Store']['delivery_option'] == 'Yes') {
				$stores[] = $value['Store']['id'];
			}
		}

		$storeList = $this->DeliveryLocation->find('all', array(
			'conditions' => array(
				'DeliveryLocation.location_id !=' => '',
                'DeliveryLocation.city_id =' => $id,
				'DeliveryLocation.store_id' => $stores,
				'Location.status' => 1
			),
			'group' => array(
                'DeliveryLocation.location_id'
            )
        ));

		foreach ($storeList as $key => $value) {
			if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {
				$locations[$value['Location']['id']] = $value['Location']['zip_code'];
			} else {
				$locations[$value['Location']['id']] = $value['Location']['area_name'];
			}
		}

		$this->set(compact('model', 'locations'));
	}


	public function storeMinOrderCheck() {

		$this->ShoppingCart->recursive = 2;
		$storeProduct = $this->ShoppingCart->find('all',array(
		        						'conditions' => array('ShoppingCart.session_id' => $this->SessionId),
		        						'fields' => array('store_id',
		        										 'COUNT(ShoppingCart.store_id) AS productCount',
		        										 'SUM(ShoppingCart.product_total_price) As productTotal'),
		        						'group'=>array('ShoppingCart.store_id')));
		foreach ($storeProduct as $key => $value) {
			if ($value['Store']['minimum_order'] > $value[0]['productTotal']) {
				echo $value['Store']['store_name'].' Minimum order '. $value['Store']['minimum_order'];
			}
		}
		exit();
	}
	
	public function filtterByCategory(){
		$id      	= $this->request->data['id'];
		$storeId 	= $this->request->data['storeId'];
		$count   	= $this->request->data['count'];
		$searchKey  = (!empty($this->request->data['searchKey'])) ? trim($this->request->data['searchKey']) : '';
		$productList = $dealProducts = array();

		if (!empty($searchKey)) {
			$productList = $this->Product->find('all', array(
                'conditions' => array(
                    'Product.category_id' => $id,
                    'Product.status' => 1,
                    'MainCategory.status' => 1,
                    'Product.store_id' => $storeId,
                    'Product.product_name LIKE' => "%".$searchKey."%"
                ),
                'order' => array(
                    'Product.category_id'
                )
            ));
		} else {
            if (!empty($id)) {
                $productList = $this->Product->find('all', array(
                    'conditions' => array(
                        'Product.category_id' => $id,
                        'Product.status' => 1,
                        'MainCategory.status' => 1,
                        'Product.store_id' => $storeId
                    ),
                    'order' => array(
                        'Product.category_id'
                    )
                ));
            } else {
                $productList = $this->Product->find('all', array(
                    'conditions' => array(
                        'Product.status' => 1,
                        'MainCategory.status' => 1,
                        'Product.store_id' => $storeId
                    ),
                    'order' => array(
                        'Product.category_id'
                    )
                ));

                $this->Deal->recursive = 2;
                $dealProducts = $this->Deal->find('all', array(
                    'conditions' => array('Deal.store_id' => $storeId,
                        'Deal.status' => 1,
                        'MainProduct.status' => 1,
                    )));


            }
		}
		$this->set(compact('productList', 'count', 'dealProducts'));
	}

	public function dealProducts() {

		$storeId = $this->request->data['storeId'];
		$this->Deal->recursive = 2;
		$dealProduct = $this->Deal->find('all', array(
							'conditions' => array('Deal.store_id' => $storeId,
											'Deal.status' => 1,
											'MainProduct.status' => 1),
							'order' => array('MainProduct.category_id')));

		$subCount = 0;

		foreach ($dealProduct as $key => $value) {
			if ($value['MainProduct']['MainCategory']['status'] == 1) {
				if ($subCount <= 2) {

					$dealProducts[] = $value;
				}
			}
		}
		$this->set(compact('dealProducts'));
	}
}