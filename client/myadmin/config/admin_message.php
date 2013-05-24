<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../include/page.class.php");
include("../../class/message.class.php");
 include("../check.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>留言管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/jquery-1.4.1.min.js"></script>
<script>
	function output()
	{
		window.open ('excel.php','newwindow','height=100,width=400,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');	
	}
	$(function(){
		$(".hhff").click(function(){
			var h= $(this).prev("#replay").val();
			var id= $(this).next("#id").val();
			 
			  $.ajax({
				   type: "POST",
				   url: "updatehf.php",
							   dataType:'html',
				   				data:"content="+h+"&id="+id+"&cache="+new Date().getTime(),
				 				 success: function(msg){
								 	alert(msg);
									if(msg=="ok")
									{
										alert('操作成功');	
									}  
							   }	
							}); 	

		});
	});
</script>

<style>
body {
	margin:0;
	padding:10px;
	background:#fff;
	font:80% Arial, Helvetica, sans-serif;
	color:#555;
	line-height:180%;
}

h1{
	font-size:180%;
	font-weight:normal;
	color:#555;
}
a{
	text-decoration:none;
	color:#f30;	
}
p{
	clear:both;
	margin:0;
	padding:.5em 0;
}
pre{
	display:block;
	font:100% "Courier New", Courier, monospace;
	padding:10px;
	border:1px solid #bae2f0;
	background:#e3f4f9;	
	margin:.5em 0;
	overflow:auto;
	width:800px;
}

#tooltip{
	position:absolute;
	border:1px solid #333;
	background:#f7f5d1;
	padding:2px 5px;
	color:#333;
	display:none;
	}	

</style>

</head>

<body>
<script language="javascript" src="../javascript/jquery-1.4.1.min.js"></script>
<script language="javascript" src="../javascript/table.js"></script>

 <script type="text/javascript">
this.tooltip = function(){		
	xOffset = 10;
	yOffset = 20;			
	$("a.tooltip").hover(function(e){		  
		this.t = this.title;
		this.title = "";									  
		$("body").append("<p id='tooltip'>"+ this.t +"</p>");
		$("#tooltip")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px")
			.fadeIn("fast");		
    },
	function(){
		this.title = this.t;		
		$("#tooltip").remove();
    });	
	$("a.tooltip").mousemove(function(e){
		$("#tooltip")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px");
	});			
};

$(document).ready(function(){
	tooltip();
});
</script>

 <?php
$news=new Message();
$m=new MessageInfo();
$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';
if ($a=="del")
{
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	if($news->DelMessage($id))
	{
	echo Msg("操作成功","admin_message.php?action=list");
	}
}


if ($a=="dels")
{
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	if($news->DelMessages($id))
	{
	echo Msg("操作成功","admin_message.php?action=list");
	}
}
if ($a=="delall")
{
	$idlist= isset($_REQUEST['idlist']) ? $_REQUEST['idlist'] : '0';
 	
	if($idlist!=0)
	{
		$idlist= implode(',', $idlist);
		 if($news->DelMessage($idlist))
		{
			echo Msg("操作成功","admin_message.php?action=list");
		}
	}
	else
	{
		echo Msg("至少选中一项目","back");
	
	}
}

