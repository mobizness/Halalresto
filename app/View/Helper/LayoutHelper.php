<?php
/**
 * Application level Layout Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Layout helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class LayoutHelper extends AppHelper {

	public $helpers = array(
						'Html',
						'Form',
						'Session',
						'Js',
					  );

	public function simpleStatus($value) {
		
		if ($value == 1){
		   
		   return $this->Html->image('/img/tick.png',array(
					'title' => __('Active', true),'alt' => __('Active', true)
				  ));
		}
		return $output = $this->Html->image('/img/cross.png',array(
							'title' => __('Inactive', true),'alt' => __('Inactive', true)
						 ));
    }

	public function userStatus($value,$id=null) {
		$status = 1;
		if ($value == 1){
		   $status = 0;
		   return $this->Html->image('/img/user_management_act.png ',array(
					'title' => __('Active', true),'alt' => __('Active', true),'id'=>'status_'.$id,'style'=>'cursor:pointer;','lang'=> $status
				  ));
		}
		return $output = $this->Html->image('/img/user_management.png',array(
							'title' => __('Inactive', true),'alt' => __('Inactive', true),'id'=>'status_'.$id,'style'=>'cursor:pointer;','lang'=> $status
						 ));
    }

/**
 * Show Menus
 *
 * @param string $menuAlias Menu alias
 * @param array $options (optional)
 * @return string
 */

    public function menu($userId, $type = 'header', $statusCount = null) {
		$nav =& ClassRegistry::init('Navigation');
		$navRole =& ClassRegistry::init('NavigationRole');
		$userTable =& ClassRegistry::init('User');
		$navigations = $navRole->find('all', array('conditions' => array('NavigationRole.user_id' => $userId,
																			'Navigation.status' => 1, 'Navigation.type' => $type), 																							'order' =>  array('NavigationRole.id' =>  'ASC'))); 



		/** Find a User Role**/
		$findUserRole = $userTable->findById($userId);
		/** If Admin logged show all menu **/
		if(!empty($findUserRole)) {
			if($findUserRole['User']['role_id'] == 1) {
                $navigations = $nav->find('all', array(
                                                      'conditions' => array('Navigation.status' => 1, 'Navigation.type' => $type),
                                                      'order' => array('Navigation.order'),
                                                 ));
			}
		}
		$output = '';
		if(!empty($navigations)) {
			foreach($navigations as $key => $navigation) {
				/* active class */
				$currentUrl = urldecode($this->here); 
				if($navigation['Navigation']['controller'] == "#")
					$newFormendLink = "#";
				if($navigation['Navigation']['controller'] == "/")
					$newFormendLink = "/";

				else if(!empty($navigation['Navigation']['plugin']))
					$newFormendLink = array('plugin' => $navigation['Navigation']['plugin'], 'controller' => $navigation['Navigation']['controller'], 'action' => $navigation['Navigation']['action']);
				else
					$newFormendLink = array('plugin' => null, 'controller' => $navigation['Navigation']['controller'], 'action' => $navigation['Navigation']['action'], null);

				$cls = ''; 
				if (Router::url($newFormendLink) == $currentUrl) {
					$cls = 'active';
				} 
				
				/** Get count variable to assign statusCount key **/
				if($navigation['Navigation']['count_variable'] != "")
					$countVariable  = $navigation['Navigation']['count_variable']; 
				else 
					$countVariable  = "";

                $sLastCssClass = '';
                if(count($navigations) - 1 == $key)
                {
                    $sLastCssClass = 'last';
                }
				
				if($statusCount[$countVariable] == "0") {
					$output .= "<li class='$sLastCssClass ".$navigation['Navigation']['class']." "."noRecords". " ".$cls."'>";
					$output .= $this->Html->link(__($navigation['Navigation']['name'], true)."<span>".$statusCount[$countVariable]."</span>","#", array('class' => 'tip', 'escape' => false));
					$output .= "<div class='tooltip'><span class='arrow'>".__("No Enquiry Details", true)."</span></div>";
					$output .= "</li>";
				} else {
					$output .= "<li class='$sLastCssClass ".$navigation['Navigation']['class']." ".$cls."'>";
					$output .= $this->Html->link($type!='middle'?(__($navigation['Navigation']['name'], true)."<span>".$statusCount[$countVariable]."</span>"):'',
																									$newFormendLink, array('escape' => false)); 
					$output .= "</li>"; 
				}
			}
		}
        return $output;
    }
}
