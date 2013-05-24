<?php 
	///========================================== 实体==========================================类
	class MessageInfo{
		public $Id;
		public $UserId;
		public $UserName;
		public $Content;
		public $NewsId;
		public $ReTime;
		public $ReUserName;
		public $Times;
		public $RePlay;
 
	}
	
 
 


	
	///==========================================留言操作类==========================================
	class Message{
		
		public $conn;
		public function __construct()
		{
			$this->conn=new mysql();
		}
			//析构
		public function __destruct()
		{
			 
		}
 
		///删除
		public function DelMessage($id)
		{
			$t=" t_message";
			$w=" id in ($id)";
			$this->conn->delete($t,$w); 
		}
		
	 
		
		
		
		///删除
		public function DelMessages($id)
		{
			$t=" t_message_replay";
			$w=" id in ($id)";
			$this->conn->delete($t,$w); 
		}
 		
 
	 
		
			///计算总数
		public function GetCount($sqlwhere='')
		{
			$sql="select id from t_message a  where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$result=$this->conn->query($sql);
			$num= $this->conn->num_rows();
			return $num;
		}
		
 		
		
		///修改新闻分类
		public function AddReplay($MessageInfo)
		{
		
			try {
				$table="t_message_alert";
				$v=$MessageInfo->Id;
				$this->conn->insert($table,"messageId",$v);
				$this->conn->update("t_message","state=1","id=".$MessageInfo->Id);
				  
				$table="t_message";
				$v="replay='".$MessageInfo->RePlay."',retime='".$MessageInfo->ReTime."',reusername='".$MessageInfo->ReUserName."'";
				$this->conn->update($table,$v," id=".$MessageInfo->Id);

					return $MessageInfo->Id;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
 		///修改新闻回复数
		public function AddHits($newsid)
		{
		
			try {  
				$table="t_news";
				$v="hits=hits+1";
				$this->conn->update($table,$v," id=".$newsid);
					return $newsid;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
				
		///分页类
		public function GetPageList($sqlwhere='',$page=1,$pagesize=20)
		{
		
			$rss=array();
			$sql="select  a.*,b.title  from t_message  a left join t_news b on b.id=a.newsid ";
		 
			$sql=$sql." where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql." order by id desc limit $page,$pagesize";
			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rss[]=$row;
			 }
			 return $rss;
		}
		
		
 	///返回分类列表
		public function Gethf($sql)
		{
			$rss=array(); 
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
				 
				$sql="select * from t_message where id=".$newsid ;	
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