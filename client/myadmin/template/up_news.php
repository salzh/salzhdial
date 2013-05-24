<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/template.class.php");
include("../../appeditor/fckeditor.php");
include("../check.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新闻管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/fun.js"></script>

<script language="javascript">
	function checked(f)
	{
		if(f.title.value=="")
		{
			alert("标题不能为空");
			f.title.focus();
			return false;
		}

		return true;
		
	}
</script>
<style>
	form td{padding:3px;}
	td{font-size:12px;}
 
 


</style>
</head>

<body>
 
<?
$info=new Info();
$infoClass=new InfoClass();
 
$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'add';
$templateId=isset($_POST["templateId"]) ? $_POST["templateId"]: '0';
$shows= isset($_POST["shows"]) ? $_POST["shows"] : '';
$typesid=isset($_POST["TypesId"]) ? $_POST["TypesId"]: '0';


if($a=="addsave" || $a=="modifysave")
{
	$title=$_POST["title"];
	$issn=$_POST["issn"];
	if($title=='')
	{
		$title='未命名';
	}

	$classid= isset($_REQUEST['bid']) ? $_REQUEST['bid'] : '0';	
	$ccid=$infoClass->GetBidSid($classid);
	 
	$bbid=$ccid['bbid'];
	$ssid=$ccid['ssid'];

	$times=date('Y-m-d');
	$typesid=isset($_POST["TypesId"]) ? $_POST["TypesId"]: '0';


	$depid= isset($_REQUEST['depid']) ? $_REQUEST['depid'] : '0';
	if($depid!=0)
	{
		$depid= implode(',', $depid);
	}	
	
	 $areaid= isset($_REQUEST['areaid']) ? $_REQUEST['areaid'] : '0';
	 
	 if($areaid!=0)
	{
		$areaid= implode(',', $areaid);
	}	
	$brandid= isset($_REQUEST['brandid']) ? $_REQUEST['brandid'] : '0';	
	$aa=array();
	$aa=$infoClass->GetBrandBidSid($brandid);
	$bid=$aa['bid'];
	$sid=$aa['sid'];
 	$orderid=isset($_REQUEST['orderid']) ? $_REQUEST['orderid'] : '0';	
	$info->title=$title;
	$info->issn=$issn;
	$info->typesId=$typesid;
	$info->times=$times;
	$info->idx=$orderid;
	$info->shows=$shows;
	$info->sid=$sid;
	$info->bid=$bid;
	$info->ssid=$ssid;
	$info->bbid=$bbid;	
	$info->areaId=$areaid;
	$info->deptId=$depid;
	$info->templateId=$templateId;
}
///添加保存
if($a=="addsave")
{	
	if($infoClass->AddInfo($info)>0)
	{
		echo Msg("操作成功","admin_news.php?action=list");
	}

}

if ($a=="modifysave")
{
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
	$info->Id=$id;
	if($id>0)
	{
			 
		if($infoClass->EditInfo($info)>0)
		{
			 echo Msg("操作成功","admin_news.php?action=list");
		}
		else
		{
			 echo Msg("操作失败","back");
		}
	}
}
?>

<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="75%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">新闻管理-添加</span></td>
      <td width="25%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;</td>
    </tr>
  </table>
<?php
 
if($a=="add")
{
?>
  <form id="form1" name="form1" method="post" action="?action=addsave"  onsubmit="return checked(this)">
    <table width="100%" height="616" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="7%" height="40" bgcolor="#FFFFFF"><div align="center">标 题 </div></td>
        <td width="93%" height="40" bgcolor="#FFFFFF"><input name="title" type="text" id="title" size="50" maxlength="100" />
          &nbsp;<div style="display:none">排序
          <input name="orderid" type="text" id="orderid" value="1" style="ime-mode:disabled" onKeyPress="if ((event.keyCode<48 || event.keyCode>57)) event.returnValue=false"  onkeydown="if(event.keyCode==13)event.keyCode=9"  size="5" maxlength="5" /></div>
          &nbsp;  
          <input name="hits" type="text" id="hits" value="1" style="ime-mode:disabled;display:none" onkeypress="if ((event.keyCode&lt;48 || event.keyCode&gt;57)) event.returnValue=false"  onkeydown="if(event.keyCode==13)event.keyCode=9"  size="5" maxlength="5"  />
&nbsp; </td>
      </tr>
      <tr>
        <td width="7%" height="40" bgcolor="#FFFFFF"><div align="center">刊 号 </div></td>
        <td width="93%" height="40" bgcolor="#FFFFFF"><input name="issn" type="text" id="title" size="50" maxlength="100" />
&nbsp; </td>
      </tr>      
      <tr style="display:none">
        <td height="40" bgcolor="#FFFFFF"><div align="center">板块子分类</div></td>
        <td height="40" bgcolor="#FFFFFF">
		<div id=box><div id=box2><?
		echo $infoClass->GetSelect();
		?>
		</div></div>		</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="right">所属分类&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </div></td>
        <td height="40" bgcolor="#FFFFFF">
		<select name='brandid' style='font-size:15px; color:#666; font-weight:bold; margin:5px;padding:15px;'>
        <option  value='41' style='background:#efefef;'>日报</option>
        <option  value='42' style='background:#efefef;'>周报</option>
        <option  value='43' style='background:#efefef;'>月报</option>
        </select>
		</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">区域</div></td>
        <td height="40" bgcolor="#FFFFFF">
		<?
		echo $infoClass->GetAreaList($_SESSION["auth"]);
		?>		</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">部门</div></td>
        <td height="40" bgcolor="#FFFFFF">
		<?
		echo $infoClass->GetDepList($_SESSION["product"]);
		?>		</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">模板类型：</div></td>
        <td height="40" bgcolor="#FFFFFF"><?=$infoClass->GetComboList("t_template","id,templateName",$templateId,"templateId","")?>
        </td>
      </tr>
      <tr style="display:none">
        <td height="40" bgcolor="#FFFFFF"><div align="center">类型：</div></td>
        <td height="40" bgcolor="#FFFFFF"><select name="TypesId" id="TypesId">
		 <option value="0" selected="selected"> </option>
          <option value="1">组织架构</option>
          <option value="2">品类管理</option>
          <option value="3">成功案例分享</option>
          <option value="4">TCP</option>
         
        </select>
        </td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">属性</div></td>
        <td height="40" bgcolor="#FFFFFF">是否发布：
<input name="shows" type="checkbox" id="shows" value="yes" checked="checked" /></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center"></div></td>
        <td height="40" bgcolor="#FFFFFF"><input type="submit" name="Submit" class="bt" value="保存信息" />
        <input type="reset" name="Submit2" value="取消" class="bt" /></td>
      </tr>
    </table>
  </form>
<?
}
?>  
  
  
<?
	if ($a=="modify")
	{
		$id=isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	 	$rs=$infoClass->GetInfoModel($id);
		//var_dump($rs);
?>  
    <form id="form1" name="form1" method="post" action="?action=modifysave"  onsubmit="return checked(this)">
    <table width="100%" height="657" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="7%" height="40" bgcolor="#FFFFFF"><div align="center">标 题 </div></td>
        <td width="93%" height="40" bgcolor="#FFFFFF"><input name="title" type="text" id="title"  value="<?=$rs->title?>" size="50" maxlength="100" />
		<input name="id" type="text" id="id"  value="<?=$rs->id?>"  style="display:none" />
          &nbsp;<div style="display:none">排序
          <input name="orderid" type="text" id="orderid"  value="<?=$rs->idx?>" style="ime-mode:disabled" onKeyPress="if ((event.keyCode<48 || event.keyCode>57)) event.returnValue=false"  onkeydown="if(event.keyCode==13)event.keyCode=9"  size="5" maxlength="5" /></div>
		</td>
      </tr>
      <tr>
        <td width="7%" height="40" bgcolor="#FFFFFF"><div align="center">刊 号 </div></td>
        <td width="93%" height="40" bgcolor="#FFFFFF"><input name="issn" type="text" id="title"  value="<?=$rs->issn?>" size="50" maxlength="100" />
		</td>
      </tr>
      <tr style="display:none">
        <td height="40" bgcolor="#FFFFFF"><div align="center">板块子分类</div></td>
        <td height="40" bgcolor="#FFFFFF">
		<div id=box>
		  <div id=box2><?
		echo $infoClass->GetSelect($rs->bbid,$rs->ssid);
		?></div>
		</div>		</td>
      </tr>
      <tr style="display:none">
        <td height="40" bgcolor="#FFFFFF"><div align="center">类型</div></td>
        <td height="40" bgcolor="#FFFFFF">
		
		<select name="TypesId" id="TypesId">
         <option value="0" <? if($rs->typesId==0) { ?> selected="selected" <? } ?>> </option>
		  <option value="1"  <? if($rs->typesId==1) { ?> selected="selected" <? } ?>>组织架构</option>
          <option value="2"  <? if($rs->typesId==2) { ?> selected="selected" <? } ?>>品类管理</option>
          <option value="3"  <? if($rs->typesId==3) { ?> selected="selected" <? } ?>>成功案例分享</option>
          <option value="4"  <? if($rs->typesId==4) { ?> selected="selected" <? } ?>>TCP</option>
         </select></td>
      </tr>     
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">所属分类</div></td>
        <td height="40" bgcolor="#FFFFFF">
		<select name='brandid' style='font-size:15px; color:#666; font-weight:bold; margin:5px;padding:15px;'>
        <option  value='41' style='background:#efefef;' <?=$rs->bid==41?"selected":""?>>日报</option>
        <option  value='42' style='background:#efefef;' <?=$rs->bid==42?"selected":""?>>周报</option>
        <option  value='43' style='background:#efefef;' <?=$rs->bid==43?"selected":""?>>月报</option>
        </select>
		</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">模板类型</div></td>
        <td height="40" bgcolor="#FFFFFF"><?=$infoClass->GetComboList("t_template","id,templateName",$rs->templateId,"templateId","")?></td>
      </tr>       
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">区域</div></td>
        <td height="40" bgcolor="#FFFFFF"><?
		echo $infoClass->GetAreaList($rs->areaId);
		?>        </td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">部门</div></td>
        <td height="40" bgcolor="#FFFFFF"><?
		echo $infoClass->GetDepList($rs->deptId);
		?>        </td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">属性</div></td>
        <td height="40" bgcolor="#FFFFFF">是否发布：
<input name="shows" type="checkbox" id="shows" value="yes"  <?  if($rs->shows=="yes")
											{ 
										   ?>  checked="checked" 
										   <? }?> /></td>
      </tr>

      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center"></div></td>
        <td height="40" bgcolor="#FFFFFF"><input type="submit" name="Submit" class="bt" value="保存信息" />
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
