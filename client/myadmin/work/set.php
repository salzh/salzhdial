<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/work.class.php");
include("../../class/fun.class.php");
$pagetitle="群组发送";
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
		$("#AddressGroupId").attr("value",$("#AddressGroup").val());
		var uname=document.getElementById("Title").value;
		if(uname.Trim()==""){alert("请输入主题");document.getElementById("Title").focus();return false;}
	}
</script>
</head>

<body>
	<link rel="stylesheet" type="text/css" href="../css/jquery.multiselect.css" />
    <link type="text/css" href="../css/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
     <link type="text/css" href="../css/jquery-ui-timepicker-addon.css" rel="stylesheet" />
    <script type="text/javascript" src="../js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.8.17.custom.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="../js/jquery-ui-timepicker-zh-CN.js"></script>
	<script type="text/javascript" src="../js/jquery.multiselect.js"></script>
    <script type="text/javascript">
    $(function(){
        $("#AddressGroup").multiselect();
    });
    </script>    
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
<?

$fun=new FunDal();
$ac=$_REQUEST['action'];
$id= isset($_REQUEST['id'])? $_REQUEST['id'] : '0';
$AddressGroup= isset($_REQUEST['AddressGroup'])? $_REQUEST['AddressGroup'] : '';
$AddressText= isset($_REQUEST['AddressText'])? str_replace("，",",",$_REQUEST['AddressText']) : '';
$UserId=$_SESSION["userid"];

$WorkNo="";
$WorkType="";
$SendTime="";
$WorkCount="";
$OverCount="";
$SuccessCount="";
$Money="";
$WorkState="";
$AddressSource="";
$AddressGroupId="";
$AddressFile="";
$Title="";
$SendTimeType="";
$FixedTime="";
$IfEndTime="";
$EndTime="";
$Level="";
$WorkTimeSH1="";
$WorkTimeSM1="";
$WorkTimeEH1="";
$WorkTimeEM1="";
$WorkTimeSH2="";
$WorkTimeSM2="";
$WorkTimeEH2="";
$WorkTimeEM2="";
$IfVoiceTemplate="";
$VoiceTemplateId="";
$VoiceType="";
$VoiceFile="";
$TTS="";
$IfClick="";
$RepeatNum="";
$ReturnNum="";
$ComplainNum="";
$ReturnVoiceType="";
$ReturnVoiceFile="";
$ReturnTTS="";
$IfFax="";
$FaxFile="";
$IfMessage="";
$IfMessageS1="";
$IfMessageS2="";
$IfMessageS3="";
$IfMessageS4="";
$IfMessageS5="";
$IfMessageS6="";
$Message="";
$ComplainAgents="";

$thisDal= new WorkDal();