if ($a=="hf")
{
	$replay= isset($_REQUEST['replay']) ? $_REQUEST['replay'] : '';
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';

	$m->RePlay=$replay;
	$m->ReUserName="管理员";
	$m->ReTime=date('Y-m-d h:m:s');
	$m->Id=$id;
	if($news->AddReplay($m))
	{
		echo Msg("操作成功","admin_message.php?action=list");
	}
}

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="72%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">留言管理</span></td>
    <td width="28%" height="45" style="border-bottom:2px #0066CC solid"><form id="form1" name="form1" method="post" action="?">
      <div align="right">
        <input name="keyword" type="text" class="inputSearch" id="keyword" />
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
            全选&nbsp;
            <input type="submit" name="Submit23" value="删除选中"    class="button" /></td>
          <td width="57%" bgcolor="#FFFBCC" class="title"><a href="excel.php" target="_blank">导出数据</a></td>
          <td width="18%"><div align="right">
            <input type="button" name="Submit2" value="返回上一步" onclick="history.back(-1);"  class="button" />
            <input type="button" name="Submit22" value="刷新本页" onclick="history.back(0);" class="button" />
          </div></td>
        </tr>
      </table></td>
    </tr>
  </table>

 
 
    <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC" style="margin-top:5px;"  class="cooltable">
      <tr  height="35" style="color:#fff;">
        <td width="4%" bgcolor="#0066CC" ><div align="center">选择</div></td>
        <td width="5%" bgcolor="#0066CC" ><div align="center">编号</div></td>
        <td width="27%" bgcolor="#0066CC" ><div align="center">内容</div></td>
        <td width="13%" bgcolor="#0066CC" ><div align="center">所属新闻</div></td>
        <td width="11%" bgcolor="#0066CC" ><div align="center">发布者/发布时间</div></td>
        <td width="23%" bgcolor="#0066CC" ><div align="center">回复</div></td>
        <td width="5%" bgcolor="#0066CC" ><div align="center">操作</div></td>
      </tr> 
  <?php

$keyword= isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
		$s='';
		if($keyword!='')
		{
			$s=" and title like '%$keyword%'";
		}
	$count=$news->GetCount($s);
	$options = array(
 	    'total_rows' => $count, //总行数
 	    'list_rows'  => '20',  //每页显示量
 	);
	
		


	 //判断当前页码
	 $page= isset($_REQUEST['p']) ? $_REQUEST['p'] : '1';
	 $page=cint($page);
	 $offset=$options['list_rows']*($page-1);
 
 	$list2=$news->GetPageList($s, $offset,$options['list_rows']);
	foreach($list2 as $rs)
	{ 
		 
 ?>

  <tr  height="30" >
        <td><div align="center">
          <input type="checkbox" name="idlist[]"  id="idlist[]" class="chkbox"  value="<?=$rs['id']?>" />
        </div></td>
        <td><div align="center">
          <?=$rs['id']?>
        </div></td>
        <td><a href="#" class="tooltip" title="<?=$rs['content']?>"><?=$rs['content']?></a>
          <table border="0" cellspacing="0" cellpadding="0">
          
		  <?
 			$sqlh="select id,title,username,addtime  from  t_message_replay where mid=".$rs['id']."";
			$listh=$news->Gethf($sqlh);
			if($listh!=NULL)
			{
			
			foreach($listh as $rsh)
			{
		?>


		    <tr>
              <td width="283">
  <?=$rsh['username']?>在<?=$rsh['addtime']?>回复:
  <?=$rsh['title']?></td>
              <td width="40" height="35"><a href="?action=dels&amp;id=<?=$rsh['id']?>" onclick="return confirm('确定要删除吗，删除后不可恢复');"><img src="../images/del.png" style="border:0px;" /></a></td>
            </tr>
			
			  <?	
			}
			}
			
		?>
          </table>	  </td>
        <td><?=$rs['title']?></td>
        <td><div align="center">
          <?=$rs['username']==""?"":$rs['username']?>
          <br />
          <div align="center">
            <?=$rs['times']?>
          </div>
        </div></td>
        <td>
          <div align="left" class="listhf">
            <textarea name="replay" cols="40" rows="3" id="replay"  style="height:40px;"><?=$rs['replay']?></textarea>
            <input type="button" name="Submit3" value="回复"  style="font-size:12px; height:25px;"  class="hhff" />
            <input name="id" type="hidden" id="id" value="<?=$rs['id']?>" />
          </div></td><td><div align="center">&nbsp;&nbsp;
		<a href="?action=del&id=<?=$rs['id']?>" onclick="return confirm('确定要删除吗，删除后不可恢复');"> <img src="../images/del.png" style="border:0px;" /></a></div></td>
    </tr>
	  
<?php
	}
?>
  </table>
  <div> 	 
 	<?php
	/* 实例化 */
 	$page = new page($options);
 	//然后 在sql语句里面 limit $page->first_row , $page->list_rows
 	echo "<div id=page>".$page->show(1)."</div>"; //ok  打印第一样式
	?>
</form>

 
</body>
</html>