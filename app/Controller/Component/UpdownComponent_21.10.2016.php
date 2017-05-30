<?php
/**
 * Updown Component
 *
 * PHP version 5
 *
 * @category Helper
 */

App::uses('Component', 'Controller'); 

class UpdownComponent extends Component {
	
	var $components = array('Session');

	function downloadFile($filename, $refName, $path) {
		$originalName = $filename;
		$filename = $path."". $refName;
		if($originalName == '')
			$originalName = $filename;
		$file_extension = strtolower(substr(strrchr($filename,"."),1));		
		switch ($file_extension) {
			case "pdf": $ctype="application/pdf"; break;
			case "xls": $ctype="application/xls"; break;
			case "zip": $ctype="application/zip"; break;
			case "doc": $ctype="application/msword"; break;
			case "docx": $ctype="application/msword"; break;
			case "xls": $ctype="application/vnd.ms-excel"; break;
			case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
			case "gif": $ctype="image/gif"; break;
			case "png": $ctype="image/png"; break;
			case "jpe": case "jpeg":
			case "jpg": $ctype="image/jpg"; break;
			default: $ctype="application/force-download";
		}
		if (file_exists($filename)) {
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			header("Content-Type: $ctype");
			header("Content-Disposition: attachment; filename=\"".basename($originalName)."\";");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".@filesize($filename));			
			set_time_limit(0);
			@readfile("$filename") or die("File not found.");
			die;
		}
		else
			$this->Session->setFlash('File Not Found');
	}

	public function FileOnly($getFileData = array(), $uploadDir){
		//echo "<pre>";
		//print_r($getFileData);
		if(!empty($getFileData)){
			$getFileName = $getFileData['filename'];
			$fileSource = base64_encode($getFileData['filedata']);
			if(file_exists($uploadDir.$getFileName))
				$getFileName = $this->renameOriginal($getFileName, $uploadDir);

			move_uploaded_file($fileSource, $uploadDir.$getFileName); 
			$getExt = $this->seperateFnameAndExt($getFileName);
		}
	}

	public function uploadWithOrgName($fDetail, $path) {
		if($fDetail['name'] != '' && $fDetail['size'] != 0) {
			$fName = $fDetail['name'];
			$fSize = $fDetail['size'];
			$tmpName = $fDetail['tmp_name'];
			if(file_exists($path.$fName))
				$fName = $this->renameOriginal($fName, $path);
			move_uploaded_file($tmpName, $path.$fName);
			$getExt = $this->seperateFnameAndExt($fName);
			$data['refName'] = $fName;
			$data['fName'] = $fName;
			$data['orgName'] = $fDetail['name'];
			$data['fSize'] = $fSize;
			$data['fExt'] = $getExt['ext'];
			return $data;
		}
		else
			$this->Session->setFlash('Error Uploading');
	}
	
	public function renameOriginal($fileName , $path) {
		//$randNum = rand(1, 9999);
		$microTime = microtime();
		$timeStamp = strstr(microtime()," ");
		$timeStamp = str_replace(" ","",$timeStamp);
		$randNum   = str_replace('0.', '', substr($microTime, 0, strpos($microTime, ' ')));

		$aFnameDetail = $this->seperateFnameAndExt($fileName);
		$fileRenamed = $this->concat($randNum, $aFnameDetail);

		if(!file_exists($path.DS.$fileRenamed))
			return $fileRenamed;
		$this->renameOriginal($fileName,$path);
	}
	
	public function uploadFile($fDetail, $path) {
		$getTimeStamp = "";
		if($fDetail['name'] != '') {
			$fName = $fDetail['name'];
			$fSize = $fDetail['size'];
			$tmpName = $fDetail['tmp_name'];
			$getTimeStamp = $this->getTimeStampNumber();
			$aFnameDetail = $this->seperateFnameAndExt($fName);

			$refName = $this->concat($getTimeStamp, $aFnameDetail);
			move_uploaded_file($tmpName,$path.DS.$refName);

			$data['refName'] = $refName;
			$data['fName'] = $fName;
			$data['fSize'] = $fSize;
			$data['fExt'] = $aFnameDetail['ext'];
			return $data;
		} else {
			$this->Session->setFlash('Error Uploading');
		}
	}

	public function extention($fName) {
		 return substr($fName, strrpos($fName,'.'));
	}
	
	/* Get filename and ext */
	public function seperateFnameAndExt($fName) {
		$extention =  substr($fName, strrpos($fName,'.'));
		$extLenght = strlen($extention);
		$fnameWithOutExt = substr($fName, 0, -$extLenght);
		return array('fNameWOExt' => $fnameWithOutExt, 'ext' => strtolower($extention));
	}
	
	public function getTimeStampNumber() {
		 return $timeStamp = rand().mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
	}

	public function concat($fileName, $aFnameData) {
		 return trim($fileName.$aFnameData['ext']);
	}

	function findexts($imageUrl) {
		if(!$imageUrl)
			return;
		$what = getimagesize( $imageUrl );
        switch( $what['mime']) {
            case 'image/png' : 
            	return 'png';
            break;
            case 'image/jpeg': 
    			return 'jpeg';
            break;
            case 'image/gif' : 
            	return 'gif';
    	    break;
	        default: 
	        	return false;
	        break;
        }
	}

