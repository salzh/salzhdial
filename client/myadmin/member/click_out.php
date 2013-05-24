<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../include/page.class.php");
//include("../../class/public.class.php");
include("../../class/user.php");
include("../check.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function HS_DateAdd(interval,number,date){
	number = parseInt(number);
	if (typeof(date)=="string"){var date = new Date(date.split("-")[0],date.split("-")[1],date.split("-")[2])}
	if (typeof(date)=="object"){var date = date}
	switch(interval){
	case "y":return new Date(date.getFullYear()+number,date.getMonth(),date.getDate()); break;
	case "m":return new Date(date.getFullYear(),date.getMonth()+number,checkDate(date.getFullYear(),date.getMonth()+number,date.getDate())); break;
	case "d":return new Date(date.getFullYear(),date.getMonth(),date.getDate()+number); break;
	case "w":return new Date(date.getFullYear(),date.getMonth(),7*number+date.getDate()); break;
	}
}
function checkDate(year,month,date){
	var enddate = ["31","28","31","30","31","30","31","31","30","31","30","31"];
	var returnDate = "";
	if (year%4==0){enddate[1]="29"}
	if (date>enddate[month]){returnDate = enddate[month]}else{returnDate = date}
	return returnDate;
}

function WeekDay(date){
	var theDate;
	if (typeof(date)=="string"){theDate = new Date(date.split("-")[0],date.split("-")[1],date.split("-")[2]);}
	if (typeof(date)=="object"){theDate = date}
	return theDate.getDay();
}
function HS_calender(){
	var lis = "";
	var style = "";
	style +="<style type='text/css'>";
	style +=".calender { width:170px; height:auto; font-size:12px; margin-right:14px; background:url(calenderbg.gif) no-repeat right center #fff; border:1px solid #397EAE; padding:1px}";
	style +=".calender ul {list-style-type:none; margin:0; padding:0;}";
	style +=".calender .day { background-color:#EDF5FF; height:20px;}";
	style +=".calender .day li,.calender .date li{ float:left; width:14%; height:20px; line-height:20px; text-align:center}";
	style +=".calender li a { text-decoration:none; font-family:Tahoma; font-size:11px; color:#333}";
	style +=".calender li a:hover { color:#f30; text-decoration:underline}";
	style +=".calender li a.hasArticle {font-weight:bold; color:#f60 !important}";
	style +=".lastMonthDate, .nextMonthDate {color:#bbb;font-size:11px}";
	style +=".selectThisYear a, .selectThisMonth a{text-decoration:none; margin:0 2px; color:#000; font-weight:bold}";
	style +=".calender .LastMonth, .calender .NextMonth{ text-decoration:none; color:#000; font-size:18px; font-weight:bold; line-height:16px;}";
	style +=".calender .LastMonth { float:left;}";
	style +=".calender .NextMonth { float:right;}";
	style +=".calenderBody {clear:both}";
	style +=".calenderTitle {text-align:center;height:20px; line-height:20px; clear:both}";
	style +=".today { background-color:#ffffaa;border:1px solid #f60; padding:2px}";
	style +=".today a { color:#f30; }";
	style +=".calenderBottom {clear:both; border-top:1px solid #ddd; padding: 3px 0; text-align:left}";
	style +=".calenderBottom a {text-decoration:none; margin:2px !important; font-weight:bold; color:#000}";
	style +=".calenderBottom a.closeCalender{float:right}";
	style +=".closeCalenderBox {float:right; border:1px solid #000; background:#fff; font-size:9px; width:11px; height:11px; line-height:11px; text-align:center;overflow:hidden; font-weight:normal !important}";
	style +="</style>";

	var now;
	if (typeof(arguments[0])=="string"){
		selectDate = arguments[0].split("-");
		var year = selectDate[0];
		var month = parseInt(selectDate[1])-1+"";
		var date = selectDate[2];
		now = new Date(year,month,date);
	}else if (typeof(arguments[0])=="object"){
		now = arguments[0];
	}
	var lastMonthEndDate = HS_DateAdd("d","-1",now.getFullYear()+"-"+now.getMonth()+"-01").getDate();
	var lastMonthDate = WeekDay(now.getFullYear()+"-"+now.getMonth()+"-01");
	var thisMonthLastDate = HS_DateAdd("d","-1",now.getFullYear()+"-"+(parseInt(now.getMonth())+1).toString()+"-01");
	var thisMonthEndDate = thisMonthLastDate.getDate();
	var thisMonthEndDay = thisMonthLastDate.getDay();
	var todayObj = new Date();
	today = todayObj.getFullYear()+"-"+todayObj.getMonth()+"-"+todayObj.getDate();
	
	for (i=0; i<lastMonthDate; i++){  // Last Month's Date
		lis = "<li class='lastMonthDate'>"+lastMonthEndDate+"</li>" + lis;
		lastMonthEndDate--;
	}
	for (i=1; i<=thisMonthEndDate; i++){ // Current Month's Date

		if(today == now.getFullYear()+"-"+now.getMonth()+"-"+i){
			var todayString = now.getFullYear()+"-"+(parseInt(now.getMonth())+1).toString()+"-"+i;
			lis += "<li><a href=javascript:void(0) class='today' onclick='_selectThisDay(this)' title='"+now.getFullYear()+"-"+(parseInt(now.getMonth())+1)+"-"+i+"'>"+i+"</a></li>";
		}else{
			lis += "<li><a href=javascript:void(0) onclick='_selectThisDay(this)' title='"+now.getFullYear()+"-"+(parseInt(now.getMonth())+1)+"-"+i+"'>"+i+"</a></li>";
		}
		
	}
	var j=1;
	for (i=thisMonthEndDay; i<6; i++){  // Next Month's Date
		lis += "<li class='nextMonthDate'>"+j+"</li>";
		j++;
	}
	lis += style;

	var CalenderTitle = "<a href='javascript:void(0)' class='NextMonth' onclick=HS_calender(HS_DateAdd('m',1,'"+now.getFullYear()+"-"+now.getMonth()+"-"+now.getDate()+"'),this) title='Next Month'>&raquo;</a>";
	CalenderTitle += "<a href='javascript:void(0)' class='LastMonth' onclick=HS_calender(HS_DateAdd('m',-1,'"+now.getFullYear()+"-"+now.getMonth()+"-"+now.getDate()+"'),this) title='Previous Month'>&laquo;</a>";
	CalenderTitle += "<span class='selectThisYear'><a href='javascript:void(0)' onclick='CalenderselectYear(this)' title='Click here to select other year' >"+now.getFullYear()+"</a></span>年<span class='selectThisMonth'><a href='javascript:void(0)' onclick='CalenderselectMonth(this)' title='Click here to select other month'>"+(parseInt(now.getMonth())+1).toString()+"</a></span>月"; 

	if (arguments.length>1){
		arguments[1].parentNode.parentNode.getElementsByTagName("ul")[1].innerHTML = lis;
		arguments[1].parentNode.innerHTML = CalenderTitle;

	}else{
		var CalenderBox = style+"<div class='calender'><div class='calenderTitle'>"+CalenderTitle+"</div><div class='calenderBody'><ul class='day'><li>日</li><li>一</li><li>二</li><li>三</li><li>四</li><li>五</li><li>六</li></ul><ul class='date' id='thisMonthDate'>"+lis+"</ul></div><div class='calenderBottom'><a href='javascript:void(0)' class='closeCalender' onclick='closeCalender(this)'>×</a><span><span><a href=javascript:void(0) onclick='_selectThisDay(this)' title='"+todayString+"'>Today</a></span></span></div></div>";
		return CalenderBox;
	}
}
function _selectThisDay(d){
	var boxObj = d.parentNode.parentNode.parentNode.parentNode.parentNode;
		boxObj.targetObj.value = d.title;
		boxObj.parentNode.removeChild(boxObj);
}
function closeCalender(d){
	var boxObj = d.parentNode.parentNode.parentNode;
		boxObj.parentNode.removeChild(boxObj);
}

