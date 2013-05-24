<?php
// Test CVS
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/public.class.php");
include("../check.php");
require_once 'Excel/reader.php';

$obj= new PublicClass();

// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();


// Set output Encoding.
$data->setOutputEncoding('gbk');

/***
* if you want you can change 'iconv' to mb_convert_encoding:
* $data->setUTFEncoder('mb');
*
**/

/***
* By default rows & cols indeces start with 1
* For change initial index use:
* $data->setRowColOffset(0);
*
**/



/***
*  Some function for formatting output.
* $data->setDefaultFormat('%.2f');
* setDefaultFormat - set format for columns with unknown formatting
*
* $data->setColumnFormat(4, '%.3f');
* setColumnFormat - set format for column (apply only to number fields)
*
**/

$data->read('report.xls');

/*


 $data->sheets[0]['numRows'] - count rows
 $data->sheets[0]['numCols'] - count columns
 $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

 $data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell
    
    $data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
        if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
    $data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format 
    $data->sheets[0]['cellsInfo'][$i][$j]['colspan'] 
    $data->sheets[0]['cellsInfo'][$i][$j]['rowspan'] 
*/



error_reporting(E_ALL ^ E_NOTICE);

$sheetcount = count($data->sheets);
$sheet_names= "";
for($i = 0; $i < $sheetcount; $i++){
	$sheet_names= $data->boundsheets[$i]['name'].'$';
	$ivsql="";
	$isql="insert into ".$sheet_names."(";
	for ($y = 1; $y <= $data->sheets[$i]['numCols']; $y++) {
		$isql.="`".$data->sheets[$i]['cells'][1][$y]."`,";
	}
	$isql=substr($isql,0,strlen($isql)-1); 
	$isql.=") values";
	for ($x = 2; $x <= $data->sheets[$i]['numRows']; $x++) {
		$ivsql.="(";
		for ($y = 1; $y <= $data->sheets[$i]['numCols']; $y++) {
			$ivsql.="'".iconv("GBK", "UTF-8",$data->sheets[$i]['cells'][$x][$y])."',";
			//echo "\"".iconv("GBK", "UTF-8",$data->sheets[$i]['cells'][$x][$y])."\",";
		}
		$ivsql=substr($ivsql,0,strlen($ivsql)-1); 
		$ivsql.="),";
		//echo $ivsql."\n";
		//echo "\n";
	}
	if($ivsql!="")
	{
		$isql.=substr($ivsql,0,strlen($ivsql)-1); 

		$obj->processSQL("delete from ".$sheet_names);
		$obj->processSQL($isql);
		echo $sheet_names."导入完成<br>";
		//echo $isql;
	}
}
/*
for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
	for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
		echo "\"".$data->sheets[0]['cells'][$i][$j]."\",";
	}
	echo "\n";

}

$ud=new UserDal();
$uinfo=new UserInfo();

$uinfo->UserId="";
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
	$uinfo->Email=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][1]);
	$uinfo->Name=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][2]);
	$uinfo->Position=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][3]);
	$uinfo->Group=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][4]);
	$uinfo->QOC_Role=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][5]);
	$uinfo->Region=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][6]);
	$uinfo->Channel=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][7]);

	if($ud->CheckUser($uinfo->Email)==0)
	{
		$rtn=$ud->RegUser($uinfo);
		echo "<span style='color:#FF0000'>".iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][1])."已添加！"."</span>";
		echo "<br/>";
	}
	else
	{
		$rtn=$ud->EditUserInfo($uinfo);
		echo iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][1])."已更新！";
		echo "<br/>";
	}
}
//print_r($data);
//print_r($data->formatRecords);
*/
?>
