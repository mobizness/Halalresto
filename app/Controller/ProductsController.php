<?php

/* Manikandan N */

App::uses('AppController', 'Controller');
App::uses('Spreadsheet_Excel_Reader', 'Vendor');

class ProductsController extends AppController {

    var $helpers = array('Html', 'Session', 'Javascript', 'Ajax', 'Common');
    public $uses = array('Product', 'Category', 'Store', 'ProductDetail',
        'ProductImage', 'User', 'Customer', 'Store', 'ProductAddon');
    public $components = array('Img', 'Updown');

    /**
     * ProductsController::admin_index()
     * 
     * @return void
     */
    public function admin_index($storeId = null) {

        if ($storeId != '') {
            $products_detail = $this->Product->find('all', array(
                'conditions' => array('Product.store_id' => $storeId,
                    'NOT' => array('Product.status' => 3)),
                'order' => array('Product.id DESC')));
        } else {
            $products_detail = array();
        }

        $this->Store->virtualFields = array(
            'storewithId' => "CONCAT(Store.store_name, ' (',Store.id,')')"
        );
        $stores = $this->Store->find('list', array(
            'conditions' => array('Store.status' => 1),
            'fields' => array('Store.id', 'Store.storewithId')));

        $this->request->data['Store']['Storeproduct'] = $storeId;

        $this->set(compact('products_detail', 'stores', 'storeId'));
    }

