<?php
/**
 * Common Helper
 *
 * PHP version 5
 *
 * @category Helper
 */
class CommonHelper extends AppHelper {
/**
 * Other helpers used by this helper
 *
 * @var array
 * @access public
 */
    public $helpers = array(
        'Html',
        'Form',
        'Session',
        'Js',
		'Ajax',
    );

    public $aSeminarList = null;

/**
 * trunc function
 * trucating given string
 * @var array
 * @access public
 */
	public function trunc($string, $length = 120, $etc = '...', $break_words = false, $middle = false) {
		if ($length == 0)
		    return '';

		if (strlen($string) > $length) {
		    $length -= min($length, strlen($etc));
		    if (!$break_words && !$middle) {
		        $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
		    }
		    if(!$middle) {
		        return substr($string, 0, $length) . $etc;
		    } else {
		        return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
		    }
		} else {
		    return $string;
		}
	}

/**
 * dateTimeDisplay
 * returns date and time as specified 
 * input format : yyyy-mm-dd H:i:s
 * output format : dd.mm.yyyy
 * @var array
 * @access public
 */
	public function datetimeDisplay($date, $setTime = false, $seperator = '.') {
		$date = trim($date);
        if(empty($date) || $date == "0000-00-00" || $date == "0000-00-00 00:00:00")
			return '-'; 
		$time = '';
		if(strstr($date, ' ')) {			
		    if($setTime == true) {
		    	list($date, $time) = explode(" ", $date);
		    	$newtime = explode(":",$time);
		    	$time = $newtime[0].":".$newtime[1];
		    }
		}
		return substr($date, 8, 2).$seperator.substr($date, 5, 2).$seperator.substr($date, 0, 4)." ".$time;
	}

/**
 * dateTimeDisplay
 * returns date and next line time as specified 
 * input format : yyyy-mm-dd
					   H:i:s
 * output format : dd.mm.yyyy
 * @var array
 * @access public
 */
	public function dateNextLineTime($date, $setTime = false, $seperator = '.') {
		$date = trim($date);
        if(empty($date) || $date == "0000-00-00" || $date == "0000-00-00 00:00:00")
			return '-'; 
		$time = '';
		if(strstr($date, ' ')) {			
		    if($setTime == true) {
		    	list($date, $time) = explode(" ", $date);
		    	$newtime = explode(":",$time);
		    	$time = $newtime[0].":".$newtime[1];
		    }
		}
		//return "<div class='showDate'>".substr($date, 8, 2).$seperator.substr($date, 5, 2).$seperator.substr($date, 0, 4)."</div>"." "."<div class='showTime'>".$time."</div>";
		return "<div class='showDate'>".substr($date, 8, 2).$seperator.substr($date, 5, 2).$seperator.substr($date, 0, 4)."</div>"." "."<div class='showTime'>".$time."</div>";
	}

/** 
 * moneyDisplay
 * input format:  12.900000
 * Return format: 12.90
 * @access public
 */
    function priceDisplay($data, $num = 2, $fSep = '.', $sSep = ',') {
    	return number_format($data, $num, $fSep, $sSep);
    }


/**
 * Show flash message
 *
 * @return void
 */
 
    public function sessionFlash() {
        $messages = $this->Session->read('Message');
        if( is_array($messages) && !empty($messages) ) {
            foreach(array_keys($messages) AS $key) {
                echo $this->Session->flash($key);
            }
        }
    }
	
/**
 * Image resize
 *
 * @return resize image 
 */

    public function imageResize($image, $url,  $fit = true, $maxWidth = 0, $maxHeight = 0 ){
    	//print_r($image);die;
        $imgSize = getimagesize($image);
        //echo "<pre>";
        //print_r($imgSize);die; 
		$height = 0;
		if($imgSize) {
		    if($fit){
		       $ratio = floatval($maxWidth / $imgSize[0]) ;
		       $height = $imgSize[1] * $ratio ;  
		    } else {
		       $height = $maxHeight;
		    }
		}
        return $output = '<img class="mouseover-img"  src ="'.$url.'" width="'.$maxWidth.'" height="'.$height.'" border="0" >';
  	} 
  	// Table Header for seperate th class
  	var $tags = array(
		'tableheader' => '<th%s>%s</th>',
		'tableheaderrow' => '<tr%s>%s</tr>',
		'tablecell' => '<td%s>%s</td>',
		'tablerow' => '<tr%s>%s</tr>'
	);	
	
