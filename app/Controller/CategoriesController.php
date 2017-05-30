<?php

/* janakiraman */
App::uses('AppController', 'Controller');

class CategoriesController extends AppController {

    public $helpers = array('Html', 'Form', 'Session', 'Javascript');
    public $uses = array('Category', 'Mainaddon', 'Product', 'Store');

    /**
     * CategoriesController::admin_index()
     * Categories Management Process
     * @return void
     */
    public function admin_index() {
        $Category_list = $this->Category->find('all', array(
            'conditions' => array(
                'AND' => array('Category.parent_id' => 0),
                'NOT' => array('Category.status' => 3)),
            'order' => array('Category.id DESC'),
        ));
        $this->set('Category_list', $Category_list);
    }

    /**
     * CategoriesController::admin_add()
     * Categories Add Process
     * @return void
     */
    public function admin_add() {

        if ($this->request->is('post')) {
            $this->Category->set($this->request->data);
            if ($this->Category->validates()) {

                $Category_check = $this->Category->find('first', array(
                    'conditions' => array(
                        'Category.parent_id' => 0,
                        'Category.category_name' => trim($this->request->data['Category']['category_name']),
                        'Category.store_id' => $this->request->data['Category']['store_id'],
                        'NOT' => array('Category.status' => 3))));

                if (!empty($Category_check)) {
                    $this->Session->setFlash('<p>' . __('Impossible d’ajouter vos catégories', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {
                    $this->Category->save($this->request->data, null, null);
                    $this->Session->setFlash('<p>' . __('Votre catégorie a été enregistrée', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'Categories', 'action' => 'index'));
                }
            } else {
                $this->Category->validationErrors;
            }
        }
        $this->Store->virtualFields = array(
            'storewithId' => "CONCAT(Store.store_name, ' (',Store.id,')')"
        );
        $stores = $this->Store->find('list', array(
            'conditions' => array('Store.status' => 1),
            'fields' => array('Store.id', 'Store.storewithId')));
        $this->set('stores', $stores);
    }

    /**
     * CategoriesController::admin_edit()
     * Categories Edit Process
     * @param mixed $id
     * @return void
     */
    public function admin_edit($id = null) {

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Category->set($this->request->data);
            if ($this->Category->validates()) {

                $Category = $this->Category->find('first', array(
                    'conditions' => array(
                        'Category.parent_id' => 0,
                        'Category.category_name' => trim($this->request->data['Category']['category_name']),
                        'NOT' => array('Category.id' => $this->request->data['Category']['id'],
                            'Category.status' => 3))));


                if (!empty($Category)) {
                    $this->Session->setFlash('<p>' . __('Impossible d’ajouter vos catégories', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {
                    $this->Category->save($this->request->data, null, null);
                    $this->Session->setFlash('<p>' . __('Votre catégorie a été enregistrée', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'Categories', 'action' => 'index'));
                }
            } else {
                $this->Category->validationErrors;
            }
        }
        $getStateData = $this->Category->findById($id);
        $this->request->data = $getStateData;
    }

    public function store_add() {

        $this->layout = 'assets';
        $stores_id = $this->Auth->User();
        $store_id = $stores_id['Store']['id'];
        if ($this->request->is('post')) {
            $this->Category->set($this->request->data);
            if ($this->Category->validates()) {
                if (!empty($this->request->data['Category']['store_id']) && ($this->request->data['Category']['store_id'] == $store_id)) {
                    $Category_check = $this->Category->find('all', array(
                        'conditions' => array('Category.parent_id' => 0,
                            'Category.category_name' => trim($this->request->data['Category']['category_name']),
                            'AND' => array('Category.store_id' => $store_id),
                            'NOT' => array('Category.status' => 3))));
                    if (!empty($Category_check)) {
                        $this->Session->setFlash('<p>' . __('Impossible d’ajouter vos catégories', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                    } else {
                        $this->Category->save($this->request->data, null, null);
                        $this->Session->setFlash('<p>' . __('Votre catégorie a été enregistrée', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                        $this->redirect(array('controller' => 'Categories', 'action' => 'index'));
                    }
                } else {
                    $this->Session->setFlash('<p>Vous avez essayé de modifier le formulaire</p>', 'default', array('class' => 'alert alert-danger'));
                }
            } else {
                $this->Category->validationErrors;
            }
        }
        $this->set('store_id', $store_id);
    }

    public function store_index() {

        $status = $this->params['url']['status'];
        $this->layout = 'assets';
        $check = array();
        $check1 = "";
        $check2 = "";
        //$id = $this->Auth->User();
        if ($status == "0" || $status == "1") {
            $check = array('Category.status' => $status);
            $check1 = array('Mainaddon.status' => $status);
            $check2 = array('Product.status' => $status);
        }

        $stores_id = $this->Auth->User();
        $store_id = $stores_id['Store']['id'];

        $Category_list = $this->Category->find('all', array(
            'conditions' => array(
                'AND' => array('Category.parent_id' => 0, $check, 'Category.store_id' => $store_id),
                'NOT' => array('Category.status' => 3)),
            'order' => array('Category.id DESC'),
            'fields' => array(
                'Category.id', 'Category.category_name', 'Category.status')));
        $this->set('Category_list', $Category_list);

        $Category_listingsForAddOnAndProducts = $this->Category->find('all', array(
            'conditions' => array(
                'AND' => array('Category.parent_id' => 0, 'Category.store_id' => $store_id, 'Category.status' => 1)),
            //'NOT' => array('Category.status' => 3)),
            'order' => array('Category.id DESC'),
            'fields' => array(
                'Category.id', 'Category.category_name', 'Category.status')));
        $this->set('Category_listingsForAddOnAndProducts', $Category_listingsForAddOnAndProducts);


        $products_detail = array();
        $id = $this->Auth->User();
        if (!empty($Category_listingsForAddOnAndProducts)) {
            for ($i = 0; $i < sizeof($Category_listingsForAddOnAndProducts); $i++) {
                //Gestion Menu controller index

                $products_detailItem = $this->Product->find('all', array(
                    'conditions' => array(
                        'AND' => array('Product.store_id' => $id['Store']['id'], 'Product.category_id' => $Category_listingsForAddOnAndProducts[$i]['Category']['id'], $check2),
                        'NOT' => array(
                            'Product.status' => 3
                        )
                    ),
                    'order' => array(
                        'Product.id DESC'
                    )
                ));

                array_push($products_detail, $products_detailItem);
            }
        }

        $store_logo = $id["Store"]["store_logo"];
        $store_url = Router::url('/', true) . "storelogos/" . $id["Store"]["store_logo"];
        $store_id = $id["Store"]["id"];
        $store_name = $id["Store"]["store_name"] . " ($store_id)";
        $this->set('products_detail', $products_detail);
        $this->set('store_url', $store_url);
        $this->set('store_logo', $store_logo);
        $this->set('store_name', $store_name);
    }

    public function store_edit($id = null) {
        $this->layout = 'assets';
        $stores_id = $this->Auth->User();
        $store_id = $stores_id['Store']['id'];

        $checkCategory = $this->Category->find('first', array(
            'conditions' => array(
                'Category.parent_id' => 0,
                'Category.store_id' => $store_id,
                'Category.id' => $id,
                'NOT' => array('Category.status' => 3)
                )
        ));
        if (!empty($checkCategory) && !empty($id)) {

            if ($this->request->is('post') || $this->request->is('put')) {
                $this->Category->set($this->request->data);
                if ($this->Category->validates()) {
                    $Category = $this->Category->find('first', array(
                        'conditions' => array(
                            'Category.parent_id' => 0,
                            'Category.category_name' => trim($this->request->data['Category']['category_name']),
                            'NOT' => array('Category.id' => $this->request->data['Category']['id'],
                                'Category.status' => 3))));

                    if (!empty($Category)) {
                        $this->Session->setFlash('<p>' . __('Impossible d’ajouter vos catégories', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                    } else {
                        $this->Category->save($this->request->data, null, null);
                        $this->Session->setFlash('<p>' . __('Votre catégorie a été enregistrée', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                        $this->redirect(array('controller' => 'Categories', 'action' => 'index'));
                    }
                } else {
                    $this->Category->validationErrors;
                }
            }
            $getStateData = $this->Category->findById($id);
            $this->request->data = $getStateData;
        } else {
            $this->Category->save($this->request->data, null, null);
            $this->Session->setFlash('<p>' . __('Catégorie non valide', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
            $this->redirect(array('controller' => 'Categories', 'action' => 'index '));
        }
    }

}
