<?php

/* Janakiraman */

App::uses('AppController', 'Controller');

class CommonsController extends AppController {

    var $helpers = array('Html', 'Session', 'Javascript', 'Ajax', 'Common');
    public $uses = array('Store', 'User', 'HalalProduct', 'Newsletters_user', 'Category', 'Customer', 'State', 'City', 'Location', 'DeliveryLocation',
        'Cuisine', 'Country', 'Product', 'CustomerAddressBook', 'Voucher', 'Storeoffer', 'Deal',
        'Driver', 'Order', 'Review', 'StoreTiming', 'ContactUs', 'Mainaddon');

    /**
     * CommonsController::statusChanges()
     * Status Change Process
     * @return void
     */
    public function adminstatistics() {
        /*
          SELECT s.store_name as name, sum(o.order_grand_total) as y from stores as s LEFT JOIN orders as o ON o.store_id = s.id WHERE s.status = 1 GROUP BY s.id ORDER BY sum(o.order_grand_total) DESC LIMIT 20
         * 
         *          */
        if (!empty($this->request->data["action"])) {
            $action = $this->request->data["action"];

            if ($action == "admin_most_sales") {
                $admin_most_sales = $this->Store->query(" SELECT s.store_name as name, sum(o.order_grand_total) as y from stores as s LEFT JOIN orders as o ON o.store_id = s.id WHERE s.status = 1 AND o.status IN ('Delivered','Collected') GROUP BY s.id ORDER BY sum(o.order_grand_total) DESC LIMIT 20");
                print(json_encode($admin_most_sales));
            } else if ($action == "admin_break_down_revenue") {
                $admin_break_down_revenue = $this->Store->query("(SELECT SUM(order_grand_total) as delivery FROM orders WHERE order_type = 'Delivery' AND status IN ('Delivered','Collected')) UNION (SELECT SUM(order_grand_total) as pickup FROM orders WHERE order_type = 'Collection' AND status  IN ('Delivered','Collected'))");
                print(json_encode($admin_break_down_revenue));
            } else if ($action == "aggregated_delivery_pickup_total_budget") {
                $aggregated_delivery_pickup_total_budget = $this->Store->query("SELECT AVG(order_grand_total) as total, (SELECT AVG(order_grand_total) FROM orders WHERE order_type='Delivery') as delivery_average ,(SELECT AVG(order_grand_total) FROM orders WHERE order_type='Collection') as collection_average FROM orders");
                print(json_encode($aggregated_delivery_pickup_total_budget));
            } else if ($action == "admin_reservations") {
                $admin_reservations = $this->Store->query("SELECT COUNT(store_id) as y, MONTH(STR_TO_DATE(booking_date, '%d-%m-%Y')) as name FROM booka_tables WHERE status = 'Approved' AND booking_date != ''GROUP BY name");
                print(json_encode($admin_reservations));
            } else if ($action == "admin_total_orders_month") {
                $date = $this->request->data["date"];
                $admin_total_orders_month = $this->Store->query("SELECT count(id) as y, MONTH(STR_TO_DATE(delivery_date, '%Y-%m-%d')) as name FROM orders where delivery_date LIKE '%$date%'  AND status in ('Delivered', 'Collected') GROUP BY MONTH(STR_TO_DATE(delivery_date, '%Y-%m-%d'))");
                print(json_encode($admin_total_orders_month));
            } else if ($action == "admin_total_revenue_month") {
                $date = $this->request->data["date"];
                $total_revenue_month = $this->Store->query("SELECT sum(order_grand_total) "
                        . " as y, MONTH(STR_TO_DATE(delivery_date, '%Y-%m-%d')) as name "
                        . " FROM orders where delivery_date LIKE '%$date%' AND status in ('Delivered', 'Collected')"
                        . "GROUP BY MONTH(STR_TO_DATE(delivery_date, '%Y-%m-%d'))");
                print(json_encode($total_revenue_month));
            } else if ($action == "admin_rush_time") {
                $admin_rush_time = $this->Store->query("SELECT DATE_FORMAT(created,'%H') as name, (count(id) / (SELECT count(id) FROM orders WHERE status IN ('Delivered','Collected')) * 100) as y FROM orders WHERE status IN ('Delivered','Collected') GROUP BY name");
                print(json_encode($admin_rush_time));
            }
        }
        exit();
    }

