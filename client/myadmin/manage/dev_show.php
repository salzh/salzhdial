<?php

include("../check.php");
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/dev.class.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理员管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/fun.js"></script>
<style>
	form td{padding:3px;}
	td{font-size:12px;}
 
 


</style>
</head>

<body>
<?
 $model=new UserDevInfo();
 $user=new UserDevDal();
		$action=isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
		$ispass=isset($_REQUEST['ispass']) ? $_REQUEST['ispass'] : '0';
		$userid=isset($_REQUEST['userid']) ? $_REQUEST['userid'] : '0';
		$id=isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
		if($action=="save")
		{
			 if($user->SetIsPass($id,$ispass,$userid)>0)
				{
					//echo $userid;
					 echo Msg("操作成功","admin_dev.php?action=list");
					exit;
				}
				else
				{
					echo Msg("操作失败","back");
				}
		}
 
 
?>
 
 
<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="75%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;申请开发者联盟详情</td>
      <td width="25%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;</td>
    </tr>
  </table>
 
 
<?
 
		$id=isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	 	$list=$user->GetShow($id);
		foreach($list as $rs)
		{
		
		

?>  
    <form id="form1" name="form1" method="post" action="?action=save"  >
    <table width="100%" height="371" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="12%" height="28" bgcolor="#FFFFFF"><div align="center">申请用户</div></td>
        <td width="88%" height="28" bgcolor="#FFFFFF"><?=$rs['UserName']?> &nbsp; 类型:  <? if($rs['types']=='1'){echo "个人";}?>   <? if($rs['types']=='2'){echo "团队";}?> <? if($rs['types']=='3'){echo "公司";}?></td>
      </tr>
      
      <tr>
        <td height="28" bgcolor="#FFFFFF"><div align="center">姓名/团队/公司</div></td>
        <td height="28" bgcolor="#FFFFFF">
		<div id=box> <?=$rs['realname']?>
		</div>		</td>
      </tr>
      
      <tr>
        <td height="33" bgcolor="#FFFFFF"><div align="center">介绍/身份证</div></td>
        <td height="33" bgcolor="#FFFFFF"> <?=$rs['realinfo']?></td>
      </tr>
      <tr>
        <td height="33" bgcolor="#FFFFFF"><div align="center">地址</div></td>
        <td height="33" bgcolor="#FFFFFF"> <?=$rs['address']?></td>
      </tr>
      <tr>
        <td height="33" bgcolor="#FFFFFF"><div align="center">电话</div></td>
        <td height="33" bgcolor="#FFFFFF"><?=$rs['tel']?></td>
      </tr>
      <tr>
        <td height="33" bgcolor="#FFFFFF"><div align="center">地区</div></td>
        <td height="33" bgcolor="#FFFFFF"><?=$rs['cityname']?></td>
      </tr>
      <tr>
        <td height="33" bgcolor="#FFFFFF"><div align="center">致力与开发</div></td>
        <td height="33" bgcolor="#FFFFFF">
		
		<?
				$list2=$user->GetAppClassList($rs['dev']);
				foreach($list2 as $rs2)
				{
					echo "[".$rs2['ClassName'] ."] &nbsp;";
				}
		?>
		</td>
      </tr>
	  <tr>
        <td height="33" bgcolor="#FFFFFF"><div align="center">特长</div></td>
        <td height="33" bgcolor="#FFFFFF"><?=$rs['strong']?></td>
      </tr>
      <tr>
        <td height="33" bgcolor="#FFFFFF"><div align="center">申请时间</div></td>
        <td height="33" bgcolor="#FFFFFF"><?=$rs['times']?></td>
      </tr>
      <tr>
        <td height="33" bgcolor="#FFFFFF"><div align="center">通讯方式</div></td>
        <td height="33" bgcolor="#FFFFFF"><?=$rs['online']?> <input name="id" type="hidden" id="id" value="<?=$rs[0]?>" />
        <input name="userid" type="hidden" id="userid" value="<?=$rs["userid"]?>" /></td>
      </tr>
      <tr>
        <td height="33" bgcolor="#FFFFFF"><div align="center">状态</div></td>
        <td height="33" bgcolor="#FFFFFF"><input name="ispass" type="radio" value="1"  <? if($rs["ispass"]==1) { ?>checked="checked" <? } ?> />
启用&nbsp;
			<input type="radio" name="ispass" value="0" <? if($rs["ispass"]=="0") { ?>checked="checked" <? } ?> />
锁定
        &nbsp; </td>
      </tr>
      
      <tr>
        <td height="39" bgcolor="#FFFFFF"><div align="center"></div></td>
        <td height="39" bgcolor="#FFFFFF"><input type="submit" name="Submit" class="bt" value="保存信息" />
        <input type="reset" name="Submit2" value="取消" class="bt" /></td>
      </tr>
    </table>
  </form>

<?
}
?>

</div>



</body>
</html>