if($id!="0")
{
	$WorkNo=$thisModel->WorkNo;
	$WorkType=$thisModel->WorkType;
	$SendTime=$thisModel->SendTime;
	$WorkCount=$thisModel->WorkCount;
	$OverCount=$thisModel->OverCount;
	$SuccessCount=$thisModel->SuccessCount;
	$Money=$thisModel->Money;
	$WorkState=$thisModel->WorkState;
	$AddressSource=$thisModel->AddressSource;
	$AddressGroupId=$thisModel->AddressGroupId;
	$AddressFile=$thisModel->AddressFile;
	$Title=$thisModel->Title;
	$SendTimeType=$thisModel->SendTimeType;
	$FixedTime=$thisModel->FixedTime;
	$IfEndTime=$thisModel->IfEndTime;
	$EndTime=$thisModel->EndTime;
	$Level=$thisModel->Level;
	$WorkTimeSH1=$thisModel->WorkTimeSH1;
	$WorkTimeSM1=$thisModel->WorkTimeSM1;
	$WorkTimeEH1=$thisModel->WorkTimeEH1;
	$WorkTimeEM1=$thisModel->WorkTimeEM1;
	$WorkTimeSH2=$thisModel->WorkTimeSH2;
	$WorkTimeSM2=$thisModel->WorkTimeSM2;
	$WorkTimeEH2=$thisModel->WorkTimeEH2;
	$WorkTimeEM2=$thisModel->WorkTimeEM2;
	$IfVoiceTemplate=$thisModel->IfVoiceTemplate;
	$VoiceTemplateId=$thisModel->VoiceTemplateId;
	$VoiceType=$thisModel->VoiceType;
	$VoiceFile=$thisModel->VoiceFile;
	$TTS=$thisModel->TTS;
	$IfClick=$thisModel->IfClick;
	$RepeatNum=$thisModel->RepeatNum;
	$ReturnNum=$thisModel->ReturnNum;
	$ComplainNum=$thisModel->ComplainNum;
	$ReturnVoiceType=$thisModel->ReturnVoiceType;
	$ReturnVoiceFile=$thisModel->ReturnVoiceFile;
	$ReturnTTS=$thisModel->ReturnTTS;
	$IfFax=$thisModel->IfFax;
	$FaxFile=$thisModel->FaxFile;
	$IfMessage=$thisModel->IfMessage;
	$IfMessageS1=$thisModel->IfMessageS1;
	$IfMessageS2=$thisModel->IfMessageS2;
	$IfMessageS3=$thisModel->IfMessageS3;
	$IfMessageS4=$thisModel->IfMessageS4;
	$IfMessageS5=$thisModel->IfMessageS5;
	$IfMessageS6=$thisModel->IfMessageS6;
	$Message=$thisModel->Message;
	$ComplainAgents=$thisModel->ComplainAgents;
	$AddressText=$thisModel->AddressText;
}
if($ac=="pass"){
	$thisModel->id=$id;
	$thisModel->WorkState=0;
	$rtn=$fun->UpdateModel("t_work",$thisModel);
	if ($rtn==0){
	//echo Msg("操作失败.","back");
	}
	else{
		echo Msg("操作成功","list.php?action=list");
	} 
	return;	
	echo "stop";
	exit(0);
}
else if($ac=="reject"){
	$thisModel->id=$id;
	$thisModel->WorkState=2;
	$rtn=$fun->UpdateModel("t_work",$thisModel);
	if ($rtn==0){
	//echo Msg("操作失败.","back");
	}
	else{
		echo Msg("操作成功","list.php?action=list");
	} 
	return;	
	echo "stop";
	exit(0);
}
else if($ac=="stop"){
	$thisModel->id=$id;
	$thisModel->WorkState=4;
	$rtn=$fun->UpdateModel("t_work",$thisModel);
	if ($rtn==0){
	//echo Msg("操作失败.","back");
	}
	else{
		echo Msg("操作成功","list.php?action=list");
	} 
	return;	
	echo "stop";
	exit(0);
}
else if($ac=="resend"){
	echo $id;
	$thisModel=$thisDal->GetHisModelById($id);

	$thisModel->id=NULL;
	$thisModel->WorkNo= date("Ymdhis").$fun->getRandNum();
	$thisModel->AddressSource=4;
	$thisModel->OverCount=0;
	$thisModel->SuccessCount=0;
	$thisModel->WorkState=0;
	
	$thisModel->CreateDate=NULL;
	$rtn=$fun->AddModel("t_work",$thisModel);
	if ($rtn==0){
	//echo Msg("操作失败","back");
	}
	else{ 
		echo "<script>this.location='setdetail.php?id=$rtn&oid=$id';</script>";
		//echo Msg("开始导入电话号码，请稍后。。。","setdetail.php?id=$rtn");
	} 
	return;	
}
else if($ac=="resume"){
	$thisModel->id=$id;
	$thisModel->WorkState=1;
	$rtn=$fun->UpdateModel("t_work",$thisModel);
	if ($rtn==0){
	//echo Msg("操作失败.","back");
	}
	else{
		echo Msg("操作成功","list.php?action=list");
	} 
	return;	
}
else if($ac=="addsave"){

	if(trim($_REQUEST['Title'])==''){
		echo Msg("操作失败。","back");
	
	return ;} 

	$WorkNo= isset($_REQUEST['WorkNo'])? $_REQUEST['WorkNo'] : '';
	$WorkType= isset($_REQUEST['WorkType'])? $_REQUEST['WorkType'] : '0';
	$SendTime= isset($_REQUEST['SendTime'])? $_REQUEST['SendTime'] : '0000-00-00';
	$WorkCount= isset($_REQUEST['WorkCount'])? $_REQUEST['WorkCount'] : '0';
	$OverCount= isset($_REQUEST['OverCount'])? $_REQUEST['OverCount'] : '0';
	$SuccessCount= isset($_REQUEST['SuccessCount'])? $_REQUEST['SuccessCount'] : '0';
	$Money= isset($_REQUEST['Money'])? $_REQUEST['Money'] : '0';
	$WorkState= isset($_REQUEST['WorkState'])? $_REQUEST['WorkState'] : '0';
	$AddressSource= isset($_REQUEST['AddressSource'])? $_REQUEST['AddressSource'] : '';
	$AddressGroupId= isset($_REQUEST['AddressGroupId'])? $_REQUEST['AddressGroupId'] : '';
	$AddressFile= isset($_REQUEST['AddressFile'])? $_REQUEST['AddressFile'] : '';
	$Title= isset($_REQUEST['Title'])? $_REQUEST['Title'] : '';
	$SendTimeType= isset($_REQUEST['SendTimeType'])? $_REQUEST['SendTimeType'] : '0';
	$FixedTime= isset($_REQUEST['FixedTime'])? $_REQUEST['FixedTime'] : '';
	$IfEndTime= isset($_REQUEST['IfEndTime'])? $_REQUEST['IfEndTime'] : '0';
	$EndTime= isset($_REQUEST['EndTime'])? $_REQUEST['EndTime'] : '';
	$Level= isset($_REQUEST['Level'])? $_REQUEST['Level'] : '0';
	$WorkTimeSH1= isset($_REQUEST['WorkTimeSH1'])? $_REQUEST['WorkTimeSH1'] : '';
	$WorkTimeSM1= isset($_REQUEST['WorkTimeSM1'])? $_REQUEST['WorkTimeSM1'] : '';
	$WorkTimeEH1= isset($_REQUEST['WorkTimeEH1'])? $_REQUEST['WorkTimeEH1'] : '';
	$WorkTimeEM1= isset($_REQUEST['WorkTimeEM1'])? $_REQUEST['WorkTimeEM1'] : '';
	$WorkTimeSH2= isset($_REQUEST['WorkTimeSH2'])? $_REQUEST['WorkTimeSH2'] : '';
	$WorkTimeSM2= isset($_REQUEST['WorkTimeSM2'])? $_REQUEST['WorkTimeSM2'] : '';
	$WorkTimeEH2= isset($_REQUEST['WorkTimeEH2'])? $_REQUEST['WorkTimeEH2'] : '';
	$WorkTimeEM2= isset($_REQUEST['WorkTimeEM2'])? $_REQUEST['WorkTimeEM2'] : '';
	$IfVoiceTemplate= isset($_REQUEST['IfVoiceTemplate'])? $_REQUEST['IfVoiceTemplate'] : '';
	$VoiceTemplateId= isset($_REQUEST['VoiceTemplateId'])? $_REQUEST['VoiceTemplateId'] : '';
	$VoiceType= isset($_REQUEST['VoiceType'])? $_REQUEST['VoiceType'] : '';
	$VoiceFile= isset($_REQUEST['VoiceFile'])? $_REQUEST['VoiceFile'] : '';
	$TTS= isset($_REQUEST['TTS'])? $_REQUEST['TTS'] : '';
	if($VoiceType!='1')
	{
		$VoiceTemplateId="";	
	}
	if($VoiceType=='1')
	{
		if($VoiceTemplateId>0)
		{
			$VoiceFile=$fun->GetValue("select VoiceFile from t_voice_template where id=".$VoiceTemplateId);
		}
	}	
	if($VoiceType=='3')
	{
		$VoiceFile="wav/".date("Ymdhis").$fun->getRandNum()."";
		$fun->CreateFile("../../".$VoiceFile.".txt",$TTS);
		$result=shell_exec("tts "."/opt/lampp/htdocs/evoice/".$VoiceFile.".txt"." "."/opt/lampp/htdocs/evoice/".$VoiceFile.".wav");
		$VoiceFile.=".wav";
	}	

	$IfClick= isset($_REQUEST['IfClick'])? $_REQUEST['IfClick'] : '';
	$RepeatNum= isset($_REQUEST['RepeatNum'])? $_REQUEST['RepeatNum'] : '';
	$ReturnNum= isset($_REQUEST['ReturnNum'])? $_REQUEST['ReturnNum'] : '';
	$ComplainNum= isset($_REQUEST['ComplainNum'])? $_REQUEST['ComplainNum'] : '';
	$ReturnVoiceType= isset($_REQUEST['ReturnVoiceType'])? $_REQUEST['ReturnVoiceType'] : '';
	$ReturnVoiceFile= isset($_REQUEST['ReturnVoiceFile'])? $_REQUEST['ReturnVoiceFile'] : '';
	$ReturnTTS= isset($_REQUEST['ReturnTTS'])? $_REQUEST['ReturnTTS'] : '';
	if($ReturnVoiceType=='2')
	{
		$ReturnVoiceFile="wav/".date("Ymdhis").$fun->getRandNum()."";
		$fun->CreateFile("../../".$ReturnVoiceFile.".txt",$ReturnTTS);
		$result=shell_exec("tts "."/opt/lampp/htdocs/evoice/".$ReturnVoiceFile.".txt"." "."/opt/lampp/htdocs/evoice/".$ReturnVoiceFile.".wav");
		$ReturnVoiceFile.=".wav";
	}		
	$IfFax= isset($_REQUEST['IfFax'])? $_REQUEST['IfFax'] : '';
	$FaxFile= isset($_REQUEST['FaxFile'])? $_REQUEST['FaxFile'] : '';
	$IfMessage= isset($_REQUEST['IfMessage'])? $_REQUEST['IfMessage'] : '';
	$IfMessageS1= isset($_REQUEST['IfMessageS1'])? $_REQUEST['IfMessageS1'] : '';
	$IfMessageS2= isset($_REQUEST['IfMessageS2'])? $_REQUEST['IfMessageS2'] : '';
	$IfMessageS3= isset($_REQUEST['IfMessageS3'])? $_REQUEST['IfMessageS3'] : '';
	$IfMessageS4= isset($_REQUEST['IfMessageS4'])? $_REQUEST['IfMessageS4'] : '';
	$IfMessageS5= isset($_REQUEST['IfMessageS5'])? $_REQUEST['IfMessageS5'] : '';
	$IfMessageS6= isset($_REQUEST['IfMessageS6'])? $_REQUEST['IfMessageS6'] : '';
	$Message= isset($_REQUEST['Message'])? $_REQUEST['Message'] : '';
	$ComplainAgents=isset($_REQUEST['ComplainAgents'])? $_REQUEST['ComplainAgents'] : '';
	$ComplainAgents=str_replace("，",",",$ComplainAgents);
	$thisModel=new WorkInfo();
	$thisModel->UserId=$UserId;
if($WorkNo!=''){$thisModel->WorkNo=$WorkNo;}
if($WorkType!=''){$thisModel->WorkType=$WorkType;}
if($SendTime!=''){$thisModel->SendTime=$SendTime;}
if($WorkCount!=''){$thisModel->WorkCount=$WorkCount;}
if($OverCount!=''){$thisModel->OverCount=$OverCount;}
if($SuccessCount!=''){$thisModel->SuccessCount=$SuccessCount;}
if($Money!=''){$thisModel->Money=$Money;}
if($WorkState!=''){$thisModel->WorkState=$WorkState;}
if($AddressSource!=''){$thisModel->AddressSource=$AddressSource;}
if($AddressGroupId!=''){$thisModel->AddressGroupId=$AddressGroupId;}
if($AddressFile!=''){$thisModel->AddressFile=$AddressFile;}
if($Title!=''){$thisModel->Title=$Title;}
if($SendTimeType!=''){$thisModel->SendTimeType=$SendTimeType;}
if($FixedTime!=''){$thisModel->FixedTime=$FixedTime;}
if($IfEndTime!=''){$thisModel->IfEndTime=$IfEndTime;}
if($EndTime!=''){$thisModel->EndTime=$EndTime;}
if($Level!=''){$thisModel->Level=$Level;}
if($WorkTimeSH1!=''){$thisModel->WorkTimeSH1=$WorkTimeSH1;}
if($WorkTimeSM1!=''){$thisModel->WorkTimeSM1=$WorkTimeSM1;}
if($WorkTimeEH1!=''){$thisModel->WorkTimeEH1=$WorkTimeEH1;}
if($WorkTimeEM1!=''){$thisModel->WorkTimeEM1=$WorkTimeEM1;}
if($WorkTimeSH2!=''){$thisModel->WorkTimeSH2=$WorkTimeSH2;}
if($WorkTimeSM2!=''){$thisModel->WorkTimeSM2=$WorkTimeSM2;}
if($WorkTimeEH2!=''){$thisModel->WorkTimeEH2=$WorkTimeEH2;}
if($WorkTimeEM2!=''){$thisModel->WorkTimeEM2=$WorkTimeEM2;}
if($IfVoiceTemplate!=''){$thisModel->IfVoiceTemplate=$IfVoiceTemplate;}
if($VoiceTemplateId!=''){$thisModel->VoiceTemplateId=$VoiceTemplateId;}
if($VoiceType!=''){$thisModel->VoiceType=$VoiceType;}
if($VoiceFile!=''){$thisModel->VoiceFile=$VoiceFile;}
if($TTS!=''){$thisModel->TTS=$TTS;}
if($IfClick!=''){$thisModel->IfClick=$IfClick;}
if($RepeatNum!=''){$thisModel->RepeatNum=$RepeatNum;}
if($ReturnNum!=''){$thisModel->ReturnNum=$ReturnNum;}
if($ComplainNum!=''){$thisModel->ComplainNum=$ComplainNum;}
if($ReturnVoiceType!=''){$thisModel->ReturnVoiceType=$ReturnVoiceType;}
if($ReturnVoiceFile!=''){$thisModel->ReturnVoiceFile=$ReturnVoiceFile;}
if($ReturnTTS!=''){$thisModel->ReturnTTS=$ReturnTTS;}
if($IfFax!=''){$thisModel->IfFax=$IfFax;}
if($FaxFile!=''){$thisModel->FaxFile=$FaxFile;}
if($IfMessage!=''){$thisModel->IfMessage=$IfMessage;}
if($IfMessageS1!=''){$thisModel->IfMessageS1=$IfMessageS1;}
if($IfMessageS2!=''){$thisModel->IfMessageS2=$IfMessageS2;}
if($IfMessageS3!=''){$thisModel->IfMessageS3=$IfMessageS3;}
if($IfMessageS4!=''){$thisModel->IfMessageS4=$IfMessageS4;}
if($IfMessageS5!=''){$thisModel->IfMessageS5=$IfMessageS5;}
if($IfMessageS6!=''){$thisModel->IfMessageS6=$IfMessageS6;}
if($Message!=''){$thisModel->Message=$Message;}
if($CreateDate!=''){$thisModel->CreateDate=$CreateDate;}
if($id!=''){$thisModel->id=$id;}
if($UserId!=''){$thisModel->UserId=$UserId;}
if($WorkId!=''){$thisModel->WorkId=$WorkId;}
if($TelNo!=''){$thisModel->TelNo=$TelNo;}
if($Receiver!=''){$thisModel->Receiver=$Receiver;}
if($SendTime!=''){$thisModel->SendTime=$SendTime;}
if($TimeLength!=''){$thisModel->TimeLength=$TimeLength;}
if($SendNum!=''){$thisModel->SendNum=$SendNum;}
if($Money!=''){$thisModel->Money=$Money;}
if($SendResult!=''){$thisModel->SendResult=$SendResult;}
if($ComplainAgents!=''){$thisModel->ComplainAgents=$ComplainAgents;};
if($AddressText!=''){$thisModel->AddressText=$AddressText;};

	if($id>0)
	{
		$thisModel->id=$id;
	
		$rtn=$fun->UpdateModel($WorkDal->thisTable,$thisModel);
		if ($rtn==0){
		//echo Msg("操作失败.","back");
		}
		else{
			echo Msg("操作成功","list.php?action=list");
		} 
		return;		
	}
	else
	{
		$thisModel->WorkNo= date("Ymdhis").$fun->getRandNum();
	
		$rtn=$fun->AddModel("t_work",$thisModel);

		if ($rtn==0){
		//echo Msg("操作失败","back");
		}
		else{ 
			echo "<script>this.location='setdetail.php?id=$rtn';</script>";
			//echo Msg("开始导入电话号码，请稍后。。。","setdetail.php?id=$rtn");
		} 
		return;
	}
	
}

