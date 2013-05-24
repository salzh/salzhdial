<?php
	///==========================================用户实体==========================================类
	class AddressGroupInfo{
		public $id;
		public $UserId;
		public $GroupNo;
		public $GroupType;
		public $GroupName;
		public $GroupCount;
		public $ProcessState;
		public $CreateDate;
	}
	
	///==========================================用户操作类==========================================
	class AddressGroupDal{
		
		public $conn;
		public $thisTable="t_addressgroup";
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
		public function CheckName($GroupName,$UserId)
		{
			$table=$this->thisTable;
			$sql="select id from $table where GroupName='$GroupName' and UserId=$UserId";
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
		public function GetModelById($id)
		{
			$table=$this->thisTable;			
			try {  
				$nmodel= new AddressGroupInfo();
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
		public function GetPageList($sqlwhere='',$page=1,$pagesize=20)
		{
			$table=$this->thisTable;
			$rss=array();
			$sql="select a.*  from $table  a  where 1=1 ";
		 
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
		public function Del($id)
		{
			$table=$this->thisTable;
			try{
			$w=" id =".$id;
			 
			$this->conn->delete($table,$w); 
			return true;
			}
			catch (Exception $e) {  
					return false;
				}   
		}

		public function Dels($id)
		{
			$table=$this->thisTable;
			try{
			$w=" id in(".$id.")";
			 
			$this->conn->delete($table,$w); 
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
			$sql="select  username from $table where StatusId=1 and username='$username' and password='$password'";
			$result=$this->conn->query($sql);
			$count=$this->conn->num_rows();
			if($count>0)
			{
				$result=$this->conn->query("update t_user set loginNum=IFNULL(loginNum,0)+1,LastLogin=now() where username='$username'");
			}
			return $count;
		}	 
		
		public function SetLinkmanNum($id)
		{
			$table=$this->thisTable;			
	
			$result=$this->conn->query("update $table set GroupCount=(select count(*) from t_linkman where groupid=$id) where id='$id'");

			return 1;
		}			
 }
	
	 
?>