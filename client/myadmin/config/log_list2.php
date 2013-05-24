<?php
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../include/page.class.php");
include("../../class/product.class.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>关键词管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
body,td{font-size:12px;}
#Layer1 {
	position:absolute;
	left:562px;
	top:128px;
	width:511px;
	height:390px;
	z-index:1;
	background:#fff;
}

#Layer2 {
	position:absolute;
	left:546px;
	top:129px;
	width:585px;
	height:390px;
	z-index:1;
		background:#fff;

}
 
-->
</style>
</head>

<body>
<?php
	
	$pro= new Product();
	 
?>

 
 
 
  <table width="700" border="0" cellpadding="0" cellspacing="1" bgcolor="#999999" style="margin-top:3px;">
      <tr>
        <td width="41" height="16" bgcolor="#ECF5FF"><div align="center">ID</div></td>
        <td width="118" bgcolor="#ECF5FF"><div align="center">时间</div></td>
        <td width="79" bgcolor="#ECF5FF"><div align="center">账号</div></td>
        <td bgcolor="#ECF5FF"><div align="center">产品</div></td>
        <td bgcolor="#ECF5FF"><div align="center">说明</div></td>
      </tr>
 <?php 
	 
	$keyword= isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
	$s='';
	 
	if(trim($keyword)!=''){$s=$s." and username like '%".$keyword."%' or productname  like '%".$keyword."%' ";}
	$count=$pro->GetCountLog($s);
	$options = array(
 	    'total_rows' => $count, //总行数
 	    'list_rows'  => '10',  //每页显示量
 	);
	 //判断当前页码
	 $page= isset($_REQUEST['p']) ? $_REQUEST['p'] : '1';
	 $page=cint($page);
	 $offset=$options['list_rows']*($page-1);
 
 	$list2=$pro->GetPageListLog($s, $offset,$options['list_rows']);
	foreach($list2 as $rs){ 
 ?>
		<tr bgcolor="#f5f5f5">
        <td width="41" height="25" bgcolor="#FFFFFF" align="center"><?=$rs['id']?></td>
        <td width="118" bgcolor="#FFFFFF" align="center" style="font-size:10px;"> <?=$rs['times']?> </td>
        <td width="79" bgcolor="#FFFFFF" align="left"><a><?=$rs['username']?></a></td>
        <td width="210" bgcolor="#FFFFFF" align="left" style="font-size:12px;"><?=$rs['pid']?>&nbsp;<?=$rs['productname']?></td>
        <td width="246" bgcolor="#FFFFFF" align="left"><a><?=$rs['notes']?></a></td>
      </tr>
	  
<?}?><tr><td colspan="5" bgcolor="#FFFFFF">
  <div> 	 
 	<?php
	/* 实例化 */
 	$page = new page($options);
 	//然后 在sql语句里面 limit $page->first_row , $page->list_rows
 	 echo "<div id=page style='width: 100%;'>".$page->show(1)."</div>"; //ok  打印第一样式
	?>
  </div></td>
</tr>
</table>
 


</body>
</html>
