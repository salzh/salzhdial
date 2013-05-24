<?php
include("../check.php");

include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../include/page.class.php");
include("../../class/product.class.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>产品管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
</head>

<body>
<script language="javascript" src="../javascript/jquery-1.4.1.min.js"></script>
<script language="javascript" src="../javascript/table.js"></script>

 

<div class="box">
<? 
 
 function prolist($a)
{
	$qxpid="";
	$bbb = explode(",", $a);
	for($i=0;$i<count($bbb);$i++)
	{
		if($i==0)
		{
			if($bbb[$i]=="" || $bbb[$i]==NULL)
			{
			}
			else
			{
				$qxpid.=cint($bbb[$i]);
			}
			
		}
		else
		{
			if($bbb[$i]=="" || $bbb[$i]==NULL)
			{
			}
			else
			{
				$qxpid.=",".cint($bbb[$i]);
			}
		}
	}
	return $qxpid;
 
}


$product=new Product();
$t="";
$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';
if ($a=="del")
{
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
 	 $t=$product->GetTitle($id);

	if($product->DelProduct($id))
	{
	$product->AddLog($_COOKIE['username'],$id,$t,"删除 ".$t."");
	echo Msg("操作成功","admin_product.php?action=list");
	}
}
if ($a=="delall")
{
	$idlist= isset($_REQUEST['idlist']) ? $_REQUEST['idlist'] : '0';
	if($idlist!=0)
	{
 
		$idlist= implode(',', $idlist);
		  $t=$product->GetTitle($idlist);
		 if($product->DelProduct($idlist))
		{
				$product->AddLog($_COOKIE['username'],$idlist,$t,"删除 ".$t."");
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
    <td width="72%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title"><strong>产品管理</strong></span></td>
    <td width="28%" height="45" style="border-bottom:2px #0066CC solid"><form id="form1" name="form1" method="post" action="?">
      <div align="right">
        <input name="keyword" type="text" class="inputSearch" id="keyword" />
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
          <td width="57%" bgcolor="#FFFBCC">&nbsp;</td>
          <td width="18%"><div align="right">
            <input type="button" name="Submit2" value="返回上一步" onclick="history.back(-1);"  class="button" />
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
        <td width="8%" bgcolor="#0066CC" ><div align="center">图片</div></td>
        <td width="41%" bgcolor="#0066CC" ><div align="center">标题</div></td>
        <td width="16%" bgcolor="#0066CC" ><div align="center">类别</div></td>
        <td width="6%" bgcolor="#0066CC" ><div align="center">状态</div></td>
        <td width="10%" bgcolor="#0066CC" ><div align="center">发布时间</div></td>
        <td width="10%" bgcolor="#0066CC" ><div align="center">操作</div></td>
      </tr> 
  <?php

$keyword= isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
		$s='';
		if($keyword!='')
		{
			$s=" and title like '%$keyword%'";
		}
		
		if($_COOKIE['level']!='1')
		{
			$s=" and a.bid in(".prolist($_COOKIE['prolist']).")";
		}
	$count=$product->GetCount($s);
	$options = array(
 	    'total_rows' => $count, //总行数
 	    'list_rows'  => '10',  //每页显示量
 	);
	
		


	 //判断当前页码
	 $page= isset($_REQUEST['p']) ? $_REQUEST['p'] : '1';
	 $page=cint($page);
	 $offset=$options['list_rows']*($page-1);
 
 	$list2=$product->GetPageList($s, $offset,$options['list_rows']);
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
        <td><div align="center"><img src="../../<?=$rs["pic"]?>" width="32" height="32" /></div></td>
        <td><?=$rs['title']?></td>
        <td><?=$rs['bname']?>-<?=$rs['sname']?></td>
        <td align="center"> <?=$rs['shows']=='yes'?'启用':'锁定'?></td>
        <td><div align="center">
          <?=$rs['times']?>
        </div></td>
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