function CalenderselectYear(obj){
		var opt = "";
		var thisYear = obj.innerHTML;
		for (i=1970; i<=2020; i++){
			if (i==thisYear){
				opt += "<option value="+i+" selected>"+i+"</option>";
			}else{
				opt += "<option value="+i+">"+i+"</option>";
			}
		}
		opt = "<select onblur='selectThisYear(this)' onchange='selectThisYear(this)' style='font-size:11px'>"+opt+"</select>";
		obj.parentNode.innerHTML = opt;
}

function selectThisYear(obj){
	HS_calender(obj.value+"-"+obj.parentNode.parentNode.getElementsByTagName("span")[1].getElementsByTagName("a")[0].innerHTML+"-1",obj.parentNode);
}

function CalenderselectMonth(obj){
		var opt = "";
		var thisMonth = obj.innerHTML;
		for (i=1; i<=12; i++){
			if (i==thisMonth){
				opt += "<option value="+i+" selected>"+i+"</option>";
			}else{
				opt += "<option value="+i+">"+i+"</option>";
			}
		}
		opt = "<select onblur='selectThisMonth(this)' onchange='selectThisMonth(this)' style='font-size:11px'>"+opt+"</select>";
		obj.parentNode.innerHTML = opt;
}
function selectThisMonth(obj){
	HS_calender(obj.parentNode.parentNode.getElementsByTagName("span")[0].getElementsByTagName("a")[0].innerHTML+"-"+obj.value+"-1",obj.parentNode);
}
function HS_setDate(inputObj){
	var calenderObj = document.createElement("span");
	calenderObj.innerHTML = HS_calender(new Date());
	calenderObj.style.position = "absolute";
	calenderObj.targetObj = inputObj;
	inputObj.parentNode.insertBefore(calenderObj,inputObj.nextSibling);
}
  </script>
