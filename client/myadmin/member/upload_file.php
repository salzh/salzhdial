<?php
header('content-Type=text/html;charset=utf-8');
echo $_FILES["file"]["type"];
if (($_FILES["file"]["type"] == "application/vnd.ms-excel"||$_FILES["file"]["type"] == "application/octet-stream")
&& ($_FILES["file"]["size"] < 400000))
  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  else
    {
    echo "文件名: " . $_FILES["file"]["name"] . "<br />";
    echo "类型: " . $_FILES["file"]["type"] . "<br />";
    echo "大小: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";

	move_uploaded_file($_FILES["file"]["tmp_name"],
	"" . "User.xls");
	echo "上传成功，正在导入中。。。";
	Header("Location: input_excel.php"); 
    }
  }
else
  {
  echo "错误的文件格式";
  }
?>