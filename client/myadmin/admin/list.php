<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../include/page.class.php");
include("../../class/admin.class.php");
include("../check.php");
$ad=new AdminDal();
$uname= isset($_REQUEST['uname']) ? $_REQUEST['uname'] : '';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理员管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="../tree/zTreeStyle.css" type="text/css">
	<script type="text/javascript" src="../tree/jquery-1.4.4.min.js"></script>
	<script type="text/javascript" src="../tree/jquery.ztree.core-3.5.js"></script>
	<SCRIPT type="text/javascript">
		<!--
		var setting = {
			data: {
				simpleData: {
					enable: true
				}
			},
			callback: {
				onClick: onTreeClick
			}
		};

		var zNodes =<?

			$s=" and StatusId<>-1 ";
		
		 	$treelist=$ad->GetTreeList($s);
			echo json_encode($treelist);
		?>;

		$(document).ready(function(){
			$.fn.zTree.init($("#treeDemo"), setting, zNodes);
		});
		
		function onTreeClick(event, treeId, treeNode, clickFlag) {
			$("#listdetail").attr("src","rightlist.php?upid="+treeNode.id+"&uname=<?=$uname?>");
			//$("#treedetail").html("[ onClick ]&nbsp;&nbsp;clickFlag = " + treeNode.id + " (" + (clickFlag===1 ? "普通选中": (clickFlag===0 ? "<b>取消选中</b>" : "<b>追加选中</b>")) + ")");
			/*
			$.ajax({
				type : "POST",
				url : 'rightlist.php?upid='+treeNode.id,
				dataType : "html",
				success: function(data) {
					$("#treedetail").html(data);
					},
				error : function() {
					alert("数据读取错误，请联系管理员.");
				}
			});	
			*/		
		}
		//-->
	</SCRIPT>
    
</head>

<body>
<script language="javascript">
	function add(){
		
	}
</script>

<div class="box">
<?php
$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';
if ($a=="del"){
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	if($ad->Del($id)){
	echo Msg("操作成功","list.php?action=list");
	}
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="72%" height="45" style="border-bottom:2px #218644 solid">&nbsp;<span class="title">管理员管理</span></td>
    <td width="28%" height="45" style="border-bottom:2px #218644 solid"><form id="form1" name="form1" method="post" action="?">
      <div align="right">
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
          <td width="25%" height="26" bgcolor="#FFFBCC"><input onclick="checkAllLine()" class="chkbox" id="checkedAll" name="checkedAll" type="checkbox" />
            全选&nbsp; </td>
          <td width="37%" bgcolor="#FFFBCC">&nbsp;</td>
          <td width="38%"><div align="right">
            <input type="button" name="Submit222" value="添加" onclick="location='set.php?action=page'"  class="button" />
            <input type="button" name="Submit2" value="返回上一步" onclick="history.back(-1);"  class="button" />
            <input type="button" name="Submit22" value="刷新本页" onclick="document.location.reload();" class="button" />
          </div></td>
        </tr>
      </table></td>
    </tr>
  </table>
  <table width="100%"  border="1" cellpadding="0" cellspacing="0" bordercolor="#999999" height="550">
  	<tr>
    <td width="20%" valign="top">
        <div class="zTreeDemoBackground left">
            <ul id="treeDemo" class="ztree"></ul>
        </div>    
    </td>
    <td width="80%" valign="top">
	<iframe id="listdetail" src="rightlist.php?uname=<?=$uname?>" frameborder="0" width="100%" height="550"></iframe>
  </td>
  </tr>
  </table>
</form>

</div>

</body>
</html>