<?
session_start();
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
body{background:#f5f5f5; overflow:hidden; }
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
<table width="200" height="100" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td id="list">
	<?
	if($_SESSION['operatorid']==1)
	{
	?>
	<div class="leftMenu"   style="cursor:pointer"  onclick="show('list1');"><img src="images/yygl.png"/>&nbsp;&nbsp;语音管理&nbsp;&nbsp; </div>
 		<span id="list1" fid="l1"  style="display:none" >
		<a  class="leftMenu_h"   href="work/set.php" target="right" > 语音发送</a>
		<a  class="leftMenu_h"  href="news/up_news.php?action=add" target="right" >作业查询</a>  
		<a  class="leftMenu_h"   href="template/admin_news.php" target="right" >清单查询</a>
		<a  class="leftMenu_h"  href="work/searchhis.php" target="right" >历史记录</a>
        <a  class="leftMenu_h"  href="template/up_news.php?action=add" target="right" >语音模板</a>              
		</span>     <?
	}
	else
	{
	?>
	<div class="leftMenu"   style="cursor:pointer"  onclick="show('list1');"><img style="margin-top:2px" src="images/yygl.png" />&nbsp;&nbsp;语音管理</div>
 		<span id="list1" fid="l1"  style="display:none" >
		<a  class="leftMenu_h"   href="work/set.php" target="right" >语音发送</a>
		<a  class="leftMenu_h"  href="work/list.php" target="right" >作业查询</a>  
		<a  class="leftMenu_h"   href="work/searchlist.php" target="right" >清单查询</a>
		<a  class="leftMenu_h"  href="work/search.php" target="right" >历史记录</a>
        <a  class="leftMenu_h"  href="voicetemplate/list.php" target="right" >语音模板</a>
        </span>	 
 
	<div class="leftMenu"   style="cursor:pointer"  onclick="show('list2');"><img src="images/book.png"  style="margin-top:2px"/>&nbsp;&nbsp;数据管理</div>
 		<span id="list2" fid="l2"  style="display:none" >
		<a  class="leftMenu_h"   href="addressgroup/list.php" target="right" >常用联系人</a>
		<a  class="leftMenu_h"  href="addressgroup/listinput.php" target="right" >群发地址本</a>  
		<a  class="leftMenu_h"   href="linkman/search.php" target="right" >综合查询</a>
        </span>	 
 
 	<div class="leftMenu"   style="cursor:pointer"  onclick="show('list3');"><img src="images/dzqz.png" />&nbsp;&nbsp;电子签章</div>
 		<span id="list3" fid="l3"  style="display:none" >
		<a  class="leftMenu_h"   href="seal/list.php" target="right" >印章管理</a>
		<a  class="leftMenu_h"  href="autograph/list.php" target="right" >签名管理</a>  
        </span>	

 	<div class="leftMenu"   style="cursor:pointer"  onclick="show('list4');"><img style="margin-top:2px" src="images/zhgl.png" />&nbsp;&nbsp;账户管理</div>
 		<span id="list4" fid="l4"  style="display:none" >
		<a  class="leftMenu_h"   href="admin/list.php" target="right" >员工账号</a>
		<a  class="leftMenu_h"  href="admin/querylist.php" target="right" >员工查询</a>  
		<a  class="leftMenu_h"  href="recharge/list.php" target="right" >充值查询</a> 
        <a  class="leftMenu_h"  href="recharge/setMoney.php" target="right" >用户充值</a>  
		<a  class="leftMenu_h"  href="admin/updateuser.php" target="right" >用户信息</a>  
		<a  class="leftMenu_h"  href="admin/updateuserdetail.php" target="right" >证件管理</a>  
		<a  class="leftMenu_h"  href="setadmin.php?action=page" target="right" >更改密码</a>  
        </span>	

	
	<div class="leftMenu" style="cursor:pointer"   onclick="show('list5');"><img style="margin-top:2px" src="images/set.png" />&nbsp;&nbsp;网站配置</div>
		<span id="list5" fid="l5"  style="display:none;">
		<a onclick="left_btn(6)" class="leftMenu_h"   href="config/admin_config.php" target="right" >网站基本信息</a>
 		</span>
	 
	
	<?
	}
	?>
 <div class="leftMenu" id="l10" style="cursor:pointer" onclick="window.location.href='loginout.php'" ><img style="margin-top:2px" src="images/logout.png" width="28" height="28" />&nbsp;&nbsp;退出登录 </div>
  
	</td>
  </tr>
</table>
 </body>
</html>
