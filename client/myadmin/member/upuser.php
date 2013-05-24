<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>上传</title>
</head>

<body>

<form action="upload_file.php" method="post"
enctype="multipart/form-data">
<label for="file">用户数据上传<br />
  <br />
  文件名:</label>
<input name="file" type="file" id="file" style="width:500px" width="500"/> 
<br /><br />
<input type="submit" name="submit" value="上传" />
</form>
<br/>
注：上传之前请先将excel文件保存为xls格式，另由于上传数据量较大，导入时间比较长，请耐心等待。
</body>
</html>
