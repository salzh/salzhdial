<?
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/message.class.php");
$filename="exceldata.xls";
header('Pragma:public');
header('Content-Type:application/x-msexecl;name="'.$filename.'";charset=GB2312');
header('Content-Disposition:inline;filename="'.$filename.'"');
$news=new Message();
$tx='留言信息';   
echo   iconv("UTF-8", "GB2312",$tx)."\n\n";   
//输出内容如下：  
echo   iconv("UTF-8", "GB2312","编号")."\t";   
echo   iconv("UTF-8", "GB2312","所属新闻")."\t"; 
echo   iconv("UTF-8", "GB2312","发布者")."\t"; 
echo   iconv("UTF-8", "GB2312","发布时间")."\t"; 
echo   iconv("UTF-8", "GB2312","内容")."\t"; 
echo   iconv("UTF-8", "GB2312","指定回复人")."\t"; 
echo   iconv("UTF-8", "GB2312","回复内容")."\t";    
echo   "\n";   
$sql="select a.*,b.title  from t_message  a left join t_news b on b.id=a.newsid";
$list=$news->Gethf($sql);
foreach($list as $rs)
{
$rsid=$rs['id'];
echo   iconv("UTF-8", "GB2312",$rsid)."\t";   
echo   iconv("UTF-8", "GB2312",$rs['title'])."\t";   
echo   iconv("UTF-8", "GB2312",$rs['username'])."\t";   
echo   iconv("UTF-8", "GB2312",$rs['times'])."\t";   
echo   iconv("UTF-8", "GB2312",$rs['content'])."\t";   
echo   iconv("UTF-8", "GB2312",$rs['rename'])."\t";   
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
echo   iconv("UTF-8", "GB2312",$redes)."\t"; 
echo   "\n";   
}
?>