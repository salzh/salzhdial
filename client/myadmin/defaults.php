<?
include("../include/mysql.class.php");
include("../include/config.php");
include("../check.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$configs["web_name"]?>后台管理系统</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
#top {height:60px;}
#left{float:left; width:20%}
#right{float:right; width:80%;overflow:hidden}

-->
</style> 
<style type="text/css">
html {height:100%;max-height:100%;padding:0;margin:0;border:0;background:#ffffff; overflow:hidden;}
body{height:100%;max-height:100%; border:0;overflow:hidden; background:#fff; padding:0;}
/*lay
--------------*/
#content{
 position:absolute; 
 overflow:hidden; 
 z-index:3; 
 top:106px; 
 left:0;
 width:100%;
 bottom:28px; 
 bottom:28px\9;
 background:#fff; 
 }
*html #content {
 top:0; 
 height:100%; 
 max-height:100%; 
 width:100%; 
 overflow:hidden;
 position:absolute; 
 z-index:3; 
 }
#header{
 position:absolute;
 margin:0;
 top:0;
 left:0;
 display:block;
 width:100%;
 height:60px;
 background:#fff;
 z-index:5;
 overflow:hidden;
 color:#fff;
 text-align:center;
 }
 
 
/*中间表格*/
.main_tab{
 width:100%;
 height:100%;
 }
.main_tab td{
 padding:0px;
 margin:0px;
 vertical-align:top;
 height:100%;
 max-height:100%;
 }
.main_tab td.td_left{
 width:181px;
 background:#fff;
 } 
.main_tab td.td_close{
 padding:0px;
 width:8px;
 vertical-align:middle;
 background:#fff;
 font-size:12px;
 overflow:hidden;
 }

</style>
</head>
<script language="javascript">
/*============================*/
/* 对已有的frame进行高度自适应  */
/*============================*/
function oldIframeAutoHeight(id)
{
    var oldFarme = document.getElementById(id);

    if (document.all)
    {
        oldFarme.attachEvent('onload', function()
        {
            oldFarme.setAttribute("width", window.frames[id].document.body.scrollWidth);
            oldFarme.setAttribute("height", window.frames[id].document.body.scrollHeight);
        });
    }
    else
    {
        oldFarme.addEventListener('load', function()
        {
            oldFarme.setAttribute("width", window.frames[id].document.body.scrollWidth);
            oldFarme.setAttribute("height", window.frames[id].document.body.scrollHeight);
        }, false);
    }
}
</script>
<body>
 <div id="header"><iframe frameborder="0" src="top.php" height="60" marginheight="0" marginwidth="0" width="100%" scrolling="no"></iframe></div>
<div id="content">
  <table width="100%" height="360"  border="0" cellpadding="0"  class="main_tab">
    <tr>
      <td width="200" valign="top" class="td_left"><iframe frameborder="0" src="left.php" height="100%" marginheight="0" marginwidth="0" width="200" scrolling="No"></iframe></td>
      <td valign="top"><iframe frameborder="0" src="main.php" height="100%"  width="800" onload="oldIframeAutoHeight(this);" marginheight="0" id="right" name="right" marginwidth="0"   scrolling="No"></iframe></td>
    </tr>
  </table>
</div>
</body>
</html>


 
</body>
</html>
