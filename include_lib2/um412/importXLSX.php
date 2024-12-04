<?php
$outputTo="array";

if (!isset($startRow))$startRow=1;
/** Set default timezone (will throw a notice otherwise) */
date_default_timezone_set('Asia/Kolkata');

include $lib_path.'PHPExcel/IOFactory.php';
 
if(isset($_FILES['nff']['name'])){
		
	$file_name = $_FILES['nff']['name'];
	$ext = pathinfo($file_name, PATHINFO_EXTENSION);
	
	//Checking the file extension
	if($ext == "xlsx"){
		$tOut='';
		
		$file_name = $_FILES['nff']['tmp_name'];
		$inputFileName = $file_name;

		//  Read your Excel workbook
		try {
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} catch (Exception $e) {
			die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) 
			. '": ' . $e->getMessage());
		}

		//Table used to display the contents of the file
		$tOut.= '<center><table style="width:50%;" border=1>';
		
		//  Get worksheet dimensions
		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();
		$arrTable=array();
		//  Loop through each row of the worksheet in turn
		for ($row = $startRow; $row <= $highestRow; $row++) {
			//  Read a row of data into an array
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, 
			NULL, TRUE, FALSE);
			$tOut.="<tr>";
			$column=array();
			//echoing every cell in the selected row for simplicity. You can save the data in database too.
			foreach($rowData[0] as $k=>$v)	{
				$tOut.= "<td>".$v."</td>";
				$column[]=$v;
			}
			$arrTable[]=$column;
			$tOut.= "</tr>"; 
		}
		$tOut.= '</table></center>';
		//echo $tOut;
	}

	else{
		die( '<p style="color:red;">Please upload file with xlsx extension only</p>'); 
	}	
}
?>