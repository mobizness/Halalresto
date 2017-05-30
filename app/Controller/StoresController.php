<?php

/* MN */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class StoresController extends AppController {

    var $helpers = array('Html', 'Session', 'Javascript', 'Ajax', 'Common');
    public $uses = array('Store', 'Meat_issuer', 'HalalProduct', 'User', 'State', 'City', 'Location', 'DeliveryLocation',
        'Notification', 'StoreTiming', 'Cuisine', 'StoreCuisine', 'StoreCuisine');
    public $components = array('Updown', 'Googlemap', 'Functions', 'Img');

    public function beforeFilter() {
        parent::beforeFilter();

        $this->storeState = $this->State->find('list', array(
            'fields' => array('id', 'state_name')));
        $storesCity = $this->storeCity = $this->City->find('list', array(
            'fields' => array('City.id', 'City.city_name')));

        if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {
            $storeArea = $this->storeLocation = $this->Location->find('list', array(
                'fields' => array('id', 'zip_code')));
        } else {
            $storeArea = $this->storeLocation = $this->Location->find('list', array(
                'fields' => array('id', 'area_name')));
        }

        $this->set(compact('storesCity', 'storeArea'));
    }

    /**
     * StoresController::admin_index()
     * Store Management Detail
     * @return void
     */
    public function admin_index() {
        $stores = $this->Store->find('all', array(
            'conditions' => array('NOT' => array('Store.status' => 3)),
            'group' => array('Store.id DESC')));
        $this->set(compact('stores'));
    }

    public function admin_halalproducts() {
        $products = $this->HalalProduct->find('all');
        $this->set('products', $products);
    }

    public function admin_halalproductedit($id = null) {

        if ($this->request->is('post') || $this->request->is('put')) {
            
            if (!empty($this->request->data['image']['tmp_name'])) {

                #Store banner image upload
                if (!file_exists(WWW_ROOT . DS . 'halalrestoproducts')) {
                    $this->Img->mkdir(WWW_ROOT . DS . 'halalrestoproducts');
                    $path = WWW_ROOT . DS . 'halalrestoproducts';
                    $this->Img->mkdir($path . DS . "home");
                    $this->Img->mkdir($path . DS . "original");
                }

                $root = WWW_ROOT . DS . 'halalrestoproducts' . DS;
                $homepath = $root . "home" . DS;
                $origpath = $root . "original" . DS;


                $allowed_ext = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'video/mp4');

                $upload_file = $this->request->data['image']['tmp_name'];
                $file = $this->request->data['image']['name'];
                $type = $this->request->data['image']['type'];

                if (!empty($upload_file)) {

                    $imagesizedata = true;
                    if ($imagesizedata) {
                        if ($file != "" && in_array($type, $allowed_ext)) {


                            $file = $this->request->data['image']['name'];
                            $file1 = explode('.', $file);
                            $newName = time() . '.' . $file1[1];
                            $filename = $origpath . '' . $newName;
                            $targetdir = $origpath . DS;
                            $filename = $origpath . '' . $newName;

                            #Resize		                                
                            if ($type == "image/jpg" || $type == "image/jpeg") {
                                $upload_file = $this->request->data['image']['tmp_name'];
                                $src = imagecreatefromjpeg($upload_file);
                            } else if ($type == "image/png") {
                                $upload_file = $this->request->data['image']['tmp_name'];
                                $src = imagecreatefrompng($upload_file);
                            } else {

                                $src = imagecreatefromgif($newName);
                            }

                            list($width, $height) = getimagesize($upload_file);

                            $newwidth = 1600;
                            $newheight = 460;
                            $tmp = imagecreatetruecolor($newwidth, $newheight);

                            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                            // $filename = $homepath.$newName;
                            $filename = $homepath . '' . $newName;

                            imagejpeg($tmp, $filename, 100);
                            #Upload
                            $upload = $this->Img->upload($upload_file, $targetdir, $newName);
                            imagedestroy($src);
                            imagedestroy($tmp);
                            // $this->Img->resampleGD($targetdir.DS.$newName, $homepath, $newName, 1600, 460, 1, 0);
                            $this->request->data["HalalProduct"]['image'] = $newName;
                        }
                    }
                }
            } else {
                $this->request->data["HalalProduct"]['image'] = '';
            }
            $this->HalalProduct->save($this->request->data["HalalProduct"]);
            $this->redirect(array('controller' => 'stores', 'action' => 'halalproducts', 'admin' => true));
        }


        $products = $this->HalalProduct->find('all', array(
            'conditions' => array('HalalProduct.status' => 1, 'HalalProduct.id' => $id)));
        if (empty($products)) {
            $this->redirect(array('controller' => 'stores', 'action' => 'halalproducts', 'admin' => true));
        }
        $this->request->data = $products[0];
    }

    public function admin_productsadd() {
        if ($this->request->is('post') || $this->request->is('put')) {
            if (!empty($this->request->data['image']['tmp_name'])) {

                #Store banner image upload
                if (!file_exists(WWW_ROOT . DS . 'halalrestoproducts')) {
                    $this->Img->mkdir(WWW_ROOT . DS . 'halalrestoproducts');
                    $path = WWW_ROOT . DS . 'halalrestoproducts';
                    $this->Img->mkdir($path . DS . "home");
                    $this->Img->mkdir($path . DS . "original");
                }

                $root = WWW_ROOT . DS . 'halalrestoproducts' . DS;
                $homepath = $root . "home" . DS;
                $origpath = $root . "original" . DS;


                $allowed_ext = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'video/mp4');

                $upload_file = $this->request->data['image']['tmp_name'];
                $file = $this->request->data['image']['name'];
                $type = $this->request->data['image']['type'];

                if (!empty($upload_file)) {

                    $imagesizedata = true;
                    if ($imagesizedata) {
                        if ($file != "" && in_array($type, $allowed_ext)) {


                            $file = $this->request->data['image']['name'];
                            $file1 = explode('.', $file);
                            $newName = time() . '.' . $file1[1];
                            $filename = $origpath . '' . $newName;
                            $targetdir = $origpath . DS;
                            $filename = $origpath . '' . $newName;

                            #Resize		                                
                            if ($type == "image/jpg" || $type == "image/jpeg") {
                                $upload_file = $this->request->data['image']['tmp_name'];
                                $src = imagecreatefromjpeg($upload_file);
                            } else if ($type == "image/png") {
                                $upload_file = $this->request->data['image']['tmp_name'];
                                $src = imagecreatefrompng($upload_file);
                            } else {

                                $src = imagecreatefromgif($newName);
                            }

                            list($width, $height) = getimagesize($upload_file);

                            $newwidth = 1600;
                            $newheight = 460;
                            $tmp = imagecreatetruecolor($newwidth, $newheight);

                            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                            // $filename = $homepath.$newName;
                            $filename = $homepath . '' . $newName;

                            imagejpeg($tmp, $filename, 100);
                            #Upload
                            $upload = $this->Img->upload($upload_file, $targetdir, $newName);
                            imagedestroy($src);
                            imagedestroy($tmp);
                            // $this->Img->resampleGD($targetdir.DS.$newName, $homepath, $newName, 1600, 460, 1, 0);
                            $this->request->data["HalalProduct"]['image'] = $newName;
                        }
                    }
                }
            } else {
                $this->request->data["HalalProduct"]['image'] = '';
            }
            $this->HalalProduct->save($this->request->data["HalalProduct"]);
            $this->redirect(array('controller' => 'stores', 'action' => 'halalproducts', 'admin' => true));
        }
    }

    public function admin_statistics() {
        $this->Store->virtualFields = array(
            'storewithId' => "CONCAT(Store.store_name, ' (',Store.id,')')"
        );
        $stores = $this->Store->find('list', array(
            'conditions' => array('Store.status' => 1),
            'fields' => array('Store.id', 'Store.storewithId')));
        $this->set('stores', $stores);
    }

    public function admin_astatistics() {
        
    }

    /**
     * StoresController::admin_add()
     * Store Add Detail
     * @return void
     */
    public function admin_add() {

        $storeCity = array();
        $storeLocation = array();

        if ($this->request->is('post') || $this->request->is('put')) {

            $this->Store->set($this->request->data);
            if ($this->Store->validates()) {

                $CustomerExist = $this->User->find('first', array(
                    'conditions' => array('User.role_id' => 4,
                        'User.username' => trim($this->request->data['User']['username']),
                        'NOT' => array('Customer.status' => 3))));
                $StoreExists = $this->User->find('first', array(
                    'conditions' => array('User.role_id' => 3,
                        'User.username' => trim($this->request->data['User']['username']),
                        'NOT' => array('Store.status' => 3))));
                if (!empty($CustomerExist) || !empty($StoreExists)) {


                    $storeCity = $this->City->find('list', array(
                        'conditions' => array('City.state_id' => $this->request->data['Store']['store_state']),
                        'fields' => array('City.id', 'City.city_name')));

                    $this->Session->setFlash('<p>' . __('L’email utilisateur existe déjà', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {

                    $storeArrray = $this->request->data;

                    if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
                        if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {

                            $storeAddress = $storeArrray['Store']['street_address'] . ', ' .
                                    $this->storeCity[$storeArrray['Store']['store_city']] . ', ' .
                                    $this->storeState[$storeArrray['Store']['store_state']] . ' ' .
                                    $this->storeLocation[$storeArrray['Store']['store_zip']] . ', ' .
                                    $this->siteSetting['Country']['country_name'];
                        } else {
                            $storeAddress = $storeArrray['Store']['street_address'] . ', ' .
                                    $this->storeLocation[$storeArrray['Store']['store_zip']] . ', ' .
                                    $this->storeCity[$storeArrray['Store']['store_city']] . ', ' .
                                    $this->storeState[$storeArrray['Store']['store_state']] . ', ' .
                                    $this->siteSetting['Country']['country_name'];
                        }
                    } else {
                        $storeAddress = $this->request->data['Store']['address'];
                    }

                    $latLong = $this->Googlemap->getlatitudeandlongitude($storeAddress);
                    $storeArrray['Store']['latitude'] = (!empty($latLong['lat'])) ? $latLong['lat'] : 0;
                    $storeArrray['Store']['longitude'] = (!empty($latLong['long'])) ? $latLong['long'] : 0;
                    $storeArrray['Store']['seo_url'] = $this->Functions->seoUrl($this->request->data['Store']['store_name']);
                    $storeArrray['User']['role_id'] = 3;
                    $storeArrray['User']['password'] = $this->Auth->password($storeArrray['User']['password']);
                    if ($this->User->save($storeArrray, null, null)) {
                        $storeArrray['Store']['user_id'] = $this->User->id;
                        $destinationPath = WWW_ROOT . 'storelogos';
                        if ($storeArrray['Store']['store_logo']['error'] == 0) {
                            $refFile = $this->Updown->uploadFile($storeArrray['Store']['store_logo'], $destinationPath);
                            $storeArrray['Store']['store_logo'] = $refFile['refName'];
                        } else {
                            $storeArrray['Store']['store_logo'] = '';
                        }

                        if (!empty($this->request->data['Store']['store_banner']['tmp_name'])) {
                            #Store banner image upload
                            if (!file_exists(WWW_ROOT . DS . 'storebanner')) {
                                $this->Img->mkdir(WWW_ROOT . DS . 'storebanner');
                                $path = WWW_ROOT . DS . 'storebanner';
                                $this->Img->mkdir($path . DS . "home");
                                $this->Img->mkdir($path . DS . "original");
                            }

                            $root = WWW_ROOT . DS . 'storebanner' . DS;
                            $homepath = $root . "home" . DS;
                            $origpath = $root . "original" . DS;


                            $allowed_ext = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'video/mp4');

                            $upload_file = $this->request->data['Store']['store_banner']['tmp_name'];
                            $file = $this->request->data['Store']['store_banner']['name'];
                            $type = $this->request->data['Store']['store_banner']['type'];

                            if (!empty($upload_file)) {

                                $imagesizedata = true;
                                if ($type != "video/mp4") {
                                    $imagesizedata = getimagesize($upload_file);
                                }


                                if ($imagesizedata) {
                                    if ($file != "" && in_array($type, $allowed_ext)) {


                                        $file = $this->request->data['Store']['store_banner']['name'];
                                        $file1 = explode('.', $file);
                                        $newName = time() . '.' . $file1[1];
                                        $filename = $origpath . '' . $newName;
                                        $targetdir = $origpath . DS;
                                        $filename = $origpath . '' . $newName;
                                        if ($type == "video/mp4") {
                                            move_uploaded_file($upload_file, $filename);
                                            $storeArrray['Store']['store_banner'] = $newName;
                                        } else {
                                            #Resize		                                
                                            if ($type == "image/jpg" || $type == "image/jpeg") {
                                                $upload_file = $this->request->data['Store']['store_banner']['tmp_name'];
                                                $src = imagecreatefromjpeg($upload_file);
                                            } else if ($type == "image/png") {
                                                $upload_file = $this->request->data['Store']['store_banner']['tmp_name'];
                                                $src = imagecreatefrompng($upload_file);
                                            } else {

                                                $src = imagecreatefromgif($newName);
                                            }

                                            list($width, $height) = getimagesize($upload_file);

                                            $newwidth = 1600;
                                            $newheight = 460;
                                            $tmp = imagecreatetruecolor($newwidth, $newheight);

                                            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                                            // $filename = $homepath.$newName;
                                            $filename = $homepath . '' . $newName;

                                            imagejpeg($tmp, $filename, 100);
                                            #Upload
                                            $upload = $this->Img->upload($upload_file, $targetdir, $newName);
                                            imagedestroy($src);
                                            imagedestroy($tmp);
                                            // $this->Img->resampleGD($targetdir.DS.$newName, $homepath, $newName, 1600, 460, 1, 0);
                                            $storeArrray['Store']['store_banner'] = $newName;
                                        }
                                    }
                                }
                            }
                        } elseif (!empty($this->request->data['Store']['org_ban'])) {
                            $storeArrray['Store']['store_banner'] = $this->request->data['Store']['org_ban'];
                        } else {
                            $storeArrray['Store']['store_banner'] = '';
                        }


                        $storeArrray['Store']['minimum_order'] = (!empty($storeArrray['Store']['minimum_order'])) ? $storeArrray['Store']['minimum_order'] : '0.00';
                        $storeArrray['Store']['tax'] = $storeArrray['Store']['tax'];
                        $storeArrray['Store']['commission'] = (!empty($this->request->data['Store']['commission'])) ? $this->request->data['Store']['commission'] : '0.00';


                        $storeArrray['Store']['family'] = ($this->request->data['Store']['family'] == 1) ? '1' : '0';
                        $storeArrray['Store']['friends'] = ($this->request->data['Store']['friends'] == 1) ? '1' : '0';
                        $storeArrray['Store']['couple'] = ($this->request->data['Store']['couple'] == 1) ? '1' : '0';

                        // echo "------------<pre>";print_r($storeArrray);echo "</pre>";exit;

                        if ($this->Store->save($storeArrray, null, null)) {
                            $this->request->data['StoreTiming']['store_id'] = $this->Store->id;
                            $this->StoreTiming->save($this->request->data['StoreTiming'], null, null);

                            if (!empty($storeArrray['deliveryLocation'])) {
                                $locations = $storeArrray['deliveryLocation'];
                                foreach ($locations as $key => $value) {
                                    if ($value != '') {
                                        $cityId = $this->City->find('first', array(
                                            'conditions' => array(
                                                'city_name' => $value['city_name']
                                            )
                                        ));
                                        if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {
                                            $condition = array(
                                                'city_id' => $cityId['City']['id'],
                                                'zip_code' => $value['location_name']
                                            );
                                        } else {
                                            $condition = array(
                                                'city_id' => $cityId['City']['id'],
                                                'area_name' => $value['location_name']
                                            );
                                        }
                                        $locationId = $this->Location->find('first', array(
                                            'conditions' => $condition
                                        ));
                                        if (!empty($locationId['Location']['id'])) {
                                            $value['store_id'] = $this->Store->id;
                                            $value['city_id'] = $cityId['City']['id'];
                                            $value['location_id'] = $locationId['Location']['id'];
                                            $value['minimum_order'] = (!empty($value['minimum_order'])) ?
                                                    $value['minimum_order'] : '0.00';
                                            $value['delivery_charge'] = (!empty($value['delivery_charge'])) ?
                                                    $value['delivery_charge'] : '0.00';
                                            $deliveryLocation['DeliveryLocation'] = $value;

                                            $this->DeliveryLocation->save($deliveryLocation);
                                            $this->DeliveryLocation->id = '';
                                        }
                                    }
                                }
                            }

                            //Store Cuisines
                            if (!empty($storeArrray['Cuisine']['cuisine_id'])) {
                                $cuisines = $storeArrray['Cuisine']['cuisine_id'];
                                foreach ($cuisines as $keyword => $val) {
                                    if (!empty($val)) {
                                        $storeCuisine['store_id'] = $this->Store->id;
                                        $storeCuisine['cuisine_id'] = $val;
                                        $this->StoreCuisine->save($storeCuisine);
                                        $this->StoreCuisine->id = '';
                                    }
                                }
                            }

                            $siteName = $this->siteSetting['Sitesetting']['site_name'];
                            $newRegisteration = $this->Notification->find('first', array(
                                'conditions' => array('Notification.title' => 'Store signup')));
                            if ($newRegisteration) {
                                $regContent = $newRegisteration['Notification']['content'];
                                $regsubject = $newRegisteration['Notification']['subject'];
                                $regsubject = str_replace("{siteName}", $siteName, $regsubject);
                            }
                            $storeEmail = $this->siteSetting['Sitesetting']['admin_email'];
                            $source = $this->siteUrl . '/siteicons/logo.png';
                            $mailContent = $regContent;
                            $userID = $this->User->id;
                            $siteUrl = $this->siteUrl;
                            $activation = $this->siteUrl . '/users/storeActiveLink/' . $userID;
                            $StoreName = $storeArrray['Store']['store_name'];

                            $mailContent = str_replace("{sellar name}", $StoreName, $mailContent);
                            $mailContent = str_replace("{CLICK_HERE_TO_LOGIN}", $activation, $mailContent);
                            $mailContent = str_replace("{siteUrl}", $siteUrl, $mailContent);
                            $mailContent = str_replace("{SERVER_NAME}", $siteName, $mailContent);

                            $email = new CakeEmail();
                            $email->from($storeEmail);
                            $email->to($storeArrray['Store']['contact_email']);
                            $email->subject($regsubject);
                            $email->template('register');
                            $email->emailFormat('html');
                            $email->viewVars(array('mailContent' => $mailContent,
                                'source' => $source,
                                'storename' => $siteName));
                            $email->send();
                            $this->Session->setFlash('<p>' . __('Le restaurant a été enregistré', true) . '</p>', 'default', array('class' => 'alert alert-success'));

                            $this->redirect(array('controller' => 'stores', 'action' => 'index', 'admin' => true));
                        }
                    }
                }
            } else {
                $this->Store->validationErrors;
            }
        }
        $this->set('states', $this->State->find('list', array(
                    'conditions' => array('State.status' => 1,
                        'State.country_id' => $this->siteSetting['Sitesetting']['site_country']),
                    'fields' => array('id', 'state_name'))));
        $this->set(compact('storeCity', 'storeLocation'));

        $storeCuisine = $this->Cuisine->find('all', array(
            'conditions' => array(
                'Cuisine.status' => 1
            )
        ));
        foreach ($storeCuisine as $cuisine) {
            $cuisines[$cuisine['Cuisine']['id']] = $cuisine['Cuisine']['cuisine_name'];
        }
        $this->set(compact('cuisines'));
    }

    /**
     * StoresController::admin_edit()
     * Store Edit Detail
     * @param mixed $id
     * @return void
     */
    public function admin_edit($id = null) {
        if ($this->request->is('post') || $this->request->is('put')) {

            // echo "<pre>";print_r($this->request->data);echo "</pre>";exit;

            $this->Store->set($this->request->data);
            if ($this->Store->validates()) {
                $store = $this->Store->findById($this->request->data['Store']['id']);
                $storeEmailCheck = $this->User->find('first', array(
                    'conditions' => array(
                        'User.role_id' => 3,
                        'User.username' => trim($this->request->data['User']['username']),
                        'NOT' => array('User.id' => $store['User']['id'],
                            'Store.status' => 3))));

                $CustomerExist = $this->User->find('first', array(
                    'conditions' => array(
                        'User.role_id' => 4,
                        'User.username' => trim($this->request->data['User']['username']),
                        'NOT' => array('Customer.status' => 3))));

                if (!empty($storeEmailCheck) || !empty($CustomerExist)) {
                    $this->Session->setFlash('<p>' . __('L’email utilisateur existe déjà', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {

                    $storeArrray = $this->request->data;

                    if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
                        if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {

                            $storeAddress = $storeArrray['Store']['street_address'] . ', ' .
                                    $this->storeCity[$storeArrray['Store']['store_city']] . ', ' .
                                    $this->storeState[$storeArrray['Store']['store_state']] . ' ' .
                                    $this->storeLocation[$storeArrray['Store']['store_zip']] . ', ' .
                                    $this->siteSetting['Country']['country_name'];
                        } else {
                            $storeAddress = $storeArrray['Store']['street_address'] . ', ' .
                                    $this->storeLocation[$storeArrray['Store']['store_zip']] . ', ' .
                                    $this->storeCity[$storeArrray['Store']['store_city']] . ', ' .
                                    $this->storeState[$storeArrray['Store']['store_state']] . ', ' .
                                    $this->siteSetting['Country']['country_name'];
                        }
                    } else {
                        $storeAddress = $this->request->data['Store']['address'];
                    }

                    $latLong = $this->Googlemap->getlatitudeandlongitude($storeAddress);
                    $storeArrray['Store']['latitude'] = (!empty($latLong['lat'])) ? $latLong['lat'] : 0;
                    $storeArrray['Store']['longitude'] = (!empty($latLong['long'])) ? $latLong['long'] : 0;
                    $storeArrray['Store']['seo_url'] = $this->Functions->seoUrl($this->request->data['Store']['store_name']);

                    if ($this->User->save($storeArrray, null, null)) {
                        $destinationPath = WWW_ROOT . 'storelogos';
                        if ($storeArrray['Store']['store_logo']['error'] == 0) {

                            $imagesizedata = getimagesize($this->request->data['Store']['store_logo']['tmp_name']);
                            if ($imagesizedata) {
                                $refFile = $this->Updown->uploadFile($storeArrray['Store']['store_logo'], $destinationPath);
                                $storeArrray['Store']['store_logo'] = $refFile['refName'];
                            }
                        } else {
                            $storeArrray['Store']['store_logo'] = $storeArrray['Store']['org_logo'];
                        }


                        if (!empty($this->request->data['Store']['store_banner']['tmp_name'])) {
                            #Store banner image upload
                            if (!file_exists(WWW_ROOT . DS . 'storebanner')) {
                                $this->Img->mkdir(WWW_ROOT . DS . 'storebanner');
                                $path = WWW_ROOT . DS . 'storebanner';
                                $this->Img->mkdir($path . DS . "home");
                                $this->Img->mkdir($path . DS . "original");
                            }

                            $root = WWW_ROOT . DS . 'storebanner' . DS;
                            $homepath = $root . "home" . DS;
                            $origpath = $root . "original" . DS;


                            $allowed_ext = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'video/mp4');

                            $upload_file = $this->request->data['Store']['store_banner']['tmp_name'];
                            $file = $this->request->data['Store']['store_banner']['name'];
                            $type = $this->request->data['Store']['store_banner']['type'];

                            if (!empty($upload_file)) {

                                $imagesizedata = true;
                                if ($type != "video/mp4") {
                                    $imagesizedata = getimagesize($upload_file);
                                }


                                if ($imagesizedata) {
                                    if ($file != "" && in_array($type, $allowed_ext)) {


                                        $file = $this->request->data['Store']['store_banner']['name'];
                                        $file1 = explode('.', $file);
                                        $newName = time() . '.' . $file1[1];
                                        $filename = $origpath . '' . $newName;
                                        $targetdir = $origpath . DS;
                                        $filename = $origpath . '' . $newName;
                                        if ($type == "video/mp4") {
                                            move_uploaded_file($upload_file, $filename);
                                            $storeArrray['Store']['store_banner'] = $newName;
                                        } else {
                                            #Resize		                                
                                            if ($type == "image/jpg" || $type == "image/jpeg") {
                                                $upload_file = $this->request->data['Store']['store_banner']['tmp_name'];
                                                $src = $upload_file;
                                            } else if ($type == "image/png") {
                                                $upload_file = $this->request->data['Store']['store_banner']['tmp_name'];
                                                $src = $upload_file;
                                            } else {

                                                $src = $upload_file;
                                            }

                                            //list($width, $height) = getimagesize($upload_file);
                                            //$newwidth = 1600;
                                            //$newheight = 460;
                                            //$tmp = imagecreatetruecolor($newwidth, $newheight);
                                            //imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                                            // $filename = $homepath.$newName;
                                            $filename = $homepath . '' . $newName;

                                            //imagejpeg($tmp, $filename, 100);
                                            #Upload
                                            $upload = $this->Img->upload($upload_file, $targetdir, $newName);
                                            //imagedestroy($src);
                                            imagedestroy($tmp);
                                            // $this->Img->resampleGD($targetdir.DS.$newName, $homepath, $newName, 1600, 460, 1, 0);
                                            $storeArrray['Store']['store_banner'] = $newName;
                                        }
                                    }
                                }
                            }
                        } elseif (!empty($this->request->data['Store']['org_ban'])) {
                            $storeArrray['Store']['store_banner'] = $this->request->data['Store']['org_ban'];
                        } else {
                            $storeArrray['Store']['store_banner'] = '';
                        }

                        $storeArrray['Store']['family'] = ($this->request->data['Store']['family'] == 1) ? '1' : '0';
                        $storeArrray['Store']['friends'] = ($this->request->data['Store']['friends'] == 1) ? '1' : '0';
                        $storeArrray['Store']['couple'] = ($this->request->data['Store']['couple'] == 1) ? '1' : '0';

                        // echo "<pre>";print_r($storeArrray);echo "</pre>";exit;

                        if ($this->Store->save($storeArrray, null, null)) {
                            if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
                                $this->DeliveryLocation->deleteAll(array("store_id" => $storeArrray['Store']['id']));
                                if (!empty($storeArrray['deliveryLocation'])) {
                                    $locations = $storeArrray['deliveryLocation'];
                                    foreach ($locations as $key => $value) {
                                        if (!empty($value)) {
                                            $cityId = $this->City->find('first', array(
                                                'conditions' => array(
                                                    'city_name' => $value['city_name']
                                                )
                                            ));
                                            if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {
                                                $condition = array(
                                                    'city_id' => $cityId['City']['id'],
                                                    'zip_code' => $value['location_name']
                                                );
                                            } else {
                                                $condition = array(
                                                    'city_id' => $cityId['City']['id'],
                                                    'area_name' => $value['location_name']
                                                );
                                            }
                                            $locationId = $this->Location->find('first', array(
                                                'conditions' => $condition
                                            ));
                                            if (!empty($locationId['Location']['id'])) {
                                                $value['store_id'] = $storeArrray['Store']['id'];
                                                $value['city_id'] = $cityId['City']['id'];
                                                $value['location_id'] = $locationId['Location']['id'];
                                                $value['minimum_order'] = (!empty($value['minimum_order'])) ?
                                                        $value['minimum_order'] : '0.00';
                                                $value['delivery_charge'] = (!empty($value['delivery_charge'])) ?
                                                        $value['delivery_charge'] : '0.00';
                                                $deliveryLocation['DeliveryLocation'] = $value;
                                                $this->DeliveryLocation->save($deliveryLocation);
                                                $this->DeliveryLocation->id = '';
                                            }
                                        }
                                    }
                                }
                            }

                            //Store Timings
                            $Days = array('monday_status', 'tuesday_status', 'wednesday_status', 'thursday_status',
                                'friday_status', 'saturday_status', 'sunday_status');
                            foreach ($Days as $keyword => $value) {
                                $this->request->data['StoreTiming'][$value] = ($this->request->data['StoreTiming'][$value] == 'Close') ? 'Close' : 'Open';
                            }

                            $this->request->data['StoreTiming']['store_id'] = $this->request->data['Store']['id'];
                            $this->StoreTiming->save($this->request->data['StoreTiming'], null, null);

                            //Store Cuisines
                            $this->StoreCuisine->deleteAll(array("store_id" => $storeArrray['Store']['id']));
                            if (!empty($storeArrray['Cuisine']['cuisine_id'])) {
                                $cuisines = $storeArrray['Cuisine']['cuisine_id'];
                                foreach ($cuisines as $keyword => $val) {
                                    if ($val != '') {
                                        $storeCuisine['store_id'] = $storeArrray['Store']['id'];
                                        $storeCuisine['cuisine_id'] = $val;
                                        $this->StoreCuisine->save($storeCuisine);
                                        $this->StoreCuisine->id = '';
                                    }
                                }
                            }

                            $this->Session->setFlash('<p>' . __('Les informations du restaurant ont été mises à jour', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                            $this->redirect(array('controller' => 'stores', 'action' => 'index', 'admin' => true));
                        }
                    }
                }
            } else {
                $this->Store->validationErrors;
            }
        }
        $getStoreData = $this->Store->findById($id);
        $this->set('states', $this->State->find('list', array(
                    'conditions' => array('State.status' => 1,
                        'State.country_id' => $this->siteSetting['Sitesetting']['site_country']),
                    'fields' => array('id', 'state_name'))));
        $this->set('cities', $this->City->find('list', array(
                    'conditions' => array('City.status' => 1,
                        'City.state_id' => $getStoreData['Store']['store_state']),
                    'fields' => array('id', 'city_name'))));

        if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {
            $this->set('locations', $this->Location->find('list', array(
                        'conditions' => array('Location.status' => 1,
                            'Location.city_id' => $getStoreData['Store']['store_city']),
                        'fields' => array('id', 'zip_code'))));
        } else {
            $this->set('locations', $this->Location->find('list', array(
                        'conditions' => array('Location.status' => 1,
                            'Location.city_id' => $getStoreData['Store']['store_city']),
                        'fields' => array('id', 'area_name'))));
        }
        $deliveryLocation = $this->DeliveryLocation->find('all', array(
            'conditions' => array(
                'DeliveryLocation.store_id' => $getStoreData['Store']['id']
            )
                )
        );
        $storeCuisine = $this->StoreCuisine->find('list', array(
            'fields' => array(
                'cuisine_id'
            ),
            'conditions' => array(
                'store_id' => $getStoreData['Store']['id']
            )
        ));

        $this->request->data = $getStoreData;

        $this->set('getStoreData', $getStoreData);
        $this->set('getStoreCuisine', $storeCuisine);

        $storeCuisine = $this->Cuisine->find('all');
        foreach ($storeCuisine as $cuisine) {
            $cuisines[$cuisine['Cuisine']['id']] = $cuisine['Cuisine']['cuisine_name'];
        }
        $this->set(compact('cuisines', 'deliveryLocation'));
    }

    /**
     * StoresController::admin_locations()
     * Location Find Process 
     * @return void
     */
    public function admin_locations() {
        $id = $this->request->data['id'];
        $model = $this->request->data['model'];
        switch (trim($model)) {
            case 'State':
                $locations = $this->State->find('list', array(
                    'conditions' => array('State.country_id' => $id,
                        'State.status' => 1),
                    'fields' => array('id', 'state_name')));
                break;
            case 'City':
                $locations = $this->City->find('list', array(
                    'conditions' => array('City.state_id' => $id,
                        'City.status' => 1),
                    'fields' => array('id', 'city_name')));
                break;
            case 'Location':
                if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {
                    $locations = $this->Location->find('list', array(
                        'conditions' => array('Location.city_id' => $id,
                            'Location.status' => 1),
                        'fields' => array('id', 'zip_code')));
                } else {
                    $locations = $this->Location->find('list', array(
                        'conditions' => array('Location.city_id' => $id,
                            'Location.status' => 1),
                        'fields' => array('id', 'area_name')));
                }
                break;
        }
        $this->set(compact('model', 'locations'));
    }

    public function locations() {
        $id = $this->request->data['id'];
        $model = $this->request->data['model'];
        switch (trim($model)) {
            case 'State':
                $locations = $this->State->find('list', array(
                    'conditions' => array('State.country_id' => $id,
                        'State.status' => 1),
                    'fields' => array('id', 'state_name')));
                break;
            case 'City':
                $locations = $this->City->find('list', array(
                    'conditions' => array('City.state_id' => $id,
                        'City.status' => 1),
                    'fields' => array('id', 'city_name')));
                break;
            case 'Location':
                if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {
                    $locations = $this->Location->find('list', array(
                        'conditions' => array('Location.city_id' => $id,
                            'Location.status' => 1),
                        'fields' => array('id', 'zip_code')));
                } else {
                    $locations = $this->Location->find('list', array(
                        'conditions' => array('Location.city_id' => $id,
                            'Location.status' => 1),
                        'fields' => array('id', 'area_name')));
                }
                break;
        }
        $this->set(compact('model', 'locations'));
    }

    public function store_edit() {

        $this->layout = 'assets';
        if ($this->request->is('post') || $this->request->is('put')) {

            $this->Store->set($this->request->data);
            if ($this->Store->validates()) {
                $storeEmailCheck = $this->User->find('first', array(
                    'conditions' => array(
                        'User.role_id' => 3,
                        'User.username' => trim($this->request->data['User']['username']),
                        'NOT' => array('User.id' => $this->request->data['User']['id'],
                            'Store.status' => 3))));

                $CustomerExist = $this->User->find('first', array(
                    'conditions' => array('User.role_id' => 4,
                        'User.username' => trim($this->request->data['User']['username']),
                        'NOT' => array('Customer.status' => 3))));

                if (!empty($storeEmailCheck) || !empty($CustomerExist)) {

                    $this->Session->setFlash('<p>' . __('Le nom utilisateur existe déjà', true) . '</p>', 'default', array('class' => 'alert alert-danger'));
                } else {
                    if ($this->User->save($this->request->data, null, null)) {
                        $destinationPath = WWW_ROOT . 'storelogos';
                        if ($this->request->data['Store']['store_logo']['error'] == 0) {

                            $imagesizedata = getimagesize($this->request->data['Store']['store_logo']['tmp_name']);
                            if ($imagesizedata) {
                                $refFile = $this->Updown->uploadFile($this->request->data['Store']['store_logo'], $destinationPath);
                                $this->request->data['Store']['store_logo'] = $refFile['refName'];
                            }
                        } else {
                            $this->request->data['Store']['store_logo'] = $this->request->data['Store']['org_logo'];
                        }

                        //Upload Meat Certificate
                        if ($this->request->data['Store']['meat_certificate']['error'] == 0) {

                            $imagesizedata = getimagesize($this->request->data['Store']['meat_certificate']['tmp_name']);
                            if ($imagesizedata) {
                                $refFile = $this->Updown->uploadFile($this->request->data['Store']['meat_certificate'], WWW_ROOT . 'storemeatcertificates');
                                $this->request->data['Store']['meat_certificate'] = $refFile['refName'];
                            }
                        } else {
                            $this->request->data['Store']['meat_certificate'] = $this->request->data['Store']['org_meat_certificate'];
                        }

                        if ($this->request->data['Store']['meat_issuer_name'] == "OTHER") {
                            $this->request->data['Store']['meat_issuer_name'] = $this->request->data['Store']['meat_issuer_name_other'];
                            $meat_issuer_name_other = $this->Meat_issuer->find('all', array('conditions' => array('name' => trim($this->request->data['Store']['meat_issuer_name_other']))));
                            if (sizeof($meat_issuer_name_other) == 0) {

                                $meat_issuers_value = trim($this->request->data['Store']['meat_issuer_name_other']);
                                $this->Store->query("INSERT INTO meat_issuers(name) VALUES ('$meat_issuers_value')");
                            }
                        }
                        if ($this->request->data['Store']['store_banner']['error'] == 0) {
                            #Store banner image upload
                            if (!file_exists(WWW_ROOT . DS . 'storebanner')) {
                                $this->Img->mkdir(WWW_ROOT . DS . 'storebanner');
                                $path = WWW_ROOT . DS . 'storebanner';
                                $this->Img->mkdir($path . DS . "home");
                                $this->Img->mkdir($path . DS . "original");
                            }

                            $root = WWW_ROOT . DS . 'storebanner' . DS;
                            $homepath = $root . "home" . DS;
                            $origpath = $root . "original" . DS;


                            $allowed_ext = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'video/mp4');

                            $upload_file = $this->request->data['Store']['store_banner']['tmp_name'];
                            $file = $this->request->data['Store']['store_banner']['name'];
                            $type = $this->request->data['Store']['store_banner']['type'];

                            if (!empty($upload_file)) {

                                $imagesizedata = true;
                                if ($type != "video/mp4") {
                                    $imagesizedata = getimagesize($upload_file);
                                }

                                if ($imagesizedata) {
                                    if ($file != "" && in_array($type, $allowed_ext)) {

                                        $targetdir = $origpath . DS;

                                        $file = $this->request->data['Store']['store_banner']['name'];
                                        $file1 = explode('.', $file);
                                        $newName = time() . '.' . $file1[1];
                                        $filename = $origpath . '' . $newName;

                                        if ($type == "video/mp4") {
                                            move_uploaded_file($upload_file, $filename);
                                            $this->request->data['Store']['store_banner'] = $newName;
                                        } else {
                                            $filename = $homepath . '' . $newName;
                                            #Resize		                                
                                            if ($type == "image/jpg" || $type == "image/jpeg") {
                                                $upload_file = $this->request->data['Store']['store_banner']['tmp_name'];
                                                $src = imagecreatefromjpeg($upload_file);
                                            } else if ($type == "image/png") {
                                                $upload_file = $this->request->data['Store']['store_banner']['tmp_name'];
                                                $src = imagecreatefrompng($upload_file);
                                            } else {

                                                $src = imagecreatefromgif($newName);
                                            }

                                            list($width, $height) = getimagesize($upload_file);

                                            $newwidth = 1600;
                                            $newheight = 460;
                                            $tmp = imagecreatetruecolor($newwidth, $newheight);

                                            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                                            // $filename = $homepath.$newName;
                                            imagejpeg($tmp, $filename, 100);
                                            #Upload
                                            $upload = $this->Img->upload($upload_file, $targetdir, $newName);

                                            imagedestroy($src);
                                            imagedestroy($tmp);
                                            // $this->Img->resampleGD($targetdir.DS.$newName, $homepath, $newName, 1600, 460, 1, 0);
                                            $this->request->data['Store']['store_banner'] = $newName;
                                        }
                                    }
                                }
                            }
                        } else {
                            $this->request->data['Store']['store_banner'] = $this->request->data['Store']['org_logo'];
                        }


                        $this->request->data['Store']['family'] = ($this->request->data['Store']['family'] == 1) ? '1' : '0';
                        $this->request->data['Store']['friends'] = ($this->request->data['Store']['friends'] == 1) ? '1' : '0';
                        $this->request->data['Store']['couple'] = ($this->request->data['Store']['couple'] == 1) ? '1' : '0';


                        $storeArrray['Store']['seo_url'] = $this->Functions->seoUrl($this->request->data['Store']['store_name']);
                        if ($this->Store->save($this->request->data, null, null)) {
                            if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
                                $this->DeliveryLocation->deleteAll(array("store_id" => $this->request->data['Store']['id']));
                                if (!empty($this->request->data['deliveryLocation'])) {
                                    $locations = $this->request->data['deliveryLocation'];
                                    foreach ($locations as $key => $value) {
                                        if (!empty($value)) {
                                            $cityId = $this->City->find('first', array(
                                                'conditions' => array(
                                                    'city_name' => $value['city_name']
                                                )
                                            ));
                                            if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {
                                                $condition = array(
                                                    'city_id' => $cityId['City']['id'],
                                                    'zip_code' => $value['location_name']
                                                );
                                            } else {
                                                $condition = array(
                                                    'city_id' => $cityId['City']['id'],
                                                    'area_name' => $value['location_name']
                                                );
                                            }
                                            $locationId = $this->Location->find('first', array(
                                                'conditions' => $condition
                                            ));
                                            if (!empty($locationId['Location']['id'])) {
                                                $value['store_id'] = $this->request->data['Store']['id'];
                                                $value['city_id'] = $cityId['City']['id'];
                                                $value['location_id'] = $locationId['Location']['id'];
                                                $value['minimum_order'] = (!empty($value['minimum_order'])) ?
                                                        $value['minimum_order'] : '0.00';
                                                $value['delivery_charge'] = (!empty($value['delivery_charge'])) ?
                                                        $value['delivery_charge'] : '0.00';
                                                $deliveryLocation['DeliveryLocation'] = $value;

                                                $this->DeliveryLocation->save($deliveryLocation);
                                                $this->DeliveryLocation->id = '';
                                            }
                                        }
                                    }
                                }
                            }

                            //Store Timings
                            $Days = array('monday_status', 'tuesday_status', 'wednesday_status', 'thursday_status',
                                'friday_status', 'saturday_status', 'sunday_status');
                            foreach ($Days as $keyword => $value) {
                                $this->request->data['StoreTiming'][$value] = ($this->request->data['StoreTiming'][$value] == 'Close') ? 'Close' : 'Open';
                            }

                            //Store Cuisines
                            $this->StoreCuisine->deleteAll(array("store_id" => $this->request->data['Store']['id']));
                            if (!empty($this->request->data['Cuisine']['cuisine_id'])) {
                                $cuisines = $this->request->data['Cuisine']['cuisine_id'];
                                foreach ($cuisines as $keyword => $val) {
                                    if ($val != '') {
                                        $storeCuisine['store_id'] = $this->request->data['Store']['id'];
                                        $storeCuisine['cuisine_id'] = $val;
                                        $this->StoreCuisine->save($storeCuisine);
                                        $this->StoreCuisine->id = '';
                                    }
                                }
                            }
                            $this->request->data['StoreTiming']['store_id'] = $this->request->data['Store']['id'];
                            $this->StoreTiming->save($this->request->data['StoreTiming'], null, null);

                            $this->Session->setFlash('<p>' . __('Les informations du restaurant ont été mises à jour', true) . '</p>', 'default', array('class' => 'alert alert-success'));
                        }
                    }
                }
            } else {
                $this->Store->validationErrors;
            }
        }
        $getStoreData = $this->Store->findById($this->Auth->User('Store.id'));

        $meat_issuers = $this->Meat_issuer->find('list', array(
            'conditions' => array('status' => 1),
            'fields' => array('name', 'name'),
            'order' => "name ASC"
        ));
        $meat_issuers["OTHER"] = "AUTRE";
        $this->set('states', $this->State->find('list', array(
                    'fields' => array('id', 'state_name'))));
        $this->set('cities', $this->City->find('list', array(
                    'conditions' => array('City.status' => 1,
                        'City.state_id' => $getStoreData['Store']['store_state']),
                    'fields' => array('id', 'city_name'))));

        if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {
            $this->set('locations', $this->Location->find('list', array(
                        'conditions' => array('Location.status' => 1,
                            'Location.city_id' => $getStoreData['Store']['store_city']),
                        'fields' => array('id', 'zip_code'))));
        } else {
            $this->set('locations', $this->Location->find('list', array(
                        'conditions' => array('Location.status' => 1,
                            'Location.city_id' => $getStoreData['Store']['store_city']),
                        'fields' => array('id', 'area_name'))));
        }
        $deliveryLocation = $this->DeliveryLocation->find('all', array(
            'conditions' => array(
                'DeliveryLocation.store_id' => $getStoreData['Store']['id']
            )
                )
        );
        $this->request->data = $getStoreData;

        $storeCuisine = $this->StoreCuisine->find('list', array(
            'fields' => array(
                'cuisine_id'
            ),
            'conditions' => array(
                'store_id' => $getStoreData['Store']['id']
            )
        ));

        $this->request->data = $getStoreData;
        $this->set('getStoreData', $getStoreData);
        $this->set('getStoreCuisine', $storeCuisine);

        $storeCuisine = $this->Cuisine->find('all');
        foreach ($storeCuisine as $cuisine) {
            $cuisines[$cuisine['Cuisine']['id']] = $cuisine['Cuisine']['cuisine_name'];
        }
        $this->set(compact('cuisines', 'deliveryLocation', 'meat_issuers'));
    }

    public function getCityName() {
        $this->layout = false;
        $stateId = $this->request->data['stateId'];
        $getCity = '';
        $cityList = $this->City->find('list', array(
            'fields' => array(
                'City.city_name'
            ),
            'conditions' => array(
                'City.state_id' => $stateId,
                'City.status' => 1
            )
        ));
        foreach ($cityList as $key => $val) {
            $getCity .= $val . ',';
        }

        echo trim($getCity, ',');
        exit();
    }

    public function getLocationName() {
        $this->layout = false;
        $stateId = $this->request->data['stateId'];
        $cityName = $this->request->data['cityName'];
        $getCityId = $this->City->find('first', array(
            'conditions' => array(
                'state_id' => $stateId,
                'city_name' => $cityName
            )
        ));

        $getLocation = '';
        $locationList = $this->Location->find('list', array(
            'fields' => array(
                'Location.zip_code',
                'Location.area_name'
            ),
            'conditions' => array(
                'Location.state_id' => $stateId,
                'Location.city_id' => $getCityId['City']['id'],
                'Location.status' => 1
            )
        ));

        foreach ($locationList as $key => $val) {
            $getLocation .= ($this->siteSetting['Sitesetting']['search_by'] == 'zip') ? $key . ',' : $val . ',';
        }

        echo trim($getLocation, ',');
        exit();
    }

}
