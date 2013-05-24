<?php
	///==========================================用户实体==========================================类
	class MonitingInfo{
		public $host;
		public $livecalls;
		public $maxcalls;
	} 
	///==========================================用户操作类==========================================
	class MonitingDal{
		
		public $conn;
		public $thisTable="t_livetable";
		public function __construct()
		{
			$this->conn=new mysql();
		}
		
		//析构
		public function __destruct()
		{
			 
		}

		//返回管理员实体 HE
		public function GetHost()
		{
			$rss=array();
			$table=$this->thisTable;			
			try {  
				$sql="select * from $table" ;	
				$result=$this->conn->query($sql);
				while($row= $this->conn->fetch_array())
				{
					 $rss[]=$row;
					
				 }
				 return $rss;	
			} catch (Exception $e) {  
					return NULL;
			}
  		}				
		//返回管理员实体 HE
		public function GetModelByHost($host='')
		{
			$table=$this->thisTable;			
			try {  
				$nmodel= new MonitingInfo();
				if($host!='')
				{
					$sql="select * from $table where host='".$host."'" ;	
				}
				else
				{
					$sql="select * from $table limit 0,1" ;	
				}
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
		
			///分页类 HE
		public function GetTodayList($host)
		{
			$rss=array();
			$sql="select date_format(createtime,'%H'),sum(num) from t_livelog where host='".$host."' and DATEDIFF(createtime,CURDATE())=0 group by date_format(createtime, '%Y-%m-%d %H ')";
		 
		 
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
			$sql="INSERT INTO t_his_work (`id`, `UserId`, `WorkNo`, `WorkType`, `SendTime`, `WorkCount`, `WorkState`, `AddressSource`, `AddressGroupId`, `AddressFile`, `Title`, `SendTimeType`, `FixedTime`, `IfEndTime`, `EndTime`, `Level`, `WorkTimeSH1`, `WorkTimeSM1`, `WorkTimeEH1`, `WorkTimeEM1`, `WorkTimeSH2`, `WorkTimeSM2`, `IfVoiceTemplate`, `VoiceTemplateId`, `VoiceType`, `VoiceFile`, `TTS`, `IfClick`, `RepeatNum`, `ReturnNum`, `ComplainNum`, `ReturnVoiceType`, `ReturnVoiceFile`, `ReturnTTS`, `IfFax`, `FaxFile`, `Ifmessage`, `IfMessageS1`, `IfMessageS2`, `IfMessageS3`, `IfMessageS4`, `IfMessageS5`, `IfMessageS6`, `Message`, `CreateDate`, `WorkTimeEH2`, `WorkTimeEM2`, `ComplainAgents`, `OverCount`, `SuccessCount`, `Money`,baktime) select a.id, a.UserId, `WorkNo`, `WorkType`, a.SendTime, `WorkCount`, `WorkState`, `AddressSource`, `AddressGroupId`, `AddressFile`, `Title`, `SendTimeType`, `FixedTime`, `IfEndTime`, `EndTime`, `Level`, `WorkTimeSH1`, `WorkTimeSM1`, `WorkTimeEH1`, `WorkTimeEM1`, `WorkTimeSH2`, `WorkTimeSM2`, `IfVoiceTemplate`, `VoiceTemplateId`, `VoiceType`, `VoiceFile`, `TTS`, `IfClick`, `RepeatNum`, `ReturnNum`, `ComplainNum`, `ReturnVoiceType`, `ReturnVoiceFile`, `ReturnTTS`, `IfFax`, `FaxFile`, `Ifmessage`, `IfMessageS1`, `IfMessageS2`, `IfMessageS3`, `IfMessageS4`, `IfMessageS5`, `IfMessageS6`, `Message`, `CreateDate`, `WorkTimeEH2`, `WorkTimeEM2`, `ComplainAgents`,sum(case when IFNULL(b.SendTime,0)<>0 then 1 else 0 end) as OverCount,sum(IFNULL(b.SendResult,0)) as SuccessCount,sum(IFNULL(b.Money,0)) as Money,'$baktime' as baktime from t_work a left join t_work_detail b on b.WorkId=a.id where WorkState=3 ";
		 
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql." group by a.id, a.UserId, `WorkNo`, `WorkType`, a.SendTime, `WorkCount`, `WorkState`, `AddressSource`, `AddressGroupId`, `AddressFile`, `Title`, `SendTimeType`, `FixedTime`, `IfEndTime`, `EndTime`, `Level`, `WorkTimeSH1`, `WorkTimeSM1`, `WorkTimeEH1`, `WorkTimeEM1`, `WorkTimeSH2`, `WorkTimeSM2`, `IfVoiceTemplate`, `VoiceTemplateId`, `VoiceType`, `VoiceFile`, `TTS`, `IfClick`, `RepeatNum`, `ReturnNum`, `ComplainNum`, `ReturnVoiceType`, `ReturnVoiceFile`, `ReturnTTS`, `IfFax`, `FaxFile`, `Ifmessage`, `IfMessageS1`, `IfMessageS2`, `IfMessageS3`, `IfMessageS4`, `IfMessageS5`, `IfMessageS6`, `Message`, `CreateDate`, `WorkTimeEH2`, `WorkTimeEM2`, `ComplainAgents`,baktime";
		 
		 
			//secho $sql;
			$result=$this->conn->query($sql);
			if($result==1)
			{
				$sql="INSERT INTO `t_his_work_detail` (`id`, `UserId`, `WorkId`, `TelNo`, `Receiver`, `SendTime`, `TimeLength`, `SendNum`, `Money`, `SendResult`, `CreateTime`, `Linkman`, `Company`, `Dept`, `Position`, `Country`, `Province`, `City`, `Address`, `PostCode`, `Fax`, `Tel`, `HomeTel`, `Mobi`, `Email`, `Url`, `Description`, `KeyPress`) select `id`, `UserId`, `WorkId`, `TelNo`, `Receiver`, `SendTime`, `TimeLength`, `SendNum`, `Money`, `SendResult`, `CreateTime`, `Linkman`, `Company`, `Dept`, `Position`, `Country`, `Province`, `City`, `Address`, `PostCode`, `Fax`, `Tel`, `HomeTel`, `Mobi`, `Email`, `Url`, `Description`,`KeyPress` from t_work_detail where WorkId in (select id from t_his_work where baktime='$baktime')";	
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


		public function GetPageListSum($sqlwhere='',$page=1,$pagesize=20,$orderby='')
		{
			$table=$this->thisTable;
			$rss=array();
			$rsa=array();
			$sql="select count(*) from t_work a where a.WorkState=3 $sqlwhere";
			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rsa[]=$row;
				
			 }
			 if($rsa[0][0]>0)
			 {	
				$this->ProcessWorkList($sqlwhere);
			 }
			$sql="select WorkNo,WorkType,StateId,username,Title,SendTime,WorkCount,OverCount,SuccessCount,Money,WorkState,VoiceFile,a.UserId,a.id,a.FixedTime,his from (";
			$sql.="select WorkNo,WorkType,WorkState as StateId,username,Title,CreateDate as SendTime,WorkCount,sum(case when IFNULL(b.SendTime,0)<>0 then 1 else 0 end) as OverCount,sum(IFNULL(b.SendResult,0)) as SuccessCount,sum(IFNULL(b.Money,0)) as Money,d.ClassName as WorkState,VoiceFile,a.UserId,a.id,a.FixedTime,'0' as his   from t_work a left join t_work_detail b on b.WorkId=a.id left join t_user c on c.id=a.UserId left join t_dic d on d.ClassType='20' and d.ClassId=a.WorkState where 1=1 ";
		 
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql." group by WorkNo,WorkType,StateId,username,Title,CreateDate,WorkCount,WorkState,VoiceFile,a.UserId,a.id,a.FixedTime union ";
		 
		 
			$sql.="select WorkNo,WorkType,WorkState as StateId,username,Title,CreateDate as SendTime,WorkCount,a.OverCount,a.SuccessCount,a.Money,d.ClassName as WorkState,VoiceFile,a.UserId,a.id,a.FixedTime,'1' as his   from t_his_work a left join t_his_work_detail b on b.WorkId=a.id left join t_user c on c.id=a.UserId left join t_dic d on d.ClassType='20' and d.ClassId=a.WorkState where 1=1 ";
		 
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql." group by WorkNo,WorkType,StateId,username,Title,CreateDate,WorkCount,WorkState,VoiceFile,a.UserId,a.id,a.FixedTime";
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
			
			$this->conn->delete("t_work_detail"," WorkId=$id"); 
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