<?php

/* Manikandan. N */
App::uses('AppController', 'Controller');

class DashboardsController extends AppController {

    var $helpers = array('Html', 'Session', 'Javascript', 'Ajax', 'Common');
    public $uses = array('Store','NewsLetter', 'Order', 'Invoice', 'Customer', 'Store');
    public $components = array('Functions');

    // Admin index
    public function admin_index() {
        $site_setting = $this->siteSetting;
        $tax = $site_setting['Sitesetting']['vat_percent'];
        $cardfess = $site_setting['Sitesetting']['card_fee'];
        $statusOrder = array('Delivered', 'Failed', 'Deleted');

        $order_detail = $this->Order->find('all', array(
            'conditions' => array(
                'Order.status' => 'Delivered'
            )
        ));
        $counts = count($order_detail);
        $total = 0;
        foreach ($order_detail as $key => $value) {
            $total = $total + $value['Order']['order_grand_total'];
        }

        //Today
        list($todayOrders, $todayCustomers) = $this->getOrderDetails('Today');
        //Yesterday
        list($yesterdayOrders, $yesterdayCustomers) = $this->getOrderDetails('Yesterday');
        //Last Week
        list($lastWeekOrders, $lastWeekCustomers) = $this->getOrderDetails('ThisWeek');
        //Last Month
        list($lastMonthOrders, $lastMonthCustomers) = $this->getOrderDetails('ThisMonth');
        //Active Users
        list($activeCustomers, $activeRestaurants) = $this->getOrderDetails('Active');
        //Deactive Users
        list($deactiveCustomers, $deactiveRestaurants) = $this->getOrderDetails('Deactive');
        //Pending Users
        list($pendingCustomers, $pendingRestaurants) = $this->getOrderDetails('Pending');
        //Total Users
        list($allCustomers, $allRestaurants) = $this->getOrderDetails('Overall');

        $dasboard_value['order_count'] = $counts;
        $dasboard_value['order_price'] = $total;

        //Today
        $dasboard_value['todayOrderCount'] = $todayOrders[0]['orderCount'];
        $dasboard_value['todaySubTotal'] = $todayOrders[0]['Subtotal'];
        $dasboard_value['todayCustomer'] = $todayCustomers[0]['customerCount'];

        //Yesterday
        $dasboard_value['yesterdayOrderCount'] = $yesterdayOrders[0]['orderCount'];
        $dasboard_value['yesterdaySubTotal'] = $yesterdayOrders[0]['Subtotal'];
        $dasboard_value['yesterdayCustomer'] = $yesterdayCustomers[0]['customerCount'];

        //Last Week
        $dasboard_value['weekOrderCount'] = $lastWeekOrders[0]['orderCount'];
        $dasboard_value['weekSubTotal'] = $lastWeekOrders[0]['Subtotal'];
        $dasboard_value['weekCustomer'] = $lastWeekCustomers[0]['customerCount'];

        //Last Month
        $dasboard_value['monthOrderCount'] = $lastMonthOrders[0]['orderCount'];
        $dasboard_value['monthSubTotal'] = $lastMonthOrders[0]['Subtotal'];
        $dasboard_value['monthCustomer'] = $lastMonthCustomers[0]['customerCount'];

        //Users
        $dasboard_value['activeUsers'] = $activeCustomers[0]['customerCount'];
        $dasboard_value['deactiveUsers'] = $deactiveCustomers[0]['customerCount'];
        $dasboard_value['pendingUsers'] = $pendingCustomers[0]['customerCount'];
        $dasboard_value['totalUsers'] = $allCustomers[0]['customerCount'];

        //Restaurants
        $dasboard_value['activeRestaurants'] = $activeRestaurants;
        $dasboard_value['deactiveRestaurants'] = $deactiveRestaurants;
        $dasboard_value['pendingRestaurants'] = $pendingRestaurants;
        $dasboard_value['totalRestaurants'] = $allRestaurants;

        $this->set(compact('dasboard_value'));
    }

    // Store index
    public function store_index() {
        $this->layout = 'assets';
        $storeId = $this->Auth->User('Store.id');
        $site_setting = $this->siteSetting;

        $order_detail = $this->Order->find('all', array(
            'conditions' => array(
                'Order.store_id' => $storeId,
                'Order.status' => 'Delivered')));
        $counts = count($order_detail);
        $total = 0;
        foreach ($order_detail as $key => $value) {
            $total = $total + $value['Order']['order_grand_total'];
        }

        //Today
        list($todayOrders, $todayCustomers) = $this->getOrderDetails('Today');
        //Yesterday
        list($yesterdayOrders, $yesterdayCustomers) = $this->getOrderDetails('Yesterday');
        //Last Week
        list($lastWeekOrders, $lastWeekCustomers) = $this->getOrderDetails('ThisWeek');
        //Last Month
        list($lastMonthOrders, $lastMonthCustomers) = $this->getOrderDetails('ThisMonth');
        //Active Users
        list($activeCustomers, $activeRestaurants) = $this->getOrderDetails('Active');
        //Deactive Users
        list($deactiveCustomers, $deactiveRestaurants) = $this->getOrderDetails('Deactive');
        //Pending Users
        list($pendingCustomers, $pendingRestaurants) = $this->getOrderDetails('Pending');
        //Total Users
        list($allCustomers, $allRestaurants) = $this->getOrderDetails('Overall');

        $dasboard_value['order_count'] = $counts;
        $dasboard_value['order_price'] = $total;

        //Today
        $dasboard_value['todayOrderCount'] = $todayOrders[0]['orderCount'];
        $dasboard_value['todaySubTotal'] = $todayOrders[0]['Subtotal'];
        $dasboard_value['todayCustomer'] = $todayCustomers[0]['customerCount'];

        //Yesterday
        $dasboard_value['yesterdayOrderCount'] = $yesterdayOrders[0]['orderCount'];
        $dasboard_value['yesterdaySubTotal'] = $yesterdayOrders[0]['Subtotal'];
        $dasboard_value['yesterdayCustomer'] = $yesterdayCustomers[0]['customerCount'];

        //Last Week
        $dasboard_value['weekOrderCount'] = $lastWeekOrders[0]['orderCount'];
        $dasboard_value['weekSubTotal'] = $lastWeekOrders[0]['Subtotal'];
        $dasboard_value['weekCustomer'] = $lastWeekCustomers[0]['customerCount'];

        //Last Month
        $dasboard_value['monthOrderCount'] = $lastMonthOrders[0]['orderCount'];
        $dasboard_value['monthSubTotal'] = $lastMonthOrders[0]['Subtotal'];
        $dasboard_value['monthCustomer'] = $lastMonthCustomers[0]['customerCount'];

        //Restaurants
        $dasboard_value['activeRestaurants'] = $activeRestaurants;
        $dasboard_value['deactiveRestaurants'] = $deactiveRestaurants;
        $dasboard_value['pendingRestaurants'] = $pendingRestaurants;
        $dasboard_value['totalRestaurants'] = $allRestaurants;

        $this->set(compact('dasboard_value'));
    }

