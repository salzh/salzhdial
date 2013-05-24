<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../include/page.class.php");
include("../../class/addressgroup.class.php");
include("../../class/fun.class.php");
include("../check.php");
$pagetitle="群发地址簿管理";
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
<script type="text/javascript" src="../js/jquery-1.7.1.min.js"></script>
<script language="javascript" src="../javascript/table.js"></script>
<script type="text/javascript">
	var $j=jQuery.noConflict();
	var $Id=function(id){return document.getElementById(id);}
	String.prototype.Trim = function(){return this.replace(/(^\s*)|(\s*$)/g, "");}    
	function check(){
		var uname=$Id("GroupName").value;
		if(uname.Trim()==""){alert("请输入名称");$Id("GroupName").focus();return false;}
		var uname=$Id("file").value;
		if(uname.Trim()==""){alert("请选择文件");$Id("file").focus();return false;}		
	}
</script>

<div class="box">
<?php
$listDal=new AddressGroupDal();
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
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="cooltable">
  <tr>
    <td height="45" align="center" style="border-bottom:2px #218644 solid">
    <form action="inputprocess.php" method="post" enctype="multipart/form-data" onsubmit="return check()">
    <table width="500" border="1" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2" bgcolor="#218644" style="color:#FFF">(带*为必填项）</td>
        </tr>
      <tr>
        <td width="192" align="right">地址本名称：</td>
        <td width="302" align="left"><input type="text" name="GroupName" id="GroupName" value="" maxlength="50" /></td>
      </tr>
      <tr style="display:none">
        <td align="right">类型：</td>
        <td align="left"><?=$fun->GetRadioList("GroupType",18,$GroupType)?></td>
      </tr>
      <tr>
        <td align="right">上传地址本文件：</td>
        <td align="left"><input name="file" type="file" id="file" style="width:300px" width="300"/></td>
      </tr>
      <tr>
        <td colspan="2">*<a href="example.xls">文件类型:Excel。第一、二、三、四、五、六列分别是传真号码、商务电话、移动电话、商务邮箱、联系人、公司名。详情参阅范例。</a><br />
          *txt地址本格式为：传真号码/联系人/公司名（传真号码必填）<br />
          *自定义传真扩展列部分之间用英文半角&quot;|&quot;符号相连,并放在第Q列</td>
      </tr>
      <tr>
        <td colspan="2" align="center"><div style="margin:auto;width:100px"><input type="submit" name="Submit" id="sub_btn" class="bt" value="导入" /></div></td>
        </tr>
    </table>
    </form>
    </td>
    </tr>
</table>
<form id="form2" name="form2" method="post" action="?action=delall" onsubmit="return confirm('确定要删除吗，删除后不可恢复');">
  <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#E6DB55" style="margin-top:5px;">
    <tr>
      <td height="36" bgcolor="#FFFBCC"><table width="100%" border="0" cellpadding="0" cellspacing="5" bgcolor="#FFFBCC">
        <tr>
          <td width="25%" height="26" bgcolor="#FFFBCC"><input onclick="checkAllLine()" class="chkbox" id="checkedAll" name="checkedAll" type="checkbox" />
            全选&nbsp; <input type="submit" name="Submit23" value="删除选中"    class="button" /></td>
          <td width="37%" bgcolor="#FFFBCC">&nbsp;</td>
          <td width="38%"><div align="right">
            <input type="button" name="Submit22" value="刷新本页" onclick="document.location.reload();" class="button" />
          </div></td>
        </tr>
      </table></td>
    </tr>
  </table>
    <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC" style="margin-top:5px;"  class="cooltable">
      <tr style="color:#fff;">
        <td width="5%" bgcolor="#218644" ><div align="center">选择</div></td>
        <td width="5%" bgcolor="#218644" ><div align="center">行号</div></td>
        <td width="20%" bgcolor="#218644" ><div align="center">地址本名称</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">人数</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">创建时间</div></td>
        <td width="10%" bgcolor="#218644" ><div align="center">操作</div></td>
      </tr> 
  <?php

	$uname= isset($_REQUEST['uname']) ? $_REQUEST['uname'] : '';
		$s='';
		if($uname!='')
		{
			$s=" and GroupName like '%$uname%'";
		}
		$s=" and UserId = ".$_SESSION["userid"];
	$count=$listDal->GetCount($s);
	$options = array(
 	    'total_rows' => $count, //总行数
 	    'list_rows'  => '10',  //每页显示量
 	);
	 //判断当前页码
	 $page= isset($_REQUEST['p']) ? $_REQUEST['p'] : '1';
	 $page=cint($page);
	 $offset=$options['list_rows']*($page-1);
 
 	$list2=$listDal->GetPageList($s, $offset,$options['list_rows']);
	$rowi=1;
	foreach($list2 as $rs){ 
 ?>
  <tr>
        <td align="center"><input type="checkbox" name="idlist[]"  id="idlisst[]" class="chkbox"  value="<?=$rs['id']?>" /></td>
        <td align="center"><?=$rowi?></td>
        <td><a href="../linkman/list.php?GroupId=<?=$rs['id']?>" target="_self"><?=$rs['GroupName']?></a></td>
        <td><?=$rs['GroupCount']?></td>
        <td><?=$rs['CreateDate']?></td>
        <td><div align="center">
		<a href="set.php?id=<?=$rs['id']?>&action=page"><img src="../images/edit.png" style="border:0px;" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
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