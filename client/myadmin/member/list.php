<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../include/page.class.php");
include("../../class/user.php");
include("../check.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />

</head>

<body>
<script language="javascript" src="../javascript/jquery-1.4.1.min.js"></script>
<script language="javascript" src="../javascript/table.js"></script>
<script language="javascript">
	function add(){
		
	}
	function output()
	{
		window.open ('excel.php','newwindow','height=100,width=400,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');	
	}	
</script>

<div class="box">
<?php
$ud=new UserDal();
$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';
if ($a=="del"){
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	if($ud->Del($id)){
	echo Msg("操作成功","list.php?action=list");
	}
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="72%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">用户管理</span></td>
    <td width="28%" height="45" style="border-bottom:2px #0066CC solid"><form id="form1" name="form1" method="GET" action="?">
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
            全选&nbsp; </td>
          <td width="37%" bgcolor="#FFFBCC"><a href="excel.php">导出数据</a></td>
          <td width="38%"><div align="right">
            <input type="button" name="Submit2" value="返回上一步" onclick="history.back(-1);"  class="button" />
            <input type="button" name="Submit22" value="刷新本页" onclick="history.back(0);" class="button" />
          </div></td>
        </tr>
      </table></td>
    </tr>
  </table>
    <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC" style="margin-top:5px;"  class="cooltable">
      <tr style="color:#fff;">
        <td width="3%" bgcolor="#0066CC" ><div align="center">选择</div></td>
        <td width="4%" bgcolor="#0066CC" ><div align="center">编号</div></td>
        <td width="28%" bgcolor="#0066CC" ><div align="center">Email</div></td>
        <td width="12%" bgcolor="#0066CC" ><div align="center">Name</div></td>
        <td width="8%" bgcolor="#0066CC" ><div align="center">Position</div></td>
        <td width="13%" bgcolor="#0066CC" ><div align="center">Group</div></td>
		   <td width="13%" bgcolor="#0066CC" ><div align="center">QOC_Role</div></td>
		      <td width="13%" bgcolor="#0066CC" ><div align="center">Region</div></td>
        <td width="14%" bgcolor="#0066CC" ><div align="center">Channel</div></td>
        <td width="13%" bgcolor="#0066CC" ><div align="center">登陆</div></td>
        <td width="13%" bgcolor="#0066CC" ><div align="center">查看</div></td>
        <td width="13%" bgcolor="#0066CC" ><div align="center">留言</div></td>
        <td width="13%" bgcolor="#0066CC" ><div align="center">回复</div></td>
        <td width="13%" bgcolor="#0066CC" ><div align="center">操作</div></td>
      </tr> 
  <?php

$uname= isset($_REQUEST['uname']) ? $_REQUEST['uname'] : '';
		$s='';
		if($uname!='')
		{
			$s=" and Email like '%$uname%' or  Name like '%$uname%'  or  Region like '%$uname%'  or  Position like '%$uname%'  or  Channel like '%$uname%'";
		}
	$count=$ud->GetCount($s);
	$options = array(
 	    'total_rows' => $count, //总行数
 	    'list_rows'  => '100',  //每页显示量
 	);
	 //判断当前页码
	 $page= isset($_REQUEST['p']) ? $_REQUEST['p'] : '1';
	 $page=cint($page);
	 $offset=$options['list_rows']*($page-1);
 
 	$list2=$ud->GetPageList($s, $offset,$options['list_rows']);
	foreach($list2 as $rs){ 
 ?>
  <tr>
        <td align="center"><input type="checkbox" name="idlist[]"  id="idlisst[]" class="chkbox"  value="<?=$rs['UserId']?>" /></td>
        <td align="center"><?=$rs['UserId']?></td>
        <td><?=$rs['Email']?></td>
        <td><?=$rs['Name']?></td>
        <td><?=$rs['Position']?></td>
        <td align="center"> <?=$rs['Group']?></td>
		  <td align="center"> <?=$rs['QOC_Role']?></td>
        <td align="center"><?=$rs['Region']?></td>
		  <td align="center"><?=$rs['Channel']?></td>
		  <td><?=$rs['loginnum']?></td>
		  <td><?=$rs['hits']?></td>
		  <td><?=$rs['msgnum']?></td>
		  <td><?=$rs['remsgnum']?></td>
        <td><div align="center">
		<a href="set.php?id=<?=$rs['UserId']?>&action=page"><img src="../images/edit.png" style="border:0px;" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="?action=del&id=<?=$rs['UserId']?>" onclick="return confirm('确定要删除吗，删除后不可恢复');"> <img src="../images/del.png" style="border:0px;" /></a></div></td>
      </tr>
	  
<?php }?>
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