<?php include("../../include/mysql.class.php");
include("../../class/moniting.class.php");
$monitingInfo=new MonitingInfo();
$monitingDal=new MonitingDal();
$list=$monitingDal->GetHost();
echo json_encode($list);
?>
