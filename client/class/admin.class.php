<?php
include("../include/mysql.class.php");
	///==========================================用户实体==========================================类
	class AdminInfo{
		public $id;
		public $username;
		public $password;
		public $realname;
		public $byname;
		public $areaNum;
		public $linkman;
		public $address;
		public $postcode;
		public $tel;
		public $fax;
		public $mobi;
		public $email;
		public $mainTel;
		public $sendLevel;
		public $voiceMoney;		
		public $faxMoney;
		public $messageMoney;
		public $alertMoney;
		public $ip;
		public $loginNum;
		public $LastLogin;
		public $cardType;
		public $cardNo;
		public $cardPic;
		public $faxIB;
		public $sendSound;
		public $ifDelInfo;
		public $state;
		public $upId;
		public $createTime;
		public $StatusId;
		public $FeeRate;
	}
	
 
	///==========================================用户操作类==========================================
	class AdminDal{
		
		public $conn;
		public $thisTable="t_user";
		public function __construct()
		{
			$this->conn=new mysql();
		}
		
		//析构
		public function __destruct()
		{
			 
		}
		
			///修改用户信息 HE
		public function Edit($AdminInfo)
		{
			try {  
				$table=$this->thisTable;
				$v="realname='".$AdminInfo->realname."',password='".$AdminInfo->password."',StatusId='".$AdminInfo->StatusId."'";
				$this->conn->update($table,$v," id=".$AdminInfo->id);
					return $AdminInfo->id;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
		
		
		///检查用户名是否存在
		public function CheckUser($username)
		{
			$table=$this->thisTable;
			$sql="select id from $table where username='$username'";
			//echo $sql;
			$result=$this->conn->query($sql);
			$count=$this->conn->num_rows();
			return $count;
			//echo $sql;
		}
		
	 
		
		//返回管理员实体
		public function GetModelByUserName($username)
		{
			$table=$this->thisTable;	
			try {  
				$nmodel= new AdminInfo();
				$sql="select * from $table where username='".$username."'" ;	
				return $this->conn->GetModel($sql,$nmodel);
			} catch (Exception $e) {  
					return NULL;
			}   	 
		}
		
		//返回管理员实体 HE
		public function GetModelByUserId($id)
		{
			$table=$this->thisTable;			
			try {  
				$nmodel= new AdminInfo();
				$sql="select * from $table where id='".$id."'" ;	
				return $this->conn->GetModel($sql,$nmodel);
			} catch (Exception $e) {  
					return NULL;
			}  		}
		
		
		///根据用户ID返回详情 
		public function GetUserNameByUserId($userid)
		{
			$table=$this->thisTable;
			$rss=array();
			try {  
				$sql="select * from $table where id='".$userid."'" ;	
				$result=$this->conn->query($sql);
				 while($row = $this->conn->fetch_array())
				{
					 $rss[]=$row; 
				}
				return $rss[0];
			} catch (Exception $e) {  
					return NULL;
			}   	 
		}
		
		
				///计算总数
		public function GetCount($sqlwhere='')
		{
			$table=$this->thisTable;
			$sql="select a.id from $table a  where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$result=$this->conn->query($sql);
			$num= $this->conn->num_rows();
			return $num;
		}
		
		
		
			///分页类 HE
		public function GetTreeList($sqlwhere='')
		{
			$table=$this->thisTable;
			$rss=array();
			
			$sql="select a.id,a.upId as pId,a.username as name  from $table  a  where   FIND_IN_SET( id, getChildLst(".$_SESSION["userid"].") )";
		 
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
		 
			//secho $sql;
			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rss[]=$row;
				
			 }
			 return $rss;
		}

		public function GetPageList($sqlwhere='',$page=1,$pagesize=20)
		{
			$table=$this->thisTable;
			$rss=array();
			$sql="select a.*,(select username from t_user where id=a.upId) as father  from $table  a  where 1=1 ";
		 
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql." limit $page,$pagesize";
		 
		 
			//secho $sql;
			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rss[]=$row;
				
			 }
			 return $rss;
		}
		
		
		//删除 HE
		public function Del($userid)
		{
			$table=$this->thisTable;
			try{
			//$w=" id =".$userid;
			if($_SESSION["userid"]==1)
			{
				$rss=array();
				$rsa=array();
				$w=" FIND_IN_SET( id, getChildLst($userid) ) ";
				
				$q="SELECT sum(voiceMoney) as m FROM `t_user` WHERE FIND_IN_SET(id, getChildLst($userid)) ";
				$result=$this->conn->query($q);			
				while($row= $this->conn->fetch_array())
				{
					 $rss[]=$row;
					
				}
				$money=$rss[0][0];		
				$q="SELECT upId as m FROM `t_user` WHERE id=$userid ";
				$result=$this->conn->query($q);
				while($row= $this->conn->fetch_array())
				{
					 $rsa[]=$row;
					
				}
				$fid=$rsa[0][0];	
				$q="update t_user set voiceMoney=voiceMoney+".$money." where id=".$fid;
				//echo $q;
				$result=$this->conn->query($q);
				$this->conn->delete($table,$w);
			}
			else
			{
				$result=$this->conn->query("update t_user set StatusId=-1 where FIND_IN_SET( id, getChildLst($userid) )");
			}
			return true;
			}
			catch (Exception $e) {  
					return false;
				}   
		}
		
	//登录 
		public function Login($username,$password)
		{
			$table=$this->thisTable;			
			$sql="select  username,StatusId from $table where username='$username' and password='$password'";
			$result=$this->conn->query($sql);
			$count=$this->conn->num_rows();
			if($count>0)
			{
				$result=$this->conn->query("update t_user set loginNum=IFNULL(loginNum,0)+1,LastLogin=now() where username='$username'");
			}
			return $count;
		}	 
		
 }
	
	 
?>