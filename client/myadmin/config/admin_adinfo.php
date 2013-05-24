<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新闻管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/jquery-1.4.1.min.js"></script>
<script language="javascript" src="../javascript/table.js"></script>
</head>

<body>

<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="71%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">广告管理</span></td>
      <td width="29%" height="45" style="border-bottom:2px #0066CC solid"><form id="form1" name="form1" method="post" action="">
        <div align="right">
          <?
		  include("../include/adtypes.php");
		  ?>
          <input type="submit" name="Submit" value="搜索" class="button" />
          </div>
      </form>
      </td>
    </tr>
  </table>
  
  <form id="form2" name="form2" method="post" action="">
    <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#E6DB55" style="margin-top:5px;">
      <tr>
        <td height="36" bgcolor="#FFFBCC"><table width="100%" border="0" cellpadding="0" cellspacing="5" bgcolor="#FFFBCC">
          <tr>
            <td width="9%" height="26" bgcolor="#FFFBCC"><input type="button" name="Submit23" value="添加" class="button" /></td>
            <td width="74%" bgcolor="#FFFBCC">&nbsp;</td>
            <td width="17%"><input type="button" name="Submit2" value="返回上一步" class="button" />
              <input type="button" name="Submit22" value="刷新本页" class="button" /></td>
          </tr>
        </table></td>
      </tr>
    </table>
	
 
    <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC" style="margin-top:5px;"  class="cooltable">
      <tr  height="35" style="color:#fff;">
        <td width="4%" height="28" bgcolor="#0066CC" ><div align="center"><strong>编号</strong></div></td>
        <td width="10%" bgcolor="#0066CC" ><div align="center"><strong>图片</strong></div></td>
        <td width="48%" bgcolor="#0066CC" ><div align="center"><strong>名称</strong></div></td>
        <td width="8%" bgcolor="#0066CC" ><div align="center"><strong>分类</strong></div></td>
        <td width="8%" bgcolor="#0066CC" ><div align="center"><strong>排序</strong></div></td>
        <td width="15%" bgcolor="#0066CC" ><div align="center"><strong>操作</strong></div></td>
      </tr>
	  
	   <tr  height="30" >
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </form>
</div>


</body>
</html>