	public function tableHeader($names, $trOptions = null, $thOptions = null) {
		$out = array();
		foreach ($names as $arg) {
			if (!is_array($arg)) {
				$out[] = sprintf($this->tags['tableheader'], $this->_parseAttributes($thOptions), $arg);
			} else {
				$out[] = sprintf($this->tags['tableheader'], $this->_parseAttributes(current($arg)), key($arg));
			}
		}
		return sprintf($this->tags['tablerow'], $this->_parseAttributes($trOptions), join(' ', $out));
	}


	public function tag($name, $text = null, $options = array()) {
		if (is_array($options) && isset($options['escape']) && $options['escape']) {
			$text = h($text);
			unset($options['escape']);
		}
		if (!is_array($options)) {
			$options = array('class' => $options);
		}
		if ($text === null) {
			$tag = 'tagstart';
		} else {
			$tag = 'tag';
		}
		return sprintf($this->tags[$tag], $name, $this->_parseAttributes($options, null, ' ', ''), $text, $name);
	}

	public function strConcat($getStr) {
		$joinStr = "-";
		if(!empty($getStr)) {
			$joinStr = implode(", ", $getStr);
		}
		return $joinStr;
	}

/** 
*	input format:yyyy-mm-dd
*	Return format: dd.mm.yyyy
*/
	public function dateDisplay($date, $seperator = '.') {
        $retDate = substr($date, 8, 2).$seperator.substr($date, 5, 2).$seperator.substr($date, 0, 4);
        if($retDate == "00.00.0000")
            $retDate = "";
//        pr($retDate);

		return $retDate;
	}

    /**
     * converts byte value to MB with 2 decimal places
     * @param $iByte
     *
     * @return float
     */
    public function byte2MB($iByte) {
        return round($iByte / 1024 / 1024, 2);
    }

    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' B';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' B';
        }
        else
        {
            $bytes = '0 B';
        }

        return $bytes;
    }

/**
 * Display flag 
 *
 * @return void
 */
 
/**
 * Display flag 
 *
 * @return void
 */
 
    public function flag($countryName) {
		$flag = "";
		$country = strtolower($countryName);
		if($country == 'swiss') {
			$flag = 'flag-ch';
		} else if($country == 'german') {
			$flag = 'flag-de';
		}	
		return $flag;
	}

/**
 * Display Plus / Minus 
 *
 * @return void
 */
 
    public function sign($type) {
		$flag = "";
		if($type == 'debit') {
			$flag = '+';
		} else if($type == 'credit') {
			$flag = '-';
		}	
		return $flag;
	}
	
/**
 * Display footer menu seprated menu 
 *
 * @return Menu
 */
 
 	public function footerMenu($colsName){
		$footerMenu = ClassRegistry::init('Navigation');
		$getFooterMenu = $footerMenu->find('all', array('conditions' => array('Navigation.footer_cols' => $colsName, 'Navigation.type' => 'footer',
																			  												'Navigation.status' => 1), 'order' => 'Navigation.order ASC'));
		$menuJoined = "";
		foreach($getFooterMenu as $footerKey => $footerVal){
			$menuJoined .= "<li><a href='".$footerVal["Navigation"]["url"]."'>".$footerVal["Navigation"]["title"]."</a></li>";
		}
		return $menuJoined;
	}

/**
 * Display getSeminarSlug - URL 
 *
 * @return Menu
 */

    public function getSeminarSlug($sTitle) {
        $sSiteUrl = $this->_View->viewVars['siteUrl'];

        $slug = Inflector::slug ($sTitle,'-');
        $slug = strtolower($slug);
        return $sSiteUrl . 'unsere-leistungen/' . $slug;
    }

/**
 * Display getSeminarList 
 *
 * @return Menu
 */

	public function getSeminarList() {
        if($this->aSeminarList == null) {
            $oSeminarModel = ClassRegistry::init('Seminars');
            $aSeminarList = $oSeminarModel->find('all', array(
                                             'fields' => array('id', 'title'),
                                             'order' => 'title ASC',
                                        ));
            $this->aSeminarList = $aSeminarList;
        }
        return $this->aSeminarList;
    }


/**
 * Display displayTestimonial - You want call this function on View File. 
 *
 * @return Menu
 */

	public function displayTestimonial(){
		$oTestimonialModel = ClassRegistry::init('Testimonials');
		$rTestimonial = $oTestimonialModel->find('all', array('conditions' => array('Testimonials.status' => 1)));
		return $rTestimonial;
	}

