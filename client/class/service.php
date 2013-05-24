<?php 
	///==========================================服务实体==========================================类
	class ServiceInfo{
		public $Id;
		public $Title;
		public $Content;
		public $Scontent;
		public $Author;
		public $Times;
		public $Froms;
		public $Indexs;
		public $OrderId;
		public $Shows;
		public $Recommand;
		public $Hits;
		public $Hot;
		public $Bid;
		public $Sid;
		public $Des;
		public $KeyWords;
		public $Pic;
		public $Pic_s;
		public $LikeWords;
	}
	
	
	////==========================================文章分类实体类==========================================
	
	class ServiceClassInfo{
		public $ClassName;
		public $ClassId;
		public $OrderId;
		public $Bid;
		public $Des;
		public $KeyWord;
		public $Content;
	}
	
	///==========================================文章分类操作类==========================================
	class ServiceClass{
		
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
		public function AddServiceClass($ServiceClassInfo)
		{
			 
			$table="t_service_class";
			$v=$ServiceClassInfo->ClassName ."','".$ServiceClassInfo->OrderId."','".$ServiceClassInfo->Content."','".$ServiceClassInfo->Des."','".$ServiceClassInfo->KeyWord."','". $ServiceClassInfo->Bid;
			$this->conn->insert($table,"classname,orderid,content,des,keyword,bid",$v);
			return $this->conn->insert_id();
		}
		
	
		///修改新闻分类
		public function EditServiceClass($ServiceClassInfo)
		{
		
			try {  
				$table="t_service_class";
				$v="classname='".$ServiceClassInfo->ClassName."',orderid='".$ServiceClassInfo->OrderId."',content='".$ServiceClassInfo->Content."',des='".$ServiceClassInfo->Des."',keyword='".$ServiceClassInfo->KeyWord."',bid=". $ServiceClassInfo->Bid;
				$this->conn->update($table,$v," Classid=".$ServiceClassInfo->ClassId);
					return $ServiceClassInfo->ClassId;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
		
		///返回实体
		public function GetClassModel($classid)
		{
		
			try {  
				$nmodel= new ServiceClassInfo();
				$sql="select * from t_service_class where classId=".$classid ;	
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
			$sql="select classid,bid from t_service_class where classid=".$classid;	
			$result=$this->conn->query($sql);
			$row= $this->conn->fetch_array();
			 
			 	$rss["classid"]=$row[0];
				$rss["bid"]=$row[1];
			 
			 return $rss;
		}
		
		
		///根据类ID，返回BID SID
		public function GetBidSid($classid)
		{
			$sql="select classid,bid from t_service_class where classid=".$classid;	
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
		public function DelServiceClass($classid)
		{
			$t=" t_service_class";
			$w=" classid=".$classid;
			$this->conn->delete($t,$w); 
		}
		
		///是否有子类
		public function IfBid($classid)
		{
			$sql="select classid from t_service_class where bid=".$classid ;
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
			$sql="select * from t_service_class where bid='".$bid."' order by orderid asc,classid desc";
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
		
			$sql="select max(orderid) from t_service_class";	
			$result=$this->conn->query($sql);
			$rs=$this->conn->fetch_array();
			$orderid = $rs[0];
			return $orderid;
		}
		
		///根据类ID返回 类名
		public function GetClassName($ClassId)
		{
			$sql="select classname from t_service_class where classId=".$ClassId ;	
			$result=$this->conn->query($sql);
			$row=$this->conn->fetch_array();
			$classname = $row[0];
			return $classname;
		}
		
		
	}
	
	/////==========================================新闻操作类//==========================================
	/////===============================================================================================
	class Service{
	
		public function __construct()
		{
			$this->conn=new mysql();
			
		}
		
		
		///添加文章
		public function AddService($ServiceInfo){
			$table="t_service";
 
			$v=$ServiceInfo->Title ."','";
			$v.=$ServiceInfo->Content."','";
			$v.=$ServiceInfo->Scontent."','";
			$v.=$ServiceInfo->Author."','";
			$v.=$ServiceInfo->Times."','";
			$v.=$ServiceInfo->Froms."','";
			$v.=$ServiceInfo->Indexs."','";
			$v.=$ServiceInfo->OrderId."','";
			$v.=$ServiceInfo->Shows."','";
			$v.=$ServiceInfo->Recommand."','";
			$v.=$ServiceInfo->Hits."','";
			$v.=$ServiceInfo->Hot."','";
			$v.=$ServiceInfo->Bid."','";
			$v.=$ServiceInfo->Sid."','";
			$v.=$ServiceInfo->Des."','";
			$v.=$ServiceInfo->KeyWords."','";
			$v.=$ServiceInfo->Pic."','";
			$v.=$ServiceInfo->Pic_s."','";
			$v.=$ServiceInfo->LikeWords;
			
		//	echo $v;
			 
			$this->conn->insert($table,"Title,Content,Scontent,Author,Times,Froms,Indexs,OrderId,Shows,Recommand,Hits,Hot,Bid,Sid,Des,KeyWords,Pic,Pic_s,LikeWords",$v);
			return $this->conn->insert_id();	
		}
		
		
		///修改文章
		public function EditService($ServiceInfo)
		{
		 
 
			$v="title='$ServiceInfo->Title',";
			$v.="Content='$ServiceInfo->Content',";
			$v.="Scontent='$ServiceInfo->Scontent',";
			$v.="Author='$ServiceInfo->Author',";
			$v.="Times='$ServiceInfo->Times',";
			$v.="Froms='$ServiceInfo->Froms',";
			$v.="Indexs='$ServiceInfo->Indexs',";
			$v.="OrderId='$ServiceInfo->OrderId',";
			$v.="Shows='$ServiceInfo->Shows',";
			$v.="Recommand='$ServiceInfo->Recommand',";
			$v.="Hits='$ServiceInfo->Hits',";
			$v.="Hot='$ServiceInfo->Hot',";
			$v.="Bid='$ServiceInfo->Bid',";
			$v.="Sid='$ServiceInfo->Sid',";
			$v.="Des='$ServiceInfo->Des',";
			$v.="KeyWords='$ServiceInfo->KeyWords',";
			$v.="Pic='$ServiceInfo->Pic',";
			$v.=" pic_s = '$ServiceInfo->Pic_s',";
			$v.="LikeWords='$ServiceInfo->LikeWords'";
			 
			try {  
				$table="t_service";
				$this->conn->update($table,$v," id=".$ServiceInfo->Id);
					return $ServiceInfo->Id;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
		//删除
		public function DelService($id)
		{
			try{
			$t=" t_service";
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
			$sql="select id from t_service a  where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$result=$this->conn->query($sql);
			$num= $this->conn->num_rows();
			return $num;
		}
		
		
		//返回列表
		public function GetNext($id,$bid=0)
		{
			$sql="select id,title from t_service where bid=$bid and id<$id and shows='yes'  order by id desc limit 1";
			$result=$this->conn->query($sql);
			$row=$this->conn->fetch_array();
			if($row!=NULL)
			{
				$Service["id"] = $row[0];
				$Service["title"] = $row[1];
			}
			else
			{
				$Service["id"]=0;
				$Service["title"]=0;
			}
			return $Service;
		}
		
		
		public function Getlist($bid,$start=0,$top=5,$sqlwhere='')
		{
			$rss=array();
			$sql='';
			$sql.='select id,title,bid,pic,times,scontent from t_service where 1=1   ';
			if($bid!=0)
			{
			 $sql.=" and bid=$bid ";
			}
			
			if($sqlwhere!='')
			{
			$sql.=" and ".$sqlwhere;
			}
			if($bid==0)
			{
				$sql.=" order by hit desc,orderid asc,id desc limit $start,$top ";
			}
			else
			{
			$sql.=" order by orderid asc,id desc limit $start,$top ";
			}
 
			$result=$this->conn->query($sql);
			while($row = $this->conn->fetch_array())
			{
				 $rss[]=$row;
				 
			 }

			return $rss;

		}
		
		public function GetPrv($id,$bid=0)
		{
			$sql="select id,title from t_service where bid=$bid and id>$id and shows='yes'  order by id asc limit 1";
			$result=$this->conn->query($sql);
			$row=$this->conn->fetch_array();
			if($row!=NULL)
			{
				$Service["id"] = $row[0];
				$Service["title"] = $row[1];
			}
			else
			{
				$Service["id"]=0;
				$Service["title"]=0;
			}
			return $Service;
		}
		
		///分页类
		public function GetPageList($sqlwhere='',$page=1,$pagesize=20)
		{
		
			$rss=array();
			$sql="select a.*,b.classname as bname,c.classname as sname from t_service  a ";
			$sql=$sql." left join t_service_class b on b.classid=a.bid ";
			$sql=$sql." left join t_service_class c on c.classid=a.sid ";
			$sql=$sql." where 1=1 ";
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
		
		
		public function GetModel($Serviceid)
		{
			$rss=array();
			try {  
				 
				$sql="select * from t_service where id=".$Serviceid ;	
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