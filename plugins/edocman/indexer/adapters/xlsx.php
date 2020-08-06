<?php
class Xlsx2Text {
	function getText($file) 
	{
		require_once dirname(__FILE__).'/SpreadsheetReader.php' ;
		require_once dirname(__FILE__).'/excel_reader2.php' ;
		require_once dirname(__FILE__).'/SpreadsheetReader_XLSX.php' ;
		$Reader			= new SpreadsheetReader($file);
		$totalCount		= 0;
		$test			=0;
		foreach ($Reader as $Row)
		{   
			$count_rows = $Row;
			foreach($count_rows as $countRow)
			{
				if($countRow != '')
				{
					$taa = explode(' ',$countRow);
					$tCount = count($taa);
					$result[]=$countRow." ";
				}
			}
		}
		return implode("",$result);
	}
}