	function csvToExcel($csvName, $sourcePath = null, $destPath = null) {
		if(!$sourcePath)
			$sourcePath = WWW_ROOT.DS."files".DS."documents".DS;
		if(!$destPath)
			$destPath = $sourcePath;
		
	    App::import('Vendor', 'PHPExcel', array('file' => 'excel'.DS.'PHPExcel'.DS.'IOFactory.php'));

		$objReader = PHPExcel_IOFactory::createReader('CSV')->setDelimiter(';')
				                                            ->setEnclosure('"')
				                                            ->setLineEnding("\r\n")
				                                            ->setSheetIndex(0);
		$srcFile = $sourcePath.$csvName;
		$objPHPExcelFromCSV = $objReader->load($srcFile);
		$objWriter2007 = PHPExcel_IOFactory::createWriter($objPHPExcelFromCSV, 'Excel2007');
		$dfName = $this->getTimeStampNumber();
		$desFile = $destPath.$dfName.".xlsx";
		$objWriter2007->save($desFile);
		return $dfName.".xlsx";
	}
	
	function readExcel($fileName = null, $path = null) {
		if(!$fileName)
			return array();
		if(!$path)
			$path = WWW_ROOT.DS."files".DS."documents".DS;
		
	    App::import('Vendor', 'PHPExcel', array('file' => 'excel'.DS.'PHPExcel'.DS.'IOFactory.php'));
		$inputFileName = $path.$fileName;
		$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
		return $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
	}

	public function bookaTableTimes() {
		
		$times = array('12.00 AM' => '12.00 AM', '12.15 AM' => '12.15 AM', '12.30 AM' => '12.30 AM', '12.45 AM' => '12.45 AM',
					   '1.00 AM' => '1.00 AM', '1.15 AM' => '1.15 AM', '1.30 AM' => '1.30 AM', '1.45 AM' => '1.45 AM',
					   '2.00 AM' => '2.00 AM', '2.15 AM' => '2.15 AM', '2.30 AM' => '2.30 AM', '2.45 AM' => '2.45 AM',
					   '3.00 AM' => '3.00 AM', '3.15 AM' => '3.15 AM', '3.30 AM' => '3.30 AM', '3.45 AM' => '3.45 AM',
					   '4.00 AM' => '4.00 AM', '4.15 AM' => '4.15 AM', '4.30 AM' => '4.30 AM', '4.45 AM' => '4.45 AM',
					   '5.00 AM' => '5.00 AM', '5.15 AM' => '5.15 AM', '5.30 AM' => '5.30 AM', '5.45 AM' => '5.45 AM',
					   '6.00 AM' => '6.00 AM', '6.15 AM' => '6.15 AM', '6.30 AM' => '6.30 AM', '6.45 AM' => '6.45 AM',
					   '7.00 AM' => '7.00 AM', '7.15 AM' => '7.15 AM', '7.30 AM' => '7.30 AM', '7.45 AM' => '7.45 AM',
					   '8.00 AM' => '8.00 AM', '8.15 AM' => '8.15 AM', '8.30 AM' => '8.30 AM', '8.45 AM' => '8.45 AM',
					   '9.00 AM' => '9.00 AM', '9.15 AM' => '9.15 AM', '9.30 AM' => '9.30 AM', '9.45 AM' => '9.45 AM',
					   '10.00 AM' => '10.00 AM', '10.15 AM' => '10.15 AM', '10.30 AM' => '10.30 AM', '10.45 AM' => '10.45 AM',
					   '11.00 AM' => '11.00 AM', '11.15 AM' => '11.15 AM', '11.30 AM' => '11.30 AM', '11.45 AM' => '11.45 AM',
					   '12.00 PM' => '12.00 PM', '12.15 PM' => '12.15 PM', '12.30 PM' => '12.30 PM', '12.45 PM' => '12.45 PM',
					   '1.00 PM' => '1.00 PM', '1.15 PM' => '1.15 PM', '1.30 PM' => '1.30 PM', '1.45 PM' => '1.45 PM',
					   '2.00 PM' => '2.00 PM', '2.15 PM' => '2.15 PM', '2.30 PM' => '2.30 PM', '2.45 PM' => '2.45 PM',
					   '3.00 PM' => '3.00 PM', '3.15 PM' => '3.15 PM', '3.30 PM' => '3.30 PM', '3.45 PM' => '3.45 PM',
					   '4.00 PM' => '4.00 PM', '4.15 PM' => '4.15 PM', '4.30 PM' => '4.30 PM', '4.45 PM' => '4.45 PM',
					   '5.00 PM' => '5.00 PM', '5.15 PM' => '5.15 PM', '5.30 PM' => '5.30 PM', '5.45 PM' => '5.45 PM',
					   '6.00 PM' => '6.00 PM', '6.15 PM' => '6.15 PM', '6.30 PM' => '6.30 PM', '6.45 PM' => '6.45 PM',
					   '7.00 PM' => '7.00 PM', '7.15 PM' => '7.15 PM', '7.30 PM' => '7.30 PM', '7.45 PM' => '7.45 PM',
					   '8.00 PM' => '8.00 PM', '8.15 PM' => '8.15 PM', '8.30 PM' => '8.30 PM', '8.45 PM' => '8.45 PM',
					   '9.00 PM' => '9.00 PM', '9.15 PM' => '9.15 PM', '9.30 PM' => '9.30 PM', '9.45 PM' => '9.45 PM',
					   '10.00 PM' => '10.00 PM', '10.15 PM' => '10.15 PM', '10.30 PM' => '10.30 PM', '10.45 PM' => '10.45 PM',
					   '11.00 PM' => '11.00 PM', '11.15 PM' => '11.15 PM', '11.30 PM' => '11.30 PM', '11.45 PM' => '11.45 PM');

		return $times;

	}
}