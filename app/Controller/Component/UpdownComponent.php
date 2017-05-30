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
		
		$times = array('00.00' => '00.00', '00.15' => '00.15', '00.30' => '00.30', '00.45' => '00.45',
					   '01.00' => '01.00', '01.15' => '01.15', '01.30' => '01.30', '01.45' => '01.45',
					   '02.00' => '02.00', '02.15' => '02.15', '02.30' => '02.30', '02.45' => '02.45',
					   '03.00' => '03.00', '03.15' => '03.15', '03.30' => '03.30', '03.45' => '03.45',
					   '04.00' => '04.00', '04.15' => '04.15', '04.30' => '04.30', '04.45' => '04.45',
					   '05.00' => '05.00', '05.15' => '05.15', '05.30' => '05.30', '05.45' => '05.45',
					   '06.00' => '06.00', '06.15' => '06.15', '06.30' => '06.30', '06.45' => '06.45',
					   '07.00' => '07.00', '07.15' => '07.15', '07.30' => '07.30', '07.45' => '07.45',
					   '08.00' => '08.00', '08.15' => '08.15', '08.30' => '08.30', '08.45' => '08.45',
					   '09.00' => '09.00', '09.15' => '09.15', '09.30' => '09.30', '09.45' => '09.45',
					   '10.00' => '10.00', '10.15' => '10.15', '10.30' => '10.30', '10.45' => '10.45',
					   '11.00' => '11.00', '11.15' => '11.15', '11.30' => '11.30', '11.45' => '11.45',
					   '12.00' => '12.00', '12.15' => '12.15', '12.30' => '12.30', '12.45' => '12.45',
					   '13.00' => '13.00', '13.15' => '13.15', '13.30' => '13.30', '13.45' => '13.45',
					   '14.00' => '14.00', '14.15' => '14.15', '14.30' => '14.30', '14.45' => '14.45',
					   '15.00' => '15.00', '15.15' => '15.15', '15.30' => '15.30', '15.45' => '15.45',
					   '16.00' => '16.00', '16.15' => '16.15', '16.30' => '16.30', '16.45' => '16.45',
					   '17.00' => '17.00', '17.15' => '17.15', '17.30' => '17.30', '17.45' => '17.45',
					   '18.00' => '18.00', '18.15' => '18.15', '18.30' => '18.30', '18.45' => '18.45',
					   '19.00' => '19.00', '19.15' => '19.15', '19.30' => '19.30', '19.45' => '19.45',
					   '20.00' => '20.00', '20.15' => '20.15', '20.30' => '20.30', '20.45' => '20.45',
					   '21.00' => '21.00', '21.15' => '21.15', '21.30' => '21.30', '21.45' => '21.45',
					   '22.00' => '22.00', '22.15' => '22.15', '22.30' => '22.30', '22.45' => '22.45',
					   '23.00' => '23.00', '23.15' => '23.15', '23.30' => '23.30', '23.45' => '23.45');

		return $times;

	}
}