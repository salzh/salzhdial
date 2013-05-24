<?php
session_start();
$_SESSION['re_code']='';
$type = 'gif';
$width= 50;
$height= 25;
header("Content-type: image/".$type);
srand((double)microtime()*1000000);
$randval = randStr(4,"ALL");
if($type!='gif' && function_exists('imagecreatetruecolor')){
     $im = @imagecreatetruecolor($width,$height);
}else{
     $im = @imagecreate($width,$height);
}
     $r = Array(225,211,255,223);
     $g = Array(225,236,237,215);
     $b = Array(225,236,166,125);

     $key = rand(0,3);
 
     $backColor = ImageColorAllocate($im,$r[$key],$g[$key],$b[$key]);//±³¾°É«£¨Ëæ»ú£©
     $borderColor = ImageColorAllocate($im, 220, 166, 111);//±ß¿òÉ«
     $pointColor = ImageColorAllocate($im, 255, 255, 255);//µãÑÕÉ«

     @imagefilledrectangle($im, 0, 0, $width - 30, $height - 10, $backColor);//±³¾°Î»ÖÃ
     @imagerectangle($im, 0, 0, $width-1, $height-1, $borderColor); //±ß¿òÎ»ÖÃ
     $stringColor = ImageColorAllocate($im, 0,0,0);

     for($i=0;$i<=100;$i++){
           $pointX = rand(2,$width-2);
           $pointY = rand(2,$height-2);
           @imagesetpixel($im, $pointX, $pointY, $pointColor);
     }

     @imagestring($im, 8, 5, 1, $randval, $stringColor);
     $ImageFun='Image'.$type;
     $ImageFun($im);
     @ImageDestroy($im);
     $_SESSION['re_code'] = $randval;
//²úÉúËæ»ú×Ö·û´®
function randStr($len=6,$format='ALL') {
           switch($format) {
                 case 'ALL':
                 $chars='ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'; break;
                 case 'CHAR':
                 $chars='ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz'; break;
                 case 'NUMBER':
                 $chars='123456789'; break;
                 default :
                 $chars='ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
                 break;
           }
     $string="";
     while(strlen($string)<$len)
     $string.=substr($chars,(mt_rand()%strlen($chars)),1);
     return $string;
}

 
?>
