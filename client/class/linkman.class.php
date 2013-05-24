<?php
	///==========================================用户实体==========================================类
	class LinkmanInfo{
		public $id;
		public $UserId;
		public $GroupId;
		public $Linkman;
		public $Company;
		public $Dept;
		public $Position;
		public $Country;
		public $Province;
		public $City;
		public $Address;
		public $PostCode;
		public $Fax;
		public $Tel;
		public $HomeTel;
		public $Mobi;
		public $Email;
		public $Url;
		public $Description;
		public $CreateDate;
	} 
	///==========================================用户操作类==========================================
	class LinkmanDal{
		
		public $conn;
		public $thisTable="t_linkman";
		public function __construct()
		{
			$this->conn=new mysql();
		}
		
		//析构
		public function __destruct()
		{
			 
		}
				
		//返回管理员实体 HE
		public function GetModelById($id)
		{
			$table=$this->thisTable;			
			try {  
				$nmodel= new LinkmanInfo();
				$sql="select * from $table where id='".$id."'" ;	
				return $this->conn->GetModel($sql,$nmodel);
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
		public function GetListByGroupId($groupId)
		{
			$table=$this->thisTable;
			$rss=array();
			$sql="select a.*  from $table a where GroupId=$groupId ";
		 
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql." ";
		 
		 
			//secho $sql;
			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rss[]=$row;
				
			 }
			 return $rss;
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
		public function Del($id,$groupId)
		{
			$table=$this->thisTable;
			try{
			$w=" id =".$id;
			 
			$this->conn->delete($table,$w);
			$result=$this->conn->query("update t_addressgroup set GroupCount=(select count(*) from t_linkman where groupid=$groupId) where id='$groupId'");
			return true;
			}
			catch (Exception $e) {  
					return false;
				}   
		}

		public function Dels($id,$groupId)
		{
			$table=$this->thisTable;
			try{
			$w=" id in(".$id.")";
			 
			$this->conn->delete($table,$w);
			$result=$this->conn->query("update t_addressgroup set GroupCount=(select count(*) from t_linkman where groupid=$groupId) where id='$groupId'");
			 
			return true;
			}
			catch (Exception $e) {  
					return false;
				}   
		}
		
		
 }
	
	 
?>