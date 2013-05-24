<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../include/page.class.php");
include("../../class/voicetemplate.class.php");
include("../../class/fun.class.php");
include("../check.php");
$pagetitle="语音模板审核";
$sdate= isset($_REQUEST['sdate'])? $_REQUEST['sdate'] : '';
$AuditingType= isset($_REQUEST['AuditingType'])? $_REQUEST['AuditingType'] : '0';
$edate= isset($_REQUEST['edate'])? $_REQUEST['edate'] : '';
$uname= isset($_REQUEST['uname']) ? $_REQUEST['uname'] : '';
$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
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
$listDal=new VoiceTemplateDal();
$thisModel= new VoiceTemplateInfo();
$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';
if($a=="pass"){
	$thisModel->id=$id;
	$thisModel->Auditing=1;
	$rtn=$fun->UpdateModel("t_voice_template",$thisModel);
	if ($rtn==0){
	//echo Msg("操作失败.","back");
	}
	else{
		echo Msg("操作成功","auditinglist.php?action=list&AuditingType=$AuditingType");
	} 
	return;	
	echo "stop";
	exit(0);
}
else if($a=="reject"){
	$thisModel->id=$id;
	$thisModel->Auditing=2;
	$rtn=$fun->UpdateModel("t_voice_template",$thisModel);
	if ($rtn==0){
	//echo Msg("操作失败.","back");
	}
	else{
		echo Msg("操作成功","auditinglist.php?action=list&AuditingType=$AuditingType");
	} 
	return;	
	echo "stop";
	exit(0);
}
if($a=="passall"){
	$idlist= isset($_REQUEST['idlist']) ? $_REQUEST['idlist'] : '0';
	if($idlist!=0)
	{
		$idlist= implode(',', $idlist);
		 if($listDal->AllPass($idlist))
		{
			echo Msg("操作成功","auditinglist.php?action=list&AuditingType=$AuditingType");
		}
	}
	else
	{
		echo Msg("至少选中一项目","back");
	
	}
	$thisModel->id=$id;
	$thisModel->WorkState=0;
	$rtn=$fun->UpdateModel("t_work",$thisModel);
	if ($rtn==0){
	//echo Msg("操作失败.","back");
	}
	else{
		echo Msg("操作成功","auditinglist.php?action=list&AuditingType=$AuditingType");
	} 
	return;	
	echo "stop";
	exit(0);
}

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
    <td width="2%" height="45" style="border-bottom:2px #218644 solid">&nbsp;</td>
    <td width="98%" height="45" style="border-bottom:2px #218644 solid"><form id="form1" name="form1" method="get" action="?">
      <div align="right"> 
      <input type='radio' name='AuditingType' value='0' id='AuditingType' <?=$AuditingType==0?'checked':''?>/><label for='AuditingType'>需审核</label>
      <input type='radio' name='AuditingType' value='1' id='AuditingType' <?=$AuditingType==1?'checked':''?>/><label for='AuditingType'>审核通过</label>
      <input type='radio' name='AuditingType' value='2' id='AuditingType' <?=$AuditingType==2?'checked':''?>/><label for='AuditingType'>审核拒绝</label>
        开始时间：
          <input type="text" name="sdate" class="ui_timepicker" value="<?=$sdate?>" />
          &nbsp;截止时间：
          <input type="text" name="edate" class="ui_timepicker" value="<?=$edate?>" />
          &nbsp;
          <input name="uname" type="text" class="inputSearch" id="uname" value="<?=$uname?>" style="height:25px" />
        <input type="submit" name="Submit" value="搜索" class="button" />
        </div>
    </form></td>
  </tr>
