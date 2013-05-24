<?php
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/message.class.php");
 
        header("Content-Type: application/vnd.ms-excel");
 
        header("Content-Disposition: attachment; filename=" .date("Y-m-d"). ".xls");
 
?>
 
<html xmlns:o="urn:schemas-microsoft-com:office:office"
 
                xmlns:x="urn:schemas-microsoft-com:office:excel"
 
                xmlns="http://www.w3.org/TR/REC-html40">
 
<head>
 
        <meta http-equiv="expires" content="Mon, 06 Jan 1999 00:00:01 GMT">
 
        <meta http-equiv=Content-Type content="text/html; charset=utf-8">
 
        <!--[if gte mso 9]><xml>
 
        <x:ExcelWorkbook>
 
        <x:ExcelWorksheets>
 
                   <x:ExcelWorksheet>
 
                   <x:Name></x:Name>
 
                   <x:WorksheetOptions>
 
                                   <x:DisplayGridlines/>
 
                   </x:WorksheetOptions>
 
                   </x:ExcelWorksheet>
 
        </x:ExcelWorksheets>
 
        </x:ExcelWorkbook>
 
        </xml><![endif]-->
 
</head>
 
<body>
 
<table>
  
        <tr height="17" style="height:12.75pt">
 
                <td width="115">编号</td>
 
                <td width="87">Email</td>

                <td width="87">Name</td>

                <td width="87">Position</td>

                <td width="87">Group</td>

                <td width="87">QOC——Role</td>

                <td width="87">Region</td>
 
                <td width="87">Channel</td>

                <td width="87">登陆</td>

                <td width="87">查看</td>
                
                <td width="87">留言</td>
                
                <td width="87">回复</td>
        </tr>
 
<?php
$news=new Message();
$sql="select * from t_user ";
$list=$news->Gethf($sql);
foreach($list as $rs)
{
	$rsid=$rs['UserId'];
        echo "
 
                <tr>

                        <td>{$rsid}</td>
 
                        <td>{$rs['Email']}</td>
						
                        <td>{$rs['Name']}</td>
 
                        <td>{$rs['Position']}</td>
 
                        <td>{$rs['Group']}</td>
 
                        <td>{$rs['QOC_Role']}</td>
 
                        <td>{$rs['Region']}</td>

                        <td>{$rs['Channel']}</td>

                        <td>{$rs['loginnum']}</td>

                        <td>{$rs['hits']}</td>

                        <td>{$rs['msgnum']}</td>

                        <td>{$rs['remsgnum']}</td>
 
                </tr>";
 
}
 
?>
 
</table>
 
</body>
 
</html>