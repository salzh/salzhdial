<?php
	///==========================================用户实体==========================================类
	class VoiceTemplateInfo{
		public $id;
		public $UserId;
		public $TemplateName;
		public $VoiceType;
		public $VoiceFile;
		public $TTS;
		public $IfClick;
		public $RepeatNum;
		public $ReturnNum;
		public $ComplainNum;
		public $ReturnVoiceType;
		public $ReturnVoiceFile;
		public $ReturnTTS;
		public $CreateDate;
		public $ComplainAgents;
		public $Auditing;
	} 
	///==========================================用户操作类==========================================
	class VoiceTemplateDal{
		
		public $conn;
		public $thisTable="t_voice_template";
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
				$nmodel= new VoiceTemplateInfo();
				$sql="select * from $table where id='".$id."'" ;	
				return $this->conn->GetModel($sql,$nmodel);
			} catch (Exception $e) {  
					return NULL;
			}  		}
		
				
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
			$sql="select a.*,d.ClassName as AuditingName from $table  a left join t_dic d on d.ClassType=22 and a.Auditing=d.ClassId where 1=1 ";
		 
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
		
		public function AllPass($id)
		{
			$table=$this->thisTable;
			try{
			$w=" id in(".$id.")";
			 
			$result=$this->conn->query("update t_voice_template set Auditing=1 where ".$w);
			return true;
			}
			catch (Exception $e) {  
					return false;
				}   
		}
 }
	
	 
?>