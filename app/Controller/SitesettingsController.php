<?php
/* MN */
App::uses('AppController', 'Controller');
class SitesettingsController extends AppController {
	var $helpers       = array('Html', 'Session', 'Javascript', 'Ajax', 'Common');
	public $uses       = array('Sitesetting', 'Country', 'State', 'City', 'Location', 'Translation');
	public $components = array('Updown');
	/**
	 * SitesettingsController::admin_index()
	 * Site Management
	 * @return void
	 */
	public function admin_index() {	
		
		if ($this->request->is('post') || $this->request->is('put')) {
            $this->Sitesetting->set($this->request->data);
            if($this->Sitesetting->validates()) {
				$destinationPath = WWW_ROOT.'siteicons';
	            if ($this->request->data['Sitesetting']['site_logo']['error'] == 0) {
	             	
	                $refFile = $this->Updown->uploadFile($this->request->data['Sitesetting']['site_logo'],
	                                                                                        $destinationPath);
	                rename($destinationPath.'/'.$refFile['refName'],$destinationPath.'/logo.png');

	             }
	             if ($this->request->data['Sitesetting']['site_fav']['error'] == 0) {
	                $refFav = $this->Updown->uploadFile($this->request->data['Sitesetting']['site_fav'], 	
	                                                                                        $destinationPath);
	                rename($destinationPath.'/'.$refFav['refName'],$destinationPath.'/fav.ico');
	             }
	             $this->request->data['Sitesetting']['site_logo'] = 'logo.png';
	             $this->request->data['Sitesetting']['site_fav']  = 'fav.ico';
				 $this->request->data['Sitesetting']['user_id'] = $this->Auth->User('id');
				 $this->Sitesetting->save($this->request->data, null, null);
				 $this->Session->setFlash('<p>'.__('Les informations des paramètres du site ont été mises à jour avec succès', true).'</p>', 'default', array('class' => 'alert alert-success'));
			} else {
                $this->Sitesetting->validationErrors;
            }
        }

        $stores = $this->Sitesetting->find('first');
		$this->set('countries', $this->Country->find('list',array(
													'conditions'=>array('Country.status'=>1),
                                                     'fields' => array('Country.id', 'Country.country_name'))));
		$this->set('states', $this->State->find('list', array(
												'conditions' => array('State.status' => 1,
															'State.country_id' => $this->siteSetting['Sitesetting']['site_country']),
                                                'fields' => array('id', 'state_name'))));
		$this->set('cities', $this->City->find('list', array(
    								             'conditions' => array('City.status' => 1,
                                                 'City.state_id' => $stores['Sitesetting']['site_state']),
                                                 'fields' => array('id', 'city_name'))));
		if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {
			$this->set('locations', $this->Location->find('list', array(
											  'conditions' => array('Location.status' => 1,
                                              'Location.city_id' => $stores['Sitesetting']['site_city']),
											  'fields' => array('id', 'zip_code'))));
		} else {
			$this->set('locations', $this->Location->find('list', array(
								            	'conditions' => array('Location.status' => 1,
                                                'Location.city_id' => $stores['Sitesetting']['site_city']),
									            'fields' => array('id', 'area_name'))));
		}
		
		if (!empty($stores)) {
			$this->request->data = $stores;
		}
	}
	/**
	 * SitesettingsController::admin_locations()
	 * Site Location Fitchh process
	 * @return void
	 */
	public function admin_locations() {
		$id 	= $this->request->data['id'];
		$model 	= $this->request->data['model'];
		switch (trim($model)) {
        	case 'State':
				$locations = $this->State->find('list', array(
								'conditions'=> array('State.country_id' => $id,
									'State.status=>1'),
								'fields' => array('id','state_name')));
			break;
			case 'City':
				$locations = $this->City->find('list', array(
								'conditions'=> array('City.state_id' => $id,'City.status'=>1),
								'fields' => array('id','city_name')));
			break;
			case 'Location':
				$locations = $this->Location->find('list', array(
                                'conditions'=> array('Location.city_id' => $id,'Location.status'=>1),
            		     		'fields' => array('id','area_name')));
			break;
		}
		$this->set(compact('model', 'locations'));
	}
	public function admin_paymentSetting(){
		$user_id = $this->Auth->User();			
		$site_id = $this->Sitesetting->findByUserId($user_id['id']);
		if(!empty($this->request->data)) {
			$this->request->data['Sitesetting']['id'] 		   =  $site_id['Sitesetting']['id'];
			$this->Sitesetting->save($this->request->data['Sitesetting'],null,null);
			$this->Session->setFlash('<p>'.__('Les informations de votre compte ont été enregistrées', true).'</p>', 'default', 
                                      array('class' => 'alert alert-success'));
    		$this->redirect(array('controller' => 'dashboards','action' => 'index'));				

		 } else {
		 	$site_id = $this->Sitesetting->find('first');
		 	$this->request->data = $site_id;			
	   }	
	}

	 //translations
    public function admin_translation() {

    	if($this->request->is('post')) {
    		//$language = ($this->siteSetting['Sitesetting']['default_language'] == 1) ? 'eng' : 'deu';
    		$language = 'deu';
            $my_file = '../Locale/'.$language.'/LC_MESSAGES/default.po';
            $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
            $this->Translation->deleteAll(array('languageid '=> $this->siteSetting['Sitesetting']['default_language']));
            $this->Translation->deleteAll(array('languageid '=> 2));
            foreach($this->request->data['Translation'] as $key => $value) {
                $this->Translation->save($value);
                $this->Translation->id = "";
                $saveContentFile = "\n".'msgid'.' '.  '"'.trim($value['msgid']).'"'." "."\n". 'msgstr'.' '. '"'.trim($value['msgstr']).'"'; 
                $datawrite = $saveContentFile;
                fwrite($handle, $datawrite);
   
           }
           $this->redirect(array('controller' => 'sitesettings','action' => 'clear_cache'));    
		}
        $translations = $this->Translation->find('all', array(
        						//'conditions' => array('Translation.languageid' => $this->siteSetting['Sitesetting']['default_language']),
        						'conditions' => array('Translation.languageid' => 2),
        						'order' => array('Translation.location ASC', 'Translation.id ASC')));

        $this->set(compact('translations'));
  	}

  	public function admin_clear_cache() {
        Cache::clear();
        clearCache();

        $files = array();
        $files = array_merge($files, glob(CACHE . '*')); // remove cached css
        //$files = array_merge($files, glob(CACHE . 'css' . DS . '*')); // remove cached css
        //$files = array_merge($files, glob(CACHE . 'js' . DS . '*'));  // remove cached js           
        $files = array_merge($files, glob(CACHE . 'models' . DS . '*'));  // remove cached models           
        $files = array_merge($files, glob(CACHE . 'persistent' . DS . '*'));  // remove cached persistent           

        foreach ($files as $f) {
            if (is_file($f)) {
                @unlink($f);
            }
        }

        $this->Session->setFlash('<p>'.__('Les traductions ont été mises à jour', true).'</p>', 'default', 
                                          array('class' => 'alert alert-success'));
        $this->redirect(array('controller' => 'sitesettings','action' => 'translation','admin' => true));   
    }
}