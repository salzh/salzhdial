<?
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/message.class.php");
$filename="exceldata.xls";
header('Pragma:public');
header('Content-Type:application/x-msexecl;name="'.$filename.'";charset=GBK');
header('Content-Disposition:inline;filename="'.$filename.'"');
$news=new Message();
$tx='用户信息';   
echo   iconv("UTF-8", "GBK",$tx)."\n\n";   
//输出内容如下：  
echo   iconv("UTF-8", "GBK","编号")."\t";   
echo   iconv("UTF-8", "GBK","Email")."\t"; 
echo   iconv("UTF-8", "GBK","Name")."\t"; 
echo   iconv("UTF-8", "GBK","Position")."\t"; 
echo   iconv("UTF-8", "GBK","Group")."\t"; 
echo   iconv("UTF-8", "GBK","QOC——Role")."\t"; 
echo   iconv("UTF-8", "GBK","Region")."\t";    
echo   iconv("UTF-8", "GBK","Channel")."\t";    
echo   iconv("UTF-8", "GBK","登陆")."\t";    
echo   iconv("UTF-8", "GBK","查看")."\t";    
echo   iconv("UTF-8", "GBK","留言")."\t";    
echo   iconv("UTF-8", "GBK","回复")."\t";    
echo   "\n";   
$sql="select * from t_user ";
$list=$news->Gethf($sql);
foreach($list as $rs)
{
$rsid=$rs['UserId'];
echo   iconv("UTF-8", "GBK",$rsid)."\t";   
echo   iconv("UTF-8", "GBK",$rs['Email'])."\t";   
echo   iconv("UTF-8", "GBK",$rs['Name'])."\t";   
echo   iconv("UTF-8", "GBK",$rs['Position'])."\t";   
echo   iconv("UTF-8", "GBK",$rs['Group'])."\t";   
echo   iconv("UTF-8", "GBK",$rs['QOC_Role'])."\t"; 
echo   iconv("UTF-8", "GBK",$rs['Region'])."\t"; 
echo   iconv("UTF-8", "GBK",$rs['Channel'])."\t"; 
echo   iconv("UTF-8", "GBK",$rs['loginnum'])."\t"; 
echo   iconv("UTF-8", "GBK",$rs['hits'])."\t"; 
echo   iconv("UTF-8", "GBK",$rs['msgnum'])."\t"; 
echo   iconv("UTF-8", "GBK",$rs['remsgnum'])."\t";   
echo   "\n";   
}
?>