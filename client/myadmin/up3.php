﻿<?php
/******************************************************************************

参数说明:
$max_file_size  : 上传文件大小限制, 单位BYTE
$destination_folder : 上传文件路径
$watermark   : 是否附加水印(1为加水印,其他为不加水印);

使用说明:
1. 将PHP.INI文件里面的"extension=php_gd2.dll"一行前面的;号去掉,因为我们要用到GD库;
2. 将extension_dir =改为你的php_gd2.dll所在目录;
******************************************************************************/

//上传文件类型列表
$uptypes=array(
    'video/x-flv'
);

$max_file_size=20000000;     //上传文件大小限制, 单位BYTE
$destination_folder="../uploadimg/"; //上传文件路径
$destination_folder2="uploadimg/"; //上传文件路径


$watermark=2;      //是否附加水印(1为加水印,其他为不加水印);
$watertype=1;      //水印类型(1为文字,2为图片)
$waterposition=1;     //水印位置(1为左下角,2为右下角,3为左上角,4为右上角,5为居中);
$waterstring="";  //水印字符串
$waterimg="xplore.gif";    //水印图片
$imgpreview=1;      //是否生成预览图(1为生成,其他为不生成);
$imgpreviewsize=1/4;    //缩略图比例
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>上传图片</title>
<style type="text/css">
<!--
body
{
     font-size: 9pt;
	margin:0px;
}
input
{
     background-color: #ffffff;
	 
     border: 1px inset #CCCCCC;
}
-->
</style>
</head>

<body>
<table width="100%" border="0" cellspacing="3" cellpadding="0">
  <tr>
    <td width="42%" height="78" valign="top"><form enctype="multipart/form-data" method="post" name="upform" style="margin:0px;">
  上传文件:
  <input name="upfile" type="file">
  <input type="submit" value="上传"><br>
  允许上传的文件类型为:<?=implode(', ',$uptypes)?>
</form>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (!is_uploaded_file($_FILES["upfile"]['tmp_name']))
    //是否存在文件
    {
         echo "图片不存在!";
         exit;
    }

    $file = $_FILES["upfile"];
    if($max_file_size < $file["size"])
    //检查文件大小
    {
        echo "文件太大!";
        exit;
    }

    if(!in_array($file["type"], $uptypes))
    //检查文件类型
    {
        echo "文件类型不符!".$file["type"];
        exit;
    }

    if(!file_exists($destination_folder))
    {
        mkdir($destination_folder);
    }

    $filename=$file["tmp_name"];
    $image_size = getimagesize($filename);
    $pinfo=pathinfo($file["name"]);
    $ftype=$pinfo['extension'];
    $destination = $destination_folder.time().".".$ftype;
	$destination2 = $destination_folder2.time().".".$ftype;
    if (file_exists($destination) && $overwrite != true)
    {
        echo "同名文件已经存在了";
        exit;
    }

    if(!move_uploaded_file ($filename, $destination))
    {
        echo "移动文件出错";
        exit;
    }

    $pinfo=pathinfo($destination);
    $fname=$pinfo['basename'];
    echo " <font color=red>已经成功上传</font>&nbsp;文件名:  <font color=blue>".$destination_folder.$fname."</font> &nbsp;";
    echo " 宽度:".$image_size[0];
    echo " 长度:".$image_size[1];
    echo "  &nbsp;  &nbsp;大小:".$file["size"]." bytes";

    
    if($imgpreview==1)
    {
	echo "<script>";
    echo "window.parent.document.form1.video.value='".$destination2."';";
	echo "</script>";
    }
	else
	{
	
	}
}

 ?>


</td>
  </tr>
</table>
</body>
