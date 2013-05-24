<?php
	///==========================================用户实体==========================================类
	class WorkInfo{
		public $id;
		public $UserId;
		public $WorkNo;
		public $WorkType;
		public $SendTime;
		public $WorkCount;
		public $OverCount;
		public $SuccessCount;
		public $Money;
		public $WorkState;
		public $AddressSource;
		public $AddressGroupId;
		public $AddressFile;
		public $Title;
		public $SendTimeType;
		public $FixedTime;
		public $IfEndTime;
		public $EndTime;
		public $Level;
		public $WorkTimeSH1;
		public $WorkTimeSM1;
		public $WorkTimeEH1;
		public $WorkTimeEM1;
		public $WorkTimeSH2;
		public $WorkTimeSM2;
		public $WorkTimeEH2;
		public $WorkTimeEM2;
		public $IfVoiceTemplate;
		public $VoiceTemplateId;
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
		public $IfFax;
		public $FaxFile;
		public $IfMessage;
		public $IfMessageS1;
		public $IfMessageS2;
		public $IfMessageS3;
		public $IfMessageS4;
		public $IfMessageS5;
		public $IfMessageS6;
		public $Message;
		public $CreateDate;
		public $ComplainAgents;
		public $AddressText;
	} 
	///==========================================用户操作类==========================================
	class WorkDal{
		
		public $conn;
		public $thisTable="t_work";
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
			} 
		}
		
		public function GetHisModelById($id)
		{
			$table="t_his_work";			
			try {  
				$nmodel= new WorkInfo();
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
			
			$sql="select sum(allcount) from (";
			$sql.="select count(*) as allcount from t_work a where 1=1 ";
		 
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql."  union ";
		 
		 
			$sql.="select count(*) as allcount from t_his_work a where 1=1 ";
		 
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql." ";
			$sql.=") a ";
			
			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rss[]=$row;
				
			 }
			 return $rss[0][0];			
		}
		
		
		
			///分页类 HE
		public function GetPageList($sqlwhere='',$page=1,$pagesize=20)
		{
			$table=$this->thisTable;
			$rss=array();
			$sql="select a.*,d.ClassName as WorkState,c.username  from $table  a  left join t_user c on c.id=a.UserId left join t_dic d on d.ClassType='20' and d.ClassId=a.WorkState where 1=1 ";
		 
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql." order by id desc limit $page,$pagesize";
		 
		 
			//secho $sql;
			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rss[]=$row;
				
			 }
			 return $rss;
		}
		
		public function ProcessWorkList($sqlwhere)
		{
			$baktime=date('YmdHis'.$_SESSION["userid"],time());
			$table=$this->thisTable;
			$rss=array();
			$sql="INSERT INTO t_his_work (`id`, `UserId`, `WorkNo`, `WorkType`, `SendTime`, `WorkCount`, `WorkState`, `AddressSource`, `AddressGroupId`, `AddressFile`, `Title`, `SendTimeType`, `FixedTime`, `IfEndTime`, `EndTime`, `Level`, `WorkTimeSH1`, `WorkTimeSM1`, `WorkTimeEH1`, `WorkTimeEM1`, `WorkTimeSH2`, `WorkTimeSM2`, `IfVoiceTemplate`, `VoiceTemplateId`, `VoiceType`, `VoiceFile`, `TTS`, `IfClick`, `RepeatNum`, `ReturnNum`, `ComplainNum`, `ReturnVoiceType`, `ReturnVoiceFile`, `ReturnTTS`, `IfFax`, `FaxFile`, `Ifmessage`, `IfMessageS1`, `IfMessageS2`, `IfMessageS3`, `IfMessageS4`, `IfMessageS5`, `IfMessageS6`, `Message`, `CreateDate`, `WorkTimeEH2`, `WorkTimeEM2`, `ComplainAgents`, `OverCount`, `SuccessCount`, `Money`,baktime) select a.id, a.UserId, `WorkNo`, `WorkType`, a.SendTime, `WorkCount`,'3' as WorkState, `AddressSource`, `AddressGroupId`, `AddressFile`, `Title`, `SendTimeType`, `FixedTime`, `IfEndTime`, `EndTime`, `Level`, `WorkTimeSH1`, `WorkTimeSM1`, `WorkTimeEH1`, `WorkTimeEM1`, `WorkTimeSH2`, `WorkTimeSM2`, `IfVoiceTemplate`, `VoiceTemplateId`, `VoiceType`, `VoiceFile`, `TTS`, `IfClick`, `RepeatNum`, `ReturnNum`, `ComplainNum`, `ReturnVoiceType`, `ReturnVoiceFile`, `ReturnTTS`, `IfFax`, `FaxFile`, `Ifmessage`, `IfMessageS1`, `IfMessageS2`, `IfMessageS3`, `IfMessageS4`, `IfMessageS5`, `IfMessageS6`, `Message`, `CreateDate`, `WorkTimeEH2`, `WorkTimeEM2`, `ComplainAgents`,OverCount,sum(IFNULL(b.SendResult,0)) as SuccessCount,sum(IFNULL(b.Money,0)) as Money,'$baktime' as baktime from t_work a left join t_work_detail b on b.WorkId=a.id where OverCount=WorkCount "; // WorkState=3
		 
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql." group by a.id, a.UserId, `WorkNo`, `WorkType`, a.SendTime, `WorkCount`, `WorkState`, `AddressSource`, `AddressGroupId`, `AddressFile`, `Title`, `SendTimeType`, `FixedTime`, `IfEndTime`, `EndTime`, `Level`, `WorkTimeSH1`, `WorkTimeSM1`, `WorkTimeEH1`, `WorkTimeEM1`, `WorkTimeSH2`, `WorkTimeSM2`, `IfVoiceTemplate`, `VoiceTemplateId`, `VoiceType`, `VoiceFile`, `TTS`, `IfClick`, `RepeatNum`, `ReturnNum`, `ComplainNum`, `ReturnVoiceType`, `ReturnVoiceFile`, `ReturnTTS`, `IfFax`, `FaxFile`, `Ifmessage`, `IfMessageS1`, `IfMessageS2`, `IfMessageS3`, `IfMessageS4`, `IfMessageS5`, `IfMessageS6`, `Message`, `CreateDate`, `WorkTimeEH2`, `WorkTimeEM2`, `ComplainAgents`,OverCount,baktime";
		 
		 
			//secho $sql;
			$result=$this->conn->query($sql);
			if($result==1)
			{
				$sql="INSERT INTO `t_his_work_detail` (`id`, `UserId`, `WorkId`, `TelNo`, `Receiver`, `SendTime`, `TimeLength`, `SendNum`, `Money`, `SendResult`, `CreateTime`, `Linkman`, `Company`, `Dept`, `Position`, `Country`, `Province`, `City`, `Address`, `PostCode`, `Fax`, `Tel`, `HomeTel`, `Mobi`, `Email`, `Url`, `Description`, `KeyPress`,dialstatus,dialtime) select `id`, `UserId`, `WorkId`, `TelNo`, `Receiver`, `SendTime`, `TimeLength`, `SendNum`, `Money`, `SendResult`, `CreateTime`, `Linkman`, `Company`, `Dept`, `Position`, `Country`, `Province`, `City`, `Address`, `PostCode`, `Fax`, `Tel`, `HomeTel`, `Mobi`, `Email`, `Url`, `Description`,`KeyPress`,dialstatus,dialtime from t_work_detail where WorkId in (select id from t_his_work where baktime='$baktime')";	
				$result=$this->conn->query($sql);
				if($result==1)
				{
					$sql="delete from t_work_detail where WorkId in (select id from t_his_work where baktime='$baktime')";		
					$result=$this->conn->query($sql);
					$sql="delete from t_work where id in (select id from t_his_work where baktime='$baktime')";
					$result=$this->conn->query($sql);				
				}
			}
			return $result;
		}		

		public function GetDetailTotal($sqlwhere='')
		{
			$sql="select count(*) as WorkCount,sum(IFNULL(SendTime,0)) as OverCount,sum(IFNULL(b.SendResult,0)) as SuccessCount,sum(IFNULL(b.Money,0)) as Money from  t_work_detail b  where 1=1 ".$sqlwhere;		
				
		}

		public function GetPageListSum($sqlwhere='',$page=1,$pagesize=20,$orderby='')
		{
			$table=$this->thisTable;
			$rss=array();
			$rsa=array();
			$sql="select count(*) from t_work a where OverCount=WorkCount $sqlwhere";
			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rsa[]=$row;
				
			 }
			 if($rsa[0][0]>0)
			 {	
				$this->ProcessWorkList($sqlwhere);
			 }
			$sql="select WorkNo,WorkType,StateId,username,Title,SendTime,WorkCount,OverCount,WorkState,VoiceFile,a.UserId,a.id,a.FixedTime,his from (";
			$sql.="select WorkNo,WorkType,WorkState as StateId,username,Title,CreateDate as SendTime,WorkCount,OverCount,d.ClassName as WorkState,VoiceFile,a.UserId,a.id,a.FixedTime,'0' as his from t_work a left join t_user c on c.id=a.UserId left join t_dic d on d.ClassType='20' and d.ClassId=a.WorkState where 1=1 ";
			//$sql="select WorkNo,WorkType,StateId,username,Title,SendTime,WorkCount,OverCount,SuccessCount,Money,WorkState,VoiceFile,a.UserId,a.id,a.FixedTime,his from (";
			//$sql.="select WorkNo,WorkType,WorkState as StateId,username,Title,CreateDate as SendTime,WorkCount,OverCount,sum(IFNULL(b.SendResult,0)) as SuccessCount,sum(IFNULL(b.Money,0)) as Money,d.ClassName as WorkState,VoiceFile,a.UserId,a.id,a.FixedTime,'0' as his   from t_work a left join t_work_detail b on b.WorkId=a.id left join t_user c on c.id=a.UserId left join t_dic d on d.ClassType='20' and d.ClassId=a.WorkState where 1=1 ";		 
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql."  union ";
		 	//$sql=$sql." group by WorkNo,WorkType,StateId,username,Title,CreateDate,WorkCount,OverCount,WorkState,VoiceFile,a.UserId,a.id,a.FixedTime union ";
		 
			$sql.="select WorkNo,WorkType,WorkState as StateId,username,Title,CreateDate as SendTime,WorkCount,a.OverCount,d.ClassName as WorkState,VoiceFile,a.UserId,a.id,a.FixedTime,'1' as his   from t_his_work a left join t_his_work_detail b on b.WorkId=a.id left join t_user c on c.id=a.UserId left join t_dic d on d.ClassType='20' and d.ClassId=a.WorkState where 1=1 ";
			
			//$sql.="select WorkNo,WorkType,WorkState as StateId,username,Title,CreateDate as SendTime,WorkCount,a.OverCount,a.SuccessCount,a.Money,d.ClassName as WorkState,VoiceFile,a.UserId,a.id,a.FixedTime,'1' as his   from t_his_work a left join t_his_work_detail b on b.WorkId=a.id left join t_user c on c.id=a.UserId left join t_dic d on d.ClassType='20' and d.ClassId=a.WorkState where 1=1 ";
		 
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql." ";
			//$sql=$sql." group by WorkNo,WorkType,StateId,username,Title,CreateDate,WorkCount,WorkState,VoiceFile,a.UserId,a.id,a.FixedTime";
			$sql.=") a  $orderby limit $page,$pagesize";
			//secho $sql;
			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rss[]=$row;
				
			 }
			 return $rss;
		}		
		
		public function GetPageListAll($sqlwhere='',$page=1,$pagesize=20,$orderby='')
		{
			$table=$this->thisTable;
			$rss=array();
			$rsa=array();
			$sql="select count(*) from t_work a where OverCount=WorkCount $sqlwhere";
			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rsa[]=$row;
				
			 }
			 if($rsa[0][0]>0)
			 {	
				$this->ProcessWorkList($sqlwhere);
			 }
			$sql="select WorkNo,WorkType,StateId,username,Title,SendTime,WorkCount,OverCount,WorkState,VoiceFile,a.UserId,a.id,a.FixedTime,his from (";
			$sql.="select WorkNo,WorkType,WorkState as StateId,username,Title,CreateDate as SendTime,WorkCount,OverCount,d.ClassName as WorkState,VoiceFile,a.UserId,a.id,a.FixedTime,'0' as his   from t_work a left join t_user c on c.id=a.UserId left join t_dic d on d.ClassType='20' and d.ClassId=a.WorkState where 1=1 ";
		 
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql." union ";
		 
		 
			$sql.="select WorkNo,WorkType,WorkState as StateId,username,Title,CreateDate as SendTime,WorkCount,a.OverCount,d.ClassName as WorkState,VoiceFile,a.UserId,a.id,a.FixedTime,'1' as his   from t_his_work a left join t_user c on c.id=a.UserId left join t_dic d on d.ClassType='20' and d.ClassId=a.WorkState where 1=1 ";
		 
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql.=") a  $orderby limit $page,$pagesize";
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
			$this->conn->delete("t_his_work",$w); 
			$this->conn->delete("t_work_detail"," WorkId=$id"); 
			$this->conn->delete("t_his_work_detail"," WorkId=$id"); 
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
			$this->conn->delete("t_his_work",$w);
			$this->conn->delete("t_work_detail"," WorkId in(".$id.")");
			$this->conn->delete("t_his_work_detail"," WorkId in(".$id.")");  
			return true;
			}
			catch (Exception $e) {  
					return false;
				}   
		}		
		
		//删除 HE
		public function DelHis($id)
		{
			$table="t_his_work";
			try{
			$w=" id =".$id;
			 
			$this->conn->delete($table,$w); 
			
			$this->conn->delete("t_his_work_detail"," WorkId=$id"); 
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
			 
			$result=$this->conn->query("update t_work set WorkState=0 where ".$w);
			return true;
			}
			catch (Exception $e) {  
					return false;
				}   
		}
		
 }
	
	 
?>