</table>
<form id="form2" name="form2" method="post" action="?action=passall" onsubmit="return confirm('确定要批量审核通过吗');">
  <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#E6DB55" style="margin-top:5px;">
    <tr>
      <td height="36" bgcolor="#FFFBCC"><table width="100%" border="0" cellpadding="0" cellspacing="5" bgcolor="#FFFBCC">
        <tr>
          <td width="25%" height="26" bgcolor="#FFFBCC"><input onclick="checkAllLine()" class="chkbox" id="checkedAll" name="checkedAll" type="checkbox" />
            全选&nbsp;<input type="submit" name="Submit23" value="批量通过"    class="button" /></td>
          <td width="37%" bgcolor="#FFFBCC">&nbsp;</td>
          <td width="38%"><div align="right">
            <input type="button" name="Submit222" value="添加" onclick="location='set.php?action=page&GroupId=<?=$GroupId?>'"  class="button" />
            <input type="button" name="Submit2" value="返回上一步" onclick="history.back(-1);"  class="button" />
            <input type="button" name="Submit22" value="刷新本页"  onclick="document.location.reload();" class="button" />
          </div></td>
        </tr>
      </table></td>
    </tr>
  </table>
    <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC" style="margin-top:5px;"  class="cooltable">
      <tr style="color:#fff;">
        <td width="5%" bgcolor="#218644" ><div align="center">选择</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">行号</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">模板名</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">发送语音</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">重听按键</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">投诉按键</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">回访按键</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">回访语音</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">创建时间</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">状态</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">操作</div></td>
      </tr> 
  <?php

	
		$s="and Auditing=".$AuditingType;
		if($uname!='')
		{
			$s=" and TemplateName like '%$uname%'";
		}
		if($sdate!='')
		{
			$s.=" and a.CreateDate >= '$sdate'";
		}
		if($edate!='')
		{
			$s.=" and a.CreateDate <= '$edate'";
		}				
		$s.=" and FIND_IN_SET( a.UserId, getChildLst(".$_SESSION["userid"]."))";
	$count=$listDal->GetCount($s);
	$options = array(
 	    'total_rows' => $count, //总行数
 	    'list_rows'  => '10',  //每页显示量
 	);
	 //判断当前页码
	 $page= isset($_REQUEST['p']) ? $_REQUEST['p'] : '1';
	 $page=cint($page);
	 $offset=$options['list_rows']*($page-1);
 
 	$list2=$listDal->GetPageList($s, $offset,$options['list_rows'],"order by a.id desc");
	$rowi=1;
	foreach($list2 as $rs){ 
 ?>
  <tr>
        <td align="center"><input type="checkbox" name="idlist[]"  id="idlisst[]" class="chkbox"  value="<?=$rs['id']?>" /></td>
        <td align="center"><?=$rowi?></td>
        <td><?=$rs['TemplateName']?></td>
        <td><a href="<?=$rs['VoiceFile']==''?'#':'../../'.$rs['VoiceFile']?>" >语音文件</a></td>
        <td><?=$rs['RepeatNum']?></td>
        <td><?=$rs['ComplainNum']?></td>
        <td><?=$rs['ReturnNum']?></td>
        <td><a href="<?=$rs['ReturnVoiceFile']==''?'#':'../../'.$rs['ReturnVoiceFile']?>" >语音文件</a></td>
        <td><?=$rs['CreateDate']?></td>
        <td><?=$rs['AuditingName']?></td>
        <td>
<a href="auditinglist.php?id=<?=$rs['id']?>&action=pass&AuditingType=<?=$AuditingType?>" onclick="return confirm('确定审核通过吗？');">通过</a>&nbsp;&nbsp;
          <a href="auditinglist.php?id=<?=$rs['id']?>&action=reject&AuditingType=<?=$AuditingType?>" onclick="return confirm('确定审核拒绝吗？');">拒绝</a>        
        </td>

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
<script>
function checkAllLine() { 
 if ($("#checkedAll").attr("checked") == "checked") { // 全选 
  $('.cooltable tbody tr').each(
   function() {
    $(this).addClass('selected');
    $(this).find('input[type="checkbox"]').attr('checked','checked');
   }
  );
 } else { // 取消全选 
  $('.cooltable tbody tr').each(
   function() {
    $(this).removeClass('selected');
    $(this).find('input[type="checkbox"]').removeAttr('checked');
   }
 );
}
}
</script>
</body>
</html>