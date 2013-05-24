<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/work.class.php");
include("../../class/workdetail.class.php");
include("../../class/linkman.class.php");
include("../../class/admin.class.php");
include("../../class/fun.class.php");
require_once '../Excel/reader.php';
$pagetitle="群组发送详细";
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$pagetitle?></title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<style>
	form td{padding:3px;}
	td{font-size:12px;}
	</style>

<script type="text/javascript">
	var $Id=function(id){return document.getElementById(id);}
	String.prototype.Trim = function(){return this.replace(/(^\s*)|(\s*$)/g, "");}    
	function check(){
		var uname=document.getElementById("Title").value;
		if(uname.Trim()==""){alert("请输入主题");document.getElementById("Title").focus();return false;}
	}
</script>
</head>

<body>
    <link type="text/css" href="../css/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
     <link type="text/css" href="../css/jquery-ui-timepicker-addon.css" rel="stylesheet" />
    <script type="text/javascript" src="../js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.8.17.custom.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="../js/jquery-ui-timepicker-zh-CN.js"></script>
    <script type="text/javascript">
    $(function () {
        $(".ui_timepicker").datetimepicker({
            //showOn: "button",
            //buttonImage: "./css/images/icon_calendar.gif",
            //buttonImageOnly: true,
            showSecond: true,
            timeFormat: 'hh:mm:ss',
            stepHour: 1,
            stepMinute: 1,
            stepSecond: 1
        })
    })
    </script>
    导入开始，请稍后......<br />
<?
// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();

// Set output Encoding.
$data->setOutputEncoding('gbk');

$fun=new FunDal();
$ac=$_REQUEST['action'];
$id= isset($_REQUEST['id'])? $_REQUEST['id'] : '0';
$oid= isset($_REQUEST['oid'])? $_REQUEST['oid'] : '0';
$UserId=$_SESSION["userid"];


$AddressSource="";
$AddressGroupId="";
$AddressFile="";
$UserId="";
$AddressText="";
$thisDal= new WorkDal();
$workDetailDal= new WorkDetailDal();
$workDetialModel= new WorkDetailInfo();

//$rsNumber=$fun->GetList("select MobileNumber from t_mobile_filter");

if($id!="0")
{
	$thisModel=$thisDal->GetModelById($id);
	$UserId=$thisModel->UserId;
	$AddressSource=$thisModel->AddressSource;
	$AddressGroupId=$thisModel->AddressGroupId;
	$AddressFile=$thisModel->AddressFile;
	$AddressText=$thisModel->AddressText;
	if($thisModel->WorkCount>0)
	{
		echo "导入已完成。";
	}
	else
	{
		$workDetailDal->DelbyWorkId($id);
	}
}

