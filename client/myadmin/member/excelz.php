<?php
header('Content-type: application/vnd.ms-excel'); //定义文件输出格式
header('Content-Disposition: filename=question.xls') ; //定义输出的文件名
include("connect.php");
mysql_query("SET NAMES 'utf8'");
//$data=mysql_query("select a.question,a.answer,u.* from ask_question a left join t_user u on u.Email=a.username order by a.id");
$data=mysql_query("select * from t_user u where u.Email in (select distinct username from ask_question)");
$num=mysql_num_rows($data);
$i=1;
?>
<table width="900" border="1">
  <tr>
    <td width="103" rowspan="2" align="center">编号</td>
    <td colspan="150" align="center">用户信息</td>
    <td colspan="17" align="center">问卷结果</td>
  </tr>
  <tr>
    <td width="70" align="center">Emai</td>
    <td width="64" align="center">Name</td>
    <td width="59" align="center">Position</td>
    <td width="54" align="center">Group</td>
    <td width="76" align="center">QOC_Role</td>
    <td width="47" align="center">Region</td>
    <td width="49" align="center">Channel</td>
    <td width="150" align="center">答题时间</td>
	<?php for($i=1;$i<=16;$i++){?>
    <td>问题<?php echo $i; ?></td>
	<?php }  ?>
  </tr>
<?php
$i=1;
while($rows=mysql_fetch_assoc($data)){?>
  <tr>
    <td><?php echo $i; ?>&nbsp;</td>
    <td><?php echo $rows['Email']; ?>&nbsp;</td>
    <td><?php echo $rows['Name']; ?>&nbsp;</td>
    <td><?php echo $rows['Position']; ?>&nbsp;</td>
    <td><?php echo $rows['Group']; ?>&nbsp;</td>
    <td><?php echo $rows['QOC_Role']; ?>&nbsp;</td>
    <td><?php echo $rows['Region']; ?>&nbsp;</td>
    <td><?php echo $rows['Channel']; ?>&nbsp;</td>
	<?php
	$data2=mysql_query("select * from ask_question where username='".$rows['Email']."'");
	$v0="";	
	$v1="";
	$v2="";
	$v3="";
	$v4="";
	$v5="";
	$v6="";
	$v7="";
	$v8="";
	$v9="";
	$v10="";
	$v11="";
	$v12="";
	$v13="";
	$v14="";
	$v15="";
	$v16="";
	
	while($rows2=mysql_fetch_assoc($data2)){
		$v0=$rows2['addtimes'];
		switch(substr($rows2['question'],0,strpos(str_replace("-",".",$rows2['question']),".")))
		{
			case "1":
				$v1=$rows2['answer'];
				break;
			case "2":
				$v2=$rows2['answer'];
				break;
			case "3":
				$v3=$rows2['answer'];
				break;
			case "4":
				$v4=$rows2['answer'];
				break;
			case "5":
				$v5=$rows2['answer'];
				break;
			case "6":
				$v6=$rows2['answer'];
				break;
			case "7":
				$v7=$rows2['answer'];
				break;
			case "8":
				$v8=$rows2['answer'];
				break;
			case "9":
				$v9=$rows2['answer'];
				break;
			case "10":
				$v10=$rows2['answer'];
				break;
			case "11":
				$v11=$rows2['answer'];
				break;
			case "12":
				$v12=$rows2['answer'];
				break;
			case "13":
				$v13=$rows2['answer'];
				break;
			case "14":
				$v14=$rows2['answer'];
				break;
			case "15":
				$v15=$rows2['answer'];
				break;
			case "16":
				$v16=$rows2['answer'];
				break;
		}
	}  ?>
    <?php echo "<td>".$v0."</td>"."<td>".$v1."</td>"."<td>".$v2."</td>"."<td>".$v3."</td>"."<td>".$v4."</td>"."<td>".$v5."</td>"."<td>".$v6."</td>"."<td>".$v7."</td>"."<td>".$v8."</td>"."<td>".$v9."</td>"."<td>".$v10."</td>"."<td>".$v11."</td>"."<td>".$v12."</td>"."<td>".$v13."</td>"."<td>".$v14."</td>"."<td>".$v15."</td>"."<td>".$v16."</td>"; ?>
  </tr>
<?php
$i++;
} ?>
</table>