    // Period based Order details
    public function getOrderDetails($period) {
        $currentDate = date('Y-m-d');
        $id = $this->Auth->User();
        $storeId = (!empty($id['Store']['id'])) ? $id['Store']['id'] : '';
        $restaurantCondition = $orderCondition = '';

        switch ($period) {
            case 'Today':

                $orderCondition = (!empty($storeId)) ?
                        array(
                    'Order.status' => 'Delivered',
                    'Order.delivery_date LIKE' => $currentDate . '%',
                    'Order.store_id' => $storeId
                        ) :
                        array(
                    'Order.status' => 'Delivered',
                    'Order.delivery_date LIKE' => $currentDate . '%'
                );

                $customerCondition = array(
                    'Customer.created LIKE' => $currentDate . '%'
                );
                break;

            case 'Yesterday':
                $yesterDay = date("Y-m-d", strtotime('-1 day'));
                $orderCondition = (!empty($storeId)) ?
                        array(
                    'Order.status' => 'Delivered',
                    'Order.delivery_date LIKE' => $yesterDay . '%',
                    'Order.store_id' => $storeId
                        ) :
                        array(
                    'Order.status' => 'Delivered',
                    'Order.delivery_date LIKE' => $yesterDay . '%'
                );

                $customerCondition = array(
                    'Customer.created LIKE' => $yesterDay . '%'
                );
                break;

            case 'ThisWeek':
                $lastWeekDate = date("Y-m-d", strtotime("-6 days"));
                $orderCondition = (!empty($storeId)) ?
                        array(
                    'Order.status' => 'Delivered',
                    'Order.store_id' => $storeId,
                    'Order.delivery_date between ? and ?' => array(
                        $lastWeekDate, $currentDate
                    )
                        ) :
                        array(
                    'Order.status' => 'Delivered',
                    'Order.delivery_date between ? and ?' => array(
                        $lastWeekDate, $currentDate
                    )
                );

                $customerCondition = array(
                    'Customer.created between ? and ?' => array(
                        $lastWeekDate, $currentDate
                    )
                );
                break;

            case 'ThisMonth':
                $lastMonthDate = date("Y-m-d", strtotime("-29 days"));
                $orderCondition = (!empty($storeId)) ?
                        array(
                    'Order.status' => 'Delivered',
                    'Order.store_id' => $storeId,
                    'Order.delivery_date between ? and ?' => array(
                        $lastMonthDate, $currentDate
                    )
                        ) :
                        array(
                    'Order.status' => 'Delivered',
                    'Order.delivery_date between ? and ?' => array(
                        $lastMonthDate, $currentDate
                    )
                );

                $customerCondition = array(
                    'Customer.created between ? and ?' => array(
                        $lastMonthDate, $currentDate
                    )
                );
                break;

            case 'Active':
                $customerCondition = array(
                    'Customer.status' => 1
                );

                $restaurantCondition = array(
                    'Store.status' => 1
                );
                break;

            case 'Deactive':
                $customerCondition = array(
                    'Customer.status' => 0
                );

                $restaurantCondition = array(
                    'Store.status' => 0
                );
                break;

            case 'Pending':
                $customerCondition = array(
                    'Customer.status' => 2
                );

                $restaurantCondition = array(
                    'Store.status' => 2
                );
                break;

            case 'Overall':
                $customerCondition = array(
                    'Customer.status !=' => 3
                );

                $restaurantCondition = array(
                    'Store.status !=' => 3
                );
                break;
        }

        $OrderRecords = $this->Order->find('first', array(
            'fields' => array(
                'SUM(Order.order_sub_total) AS Subtotal',
                'COUNT(Order.id) AS orderCount'
            ),
            'conditions' => $orderCondition
        ));

        $CustomerRecords = $this->Customer->find('first', array(
            'fields' => array(
                'Count(Customer.id) AS customerCount'
            ),
            'conditions' => $customerCondition
        ));

        $RestaurantRecords = $this->Store->find('list', array(
            'conditions' => $restaurantCondition
        ));

        if ($period == 'Active' || $period == 'Deactive' || $period == 'Pending' || $period == 'Overall') {
            return array(
                $CustomerRecords,
                count($RestaurantRecords)
            );
        } else {
            return array(
                $OrderRecords,
                $CustomerRecords
            );
        }
    }

}