if($AddressSource==2)
{
	if($AddressFile!='')
	{
		$data->read("../../".$AddressFile);
		
		error_reporting(E_ALL ^ E_NOTICE);
		$successnum=0;
		for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
			$workDetialModel->TelNo=str_replace("\t" , "" , iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][2]));
			$workDetialModel->UserId=$UserId;
			$workDetialModel->WorkId=$id;
			$workDetialModel->Receiver=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][5]);
			$workDetialModel->SendResult=0;
			$workDetialModel->Linkman=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][5]);
			$workDetialModel->Company=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][6]);
			$workDetialModel->Dept=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][7]);
			$workDetialModel->Position=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][8]);
			$workDetialModel->Country=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][9]);
			$workDetialModel->Province=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][10]);
			$workDetialModel->City=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][11]);
			$workDetialModel->Address=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][13]);
			$workDetialModel->PostCode=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][12]);
			$workDetialModel->Fax=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][1]);
			$workDetialModel->Tel=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][2]);
			$workDetialModel->HomeTel=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][14]);
			$workDetialModel->Mobi=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][3]);
			$workDetialModel->Email=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][4]);
			$workDetialModel->Url=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][15]);
			$workDetialModel->Description=iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][16]);
			if(strlen($workDetialModel->TelNo)>7)
			{
				$rtn=$fun->AddModel("t_work_detail",$workDetialModel);	
			}
			else
			{
				$rtn=0;	
			}
			
			/*
			if(strlen($workDetialModel->TelNo)==8)
			{	
				$rtn=$fun->AddModel("t_work_detail",$workDetialModel);
			}
			elseif(strlen($workDetialModel->TelNo)==11)
			{
				if(substr($workDetialModel->TelNo,0,1)=="0")
				{
					if(substr($workDetialModel->TelNo,0,3)=="021")
					{
						$rtn=$fun->AddModel("t_work_detail",$workDetialModel);
					}
					else
					{
						$rtn=0;
					}
				}
				else if (in_array(substr($workDetialModel->TelNo,0,7), $rsNumber)) {
    				$rtn=0;
				}
				else
				{
					$rtn=$fun->AddModel("t_work_detail",$workDetialModel);
				}
			}
			elseif(strlen($workDetialModel->TelNo)==12&&substr($workDetialModel->TelNo,0,1)=="0")
			{
				$workDetialModel->TelNo=substr($workDetialModel->TelNo,1);
				if (in_array(substr($workDetialModel->TelNo,0,7), $rsNumber)) {
    				$rtn=0;
				}
				else
				{
					$rtn=$fun->AddModel("t_work_detail",$workDetialModel);
				}
			}			
			else
			{
				$rtn=0;
			}
			*/
			if($rtn>0){
				echo "<span style='color:#FF0000'>".iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][2])."号码已加入队列！"."</span>";
				echo "<br/>";
				$successnum++;
			}
			else
			{
				echo "<span style='color:#FF0000'>".iconv("GBK", "UTF-8",$data->sheets[0]['cells'][$i][2])."导入失败！"."</span>";
				echo "<br/>";
			}
		}
		$sendecho="";
		if($successnum>0)
		{
			$thisModel->WorkCount=$successnum;
			$rtn=$fun->UpdateModel("t_work",$thisModel);
			$u= new AdminDal();
			$m=$u->GetModelByUserId($_SESSION["userid"]);
			if($m->voiceMoney<($successnum*0.1))
			{
				$sendecho= "导入完成，共".$successnum."条记录，您的账户余额为".$m->voiceMoney."元，余额不足，可能无法完成当前任务。";
			}
			else
			{
				$sendecho= "导入完成，共".$successnum."条记录，预计费用为：".($successnum*0.1)."元";
			}
			
		}
	}
	else
	{
		//echo Msg($sendecho,"list.php?action=list");
	}
}
else if($AddressSource==3)
{
	if($AddressText!='')
	{		
		$successnum=0;
	   $ArrayAddress = explode(",", $AddressText);
	   foreach($ArrayAddress as $Address)
	   {
			$workDetialModel->TelNo=str_replace("\t" , "" , $Address);
			$workDetialModel->UserId=$UserId;
			$workDetialModel->WorkId=$id;
			$workDetialModel->SendResult=0;
			
			if(strlen($workDetialModel->TelNo)>7)
			{
				$rtn=$fun->AddModel("t_work_detail",$workDetialModel);
			}
			else
			{
				$rtn=0;
			}
						
			if($rtn>0){
				echo "<span style='color:#FF0000'>".$Address."号码已加入队列！"."</span>";
				echo "<br/>";
				$successnum++;
			}
			else
			{
				echo "<span style='color:#FF0000'>".$Address."导入失败！"."</span>";
				echo "<br/>";
			}
	   }
	   
		$sendecho="";
		if($successnum>0)
		{
			$thisModel->WorkCount=$successnum;
			$rtn=$fun->UpdateModel("t_work",$thisModel);
			$u= new AdminDal();
			$m=$u->GetModelByUserId($_SESSION["userid"]);
			if($m->voiceMoney<($successnum*0.1))
			{
				$sendecho= "导入完成，共".$successnum."条记录，您的账户余额为".$m->voiceMoney."元，余额不足，可能无法完成当前任务。";
			}
			else
			{
				$sendecho= "导入完成，共".$successnum."条记录，预计费用为：".($successnum*0.1)."元";
			}
			
		}
	}
	else
	{
		echo Msg($sendecho,"list.php?action=list");
	}
}
else if($AddressSource==4)
{
		$successnum=0;
		$rsLinkman=$fun->GetModelList("select * from t_his_work_detail where SendResult=0 and WorkId=$oid");
		if($rsLinkman!=NULL)
		{
			foreach($rsLinkman as $linkmanItem)
			{
				if($linkmanItem['Tel']!='')
				{
					$workDetialModel->TelNo=$linkmanItem['TelNo'];
					$workDetialModel->UserId=$UserId;
					$workDetialModel->WorkId=$id;
					$workDetialModel->Receiver=$linkmanItem['Receiver'];
					$workDetialModel->SendResult=0;
					$workDetialModel->Linkman=$linkmanItem['Linkman'];
					$workDetialModel->Company=$linkmanItem['Company'];
					$workDetialModel->Dept=$linkmanItem['Dept'];
					$workDetialModel->Position=$linkmanItem['Position'];
					$workDetialModel->Country=$linkmanItem['Country'];
					$workDetialModel->Province=$linkmanItem['Province'];
					$workDetialModel->City=$linkmanItem['City'];
					$workDetialModel->Address=$linkmanItem['Address'];
					$workDetialModel->PostCode=$linkmanItem['PostCode'];
					$workDetialModel->Fax=$linkmanItem['Fax'];
					$workDetialModel->Tel=$linkmanItem['Tel'];
					$workDetialModel->HomeTel=$linkmanItem['HomeTel'];
					$workDetialModel->Mobi=$linkmanItem['Mobi'];
					$workDetialModel->Email=$linkmanItem['Email'];
					$workDetialModel->Url=$linkmanItem['Url'];
					$workDetialModel->Description=$linkmanItem['Description'];
					
					if(strlen($workDetialModel->TelNo)>7)
					{	
						$rtn=$fun->AddModel("t_work_detail",$workDetialModel);
					}
					else
					{
						$rtn=0;
					}
											
					if($rtn>0){
						echo "<span style='color:#FF0000'>".$linkmanItem['Tel']."号码已加入队列！"."</span>";
						echo "<br/>";
						$successnum++;
					}
					else
					{
						echo "<span style='color:#FF0000'>".$linkmanItem['Tel']."导入失败！"."</span>";
						echo "<br/>";
					}	
				}
				else
				{
						echo "<span style='color:#FF0000'>".$linkmanItem['Linkman']."号码为空！"."</span>";
						echo "<br/>";
				}
			}
	}
	$sendecho='';
	if($successnum>=0)
	{
		$thisModel->WorkCount=$successnum;
		$rtn=$fun->UpdateModel("t_work",$thisModel);
		$u= new AdminDal();
		$m=$u->GetModelByUserId($_SESSION["userid"]);
		if($m->voiceMoney<($successnum*0.1))
		{
			$sendecho= "导入完成，共".$successnum."条记录，您的账户余额为".$m->voiceMoney."元，余额不足，可能无法完成当前任务。";
		}
		else
		{
			$sendecho= "导入完成，共".$successnum."条记录，预计费用为：".($successnum*0.1)."元";
		}
	}
	echo "<span style='color:#FF0000'>".$sendecho."</span>";
	echo "<br/>";	
}
else
{
	if($AddressGroupId!="")
	{
		$ArrayId = explode(",", $AddressGroupId);
		$LinkmanDal= new LinkmanDal();
		$successnum=0;
		foreach($ArrayId as $groupId)
		{
			$rsLinkman=$LinkmanDal->GetListByGroupId($groupId);
			foreach($rsLinkman as $linkmanItem)
			{
				if($linkmanItem['Tel']!='')
				{
					$workDetialModel->TelNo=$linkmanItem['Tel'];
					$workDetialModel->UserId=$UserId;
					$workDetialModel->WorkId=$id;
					$workDetialModel->Receiver=$linkmanItem['Linkman'];
					$workDetialModel->SendResult=0;
					$workDetialModel->Linkman=$linkmanItem['Linkman'];
					$workDetialModel->Company=$linkmanItem['Company'];
					$workDetialModel->Dept=$linkmanItem['Dept'];
					$workDetialModel->Position=$linkmanItem['Position'];
					$workDetialModel->Country=$linkmanItem['Country'];
					$workDetialModel->Province=$linkmanItem['Province'];
					$workDetialModel->City=$linkmanItem['City'];
					$workDetialModel->Address=$linkmanItem['Address'];
					$workDetialModel->PostCode=$linkmanItem['PostCode'];
					$workDetialModel->Fax=$linkmanItem['Fax'];
					$workDetialModel->Tel=$linkmanItem['Tel'];
					$workDetialModel->HomeTel=$linkmanItem['HomeTel'];
					$workDetialModel->Mobi=$linkmanItem['Mobi'];
					$workDetialModel->Email=$linkmanItem['Email'];
					$workDetialModel->Url=$linkmanItem['Url'];
					$workDetialModel->Description=$linkmanItem['Description'];
					
					if(strlen($workDetialModel->TelNo)>7)
					{	
						$rtn=$fun->AddModel("t_work_detail",$workDetialModel);
					}
					else
					{
						$rtn=0;
					}
											
					if($rtn>0){
						echo "<span style='color:#FF0000'>".$linkmanItem['Tel']."号码已加入队列！"."</span>";
						echo "<br/>";
						$successnum++;
					}
					else
					{
						echo "<span style='color:#FF0000'>".$linkmanItem['Tel']."导入失败！"."</span>";
						echo "<br/>";
					}	
				}
				else
				{
						echo "<span style='color:#FF0000'>".$linkmanItem['Linkman']."号码为空！"."</span>";
						echo "<br/>";
				}
			}
		}
	}
	$sendecho='';
	if($successnum>0)
	{
		$thisModel->WorkCount=$successnum;
		$rtn=$fun->UpdateModel("t_work",$thisModel);
		$u= new AdminDal();
		$m=$u->GetModelByUserId($_SESSION["userid"]);
		if($m->voiceMoney<($successnum*0.1))
		{
			$sendecho= "导入完成，共".$successnum."条记录，您的账户余额为".$m->voiceMoney."元，余额不足，可能无法完成当前任务。";
		}
		else
		{
			$sendecho= "导入完成，共".$successnum."条记录，预计费用为：".($successnum*0.1)."元";
		}
	}
	echo "<span style='color:#FF0000'>".$sendecho."</span>";
	echo "<br/>";	
}

//echo Msg("操作成功","list.php?action=list");
?>
end
</body>
</html>