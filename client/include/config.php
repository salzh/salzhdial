<?  

class ConfigInfo{
	public $WebTitle;
	public $WebUrl;
	public $WebName;
	public $WebKeyWord;
	public $WebDes;
	public $WebFoot;
	public $WebHomeMenu;
}
class WebConfig{
	public $conn;
	public function __construct()
	{
		$this->conn=new mysql();
		
	}
	
		//析构
	public function __destruct()
	{
		  
	}
	 

	///修改网站配置
	public function EditConfig($ConfigInfo)
	{
	 
		try {  
			$table="t_config";
			$v="webtitle='".$ConfigInfo->WebTitle."',weburl='".$ConfigInfo->WebUrl."',webname='".$ConfigInfo->WebName."',webkeyword='".$ConfigInfo->WebKeyWord."',webdes='".$ConfigInfo->WebDes."',webfoot='".$ConfigInfo->WebFoot."',webhomemenu='".$ConfigInfo->WebHomeMenu."'";
			$this->conn->update($table,$v," UserId=".$_SESSION["userid"]);
				return 1;
			} 
		catch (Exception $e) {  
				return 0;
			}   
	}
	
	



	
	///返回实体
	public function GetConfigModel($oid=0)
	{
 
		try {  
			$nmodel= new ConfigInfo();
			//$sql="select * from t_config where UserId=".$_SESSION["userid"];	
			if($oid>0)
			{
				$sql="select * from t_config where UserId=$oid";
			}
			else
			{
				if($_SESSION["upuserid"]=="1"||$_SESSION["upuserid"]=="0")
				{
					$sql="select * from t_config where UserId=1";
				}
				else
				{
					if($_SESSION["userid"]!="")
					{
						$sql="select * from t_config where UserId in(SELECT id FROM t_user where FIND_IN_SET(id,getPatherId (".$_SESSION["userid"].")) and upId=1)";		}
					else
					{
						$sql="select * from t_config where UserId=1";
					}
				}
			}
			$result=$this->conn->query($sql);
			$count=$this->conn->num_rows();

			if($count==0&&$_SESSION["userid"]!="")
			{
				$sql="insert into t_config(webtitle,weburl,webname,webkeyword,webdes,webfoot,webhomemenu,UserId) select webtitle,weburl,webname,webkeyword,webdes,webfoot,webhomemenu,'".$_SESSION["userid"]."' as UserId from t_config where UserId=(select upId from t_user where id=".$_SESSION["userid"].")";
				$result=$this->conn->query($sql);
				$sql="select * from t_config where UserId=".$_SESSION["userid"];	
				$result=$this->conn->query($sql);
			}
			while($row=$this->conn->fetch_array())
			{
			 $nmodel->WebTitle=$row['webtitle'];
			 $nmodel->WebUrl=$row['weburl'];
			 $nmodel->WebName=$row['webname'];
			 $nmodel->WebKeyWord=$row['webkeyword'];
			 $nmodel->WebDes=$row['webdes'];
			 $nmodel->WebFoot=$row['webfoot'];
			 $nmodel->WebHomeMenu=$row['webhomemenu'];
			 }
			return $nmodel;
		} catch (Exception $e) {  
				return NULL;
		}   	 
	}
}
	  
$cfg= new WebConfig();
$cfginfo= new ConfigInfo();
$cfginfo=$cfg->GetConfigModel();


