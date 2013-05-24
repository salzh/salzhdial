<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/workdetail.class.php");
include("../../class/fun.class.php");
$pagetitle="详细信息";
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
</head>

<body>

<?

$fun=new FunDal();

$id= isset($_REQUEST['id'])? $_REQUEST['id'] : '0';

$UserId=$_SESSION["userid"];

$thisDal= new WorkDetailDal();

if($id!="0")
{
	$s=" and a.id=$id and a.UserId=$UserId";
	$list=$thisDal->GetPageList($s);
}


?>
<div class="box">
<div>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="75%" height="45" style="border-bottom:2px #218644 solid">&nbsp;<span class="title"><?=$pagetitle?></span></td>
      <td width="25%" height="45" style="border-bottom:2px #218644 solid">&nbsp;</td>
    </tr>
  </table>
</div>
<div style="text-align:center">
  <form id="form1" name="form1" method="post" action="?action=addsave" onsubmit="return check()" enctype="multipart/form-data">
  
    <table width="600" height="360" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td height="28" colspan="2" align="center"  bgcolor="#218644" style="color:#FFF">详细内容</td>
      </tr>
      <tr>
        <td width="21%" height="28" align="center" bgcolor="#FFFFFF">被叫号码</td>
        <td width="79%" height="28" bgcolor="#FFFFFF"><?=$list[0]['TelNo']?></td>
      </tr>
      <tr id="trAddressGroupId">
        <td height="28" align="center" bgcolor="#FFFFFF">收件人</td>
        <td height="28" bgcolor="#FFFFFF"><?=$list[0]['Receiver']?></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">主题</td>
        <td height="28" bgcolor="#FFFFFF"><?=$list[0]['Title']?></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">作业号</td>
        <td height="28" bgcolor="#FFFFFF"><?=$list[0]['WorkNo']?></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">优先级</td>
        <td height="28" bgcolor="#FFFFFF"><?=$list[0]['Level']?></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">定时发送</td>
        <td height="28" bgcolor="#FFFFFF"><?=$list[0]['SendTime']?></td>
      </tr>
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF">时长</td>
        <td height="28" bgcolor="#FFFFFF"><?=$list[0]['TimeLength']?></td>
      </tr>
      <tr id="trVoiceTemplateId3">
        <td height="28" align="center" bgcolor="#FFFFFF">语音费用（元）</td>
        <td height="28" bgcolor="#FFFFFF"><?=$list[0]['Money']?></td>
      </tr>
      <tr id="trVoiceTemplateId">
        <td height="28" align="center" bgcolor="#FFFFFF">语音文件</td>
        <td height="28" bgcolor="#FFFFFF"><?=$list[0]['VoiceFile']?></td>
      </tr>
      <tr>
        <td height="39" colspan="2" bgcolor="#FFFFFF"><div style="width:100px;margin:auto"><input type="button" name="Submit2" value="返回上一步" onclick="history.back(-1);"  class="button" /></div></td>
      </tr>
    </table>
  </form>
</div>

</body>
</html>