/**
 * Display Category List. 
 *
 * @return Menu
 */

	public function displayCategory(){
		$oCategoryModel = ClassRegistry::init('Category');
		$rCategory = $oCategoryModel->find('all', array('conditions' => array('Category.status' => 1, 'Category.parent_id' => NULL), 'order' => 'Category.order ASC'));
		return $rCategory;
	}

/**
 * Display - You want call this function on View File. 
 *
 * @return Menu
 */
	public function getParent($id){
		$oCategoryModel = ClassRegistry::init('Category');
		$aCategoryList = $oCategoryModel->findById($id);
		$pStr = array();
		$rResult = '-';
		if(!empty($aCategoryList))
			$rResult = $aCategoryList['Category']['name'];
			
		return $rResult;
	}

/**
 * Display - You want call this function on splite Time. 
 *
 * @return time
 */

	public function splitTime($str){
		$resTime = 1;
		$rTime = '';
		if($str != "0000-00-00 00:00:00"){
			$splitDT = explode(" ", $str);
			//Time Value
			if(isset($splitDT[1]) && $splitDT[1]!="")
										$rTime = $splitDT[1];
			if($rTime == '00:00:00')
						$resTime = 0;
		}
		return $resTime;
	}

/**
 * Display - You want call this function on splited Days. 
 *
 * @return Dates
 */
	public function splitDate($start, $end){
		$begin = new DateTime($start);
		$end = new DateTime($end);
		$end = $end->modify( '+1 day' );
		$joinDate = array();
		$interval = new DateInterval('P1D');
		$daterange = new DatePeriod($begin, $interval ,$end);
		
		foreach($daterange as $date){
			$joinDate[] = $date->format("Y-m-d");
		}
		return $joinDate;
	}
/**
 * Display - You want call this function on blockDates. 
 *
 * @return blockDates
 */
	public function blockDates(){
		$oCalendarModel = ClassRegistry::init('EventCalendar');
		$dRes = "";
		$returnRes = array();
		$blockDates = $oCalendarModel->find('all', array('fields' => array('EventCalendar.start_date', 'EventCalendar.end_date'),
																		   						'conditions' => array('EventCalendar.blocked_status' => 1)));
		
		//pr($blockDates); die('adaads');
		if(!empty($blockDates)) {
			foreach($blockDates as $dKey => $dVal){ 
				if($dVal['EventCalendar']['start_date']!= "0000-00-00 00:00:00" && $dVal['EventCalendar']['end_date'] != "0000-00-00 00:00:00" && $dVal['EventCalendar']['end_date'] != ""){
					//Between Days
					$bDays = $this->splitDate($dVal['EventCalendar']['start_date'], $dVal['EventCalendar']['end_date']);
					$sDate = date('Y-m-d', strtotime($dVal['EventCalendar']['start_date']));
					$eDate = date('Y-m-d', strtotime($dVal['EventCalendar']['end_date']));
					if(!empty($bDays)){
						foreach($bDays as $bVal) {
							if($bVal == $sDate){
								//StartDate
								if($this->splitTime($dVal['EventCalendar']['start_date']) == 1){
																							continue;
								} else {
									$returnRes[] = "'".$this->dateDisplay($bVal)."'";
								}	
							} else if($bVal == $eDate){
								//End Date Blocked
								if($this->splitTime($dVal['EventCalendar']['end_date']) == 1)
																						  continue;
								else {
									$returnRes[] = "'".$this->dateDisplay($bVal)."'";
								}
							} else {
								//Between All Days Blocked
								$returnRes[] = "'".$this->dateDisplay($bVal)."'";
							}
						}
					}
				} else {
					if($this->splitTime($dVal['EventCalendar']['start_date']) == 1)
																			 	continue;
					
					$returnRes[] = "'".$this->dateDisplay($dVal['EventCalendar']['start_date'])."'";
				}
			}
			$dRes .= "[";
			$dRes .= implode(',', $returnRes);
			$dRes .= "]";
		}
		return $dRes;
	}

