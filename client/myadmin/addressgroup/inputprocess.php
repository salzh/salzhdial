<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/addressgroup.class.php");
include("../../class/linkman.class.php");
include("../../class/fun.class.php");
require_once '../Excel/reader.php';
$pagetitle="地址簿管理";
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$pagetitle?></title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/fun.js"></script>
<style>
	form td{padding:3px;}
	td{font-size:12px;}
	</style>

</head>

<body>
<?

$fun=new FunDal();
$ac="addsave";

$GroupName="";
$GroupType="";
$thisDal= new AddressGroupDal();

if($ac=="addsave"){
	$GroupName=$_REQUEST['GroupName'];
	$GroupType=$_REQUEST['GroupType'];
	if(trim($GroupName)==''){
		echo Msg("操作失败。","back");
	return ;} 
	
	$thisModel=new AddressGroupInfo();
	$thisModel->GroupName=$GroupName;
	$thisModel->GroupType=$GroupType;

	$b=$thisDal->CheckName($GroupName,$_SESSION["userid"]);
	if($b==1){
	echo Msg("该名称已被使用","back");
		return;
	}else{
		$thisModel->UserId=$_SESSION["userid"];

		//var_dump($ainfo);
		$rtn=$fun->AddModel("t_addressgroup",$thisModel);
		if ($rtn==0){
		echo Msg("操作失败","back");
		}
		else{
			upfile($rtn);
		} 
		return;
	}

	
}

function upfile($groupId)
{
	echo $_FILES["file"]["type"];
	if (($_FILES["file"]["type"] == "application/vnd.ms-excel"||$_FILES["file"]["type"] == "application/octet-stream"||$_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
	&& ($_FILES["file"]["size"] < 4000000))
	  {
	  if ($_FILES["file"]["error"] > 0)
		{
		echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		}
	  else
		{
		echo "文件名: " . $_FILES["file"]["name"] . "<br />";
		echo "类型: " . $_FILES["file"]["type"] . "<br />";
		echo "大小: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
		$filename="./file/".date("Ymdhis").$_FILES["file"]["name"];
		move_uploaded_file($_FILES["file"]["tmp_name"],$filename);
		echo "上传成功，正在导入中。。。";
		//Header("Location: input_excel.php"); 
		inputExcel($filename,$groupId);
		}
	  }
	else
	  {
	  echo "错误的文件格式";
	  }	
}

function inputExcel($filename,$groupId)
{
	$data = new Spreadsheet_Excel_Reader();
	
	// Set output Encoding.
	$data->setOutputEncoding('gbk');
	
	$fun=new FunDal();
	$thisDal= new LinkmanDal();
	$thisModel=new LinkmanInfo();	
	
	if($filename!='')
	{
		$data->read($filename);
		
		error_reporting(E_ALL ^ E_NOTICE);
		$successnum=0;
		for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
			
			$thisModel->UserId=$_SESSION["userid"];
			$thisModel->GroupId=$groupId;
			$thisModel->Linkman=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][5]);
			$thisModel->Company=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][6]);
			$thisModel->Dept=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][7]);
			$thisModel->Position=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][8]);
			$thisModel->Country=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][9]);
			$thisModel->Province=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][10]);
			$thisModel->City=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][11]);
			$thisModel->Address=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][13]);
			$thisModel->PostCode=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][12]);
			$thisModel->Fax=str_replace("\t" , "" ,iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][1]));
			$thisModel->Tel=str_replace("\t" , "" ,iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][2]));
			$thisModel->HomeTel=str_replace("\t" , "" ,iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][14]));
			$thisModel->Mobi=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][3]);
			$thisModel->Email=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][4]);
			$thisModel->Url=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][15]);
			$thisModel->Description=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][16]);
				
			$rtn=$fun->AddModel("t_linkman",$thisModel);
			
			if($rtn>0){
				echo "<span style='color:#FF0000'>".iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][5])."已导入地址簿！"."</span>";
				echo "<br/>";
				$successnum++;
			}
			else
			{
				echo "<span style='color:#FF0000'>".iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][2])."导入失败！"."</span>";
				echo "<br/>";
			}
		}
		if($successnum>0)
		{
			$mainModel=new AddressGroupInfo();
			$mainModel->id=$groupId;
			$mainModel->GroupCount=$successnum;
			$rtn=$fun->UpdateModel("t_addressgroup",$mainModel);
			echo Msg("操作成功","listinput.php?action=list");
		}
	}
	else
	{
		echo Msg("操作成功","listinput.php?action=list");
	}	
}
?>

</body>
</html>