$SafeCode="china";
define("ROOT","/dp/");//
$thisurl="";
$configs['web_title']=$cfginfo->WebTitle;  ///网站标题
$configs['web_url']=$cfginfo->WebUrl;  //网址
$configs['web_name']=$cfginfo->WebName; //网站名称
$configs['web_keywords']=$cfginfo->WebKeyWord; //网站关键字
$configs['web_des']=$cfginfo->WebDes; //网站描述
$configs['web_author']=''; //作者
$configs['web_foot']=$cfginfo->WebFoot; //版权
$configs['web_menu']='<a href="'.$cfginfo->WebHomeMenu.'" target="_blank" >首页</a>';
$soft_css1=" ";
$soft_css2=" ";
$home_css=" ";
$user_css="";
$t_css="";
$root="0"; //0为二级目录，1为根目录
	
	
	///转为整型
	function cint($str){
		if(!is_numeric($str)){
			$str=0;
		}
		if($str=="" || $str==NULL){
			$str=0;
		}
		return $str;
	}  
   
   	///替换HTML
	function html($content)
	{
		$content=str_replace("&","",$content);
		$content=str_replace("<","",$content);
		$content=str_replace(">","",$content);
		$content=str_replace(" ","",$content);
		$content=str_replace(chr(13),"",$content);
		$content=str_replace("\\","",$content);
		$content=str_replace("'","",$content);
		$content=str_replace(chr(34),"",$content); 
		 return $content;
	}
	
	function get_real_ip(){
	$ip=false;
	if(!empty($_SERVER["HTTP_CLIENT_IP"])){
	$ip = $_SERVER["HTTP_CLIENT_IP"];
	}
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
	if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
	for ($i = 0; $i < count($ips); $i++) {
	if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
	$ip = $ips[$i];
	break;
	}
	}
	}
	return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
	}
	

 
 	//替换成换行加空格
	function scontent($content)
	{
		$content=str_replace("\n","<br>",$content);
		$content=str_replace(" ","&nbsp;",$content);
		return $content;
	}
 
 	///ALERT提示框
	function Msg($str,$types)
	{
		if($types=="back")
		{
			return "<script language='javascript'>alert('".$str."');history.back(-1);</script>";
		}
		else if($types=="alert")
		{
				return "<script language='javascript'>alert('".$str."')</script>";
		}
		else
		{
			return "<script language='javascript'>alert('".$str."');location.href='$types';</script>";
		}
	}

		/* 
	Utf-8、gb2312都支持的汉字截取函数 
	cut_str(字符串, 截取长度, 开始长度, 编码); 
	编码默认为 utf-8 开始长度默认为 0 
	*/ 
	function sub_str($string, $sublen, $start = 0, $code = 'UTF-8') 
	{ 
		if($code == 'UTF-8') 
		{ 
		$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/"; 
		preg_match_all($pa, $string, $t_string); 
		
		if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."..."; 
		return join('', array_slice($t_string[0], $start, $sublen)); 
		} 
		else 
		{ 
		$start = $start*2; 
		$sublen = $sublen*2; 
		$strlen = strlen($string); 
		$tmpstr = ''; 
		
		for($i=0; $i< $strlen; $i++) 
		{ 
			if($i>=$start && $i< ($start+$sublen)) 
			{ 
				if(ord(substr($string, $i, 1))>129) 
				{ 
					$tmpstr.= substr($string, $i, 2); 
				} 
				else 
				{ 
				$tmpstr.= substr($string, $i, 1); 
				} 
			} 
			if(ord(substr($string, $i, 1))>129) $i++; 
		} 
		
		if(strlen($tmpstr)< $strlen ) $tmpstr.= "..."; 
			return $tmpstr; 
		} 
	} 
	
	
	///编辑器
	function Editor($name,$url,$content)
	{
		$oFCKeditor = new FCKeditor($name) ;//实例化
		$oFCKeditor->BasePath = $url.'../appeditor/';//  
		$oFCKeditor->Value =$content;
		$oFCKeditor->ToolbarSet = 'Default';
		$oFCKeditor->Width = '100%' ; 
		$oFCKeditor->Height = '300' ; 
		$oFCKeditor->Create() ;	
	}
	
	
	//图片等比例
	function resize($srcImage,$toFile,$maxWidth = 100,$maxHeight = 100,$imgQuality=100)
	{
	  
		list($width, $height, $type, $attr) = getimagesize($srcImage);
		if($width < $maxWidth  || $height < $maxHeight) return ;
		switch ($type) {
		case 1: $img = imagecreatefromgif($srcImage); break;
		case 2: $img = imagecreatefromjpeg($srcImage); break;
		case 3: $img = imagecreatefrompng($srcImage); break;
		}
	   // $scale = min($maxWidth/$width, $maxHeight/$height); //求出绽放比例
	   
	   // if($scale < 1) {
		$newWidth =$maxWidth;
		$newHeight =$maxHeight;
		$newImg = imagecreatetruecolor($maxWidth, $maxHeight);
		imagecopyresampled($newImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
		$newName = "";
		$toFile = preg_replace("/(.gif|.jpg|.jpeg|.png)/i","",$toFile);
	
		switch($type) {
			case 1: if(imagegif($newImg, "$toFile$newName.gif", $imgQuality))
			return "$newName.gif"; break;
			case 2: if(imagejpeg($newImg, "$toFile$newName.jpg", $imgQuality))
			return "$newName.jpg"; break;
			case 3: if(imagepng($newImg, "$toFile$newName.png", $imgQuality))
			return "$newName.png"; break;
			default: if(imagejpeg($newImg, "$toFile$newName.jpg", $imgQuality))
			return "$newName.jpg"; break;
		}
		imagedestroy($newImg);
	  //  }
		imagedestroy($img);
		return false;
	}
	
function wsubstr($str, $length = 0, $suffixStr = "...", $start = 0, $tags = "div|span|p", $zhfw = 0.9, $charset = "utf-8"){
$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
$zhre['utf-8']   = "/[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
$zhre['gb2312'] = "/[\xb0-\xf7][\xa0-\xfe]/";
$zhre['gbk']    = "/[\x81-\xfe][\x40-\xfe]/";
$zhre['big5']   = "/[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
//下面代码还可以应用到关键字加亮、加链接等，可以避免截断HTML标签发生
//得到标签位置
$tpos = array();
preg_match_all("/<(".$tags.")([\s\S]*?)>|<\/(".$tags.")>/ism", $str, $match);
$mpos = 0;
for($j = 0; $j < count($match[0]); $j ++){
$mpos = strpos($str, $match[0][$j], $mpos);
$tpos[$mpos] = $match[0][$j];
$mpos += strlen($match[0][$j]);
}
ksort($tpos);
//根据标签位置解析整个字符
$sarr = array();
$bpos = 0;
$epos = 0;
foreach($tpos as $k => $v){
$temp = substr($str, $bpos, $k - $epos);
if(!empty($temp))array_push($sarr, $temp);
array_push($sarr, $v);
$bpos = ($k + strlen($v));
$epos = $k + strlen($v);
}
$temp = substr($str, $bpos);
if(!empty($temp))array_push($sarr, $temp);
//忽略标签截取字符串
$bpos = $start;
$epos = $length;
for($i = 0; $i < count($sarr); $i ++){
if(preg_match("/^<([\s\S]*?)>$/i", $sarr[$i]))continue;//忽略标签
preg_match_all($re[$charset], $sarr[$i], $match);
for($j = $bpos; $j < min($epos, count($match[0])); $j ++){
if(preg_match($zhre[$charset], $match[0][$j]))$epos -= $zhfw;//计算中文字符
}
$sarr[$i] = "";
for($j = $bpos; $j < min($epos, count($match[0])); $j ++){//截取字符
$sarr[$i] .= $match[0][$j];
}
$bpos -= count($match[0]);
$bpos = max(0, $bpos);
$epos -= count($match[0]);
$epos = round($epos);
} 
//返回结果
$slice = join("", $sarr);//自己可以加个清除空html标签的东东
if($slice != $str)return $slice.$suffixStr;
return $slice;
}	
?>