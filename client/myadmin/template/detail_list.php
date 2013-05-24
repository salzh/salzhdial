<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../include/page.class.php");
include("../../class/template.class.php");
include("../check.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新闻管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />

</head>

<body>
<script language="javascript" src="../javascript/jquery-1.4.1.min.js"></script>
<script language="javascript" src="../javascript/table.js"></script>

 

<div class="box">
<?php
$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
$parentid= isset($_REQUEST['parentid']) ? $_REQUEST['parentid'] : '0';
$info=new Info();
$infoClass=new InfoClass();
$info=$infoClass->GetInfoModel($id);

$templateInfo = new TemplateInfo();
$templateInfo=$infoClass->GetTemplateModel($info->templateId);
$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';

$rsItem=$infoClass->GetTemplateItemModel($templateInfo->id);


if ($a=="delrow")
{
	$rowid= isset($_REQUEST['rowid']) ? $_REQUEST['rowid'] : '0';
	$templateItemId=isset($_REQUEST['templateItemId']) ? $_REQUEST['templateItemId'] : '0';
	$templateItem=$infoClass->GetTemplateItemModel(0,$templateItemId);
	if($infoClass->DelRecord($templateItem->tableName,$rowid))
	{
		echo Msg("操作成功","detail_list.php?action=list&id=".$id);
	}
}
if ($a=="del")
{
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	if($info->DelInfo($id))
	{
		echo Msg("操作成功","admin_news.php?action=list");
	}
}
if ($a=="delall")
{
	$idlist= isset($_REQUEST['idlist']) ? $_REQUEST['idlist'] : '0';
	if($idlist!=0)
	{
		$idlist= implode(',', $idlist);
		 if($infoClass->DelItem($idlist,$id))
		{
			echo Msg("操作成功","detail_list.php?id=".$id."&action=modify");
		}
	}
	else
	{
		echo Msg("至少选中一项目","back");
	
	}
}

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <form id="form1" name="form1" method="get" action="?">
    <td width="49%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">新闻栏目管理</span></td>
    <td width="19%" style="border-bottom:2px #0066CC solid"><div align="right">
    </div></td>
    <td width="32%" height="45" style="border-bottom:2px #0066CC solid">
      <div align="right">

        </div>
   </td>
  </tr>
   </form>
</table>
<form id="form2" name="form2" method="post" action="?action=delall" onsubmit="return confirm('确定要删除吗，删除后不可恢复');">
  <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#E6DB55" style="margin-top:5px;">
    <tr>
      <td height="36" bgcolor="#FFFBCC"><table width="100%" border="0" cellpadding="0" cellspacing="5" bgcolor="#FFFBCC">
        <tr>
          <td width="25%" height="26" bgcolor="#FFFBCC">
          <input type="hidden" id="id" name="id" value="<?=$id?>" />
          <input onclick="checkAllLine()" class="chkbox" id="checkedAll" name="checkedAll" type="checkbox" />
            全选&nbsp;
            <input type="submit" name="Submit23" value="删除选中"    class="button" /></td>
          <td width="37%" bgcolor="#FFFBCC">&nbsp;</td>
          <td width="38%"><div align="right">
          <input type="button" name="Submit1" value="预览" onclick="window.location.href('../../tpl/index.php?id=<?=$id?>');"  class="button" />
          	<input type="button" name="Submit3" value="添加栏目" onclick="window.location.href('up_item.php?action=add&parentid=<?=$id?>');"  class="button" />
            <input type="button" name="Submit2" value="返回上一步" onclick="history.back(-1);"  class="button" />
            <input type="button" name="Submit22" value="刷新本页" onclick="history.back(0);" class="button" />
          </div></td>
        </tr>
      </table></td>
    </tr>
  </table>
 
 <?
 	foreach($rsItem as $item)
	{
		$rsRow=$infoClass->GetDetailList($item->tableName,$id);
		if(count($rsRow)>0)
		{
 ?>
    <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC" style="margin-top:5px;"  class="cooltable">
      <tr  height="35" style="color:#fff;">
        <td width="3%" bgcolor="#0066CC" ><div align="center">选择</div></td>
        <td width="4%" bgcolor="#0066CC" ><div align="center">编号</div></td>
        <td width="31%" bgcolor="#0066CC" ><div align="center">栏目</div></td>
        <td width="50" bgcolor="#0066CC" ><div align="center">颜色</div></td>
        <td width="50" bgcolor="#0066CC" ><div align="center">排序</div></td>
        <td width="6%" bgcolor="#0066CC" ><div align="center">状态</div></td>
      </tr> 
  <tr  height="30" >
        <td><div align="center">
          <input type="checkbox" name="idlist[]"  id="idlisst[]" class="chkbox"  value="<?=$item->id?>" />
        </div></td>
        <td><div align="center">
          <?=$item->id?>
        </div></td>
        <td><a href="up_detail.php?id=<?=$item->id?>&action=modify"><?=$item->itemName?></a></td>
        <td><?=$item->bgColor?></td>
        <td><?=$item->idx?></td>        
        <td align="center"> <?=$item->ifvalid!='1'?'锁定':'启用'?></td>
      </tr>
  <tr  height="30" >
        <td colspan="6">
        	<table width="100%">
            	<tr>
                <?
              	  	$rsField=$infoClass->GetTemplateItemFieldModel($item->id);
					foreach($rsField as $field)
					{
				?>
                	<td>
                    	<?=$field->fieldTitle?>
                    </td>
                <?
					}
				?>
                	<td width="50" align="center">状态
                    </td>
                    <td width="50" align="center">操作
                    </td>
                </tr>  
                <?
				

				foreach($rsRow as $row)
				{
				?>
            	<tr>
                <?
              	  	$rsField=$infoClass->GetTemplateItemFieldModel($item->id);
					foreach($rsField as $field)
					{
				?>
                	<td>
                    	<?=$row[$field->fieldName]?>
                    </td>
                <?
					}
				?>
                    <td align="center"> <?=$row['shows']=='yes'?'启用':'锁定'?></td>
                    <td><div align="center">
                    <a href="up_item.php?id=<?=$row['id']?>&templateItemId=<?=$item->id?>&action=modify&parentid=<?=$id?>"><img src="../images/edit.png" style="border:0px;" /></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="?action=delrow&id=<?=$id?>&rowid=<?=$row['id']?>&templateItemId=<?=$item->id?>" onclick="return confirm('确定要删除吗，删除后不可恢复');"> <img src="../images/del.png" style="border:0px;" /></a></div></td>                
                </tr> 
                <?
				}
				?>                           
        	</table>
        </td>        
      </tr>      
  <?php
		//$rsItemList=$infoClass->GetTemplateItemFieldModel($item->id);
		}
	}
?>
  </table>

</form>

</div>

</body>
</html>