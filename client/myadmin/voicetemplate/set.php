<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/voicetemplate.class.php");
include("../../class/fun.class.php");
$pagetitle="语音模板管理";
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
<script type="text/javascript" src="../javascript/jquery-1.4.1.min.js" charset="utf-8"></script>
<script type="text/javascript">
	var $j=jQuery.noConflict();
	var $Id=function(id){return document.getElementById(id);}
	String.prototype.Trim = function(){return this.replace(/(^\s*)|(\s*$)/g, "");}    
	function check(){
		var uname=$Id("TemplateName").value;
		if(uname.Trim()==""){alert("请输入名称");$Id("TemplateName").focus();return false;}
	}
	var $j=jQuery.noConflict();
	var $Id=function(id){return document.getElementById(id);}
	function selAll(id,cn,type){
		var obj=$j("#"+id).find("."+cn);
		for(var i=0;i<obj.length;i++){
			 if(type==1){
				 obj[i].checked=true;
			 }else{
				 obj[i].checked=obj[i].checked==true?false:true;
			 } 
		}
	}
</script>
</head>

<body>
<?

$fun=new FunDal();
$ac=$_REQUEST['action'];
$id= isset($_REQUEST['id'])? $_REQUEST['id'] : '0';
$UserId=$_SESSION["userid"];

$TemplateName="";
$ComplainAgents="";
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

$thisDal= new VoiceTemplateDal();

if($id!="0")
{
	$thisModel=$thisDal->GetModelById($id);
	$id=$thisModel->id;
	$UserId=$thisModel->UserId;
	$TemplateName=$thisModel->TemplateName;
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
	$CreateDate=$thisModel->CreateDate;
	$ComplainAgents=$thisModel->ComplainAgents;
}

