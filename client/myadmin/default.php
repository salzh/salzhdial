<?
session_start();
	if(!isset($_SESSION['userid']) || $_SESSION['userid']=="" || $_SESSION['userid']==NULL) 
	{
			echo "<script language='javascript'>self.parent.location.href='./login.php';</script>";
	}	
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理系统</title>
</head>

<frameset rows="65,*" cols="*" frameborder="no" border="0" framespacing="0">
  <frame src="top.php" name="topFrame" scrolling="No" noresize="noresize" id="topFrame" title="topFrame" />
  <frameset cols="214,*" frameborder="no" border="0" framespacing="0">
    <frame src="left.php" name="leftFrame" scrolling="auto" noresize="noresize" id="leftFrame" title="leftFrame" />
    <frame src="main.php" name="right" id="right" title="main" />
  </frameset>
</frameset>
<noframes></noframes><body>
</body>
</html>