?>
<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="75%" height="45" style="border-bottom:2px #218644 solid">&nbsp;<span class="title"><?=$pagetitle?></span></td>
      <td width="25%" height="45" style="border-bottom:2px #218644 solid">&nbsp;</td>
    </tr>
  </table>
  <form id="form1" name="form1" method="post" action="?action=addsave" onsubmit="return check()" enctype="multipart/form-data">
    <table width="100%" height="215" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="13%" height="28" align="center" bgcolor="#FFFFFF">群发来源</td>
        <td width="87%" height="28" bgcolor="#FFFFFF"><?=$fun->GetRadioList("AddressSource",10,$AddressSource)?></td>
      </tr>
      <tr id="trAddressGroupId">
        <td height="28" align="center" bgcolor="#FFFFFF">&nbsp;</td>
        <td height="28" bgcolor="#FFFFFF"><?=$fun->GetComboList("AddressGroup","","-1","t_addressgroup","id,GroupName","multiple='multiple'",""," and UserId=".$_SESSION["userid"])?><input type="hidden" name="AddressGroupId" id="AddressGroupId" value="<?=$AddressGroupId?>" maxlength="100" style="width:300px"/><br />
群发语音：先在"群发地址本"创建地址本,然后点击"选择地址本"按钮,选择相应地址本群发语音；<br />
 注:外地手机不需要加0,小灵通一定要加区号。
        </td>
      </tr>
      <tr id="trAddressFile">
        <td height="28" align="center" bgcolor="#FFFFFF">上传文件</td>
        <td height="28" bgcolor="#FFFFFF">
		<iframe src="../up.php?control=AddressFile&type=xls" width="500"  scrolling="No" height="100" frameborder="0"></iframe><br />        
        <input type="text" name="AddressFile" id="AddressFile" value="<?=$AddressFile?>" maxlength="100" style="width:300px"/>
        <br />
