<?php
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../include/page.class.php");
include("../../class/keys.php");
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
	
	$kd= new KeysDal();
	$a="";
	$types=isset($_REQUEST['types']) ? $_REQUEST['types'] : '';
	$keyword= isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
	$orderby= isset($_REQUEST['orderby']) ? $_REQUEST['orderby'] : '1';
 		
	if(isset($_REQUEST['action'])){
		$a=$_REQUEST['action'];
	}
?>

<?
	if($a=="addsave"){
		$kinfo=new KeysInfo();
		$kinfo->Types=$types;
		$kinfo->Orderby=cint($orderby);
		$kinfo->Keyword=$keyword;
		if($kd->add($kinfo)>0){
			echo Msg("操作成功","?action=list");
		}
	}
	if($a=="modifysave"){
		$kinfo=new KeysInfo();
		$kinfo->Id=$_REQUEST['id'];
		$kinfo->Types=$types;
		$kinfo->Orderby=cint($orderby);
		$kinfo->Keyword=$keyword;
		if($kd->Edit($kinfo)>0){
			echo Msg("操作成功","?action=list");
		}
		
	}
	
	if($a=="del"){
		//删除
		$id=$_REQUEST['id'];
		if($kd->Del($id)>0){
			echo Msg("删除成功","?action=list");
		}
	}
	if ($a=="add"){
?>
<div id="Layer1">
  <form id="form2" name="form2" method="post" action="?action=addsave">

  <table width="87%"  border="0" cellpadding="1" cellspacing="3" style="border:1px solid #ccc">
    <tr>
      <td height="29" colspan="2" bgcolor="#0066CC"><div align="right"><a href="?action=list" style="color:#fff">关闭&nbsp;&nbsp; </a></div></td>
    </tr>
    <tr>
      <td height="20" width="100" bgcolor="#FFFFFF"><div align="center" style="width: 100px;">所属页面</div></td>
      <td height="20" bgcolor="#FFFFFF">
      
      <select name="types">
      	<?php  
        	$types = array("力康动态","力康产品","解决方案","力康服务");
        	foreach ($types as $v) {
        	?>
        	<option value="<?=$v?>"><?=$v?></option>
        	<?}?>
      </select></td>
    </tr>
    <tr>
      <td width="16%" height="22" bgcolor="#FFFFFF"><div align="center">关键词</div></td>
      <td width="84%" height="22" bgcolor="#FFFFFF"><input name="keyword" type="text" id="keyword" size="50" maxlength="30" /></td>
    </tr>
    <tr>
      <td height="22" bgcolor="#FFFFFF"><div align="center">排序</div></td>
      <td height="22" bgcolor="#FFFFFF"><input name="orderby" type="text" id="orderby" value="1" size="5" maxlength="5" /></td>
    </tr>
    <tr>
      <td height="40" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="40" bgcolor="#FFFFFF"><input type="submit" name="Submit" class="bt" value="保存" /></td>
    </tr>
  </table>
  </form>
</div>

<?}?>


