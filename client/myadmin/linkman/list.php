<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../include/page.class.php");
include("../../class/linkman.class.php");
include("../../class/addressgroup.class.php");
include("../check.php");
$pagetitle="联系人管理";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$pagetitle?></title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />

</head>

<body>
<script language="javascript" src="../javascript/jquery-1.4.1.min.js"></script>
<script language="javascript" src="../javascript/table.js"></script>
<script language="javascript">
	function add(){
		
	}
</script>

<div class="box">
<?php
$listDal=new LinkmanDal();
$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';
$GroupId= isset($_REQUEST['GroupId']) ? $_REQUEST['GroupId'] : 0;
if ($a=="del"){
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	if($listDal->Del($id,$GroupId)){
		$mainDal=new AddressGroupDal();
		$mainDal->SetLinkmanNum($GroupId);
	echo Msg("操作成功","list.php?action=list&GroupId=".$GroupId);
	}
}
else if ($a=="delall")
{
	$idlist= isset($_REQUEST['idlist']) ? $_REQUEST['idlist'] : '0';
	if($idlist!=0)
	{
		$idlist= implode(',', $idlist);
		 if($listDal->Dels($idlist,$GroupId))
		{
			$mainDal=new AddressGroupDal();
			$mainDal->SetLinkmanNum($GroupId);
			echo Msg("操作成功","list.php?action=list&GroupId=".$GroupId);
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
    <td width="72%" height="45" style="border-bottom:2px #218644 solid">&nbsp;<span class="title"><?=$pagetitle?></span></td>
    <td width="28%" height="45" style="border-bottom:2px #218644 solid"><form id="form1" name="form1" method="post" action="?">
      <div align="right">
        <input name="uname" type="text" class="inputSearch" id="uname" />
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
            全选&nbsp; <input type="submit" name="Submit23" value="删除选中"    class="button" /></td>
          <td width="37%" bgcolor="#FFFBCC">&nbsp;</td>
          <td width="38%"><div align="right">
          	<input type="hidden" id="GroupId" name="GroupId" value="<?=$GroupId?>" />
            <input type="button" name="Submit222" value="添加" onclick="location='set.php?action=page&GroupId=<?=$GroupId?>'"  class="button" />
            <input type="button" name="Submit2" value="返回上一步" onclick="history.back(-1);"  class="button" />
            <input type="button" name="Submit22" value="刷新本页" onclick="document.location.reload();" class="button" />
          </div></td>
        </tr>
      </table></td>
    </tr>
  </table>
    <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC" style="margin-top:5px;"  class="cooltable">
      <tr style="color:#fff;">
        <td width="5%" bgcolor="#218644" ><div align="center">选择</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">行号</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">联系人</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">公司名</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">商务传真</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">商务电话</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">移动电话</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">商务邮箱</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">说明</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">操作</div></td>
      </tr> 
  <?php

	$uname= isset($_REQUEST['uname']) ? $_REQUEST['uname'] : '';
	$SearchType= isset($_REQUEST['SearchType'])? $_REQUEST['SearchType'] : '';
	$SearchValue= isset($_REQUEST['SearchValue'])? $_REQUEST['SearchValue'] : '';
	$IdxType= isset($_REQUEST['IdxType'])? $_REQUEST['IdxType'] : '';
	$IdxOrder= isset($_REQUEST['IdxOrder'])? $_REQUEST['IdxOrder'] : '';
	$s='';
	if($SearchValue!=''&&$SearchType!='')
	{		
		$s=" and $SearchType like '%$SearchValue%'";
	}
	$idxField="";
	if($IdxType!='')
	{
		$idxField=" order by $IdxType ".$IdxOrder;
	}	
	if($GroupId!=0)
	{
		$s.=" and GroupId= ".$GroupId;	
	}

	if($uname!='')
	{
		$s.=" and Linkman like '%$uname%'";
	}
	$s.=" and UserId = ".$_SESSION["userid"];
	$s.=$idxField;

	$count=$listDal->GetCount($s);
	$options = array(
 	    'total_rows' => $count, //总行数
 	    'list_rows'  => '10',  //每页显示量
 	);
	 //判断当前页码
	 $page= isset($_REQUEST['p']) ? $_REQUEST['p'] : '1';
	 $page=cint($page);
	 $offset=$options['list_rows']*($page-1);
 
 	$list2=$listDal->GetPageList($s, $offset,$options['list_rows']);
	$rowi=1;
	foreach($list2 as $rs){ 
 ?>
  <tr>
        <td align="center"><input type="checkbox" name="idlist[]"  id="idlisst[]" class="chkbox"  value="<?=$rs['id']?>" /></td>
        <td align="center"><?=$rowi?></td>
        <td><?=$rs['Linkman']?></td>
        <td><?=$rs['Company']?></td>
        <td><?=$rs['Fax']?></td>
        <td><?=$rs['Tel']?></td>
        <td><?=$rs['Mobi']?></td>
        <td><?=$rs['Email']?></td>
        <td><?=$rs['Description']?></td>
        <td><div align="center">
		<a href="set.php?id=<?=$rs['id']?>&action=page"><img src="../images/edit.png" style="border:0px;" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="?action=del&id=<?=$rs['id']?>&GroupId=<?=$GroupId?>" onclick="return confirm('确定要删除吗，删除后不可恢复');"> <img src="../images/del.png" style="border:0px;" /></a></div></td>
      </tr>
	  
<?php 
	$rowi++;
}?>
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