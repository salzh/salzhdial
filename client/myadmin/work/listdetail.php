<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../include/page.class.php");
include("../../class/workdetail.class.php");
include("../../class/fun.class.php");
include("../check.php");
$pagetitle="作业详细内容查询";
$workid= isset($_REQUEST['workid']) ? $_REQUEST['workid'] : '0';
$his= isset($_REQUEST['his']) ? $_REQUEST['his'] : '0';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$pagetitle?></title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />

</head>

<body>
    <link type="text/css" href="../css/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
     <link type="text/css" href="../css/jquery-ui-timepicker-addon.css" rel="stylesheet" />
    <script type="text/javascript" src="../js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.8.17.custom.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="../js/jquery-ui-timepicker-zh-CN.js"></script>
    <script type="text/javascript">
    $(function () {
        $(".ui_timepicker").datetimepicker({
            //showOn: "button",
            //buttonImage: "./css/images/icon_calendar.gif",
            //buttonImageOnly: true,
            showSecond: true,
            timeFormat: 'hh:mm:ss',
            stepHour: 1,
            stepMinute: 1,
            stepSecond: 1
        })
    })
    </script>
<script language="javascript" src="../javascript/table.js"></script>
<script language="javascript">
	function add(){
		
	}
</script>

<div class="box">
<?php
$listDal=new WorkDetailDal();
$fun=new FunDal();
//echo "select count(*) as WorkCount,sum(case when IFNULL(SendTime,0)=0 then 0 else 1 end) as OverCount,sum(case when IFNULL(b.SendResult,0)==0 then 0 else 1 end) as SuccessCount,sum(IFNULL(b.Money,0)) as Money from  t_work_detail b  where WorkId=$workid";
if($his=="0")
{
	$total=$fun->GetModelList("select count(*) as WorkCount,sum(case when IFNULL(SendTime,0)=0 then 0 else 1 end) as OverCount,sum(case when IFNULL(b.SendResult,0)=0 then 0 else 1 end) as SuccessCount,sum(IFNULL(b.Money,0)) as Money from  t_work_detail b  where WorkId=$workid");
}
else
{
	$total=$fun->GetModelList("select count(*) as WorkCount,sum(case when IFNULL(SendTime,0)=0 then 0 else 1 end) as OverCount,sum(case when IFNULL(b.SendResult,0)=0 then 0 else 1 end) as SuccessCount,sum(IFNULL(b.Money,0)) as Money from  t_his_work_detail b  where WorkId=$workid");	
}
//var_dump($total);
$WorkCount=$total[0]['WorkCount'];
$OverCount=$total[0]['OverCount'];
$SuccessCount=$total[0]['SuccessCount'];
$Money=number_format($total[0]['Money'], 2, '.', '');

$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';
if ($a=="del"){
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	if($listDal->Del($id)){
	echo Msg("操作成功","list.php?action=list");
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
			echo Msg("操作成功","list.php?action=list");
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
    <td width="34%" height="45" style="border-bottom:2px #218644 solid">&nbsp;<span class="title"><?=$pagetitle?></span></td>
    <td width="66%" height="45" style="border-bottom:2px #218644 solid">&nbsp;</td>
  </tr>
</table>
<form id="form2" name="form2" method="post" action="?action=delall" onsubmit="return confirm('确定要删除吗，删除后不可恢复');">
  <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#E6DB55" style="margin-top:5px;">
    <tr>
      <td height="36" bgcolor="#FFFBCC"><table width="100%" border="0" cellpadding="0" cellspacing="5" bgcolor="#FFFBCC">
        <tr>
          <td width="25%" height="26" bgcolor="#FFFBCC">总份数：<?=$WorkCount?>&nbsp;&nbsp;完成份数：<?=$OverCount?>&nbsp;&nbsp;成功份数：<?=$SuccessCount?>&nbsp;&nbsp;费用：<?=$Money?></td>
          <td width="37%" bgcolor="#FFFBCC">&nbsp;</td>
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
        <td width="5%" bgcolor="#218644" ><div align="center">行号</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">商务电话</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">收件人</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">主题</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">发送时间</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">时长</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">发送次数</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">费用（元）</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">发送结果</div></td>
      </tr> 
  <?php

	$uname= isset($_REQUEST['uname']) ? $_REQUEST['uname'] : '';
		$s='';
		if($uname!='')
		{
			$s=" and workno like '%$uname%'";
		}
		if($sdate!='')
		{
			$s.=" and CreateDate >= '$sdate'";
		}
		if($edate!='')
		{
			$s.=" and CreateDate <= '$edate'";
		}				
		$s.=" and WorkId=".$workid; //." and a.UserId = ".$_SESSION["userid"];
	if($his==0)
	{
		$count=$listDal->GetCount($s);
	}
	else
	{
		$count=$listDal->GetHisCount($s);
	}
	$options = array(
 	    'total_rows' => $count, //总行数
 	    'list_rows'  => '10',  //每页显示量
 	);
	 //判断当前页码
	 $page= isset($_REQUEST['p']) ? $_REQUEST['p'] : '1';
	 $page=cint($page);
	 $offset=$options['list_rows']*($page-1);
 	if($his==0)
	{
 		$list2=$listDal->GetPageList($s, $offset,$options['list_rows']);
	}
	else
	{
 		$list2=$listDal->GetHisPageList($s, $offset,$options['list_rows']);		
	}
	$rowi=1;
	foreach($list2 as $rs){ 
 ?>
  <tr>
        <td align="center"><?=$rowi?></td>
        <td><?=$rs['TelNo']?></td>
        <td><?=$rs['Receiver']?></td>
        <td><a href="showdetail.php?id=<?=$rs['id']?>" target="_self"><?=$rs['Title']?></a></td>
        <td><?=$rs['SendTime']?></td>
        <td><?=$rs['TimeLength']?></td>
        <td><?=$rs['SendNum']?></td>
        <td><?=$rs['Money']?></td>
        <td><?=$rs['SendResult']?></td>
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