<?php 
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../include/pagenocss.class.php");
include("../../class/work.class.php");
include("../check.php");
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
<?
$pagetitle="作业查询";
$upid= isset($_REQUEST['upid'])? $_REQUEST['upid'] : $_SESSION["userid"];
$sdate= isset($_REQUEST['sdate'])? $_REQUEST['sdate'] : '';
$edate= isset($_REQUEST['edate'])? $_REQUEST['edate'] : '';
$uname= isset($_REQUEST['uname']) ? $_REQUEST['uname'] : '';
$SearchType= isset($_REQUEST['SearchType']) ? $_REQUEST['SearchType'] : '';
$listDal=new WorkDal();
$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';

$listDal=new WorkDal();
$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';
if ($a=="del"){
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	$delhis= isset($_REQUEST['delhis'])? $_REQUEST['delhis'] : '0';
	if($delhis=='0')
	{
		if($listDal->Del($id)){
			echo Msg("操作成功","rightsublist.php?action=list");
		}
	}
	else
	{
		if($listDal->DelHis($id)){
			echo Msg("操作成功","rightsublist.php?action=list");
		}
	}
}
else if ($a=="delall")
{
	$idlist= isset($_REQUEST['idlist']) ? $_REQUEST['idlist'] : '0';
	if($idlist!=0)
	{
		$idlist= implode(',', $idlist);
		 if($listDal->Dels($idlist))
		{
			echo Msg("操作成功","listall.php?action=list");
		}
	}
	else
	{
		echo Msg("至少选中一项目","back");
	
	}
}
?>
    <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC"  class="cooltable">
      <tr style="color:#fff;">
        <td width="5%" bgcolor="#218644" ><div align="center">行号</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">作业号</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">用户账号</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">主题</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">上传时间</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">定时发送</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">总份数</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">完成份数</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">成功份数</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">费用（元）</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">作业状态</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">语音下载</div></td>        
        <td width="10%" bgcolor="#218644" ><div align="center">操作</div></td>
      </tr> 
  <?php
		$s='';
		if($uname!='')
		{
			$s=" and a.$SearchType like '%$uname%'";
		}
		if($sdate!='')
		{
			$s.=" and a.CreateDate >= '$sdate'";
		}
		if($edate!='')
		{
			$s.=" and a.CreateDate <= '$edate'";
		}				
		$s.=" and a.UserId=".$upid."";

	$count=$listDal->GetCount($s);
	$options = array(
 	    'total_rows' => $count, //总行数
 	    'list_rows'  => '10',  //每页显示量
 	);
	 //判断当前页码
	 $page= isset($_REQUEST['p']) ? $_REQUEST['p'] : '1';
	 $page=cint($page);
	 $offset=$options['list_rows']*($page-1);
 
 	$list2=$listDal->GetPageListSum($s, $offset,$options['list_rows'],"order by a.id desc");
	$rowi=1;
	foreach($list2 as $rs){ 
 ?>
  <tr>
        <td align="center"><?=$rowi?></td>
        <td><a href="listdetail.php?workid=<?=$rs['id']?>&his=<?=$rs['his']?>" target="_self"><?=$rs['WorkNo']?></a></td>
        <td><?=$rs['username']?></td>
        <td><?=$rs['Title']?></td>
        <td><?=$rs['SendTime']?></td>
        <td><?=$rs['FixedTime']?></td>
        <td><?=$rs['WorkCount']?></td>
        <td><?=$rs['OverCount']?></td>
        <td><?=$rs['SuccessCount']?></td>
        <td><?=round($rs['Money'],2)?></td>
        <td><?=$rs['WorkState']?></td>
        <td><a href="../../<?=$rs['VoiceFile']?>" target="_blank">下载</a></td>
        <td width="100px"><div align="center">
        <?
			if($rs['StateId']!=4)
			{
		?>
			<a href="set.php?id=<?=$rs['id']?>&action=stop" onclick="return confirm('确定暂停发送吗？');">暂停</a>&nbsp;&nbsp;
       <?
			}
			else
			{
		?>
        	<a href="set.php?id=<?=$rs['id']?>&action=resume" onclick="return confirm('确定恢复发送吗？');">恢复</a>&nbsp;&nbsp;
		<?				
			}
		?>
          <a href="excelout.php?workid=<?=$rs['id']?>&his=<?=$rs['his']?>" onclick="return confirm('确定导出清单吗？');">导出</a>&nbsp;&nbsp;
        <a href="?action=del&id=<?=$rs['id']?>&delhis=<?=$rs['his']?>" onclick="return confirm('确定要删除吗，删除后不可恢复');">删除</a></div></td>
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