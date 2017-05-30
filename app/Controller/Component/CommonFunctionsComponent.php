<?php
/**
 * Updown Component
 *
 * PHP version 5
 *
 * @category Helper
 */
App::uses('Component', 'Controller');  
class CommonFunctionsComponent extends Component {
	public $components = array('Session','Paginator');
	
	//To Get all clashes based upon the argument
	public function clashdata($method=null,$titansclash=null) {
	
		$Clash = ClassRegistry::init('Clash');
		
		
		$clashname = str_replace("-", " ",$titansclash);
		$clashname_exp = explode('Vs',$clashname);
		
		$Fname = trim($clashname_exp['0']);
		$Sname = trim($clashname_exp['1']);
		

		if($method == 'home' && empty($clashid)) {
			$condition = array(
				'conditions' => array('Clash.isapproved' => '1','Clash.ishome' => '1'),				
				'order' => array('Clash.id Desc'),				
				'limit' => 1
							);
		}
		
		if($method == 'home' && !empty($titansclash)) { 
			$condition = array(
				'conditions' => array('Clash.isapproved' => '1','first_opponent_name' => $Fname,
				'second_opponent_name' => $Sname),				
				
				'order' => array('Clash.id Desc'),				
				'limit' => 1
							);
		}
		
		if($method == 'feature') {
			$condition = array(
				'conditions' => array('Clash.isapproved' => '1','isfeatured' => '1'),				
				'order' => array('Clash.id Desc'),				
				'limit' => 4
							);
		}
		
		if($method == 'recent') {
			$condition = array(
				'conditions' => array('Clash.isapproved' => '1'),				
				'order' => array('Clash.id Desc'),				
				'limit' => 4
							);
		}
		
		$getclashval =  $Clash->find('all',$condition);	

		return $getclashval;	
	}
	
}