<?php
echo "aa";
require_once 'reader.php';
echo "bb";
// ExcelFile($filename, $encoding);
$data = new Sdivadsheet_Excel_Reader();
// Set output Encoding.
$data->setOutputEncoding('utf-8');
//”data.xls”是指要导入到mysql中的excel文件
$data->read('User.xlsx');


for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {

//以下注释的for循环打印excel表数据
/*
for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
            echo "\"".$data->sheets[0]['cells'][$i][$j]."\",";
           }
           echo "\n";
*/

//以下代码是将excel表数据【3个字段】插入到mysql中，根据你的excel表字段的多少，改写以下代码吧！
/*
    $sql = "INSERT INTO test VALUES('".
               $data->sheets[0]['cells'][$i][1]."','".
                 $data->sheets[0]['cells'][$i][2]."','".
                 $data->sheets[0]['cells'][$i][3]."')";
    echo $sql.'<br />';
       $res = mysql_query($sql);
*/
}

?>
