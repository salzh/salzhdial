<?php
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/message.class.php");

function exportexcel(){
	  
        $content = '<table>';
        $content .= '<tr height="17" style="height:12.75pt">';

        $content .= '<td width="80">编号</td>';
        $content .= '<td width="200">Email</td>';
        $content .= '<td width="87">Name</td>';
        $content .= '<td width="87">Position</td>';
        $content .= '<td width="87">Group</td>';
        $content .= '<td width="87">QOC——Role</td>';
        $content .= '<td width="87">Region</td>';
        $content .= '<td width="87">Channel</td>';
        $content .= '<td width="87">登陆</td>';
        $content .= '<td width="87">查看</td>';
        $content .= '<td width="87">留言</td>';
        $content .= '<td width="87">回复</td></tr>';
		
		$news=new Message();
		$sql="select * from t_user ";

		$list=$news->Gethf($sql);
		foreach($list as $rs)
		{
			$rsid=$rs['UserId'];
			$content .= "<tr>";
			$content .= "<td>{$rsid}</td>";
			$content .= "<td>{$rs['Email']}</td>";
			$content .= "<td>{$rs['Name']}</td>";
			$content .= "<td>{$rs['Position']}</td>";
		 	$content .= "<td>{$rs['Group']}</td>";
			$content .= "<td>{$rs['QOC_Role']}</td>";
		 	$content .= "<td>{$rs['Region']}</td>";
			$content .= "<td>{$rs['Channel']}</td>";
			$content .= "<td>{$rs['loginnum']}</td>";
			$content .= "<td>{$rs['hits']}</td>";
			$content .= "<td>{$rs['msgnum']}</td>";
			$content .= "<td>{$rs['remsgnum']}</td>";
		 	$content .= "</tr>";
		}

        $content .= '</table>';
        
        Header("Content-Disposition:attachment;filename=user(".iconv('utf-8', 'gb2312',date("Y-m-d").").xls")); 
        //Header("Content-type:application/vnd.ms-excel;");
		Header("Content-type:text/csv;");
        //注意转码 
        //print_r($value);
        //echo iconv('gb2312','utf-8',$content);
        echo $content;
    }
	exportexcel();
?>