*支持地址本文件类型：Excel（xls）<br />
*Excel类型的地址本格式：第二列（商务电话必填）<a href="example.xls">范例[example]</a></td>
      </tr>
      <tr id="trAddressText">
        <td height="28" align="center" bgcolor="#FFFFFF">电话号码</td>
        <td height="28" bgcolor="#FFFFFF">
        <input type="text" name="AddressText" id="AddressText" value="<?=$AddressText?>" maxlength="100" style="width:300px"/>
        <br />
*手工输入电话号码，多个号码用逗号分隔</td>
      </tr>      
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">主题</td>
        <td height="28" bgcolor="#FFFFFF"><input type="text" name="Title" id="Title" value="<?=$Title?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">发送时间</td>
        <td height="28" bgcolor="#FFFFFF">
          <?=$fun->GetRadioList("SendTimeType",11,$SendTimeType)?>&nbsp;&nbsp;<input type="text" name="FixedTime" id="FixedTime" value="<?=$FixedTime?>" maxlength="100" style="width:300px" class="ui_timepicker"/>
        </td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">终止时间</td>
        <td height="28" bgcolor="#FFFFFF">
          <input type="checkbox" id="IfEndTime" name="IfEndTime" value="1" <?=$IfEndTime==1?"checked":""?>/>&nbsp;&nbsp;<input type="text" name="EndTime" id="EndTime" value="<?=$EndTime?>" maxlength="100" style="width:300px" class="ui_timepicker"/>
        </td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">优先级</td>
        <td height="28" bgcolor="#FFFFFF">
          <?=$fun->GetRadioList("Level",12,$Level)?>
        </td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">语音文件</td>
        <td height="28" bgcolor="#FFFFFF">
          <?=$fun->GetRadioList("VoiceType",13,$VoiceType,"t_dic","ClassId,ClassName","",""," and ClassId=1")?>
        </td>
      </tr>
      <tr id="trVoiceTemplateId">
        <td height="28" align="center" bgcolor="#FFFFFF">语音模板选择</td>
        <td height="28" bgcolor="#FFFFFF">
          <?=$fun->GetComboList("VoiceTemplateId","",$VoiceTemplateId,"t_voice_template","id,TemplateName","",""," and Auditing=1 and UserId=".$_SESSION["userid"])?>
        </td>
      </tr>
      <tr id="trVoiceFile">
        <td height="28" align="center" bgcolor="#FFFFFF">上传语音文件</td>
        <td height="28" bgcolor="#FFFFFF">
	<iframe src="../up.php?control=VoiceFile&type=wav" width="500"  scrolling="No" height="100" frameborder="0"></iframe>
	<p>上传录音文件格式为WAV文件 8000HZ 16位 单声道 <br />        
	  </p>
	<p>
	  <input type="text" name="VoiceFile" id="VoiceFile" value="<?=$VoiceFile?>" maxlength="100" style="width:300px"/>
	  </p></td>
      </tr>
      <tr id="trTTS">
        <td height="28" align="center" bgcolor="#FFFFFF">TTS</td>
        <td height="28" bgcolor="#FFFFFF"><textarea name="TTS" id="TTS" style="width:300px"><?=$TTS?></textarea>
        </td>
      </tr>


      <tr id="trKeydown">
        <td height="28" align="center" bgcolor="#FFFFFF">按键设置</td>
        <td height="28" bgcolor="#FFFFFF">
        	<input type="checkbox" id="IfClick" name="IfClick" value="1" <?=$IfClick==1?"checked":""?>/><br />
        <table id="tbIfClick" width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>重听按键</td>
            <td><?=$fun->GetComboList("RepeatNum",7,$RepeatNum)?></td>
          </tr>
          <tr>
            <td>转接按键</td>
            <td><?=$fun->GetComboList("ComplainNum",7,$ComplainNum)?>转座席：<input type="text" name="ComplainAgents" id="ComplainAgents" value="<?=$ComplainAgents?>" maxlength="100" style="width:300px"/>（多个座席用逗号分割）</td>
          </tr>
          <tr>
            <td>回访按键</td>
            <td><?=$fun->GetComboList("ReturnNum",7,$ReturnNum)?></td>
          </tr>
          <tr>
            <td>回访语音</td>
            <td><?=$fun->GetRadioList("ReturnVoiceType",8,$ReturnVoiceType)?></td>
          </tr>
          <tr id="trReturnVoiceFile">
            <td>&nbsp;</td>
            <td>
            	<iframe src="../up.php?control=ReturnVoiceFile&type=wav" width="500"  scrolling="No" height="100" frameborder="0"></iframe>
            	<p>上传录音文件格式为WAV文件 8000HZ 16位 单声道<br />       
           	    </p>
            	<p>
            	  <input type="text" name="ReturnVoiceFile" id="ReturnVoiceFile" value="<?=$ReturnVoiceFile?>" maxlength="100" style="width:300px"/>
          	  </p></td>
          </tr>
          <tr id="trReturnTTS">
            <td>&nbsp;</td>
            <td><textarea name="ReturnTTS" id="ReturnTTS" style="width:300px"><?=$ReturnTTS?></textarea></td>
          </tr>
        </table></td>
      </tr>      
      <tr style="display:none">
        <td height="28" align="center" bgcolor="#FFFFFF">附加传真发送</td>
        <td height="28" bgcolor="#FFFFFF"><input type="checkbox" id="IfFax" name="IfFax" value="1" <?=$IfFax==1?"checked":""?>/>
        </td>
      </tr>
      <tr style="display:none">
        <td height="28" align="center" bgcolor="#FFFFFF">附加短信发送</td>
        <td height="28" bgcolor="#FFFFFF"><input type="checkbox" id="IfMessage" name="IfMessage" value="1" <?=$IfMessage==1?"checked":""?>/>
        </td>
      </tr>
      <tr>
        <td height="39" bgcolor="#FFFFFF"><div align="center"><input type="hidden" name="id" id="id" value="<?=$id?>" /></div></td>
        <td height="39" bgcolor="#FFFFFF"><input type="submit" name="Submit" id="sub_btn" class="bt" value="保存" />
        <input type="reset" name="Submit2" value="取消" class="bt" /></td>
      </tr>
    </table>
  </form>
