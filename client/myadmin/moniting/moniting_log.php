<?php include("../../include/mysql.class.php");
include("../../class/moniting.class.php");
include("../../include/ofc/open-flash-chart.php");
$monitingInfo=new MonitingInfo();
$monitingDal=new MonitingDal();
$host= isset($_REQUEST['t'])? $_REQUEST['t'] : '';
$list=$monitingDal->GetTodayList($host);
for($h=0;$h<=23;$h++){
	$logvalue=0;
	foreach($list as $value){
		if((int)$value[0]==$h)
		{
			$logvalue=$value[1];
		}
	}
	$moneys[]= (int)$logvalue;			  
}
  
$title = New title("今日通话情况统计");
$title->set_style("{font-size:14px;font-weight:bold;height:30px;color:#000000; font-family: Verdana; text-align: center;}");
$chart = new open_flash_chart();
$chart -> set_title($title);

$bar = new bar();
$bar->set_colour('#FF3366');
$bar->set_alpha( 0.7 );
$bar->set_values($moneys);
$chart -> add_element($bar);
$x_axis = new x_axis();
$x_axis -> set_3d(1);
$x_axis -> colour = '#ADFF2F';
//用数组设定X轴下标内容
//$x_axis -> set_labels_from_array($date_day);
//$x_axis -> set_range(1,5,1);

$x_axis -> set_range(1,24,1);

$y_axis = new y_axis();
$y_axis -> set_colour('#ADFF2F');
$y_axis -> set_tick_length(5);

//设置Y轴区间及步长
$y_axis -> set_range(0, 100, 0);
$x_legend = new x_legend("单位:小时");

$x_legend->set_style("{font-size:12px;color:#FF0000;}");

$y_legend = new y_legend('次数');
$y_legend->set_style('{font-size: 12px; color:#0000ff; font-family: Verdana; text-align: center;}');

$chart -> set_x_axis($x_axis);
$chart -> set_y_axis($y_axis);
$chart -> set_x_legend($x_legend);
$chart -> set_y_legend($y_legend);
$content= $chart -> toPrettyString();
$content = iconv("GBK", "UTF-8", $content);
//$content = mb_convert_encoding($content, "UTF-8", "GBK");
echo $content;
?>