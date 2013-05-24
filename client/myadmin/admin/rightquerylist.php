<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../include/pagenocss.class.php");
include("../../class/admin.class.php");
include("../check.php");
include("../../class/fun.class.php");
$pagetitle="员工查询";
$sdate= isset($_REQUEST['sdate'])? $_REQUEST['sdate'] : '';
$edate= isset($_REQUEST['edate'])? $_REQUEST['edate'] : '';
$uname= isset($_REQUEST['uname'])? $_REQUEST['uname'] : '';
$idx= isset($_REQUEST['idx'])? $_REQUEST['idx'] : '1';
$fun=new FunDal();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$pagetitle?></title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />

</head>

<body>

<div class="box">
<?php
$ad=new AdminDal();
$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';
if ($a=="del"){
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	if($ad->Del($id)){
	echo Msg("操作成功","rightquerylist.php?action=list");
	}
}
?>
    <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC" style="margin-top:5px;"  class="cooltable">
      <tr style="color:#fff;">
        <td width="5%" bgcolor="#218644" ><div align="center">行号</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">员工账号</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">员工名称</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">移动电话</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">商务电话</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">父账户</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">用户状态</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">余额</div></td>
        <td width="28" bgcolor="#218644" ><div align="center">操作</div></td>
      </tr> 
  <?php

$uname= isset($_REQUEST['uname']) ? $_REQUEST['uname'] : '';
$upid= isset($_REQUEST['upid']) ? $_REQUEST['upid'] :  $_SESSION["userid"];

		$s='';
		if($uname!='')
		{
			$s=" and username like '%$uname%'";
		}
		if($sdate!='')
		{
			$s.=" and createTime >= '$sdate'";
		}
		if($edate!='')
		{
			$s.=" and createTime <= '$edate'";
		}	
		if($_SESSION["userid"]==1)
		{
			$s.=" and id<>".$upid." and FIND_IN_SET( id, getChildLst(".$upid.") )";	
		}
		else
		{
			$s.=" and id<>".$upid." and StatusId<>-1 and  FIND_IN_SET( id, getChildLst(".$upid.") )";
		}

	$count=$ad->GetCount($s);
	$options = array(
 	    'total_rows' => $count, //总行数
 	    'list_rows'  => '10',  //每页显示量
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
        <td align="center"><?=$rowi?></td>
        <td><?=$rs['username']?></td>
        <td><?=$rs['realname']?></td>
        <td><?=$rs['mobi']?></td>
        <td><?=$rs['tel']?></td>
        <td><?=$rs['father']?></td>        
        <td><?=str_replace('1','正常',str_replace('0','锁定',str_replace('-1','删除',$rs['StatusId'])))?></td>
        <td><?=round($rs['voiceMoney'],2)?></td>        
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
</form>

</div>

</body>
</html>