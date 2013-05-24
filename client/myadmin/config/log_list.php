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

 
 
<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="38%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<strong>产品操作日志</strong></td>
      <td width="62%" height="45" style="border-bottom:2px #0066CC solid"><form id="form1" name="form1" method="post" action="?">
        <div align="right">
          <input name="keyword" type="text" class="inputSearch" id="keyword" />
          <input type="submit" name="Submit" value="搜索" class="button" />
        </div>
      </form></td>
    </tr>
  </table>
  
    <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="margin-top:3px;">
      <tr>
        <td width="54" bgcolor="#ECF5FF"><div align="center">ID</div></td>
        <td width="267" bgcolor="#ECF5FF"><div align="center">时间</div></td>
        <td width="196" bgcolor="#ECF5FF"><div align="center">账号</div></td>
        <td width="338" bgcolor="#ECF5FF">产品</td>
        <td width="267" bgcolor="#ECF5FF"><div align="center">说明</div></td>
      </tr>
 <?php 
	 
	$keyword= isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
	$s='';
	 
	if(trim($keyword)!=''){$s=$s." and username like '%".$keyword."%' or productname  like '%".$keyword."%' ";}
	$count=$pro->GetCountLog($s);
	$options = array(
 	    'total_rows' => $count, //总行数
 	    'list_rows'  => '20',  //每页显示量
 	);
	 //判断当前页码
	 $page= isset($_REQUEST['p']) ? $_REQUEST['p'] : '1';
	 $page=cint($page);
	 $offset=$options['list_rows']*($page-1);
 
 	$list2=$pro->GetPageListLog($s, $offset,$options['list_rows']);
	foreach($list2 as $rs){ 
 ?>
		<tr bgcolor="#f5f5f5">
        <td width="54" height="25" bgcolor="#f5f5f5" align="center"><?=$rs['id']?></td>
        <td width="267" bgcolor="#f5f5f5" align="center"><a><?=$rs['times']?></a></td>
        <td width="196" bgcolor="#f5f5f5" align="left"><a><?=$rs['username']?></a></td>
        <td width="338" bgcolor="#f5f5f5" align="left" style="font-size:12px;"><?=$rs['pid']?>&nbsp;<?=$rs['productname']?></td>
        <td width="267" bgcolor="#f5f5f5" align="left"><a><?=$rs['notes']?></a></td>
      </tr>
	  
<?}?><tr><td colspan="5">
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
</div>



</body>
</html>
