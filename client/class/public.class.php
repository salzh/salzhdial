<?php 
	date_default_timezone_set('PRC');
	
	///==========================================文章分类操作类==========================================
	class PublicClass{
		
		public $conn;
		public function __construct()
		{
			$this->conn=new mysql();
			
		}
		
			//析构
		public function __destruct()
		{
			 
		}
 
		
		///返回分类列表
		public function GetList($sql)
		{
			$rss=array(); 
			$result=$this->conn->query($sql);
			 while($row= $this->conn->fetch_array())
			 {
			 	$rss[]=$row;
			 }
			 return $rss;
			 
		}
		
		///执行插入
		public function processSQL($sql)
		{
			$result=$this->conn->query($sql);
			 return 1;
			 
		}		
			
	 
		public function GetTopId($sql)
		{
			$t=0;	 
 			$result=$this->conn->query($sql);
			$row=$this->conn->fetch_array();
			if($row!=NULL)
			{
				$t= $row[0];
				 
			}
			return $t;
		}
		
		
			///计算总数
		public function GetCount($sqlwhere='')
		{
		 
			$result=$this->conn->query($sqlwhere);
			$num= $this->conn->num_rows();
			return $num;
		}
		
		
			///添加文章
		public function AddMessage($content,$username,$rename,$title,$newsid,$areaid,$depid,$typeid){
			$table="t_message";
 
			$v=$content ."','";
			$v.=$username."','";
			$v.=$rename."','";
			$v.=$title."','";
			$v.=$newsid."','";
			$v.=date('Y-m-d H:i:s')."','";
			$v.="0','";
			$v.=$areaid."','";
			$v.=$depid."','";
			$v.=$typeid;
			
			$tmp=$this->AddNewsMsgnum($newsid);			
			$this->conn->insert($table,"content,username,`rename`,title,newsid,times,userid,areaid,depid,typeid",$v);
			return $this->conn->insert_id();	
		}
		
		
		
		public function AddMessageRp($content,$username,$mid){
			$table="t_message_alert";
 
			$v=$mid;
			$this->conn->insert($table,"messageId",$v);
			$this->conn->update("t_message","state=1","id=$mid");
			$table="t_message_replay";
 
			$v=$content ."','";
			$v.=$username."','";
			$v.=$mid."','";
			$v.=date('Y-m-d H:i:s');
			$this->conn->insert($table,"title,username,mid,addtime",$v);
			return $this->conn->insert_id();	
		}
		
			///添加文章
		public function AddPMessage($content,$username,$rename,$title,$newsid,$areaid,$depid,$typeid){
			$table="t_product_message";
 
			$v=$content ."','";
			$v.=$username."','";
			$v.=$rename."','";
			$v.=$title."','";
			$v.=$newsid."','";
			$v.=date('Y-m-d H:i:s')."','";
			$v.="0','";
			$v.=$areaid."','";
			$v.=$depid."','";
			$v.=$typeid;
					
			$this->conn->insert($table,"content,username,`rename`,title,newsid,times,userid,areaid,depid,typeid",$v);
			return $this->conn->insert_id();	
		}
		
		
		
		public function AddPMessageRp($content,$username,$mid){

			$this->conn->update("t_message","state=1","id=$mid");
			$table="t_product_message_replay";
 
			$v=$content ."','";
			$v.=$username."','";
			$v.=$mid."','";
			$v.=date('Y-m-d H:i:s');
			$this->conn->insert($table,"title,username,mid,addtime",$v);
			return $this->conn->insert_id();	
		}		
		
		public function GetPageList($sqlwhere='',$page=1,$pagesize=20)
		{
			$rss=array();
			$sql=$sqlwhere;
			$sql=$sql." order by id desc limit $page,$pagesize";
			//secho $sql;
			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rss[]=$row;
				
			 }
			 return $rss;
		}
		
		
		
		
		public function GetTitle($tablename,$filename,$whereid,$id=0)
		{
			$t="";	 
 			$result=$this->conn->query("select ".$filename." from ".$tablename ." where ".$whereid."=".$id);
 			$row=$this->conn->fetch_array();
			if($row!=NULL)
			{
				$t= $row[0];
				 
			}
			return $t;
		}
		
 		///修改用户登陆数
		public function AddLoginnum($uname)
		{
		
			try {  
				$table="t_user";
				$v="loginnum=loginnum+1";
				$this->conn->update($table,$v," email='".$uname."'");
					return 1;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}			

		public function AddMsgnum($uname)
		{
		
			try {  
				$table="t_user";
				$v="msgnum=msgnum+1";
				$this->conn->update($table,$v," email='".$uname."'");
					return 1;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}			

		public function AddReMsgnum($uname)
		{
		
			try {  
				$table="t_user";
				$v="remsgnum=remsgnum+1";
				$this->conn->update($table,$v," email='".$uname."'");
					return 1;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}		

		public function AddUserHit($uname)
		{
		
			try {  
				$table="t_user";
				$v="hits=hits+1";
				$this->conn->update($table,$v," email='".$uname."'");
					return 1;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}			

 		///修改新闻留言数
		public function AddNewsMsgnum($newsid)
		{
		
			try {  
				$table="t_news";
				$v="msgnum=msgnum+1";
				$this->conn->update($table,$v," id=".$newsid);
				$this->AddMsgnum($_SESSION['usermail']);
					return $newsid;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
 		///修改新闻回复数
		public function AddNewsReMsgNum($newsid)
		{
		
			try {  
				$table="t_news";
				$v="remsgnum=remsgnum+1";
				$this->conn->update($table,$v," id=".$newsid);
				$this->AddReMsgnum($_SESSION['usermail']);
					return $newsid;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
						
 		///修改新闻点击数
		public function AddHit($newsid)
		{
		
			try {  
				$table="t_news";
				$v="hit=hit+1";
				$this->conn->update($table,$v," id=".$newsid);
				$this->AddUserHit($_SESSION['usermail']);
					return $newsid;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
 		///修改新闻点击数
		public function AddUrlHit($url,$thisurl)
		{
		
			try {  
				$table="t_click_total";
				$this->conn->insert($table,"userid,url,thisurl,times","".$_SESSION['usermail']."','".$url."','".$thisurl."','".date('Y-m-d H:i:s')."");
				return $this->conn->insert_id();
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
 		///修改新闻点击数
		public function AddFavoriteHit($infoId,$newsId,$tableName)
		{
		
			try {  
					$table="t_favorite";
					$tsum=$this->GetTopId("select count(*) from ".$table." where userid='".$_SESSION['usermail']."' and infoid='".$infoId."' and newsid='".$newsId."' and tablename='".$tableName."'");
					if($tsum==0)
					{
						$this->conn->insert($table,"userid,infoId,newsId,tablename,createDate","".$_SESSION['usermail']."','".$infoId."','".$newsId."','".$tableName."','".date('Y-m-d H:i:s')."");
						return $this->conn->insert_id();
					}
					return $tsum;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		public function DelFavoriteHit($infoId,$newsId,$tableName)
		{
		
			try {  
				$table="t_favorite";
				$result=$this->conn->query("delete from ".$table." where userid='".$_SESSION['usermail']."' and infoid='".$infoId."' and newsid='".$newsId."' and tablename='".$tableName."'");
				return 1;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}		
				
		
		public function AddNewsHit($newsId)
		{
			$rsn=$this->GetModel("select bid,sid from t_news where id=".$newsId); 
					  $bid= $rsn[0]['bid'];
					  $sid=$rsn[0]['sid'];
			try {  
				$table="t_total_news";
				$this->conn->insert($table,"userid,newsid,bid,sid","".$_SESSION['usermail']."','$newsId','".$bid."','".$sid."");
				return $this->conn->insert_id();
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}				
		
		public function GetModel($sql)
		{
			$rss=array();
			try {  
 				$result=$this->conn->query($sql);
				while($row=$this->conn->fetch_array())
				{
					 $rss[]=$row;
				}
				return $rss;
			} catch (Exception $e) {  
					return NULL;
			}   	 
		}
 
 
 		///返回分类列表
		public function GetClassList($table)
		{
			$rss=array();
			$sql="select * from t_".$table." order by classId asc";
			 
			$result=$this->conn->query($sql);
			 while($row= $this->conn->fetch_array())
			 {
			 	$rss[]=$row;
			 }
			 return $rss;
			 
		}
		
		public function GetClassListSub($table,$upid)
		{
			$rss=array();
			$sql="select * from t_".$table." where upid=".$upid." order by idx asc";
			 
			$result=$this->conn->query($sql);
			 while($row= $this->conn->fetch_array())
			 {
			 	$rss[]=$row;
			 }
			 return $rss;
			 
		}		
 
		///返回下拉菜单
		public function GetSelect($id=0,$table)
		{
			$str="<select name='cmb".$table."' style='font-size:15px; color:#666; font-weight:bold; margin:5px;padding:15px;'>";
			$list=$this->GetClassList($table);
			
			if($id=="0")
			{
				foreach($list as $rs)
				{
					$str=$str."<option  value='".$rs['classId']."' style='background:#efefef;'>". $rs['className'] ."</option>";
				}
			}
			else
			{
				foreach($list as $rs)
					{
						if ($id==$rs['classId'])
							{
								$s="selected=selected";
							}
							else
							{
								$s="";
							}
						$str=$str."<option  value='".$rs['classId']."' style='background:#efefef;' ".$s.">". $rs['className'] ."</option>";
					}
			}
			$str=$str."</select>";
			return $str;
		}
	 
	}
	


  
$area_list="";
$dep_list="";
$rolesql="";

if($_SESSION['usermail']!=NULL && $_SESSION['usermail']!="")
{
	$connOjb= new PublicClass();
	$res=$connOjb->GetList(" select * from t_user where email='".$_SESSION['usermail']."'");
	if($res!=NULL)
	{
		$area_list=$res[0]["Region"];
		$dep_list=$res[0]["Channel"];
		$_SESSION['area']=$area_list;
		$_SESSION['dep']=$dep_list;
		if(strtolower($area_list)!=strtolower("All"))
		{
			$rolesql=$rolesql." and AreaId like '%".$area_list."%' " ;
		}
		if(strtolower($dep_list)!=strtolower("All"))
		{
			 $rolesql=$rolesql." and DepId  like '%".$dep_list."%' " ;
		}
		setcookie("theuser", $_SESSION['usermail'] , time()+36000);
	}
	else
	{
		header("Location:nouser.html");
	exit;
	}
	if($_SESSION['iflogin']==1)
	{
		$tmp=$connOjb->AddLoginnum($_SESSION['usermail']);
		$_SESSION['iflogin']=0;
	}
}
else
{
	header("Location:ucdlogin.php");
	exit;
}


?>