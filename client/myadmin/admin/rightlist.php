<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../include/pagenocss.class.php");
include("../../class/admin.class.php");
include("../check.php");

$uname= isset($_REQUEST['uname']) ? $_REQUEST['uname'] : '';
$upid= isset($_REQUEST['upid']) ? $_REQUEST['upid'] : $_SESSION["userid"];

$ad=new AdminDal();
$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';
if ($a=="del"){
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	if($ad->Del($id)){
	echo Msg("操作成功","rightlist.php?action=list&upid=".$upid);
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理员管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../tree/jquery-1.4.4.min.js"></script>

<script language="javascript" src="../javascript/table.js"></script>
</head>
<body>
    <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC"  class="cooltable">
      <tr style="color:#fff;">
        <td width="5%" bgcolor="#218644" ><div align="center">选择</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">行号</div></td>
        <td width="20%" bgcolor="#218644" ><div align="center">员工账号</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">员工名称</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">移动电话</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">商务电话</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">用户状态</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">操作</div></td>
      </tr> 
  <?php

	$s='';
	if($uname!='')
	{
		$s=" and username like '%$uname%'";
	}
	$s.=" and StatusId<>-1 and upId = ".$upid;
	$count=$ad->GetCount($s);
	$options = array(
 	    'total_rows' => $count, //总行数
 	    'list_rows'  => '12',  //每页显示量
 	);
	 //判断当前页码
	 $page= isset($_REQUEST['p']) ? $_REQUEST['p'] : '1';
	 $page=cint($page);
	 $offset=$options['list_rows']*($page-1);
 
 	$list2=$ad->GetPageList($s, $offset,$options['list_rows']);
	$rowi=1;
	foreach($list2 as $rs){ 
 ?>
  <tr>
        <td align="center"><input type="checkbox" name="idlist[]"  id="idlisst[]" class="chkbox"  value="<?=$rs['id']?>" /></td>
        <td align="center"><?=$rowi?></td>
        <td><?=$rs['username']?></td>
        <td><?=$rs['realname']?></td>
        <td><?=$rs['mobi']?></td>
        <td><?=$rs['tel']?></td>        
        <td><?=$rs['StatusId']==1?"正常":"锁定"?></td>
        <td><div align="center">
		<a href="set.php?id=<?=$rs['id']?>&action=page"><img src="../images/edit.png" style="border:0px;" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="?action=del&id=<?=$rs['id']?>" onclick="return confirm('确定要删除吗，删除后不可恢复');"> <img src="../images/del.png" style="border:0px;" /></a></div></td>
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
</body>
</html>