<?php
	///==========================================用户实体==========================================类
	class WorkDetailInfo{
		public $id;
		public $UserId;
		public $WorkId;
		public $TelNo;
		public $Receiver;
		public $SendTime;
		public $TimeLength;
		public $SendNum;
		public $Money;
		public $SendResult;
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
	} 
	///==========================================用户操作类==========================================
	class WorkDetailDal{
		
		public $conn;
		public $thisTable="t_work_detail";
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
				$nmodel= new WorkInfo();
				$sql="select * from $table where id='".$id."'" ;	
				return $this->conn->GetModel($sql,$nmodel);
			} catch (Exception $e) {  
					return NULL;
			}  		}
		
				
				///计算总数
		public function GetCount($sqlwhere='')
		{
			$table=$this->thisTable;
			$sql="select a.id,b.Title  from $table  a,t_work b  where a.WorkId=b.id ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$result=$this->conn->query($sql);
			$num= $this->conn->num_rows();
			return $num;
		}
				///计算总数
		public function GetHisCount($sqlwhere='')
		{
			$table="t_his_work_detail";
			$sql="select a.id,b.Title  from $table  a,t_his_work b  where a.WorkId=b.id ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$result=$this->conn->query($sql);
			$num= $this->conn->num_rows();
			return $num;
		}
		
		
		
			///分页类 HE
		public function GetPageList($sqlwhere='',$page=0,$pagesize=20)
		{
			$table=$this->thisTable;
			$rss=array();
			$sql="select a.*,b.Title,b.WorkNo,b.Level,b.VoiceFile,case when IFNULL(a.SendResult,0)=1 then '已发送' else '未发送' end as SendText,REPLACE(a.KeyPress,'T','无') as KeyText  from $table  a,t_work b  where a.WorkId=b.id  ";
		 
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
		
			///分页类 HE
		public function GetHisPageList($sqlwhere='',$page=0,$pagesize=20)
		{
			$table="t_his_work_detail";
			$rss=array();
			$sql="select a.*,b.Title,b.WorkNo,b.Level,b.VoiceFile,case when IFNULL(a.SendResult,0)=1 then '已发送' else '未发送' end as SendText,REPLACE(a.KeyPress,'T','无') as KeyText  from $table  a,t_his_work b  where a.WorkId=b.id  ";
		 
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
		public function DelbyWorkId($id)
		{
			$table=$this->thisTable;
			try{
			$w=" WorkId =".$id;
			 
			$this->conn->delete($table,$w); 
			return true;
			}
			catch (Exception $e) {  
					return false;
				}   
		}

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
		
		
 }
	
	 
?>