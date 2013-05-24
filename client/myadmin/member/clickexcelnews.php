<?php
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/message.class.php");
include("../../class/user.php");
include("../../class/news.php");
$ud=new UserDal();

	$newsclass=new NewsClass();
	$uname= isset($_REQUEST['uname']) ? $_REQUEST['uname'] : '';
	$sdate= isset($_REQUEST['sdate']) ? $_REQUEST['sdate'] : '';
	$edate= isset($_REQUEST['edate']) ? $_REQUEST['edate'] : '';
	$stime= isset($_REQUEST['stime']) ? $_REQUEST['stime'] : '';
	$etime= isset($_REQUEST['etime']) ? $_REQUEST['etime'] : '';	
	$totaltype= isset($_REQUEST['totaltype']) ? 'checked' : '';
$brandid= isset($_REQUEST['brandid']) ? $_REQUEST['brandid'] : '0';
$aa=array();
$aa=$newsclass->GetBrandBidSid($brandid);
$bid=$aa['bid'];
$sid=$aa['sid'];
$tid=$aa['tid'];

        header("Content-Type: application/vnd.ms-excel");
 
        header("Content-Disposition: attachment; filename=report(" .date("Y-m-d"). ").xls");
 
?>
 
<html xmlns:o="urn:schemas-microsoft-com:office:office"
 
                xmlns:x="urn:schemas-microsoft-com:office:excel"
 
                xmlns="http://www.w3.org/TR/REC-html40">
 
<head>
 
        <meta http-equiv="expires" content="Mon, 06 Jan 1999 00:00:01 GMT">
 
        <meta http-equiv=Content-Type content="text/html; charset=utf-8">
 
        <!--[if gte mso 9]><xml>
 
        <x:ExcelWorkbook>
 
        <x:ExcelWorksheets>
 
                   <x:ExcelWorksheet>
 
                   <x:Name></x:Name>
 
                   <x:WorksheetOptions>
 
                                   <x:DisplayGridlines/>
 
                   </x:WorksheetOptions>
 
                   </x:ExcelWorksheet>
 
        </x:ExcelWorksheets>
 
        </x:ExcelWorkbook>
 
        </xml><![endif]-->
 
</head>
 
<body>
    <table width="100%"  border="0" cellpadding="0" cellspacing="1"  style="margin-top:5px;"  class="cooltable">

      <tr style="color:#fff;">
        <td width="3%" bgcolor="#0066CC" ><div align="center">大类名称</div></td>
        <td width="3%" bgcolor="#0066CC" ><div align="center">小类名称</div></td>
        <td width="3%" bgcolor="#0066CC" ><div align="center">文章名称</div></td>
        <?
		for($i=0;$i<24;$i++)
		{
		?>
        	<td width="30" bgcolor="#0066CC" ><div align="center"><?=$i?>点</div></td>
        <?
	  	}
	  	?>
      </tr> 
  <?php

	$s='';
	$uname=str_replace(' ','',$uname);

	if($bid!=0)
	{
			$s.=" and a.bid = ".$bid;
	}
	
	if($sid!=0)
	{
			$s.=" and a.sid = ".$sid;
	}
	

	if($sdate!='')
	{
		$s.=" and createtime>='$sdate'";	
	}
	if($stime!='')
	{
		$s.=" and DATE_FORMAT(createtime,'%k')>=$stime";
	}

	if($edate!='')
	{
		$s.=" and createtime<='$edate'";
	}
	
	if($stime!='')
	{
		$s.=" and DATE_FORMAT(createtime,'%k')<=$etime";
	}	
	if($uname!='')
	{
		$s.=" and n.title like '%$uname%'";		
	}
	$count=$ud->GetNewsTotalCount($s);
	
	$options = array(
 	    'total_rows' => $count, //总行数
 	    'list_rows'  => '50000',  //每页显示量
 	);
	 //判断当前页码
	 $page= isset($_REQUEST['p']) ? $_REQUEST['p'] : '1';
	 $page=cint($page);
	 $offset=$options['list_rows']*($page-1);
 
 	

		$list2=$ud->GetNewsPageTotal($s, $offset,$options['list_rows']);
		foreach($list2 as $rs){ 
 ?>
  <tr>
        <td align="center"><?=$rs['bname']?></td>
        <td align="center"><?=$rs['sname']?></td>
        <td align="center"><?=$rs['title']?></td>
        <?
		for($i=0;$i<24;$i++)
		{
		?>
        	<td align="center"><?=$rs['d'.$i]?></td>
        <?
	  	}
	  	?>
      </tr>
	  
<?php }
?>
  </table>


</body>
 
</html>