/**
 * Display - You want call this function on View File. 
 *
 * @return Menu
 */
	public function getParents($id){
		$oCategoryModel = ClassRegistry::init('Category');
		$aCategoryList = $oCategoryModel->findById($id);
		$pStr = array();
		$rResult = '-';
		if(!empty($aCategoryList)){
			$pStr[] = $aCategoryList['Category']['name'];
			if($aCategoryList['Category']['parent_id'] != ""){
				$aCategoryList = $oCategoryModel->findById($aCategoryList['Category']['parent_id']);
				if(!empty($aCategoryList))
					$pStr[] = $aCategoryList['Category']['name'];
					if($aCategoryList['Category']['parent_id']!=""){
						$aCategoryList = $oCategoryModel->findById($aCategoryList['Category']['parent_id']);
						if(!empty($aCategoryList))
							$pStr[] = $aCategoryList['Category']['name'];
					}
			}
				
		}
		if(!empty($pStr))
			$rResult = implode(' -> ', array_reverse($pStr));	
			//$rResult = implode(' <h7>&#x21e2;</h7> ', array_reverse($pStr));	
			
		return $rResult;
	}

	/**
	 * Display - You want call this function on View File. 
	 *
	 * @return Menu
	 */

	public function getParentss($id){
		$oThemetemplateModel = ClassRegistry::init('Themetemplate');
		$aThemetemplateList = $oThemetemplateModel->findById($id);
		$pStr = array();
		$rResult = '-';
		if(!empty($aThemetemplateList)){
			$pStr[] = $aThemetemplateList['Themetemplate']['themename'];
			/*if($aThemetemplateList['Themetemplate']['parent_id'] != ""){
				$aThemetemplateList = $oThemetemplateModel->findById($aThemetemplateList['Themetemplate']['parent_id']);
				if(!empty($aThemetemplateList))
					$pStr[] = $aThemetemplateList['Themetemplate']['themename'];
					if($aThemetemplateList['Themetemplate']['parent_id']!=""){
						$aThemetemplateList = $oThemetemplateModel->findById($aThemetemplateList['Themetemplate']['parent_id']);
						if(!empty($aThemetemplateList))
							$pStr[] = $aThemetemplateList['Themetemplate']['themename'];
					}
			}*/
				
		}
		if(!empty($pStr))
			$rResult = implode(' -> ', array_reverse($pStr));	
			//$rResult = implode(' <h7>&#x21e2;</h7> ', array_reverse($pStr));	
			
		return $rResult;
	}


/**
 * Display - You want call this function on View File. 
 *
 * @return Menu
 */
	public function getChilds($id){
		$oCategoryModel = ClassRegistry::init('Category');
		$aCategoryList = $oCategoryModel->find('all', array('conditions' => array('Category.status' => 1, 'Category.parent_id' => $id), 'order' => 'Category.order ASC'));
		return $aCategoryList;
	}


/**
 * Display - Upcoming List.
 *
 * @return Upcoming events list.
 */

	public function seminarsList(){
		$uEventModel = ClassRegistry::init('EventCalendar');
		//Blocked dates & events
		$bEventsList = $uEventModel->find(
            'all',
            array(
                 'fields' => array(
                     'Seminar.slug',
                     'EventCalendar.title',
                     'EventCalendar.start_date',
                     'EventCalendar.end_date',
                     'EventCalendar.status'
                 ),
                 'conditions' => array(
                     'EventCalendar.status' => 1,
                     'EventCalendar.created_by' => array('0','1'),
                     'EventCalendar.start_date >=' => date('Y-m-d')
                 ),
                 'order' => 'EventCalendar.start_date',
                 'limit' => 8
            )
        );
		if(!empty($bEventsList))
				return $bEventsList;
	}
