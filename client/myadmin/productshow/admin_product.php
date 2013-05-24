<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../include/page.class.php");
include("../../class/showproduct.class.php");
include("../check.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>网站其他栏目</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />

</head>

<body>
<script language="javascript" src="../javascript/jquery-1.4.1.min.js"></script>
<script language="javascript" src="../javascript/table.js"></script>

 

<div class="box">
<?php
$ShowProductClass=new ShowProductClass();
$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';
if ($a=="del")
{
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	if($ShowProductClass->Del($id))
	{
	echo Msg("操作成功","admin_product.php?action=list");
	}
}
if ($a=="modifyshow")
{
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	$state= isset($_REQUEST['state']) ? $_REQUEST['state'] : '0';
	if($ShowProductClass->modifyshow($id,$state))
	{
	echo Msg("操作成功","admin_product.php?action=list");
	}
}
if ($a=="delall")
{
	$idlist= isset($_REQUEST['idlist']) ? $_REQUEST['idlist'] : '0';
	if($idlist!=0)
	{
		$idlist= implode(',', $idlist);
		 if($ShowProductClass->Del($idlist))
		{
			echo Msg("操作成功","admin_product.php?action=list");
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
    <td width="72%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">产品图片管理</span></td>
    <td width="28%" height="45" style="border-bottom:2px #0066CC solid"><form id="form1" name="form1" method="post" action="?">
      <div align="right">
        
        <select name="types" id="types">
          <option value="1">联合利华</option>
          <option value="2">竞品</option>
        </select>
        <input type="submit" name="Submit" value="搜索" class="button" />
        </div>
    </form></td>
  </tr>
</table>
<form id="form2" name="form2" method="post" action="?action=delall" onsubmit="return confirm('确定要删除吗，删除后不可恢复');">
  <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#E6DB55" style="margin-top:5px;">
    <tr>
      <td height="36" bgcolor="#FFFBCC"><table width="100%" border="0" cellpadding="0" cellspacing="5" bgcolor="#FFFBCC">
        <tr>
          <td width="25%" height="26" bgcolor="#FFFBCC"><input onclick="checkAllLine()" class="chkbox" id="checkedAll" name="checkedAll" type="checkbox" />
            全选&nbsp;
            <input type="submit" name="Submit23" value="删除选中"    class="button" /></td>
          <td width="51%" bgcolor="#FFFBCC">&nbsp;</td>
          <td width="24%"><div align="right">
            <input type="button" name="Submit24" value="返回上一步" onclick="history.back(-1);"  class="button" />
            <input type="button" name="Submit2" value="添加信息" onclick="location.href='up_product.php?action=add'"  class="button" /> 
            <input type="button" name="Submit22" value="刷新本页" onclick="history.back(0);" class="button" />
          </div></td>
        </tr>
      </table></td>
    </tr>
  </table>
 
 
    <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC" style="margin-top:5px;"  class="cooltable">
      <tr  height="35" style="color:#fff;">
        <td width="4%" bgcolor="#0066CC" ><div align="center">选择</div></td>
        <td width="5%" bgcolor="#0066CC" ><div align="center">编号</div></td>
        <td width="37%" bgcolor="#0066CC" ><div align="center">图片</div></td>
        <td width="17%" bgcolor="#0066CC" ><div align="center">类别</div></td>
        <td width="11%" bgcolor="#0066CC" ><div align="center">操作</div></td>
      </tr> 
  <?php

$types= isset($_REQUEST['types']) ? $_REQUEST['types'] : '';
		$s='';
		if($types!='')
		{
			$s=" and types = '$types'";
		}
	$count=$ShowProductClass->GetCount($s);
	$options = array(
 	    'total_rows' => $count, //总行数
 	    'list_rows'  => '10',  //每页显示量
 	);
	
		


	 //判断当前页码
	 $page= isset($_REQUEST['p']) ? $_REQUEST['p'] : '1';
	 $page=cint($page);
	 $offset=$options['list_rows']*($page-1);
 
 	$list2=$ShowProductClass->GetPageList($s, $offset,$options['list_rows']);
	foreach($list2 as $rs)
	{ 
		 
 ?>
  <tr  height="30" >
        <td><div align="center">
          <input type="checkbox" name="idlist[]"  id="idlisst[]" class="chkbox"  value="<?=$rs['id']?>" />
        </div></td>
        <td><div align="center">
          <?=$rs['id']?>
        </div></td>
        <td><img src="../../<?=$rs['url']?>" height="30" /></td>
        <td><?=$rs['types']?></td>
        <td><div align="center">
		<a href="up_product.php?id=<?=$rs['id']?>&action=modify"><img src="../images/edit.png" style="border:0px;" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="?action=del&id=<?=$rs['id']?>" onclick="return confirm('确定要删除吗，删除后不可恢复');"> <img src="../images/del.png" style="border:0px;" /></a></div></td>
      </tr>
	  
<?php
	}
?>
  </table>
  <div> 	 
 	<?php
	/* 实例化 */
 	$page = new page($options);
 	//然后 在sql语句里面 limit $page->first_row , $page->list_rows
 	echo "<div id=page>".$page->show(1)."</div>"; //ok  打印第一样式
	?>
  </div>
</form>

</div>

</body>
</html>