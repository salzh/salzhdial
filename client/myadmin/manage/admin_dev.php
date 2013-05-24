<?php
include("../check.php");

include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../include/page.class.php");
include("../../class/dev.class.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>开发者联盟管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
</head>
<body>
<script language="javascript" src="../javascript/jquery-1.4.1.min.js"></script>
<script language="javascript" src="../javascript/table.js"></script>
<script language="javascript"  src="javascript/fun.js" ></script>

 


 

<div class="box">
<?php
$user=new UserDevDal();
$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';
if ($a=="del")
{
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	if($user->Del($id))
	{
	echo Msg("操作成功","admin_dev.php?action=list");
	}
}
if ($a=="delall")
{
	$idlist= isset($_REQUEST['idlist']) ? $_REQUEST['idlist'] : '0';
	if($idlist!=0)
	{
		$idlist= implode(',', $idlist);
		 if($user->Del($idlist))
		{
			echo Msg("操作成功","admin_dev.php?action=list");
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
    <td width="72%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">申请开发者联盟用户</span></td>
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
          <td width="74%" height="26" bgcolor="#FFFBCC">*申请删除后，用户开发者联盟信息将会被取消</td>
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
        <td width="5%" bgcolor="#0066CC" ><div align="center">编号</div></td>
        <td width="10%" bgcolor="#0066CC" ><div align="center">用户</div></td>
        <td width="15%" bgcolor="#0066CC" ><div align="center">姓名/团队/公司</div></td>
        <td width="11%" bgcolor="#0066CC" ><div align="center">类型</div></td>
        <td width="18%" bgcolor="#0066CC" ><div align="center">特长</div></td>
        <td width="12%" bgcolor="#0066CC" ><div align="center">电话</div></td>
        <td width="9%" bgcolor="#0066CC" ><div align="center">状态</div></td>
        <td width="13%" bgcolor="#0066CC" ><div align="center">申请时间</div></td>
        <td width="7%" bgcolor="#0066CC" ><div align="center">操作</div></td>
      </tr>
      <?php

		$keyword= isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
		$s='';
		if($keyword!='')
		{
			$s=" and  username like '%$keyword%'";
		}
		$count=$user->GetCount($s);
		$options = array(
			'total_rows' => $count, //总行数
			'list_rows'  => '20',  //每页显示量
		);
	 
	 //判断当前页码
	 $page= isset($_REQUEST['p']) ? $_REQUEST['p'] : '1';
	 $page=cint($page);
	 $offset=$options['list_rows']*($page-1);
 
 	$list2=$user->GetPageList($s, $offset,$options['list_rows']);
	foreach($list2 as $rs)
	{ 
		 
 ?>
      <tr  height="30" >
        <td><div align="center">
            <?=$rs['id']?>
        </div></td>
        <td><?=$rs['UserName']?></td>
        <td><?=$rs['realname']?> <a href="dev_show.php?id=<?=$rs[0]?>" onclick="show('<?=$rs['realinfo']?>')"> 查看详情</a> </td>
        <td><? if ($rs['types']==1) { echo "个人" ;}?>
            <? if ($rs['types']==2) { echo "团队" ;}?>
          <? if ($rs['types']==3) { echo "公司" ;}?></td>
        <td><?=$rs['strong']?></td>
        <td><?=$rs['tel']?>        </td>
        <td align="center"><?=$rs['ispass']=='0'?'<span style="color:red">审核中</span>':'<span style="color:green">通过</span>'?></td>
        <td><div align="center">
            <?=$rs['times']?>
        </div></td>
        <td><div align="center"> <a href="?action=del&id=<?=$rs['id']?>" onclick="return confirm('确定要删除吗，删除后不可恢复');"> <img src="../images/del.png" style="border:0px;" /></a></div></td>
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