    /**
     * ProductsController::admin_add()
     * 
     * @return void
     */
    //super admin add process
    public function admin_add() {

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Product->set($this->request->data);

            if ($this->Product->validates()) {
                $store_id = $this->request->data['Product']['store_id'];
                $Product_check = $this->Product->find('all', array(
                    'conditions' => array(
                        'Product.product_name' => trim($this->request->data['Product']['product_name']),
                        'Product.store_id' => $store_id,
                        'Product.category_id' => $this->request->data['Product']['category_id'],
                        'NOT' => array('Product.status' => 3))));
                if (!empty($Product_check)) {

                    $this->Session->setFlash('<p>' . __('Le menu existe déjà', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {

                    $this->request->data['Product']['store_id'] = $store_id;
                    $this->Product->save($this->request->data['Product'], null, null);

                    if ($this->request->data['Product']['price_option'] == "single") {
                        $this->request->data['ProductDetail']['product_id'] = $this->Product->id;
                        $this->request->data['ProductDetail']['sub_name'] = (!empty($this->request->data['ProductDetail']['sub_name'])) ?
                                $this->request->data['ProductDetail']['sub_name'] :
                                $this->request->data['Product']['product_name'];

                        $this->ProductDetail->save($this->request->data['ProductDetail'], null, null);
                    } else {
                        $productDetails = $this->request->data['ProductDetail'];
                        foreach ($productDetails as $key => $value) {
                            if (is_array($value)) {
                                $value['product_id'] = $this->Product->id;
                                $this->ProductDetail->save($value, null, null);
                                $productDetailArray[] = $this->ProductDetail->id;
                                $this->ProductDetail->id = '';
                            }
                        }
                    }

                    if ($this->request->data['Product']['product_addons'] == "Yes") {
                        //Addons
                        $addonDetails = $this->request->data['ProductAddon'];
                        $categoryId = $this->request->data['Product']['category_id'];

                        $j = '';
                        foreach ($addonDetails as $k => $v) {
                            foreach ($v['Subaddon'] as $subkey => $subval) {
                                if (!empty($subval) && isset($subval['subaddons_id'])) {
                                    if ($this->request->data['Product']['price_option'] == "single") {
                                        $subval['product_id'] = $this->Product->id;
                                        $subval['store_id'] = $store_id;
                                        $subval['category_id'] = $categoryId;
                                        $subval['mainaddons_id'] = $v['mainaddons_id'];
                                        $subval['price_option'] = $this->request->data['Product']['price_option'];
                                        $subval['productdetails_id'] = $this->ProductDetail->id;

                                        $this->ProductAddon->save($subval, null, null);
                                        $this->ProductAddon->id = '';
                                    } else {
                                        $i = ($j == 0) ? count($productDetailArray) : $j;
                                        foreach ($subval['subaddons_price'] as $subPriceKey => $subPriceVal) {
                                            $k = count($productDetailArray) - $i;
                                            $j = $i - 1;
                                            $i = ($j == 0) ? count($productDetailArray) : $j;

                                            $subPrice['product_id'] = $this->Product->id;
                                            $subPrice['store_id'] = $store_id;
                                            $subPrice['category_id'] = $categoryId;
                                            $subPrice['mainaddons_id'] = $v['mainaddons_id'];
                                            $subPrice['subaddons_id'] = $subval['subaddons_id'];
                                            $subPrice['price_option'] = $this->request->data['Product']['price_option'];
                                            $subPrice['productdetails_id'] = $productDetailArray[$k];
                                            $subPrice['subaddons_price'] = ($subPriceVal != '') ? $subPriceVal : '0.00';

                                            $this->ProductAddon->save($subPrice, null, null);
                                            $this->ProductAddon->id = '';
                                        }
                                    }
                                }
                            }
                        }
                    }

                    #Product image Upload
                    if (!file_exists(WWW_ROOT . DS . 'stores' . DS . $store_id)) {
                        $this->Img->mkdir(WWW_ROOT . DS . 'stores' . DS . $store_id);
                        $path = WWW_ROOT . DS . 'stores' . DS . $store_id;
                        $this->Img->mkdir($path . DS . "products");
                        $path = $path . DS . "products";
                        $this->Img->mkdir($path . DS . "home");
                        $this->Img->mkdir($path . DS . "carts");
                        $this->Img->mkdir($path . DS . "product_details");
                        $this->Img->mkdir($path . DS . "scrollimg");
                        $this->Img->mkdir($path . DS . "original");
                    }

                    $root = WWW_ROOT . DS . 'stores' . DS . $store_id . DS . "products" . DS;
                    $origpath = $root . "original" . DS;
                    $homepath = $root . "home" . DS;
                    $cartpath = $root . "carts" . DS;
                    $scrollimg = $root . "scrollimg" . DS;
                    $prod_det_path = $root . "product_details" . DS;

                    $allowed_ext = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif');

                    $upload_file = $this->request->data['ProductImage']['tmp_name'];
                    $file = $this->request->data['ProductImage']['name'];
                    $type = $this->request->data['ProductImage']['type'];

                    if (!empty($upload_file)) {
                        $imagesizedata = getimagesize($upload_file);
                        if ($imagesizedata) {
                            if ($file != "" && in_array($type, $allowed_ext)) {
                                $newName = $file;
                                $targetdir = $origpath . DS;

                                #Upload
                                $upload = $this->Img->upload($upload_file, $targetdir, $newName);

                                #Resize
                                $this->Img->resampleGD($targetdir . DS . $newName, $homepath, $newName, 440, 300, 1, 0);
                                $this->Img->resampleGD($targetdir . DS . $newName, $cartpath, $newName, 78, 64, 1, 0);
                                $this->Img->resampleGD($targetdir . DS . $newName, $scrollimg, $newName, 67, 55, 1, 0);
                                $this->Img->resampleGD($targetdir . DS . $newName, $prod_det_path, $newName, 1024, 768, 1, 0);

                                $product_images['id'] = $this->Product->id;
                                $product_images['product_image'] = $file;

                                $this->Product->save($product_images);
                            }
                        }
                    }

                    $this->Session->setFlash('<p>' . __('Votre menu a été enregistré', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'Products', 'action' => 'index', $store_id));
                }
            } else {
                $this->Product->validationErrors;
            }
        }
        $this->Store->virtualFields = array(
            'storewithId' => "CONCAT(Store.store_name, ' (',Store.id,')')"
        );
        $stores = $this->Store->find('list', array(
            'conditions' => array('Store.status' => 1),
            'fields' => array('Store.id', 'Store.storewithId')));

        $this->set(compact('category_list', 'stores'));
    }

    /**
     * ProductsController::admin_edit()
     *
     * @param mixed $id
     * @return void
     */
    public function admin_edit($id = null) {

        if ($this->request->is('post') || $this->request->is('put')) {
            //echo '<pre>'; print_r($this->request->data); die();
            $this->Product->set($this->request->data);
            if ($this->Product->validates()) {
                $store_id = $this->request->data['Product']['store_id'];
                $product_check = $this->Product->find('first', array(
                    'conditions' => array(
                        'Product.product_name' => trim($this->request->data['Product']['product_name']),
                        'Product.store_id' => $store_id,
                        'NOT' => array('Product.id' => $this->request->data['Product']['id'],
                            'Product.status' => 3))));
                if (!empty($product_check)) {
                    $this->Session->setFlash('<p>' . __('Le menu existe déjà', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {
                    $this->Product->save($this->request->data['Product'], null, null);
                    $this->ProductDetail->deleteAll(array('product_id' => $this->Product->id));
                    if ($this->request->data['Product']['price_option'] == "single") {
                        $this->request->data['ProductDetail']['product_id'] = $this->Product->id;
                        $this->request->data['ProductDetail']['sub_name'] = (!empty($this->request->data['ProductDetail']['sub_name'])) ?
                                $this->request->data['ProductDetail']['sub_name'] :
                                $this->request->data['Product']['product_name'];
                        $this->ProductDetail->save($this->request->data['ProductDetail'], null, null);
                    } else {
                        $productDetails = $this->request->data['ProductDetail'];
                        foreach ($productDetails as $key => $value) {
                            if (is_array($value)) {
                                $value['product_id'] = $this->Product->id;
                                $this->ProductDetail->save($value, null, null);
                                $productDetailArray[] = $this->ProductDetail->id;
                                $this->ProductDetail->id = '';
                            }
                        }
                    }
                    if ($this->request->data['Product']['product_addons'] == "Yes") {
                        //Addons
                        $addonDetails = $this->request->data['ProductAddon'];
                        $categoryId = $this->request->data['Product']['category_id'];
                        $this->ProductAddon->deleteAll(array(
                            'product_id' => $this->Product->id
                        ));

                        $j = '';
                        foreach ($addonDetails as $k => $v) {
                            foreach ($v['Subaddon'] as $subkey => $subval) {
                                if (!empty($subval) && isset($subval['subaddons_id'])) {
                                    if ($this->request->data['Product']['price_option'] == "single") {
                                        $subval['product_id'] = $this->Product->id;
                                        $subval['store_id'] = $store_id;
                                        $subval['category_id'] = $categoryId;
                                        $subval['mainaddons_id'] = $v['mainaddons_id'];
                                        $subval['price_option'] = $this->request->data['Product']['price_option'];
                                        $subval['productdetails_id'] = $this->ProductDetail->id;
                                        $this->ProductAddon->save($subval, null, null);
                                        $this->ProductAddon->id = '';
                                    } else {
                                        $i = ($j == 0) ? count($productDetailArray) : $j;
                                        foreach ($subval['subaddons_price'] as $subPriceKey => $subPriceVal) {
                                            $k = count($productDetailArray) - $i;
                                            $j = $i - 1;
                                            $i = ($j == 0) ? count($productDetailArray) : $j;

                                            $subPrice['product_id'] = $this->Product->id;
                                            $subPrice['store_id'] = $store_id;
                                            $subPrice['category_id'] = $categoryId;
                                            $subPrice['mainaddons_id'] = $v['mainaddons_id'];
                                            $subPrice['subaddons_id'] = $subval['subaddons_id'];
                                            $subPrice['price_option'] = $this->request->data['Product']['price_option'];
                                            $subPrice['productdetails_id'] = $productDetailArray[$k];
                                            $subPrice['subaddons_price'] = ($subPriceVal != '') ? $subPriceVal : '0.00';
                                            $this->ProductAddon->save($subPrice, null, null);
                                            $this->ProductAddon->id = '';
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $root = WWW_ROOT . DS . 'stores' . DS . $store_id . DS . "products" . DS;
                    $origpath = $root . "original" . DS;
                    $homepath = $root . "home" . DS;
                    $cartpath = $root . "carts" . DS;
                    $scrollimg = $root . "scrollimg" . DS;
                    $prod_det_path = $root . "product_details" . DS;

                    $allowed_ext = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif');

                    $upload_file = $this->request->data['ProductImage']['tmp_name'];
                    $file = $this->request->data['ProductImage']['name'];
                    $type = $this->request->data['ProductImage']['type'];
                    if (!empty($upload_file)) {
                        $imagesizedata = getimagesize($upload_file);
                        if ($imagesizedata) {
                            if ($file != "" && in_array($type, $allowed_ext)) {
                                $newName = $file;
                                $targetdir = $origpath . DS;

                                #Upload
                                $upload = $this->Img->upload($upload_file, $targetdir, $newName);

                                #Resize
                                $this->Img->resampleGD($targetdir . DS . $newName, $homepath, $newName, 440, 300, 1, 0);
                                $this->Img->resampleGD($targetdir . DS . $newName, $cartpath, $newName, 78, 64, 1, 0);
                                $this->Img->resampleGD($targetdir . DS . $newName, $scrollimg, $newName, 67, 55, 1, 0);
                                $this->Img->resampleGD($targetdir . DS . $newName, $prod_det_path, $newName, 1024, 768, 1, 0);

                                $product_images['id'] = $this->Product->id;
                                $product_images['product_image'] = $file;

                                $this->Product->save($product_images);
                            }
                        }
                    }
                    $this->Session->setFlash('<p>' . __('Votre menu a été enregistré', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    $this->redirect(array('controller' => 'Products', 'action' => 'index', $store_id));
                }
            } else {
                $this->Product->validationErrors;
            }
        }

        $stores = $this->Store->find('list', array(
            'conditions' => array('Store.status' => 1),
            'fields' => array('Store.id', 'Store.store_name')));

        $getProductData = $this->Product->findById($id);
        $this->request->data = $getProductData;
        $store_id = $getProductData["Product"]["store_id"];
        $category_list = $this->Category->find('list', array(
            'conditions' => array('Category.parent_id' => 0, 'Category.status' => 1, 'Category.store_id' => $store_id),
            'fields' => array('Category.id', 'Category.category_name')));
        $this->set(compact('getProductData', 'category_list', 'stores'));
    }

    public function deleteProductImage() {
        $this->ProductImage->delete($this->request->data['id']);
        echo 'success';
        exit;
    }

    public function store_index($id = null) {
        $this->layout = 'assets';
        $id = $this->Auth->User();
        $products_detail = $this->Product->find('all', array(
            'conditions' => array(
                'Product.store_id' => $id['Store']['id'],
                'NOT' => array(
                    'Product.status' => 3
                )
            ),
            'order' => array(
                'Product.id DESC'
            )
        ));
        $this->set(compact('products_detail'));
    }

    public function store_add() {
        $this->layout = 'assets';
        $stores_id = $this->Auth->User();
        $store_id = $stores_id['Store']['id'];
        if ($this->request->is('post') || $this->request->is('put')) {

            $this->Product->set($this->request->data);
            if ($this->Product->validates()) {
                $Product_check = $this->Product->find('all', array(
                    'conditions' => array('Product.product_name' => trim($this->request->data['Product']['product_name']),
                        'Product.store_id' => $store_id,
                        'NOT' => array('Product.status' => 3))));
                if (!empty($Product_check)) {

                    $this->Session->setFlash('<p>' . __('Le menu existe déjà', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {

                    $this->request->data['Product']['store_id'] = $store_id;
                    $this->Product->save($this->request->data['Product'], null, null);
                    if ($this->request->data['Product']['price_option'] == "single") {
                        $this->request->data['ProductDetail']['product_id'] = $this->Product->id;
                        $this->request->data['ProductDetail']['sub_name'] = (!empty($this->request->data['ProductDetail']['sub_name'])) ?
                                $this->request->data['ProductDetail']['sub_name'] :
                                $this->request->data['Product']['product_name'];

                        $this->ProductDetail->save($this->request->data['ProductDetail'], null, null);
                    } else {

                        $productDetails = $this->request->data['ProductDetail'];

                        foreach ($productDetails as $key => $value) {

                            if (is_array($value)) {

                                $value['product_id'] = $this->Product->id;
                                $this->ProductDetail->save($value, null, null);
                                $productDetailArray[] = $this->ProductDetail->id;
                                $this->ProductDetail->id = '';
                            }
                        }
                    }
                    if ($this->request->data['Product']['product_addons'] == "Yes") {
                        //Addons
                        $addonDetails = $this->request->data['ProductAddon'];
                        $categoryId = $this->request->data['Product']['category_id'];
                        $this->ProductAddon->deleteAll(array(
                            'product_id' => $this->Product->id
                        ));

                        $j = '';
                        foreach ($addonDetails as $k => $v) {
                            foreach ($v['Subaddon'] as $subkey => $subval) {
                                if (!empty($subval) && isset($subval['subaddons_id'])) {
                                    if ($this->request->data['Product']['price_option'] == "single") {
                                        $subval['product_id'] = $this->Product->id;
                                        $subval['store_id'] = $store_id;
                                        $subval['category_id'] = $categoryId;
                                        $subval['mainaddons_id'] = $v['mainaddons_id'];
                                        $subval['price_option'] = $this->request->data['Product']['price_option'];
                                        $subval['productdetails_id'] = $this->ProductDetail->id;
                                        $this->ProductAddon->save($subval, null, null);
                                        $this->ProductAddon->id = '';
                                    } else {
                                        $i = ($j == 0) ? count($productDetailArray) : $j;
                                        foreach ($subval['subaddons_price'] as $subPriceKey => $subPriceVal) {
                                            $k = count($productDetailArray) - $i;
                                            $j = $i - 1;
                                            $i = ($j == 0) ? count($productDetailArray) : $j;

                                            $subPrice['product_id'] = $this->Product->id;
                                            $subPrice['store_id'] = $store_id;
                                            $subPrice['category_id'] = $categoryId;
                                            $subPrice['mainaddons_id'] = $v['mainaddons_id'];
                                            $subPrice['subaddons_id'] = $subval['subaddons_id'];
                                            $subPrice['price_option'] = $this->request->data['Product']['price_option'];
                                            $subPrice['productdetails_id'] = $productDetailArray[$k];
                                            $subPrice['subaddons_price'] = ($subPriceVal != '') ? $subPriceVal : '0.00';
                                            $this->ProductAddon->save($subPrice, null, null);
                                            $this->ProductAddon->id = '';
                                        }
                                    }
                                }
                            }
                        }
                    }

                    #Product image Upload
                    if (!file_exists(WWW_ROOT . DS . 'stores' . DS . $store_id)) {

                        $this->Img->mkdir(WWW_ROOT . DS . 'stores' . DS . $store_id);
                        $path = WWW_ROOT . DS . 'stores' . DS . $store_id;
                        $this->Img->mkdir($path . DS . "products");
                        $path = $path . DS . "products";
                        $this->Img->mkdir($path . DS . "home");
                        $this->Img->mkdir($path . DS . "carts");
                        $this->Img->mkdir($path . DS . "product_details");
                        $this->Img->mkdir($path . DS . "scrollimg");
                        $this->Img->mkdir($path . DS . "original");
                    }

                    $root = WWW_ROOT . DS . 'stores' . DS . $store_id . DS . "products" . DS;
                    $origpath = $root . "original" . DS;
                    $homepath = $root . "home" . DS;
                    $cartpath = $root . "carts" . DS;
                    $scrollimg = $root . "scrollimg" . DS;
                    $prod_det_path = $root . "product_details" . DS;

                    $allowed_ext = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif');

                    $upload_file = $this->request->data['ProductImage']['tmp_name'];
                    $file = $this->request->data['ProductImage']['name'];
                    $type = $this->request->data['ProductImage']['type'];

                    if (!empty($upload_file)) {

                        $imagesizedata = getimagesize($upload_file);
                        if ($imagesizedata) {
                            if ($file != "" && in_array($type, $allowed_ext)) {

                                $newName = $file;
                                $targetdir = $origpath . DS;

                                #Upload
                                $upload = $this->Img->upload($upload_file, $targetdir, $newName);

                                #Resize
                                $this->Img->resampleGD($targetdir . DS . $newName, $homepath, $newName, 300, 300, 1, 0);
                                $this->Img->resampleGD($targetdir . DS . $newName, $cartpath, $newName, 78, 64, 1, 0);
                                $this->Img->resampleGD($targetdir . DS . $newName, $scrollimg, $newName, 67, 55, 1, 0);
                                $this->Img->resampleGD($targetdir . DS . $newName, $prod_det_path, $newName, 1024, 768, 1, 0);

                                $product_images['id'] = $this->Product->id;
                                $product_images['product_image'] = $file;

                                $this->Product->save($product_images);
                            }
                        }
                    }

                    $this->Session->setFlash('<p>' . __('Votre menu a été enregistré', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    //$this->redirect(array('controller' => 'Products','action' => 'index'));
                    $this->redirect(array('controller' => 'Categories', 'action' => 'index'));
                }
            } else {
                $this->Product->validationErrors;
            }
        }
        $category_list = $this->Category->find('list', array(
            'conditions' => array('Category.parent_id' => 0, 'Category.status' => 1, 'Category.store_id' => $store_id),
            'fields' => array('Category.id', 'Category.category_name')));

        $stores = $this->Store->find('list', array(
            'conditions' => array('Store.status' => 1),
            'fields' => array('Store.id', 'Store.store_name')));
        $this->set(compact('category_list', 'stores', 'store_id'));
    }

    public function store_edit($id = null) {
        $this->layout = 'assets';
        $store_id = $this->Auth->User('Store.id');
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Product->set($this->request->data);
            if ($this->Product->validates()) {
                $getProductEditData = $this->Product->find('first', array(
                    'conditions' => array('Product.id' => $this->request->data['Product']['id'],
                        'Product.store_id' => $store_id)));
                if (empty($getProductEditData)) {
                    $this->render('/Errors/error400');
                }

                $product_check = $this->Product->find('first', array(
                    'conditions' => array(
                        'Product.product_name' => trim($this->request->data['Product']['product_name']),
                        'Product.store_id' => $store_id,
                        'NOT' => array('Product.id' => $this->request->data['Product']['id'],
                            'Product.status' => 3))));
                if (!empty($product_check)) {
                    $this->Session->setFlash('<p>' . __('Le menu existe déjà', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {
                    $this->Product->save($this->request->data['Product'], null, null);
                    $this->ProductDetail->deleteAll(array('product_id' => $this->Product->id));

                    if ($this->request->data['Product']['price_option'] == "single") {

                        $this->request->data['ProductDetail']['product_id'] = $this->Product->id;
                        $this->request->data['ProductDetail']['sub_name'] = (!empty($this->request->data['ProductDetail']['sub_name'])) ?
                                $this->request->data['ProductDetail']['sub_name'] :
                                $this->request->data['Product']['product_name'];
                        $this->ProductDetail->save($this->request->data['ProductDetail'], null, null);
                    } else {

                        $productDetails = $this->request->data['ProductDetail'];
                        foreach ($productDetails as $key => $value) {
                            if (is_array($value)) {
                                $value['product_id'] = $this->Product->id;
                                $this->ProductDetail->save($value, null, null);
                                $productDetailArray[] = $this->ProductDetail->id;
                                $this->ProductDetail->id = '';
                            }
                        }
                    }

                    if ($this->request->data['Product']['product_addons'] == "Yes") {
                        //Addons
                        $addonDetails = $this->request->data['ProductAddon'];
                        $categoryId = $this->request->data['Product']['category_id'];
                        $this->ProductAddon->deleteAll(array(
                            'product_id' => $this->Product->id
                        ));

                        $j = '';
                        foreach ($addonDetails as $k => $v) {
                            foreach ($v['Subaddon'] as $subkey => $subval) {
                                if (!empty($subval) && isset($subval['subaddons_id'])) {
                                    if ($this->request->data['Product']['price_option'] == "single") {
                                        $subval['product_id'] = $this->Product->id;
                                        $subval['store_id'] = $store_id;
                                        $subval['category_id'] = $categoryId;
                                        $subval['mainaddons_id'] = $v['mainaddons_id'];
                                        $subval['price_option'] = $this->request->data['Product']['price_option'];
                                        $subval['productdetails_id'] = $this->ProductDetail->id;
                                        $this->ProductAddon->save($subval, null, null);
                                        $this->ProductAddon->id = '';
                                    } else {
                                        $i = ($j == 0) ? count($productDetailArray) : $j;
                                        foreach ($subval['subaddons_price'] as $subPriceKey => $subPriceVal) {
                                            $k = count($productDetailArray) - $i;
                                            $j = $i - 1;
                                            $i = ($j == 0) ? count($productDetailArray) : $j;

                                            $subPrice['product_id'] = $this->Product->id;
                                            $subPrice['store_id'] = $store_id;
                                            $subPrice['category_id'] = $categoryId;
                                            $subPrice['mainaddons_id'] = $v['mainaddons_id'];
                                            $subPrice['subaddons_id'] = $subval['subaddons_id'];
                                            $subPrice['price_option'] = $this->request->data['Product']['price_option'];
                                            $subPrice['productdetails_id'] = $productDetailArray[$k];
                                            $subPrice['subaddons_price'] = ($subPriceVal != '') ? $subPriceVal : '0.00';
                                            $this->ProductAddon->save($subPrice, null, null);
                                            $this->ProductAddon->id = '';
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $root = WWW_ROOT . DS . 'stores' . DS . $store_id . DS . "products" . DS;
                    $origpath = $root . "original" . DS;
                    $homepath = $root . "home" . DS;
                    $cartpath = $root . "carts" . DS;
                    $scrollimg = $root . "scrollimg" . DS;
                    $prod_det_path = $root . "product_details" . DS;

                    $allowed_ext = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif');

                    $upload_file = $this->request->data['ProductImage']['tmp_name'];
                    $file = $this->request->data['ProductImage']['name'];
                    $type = $this->request->data['ProductImage']['type'];
                    if (!empty($upload_file)) {
                        $imagesizedata = getimagesize($upload_file);

                        if ($imagesizedata) {
                            if ($file != "" && in_array($type, $allowed_ext)) {
                                $newName = $file;
                                $targetdir = $origpath . DS;

                                #Upload
                                $upload = $this->Img->upload($upload_file, $targetdir, $newName);

                                #Resize
                                $this->Img->resampleGD($targetdir . DS . $newName, $homepath, $newName, 300, 300, 1, 0);
                                $this->Img->resampleGD($targetdir . DS . $newName, $cartpath, $newName, 78, 64, 1, 0);
                                $this->Img->resampleGD($targetdir . DS . $newName, $scrollimg, $newName, 67, 55, 1, 0);
                                $this->Img->resampleGD($targetdir . DS . $newName, $prod_det_path, $newName, 1024, 768, 1, 0);

                                $product_images['id'] = $this->Product->id;
                                $product_images['product_image'] = $file;

                                $this->Product->save($product_images);
                            }
                        }
                    }

                    $this->Session->setFlash('<p>' . __('Votre menu a été enregistré', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                    //$this->redirect(array('controller' => 'Products', 'action' => 'index'));
                    $this->redirect(array('controller' => 'Categories', 'action' => 'index'));
                }
            } else {
                $this->Product->validationErrors;
            }
        }
        $category_list = $this->Category->find('list', array(
            'conditions' => array('Category.parent_id' => 0, 'Category.status' => 1),
            'fields' => array('Category.id', 'Category.category_name')));
        $stores = $this->Store->find('list', array(
            'conditions' => array('Store.status' => 1),
            'fields' => array('Store.id', 'Store.store_name')));

        $getProductData = $this->Product->find('first', array(
            'conditions' => array('Product.id' => $id,
                'Product.store_id' => $store_id)));
        if (empty($getProductData)) {
            $this->render('/Errors/error400');
        }
        $this->request->data = $getProductData;
        $this->set(compact('getProductData', 'category_list', 'stores'));
    }

}
