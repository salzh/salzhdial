<? header('Content-Type:text/html;charset=utf-8');
setcookie("username", NULL,0,"/");
echo "<script type='text/javascript'>alert('退出登录');parent.window.location.href='login.php'</script>";return;?>