/**
 * Display - Upcoming List.
 *
 * @return Upcoming events list.
 */

	public function uEventsList($type){
		$uEventModel = ClassRegistry::init('EventCalendar');
		$uEnquiryModel = ClassRegistry::init('Enquiry');
		$uUserModel = ClassRegistry::init('User');

		//Seminar event list
        if($type == 'front') {
            $sEventsList = $uEventModel->find(
                'all',
                array(
                     'fields' => array(
                         'Seminar.slug',
                         'EventCalendar.title',
                         'EventCalendar.start_date',
                         'EventCalendar.end_date',
                         'EventCalendar.status',
                         'EventCalendar.type',
						 'EventCalendar.blocked_status'
                     ),
                     'conditions' => array(
                         'EventCalendar.status' => 1,
                         'OR' => array(
                             'Seminar.status' => 1,
                             'Seminar.id IS NULL',
                         ),
                     )
                )
            );
        } else {
            $sEventsList = $uEventModel->find('all', array('fields' => array('Seminar.slug', 'EventCalendar.title', 'EventCalendar.start_date', 'EventCalendar.end_date', 'EventCalendar.status', 'EventCalendar.type', 'EventCalendar.blocked_status')));
        }

		/*//Blocked dates & events
		$bEventsList = $uEventModel->find('all', array('fields' => array('Seminar.slug', 'EventCalendar.title', 'EventCalendar.start_date', 'EventCalendar.end_date', 'EventCalendar.status', 'EventCalendar.type'), 'conditions' => array('EventCalendar.type' => 'blocked')));

		$sEventsList = array_merge($sEventsList, $bEventsList);*/
			
		$eDatas = array();
		$i =0;
		//echo '<pre>';
		//echo '<pre>'; print_r($sEventsList); exit;
		//Seminar added to Calendar view
		foreach($sEventsList as $key => $val){
            if($val['EventCalendar']['status'] == 0){
                $eDatas[$i]['backgroundColor'] = '#CCCCCC';
                $eDatas[$i]['textColor'] = '#000';
            }
			//Blocked Event Title
			if($val['EventCalendar']['blocked_status'] == 1 && $type == "front"){
				$eDatas[$i]['title'] = __('Blocked', true);
			} else {
				$eDatas[$i]['title'] = $val['EventCalendar']['title'];
				if($val['Seminar']['slug'] != '')
					$eDatas[$i]['url'] = $this->webroot."unsere-leistungen/".$val['Seminar']['slug'];
			}
			
			if($val['EventCalendar']['start_date'] != "" && $val['EventCalendar']['start_date'] != '0000-00-00') {
				$eDatas[$i]['start'] = $val['EventCalendar']['start_date'];  
			}
			if($val['EventCalendar']['end_date'] != "" && $val['EventCalendar']['end_date'] != '0000-00-00'){
				$eDatas[$i]['end'] = $val['EventCalendar']['end_date'];
			}
			$i++;
		}
		
		//Enquiry added to Calendar view
		if($type == "admin") {
			$sEnquiryList = $uEnquiryModel->find('all');
			//pr($sEnquiryList); die;
			if(!empty($sEnquiryList)){
				$joinStr = '';
				foreach($sEnquiryList as $sKey => $sVal){ 
					//Customer name
					if($sVal['User']['customer_name'] != "")
						$joinStr .= __('Person Name', true)." : ".trim($sVal['User']['customer_name'])."</br>";
					//Company name
					if($sVal['User']['company'] != "")
						$joinStr .= __('Company', true)." : ".trim($sVal['User']['company'])."</br>";
					//Seminar name
					if($sVal['Seminar']['title'] != "")
						$joinStr .= __('Title', true)." : ".trim($sVal['Seminar']['title'])."</br>";
					//Enquiry date
					if($sVal['Enquiry']['date'] != "")
						$joinStr .= __('Date', true)." : ".$this->dateDisplay($sVal['Enquiry']['date'])."</br>";
					//Enquiry time
					if($sVal['Enquiry']['time'] != "")
						$joinStr .= __('Time', true)." : ".trim($sVal['Enquiry']['time'])."</br>";
					//Enquiry time
					if($sVal['Enquiry']['assign_to'] != 0) {
						$lName = $uUserModel->findById($sVal['Enquiry']['assign_to']);
						$joinStr .= __('Assigned Lecturer Name', true)." : ".trim($lName['User']['customer_name'])."</br>";
					}
					
					$eDatas[$i]['title'] = $sVal['Seminar']['title'];
					$eDatas[$i]['description'] = $joinStr;
					
					//Enquiry created
					if($sVal['Enquiry']['status'] == 0){
						$eDatas[$i]['start'] = $sVal['Enquiry']['date'];
						$eDatas[$i]['backgroundColor'] = '#2E64FE';
						$eDatas[$i]['textColor'] = '#fff';
					}
					//Enquiry modified
					if($sVal['Enquiry']['status'] == 1){
						$eDatas[$i]['start'] = $sVal['Enquiry']['date'];
						$eDatas[$i]['backgroundColor'] = '#FFBF00';
						$eDatas[$i]['textColor'] = '#000';
					}
					$joinStr = "";
					$i++;
				}	
			}
		}
		
		//pr($eDatas); die;
		//print_r($eDatas); die;
		return json_encode($eDatas);
	}
}
?>