if($ac=="addsave"){

	if(trim($_REQUEST['TemplateName'])==''){
		echo Msg("操作失败。","back");
	
	return ;} 
	
	$thisModel=new VoiceTemplateInfo();
	$thisModel->UserId=$UserId;
	$thisModel->TemplateName=$_REQUEST['TemplateName'];
	$thisModel->VoiceType=$_REQUEST['VoiceType'];
	$thisModel->VoiceFile=$_REQUEST['VoiceFile'];
	$thisModel->TTS=$_REQUEST['TTS'];
	if($thisModel->VoiceType=='2')
	{
		$thisModel->VoiceFile="wav/".date("Ymdhis").$fun->getRandNum()."";
		$fun->CreateFile("../../".$thisModel->VoiceFile.".txt",$thisModel->TTS);
		$result=shell_exec("tts "."/opt/lampp/htdocs/evoice/".$thisModel->VoiceFile.".txt"." "."/opt/lampp/htdocs/evoice/".$thisModel->VoiceFile.".wav");
		$thisModel->VoiceFile.=".wav";
	}
	$thisModel->IfClick=isset($_REQUEST['IfClick'])?$_REQUEST['IfClick']:'0';
	$thisModel->RepeatNum=$_REQUEST['RepeatNum'];
	$thisModel->ReturnNum=$_REQUEST['ReturnNum'];
	$thisModel->ComplainNum=$_REQUEST['ComplainNum'];
	$thisModel->ReturnVoiceType=$_REQUEST['ReturnVoiceType'];
	$thisModel->ReturnVoiceFile=$_REQUEST['ReturnVoiceFile'];
	$thisModel->ReturnTTS=$_REQUEST['ReturnTTS'];
	$thisModel->ComplainAgents=$_REQUEST['ComplainAgents'];
	if($thisModel->ReturnVoiceType=='2')
	{
		$thisModel->ReturnVoiceFile="wav/".date("Ymdhis").$fun->getRandNum()."";
		$fun->CreateFile("../../".$thisModel->ReturnVoiceFile.".txt",$thisModel->ReturnTTS);
		$result=shell_exec("tts "."/opt/lampp/htdocs/evoice/".$thisModel->ReturnVoiceFile.".txt"." "."/opt/lampp/htdocs/evoice/".$thisModel->ReturnVoiceFile.".wav");
		$thisModel->ReturnVoiceFile.=".wav";
	}	
	$thisModel->Auditing=0; 
	if($id>0)
	{
		$thisModel->id=$id;
		//$thisModel->UserId=$_SESSION["userid"];
		$rtn=$fun->UpdateModel("t_voice_template",$thisModel);
		if ($rtn==0){
		echo Msg("操作失败.","back");
		}
		else{
			echo Msg("操作成功","list.php?action=list");
		} 
		return;		
	}
	else
	{
		//var_dump($ainfo);
		$rtn=$fun->AddModel("t_voice_template",$thisModel);
		if ($rtn==0){
		echo Msg("操作失败","back");
		}
		else{
			echo Msg("操作成功","list.php?action=list");
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
  <form id="form1" name="form1" method="post" action="?action=addsave" onsubmit="return check()">
    <table width="100%" height="215" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="13%" height="28" align="center" bgcolor="#FFFFFF">模板名称</td>
        <td width="87%" height="28" bgcolor="#FFFFFF"><input type="text" name="TemplateName" id="TemplateName" value="<?=$TemplateName?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">语音文件</td>
        <td height="28" bgcolor="#FFFFFF"><?=$fun->GetRadioList("VoiceType",8,$VoiceType)?></td>
      </tr>
      <tr id="trVoiceFile">
        <td height="28" align="center" bgcolor="#FFFFFF">上传文件</td>
        <td height="28" bgcolor="#FFFFFF">
	<iframe src="../up.php?control=VoiceFile&type=wav" width="500"  scrolling="No" height="100" frameborder="0"></iframe><br />        
        <input type="text" name="VoiceFile" id="VoiceFile" value="<?=$VoiceFile?>" maxlength="100" style="width:300px"/></td>
      </tr>
      <tr id="trTTS">
        <td height="28" align="center" bgcolor="#FFFFFF">TTS文本</td>
        <td height="28" bgcolor="#FFFFFF"><textarea name="TTS" id="TTS" style="width:300px"><?=$TTS?>
        </textarea></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">&nbsp;</td>
        <td height="28" bgcolor="#FFFFFF"><p>特别提醒：<br />
          *支持语音文件类型:WAV,上传录音文件格式为WAV文件 8000HZ 16位 单声道<br />
        *选择发送的文件请提前关闭，而且文件不能加密。 </p></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">按键设置</td>
        <td height="28" bgcolor="#FFFFFF">
        	<input type="checkbox" id="IfClick" name="IfClick" value="1" <?=$IfClick==1?"checked":""?>/><br />
        <table id="tbIfClick" width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>重听按键</td>
            <td><?=$fun->GetComboList("RepeatNum",7,$RepeatNum)?></td>
          </tr>
          <tr>
            <td>投诉按键</td>
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
            	<iframe src="../up.php?control=ReturnVoiceFile&type=wav" width="500"  scrolling="No" height="100" frameborder="0"></iframe><br />       
            <input type="text" name="ReturnVoiceFile" id="ReturnVoiceFile" value="<?=$ReturnVoiceFile?>" maxlength="100" style="width:300px"/></td>
          </tr>
          <tr id="trReturnTTS">
            <td>&nbsp;</td>
            <td><textarea name="ReturnTTS" id="ReturnTTS" style="width:300px"><?=$ReturnTTS?>
            </textarea></td>
          </tr>
        </table></td>
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
	ifClickSel(<?=$IfClick?>);
	vTypeSel(<?=$VoiceType?>);
	vReturnTypeSel(<?=$ReturnVoiceType?>);
	
	vType=document.getElementById("IfClick");
	vType.onclick=function(){ifClickSel(this.checked);};

	vType=document.all["VoiceType"];

	for (i=0;i<vType.length;i++){
		vType[i].onclick=function(){vTypeSel(this.value);};
    }	
	
	vType=document.all["ReturnVoiceType"];

	for (i=0;i<vType.length;i++){
		vType[i].onclick=function(){vReturnTypeSel(this.value);};
    }		

	function ifClickSel(selId){
		if(selId==true)
		{
			$Id("tbIfClick").style.display="";
		}
		else
		{
			$Id("tbIfClick").style.display="none";
		}
	}	
	function vTypeSel(selId){
		if(selId!=2)
		{
			$Id("trVoiceFile").style.display="";
			$Id("trTTS").style.display="none";
		}
		else
		{
			$Id("trVoiceFile").style.display="none";
			$Id("trTTS").style.display="";
		}
	}
	function vReturnTypeSel(selId){
		if(selId!=2)
		{
			$Id("trReturnVoiceFile").style.display="";
			$Id("trReturnTTS").style.display="none";
		}
		else
		{
			$Id("trReturnVoiceFile").style.display="none";
			$Id("trReturnTTS").style.display="";
		}
	}
</script>
</body>
</html>