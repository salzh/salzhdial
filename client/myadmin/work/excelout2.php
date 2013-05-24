<?php
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/workdetail.class.php");


function exportexcel(){
	  	$workid= isset($_REQUEST['workid']) ? $_REQUEST['workid'] : '0';
		$his= isset($_REQUEST['his']) ? $_REQUEST['his'] : '0';
		$sdate= isset($_REQUEST['sdate'])? $_REQUEST['sdate'] : '';
		$edate= isset($_REQUEST['edate'])? $_REQUEST['edate'] : '';
		$SearchType= isset($_REQUEST['SearchType'])? $_REQUEST['SearchType'] : '';
		$SearchValue= isset($_REQUEST['SearchValue'])? $_REQUEST['SearchValue'] : '';
		$IdxType= isset($_REQUEST['IdxType'])? $_REQUEST['IdxType'] : '';		
		$listDal=new WorkDetailDal();
        $content = '<table border="1">';
        $content .= '<tr height="17" style="height:12.75pt">';

        $content .= '<td width="80">'.setchar('编号').'</td>';
        if($_REQUEST['chk1']=="on") $content .= '<td width="100">'.setchar('商务电话').'</td>';
        if($_REQUEST['chk2']=="on") $content .= '<td width="100">'.setchar('收件人').'</td>';
        if($_REQUEST['chk3']=="on") $content .= '<td width="100">'.setchar('主题').'</td>';
        if($_REQUEST['chk4']=="on") $content .= '<td width="100">'.setchar('发送时间').'</td>';
        if($_REQUEST['chk5']=="on") $content .= '<td width="100">'.setchar('时长').'</td>';
        if($_REQUEST['chk6']=="on") $content .= '<td width="100">'.setchar('发送次数').'</td>';
        if($_REQUEST['chk7']=="on") $content .= '<td width="100">'.setchar('费用（元）').'</td>';
        if($_REQUEST['chk8']=="on") $content .= '<td width="100">'.setchar('发送结果').'</td>';
        if($_REQUEST['chk9']=="on") $content .= '<td width="100">'.setchar('用户按键').'</td>';
		if($_REQUEST['chk10']=="on") $content .= '<td width="80">'.setchar('商务传真').'</td>';
		if($_REQUEST['chk11']=="on") $content .= '<td width="80">'.setchar('商务电话').'</td>';
		if($_REQUEST['chk12']=="on") $content .= '<td width="80">'.setchar('移动电话').'</td>';
		if($_REQUEST['chk13']=="on") $content .= '<td width="80">'.setchar('商务邮箱').'</td>';
		if($_REQUEST['chk14']=="on") $content .= '<td width="80">'.setchar('联系人').'</td>';
		if($_REQUEST['chk15']=="on") $content .= '<td width="80">'.setchar('公司名').'</td>';
		if($_REQUEST['chk16']=="on") $content .= '<td width="80">'.setchar('部门').'</td>';
		if($_REQUEST['chk17']=="on") $content .= '<td width="80">'.setchar('职位').'</td>';
		if($_REQUEST['chk18']=="on") $content .= '<td width="80">'.setchar('国家').'</td>';
		if($_REQUEST['chk19']=="on") $content .= '<td width="80">'.setchar('省/直辖市').'</td>';
		if($_REQUEST['chk20']=="on") $content .= '<td width="80">'.setchar('市/区').'</td>';
		if($_REQUEST['chk21']=="on") $content .= '<td width="80">'.setchar('邮政号码').'</td>';
		if($_REQUEST['chk22']=="on") $content .= '<td width="80">'.setchar('地址').'</td>';
		if($_REQUEST['chk23']=="on") $content .= '<td width="80">'.setchar('家庭电话').'</td>';
		if($_REQUEST['chk24']=="on") $content .= '<td width="80">'.setchar('URL').'</td>';
		if($_REQUEST['chk25']=="on") $content .= '<td width="80">'.setchar('说明').'</td>';			
        $content .= '</tr>';
		
		if($SearchValue!='')
		{		
			if($SearchType!='')
			{
				$s=" and $SearchType like '%$SearchValue%'";
			}
		}
		$idxField="";
		if($IdxType!='')
		{
			$idxField=" order by b.$IdxType";
		}

		if($sdate!='')
		{
			$s.=" and a.SendTime >= '$sdate'";
		}
		if($edate!='')
		{
			$s.=" and a.SendTime <= '$edate'";
		}				
		if($workid!='0')
		{
			$s.=" and WorkId=".$workid;
		}
		//$s.=" and a.UserId = ".$_SESSION["userid"];

		if($his=='0')
		{
			$list2=$listDal->GetPageList($s, 0,50000);
		}
		else
		{
			$list2=$listDal->GetHisPageList($s, 0,50000);
		}
		//echo count($list2);
		//exit(0);
		$rowi=1;
		$contentd='';
		foreach($list2 as $rs){ 
			$contentd.= "<tr>";
			$contentd.= "<td>{$rowi}</td>";
			if($_REQUEST['chk1']=="on") $contentd.= "<td>".setchar($rs['TelNo'])."</td>";
			if($_REQUEST['chk2']=="on") $contentd.= "<td>".setchar($rs['Receiver'])."</td>";
			if($_REQUEST['chk3']=="on") $contentd.= "<td>".setchar($rs['Title'])."</td>";
		 	if($_REQUEST['chk4']=="on") $contentd.= "<td>".setchar($rs['SendTime'])."</td>";
			if($_REQUEST['chk5']=="on") $contentd.= "<td>".setchar($rs['TimeLength'])."</td>";
		 	if($_REQUEST['chk6']=="on") $contentd.= "<td>".setchar($rs['SendNum'])."</td>";
			if($_REQUEST['chk7']=="on") $contentd.= "<td>".setchar($rs['Money'])."</td>";
			if($_REQUEST['chk8']=="on") $contentd.= "<td>".setchar($rs['SendText'])."</td>";
			if($_REQUEST['chk9']=="on") $contentd.= "<td>".setchar($rs['KeyText']!=''&&$rs['KeyText']!='无'&&isset($rs['KeyText'])?'按键确认':'无')."</td>";
			if($_REQUEST['chk10']=="on") $contentd.= "<td>".setchar($rs['Fax'])."</td>";
			if($_REQUEST['chk11']=="on") $contentd.= "<td>".setchar($rs['Tel'])."</td>";
			if($_REQUEST['chk12']=="on") $contentd.= "<td>".setchar($rs['Mobi'])."</td>";
			if($_REQUEST['chk13']=="on") $contentd.= "<td>".setchar($rs['Email'])."</td>";
			if($_REQUEST['chk14']=="on") $contentd.= "<td>".setchar($rs['Linkman'])."</td>";
			if($_REQUEST['chk15']=="on") $contentd.= "<td>".setchar($rs['Company'])."</td>";
			if($_REQUEST['chk16']=="on") $contentd.= "<td>".setchar($rs['Dept'])."</td>";
			if($_REQUEST['chk17']=="on") $contentd.= "<td>".setchar($rs['Position'])."</td>";
			if($_REQUEST['chk18']=="on") $contentd.= "<td>".setchar($rs['Country'])."</td>";
			if($_REQUEST['chk19']=="on") $contentd.= "<td>".setchar($rs['Province'])."</td>";
			if($_REQUEST['chk20']=="on") $contentd.= "<td>".setchar($rs['City'])."</td>";
			if($_REQUEST['chk21']=="on") $contentd.= "<td>".setchar($rs['PostCode'])."</td>";
			if($_REQUEST['chk22']=="on") $contentd.= "<td>".setchar($rs['Address'])."</td>";
			if($_REQUEST['chk23']=="on") $contentd.= "<td>".setchar($rs['HomeTel'])."</td>";
			if($_REQUEST['chk24']=="on") $contentd.= "<td>".setchar($rs['Url'])."</td>";
			if($_REQUEST['chk25']=="on") $contentd.= "<td>".setchar($rs['Description'])."</td>";
		 	$contentd.= "</tr>";
            $rowi++;
		}

        $content.=$contentd.'</table>';

        Header("Content-Disposition:attachment;filename=list(".iconv('utf-8', 'gb2312',date("Y-m-d").").xls")); 
        //Header("Content-type:application/vnd.ms-excel;");
		//Header("Content-type:text/csv;");
		header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        //注意转码 
        //print_r($value);
        //echo iconv('utf-8','gb2312',$content);
        echo $content;
    }

exportexcel();

function setchar($value)
{
	return $value;
	//return iconv('utf-8','gb2312',$value);
}
?>