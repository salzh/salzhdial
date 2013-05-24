<?php
   //生成验证码图片
    session_start();
        Header("Content-type: image/PNG"); 
  srand((double)microtime()*1000000); 
  $roundNum=rand(1000,9999);
  //把随机数存入session以便以后用
   $_SESSION["sessionRound"]=$roundNum;
        $im = imagecreate(58,28);
        $red = ImageColorAllocate($im, 200,100,200);
        $blue = ImageColorAllocate($im, 100,200,200);
        $black = ImageColorAllocate($im, 0,0,0);
 //局域填充，相当于背景
        imagefill($im,68,30,$red);
   //将四位整数验证码绘入图片
        imagestring($im, 5, 10, 8, $roundNum, $blue);
        for($i=0;$i<50;$i++)   //加入干扰象素
        {
                imagesetpixel($im, rand()%70 , rand()%30 , $black);
        }
        ImagePNG($im);
        ImageDestroy($im);
?>