    public function storestatistics() {

        if (!empty($this->request->data["action"]) && !empty($this->request->data["storeId"])) {
            $action = $this->request->data["action"];
            $storeId = $this->request->data["storeId"];
            $date = $this->request->data["date"];

            if ($action == "rushtime") {
                $rushtime = $this->Store->query("SELECT DATE_FORMAT(created,'%H') as name, (count(store_id)/(select count(store_id) from orders WHERE store_id = $storeId AND DATE_FORMAT(created, '%m %Y') LIKE  '%$date%')) * 100 as y FROM orders
                WHERE store_id = $storeId AND DATE_FORMAT(created, '%m %Y') LIKE  '%$date%' GROUP BY name");
                print(json_encode($rushtime));
            } else if ($action == "average_budget") {
                $average_budget = $this->Store->query("SELECT DATE_FORMAT(created, '%m %Y') as name"
                        . ", AVG(order_grand_total) as total, (SELECT AVG(order_grand_total) FROM"
                        . " orders WHERE store_id = $storeId AND DATE_FORMAT(created, '%m %Y') LIKE "
                        . "'%$date%' AND order_type='Delivery') as delivery_average , "
                        . "(SELECT AVG(order_grand_total) FROM orders WHERE store_id = $storeId AND "
                        . "DATE_FORMAT(created, '%m %Y') LIKE '%$date%' AND order_type='Collection')"
                        . " as collection_average FROM orders WHERE store_id = $storeId AND "
                        . "DATE_FORMAT(created, '%m %Y') LIKE '%$date%'");
                print(json_encode($average_budget));
            } else if ($action == "most_requested_item") {
                $most_requested_item = $this->Store->query("select count(product_name) as y, product_name as name "
                        . "FROM shopping_carts WHERE order_id != 0 AND store_id = $storeId AND "
                        . "DATE_FORMAT(created, '%m %Y') "
                        . "LIKE '%$date%' GROUP BY product_name");
                print(json_encode($most_requested_item));
            } else if ($action == "most_requested_collection_item") {
                $most_requested_collection_item = $this->Store->query("select sc.order_id, o.status, count(sc.product_name) "
                        . " as y, sc.product_name as name FROM shopping_carts as sc LEFT JOIN orders as o on o.id = "
                        . " sc.order_id  WHERE sc.order_id != 0 AND sc.store_id = $storeId AND DATE_FORMAT(sc.created, '%m %Y') "
                        . " LIKE '%$date%' AND o.order_type = 'Collection' GROUP BY sc.product_name");
                print(json_encode($most_requested_collection_item));
            } else if ($action == "most_requested_delivery_item") {
                $most_requested_delivery_item = $this->Store->query("select sc.order_id, o.status, count(sc.product_name) "
                        . "as y, sc.product_name as name FROM shopping_carts as sc LEFT JOIN orders as o on o.id = "
                        . "sc.order_id  WHERE sc.order_id != 0 AND sc.store_id = $storeId AND DATE_FORMAT(sc.created, '%m %Y') "
                        . " LIKE '%$date%' AND o.order_type = 'Delivery' GROUP BY sc.product_name");
                print(json_encode($most_requested_delivery_item));
            } else if ($action == "time_more_money") {
                $time_more_money = $this->Store->query("SELECT DATE_FORMAT(created,'%H') "
                        . " as name, sum(order_grand_total) as y FROM orders WHERE store_id = $storeId "
                        . " AND DATE_FORMAT(created, '%m %Y') LIKE  '%$date%' GROUP BY name");
                print(json_encode($time_more_money));
            } else if ($action == "store_reservations") {

                $store_reservations = $this->Store->query("SELECT COUNT(store_id) as y, "
                        . "MONTH(STR_TO_DATE(booking_date, '%d-%m-%Y')) as name FROM booka_tables "
                        . "WHERE status = 'Approved' AND booking_date != '' "
                        . "AND booking_date LIKE '%$date%' "
                        . "GROUP BY name");
                print(json_encode($store_reservations));
            } else if ($action == "break_down_revenue") {
                $break_down_revenue = $this->Store->query("(SELECT SUM(order_grand_total) as delivery "
                        . "FROM orders WHERE store_id = $storeId AND order_type = 'Delivery' AND status  "
                        . "IN ('Delivered','Collected')) UNION (SELECT SUM(order_grand_total) as pickup "
                        . "FROM orders WHERE store_id = $storeId AND order_type = 'Collection' AND status  IN "
                        . "('Delivered','Collected'))");
                print(json_encode($break_down_revenue));
            } else if ($action == "total_orders_month") {
                $total_orders_month = $this->Store->query("SELECT count(id) as y, "
                        . "MONTH(STR_TO_DATE(delivery_date, '%Y-%m-%d')) as name FROM orders "
                        . "where store_id = $storeId AND delivery_date LIKE '%$date%'  AND status in ('Delivered', 'Collected') GROUP BY "
                        . "MONTH(STR_TO_DATE(delivery_date, '%Y-%m-%d'))");
                print(json_encode($total_orders_month));
            } else if ($action == "total_revenue_month") {
                $total_revenue_month = $this->Store->query("SELECT sum(order_grand_total) "
                        . " as y, MONTH(STR_TO_DATE(delivery_date, '%Y-%m-%d')) as name "
                        . " FROM orders where store_id = $storeId AND delivery_date LIKE '%$date%' AND status in ('Delivered', 'Collected')"
                        . "GROUP BY MONTH(STR_TO_DATE(delivery_date, '%Y-%m-%d'))");
                print(json_encode($total_revenue_month));
            }
        }
        exit();
    }

    public function deleteSubscribedUser() {

        if (!empty($this->request->data["id"])) {
            if ($this->request->data["action"] == "delete") {
                $this->Newsletters_user->delete($this->request->data('id'));
                echo ("Success | Deleted");
            }
        }
        exit();
    }

    public function deleteContentWriter() {

        if (!empty($this->request->data["id"])) {
            if ($this->request->data["action"] == "delete") {
                $this->User->delete($this->request->data('id'));
                echo ("Success | Deleted");
            }
        }
        exit();
    }

    public function countNewOrders() {
        if (!empty($this->Auth->User('Store.id'))) {
            $date = date("Y-m-d");
            $count = $this->Order->find('count', array(
                'conditions' => array(
                    'Order.store_id' => $this->Auth->User('Store.id'),
                    'Order.status' => 'Pending',
                    'Order.created LIKE' => "%$date%"
                ),
                'order' => array('Order.id DESC')));
            echo $count;
        }
        exit();
    }

    public function deliverInfoStatusChange() {

        if (!empty($this->request->data["id"]) && !empty($this->request->data["status"])) {
            if ($this->request->data["action"] == "delete") {
                $this->DeliveryLocation->delete($this->request->data('id'));
                echo ("Success | Deleted");
            } else {
                $id = $this->request->data["id"];
                $status = $this->request->data["status"] == "2" ? "1" : "2";
                $this->DeliveryLocation->read(null, $id);
                $this->DeliveryLocation->set(array(
                    'status' => $status,
                ));
                $this->DeliveryLocation->save();
                echo ("Success | " . $status);
            }
        }
        exit();
    }

    public function statusChanges() {
        $id = $this->request->data['id'];
        $model = $this->request->data['model'];

        if (!empty($id)) {
            switch (trim($model)) {
                case 'Cuisine':
                    $statusCuisine = $this->Cuisine->findById($id);
                    if (!empty($statusCuisine)) {
                        if ($statusCuisine['Cuisine']['status'] == 1) {
                            $statusCuisine['Cuisine']['status'] = 0;
                        } else {
                            $statusCuisine['Cuisine']['status'] = 1;
                        }
                        $this->Cuisine->save($statusCuisine['Cuisine']);
                    }
                    break;

                case 'Category':
                    $stausCategory = $this->Category->findById($id);
                    if (!empty($stausCategory)) {
                        if ($stausCategory['Category']['status'] == 1) {
                            $stausCategory['Category']['status'] = 0;
                        } else {
                            $stausCategory['Category']['status'] = 1;
                        }
                        $status = $stausCategory['Category']['status'];
                        $this->Category->save($stausCategory['Category']);
                        $this->Product->updateAll(
                                array('Product.status' => $status), array('Product.category_id' => $id)
                        );

                        echo $stausCategory['Category']['status'];
                    }
                    break;
                case 'HalalProduct':
                    $stausCategory = $this->HalalProduct->findById($id);
                    if (!empty($stausCategory)) {
                        if ($stausCategory['HalalProduct']['status'] == 1) {
                            $stausCategory['HalalProduct']['status'] = 0;
                        } else {
                            $stausCategory['HalalProduct']['status'] = 1;
                        }
                        $status = $stausCategory['HalalProduct']['status'];
                        $this->HalalProduct->save($stausCategory["HalalProduct"]);
                        echo $stausCategory['HalalProduct']['status'];
                    }
                    break;
                case 'CustomerAddressBook':
                    $stausCustomerAddressBook = $this->CustomerAddressBook->findById($id);
                    if (!empty($stausCustomerAddressBook)) {
                        if ($stausCustomerAddressBook['CustomerAddressBook']['status'] == 1) {
                            $stausCustomerAddressBook['CustomerAddressBook']['status'] = 0;
                        } else {
                            $stausCustomerAddressBook['CustomerAddressBook']['status'] = 1;
                        }
                        $this->CustomerAddressBook->save($stausCustomerAddressBook['CustomerAddressBook']);
                    }
                    break;

                case 'Product':

                    if ($this->Auth->User('role_id') == 3) {
                        $stausProduct = $this->Product->find('first', array(
                            'conditions' => array('Product.id' => $id,
                                'Product.store_id' => $this->Auth->User('Store.id'))));
                        if (!empty($stausProduct)) {
                            if ($stausProduct['Product']['status'] == 1) {
                                $stausProduct['Product']['status'] = 0;
                            } else {
                                $stausProduct['Product']['status'] = 1;
                            }
                            $this->Product->save($stausProduct['Product']);
                        }
                    } elseif ($this->Auth->User('role_id') == 1 || $this->Auth->User('role_id') == 2) {
                        $stausProduct = $this->Product->findById($id);
                        if ($stausProduct['Product']['status'] == 1) {
                            $stausProduct['Product']['status'] = 0;
                        } else {
                            $stausProduct['Product']['status'] = 1;
                        }
                        $stausProduct['Product']['id'] = $id;
                        $this->Product->save($stausProduct['Product']);
                    }
                    echo $stausProduct['Product']['status'];
                    break;

                case 'City':
                    $stausCity = $this->City->findById($id);
                    if (!empty($stausCity)) {
                        if ($stausCity['City']['status'] == 1) {
                            $stausCity['City']['status'] = 0;
                        } else {
                            $stausCity['City']['status'] = 1;
                        }
                        $this->City->save($stausCity['City']);
                    }
                    break;

                case 'Country':
                    $stausCountry = $this->Country->findById($id);
                    if (!empty($stausCountry)) {
                        if ($stausCountry['Country']['status'] == 1) {
                            $stausCountry['Country']['status'] = 0;
                        } else {
                            $stausCountry['Country']['status'] = 1;
                        }
                        $this->Country->save($stausCountry['Country']);
                    }
                    break;

                case 'State':
                    $stausState = $this->State->findById($id);
                    if (!empty($stausState)) {
                        if ($stausState['State']['status'] == 1) {
                            $stausState['State']['status'] = 0;
                        } else {
                            $stausState['State']['status'] = 1;
                        }
                        $this->State->save($stausState['State']);
                    }
                    break;

                case 'Location':
                    $stausLocation = $this->Location->findById($id);
                    if (!empty($stausLocation)) {
                        if ($stausLocation['Location']['status'] == 1) {
                            $stausLocation['Location']['status'] = 0;
                        } else {
                            $stausLocation['Location']['status'] = 1;
                        }
                        $this->Location->save($stausLocation['Location']);
                    }
                    break;

                case 'Customer':
                    $stausCustomer = $this->Customer->findById($id);
                    if (!empty($stausCustomer)) {
                        if ($stausCustomer['Customer']['status'] == 1) {
                            $this->Customer->updateAll(array('Customer.status' => 0), array('Customer.id' => $id));
                        } else {
                            $this->Customer->updateAll(array('Customer.status' => 1), array('Customer.id' => $id));
                        }
                    }
                    break;

                case 'Voucher':
                    if ($this->Auth->User('role_id') == 3) {
                        $stausVoucher = $this->Voucher->find('first', array(
                            'conditions' => array('Voucher.id' => $id,
                                'Voucher.store_id' => $this->Auth->User('Store.id'))));
                        if (!empty($stausVoucher)) {
                            if ($stausVoucher['Voucher']['status'] == 1) {
                                $stausVoucher['Voucher']['status'] = 0;
                            } else {
                                $stausVoucher['Voucher']['status'] = 1;
                            }
                            $this->Voucher->save($stausVoucher['Voucher']);
                            echo $stausVoucher['Voucher']['status'];
                        }
                    } elseif ($this->Auth->User('role_id') == 1) {
                        $stausVoucher = $this->Voucher->findById($id);

                        if ($stausVoucher['Voucher']['status'] == 1) {
                            $stausVoucher['Voucher']['status'] = 0;
                        } else {
                            $stausVoucher['Voucher']['status'] = 1;
                        }
                        $stausVoucher['Voucher']['id'] = $id;
                        $this->Voucher->save($stausVoucher['Voucher']);
                        echo $stausVoucher['Voucher']['status'];
                    }
                    break;

                case 'Storeoffer':
                    $stausStoreoffer = $this->Storeoffer->findById($id);
                    if (!empty($stausStoreoffer)) {
                        if ($stausStoreoffer['Storeoffer']['status'] == 1) {
                            $stausStoreoffer['Storeoffer']['status'] = 0;
                        } else {
                            $stausStoreoffer['Storeoffer']['status'] = 1;
                        }
                        $this->Storeoffer->save($stausStoreoffer['Storeoffer']);
                        echo $stausStoreoffer['Storeoffer']['status'];
                    }
                    break;

                case 'Review':
                    $stausReview = $this->Review->findById($id);
                    if (!empty($stausReview)) {
                        if ($stausReview['Review']['status'] == 1) {
                            $stausReview['Review']['status'] = 0;
                        } else {
                            $stausReview['Review']['status'] = 1;
                        }
                        $this->Review->save($stausReview['Review']);
                    }
                    break;

                case 'Deal':
                    if ($this->Auth->User('role_id') == 3) {
                        $stausDeal = $this->Deal->find('first', array(
                            'conditions' => array('Deal.id' => $id,
                                'Deal.store_id' => $this->Auth->User('Store.id'))));
                        if (!empty($stausDeal)) {
                            if ($stausDeal['Deal']['status'] == 1) {
                                $stausDeal['Deal']['status'] = 0;
                            } else {
                                $stausDeal['Deal']['status'] = 1;
                            }
                            $this->Deal->save($stausDeal['Deal']);
                            echo $stausDeal['Deal']['status'];
                        }
                    } elseif ($this->Auth->User('role_id') == 1) {
                        $stausDeal = $this->Deal->findById($id);

                        if ($stausDeal['Deal']['status'] == 1) {
                            $stausDeal['Deal']['status'] = 0;
                        } else {
                            $stausDeal['Deal']['status'] = 1;
                        }
                        $stausDeal['Deal']['id'] = $id;
                        $this->Deal->save($stausDeal['Deal']);
                        echo $stausDeal['Deal']['status'];
                    }
                    break;

                case 'Driver':
                    if ($this->Auth->User('role_id') == 3) {
                        $stausDriver = $this->Driver->find('first', array(
                            'conditions' => array('Driver.id' => $id,
                                'Driver.store_id' => $this->Auth->User('Store.id'))));

                        if (!empty($stausDriver)) {
                            if ($stausDriver['Driver']['status'] == 'Active') {
                                $stausDriver['Driver']['status'] = 'Deactive';
                            } else {
                                $stausDriver['Driver']['status'] = 'Active';
                            }
                            $this->Driver->save($stausDriver['Driver']);
                        }
                    } elseif ($this->Auth->User('role_id') == 1) {
                        $stausDriver = $this->Driver->findById($id);

                        if ($stausDriver['Driver']['status'] == 'Active') {
                            $stausDriver['Driver']['status'] = 'Deactive';
                        } else {
                            $stausDriver['Driver']['status'] = 'Active';
                        }
                        $this->Driver->save($stausDriver['Driver']);
                    }
                    break;

                case 'Store':
                    $stausStore = $this->Store->findById($id);
                    if (!empty($stausStore)) {
                        if ($stausStore['Store']['status'] == 1) {
                            $stausStore['Store']['status'] = 0;
                        } else {
                            $stausStore['Store']['status'] = 1;
                        }

                        if (!($this->Store->save($stausStore['Store']))) {
                            var_dump($this->Store->invalidFields());
                            echo "fail";
                            die();
                        } else {
                            echo $stausStore['Store']['status'];
                        }
                    }
                    break;

                case 'newsletter':
                    $stausCustomer = $this->Customer->findById($id);
                    if (!empty($stausCustomer)) {
                        if ($stausCustomer['Customer']['news_letter_option'] == 'Yes') {
                            $stausCustomer['Customer']['news_letter_option'] = 'No';
                        } else {
                            $stausCustomer['Customer']['news_letter_option'] = 'Yes';
                        }
                        $this->Customer->save($stausCustomer['Customer']);
                    }
                    break;

                case 'ContactUs':
                    $statusContact = $this->ContactUs->findById($id);
                    if (!empty($statusContact)) {
                        if ($statusContact['ContactUs']['status'] == 1) {
                            $statusContact['ContactUs']['status'] = 0;
                        } else {
                            $statusContact['ContactUs']['status'] = 1;
                        }
                        //echo "<pre>"; print_r($statusContact); die();
                        $this->ContactUs->save($statusContact['ContactUs']);
                    }
                    break;

                case 'Mainaddon':
                    $statusAddon = $this->Mainaddon->findById($id);
                    if (!empty($statusAddon)) {
                        if ($statusAddon['Mainaddon']['status'] == 1) {
                            $statusAddon['Mainaddon']['status'] = 0;
                        } else {
                            $statusAddon['Mainaddon']['status'] = 1;
                        }
                        $this->Mainaddon->save($statusAddon['Mainaddon']);
                    }
                    break;
            }
        }
        exit();
    }

    public function fetchCategories() {
        if ($this->request->is('post')) {
            $storeId = $this->request->data['id'];
            $category_list = $this->Category->find('list', array(
                'conditions' => array('Category.parent_id' => 0, 'Category.status' => 1, 'Category.store_id' => $storeId),
                'fields' => array('Category.id', 'Category.category_name')));
            print(json_encode($category_list));
        }
        exit();
    }

    /**
     * CommonsController::deleteProcess()
     * Delete Process
     * @return void
     */
    public function deleteProcess() {

        $id = $this->request->data['id'];
        $model = $this->request->data['model'];

        if (!empty($id)) {
            switch (trim($model)) {
                case 'Cuisine':
                    $statusCuisine = $this->Cuisine->findById($id);
                    if (!empty($statusCuisine)) {
                        $statusCuisine['Cuisine']['status'] = 3;
                        $this->Cuisine->save($statusCuisine['Cuisine']);
                    }
                    break;
                case 'halalproduct':
                    $HalalProduct = $this->HalalProduct->findById($id);
                    if (!empty($HalalProduct)) {
                        $this->HalalProduct->delete($id);
                    }
                    break;

                case 'Category':
                    $stausCategory = $this->Category->findById($id);
                    if (!empty($stausCategory)) {
                        $stausCategory['Category']['status'] = 3;
                        $this->Category->save($stausCategory['Category']);
                    }
                    break;

                case 'CustomerAddressBook':
                    $stausCustomerAddressBook = $this->CustomerAddressBook->findById($id);
                    if (!empty($stausCustomerAddressBook)) {
                        $this->CustomerAddressBook->delete($id);
                    }
                    break;

                case 'Product':
                    $deleteProduct = $this->Product->find('first', array(
                        'conditions' => array('Product.id' => $id)));
                    if ($this->Auth->User('role_id') == 3) {
                        if (!empty($id)) {
                            if (!empty($deleteProduct)) {
                                $deleteProduct['Product']['status'] = 3;
                                $deleteProduct['Product']['id'] = $id;
                                $this->Product->save($deleteProduct['Product']);
                            }
                        }
                    } elseif ($this->Auth->User('role_id') == 1) {
                        $deleteProduct['Product']['status'] = 3;
                        $deleteProduct['Product']['id'] = $id;
                        $this->Product->save($deleteProduct['Product']);
                    }
                    break;

                case 'City':
                    $stausCity = $this->City->findById($id);
                    if (!empty($stausCity)) {
                        $stausCity['City']['status'] = 3;
                        $this->City->save($stausCity['City']);
                    }
                    break;

                case 'Country':
                    $stausCountry = $this->Country->findById($id);
                    if (!empty($stausCountry)) {
                        $stausCountry['Country']['status'] = 3;
                        $this->Country->save($stausCountry['Country']);
                    }
                    break;

                case 'State':
                    $stausState = $this->State->findById($id);
                    if (!empty($stausState)) {
                        $stausState['State']['status'] = 3;
                        $this->State->save($stausState['State']);
                    }
                    break;

                case 'Location':
                    $stausLocation = $this->Location->findById($id);
                    if (!empty($stausLocation)) {
                        $stausLocation['Location']['status'] = 3;
                        $this->Location->save($stausLocation['Location']);
                    }
                    break;

                case 'Customer':
                    $stausCustomer = $this->Customer->findById($id);
                    if (!empty($stausCustomer)) {
                        $this->Customer->updateAll(array('Customer.status' => 3), array('Customer.id' => $id));
                        $this->User->delete($stausCustomer['Customer']['user_id']);
                    }
                    break;

                case 'Voucher':
                    $stausVoucher = $this->Voucher->findById($id);
                    if (!empty($stausVoucher)) {
                        $stausVoucher['Voucher']['status'] = 3;
                        $this->Voucher->save($stausVoucher['Voucher']);
                    }
                    break;

                case 'Storeoffer':
                    $stausStoreoffer = $this->Storeoffer->findById($id);
                    if (!empty($stausStoreoffer)) {
                        $stausStoreoffer['Storeoffer']['status'] = 3;
                        $this->Storeoffer->save($stausStoreoffer['Storeoffer']);
                    }
                    break;

                case 'Deal':
                    if ($this->Auth->User('role_id') == 3) {
                        $deleteDeal = $this->Deal->find('first', array(
                            'conditions' => array('Deal.id' => $id,
                                'Deal.store_id' => $this->Auth->User('Store.id'))));

                        if (!empty($deleteDeal)) {
                            $this->Deal->delete($id);
                        }
                    } elseif ($this->Auth->User('role_id') == 1) {
                        $this->Deal->delete($id);
                    }
                    break;

                case 'Order':
                    if (!empty($id)) {
                        if ($this->Auth->User('role_id') == 3) {
                            $deleteOrder = $this->Order->find('first', array(
                                'conditions' => array('Order.id' => $id,
                                    'Order.store_id' => $this->Auth->User('Store.id'))));

                            if (!empty($deleteOrder)) {
                                $deleteOrder['Order']['status'] = 'Failed';
                                $this->Order->save($deleteOrder['Order']);
                            }
                        } elseif ($this->Auth->User('role_id') == 1) {
                            $deleteOrder['Order']['status'] = 'Failed';
                            $deleteOrder['Order']['id'] = $id;
                            $this->Order->save($deleteOrder['Order']);
                        }
                    }
                    break;

                case 'Driver':
                    if (!empty($id)) {
                        $deleteDriver = $this->Driver->find('first', array(
                            'conditions' => array('Driver.id' => $id)));

                        $userId = $deleteDriver['Driver']['parent_id'];
                        $this->User->delete($userId);
                        /* if ($this->Auth->User('role_id') == 3) {
                          if (!empty($deleteDriver)) {
                          $deleteDriver['Driver']['status'] = 'Delete';
                          $this->Driver->save($deleteDriver['Driver']);
                          }
                          } elseif ($this->Auth->User('role_id') == 1) {
                          $deleteDriver['Driver']['status'] = 'Delete';
                          $deleteDriver['Driver']['id'] = $id;
                          $this->Driver->save($deleteDriver['Driver']);
                          } */
                    }
                    break;

                case 'Review':
                    $stausReview = $this->Review->findById($id);
                    if (!empty($stausReview)) {
                        $stausReview['Review']['status'] = 3;
                        $this->Review->save($stausReview['Review']);
                    }
                    break;

                case 'ContactUs':
                    $statusContact = $this->ContactUs->findById($id);
                    if (!empty($statusContact)) {
                        $statusContact['ContactUs']['status'] = 3;
                        $this->ContactUs->save($statusContact['ContactUs']);
                    }
                    break;

                case 'Mainaddon':
                    $statusAddon = $this->Mainaddon->findById($id);
                    if (!empty($statusAddon)) {
                        $statusAddon['Mainaddon']['status'] = 3;
                        $this->Mainaddon->save($statusAddon['Mainaddon']);
                    }
                    break;
            }
        }
        exit();
    }

    public function admin_multipleSelect() {

        $model = (isset($this->request->data['name'])) ? $this->request->data['name'] : '';
        $statusOption = (isset($this->request->data['actions'])) ? $this->request->data['actions'] : '';
        $recordsData = (isset($this->request->data['Commons'])) ? $this->request->data['Commons'] : array();

        foreach ($recordsData as $key => $value) {
            if (!empty($value)) {
                switch (trim($model)) {
                    case 'Product':
                        $stausProduct = $this->Product->findById($value);
                        if (!empty($stausProduct)) {
                            if ($statusOption == 'Active') {
                                $stausProduct['Product']['status'] = 1;
                            } elseif ($statusOption == 'Deactive') {
                                $stausProduct['Product']['status'] = 0;
                            } else {
                                $stausProduct['Product']['status'] = 3;
                            }
                            $this->Product->save($stausProduct['Product']);
                        }
                        break;

                    case 'Driver':
                        $stausDriver = $this->Driver->findById($value);
                        if (!empty($stausDriver)) {
                            if ($statusOption == 'Active') {
                                $stausDriver['Driver']['status'] = 'Active';
                            } elseif ($statusOption == 'Deactive') {
                                $stausDriver['Driver']['status'] = 'Deactive';
                            } else {
                                $stausDriver['Driver']['status'] = 'Delete';
                            }
                            $this->Driver->save($stausDriver['Driver']);
                        }
                        break;

                    case 'Deal':
                        $stausDeal = $this->Deal->findById($value);
                        if (!empty($stausDeal)) {
                            if ($statusOption == 'Active') {
                                $stausDeal['Deal']['status'] = 1;
                                $this->Deal->save($stausDeal['Deal']);
                            } elseif ($statusOption == 'Deactive') {
                                $stausDeal['Deal']['status'] = 0;
                                $this->Deal->save($stausDeal['Deal']);
                            } else {
                                $this->Deal->delete($value);
                            }
                        }
                        break;

                    case 'Storeoffer':
                        $stausStoreoffer = $this->Storeoffer->findById($value);
                        if (!empty($stausStoreoffer)) {
                            if ($statusOption == 'Active') {
                                $stausStoreoffer['Storeoffer']['status'] = 1;
                            } elseif ($statusOption == 'Deactive') {
                                $stausStoreoffer['Storeoffer']['status'] = 0;
                            } else {
                                $stausStoreoffer['Storeoffer']['status'] = 3;
                            }
                            $this->Storeoffer->save($stausStoreoffer['Storeoffer']);
                        }
                        break;

                    case 'Cuisine':
                        $statusCuisine = $this->Cuisine->findById($value);
                        if (!empty($statusCuisine)) {
                            if ($statusOption == 'Active') {
                                $statusCuisine['Cuisine']['status'] = 1;
                            } elseif ($statusOption == 'Deactive') {
                                $statusCuisine['Cuisine']['status'] = 0;
                            } else {
                                $statusCuisine['Cuisine']['status'] = 3;
                            }
                            $this->Cuisine->save($statusCuisine['Cuisine']);
                        }
                        break;

                    case 'Category':
                        $stausCategory = $this->Category->findById($value);
                        if (!empty($stausCategory)) {
                            if ($statusOption == 'Active') {
                                $stausCategory['Category']['status'] = 1;
                            } elseif ($statusOption == 'Deactive') {
                                $stausCategory['Category']['status'] = 0;
                            } else {
                                $stausCategory['Category']['status'] = 3;
                            }
                            $this->Category->save($stausCategory['Category']);
                        }
                        break;

                    case 'Store':
                        $stausStore = $this->Store->findById($value);
                        if (!empty($stausStore)) {
                            if ($statusOption == 'Active') {
                                $stausStore['Store']['status'] = 1;
                            } elseif ($statusOption == 'Deactive') {
                                $stausStore['Store']['status'] = 0;
                            } else {
                                $stausStore['Store']['status'] = 3;
                            }
                            $this->Store->save($stausStore['Store']);
                        }
                        break;

                    case 'Review':
                        $stausReview = $this->Review->findById($value);
                        if (!empty($stausReview)) {
                            if ($statusOption == 'Active') {
                                $stausReview['Review']['status'] = 1;
                            } elseif ($statusOption == 'Deactive') {
                                $stausReview['Review']['status'] = 0;
                            } else {
                                $stausReview['Review']['status'] = 3;
                            }
                            $this->Review->save($stausReview['Review']);
                        }
                        break;

                    case 'Voucher':
                        $stausVoucher = $this->Voucher->findById($value);
                        if (!empty($stausVoucher)) {
                            if ($statusOption == 'Active') {
                                $stausVoucher['Voucher']['status'] = 1;
                                $this->Voucher->save($stausVoucher['Voucher']);
                            } elseif ($statusOption == 'Deactive') {
                                $stausVoucher['Voucher']['status'] = 0;
                                $this->Voucher->save($stausVoucher['Voucher']);
                            } else {
                                $this->Voucher->delete($value);
                            }
                        }
                        break;

                    case 'Country':
                        $stausCountry = $this->Country->findById($value);
                        if (!empty($stausCountry)) {
                            if ($statusOption == 'Active') {
                                $stausCountry['Country']['status'] = 1;
                            } elseif ($statusOption == 'Deactive') {
                                $stausCountry['Country']['status'] = 0;
                            } else {
                                $stausCountry['Country']['status'] = 3;
                            }
                            $this->Country->save($stausCountry['Country']);
                        }
                        break;

                    case 'State':
                        $stausState = $this->State->findById($value);
                        if (!empty($stausState)) {
                            if ($statusOption == 'Active') {
                                $stausState['State']['status'] = 1;
                            } elseif ($statusOption == 'Deactive') {
                                $stausState['State']['status'] = 0;
                            } else {
                                $stausState['State']['status'] = 3;
                            }
                            $this->State->save($stausState['State']);
                        }
                        break;

                    case 'City':
                        $stausCity = $this->City->findById($value);
                        if (!empty($stausCity)) {
                            if ($statusOption == 'Active') {
                                $stausCity['City']['status'] = 1;
                            } elseif ($statusOption == 'Deactive') {
                                $stausCity['City']['status'] = 0;
                            } else {
                                $stausCity['City']['status'] = 3;
                            }
                            $this->City->save($stausCity['City']);
                        }
                        break;

                    case 'Location':
                        $stausLocation = $this->Location->findById($value);
                        if (!empty($stausLocation)) {
                            if ($statusOption == 'Active') {
                                $stausLocation['Location']['status'] = 1;
                            } elseif ($statusOption == 'Deactive') {
                                $stausLocation['Location']['status'] = 0;
                            } else {
                                $stausLocation['Location']['status'] = 3;
                            }
                            $this->Location->save($stausLocation['Location']);
                        }
                        break;

                    case 'Customer':
                        $stausCustomer = $this->Customer->findById($value);
                        if (!empty($stausCustomer)) {
                            if ($statusOption == 'Active') {
                                $this->Customer->updateAll(array('Customer.status' => 1), array('Customer.id' => $value));
                            } elseif ($statusOption == 'Deactive') {
                                $this->Customer->updateAll(array('Customer.status' => 0), array('Customer.id' => $value));
                            } else {
                                $this->Customer->updateAll(array('Customer.status' => 3), array('Customer.id' => $value));
                            }
                        }
                        break;

                    case 'CustomerAddressBook':
                        $stausCustomerAddressBook = $this->CustomerAddressBook->findById($value);
                        if (!empty($stausCustomerAddressBook)) {
                            if ($statusOption == 'Active') {
                                $stausCustomerAddressBook['CustomerAddressBook']['status'] = 1;
                                $this->CustomerAddressBook->save($stausCustomerAddressBook['CustomerAddressBook']);
                            } elseif ($statusOption == 'Deactive') {
                                $stausCustomerAddressBook['CustomerAddressBook']['status'] = 0;
                                $this->CustomerAddressBook->save($stausCustomerAddressBook['CustomerAddressBook']);
                            } else {
                                $this->CustomerAddressBook->delete($value);
                            }
                        }
                        break;

                    case 'ContactUs':
                        $statusContact = $this->ContactUs->findById($value);
                        if (!empty($statusCuisine)) {
                            if ($statusOption == 'Active') {
                                $statusContact['ContactUs']['status'] = 1;
                            } elseif ($statusOption == 'Deactive') {
                                $statusContact['ContactUs']['status'] = 0;
                            } else {
                                $statusContact['ContactUs']['status'] = 3;
                            }
                            $this->ContactUs->save($statusContact['ContactUs']);
                        }
                        break;

                    case 'Mainaddon':
                        $statusAddon = $this->Mainaddon->findById($value);
                        if (!empty($statusAddon)) {
                            if ($statusOption == 'Active') {
                                $statusAddon['Mainaddon']['status'] = 1;
                            } elseif ($statusOption == 'Deactive') {
                                $statusAddon['Mainaddon']['status'] = 0;
                            } else {
                                $statusAddon['Mainaddon']['status'] = 3;
                            }
                            $this->Mainaddon->save($statusAddon['Mainaddon']);
                        }
                        break;
                }
            }
        }

        switch (trim($model)) {
            case 'Product':
                $this->redirect(array('controller' => 'products', 'action' => 'index', $this->request->data['Store']['Storeproduct'], 'admin' => true));
                break;
            case 'Driver':
                $this->redirect(array('controller' => 'drivers', 'action' => 'index', 'admin' => true));
                break;
            case 'Deal':
                $this->redirect(array('controller' => 'deals', 'action' => 'index', 'admin' => true));
                break;
            case 'Storeoffer':
                $this->redirect(array('controller' => 'storeoffers', 'action' => 'index', 'admin' => true));
                break;
            case 'Cuisine':
                $this->redirect(array('controller' => 'cuisines', 'action' => 'index', 'admin' => true));
                break;
            case 'Category':
                if (isset($this->request->data['categoryType'])) {
                    $this->redirect(array('controller' => 'categories', 'action' => 'subCatIndex', 'admin' => true));
                } else {
                    $this->redirect(array('controller' => 'categories', 'action' => 'index', 'admin' => true));
                }
                break;
            case 'Store':
                $this->redirect(array('controller' => 'stores', 'action' => 'index', 'admin' => true));
                break;
            case 'Review':
                $this->redirect(array('controller' => 'reviews', 'action' => 'list', 'admin' => true));
                break;
            case 'Voucher':
                $this->redirect(array('controller' => 'vouchers', 'action' => 'index', 'admin' => true));
                break;
            case 'Country':
                $this->redirect(array('controller' => 'countries', 'action' => 'index', 'admin' => true));
                break;
            case 'State':
                $this->redirect(array('controller' => 'states', 'action' => 'index', 'admin' => true));
                break;
            case 'City':
                $this->redirect(array('controller' => 'cities', 'action' => 'index', 'admin' => true));
                break;
            case 'Location':
                $this->redirect(array('controller' => 'locations', 'action' => 'index', 'admin' => true));
                break;
            case 'Customer':
                $this->redirect(array('controller' => 'customers', 'action' => 'index', 'admin' => true));
                break;
            case 'CustomerAddressBook':
                $this->redirect(array('controller' => 'customers', 'action' => 'index', 'admin' => true));
                break;
            case 'ContactUs':
                $this->redirect(array('controller' => 'contactuses', 'action' => 'index', 'admin' => true));
                break;
            case 'Mainaddon':
                $this->redirect(array('controller' => 'addons', 'action' => 'index', $this->request->data['Store']['Storeproduct'], 'admin' => true));
                break;
        }
        exit();
    }

    public function store_multipleSelect() {

        $model = (isset($this->request->data['name'])) ? $this->request->data['name'] : '';
        $statusOption = (isset($this->request->data['actions'])) ? $this->request->data['actions'] : '';
        $recordsData = (isset($this->request->data['Commons'])) ? $this->request->data['Commons'] : array();

        foreach ($recordsData as $key => $value) {
            if ($this->Auth->User('role_id') == 3) {
                switch (trim($model)) {
                    case 'Product':
                        $stausProduct = $this->Product->find('first', array(
                            'conditions' => array('Product.id' => $value,
                                'Product.store_id' => $this->Auth->User('Store.id'))));
                        if (!empty($stausProduct)) {
                            if ($statusOption == 'Active') {
                                $stausProduct['Product']['status'] = 1;
                            } elseif ($statusOption == 'Deactive') {
                                $stausProduct['Product']['status'] = 0;
                            } else {
                                $stausProduct['Product']['status'] = 3;
                            }
                            $this->Product->save($stausProduct['Product']);
                        }
                        break;

                    case 'Driver':
                        $stausDriver = $this->Driver->find('first', array(
                            'conditions' => array('Driver.id' => $value,
                                'Driver.store_id' => $this->Auth->User('Store.id'))));
                        if (!empty($stausDriver)) {
                            if ($statusOption == 'Active') {
                                $stausDriver['Driver']['status'] = 'Active';
                            } elseif ($statusOption == 'Deactive') {
                                $stausDriver['Driver']['status'] = 'Deactive';
                            } else {
                                $stausDriver['Driver']['status'] = 'Delete';
                            }
                            $this->Driver->save($stausDriver['Driver']);
                        }
                        break;

                    case 'Deal':
                        $stausDeal = $this->Deal->find('first', array(
                            'conditions' => array('Deal.id' => $value,
                                'Deal.store_id' => $this->Auth->User('Store.id'))));
                        if (!empty($stausDeal)) {
                            if ($statusOption == 'Active') {
                                $stausDeal['Deal']['status'] = 1;
                                $this->Deal->save($stausDeal['Deal']);
                            } elseif ($statusOption == 'Deactive') {
                                $stausDeal['Deal']['status'] = 0;
                                $this->Deal->save($stausDeal['Deal']);
                            } else {
                                $this->Deal->delete($value);
                            }
                        }
                        break;

                    case 'Storeoffer':
                        $stausStoreoffer = $this->Storeoffer->find('first', array(
                            'conditions' => array('Storeoffer.id' => $value,
                                'Storeoffer.store_id' => $this->Auth->User('Store.id'))));
                        if (!empty($stausStoreoffer)) {
                            if ($statusOption == 'Active') {
                                $stausStoreoffer['Storeoffer']['status'] = 1;
                            } elseif ($statusOption == 'Deactive') {
                                $stausStoreoffer['Storeoffer']['status'] = 0;
                            } else {
                                $stausStoreoffer['Storeoffer']['status'] = 3;
                            }
                            $this->Storeoffer->save($stausStoreoffer['Storeoffer']);
                        }
                        break;

                    case 'Voucher':
                        $stausVoucher = $this->Voucher->find('first', array(
                            'conditions' => array('Voucher.id' => $value,
                                'Voucher.store_id' => $this->Auth->User('Store.id'))));
                        if (!empty($stausVoucher)) {
                            if ($statusOption == 'Active') {
                                $stausVoucher['Voucher']['status'] = 1;
                                $this->Voucher->save($stausVoucher['Voucher']);
                            } elseif ($statusOption == 'Deactive') {
                                $stausVoucher['Voucher']['status'] = 0;
                                $this->Voucher->save($stausVoucher['Voucher']);
                            } else {
                                $this->Voucher->delete($value);
                            }
                        }
                        break;

                    case 'Cuisine':
                        $statusCuisine = $this->Cuisine->findById($value);
                        if (!empty($statusCuisine)) {
                            if ($statusOption == 'Active') {
                                $statusCuisine['Cuisine']['status'] = 1;
                            } elseif ($statusOption == 'Deactive') {
                                $statusCuisine['Cuisine']['status'] = 0;
                            } else {
                                $statusCuisine['Cuisine']['status'] = 3;
                            }
                            $this->Cuisine->save($statusCuisine['Cuisine']);
                        }
                        break;

                    case 'Category':
                        $stausCategory = $this->Category->findById($value);
                        if (!empty($stausCategory)) {
                            if ($statusOption == 'Active') {
                                $stausCategory['Category']['status'] = 1;
                            } elseif ($statusOption == 'Deactive') {
                                $stausCategory['Category']['status'] = 0;
                            } else {
                                $stausCategory['Category']['status'] = 3;
                            }
                            $this->Category->save($stausCategory['Category']);
                        }
                        break;

                    case 'ContactUs':
                        $statusContact = $this->ContactUs->findById($value);
                        if (!empty($statusContact)) {
                            if ($statusOption == 'Active') {
                                $statusContact['ContactUs']['status'] = 1;
                            } elseif ($statusOption == 'Deactive') {
                                $statusContact['ContactUs']['status'] = 0;
                            } else {
                                $statusContact['ContactUs']['status'] = 3;
                            }
                            $this->ContactUs->save($statusContact['ContactUs']);
                        }
                        break;

                    case 'Mainaddon':
                        $statusAddon = $this->Mainaddon->findById($value);
                        if (!empty($statusAddon)) {
                            if ($statusOption == 'Active') {
                                $statusAddon['Mainaddon']['status'] = 1;
                            } elseif ($statusOption == 'Deactive') {
                                $statusAddon['Mainaddon']['status'] = 0;
                            } else {
                                $statusAddon['Mainaddon']['status'] = 3;
                            }
                            $this->Mainaddon->save($statusAddon['Mainaddon']);
                        }
                        break;
                }
            }
        }

        switch (trim($model)) {
            case 'Product':
                $this->redirect(array('controller' => 'products', 'action' => 'index', 'store' => true));
                break;
            case 'Driver':
                $this->redirect(array('controller' => 'drivers', 'action' => 'index', 'store' => true));
                break;
            case 'Deal':
                $this->redirect(array('controller' => 'deals', 'action' => 'index', 'store' => true));
                break;
            case 'Storeoffer':
                $this->redirect(array('controller' => 'storeoffers', 'action' => 'index', 'store' => true));
                break;
            case 'Voucher':
                $this->redirect(array('controller' => 'vouchers', 'action' => 'index', 'store' => true));
                break;
            case 'Cuisine':
                $this->redirect(array('controller' => 'cuisines', 'action' => 'index', 'store' => true));
                break;
            case 'Category':
                if (isset($this->request->data['categoryType'])) {
                    $this->redirect(array('controller' => 'categories', 'action' => 'subCatIndex', 'admin' => true));
                } else {
                    $this->redirect(array('controller' => 'categories', 'action' => 'index', 'admin' => true));
                }
                break;
            case 'ContactUs':
                $this->redirect(array('controller' => 'contactuses', 'action' => 'index', 'store' => true));
                break;
            case 'Mainaddon':
                $this->redirect(array('controller' => 'addons', 'action' => 'index', 'store' => true));
                break;
        }
        exit();
    }

    public function getDateTime($storeid, $postDate) {

        $currentday = date('d-m-Y');
        $storeid = (!empty($_POST['storeid'])) ? $_POST['storeid'] : $storeid;
        $date = (!empty($postDate)) ? $postDate : $currentday;
        $day = date("D", strtotime($date));

        if ($day == 'Mon') {
            $day = 'Lun';
        } elseif ($day == 'Tue') {
            $day = 'Mar';
        } elseif ($day == 'Wed') {
            $day = 'Mer';
        } elseif ($day == 'Thu') {
            $day = 'Jeu';
        } elseif ($day == 'Fri') {
            $day = 'Ven';
        } elseif ($day == 'Sat') {
            $day = 'Sam';
        } elseif ($day == 'Sun') {
            $day = 'Dim';
        }

        // echo "<pre>";print_r($day);echo "</pre>";exit;
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

                    $content .= '<option value="' . date("H:i", $i) . '">' . $day . '  - ' . date("H:i", $i) . '</option>' . "\n";
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

                    $content .= '<option value="' . date("H:i", $i) . '">' . $day . '  - ' . date("H:i", $i) . '</option>' . "\n";
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

                    $content .= '<option value="' . date("H:i", $i) . '">' . $day . '  - ' . date("H:i", $i) . '</option>' . "\n";
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
                    $content .= '<option value="' . date("H:i", $i) . '">' . $day . '  - ' . date("H:i", $i) . '</option>' . "\n";
                }
            }
        } else {
            $content = '<option value="">Fermé</option>';
        }

        if (empty($content)) {
            $content = '<option value="">Fermé</option>';
        }

        return array($content, $openStatus, $firstStatus, $secondStatus);
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

    #openandcloseDelivery
    /**
     * Site::openandcloseDelivery()
     *
     * @return
     */

    public function openandcloseDelivery($rrr_opentime, $rrr_closetime) {
        $ffirst_open_time = $ffirst_close_time = $fsec_open_time = $fsec_close_time = '';
        $rrr_nowtime = date("h:i A");
        $dec_rrr_nowtime = strtotime($rrr_nowtime);

        if (isset($rrr_opentime) && !empty($rrr_opentime))
            $dec_rrr_opentime = strtotime($rrr_opentime);
        if (isset($rrr_closetime) && !empty($rrr_closetime))
            $dec_rrr_closetime = strtotime($rrr_closetime);

        //Current Day
        if (!empty($dec_rrr_opentime) && !empty($dec_rrr_closetime)) {
            if (($dec_rrr_nowtime > $dec_rrr_opentime) && ($dec_rrr_closetime > $dec_rrr_nowtime)) {
                $openclosetype = "Open";
                //For Delivery Hours
                $sec_open_time = $dec_rrr_opentime;
                $sec_close_time = $dec_rrr_closetime;
            } //2day
            elseif ($dec_rrr_opentime > $dec_rrr_closetime) {
                //For Delivery Hours
                $sec_open_time = $dec_rrr_opentime;
                $sec_close_time = strtotime("11:59 PM");

                #Time status
                if (($dec_rrr_nowtime > $sec_open_time) && ($sec_close_time > $dec_rrr_nowtime)) {
                    $openclosetype = "Open";
                } else {
                    $openclosetype = "Closed";
                }
            } else {
                $sec_open_time = $dec_rrr_opentime;
                $sec_close_time = $dec_rrr_closetime;
                $openclosetype = "Closed";
            }
        }

        #open closetype
        $openclosetype = ($openclosetype == 'Open') ? 'Open' : 'Close';

        if (isset($first_open_time) && !empty($first_open_time))
            $ffirst_open_time = date("h:i A", $first_open_time);
        if (isset($first_close_time) && !empty($first_close_time))
            $ffirst_close_time = date("h:i A", $first_close_time);
        if (isset($sec_open_time) && !empty($sec_open_time))
            $fsec_open_time = date("h:i A", $sec_open_time);
        if (isset($sec_close_time) && !empty($sec_close_time))
            $fsec_close_time = date("h:i A", $sec_close_time);

        return array(
            $openclosetype,
            $ffirst_open_time,
            $ffirst_close_time,
            $fsec_open_time,
            $fsec_close_time);
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

    // Voucher Code Check 
    public function voucherCodeCheck($storeId = null, $voucherCode = null) {

        $Today = date("m/d/Y");
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
                    return true;
                } else {
                    return $voucherDetails;
                }
            }
            return $voucherDetails;
        }
        return true;
    }

}
