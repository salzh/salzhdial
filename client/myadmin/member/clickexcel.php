<?php
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/message.class.php");
include("../../class/user.php");
$ud=new UserDal();
	$uname= isset($_REQUEST['uname']) ? $_REQUEST['uname'] : '';
	$sdate= isset($_REQUEST['sdate']) ? $_REQUEST['sdate'] : '';
	$edate= isset($_REQUEST['edate']) ? $_REQUEST['edate'] : '';
	$stime= isset($_REQUEST['stime']) ? $_REQUEST['stime'] : '';
	$etime= isset($_REQUEST['etime']) ? $_REQUEST['etime'] : '';	
	$totaltype= isset($_REQUEST['totaltype']) ? 'checked' : '';
function getPageName($url)
{
	$obj= new UserDal();
	$pagename="";
	if(strpos($url,"index.php")||$url=="http://www.ucdportal.com/cmo/")
	{
		$pagename="首页";
	}
	elseif(strpos($url,"com.php"))
	{
		$pagename="栏目-COM";
	}
	elseif(strpos($url,"rmt.php?types=0"))
	{
		$pagename="栏目-RTM";
	}
	elseif(strpos($url,"kc.php?types=0"))
	{
		$pagename="栏目-KC";
	}
	elseif(strpos($url,"dep.php?types=0"))
	{
		$pagename="栏目-DEP";
	}
	elseif(strpos($url,"report_w.php"))
	{
		$pagename="周报";
	}	
	elseif(strpos($url,"report_m.php"))
	{
		$pagename="月报";
	}	
	elseif(strpos($url,"report.php"))
	{
		$pagename="日报";
	}	
	elseif(strpos($url,"re.php"))
	{
		$pagename="需回复";
	}
	elseif(strpos($url,"realert.php"))
	{
		$pagename="已回复";
	}
	elseif(strpos($url,"complaintbox.php"))
	{
		$pagename="意见箱";
	}
	elseif(strpos($url,"?sid="))
	{
		$t=substr($url,strpos($url,"?sid=")+5);
		if(is_numeric($t))
		{
			$pagename="栏目-".$obj->GetTopId("select classname from t_product_class where classid=".$t);
		}
		else
		{
			$pagename="栏目-".$url;
		}
	}
	elseif(strpos($url,"?id="))
	{
		$t=substr($url,strpos($url,"?id=")+4);
		if(is_numeric($t))
		{
			$pagename="文章-".$obj->GetTopId("select title from t_news where id=".$t);
		}
		else
		{
			$pagename="文章-".$url;
		}
	}	
	else
	{
		$pagename=$url;
	}
	return $pagename;
}
	
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
    <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC" style="margin-top:5px;"  class="cooltable">
      <?
      if($totaltype=="checked")
	  {
		?>
      <tr style="color:#fff;">
        <td width="4%" bgcolor="#0066CC" ><div align="center">时段</div></td>
        <td width="28%" bgcolor="#0066CC" ><div align="center">数量</div></td>
        <td width="8%" bgcolor="#0066CC" ><div align="center">当前页面</div></td>
      </tr> 
      <?
	  }
	  else
	  {
      ?>
      <tr style="color:#fff;">
        <td width="4%" bgcolor="#0066CC" ><div align="center">编号</div></td>
        <td width="28%" bgcolor="#0066CC" ><div align="center">Email</div></td>
        <td width="12%" bgcolor="#0066CC" ><div align="center">点击链接</div></td>
        <td width="8%" bgcolor="#0066CC" ><div align="center">当前页面</div></td>
        <td width="13%" bgcolor="#0066CC" ><div align="center">点击时间</div></td>
      </tr> 
  <?php
	  }
	$s='';
	$uname=str_replace(' ','',$uname);
	if($uname!='')
	{
		$obj= new UserDal();
		$stitle=$obj->GetTopId("select id from t_news where replace(`title`,' ','') ='$uname'");
		$s=" and thisurl like '%id=$stitle%'";
	}
	if($sdate!='')
	{
		$s.=" and times>='$sdate'";	
	}
	if($stime!='')
	{
		$s.=" and DATE_FORMAT(times,'%k')>=$stime";
	}

	if($edate!='')
	{
		$s.=" and times<='$edate'";
	}
	
	if($stime!='')
	{
		$s.=" and DATE_FORMAT(times,'%k')<=$etime";
	}
	if($totaltype=="checked")
	{
		$count=$ud->GetClickTotalCount($s);
	}
	else
	{
		$count=$ud->GetClickCount($s);
	}
	$options = array(
 	    'total_rows' => $count, //总行数
 	    'list_rows'  => '50000',  //每页显示量
 	);
	 //判断当前页码
	 $page= isset($_REQUEST['p']) ? $_REQUEST['p'] : '1';
	 $page=cint($page);
	 $offset=$options['list_rows']*($page-1);
 
 	
	if($totaltype=="checked")
	{
		$list2=$ud->GetClickPageTotal($s, $offset,$options['list_rows']);
		foreach($list2 as $rs){ 
 ?>
  <tr>
        <td align="center"><?=$rs['hh']?></td>
        <td><?=$rs['cnt']?></td>
        <td></td>
      </tr>
	  
<?php }
	}
	else
	{
		$list2=$ud->GetClickPageList($s, $offset,$options['list_rows']);
		foreach($list2 as $rs){ 
 ?>
  <tr>
        <td align="center"><?=$rs['id']?></td>
        <td><?=$rs['userid']?></td>
        <td><?=$rs['url']?></td>
        <td><?=getPageName($rs['thisurl'])?></td>
        <td align="center"> <?=$rs['times']?></td>
      </tr>
	  
<?php }
	}
?>
  </table>


</body>
 
</html>