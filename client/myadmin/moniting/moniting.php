<?php include("../../include/mysql.class.php");
include("../../class/moniting.class.php");
$monitingInfo=new MonitingInfo();
$monitingDal=new MonitingDal();
$list=$monitingDal->GetHost();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>控件面版</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../js/jquery-1.7.1.min.js"></script>
</head>

<body>
 
<div class="box" style="border:0px;">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="46%" align="center" valign="top"> 
      <?
	  foreach($list as $rs){ 
	  ?> 
      <table width="1176" height="184" border="2" cellpadding="0" cellspacing="3">
        <tr>
          <td height="49" colspan="3" align="center" valign="middle" style="font-size:24px"><?=$rs['host']?></td>
          </tr>
        <tr>
          <td height="46" align="center" valign="middle" style="font-size:24px">当前线路占用</td>
          <td align="center" valign="middle" style="font-size:24px">最高线路占用</td>
          <td width="675" rowspan="2" align="center" valign="middle" style="font-size:24px">
<!--图表区-->
         <script type="text/javascript" src="swfobject.js"></script>  
         <script type="text/javascript">    
         var params = {menu: "false",scale: "noScale",wmode:"opaque"}; 
		 randnumber = Math.random()*10;
         swfobject.embedSWF("open-flash-chart.swf?r="+randnumber, "<?=$rs['host']?>salechart", "100%", "182", "9.0.0","expressInstall.swf",
  {"data-file":"moniting_log.php?s="+randnumber+"%26t=<?php echo $rs['host'];?>"});
         </script> 
         <div id="<?=$rs['host']?>salechart"></div>          
          </td>
        </tr>
        <tr>
          <td width="248" height="73" align="center" valign="middle" style="font-size:50px"><span id="<?=$rs['host']?>livecalls">0</span></td>
          <td width="231" align="center" valign="middle" style="font-size:50px"><span id="<?=$rs['host']?>maxcalls">0</span></td>
          </tr>
      </table>
      <p>&nbsp;</p>
      <?
	  }
	  ?>
      </td>
    </tr>
  </table>
</div>
<script>
function getnum()
{
    $.ajax({
             type: "get",
             async: false,
             url: "moniting_data.php",
             dataType: "json",
             success: function(json){
				 console.log(json);
				var obj = eval(json); 
            	$(obj).each(function(index) { 
                	var val = obj[index];
					console.log(val); 
					$("#"+val.host+"livecalls").html(val.livecalls);
					$("#"+val.host+"maxcalls").html(val.maxcalls);					
				})
             },
             error: function(){
                 console.log("fail");
             }
         });
}
getnum();
 $(document).ready(function(){
    setInterval(getnum, 10000);
   });
</script>
</body>
</html>
