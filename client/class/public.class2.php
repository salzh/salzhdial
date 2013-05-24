<?php 

	
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
		public function AddMessage($content,$username,$newsid){
			$table="t_message";
 
			$v=$content ."','";
			$v.=$username."','";
			$v.=$newsid."','";
			$v.=date('Y-m-d h:m:s')."','";
			$v.=0;
			$this->conn->insert($table,"content,username,newsid,times,userid",$v);
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
 
	 
	}
	
  
 ?>