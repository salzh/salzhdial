<?php
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/message.class.php");
 
        header("Content-Type: application/vnd.ms-excel");
 
        header("Content-Disposition: attachment; filename=message(" .date("Y-m-d"). ").xls");
 
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
 
                <td width="87">所属新闻</td>

                <td width="87">发布者</td>

                <td width="87">发布时间</td>

                <td width="87">内容</td>

                <td width="87">指定回复人</td>

                <td width="87">回复内容</td>
 
        </tr>
 
<?php
$news=new Message();
$sql="select a.*,b.title  from t_message  a left join t_news b on b.id=a.newsid";
$list=$news->Gethf($sql);
foreach($list as $rs)
{
$rsid=$rs['id'];
$sqlh="select id,title,username,addtime  from  t_message_replay where mid='".$rsid."'";
$listh=$news->Gethf($sqlh);
$redes="";
if($listh!=NULL)
{
	foreach($listh as $rsh)
	{
		$redes=$redes.$rsh['username'].$rsh['addtime'].":".$rsh['title'].";";
	}
} 
        echo "
 
                <tr>

                        <td>{$rsid}</td>
 
                        <td>{$rs['title']}</td>
						
                        <td>{$rs['username']}</td>
 
                        <td>{$rs['times']}</td>
 
                        <td>{$rs['content']}</td>
 
                        <td>{$rs['rename']}</td>
 
                        <td>{$redes}</td>
 
                </tr>";
 
}
 
?>
 
</table>
 
</body>
 
</html>