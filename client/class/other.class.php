<?php 
	///==========================================新闻实体==========================================类
	class OtherInfo{
		public $Id;
		public $Title;
		public $Content;
		public $Scontent;
		public $Times;
		public $OrderId;
		public $Bid;
		public $Sid;
		public $Des;
		public $KeyWords;
 
	}
	
	
	////==========================================文章分类实体类==========================================
	
	class OtherClassInfo{
		public $ClassName;
		public $ClassId;
		public $OrderId;
		public $Bid;
		public $Des;
		public $KeyWord;
		public $Content;
	}
	
	///==========================================文章分类操作类==========================================
	class OtherClass{
		
		public $conn;
		public function __construct()
		{
			$this->conn=new mysql();
			
		}
		
			//析构
		public function __destruct()
		{
			 
		}
		///添加新闻分类
		public function AddOtherClass($OtherClassInfo)
		{
			 
			$table="t_other_class";
			$v=$OtherClassInfo->ClassName ."','".$OtherClassInfo->OrderId."','".$OtherClassInfo->Content."','".$OtherClassInfo->Des."','".$OtherClassInfo->KeyWord."','". $OtherClassInfo->Bid;
			$this->conn->insert($table,"classname,orderid,content,des,keyword,bid",$v);
			return $this->conn->insert_id();
		}
		
	
		///修改新闻分类
		public function EditOtherClass($OtherClassInfo)
		{
		
			try {  
				$table="t_other_class";
				$v="classname='".$OtherClassInfo->ClassName."',orderid='".$OtherClassInfo->OrderId."',content='".$OtherClassInfo->Content."',des='".$OtherClassInfo->Des."',keyword='".$OtherClassInfo->KeyWord."',bid=". $OtherClassInfo->Bid;
				$this->conn->update($table,$v," Classid=".$OtherClassInfo->ClassId);
					return $OtherClassInfo->ClassId;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
		
		///返回实体
		public function GetClassModel($classid)
		{
		
			try {  
				$nmodel= new OtherClassInfo();
				$sql="select * from t_other_class where classId=".$classid ;	
				$result=$this->conn->query($sql);
				 
				while($row=$this->conn->fetch_array())
				{
				 $nmodel->ClassId=$row['ClassId'];
				 $nmodel->ClassName=$row['ClassName'];
				 $nmodel->Content=$row['Content'];
				 $nmodel->Bid=$row['Bid'];
				 $nmodel->KeyWord=$row['KeyWord'];
				 $nmodel->OrderId=$row['OrderId'];
				 $nmodel->Des=$row['Des'];
				 }
				return $nmodel;
			} catch (Exception $e) {  
					return NULL;
			}   	 
		}
		
		///返回类ID 和 父类ID
		public function GetClassId($classid)
		{
			$sql="select classid,bid from t_other_class where classid=".$classid;	
			$result=$this->conn->query($sql);
			$row= $this->conn->fetch_array();
			 
			 	$rss["classid"]=$row[0];
				$rss["bid"]=$row[1];
			 
			 return $rss;
		}
		
		
		///根据类ID，返回BID SID
		public function GetBidSid($classid)
		{
			$sql="select classid,bid from t_other_class where classid=".$classid;	
			$result=$this->conn->query($sql);
			$row= $this->conn->fetch_array();
			 	if($row[1]==0)
				{
					$rss["bid"]=$row[0];
					$rss["sid"]=$row[1];
				}
				else
				{
			 		$rss["bid"]=$row[1];
					$rss["sid"]=$row[0];
			 	}
			 return $rss;
		}
		
		
		///删除
		public function DelOtherClass($classid)
		{
			$t=" t_other_class";
			$w=" classid=".$classid;
			$this->conn->delete($t,$w); 
		}
		
		///是否有子类
		public function IfBid($classid)
		{
			$sql="select classid from t_other_class where bid=".$classid ;
			$result=$this->conn->query($sql);
			$count=$this->conn->num_rows();
			return $count;
		}
		
		
		///返回下拉菜单
		public function GetSelect($bid=0,$sid=0)
		{
			
			$str="<select name='bid' style='font-size:15px; color:#666; font-weight:bold; margin:5px;padding:15px;'>";
			$list=$this->GetClassList();
			
			if($sid!="0")
			{
				foreach($list as $rs)
				{
					$str=$str."<option  value='".$rs['ClassId']."' style='background:#efefef;'>". $rs['ClassName'] ."</option>";
					$list2=$this->GetClassList($rs['ClassId']);
					foreach($list2 as $rs2)
						{
							if ($sid==$rs2['ClassId'])
							{
								$s="selected=selected";
							}
							else
							{
								$s="";
							}
							$str=$str."<option  value='".$rs2['ClassId']."' ".$s." >&nbsp;&nbsp;|--". $rs2['ClassName'] ."</option>";
						}
				}
			}
			else
			{
							 
						
							foreach($list as $rs)
								{
									if ($sid==$rs['ClassId'])
										{
											$s="selected=selected";
										}
										else
										{
											$s="";
										}
									$str=$str."<option  value='".$rs['ClassId']."' style='background:#efefef;' ".$s.">". $rs['ClassName'] ."</option>";
									$list2=$this->GetClassList($rs['ClassId']);
									foreach($list2 as $rs2)
										{
											$str=$str."<option  value='".$rs2['ClassId']."' ".$s." >&nbsp;&nbsp;|--". $rs2['ClassName'] ."</option>";
										}
								}
			}
			$str=$str."</select>";
			return $str;
		}
		
		
		///返回分类列表
		public function GetClassList($bid=0)
		{
			$rss=array();
			$sql="select * from t_other_class where bid='".$bid."' order by orderid asc,classid desc";
			$result=$this->conn->query($sql);
			 while($row= $this->conn->fetch_array())
			 {
			 	$rss[]=$row;
			 }
			 return $rss;
			 
		}
		
		
			
		///返回分类列表
		public function GetClassListByClassid($classid=0)
		{
			$rss=array();
			$sql="select * from t_other_class where classid='".$classid."' order by orderid asc,classid desc";
			$result=$this->conn->query($sql);
			 while($row= $this->conn->fetch_array())
			 {
			 	$rss[]=$row;
			 }
			 return $rss;
			 
		}
		
		///返回最大的排序ID
		public function GetMaxOrderId()
		{
		
			$sql="select max(orderid) from t_other_class";	
			$result=$this->conn->query($sql);
			$rs=$this->conn->fetch_array();
			$orderid = $rs[0];
			return $orderid;
		}
		
		///根据类ID返回 类名
		public function GetClassName($ClassId)
		{
			$sql="select classname from t_other_class where classId=".$ClassId ;	
			$result=$this->conn->query($sql);
			$row=$this->conn->fetch_array();
			$classname = $row[0];
			return $classname;
		}
		
		
	}
	
	/////==========================================新闻操作类//==========================================
	/////===============================================================================================
	class Other{
	
		public function __construct()
		{
			$this->conn=new mysql();
			
		}
		
		
		///添加文章
		public function AddOther($OtherInfo){
			$table="t_other";
 
			$v=$OtherInfo->Title ."','";
			$v.=$OtherInfo->Content."','";
			$v.=$OtherInfo->Scontent."','";
			$v.=$OtherInfo->Times."','";
			$v.=$OtherInfo->OrderId."','";
			$v.=$OtherInfo->Bid."','";
			$v.=$OtherInfo->Sid."','";
			$v.=$OtherInfo->Des."','";
			$v.=$OtherInfo->KeyWords;
 
			$this->conn->insert($table,"Title,Content,Scontent,Times,OrderId,Bid,Sid,Des,KeyWords",$v);
			return $this->conn->insert_id();	
		}
		
		
		///修改
		public function EditOther($OtherInfo)
		{
 
			$v="title='$OtherInfo->Title',";
			$v.="Content='$OtherInfo->Content',";
			$v.="Scontent='$OtherInfo->Scontent',";
			$v.="Times='$OtherInfo->Times',";
			$v.="OrderId='$OtherInfo->OrderId',";
			$v.="Bid='$OtherInfo->Bid',";
			$v.="Sid='$OtherInfo->Sid',";
			$v.="Des='$OtherInfo->Des',";
			$v.="KeyWords='$OtherInfo->KeyWords'";

			 
			try {  
				$table="t_other";
				$this->conn->update($table,$v," id=".$OtherInfo->Id);
					return $OtherInfo->Id;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
		//删除
		public function DelOther($id)
		{
			try{
			$t=" t_other";
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
			$sql="select id from t_other a  where 1=1 ";
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
			$sql.='select id,title,times from t_other where 1=1   ';
			if($bid!=0)
			{
			 $sql.=" and bid=$bid ";
			}
			
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
			$sql="select a.*,b.classname as bname,c.classname as sname from t_other  a ";
			$sql=$sql." left join t_other_class b on b.classid=a.bid ";
			$sql=$sql." left join t_other_class c on c.classid=a.sid ";
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
				 
				$sql="select * from t_other where id=".$newsid ;	
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
		
		public function GetContent($newsid,$bid)
		{
			$rss=array();
			try {  
				 
				 if ($newsid==0)
				 {
				 	$sql="select * from t_other where bid=".$bid." limit 1" ;
				 }
				 else
				 {
					$sql="select * from t_other where id=".$newsid ;
				}
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