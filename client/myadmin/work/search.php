<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/fun.class.php");
$pagetitle="历史记录查询";
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$pagetitle?></title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/fun.js"></script>
<style>
	form td{padding:3px;}
	td{font-size:12px;}
	</style>
<script type="text/javascript" src="../javascript/jquery-1.4.1.min.js" charset="utf-8"></script>

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
<?

$fun=new FunDal();

?>
<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="75%" height="45" style="border-bottom:2px #218644 solid">&nbsp;<span class="title"><?=$pagetitle?></span></td>
      <td width="25%" height="45" style="border-bottom:2px #218644 solid">&nbsp;</td>
    </tr>
  </table>
  <form id="form1" name="form1" method="post" action="listhis.php" onsubmit="return check()">
    <table width="100%" height="70" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF"><table width="500" border="1" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2" bgcolor="#218644" style="color:#FFF">作业查询</td>
            </tr>
          <tr>
            <td width="300" align="right">开始时间：</td>
            <td width="300" align="left"><input type="text" name="sdate" class="ui_timepicker" value="<?=$sdate?>" /></td>
          </tr>
          <tr>
            <td align="right">截止时间：</td>
            <td align="left"><input type="text" name="edate" class="ui_timepicker" value="<?=$edate?>" /></td>
          </tr>
          <tr>
            <td align="right"><?=$fun->GetComboList("SearchType",14,2)?></td>
            <td align="left"><input type="text" name="SearchValue" id="SearchValue" value="" maxlength="50" /></td>
          </tr>
          <tr>
            <td align="right">排序规则</td>
            <td align="left"><?=$fun->GetComboList("IdxType",15,4)?></td>
          </tr>
        </table></td>
      </tr>      
      <tr>
        <td height="39" align="center" bgcolor="#FFFFFF"><div style="width:100px;margin:auto;text-align:center"><input type="submit" name="Submit" id="sub_btn" class="bt" value="查询" /></div></td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>