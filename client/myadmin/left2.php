<?
include("../include/mysql.class.php");
include("../include/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>控制面版</title>
<link href="css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="javascript/jquery-1.4.1.min.js"></script>
<script language="javascript"  src="javascript/jscroll.js"></script>
<script language="javascript">
	$(function(){
		$(".leftMenu").click(function(){
				var id=($(this).attr("id"));
			 
				
		});
	
	
		 
});
 
  
</script>
<style>
body{background:#f5f5f5; overflow:scroll;overflow-x:hidden; }
</style>
</head>

<body>
<script>
function left_btn(n)
{
	var a_num=document.getElementById("list").getElementsByTagName("a");
	//for(i=0;i<a_num.length;i++)
	//{
		//a_num[i].className=i==n?"leftMenu_h2":"leftMenu_h";
 	//}
}

</script>
<script language="javascript">
 function show(ids)
 {
  var a=document.getElementById(ids);
  a.style.display=="none"?a.style.display="":a.style.display="none";
 }
</script>
	<style>
	a.select{color:#F00};/*选中的样式*/
</style>
<table width="200" height="100" border="0" cellpadding="0" cellspacing="10">
  <tr>
    <td>
	 <div align="right" style="line-height:30px; ">您好:
	  <? 
	 
	 
	 
	 if(isset($_COOKIE['username']))
	 {
	 echo $_COOKIE['username'];
	 }?> </br>
      <a href="setadmin.php?action=page" target="right">修改密码</a><br />
        <a href="loginout.php">退出登录</a><br />
	 </div>
    <div align="right"></div></td>
  </tr>
</table>
 
<table width="200" height="100" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td id="list">
	
	 <? if(count(explode("力康动态,",$_COOKIE['auth']))>1 || $_COOKIE['level']=="1"){
	 ?>
	<div class="leftMenu"   style="cursor:pointer"  onclick="show('list1');"><img src="images/news.png" />力康动态&nbsp;&nbsp; </div>
 		<span id="list1" fid="l1"  style="display:none" >
		<a onclick="left_btn(0)" class="leftMenu_h"   href="news/admin_news.php" target="right" > 力康动态管理</a>
		<a onclick="left_btn(1)" class="leftMenu_h"  href="news/up_news.php?action=add" target="right" >添加内容</a>
		<a onclick="left_btn(2)" class="leftMenu_h"  href="news/admin_news_class.php?action=list"  target="right" >力康动态分类管理</a>
	 
		</span>
	<?
	}
	?>	

 <? if(count(explode("力康服务,",$_COOKIE['auth']))>1 || $_COOKIE['level']=="1"){
	 ?>
	<div class="leftMenu" id="l2" style="cursor:pointer" onclick="show('list2');"><img src="images/news.png" />力康服务&nbsp;&nbsp; </div>
 		<span id="list2" fid="l2"   style="display:none;">
			<a onclick="left_btn(3)" class="leftMenu_h"   href="service/admin_service.php" target="right" > 力康服务管理</a>
			<a onclick="left_btn(4)" class="leftMenu_h"  href="service/up_service.php?action=add" target="right" >添加内容</a>
			<a onclick="left_btn(5)" class="leftMenu_h"  href="service/admin_service_class.php?action=list"  target="right" >服务分类管理</a>
		</span>
	<?
	}
	?>		
		
	 <? if($_COOKIE['level']=="1"){
	 ?>
	
	<div class="leftMenu"  id="l3" style="cursor:pointer"   onclick="show('list3');"><img src="images/setting.png" />网站配置&nbsp;&nbsp; </div>
		<span id="list3" fid="l3"  style="display:none;">
		<a onclick="left_btn(6)" class="leftMenu_h"   href="config/admin_config.php" target="right" >网站基本信息</a>
		<a onclick="left_btn(8)" class="leftMenu_h"  href="config/up_video.php" target="right" >视频管理</a>
		<a onclick="left_btn(9)" class="leftMenu_h"  href="banner/admin_banner.php" target="right" >BANNER管理</a>
		<a onclick="left_btn(10)" class="leftMenu_h"  href="config/keys_list.php" target="right" >热门搜索管理</a>
		</span>
		<?
		}
		?>
	
	 <? if(count(explode("关于联系管理,",$_COOKIE['auth']))>1 || $_COOKIE['level']=="1"){
	 ?>
	
	<div class="leftMenu"  id="l4" style="cursor:pointer"  onclick="show('list4');" ><img src="images/public.png" />关于/联系 内容管理&nbsp;&nbsp; </div>
		<span id="list4" fid="l4" style="display:none;">
		<a onclick="left_btn(11)" class="leftMenu_h" style="display:none;"  href="config/admin_message.php" target="right" >意见反馈</a>
		<a onclick="left_btn(12)" class="leftMenu_h"  href="config/admin_other.php" target="right" style="color:#0099CC" ><img src="images/other.png" style="border:0px;" />内容例表</a>
		<a onclick="left_btn(13)" class="leftMenu_h"  href="config/up_other.php?action=add" target="right" style="color:#0099CC" ><img src="images/other.png" style="border:0px;"  />添加内容</a>
		<a onclick="left_btn(14)" class="leftMenu_h" style="display:none;color:#0099CC"  href="config/admin_other_class.php" target="right"   ><img src="images/other.png" style="border:0px;"  />其他栏目分类管理</a>
		</span>
<?
}
?>

	 <? if(count(explode("人才招聘,",$_COOKIE['auth']))>1 || $_COOKIE['level']=="1"){
	 ?>
 	<div class="leftMenu"  id="l5" style="cursor:pointer;"  onclick="show('list5');"><img src="images/app.png" />人才招聘&nbsp;&nbsp; </div>
	<span id="list5" fid="l5" style="display:none;">
		<a onclick="left_btn(15)" class="leftMenu_h"  href="jobs/admin_job.php?action=list" target="right" >人才招聘管理</a>
		 <a onclick="left_btn(16)" class="leftMenu_h"  href="jobs/up_job.php?action=add" target="right" >添加职位</a>
 	</span>
<?
}
?>


	 <? if(count(explode("解决方案,",$_COOKIE['auth']))>1 || $_COOKIE['level']=="1"){
	 ?>
 	<div class="leftMenu"  id="l6" style="cursor:pointer;"  onclick="show('list6');"><img src="images/public.png" />解决方案管理&nbsp;&nbsp; </div>
	<span id="list6" fid="l6" style="display:none;">
		<a onclick="left_btn(17)" class="leftMenu_h"  href="solution/admin_solution.php?action=list" target="right" >解决方案管理</a>
		 <a onclick="left_btn(18)" class="leftMenu_h"  href="solution/up_solution.php?action=add" target="right" >添加解决方案</a>
		<a onclick="left_btn(19)" class="leftMenu_h" style="color:#0099CC"  href="solution/admin_solution_class.php" target="right"   > 解决方案类别管理</a>

 	</span>
	<?
}
?>
	
	 <? if(count(explode("产品管理,",$_COOKIE['auth']))>1 || $_COOKIE['level']=="1"){
	 ?>
	 	<div class="leftMenu"  id="l7" style="cursor:pointer;"  onclick="show('list7');"><img src="images/public.png" />产品管理&nbsp;&nbsp; </div>
	<span id="list7" fid="l7" style="display:none;">
		<a onclick="left_btn(20)" class="leftMenu_h"  href="product/admin_product.php?action=list" target="right" >产品管理列表</a>
		 <a onclick="left_btn(21)" class="leftMenu_h"  href="product/up_product.php?action=add" target="right" >添加产品</a>
		<a onclick="left_btn(22)" class="leftMenu_h" style="color:#0099CC"  href="product/admin_product_class.php" target="right"   > 产品分类管理</a>
		<?
		if($_COOKIE['level']=="1")
		{
		?>
		<a onclick="left_btn(22)" class="leftMenu_h" style="color:#0099CC"  href="product/admin_sc_class.php" target="right"   > 市场分类管理</a>
		<a   class="leftMenu_h" style="color:#0099CC"  href="product/keys_list.php" target="right"   > 产品关键字管理</a>
		<a   class="leftMenu_h" style="color:#0099CC"  href="config/log_list.php" target="right"   > 日志查看</a>
		<?
		}
		?>
 	</span>
	<?
	}
	?>
	
	 <? if($_COOKIE['level']=="1"){
	 ?>
	<div class="leftMenu" id="l8" style="cursor:pointer"   onclick="show('list8');"><img src="images/news.png" />管理员管理&nbsp;&nbsp; </div>
 	<span id="list8" fid="l8" style="display:none;">
	<a class="leftMenu_h" href="admin/list.php" target="right">管理员管理</a>
	<a  class="leftMenu_h" href="admin/add.php?action=page" target="right" >添加管理员</a>
	</span>
	<?
	}
	?>
	 <? if(count(explode("用户管理,",$_COOKIE['auth']))>1 || $_COOKIE['level']=="1"){
	 ?>
	<div class="leftMenu" id="l9" style="cursor:pointer"   onclick="show('list9');"><img src="images/news.png" />用户管理&nbsp;&nbsp; </div>
 	<span id="list9" fid="l9" style="display:none;">
	<a   class="leftMenu_h"   href="member/list.php" target="right">用户管理</a>
	<a   class="leftMenu_h"  href="member/add.php?action=page" target="right" >添加会员</a>
	</span>
		<?
		}
		?>
 <div class="leftMenu" id="l10" style="cursor:pointer" onclick="window.location.href='loginout.php'" ><img src="images/news.png" />退出登录&nbsp;&nbsp; </div>
  
	</td>
  </tr>
</table>
 </body>
</html>