<?php 
if($a=="modify"){
$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
$m= $kd->GetModel($id);
if($m!=NULL){
	$_id=$m->Id;
	$_types=$m->Types;
	$_keyword=$m->Keyword;
	$_orderby=$m->Orderby;
	?>
<div id="Layer2">
  <form id="form2" name="form2" method="post" action="?action=modifysave&id=<?=$_id?>">

  <table width="81%" border="0" cellpadding="1" cellspacing="3" style="border:1px solid #ccc">
    <tr>
      <td height="29" colspan="2" bgcolor="#0066CC"><div align="right"><a href="?action=list" style="color:#fff">关闭&nbsp;&nbsp; </a></div></td>
    </tr>
     <tr>
      <td height="20" bgcolor="#FFFFFF"><div align="center">所属页面</div></td>
      <td height="20" bgcolor="#FFFFFF">
      <select name="types">
      	<?php  
        	$tsArry = array("力康动态","力康产品","解决方案","力康服务");
        	foreach ($tsArry as $v) {
        	?>
        	<option value="<?=$v?>" <?=$_types==$v?"selected='selected'":""?>><?=$v?></option>
        	<?}?>
      </select></td>
    </tr>
    <tr>
      <td width="15%" height="22" bgcolor="#FFFFFF"><div align="center">关键词</div></td>
      <td width="85%" height="22" bgcolor="#FFFFFF"><input name="keyword" type="text" id="keyword" size="50" value="<?=$_keyword?>" maxlength="30" /></td>
    </tr>
    <tr>
      <td height="22" bgcolor="#FFFFFF"><div align="center">排序</div></td>
      <td height="22" bgcolor="#FFFFFF"><input name="orderby" type="text" id="orderby" value="<?=$_orderby?>" size="5" maxlength="5" /></td>
    </tr>
    <tr>
      <td height="33" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="33" bgcolor="#FFFFFF"><input type="submit" name="Submit" class="bt" value="保存" /></td>
    </tr>
  </table>
  </form>
</div>
<?}}?>
<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="38%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<strong>热门搜索管理</strong></td>
      <td width="62%" height="45" style="border-bottom:2px #0066CC solid"><input type="button" name="Submit2" class="bt" onclick="window.location.href='?action=add'" value="添加关键词" /></td>
    </tr>
  </table>
  
    <table width="500" border="0" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF" style="margin-top:3px;">
      <tr>
        <td width="54" bgcolor="#ECF5FF"><div align="center">ID</div></td>
        <td width="267" bgcolor="#ECF5FF"><div align="center">所属页面</div></td>
        <td width="267" bgcolor="#ECF5FF"><div align="center">关键词</div></td>
        <td width="267" bgcolor="#ECF5FF"><div align="center">排序</div></td>
        <td width="45" bgcolor="#ECF5FF"><div align="center">改</div></td>
        <td width="55" bgcolor="#ECF5FF"><div align="center">删</div></td>
      </tr>
 <?php 
	$s_types= isset($_REQUEST['types']) ? $_REQUEST['types'] : '';
	$s_keyword= isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
	$s='';
	if(trim($s_types)!=''){$s=" and types = '".$s_types."'";}
	if(trim($s_keyword)!=''){$s=$s." and keyword like '%".$s_keyword."%'";}
	$count=$kd->GetCount($s);
	$options = array(
 	    'total_rows' => $count, //总行数
 	    'list_rows'  => '10',  //每页显示量
 	);
	 //判断当前页码
	 $page= isset($_REQUEST['p']) ? $_REQUEST['p'] : '1';
	 $page=cint($page);
	 $offset=$options['list_rows']*($page-1);
 
 	$list2=$kd->GetPageList($s, $offset,$options['list_rows']);
	foreach($list2 as $rs){ 
 ?>
		<tr bgcolor="#f5f5f5">
        <td width="54" height="25" bgcolor="#f5f5f5" align="center"><?=$rs['id']?></td>
        <td width="267" bgcolor="#f5f5f5" align="center"><a><?=$rs['types']?></a></td>
        <td width="267" bgcolor="#f5f5f5" align="left"><a><?=$rs['Keyword']?></a></td>
        <td width="267" bgcolor="#f5f5f5" align="left"><a><?=$rs['orderby']?></a></td>
        <td width="45" bgcolor="#ffffff" align="center"><a href="?action=modify&id=<?=$rs['id']?>"><img src="../images/modify.gif" style="border:0px;" /></a></td>
        <td width="55" bgcolor="#ffffff" align="center"><a href='javascript:if(confirm("确实要删除该贴吗,删除后再也找不回?"))location="?action=del&id=<?=$rs['id']?>"'><img src="../images/del.gif" style="border:0;" /></a></td>
   	  </tr>
	  
<?}?><tr><td colspan="6">
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
