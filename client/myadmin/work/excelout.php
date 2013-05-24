<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/fun.class.php");
$pagetitle="作业导出";
	  	$workid= isset($_REQUEST['workid']) ? $_REQUEST['workid'] : '0';
		$his= isset($_REQUEST['his']) ? $_REQUEST['his'] : '0';
		$sdate= isset($_REQUEST['sdate'])? $_REQUEST['sdate'] : '';
		$edate= isset($_REQUEST['edate'])? $_REQUEST['edate'] : '';
		$SearchType= isset($_REQUEST['SearchType'])? $_REQUEST['SearchType'] : '';
		$SearchValue= isset($_REQUEST['SearchValue'])? $_REQUEST['SearchValue'] : '';
		$IdxType= isset($_REQUEST['IdxType'])? $_REQUEST['IdxType'] : '';		

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
$optionList=array("商务电话","收件人","主题","发送时间","时长","发送次数","费用（元）","发送结果","用户按键","商务传真","商务电话","移动电话","商务邮箱","联系人","公司名","部门","职位","国家","省/直辖市","市/区","邮政号码","地址","家庭电话","URL","说明");
$defaultSel=array(1,2,5,8,9);
?>
<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="75%" height="45" style="border-bottom:2px #218644 solid">&nbsp;<span class="title"><?=$pagetitle?></span></td>
      <td width="25%" height="45" style="border-bottom:2px #218644 solid">&nbsp;</td>
    </tr>
  </table>
  <form id="form1" name="form1" method="post" action="excelout2.php" onsubmit="return check()">
    <table width="100%" height="70" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td height="28" align="center" bgcolor="#FFFFFF"><table width="500" border="1" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2" bgcolor="#218644" style="color:#FFF">作业导出</td>
            </tr>
          <tr>
            <td colspan="2" height="39" align="center" bgcolor="#FFFFFF"><div style="width:100px;margin:auto;text-align:center"><input type="submit" name="Submit" id="sub_btn" class="bt" value="导出" /></div></td>
          </tr>        
            <?
			$rowi=1;
			foreach($optionList as $option)
			{
				if(in_array($rowi,$defaultSel))
				{
					$chk="checked";	
				}
				else
				{
					$chk="";	
				}
			?>
          <tr>
            <td width="300" align="right"><?=$option?>：</td>
            <td width="300" align="left"><input type="checkbox" name="chk<?=$rowi?>" id="chk<?=$rowi?>" <?=$chk?>/></td>
          </tr>
          	<?
			$rowi++;
			}
			?>
        </table></td>
      </tr>      

    </table>

    <input type="hidden" name="workid" id="workid" value="<?=$workid?>" />
    <input type="hidden" name="his" id="his" value="<?=$his?>" />
    <input type="hidden" name="sdate" id="sdate" value="<?=$sdate?>" />
    <input type="hidden" name="edate" id="edate" value="<?=$edate?>" />
    <input type="hidden" name="SearchType" id="SearchType" value="<?=$SearchType?>" />
    <input type="hidden" name="SearchValue" id="SearchValue" value="<?=$SearchValue?>" />
    <input type="hidden" name="IdxType" id="IdxType" value="<?=$IdxType?>" />
  </form>
</div>
</body>
</html>