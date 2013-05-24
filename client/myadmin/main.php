<?
	if(!isset($_COOKIE['username']) || $_COOKIE['username']=="" || $_COOKIE['username']==NULL) 
	{
			echo "<script language='javascript'>self.parent.location.href='../login.php';</script>";
	}	
include("../include/mysql.class.php");
include("../include/config.php");
include("../class/public.class2.php");
include("../class/admin.class.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>控件面版</title>
<link href="css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript"  src="javascript/jquery-1.4.1.min.js" ></script>
<script language="javascript"  src="javascript/fun.js" ></script>

<link rel="stylesheet" href="javascript/jquery-plugin-boxy/css/common.css" type="text/css" />
<link rel="stylesheet" href="javascript/jquery-plugin-boxy/css/boxy.css" type="text/css" />
<script type="text/javascript" src="javascript/jquery-plugin-boxy/js/jquery.boxy.js"></script>

				  	<script type="text/javascript">
				 
					$(function() {
					  Boxy.DEFAULTS.title = "标题";
					  // Diagnostics
					  $("#diagnostics").click(function() {
						  new Boxy("<div><a href='#nogo' onclick='diagnose(Boxy.get(this));'>显示诊断信息</a></div>");
						  return false;
					  });
   
					  // Z-index
					  var zIndex = null;
					  $("#z-index").click(function() {
						 show("aaaaaaaaaaaaaaaaaaaaaa");
					  });	  
					  $("#z-index-latest").click(function() {
						  zIndex.toTop();
						  return false;
					  });
					  
					  
					  
					 
					});
					
					function show(aaa){
					 zIndex = new Boxy(
							"<div>"+aaa+"</div>", { clickToFront: true }

						  );
						  return false;
					}
				 </script>

				
</head>

<body>
 
<div class="box" style="border:0px;">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="46%" valign="top">    
      <table width="941" height="196" border="0" cellpadding="0" cellspacing="3">
        <tr>
          <td width="888" height="190" valign="top"><table width="703" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="703" height="33" ><h1 style="font-size:22px;width:300px"><?=$configs['web_title']?></h1></td>
              </tr>
              <tr>
                <td style="border:1px #CCCCCC solid;"><div align="left">
                <?=$configs['web_foot']?>
                </div></td>
              </tr>
          </table>
            <br /></td>
          <td width="44" valign="top">&nbsp;</td>
        </tr>
      </table>
        
      </td>
    </tr>
  </table>
</div>
  
</body>
</html>
