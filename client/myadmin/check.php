<?php
if($_SESSION['userid']==NULL)
{
	echo "<script language='javascript'>self.parent.location.href='/evoice/myadmin/login.php';</script>";
	exit;
}	
?>