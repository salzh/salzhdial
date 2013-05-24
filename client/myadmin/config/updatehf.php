<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/message.class.php");
 ?>
 <?
 		$news=new Message();
		$replay= isset($_REQUEST['content']) ? $_REQUEST['content'] : '';
		$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
 		$m->RePlay=$replay;
		$m->ReUserName="管理员";
		$m->ReTime=date('Y-m-d h:m:s');
		$m->Id=$id;
		if($news->AddReplay($m))
		{
			echo "ok";
		}
 ?>