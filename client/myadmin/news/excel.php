<?php
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/message.class.php");

function exportexcel(){
$bid= isset($_REQUEST['bid']) ? $_REQUEST['bid'] : '0';
$sid= isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '0';	  
        $content = '<table>';
        $content .= '<tr height="17" style="height:12.75pt">';

        $content .= '<td width="80">编号</td>';
        $content .= '<td width="200">标题</td>';
        $content .= '<td width="87">大类</td>';
        $content .= '<td width="87">小类</td>';
        $content .= '<td width="87">状态</td>';
        $content .= '<td width="87">点击</td>';
        $content .= '<td width="87">留言</td>';
        $content .= '<td width="87">回复</td>';
        $content .= '<td width="87">发布时间</td></tr>';
		
		$news=new Message();
		$sql="select a.*,b.classname as bname,c.classname as sname,d.classname as tname from t_news  a ";
		$sql=$sql." left join t_product_class b on b.classid=a.bid ";
		$sql=$sql." left join t_product_class c on c.classid=a.sid ";
		$sql=$sql." left join t_product_class d on d.classid=a.tid ";
		$sql=$sql." where 1=1 ";
		if($bid!=0)
		{
				$sql.=" and a.bid = ".$bid;
		}
		
		if($sid!=0)
		{
				$sql.=" and a.sid = ".$sid;
		}
		$sql=$sql." order by id desc";

		$list=$news->Gethf($sql);
		foreach($list as $rs)
		{
			$content .= "<tr>";
			$content .= "<td>{$rs['id']}</td>";
			$content .= "<td>{$rs['title']}</td>";
			$content .= "<td>{$rs['bname']}</td>";
			$content .= "<td>{$rs['sname']}</td>";
		 	$content .= "<td>{$rs['shows']}</td>";
			$content .= "<td>{$rs['hit']}</td>";
		 	$content .= "<td>{$rs['msgnum']}</td>";
			$content .= "<td>{$rs['remsgnum']}</td>";
			$content .= "<td>{$rs['times']}</td>";
		 	$content .= "</tr>";
		}

        $content .= '</table>';
        
        Header("Content-Disposition:attachment;filename=news(".iconv('utf-8', 'gb2312',date("Y-m-d").").xls")); 
        //Header("Content-type:application/vnd.ms-excel;");
		Header("Content-type:text/csv;");
        //注意转码 
        //print_r($value);
        //echo iconv('gb2312','utf-8',$content);
        echo $content;
    }
	exportexcel();
?>