<style>
  body {font-size:12px}
  td {text-align:center}
  h1 {font-size:26px;}
  h4 {font-size:16px;}
  em {color:#999; margin:0 10px; font-size:11px; display:block}
  </style>
</head>

<body>
<script language="javascript" src="../javascript/jquery-1.4.1.min.js"></script>
<script language="javascript" src="../javascript/table.js"></script>
<script language="javascript">
	function add(){
		
	}
	function output()
	{
		window.open ('clickexcel.php','newwindow','height=100,width=400,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');	
	}	
</script>

<div class="box">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="10%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">查询条件</span></td>
    <td width="90%" height="45" style="vertical-align:middle;border-bottom:2px #0066CC solid"><form id="form1" name="form1" method="GET" action="clickexcel.php">
      <div align="right"><input class="chkbox" type="checkbox" name="totaltype" id="totaltype" <?=$totaltype?>/>时段汇总&nbsp;&nbsp;时间：<input name="sdate" type="text" class="inputSearch" id="sdate" onfocus="HS_setDate(this)" value="<?=$sdate?>"/>
              <select name="stime" id="stime">
         	<option value="">全部</option>
          	<option value="0" <?=$stime=='00'?'selected':''?>>0</option>
          	<option value="1" <?=$stime=='01'?'selected':''?>>1</option>
        	<option value="2" <?=$stime=='02'?'selected':''?>>2</option>
            <option value="3" <?=$stime=='03'?'selected':''?>>3</option>
          	<option value="4" <?=$stime=='04'?'selected':''?>>4</option>
        	<option value="5" <?=$stime=='05'?'selected':''?>>5</option>
          	<option value="6" <?=$stime=='06'?'selected':''?>>6</option>
          	<option value="7" <?=$stime=='07'?'selected':''?>>7</option>
        	<option value="8" <?=$stime=='08'?'selected':''?>>8</option>
            <option value="9" <?=$stime=='09'?'selected':''?>>9</option>
            <option value="10" <?=$stime=='10'?'selected':''?>>10</option>
            <option value="11" <?=$stime=='11'?'selected':''?>>11</option>
            <option value="12" <?=$stime=='12'?'selected':''?>>12</option>
            <option value="13" <?=$stime=='13'?'selected':''?>>13</option>
            <option value="14" <?=$stime=='14'?'selected':''?>>14</option>
            <option value="15" <?=$stime=='15'?'selected':''?>>15</option>
            <option value="16" <?=$stime=='16'?'selected':''?>>16</option>
            <option value="17" <?=$stime=='17'?'selected':''?>>17</option>
            <option value="18" <?=$stime=='18'?'selected':''?>>18</option>
            <option value="19" <?=$stime=='19'?'selected':''?>>19</option>
            <option value="20" <?=$stime=='20'?'selected':''?>>20</option>
            <option value="21" <?=$stime=='21'?'selected':''?>>21</option>
            <option value="22" <?=$stime=='22'?'selected':''?>>22</option>
            <option value="23" <?=$stime=='23'?'selected':''?>>23</option>
        </select>
      至<input name="edate" type="text" class="inputSearch" id="edate" onfocus="HS_setDate(this)" value="<?=$edate?>"/>
              <select name="etime" id="etime">
         	<option value="">全部</option>
          	<option value="0" <?=$etime=='00'?'selected':''?>>0</option>
          	<option value="1" <?=$etime=='01'?'selected':''?>>1</option>
        	<option value="2" <?=$etime=='02'?'selected':''?>>2</option>
            <option value="3" <?=$etime=='03'?'selected':''?>>3</option>
          	<option value="4" <?=$etime=='04'?'selected':''?>>4</option>
        	<option value="5" <?=$etime=='05'?'selected':''?>>5</option>
          	<option value="6" <?=$etime=='06'?'selected':''?>>6</option>
          	<option value="7" <?=$etime=='07'?'selected':''?>>7</option>
        	<option value="8" <?=$etime=='08'?'selected':''?>>8</option>
            <option value="9" <?=$etime=='09'?'selected':''?>>9</option>
            <option value="10" <?=$etime=='10'?'selected':''?>>10</option>
            <option value="11" <?=$etime=='11'?'selected':''?>>11</option>
            <option value="12" <?=$etime=='12'?'selected':''?>>12</option>
            <option value="13" <?=$etime=='13'?'selected':''?>>13</option>
            <option value="14" <?=$etime=='14'?'selected':''?>>14</option>
            <option value="15" <?=$etime=='15'?'selected':''?>>15</option>
            <option value="16" <?=$etime=='16'?'selected':''?>>16</option>
            <option value="17" <?=$etime=='17'?'selected':''?>>17</option>
            <option value="18" <?=$etime=='18'?'selected':''?>>18</option>
            <option value="19" <?=$etime=='19'?'selected':''?>>19</option>
            <option value="20" <?=$etime=='20'?'selected':''?>>20</option>
            <option value="21" <?=$etime=='21'?'selected':''?>>21</option>
            <option value="22" <?=$etime=='22'?'selected':''?>>22</option>
            <option value="23" <?=$etime=='23'?'selected':''?>>23</option>
        </select>

      &nbsp;&nbsp;文章标题
        <input name="uname" type="text" class="inputSearch" id="uname" value="<?=$uname?>" />
        <input type="submit" name="Submit" value="导出" class="button" />
        </div>
    </form></td>
  </tr>
</table>
<form id="form2" name="form2" method="post" action="?action=delall" onsubmit="return confirm('确定要删除吗，删除后不可恢复');">
  <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#E6DB55" style="margin-top:5px;">
    <tr>
      <td height="36" bgcolor="#FFFBCC">&nbsp;</td>
    </tr>
  </table>
</form>

</div>

</body>
</html>