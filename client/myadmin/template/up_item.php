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
<title>新闻栏目管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/fun.js"></script>

<script language="javascript">
	function checked(f)
	{


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

$parentid= isset($_REQUEST['parentid']) ? $_REQUEST['parentid'] : '0';

$rsInfo=$infoClass->GetInfoModel($parentid);

$tid=$rsInfo->templateId;

$templateItemId=isset($_REQUEST['templateItemId']) ? $_REQUEST['templateItemId'] : '0';

$tableName="";
if($templateItemId!=0)
{
	$rsItem=$infoClass->GetTemplateItemModel(0,$templateItemId);  //得到二级模板项
	$tableName=$rsItem->tableName;
}

$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'add';

///添加保存
if($a=="addsave")
{	
	$shows= isset($_POST["shows"]) ? $_POST["shows"] : '';
	$idx= isset($_POST["orderid"]) ? $_POST["orderid"] : '1';
	$rsField=$infoClass->GetTemplateItemFieldModel($templateItemId);  ////得到三级级模板项

	$sqlField="";
	$sqlValue="";
	$sqlField.="shows,";
	$sqlValue.="'$shows',";
	foreach($rsField as $field)
	{
		$sqlField.=$field->fieldName.",";
		$tmp=isset($_REQUEST["c".$field->fieldName]) ? $_REQUEST["c".$field->fieldName] : '';
		if(is_array($tmp))
		{
			$tmp=implode(',',$tmp);
		}
		$sqlValue.="'$tmp',";
	}
	$sqlField.="idx,parentid";
	$sqlValue.="'$idx','$parentid'";
	//echo $sqlField.";".$sqlValue;
	if($infoClass->InsertRecord($tableName,$sqlField,$sqlValue)>0)
	{
		echo Msg("操作成功","detail_list.php?action=list&id=".$parentid);
	}

}

if ($a=="modifysave")
{
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
	
	$shows= isset($_POST["shows"]) ? $_POST["shows"] : '';
	$idx= isset($_POST["orderid"]) ? $_POST["orderid"] : '';
	$templateItemId= isset($_POST["itemid"]) ? $_POST["itemid"] : '0';

	$tableName=$infoClass->GetTemplateTable($templateItemId);
	$rsField=$infoClass->GetTemplateItemFieldModel($templateItemId);

	$sqlField="";
	$sqlField.="shows='$shows',";
	foreach($rsField as $field)
	{
		$sqlField.=$field->fieldName."=";
		$tmp=isset($_REQUEST["c".$field->fieldName]) ? $_REQUEST["c".$field->fieldName] : '';
		if(is_array($tmp))
		{
			$tmp=implode(',',$tmp);
		}
		$sqlField.="'$tmp',";
	}
	$sqlField.="idx='$idx',parentid='$parentid'";
	//echo $sqlField;
	//exit(0);
	if($infoClass->UpdateRecord($tableName,$sqlField,$id)>0)
	{
		echo Msg("操作成功","detail_list.php?action=list&id=".$parentid);
	}
	
	
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
      <td width="75%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">新闻栏目管理-添加</span></td>
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
        <td width="7%" height="40" bgcolor="#FFFFFF"><div align="center">栏 目</div></td>
        <td width="93%" height="40" bgcolor="#FFFFFF">
		<?
		echo $infoClass->GetComboList("t_template_item","id,itemName",$templateItemId,"templateItemId","changeValue(this);",$tid,""," and ifvalid=1");
		?>
          &nbsp;排序
          <input name="orderid" type="text" id="orderid" value="1" style="ime-mode:disabled" onKeyPress="if ((event.keyCode<48 || event.keyCode>57)) event.returnValue=false"  onkeydown="if(event.keyCode==13)event.keyCode=9"  size="5" maxlength="5" /> </td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">属性</div></td>
        <td height="40" bgcolor="#FFFFFF">是否发布：
<input name="shows" type="checkbox" id="show" value="yes" checked="checked" />
<input id="parentid" name="parentid" type="hidden" value="<?=$parentid?>" />
		</td>
      </tr>
      <?
	  	$rsField=$infoClass->GetTemplateItemFieldModel($templateItemId);
		foreach($rsField as $field)
		{
	  ?>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center"><?=$field->fieldTitle?></div></td>
        <td height="40" bgcolor="#FFFFFF">
		<?php
		if($field->fieldType==1)
		{
			echo "<input type='text' name='c$field->fieldName' id='c$field->fieldName' width=80/>";
		}
		else if($field->fieldType==4)
		{
			echo "收藏按钮<input type='hidden' name='c$field->fieldName' id='c$field->fieldName' width=80 value='sc'/>";
		}
		else if($field->fieldType==5)
		{
			echo $infoClass->GetCmbSelect(5,"clevel","");
		}
		else if($field->fieldType==6)
		{
			echo $infoClass->GetOptionSelect(6,"cchannel","");
		}
		else if($field->fieldType==7)
		{
			echo $infoClass->GetOptionSelect(7,"ctype","");
		}
		else if($field->fieldType==8)
		{
			echo $infoClass->GetOptionSelect(8,"ccategory","");
		}									
		else
		{
        	Editor("c".$field->fieldName,"../","");
		}
		?>
        </td>
      </tr>
      <?
		}
	  ?> 
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
		$rsField=$infoClass->GetTemplateItemFieldModel($templateItemId);
		$templateTable=$infoClass->GetTemplateTable($templateItemId);
		$rsRow=$infoClass->GetDetailList($templateTable,0," and id=".$id);
		//var_dump($rsRow);
?>  
  <form id="form1" name="form1" method="post" action="?action=modifysave"  onsubmit="return checked(this)">
    <table width="100%" height="616" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="7%" height="40" bgcolor="#FFFFFF"><div align="center">栏 目</div></td>
        <td width="93%" height="40" bgcolor="#FFFFFF"><?=$infoClass->GetComboList("t_template_item","id,itemName",$templateItemId,"templateItemId","modifyValue(this);",$tid,"disabled")?>
          &nbsp;排序
          <input name="orderid" type="text" id="orderid" value="<?=$rsRow[0]['idx']?>" style="ime-mode:disabled" onKeyPress="if ((event.keyCode<48 || event.keyCode>57)) event.returnValue=false"  onkeydown="if(event.keyCode==13)event.keyCode=9"  size="5" maxlength="5" /> </td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">属性</div></td>
        <td height="40" bgcolor="#FFFFFF">是否发布：
<input name="shows" type="checkbox" id="show" value="yes" <?=$rsRow[0]['shows']=='yes'?'checked':'';?> />
<input id="parentid" name="parentid" type="hidden" value="<?=$rsRow[0]['parentId']?>" />
<input id="itemid" name="itemid" type="hidden" value="<?=$templateItemId?>" />
<input id="id" name="id" type="hidden" value="<?=$id?>" />
		</td>
      </tr>
      <?

		//var_dump($rsRow);
		foreach($rsField as $field)
		{
	  ?>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center"><?=$field->fieldTitle?></div></td>
        <td height="40" bgcolor="#FFFFFF">
		<?php
		if($field->fieldType==1)
		{
			echo "<input type='text' name='c$field->fieldName' id='c$field->fieldName' width=80 value='".$rsRow[0][$field->fieldName]."'/>";
		}
		else if($field->fieldType==4)
		{
			echo "收藏按钮<input type='hidden' name='c$field->fieldName' id='c$field->fieldName' width=80 value='sc'/>";
		}
		else if($field->fieldType==5)
		{
			echo $infoClass->GetCmbSelect(5,"clevel",$rsRow[0][$field->fieldName]);
		}
		else if($field->fieldType==6)
		{
			echo $infoClass->GetOptionSelect(6,"cchannel",$rsRow[0][$field->fieldName]);
		}
		else if($field->fieldType==7)
		{
			echo $infoClass->GetOptionSelect(7,"ctype",$rsRow[0][$field->fieldName]);
		}
		else if($field->fieldType==8)
		{
			echo $infoClass->GetOptionSelect(8,"ccategory",$rsRow[0][$field->fieldName]);
		}						
		else
		{
        	Editor("c".$field->fieldName,"../",$rsRow[0][$field->fieldName]);
		}
		?>
        </td>
      </tr>
      <?
		}
	  ?> 
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

<script>
function changeValue(obj){
    var str = obj.options[obj.selectedIndex].value;
    window.location.href('up_item.php?action=add&parentid=<?=$parentid?>&templateItemId='+str);
    }
function modifyValue(obj){
    var str = obj.options[obj.selectedIndex].value;
    window.location.href('up_item.php?action=modify&parentid=<?=$parentid?>&templateItemId='+str);
    }	
</script>

</body>
</html>
