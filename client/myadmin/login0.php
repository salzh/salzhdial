

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>管理系统_用户登录</title>
    <link rel="stylesheet" type="text/css" href="Css/Manager.css" />
    <script language="javascript" src="javascript/jquery-1.4.1.min.js"></script>

<script language="javascript">

function checkuser()
{
	if($("#username").val()=="")
	{
			$("#tishi").show().html("<h5>账号不能为空</h5>");
			setTimeout('$("#tishi").html("")',2000);  
			return false;
	}
	
	if($("#password").val()=="")
	{
			$("#tishi").show().html("<h5>密码不能为空</h5>");
			setTimeout('$("#tishi").html("")',2000);  
			return false;
	}
	
	if($("#p_safecode").val()=="")
	{		$("#tishi").show().html("<h5>安全码不正确</h5>");
			 setTimeout('$("#tishi").html("")',2000);  
			return false;
	}
	return true;
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #0642B0;
	color:#999999;
}
a{color:#666666; text-decoration:none;}
input{width:150px; height:25px; line-height:25px; border:1px solid #ccc;  }
body,td,th {
	font-size: 12px;
}
-->
</style></head>
<body>
   <form id="login" name="login" method="post" action="checkuser.php" onsubmit="return checkuser(this)" >
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td align="center" valign="middle"><table width="785" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="785" height="544" background="images/logobg.jpg"><table width="424" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="390" height="260"><table width="438" border="0" align="center" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="117" height="94">&nbsp;</td>
                            <td width="152">&nbsp;</td>
                            <td width="169">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="30"><div align="center">账&nbsp; 号</div></td>
                            <td height="30" align="center" background="images/input_bg.gif"><div align="left">
                                <input name="username" type="text" id="username" size="20" maxlength="25" />
                            </div></td>
                            <td rowspan="3" align="center" background="images/input_bg.gif"><input type="submit" name="Submit" value="登录" style="width:80px; line-height:80px; height:80px; background:#06f; color:#FFFFFF; border:0px;" /></td>
                          </tr>
                          <tr>
                            <td height="30"><div align="center">密&nbsp; 码</div></td>
                            <td height="30" align="center" background="images/input_bg.gif"><div align="left">
                                <input name="password" type="password" id="password" size="20" maxlength="25" />
                            </div></td>
                          </tr>
                          <tr>
                            <td height="30"><div align="center">安全码 </div></td>
                            <td height="30"><div align="left"><span id="spError" style="color:Red;"></span>
                              <input name="p_safecode" type="password" id="p_safecode" size="20" maxlength="20" />
                            </div></td>
                          </tr>
                          <tr>
                         
                          <tr>
                            <td height="23">&nbsp;</td>
                            <td height="40"><div id="tishi" style="color:red">
                              <div align="left"></div>
                            </div></td>
                            <td>&nbsp;</td>
                          </tr>
                          
                          
                          
                        </table></td>
                      </tr>
                      <tr>
                        <td><div align="right">CopyRight◎  &nbsp; xxx  All rights reserved</div></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
        </tr>
    </table>
    </form>
</body>
</html>
