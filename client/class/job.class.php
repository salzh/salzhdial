<?php 
	///==========================================新闻实体==========================================类
	class JobInfo{
		public $Id;
		public $JobName;
		public $Work;
		public $Notes;
		public $Addtime;
		public $IsUsed;
		public $OrderId;
	}
 

 
 
	/////===============================================================================================
	class Job{
		public function __construct()
		{
			$this->conn=new mysql();
		}
		 //析构
		public function __destruct()
		{
		}
	
		///添加文章
		public function Add($JobInfo){
			$table="t_jobs";
 
			$v=$JobInfo->JobName ."','";
			$v.=$JobInfo->Work."','";
			$v.=$JobInfo->Notes."','";
			$v.=$JobInfo->Addtime."','";
			$v.=$JobInfo->OrderId."','";
			$v.=$JobInfo->IsUsed;
			$this->conn->insert($table,"JobName,Work,Notes,Addtime,OrderId,IsUsed",$v);
			return $this->conn->insert_id();	
		}
		
		
		///修改
		public function Edit($JobInfo)
		{
 
			$v="jobname='$JobInfo->JobName',";
			$v.="work='$JobInfo->Work',";
			$v.="notes='$JobInfo->Notes',";
			$v.="addtime='$JobInfo->AddTime',";
			$v.="orderid='$JobInfo->OrderId',";
			$v.="isused='$JobInfo->IsUsed'";
 
				try {  
					$table="t_jobs";
					$this->conn->update($table,$v," id=".$JobInfo->Id);
					return $JobInfo->Id;
				} 
				catch (Exception $e) {  
					return 0;
				}   
		}
		
		//删除
		public function Del($id)
		{
			try{
			$t=" t_jobs";
			$w=" id in(".$id.")";
			$this->conn->delete($t,$w); 
			return true;
			}
			catch (Exception $e) {  
					return false;
				}   
		}
		
			///计算总数
		public function GetCount($sqlwhere='')
		{
			$sql="select id from t_jobs a  where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$result=$this->conn->query($sql);
			$num= $this->conn->num_rows();
			return $num;
		}
		
	
		public function Getlist($bid,$start=0,$top=20,$sqlwhere='')
		{
			$rss=array();
			$sql='';
			$sql.='select * from t_jobs where 1=1   ';
			 
			if($sqlwhere!='')
			{
			$sql.=" and ".$sqlwhere;
			}
			 
			$sql.=" order by orderid asc,id desc limit $start,$top ";
 
			$result=$this->conn->query($sql);
			while($row = $this->conn->fetch_array())
			{
				 $rss[]=$row;
			}
			return $rss;
		}
		
		
		///分页类
		public function GetPageList($sqlwhere='',$page=1,$pagesize=20)
		{
		
			$rss=array();
			$sql="select a.*  from t_jobs  a ";
			 
			$sql=$sql." where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql." limit $page,$pagesize";
			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rss[]=$row;
				
			 }
			 return $rss;
		}
		
		
		public function GetModel($newsid)
		{
			$rss=array();
			try {  
				 
				$sql="select * from t_jobs where id=".$newsid ;	
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
}
 
	  
?>