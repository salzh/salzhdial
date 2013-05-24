<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../include/page.class.php");
include("../../class/work.class.php");
include("../check.php");
$pagetitle="作业查询";
$sdate= isset($_REQUEST['sdate'])? $_REQUEST['sdate'] : '';
$edate= isset($_REQUEST['edate'])? $_REQUEST['edate'] : '';

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
$listDal=new WorkDal();
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
    <td width="66%" height="45" style="border-bottom:2px #218644 solid"><form id="form1" name="form1" method="post" action="?">
      <div align="right">
        开始时间：
          <input type="text" name="sdate" class="ui_timepicker" value="<?=$sdate?>" />
          &nbsp;截止时间：
          <input type="text" name="edate" class="ui_timepicker" value="<?=$edate?>" />
          &nbsp;主题：
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
          <td width="25%" height="26" bgcolor="#FFFBCC">&nbsp;</td>
          <td width="37%" bgcolor="#FFFBCC">&nbsp;</td>
          <td width="38%"><div align="right">
            <input type="button" name="Submit222" value="添加" onclick="location='set.php?action=page&GroupId=<?=$GroupId?>'"  class="button" />
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
        <td width="10%" bgcolor="#218644" ><div align="center">用户账号</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">作业号</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">类型</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">主题</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">发送时间</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">总份数</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">完成份数</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">成功份数</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">费用（元）</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">作业状态</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">内容</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">操作</div></td>
      </tr> 
  <?php

	$uname= isset($_REQUEST['uname']) ? $_REQUEST['uname'] : '';
		$s='';

		$SearchType= isset($_REQUEST['SearchType'])? $_REQUEST['SearchType'] : '';
		$SearchValue= isset($_REQUEST['SearchValue'])? $_REQUEST['SearchValue'] : '';
		$IdxType= isset($_REQUEST['IdxType'])? $_REQUEST['IdxType'] : '';
		if($SearchValue!='')
		{		
			switch ($SearchType)
			{
			case 1:
				$s=" and a.WorkNo like '%$SearchValue%'";
			  break;  
			case 2:
				$s=" and a.Title like '%$SearchValue%'";
			  break;
			case 3:
				//$s=" and title like '%$SearchType%'";				
			  break;
			default:
			}
		}
		$idxField="";
		if($IdxType!='')
		{
			$idxField=" order by a.$IdxType";
		}
		if($uname!='')
		{
			$s=" and a.Title like '%$uname%'";
		}
		if($sdate!='')
		{
			$s.=" and a.CreateDate >= '$sdate'";
		}
		if($edate!='')
		{
			$s.=" and a.CreateDate <= '$edate'";
		}				
		$s.=" and a.UserId = ".$_SESSION["userid"];
		

	$count=$listDal->GetCount($s);
	$options = array(
 	    'total_rows' => $count, //总行数
 	    'list_rows'  => '10',  //每页显示量
 	);
	 //判断当前页码
	 $page= isset($_REQUEST['p']) ? $_REQUEST['p'] : '1';
	 $page=cint($page);
	 $offset=$options['list_rows']*($page-1);
 
 	$list2=$listDal->GetPageListSum($s, $offset,$options['list_rows'],$idxField);
	$rowi=1;
	foreach($list2 as $rs){ 
 ?>
  <tr>
        <td align="center"><?=$rowi?></td>
        <td><?=$rs['UserId']?></td>
        <td><a href="listdetail.php?workid=<?=$rs['id']?>&his=<?=$rs['his']?>" target="_self"><?=$rs['WorkNo']?></a></td>
        <td><?=$rs['WorkType']?></td>
        <td><?=$rs['Title']?></td>
        <td><?=$rs['SendTime']?></td>
        <td><?=$rs['WorkCount']?></td>
        <td><?=$rs['OverCount']?></td>
        <td><?=$rs['SuccessCount']?></td>
        <td><?=$rs['Money']?></td>
        <td><?=$rs['WorkState']?></td>
        <td><a href="../../<?=$rs['VoiceFile']?>" target="_blank">下载</a></td>
        <td><div align="center">
		<a href="set.php?id=<?=$rs['id']?>&action=page" onclick="return confirm('确定重新发送吗？');"><img src="../images/loginout.png" style="border:0px;" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="excelout.php?workid=<?=$rs['id']?>" onclick="return confirm('确定导出清单吗？');"><img src="../images/app.png" style="border:0px;" /></a>&nbsp;&nbsp;&nbsp;&nbsp;        
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