</div>
<script>
	//$Id("tbIfClick").style.display="none";
	//$Id("trTTS").style.display="none";
	//$Id("trReturnTTS").style.display="none";
	AddressSourceSel(<?=$AddressSource?>);
	SendTimeTypeSel(<?=$SendTimeType?>);
	IfEndTimeSel(<?=$IfEndTime?>);
	ifClickSel(<?=$IfClick?>);
	VoiceTypeSel(<?=$VoiceType?>);
	vReturnTypeSel(<?=$ReturnVoiceType?>);
	
	vType=document.all["AddressSource"];
	for (i=0;i<vType.length;i++){
		vType[i].onclick=function(){AddressSourceSel(this.value);};
    }	
	
	vType=document.all["VoiceTemplateId"];
	vType.onclick=function(){VoiceTemplateIdSel(this.value);};
	
	vType=document.all["SendTimeType"];

	for (i=0;i<vType.length;i++){
		vType[i].onclick=function(){SendTimeTypeSel(this.value);};
    }
	
	vType=document.getElementById("IfEndTime");
	vType.onclick=function(){IfEndTimeSel(this.checked);};
		
	vType=document.all["VoiceType"];

	for (i=0;i<vType.length;i++){
		vType[i].onclick=function(){VoiceTypeSel(this.value);};
    }		
		
	vType=document.getElementById("IfClick");
	vType.onclick=function(){ifClickSel(this.checked);};
	
	vType=document.all["ReturnVoiceType"];

	for (i=0;i<vType.length;i++){
		vType[i].onclick=function(){vReturnTypeSel(this.value);};
    }		

	function VoiceTemplateIdSel(selId){
		if(selId==0)
		{
			document.getElementById("trKeydown").style.display="";
		}
		else
		{
			document.getElementById("trKeydown").style.display="none";
		}
	}

	function AddressSourceSel(selId){
		if(selId==2)
		{
			document.getElementById("trAddressGroupId").style.display="none";
			document.getElementById("trAddressFile").style.display="";
			document.getElementById("trAddressText").style.display="none";
		}
		else if(selId==3)
		{
			document.getElementById("trAddressGroupId").style.display="none";
			document.getElementById("trAddressFile").style.display="none";
			document.getElementById("trAddressText").style.display="";
		}
		else
		{
			document.getElementById("trAddressGroupId").style.display="";
			document.getElementById("trAddressFile").style.display="none";
			document.getElementById("trAddressText").style.display="none";
		}
	}
	
	function SendTimeTypeSel(selId){
		if(selId!=2)
		{
			document.getElementById("FixedTime").style.display="none";
		}
		else
		{
			document.getElementById("FixedTime").style.display="";
		}
	}	
	
	function IfEndTimeSel(selId){
		if(selId==true)
		{
			document.getElementById("EndTime").style.display="";
		}
		else
		{
			document.getElementById("EndTime").style.display="none";
		}
	}	

	function ifClickSel(selId){
		if(selId==true)
		{
			document.getElementById("tbIfClick").style.display="";
		}
		else
		{
			document.getElementById("tbIfClick").style.display="none";
		}
	}	


	function VoiceTypeSel(selId){
		if(selId==1)
		{
			document.getElementById("trVoiceTemplateId").style.display="";
			document.getElementById("trVoiceFile").style.display="none";
			document.getElementById("trTTS").style.display="none";
			tmp=document.all["VoiceTemplateId"];
			VoiceTemplateIdSel(tmp.value);
		}
		else if(selId==2)
		{
			document.getElementById("trVoiceTemplateId").style.display="none";
			document.getElementById("trVoiceFile").style.display="";
			document.getElementById("trTTS").style.display="none";
			VoiceTemplateIdSel(0);
		}
		else if(selId==3)
		{
			document.getElementById("trVoiceTemplateId").style.display="none";
			document.getElementById("trVoiceFile").style.display="none";
			document.getElementById("trTTS").style.display="";
			VoiceTemplateIdSel(0);			
		}else
		{
			document.getElementById("trVoiceTemplateId").style.display="";
			document.getElementById("trVoiceFile").style.display="none";
			document.getElementById("trTTS").style.display="none";
			VoiceTemplateIdSel(0);	
		}
	}
	
	function vReturnTypeSel(selId){
		if(selId!=2)
		{
			document.getElementById("trReturnVoiceFile").style.display="";
			document.getElementById("trReturnTTS").style.display="none";
		}
		else
		{
			document.getElementById("trReturnVoiceFile").style.display="none";
			document.getElementById("trReturnTTS").style.display="";
		}
	}
</script>
</body>
</html>