<?php 
	///==========================================新闻实体==========================================类
	class NewsInfo{
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
		public $ClassId;
		public $AreaId;
		public $DepId;
		public $BBid;
		public $SSid;
		public $TypesId;
	}
	
	
	////==========================================文章分类实体类==========================================
	
	class NewsClassInfo{
		public $ClassName;
		public $ClassId;
		public $OrderId;
		public $Bid;
		public $Des;
		public $KeyWord;
		public $Content;
	}
	
	///==========================================文章分类操作类==========================================
	class NewsClass{
		
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
		public function AddNewsClass($NewsClassInfo)
		{
			 
			$table="t_news_class";
			$v=$NewsClassInfo->ClassName ."','".$NewsClassInfo->OrderId."','".$NewsClassInfo->Content."','".$NewsClassInfo->Des."','".$NewsClassInfo->KeyWord."','". $NewsClassInfo->Bid;
			$this->conn->insert($table,"classname,orderid,content,des,keyword,bid",$v);
			return $this->conn->insert_id();
		}
		
	
		///修改新闻分类
		public function EditNewsClass($NewsClassInfo)
		{
		
			try {  
				$table="t_news_class";
				$v="classname='".$NewsClassInfo->ClassName."',orderid='".$NewsClassInfo->OrderId."',content='".$NewsClassInfo->Content."',des='".$NewsClassInfo->Des."',keyword='".$NewsClassInfo->KeyWord."',bid=". $NewsClassInfo->Bid;
				$this->conn->update($table,$v," Classid=".$NewsClassInfo->ClassId);
					return $NewsClassInfo->ClassId;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
		
		///返回实体
		public function GetClassModel($classid)
		{
		
			try {  
				$nmodel= new NewsClassInfo();
				$sql="select * from t_news_class where classId=".$classid ;	
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
		
		
		
		
		///返回品牌下拉
		public function GetBrandSelect($type="add",$bid=0,$sid=0,$tid=0)
		{ 
			$str="<select name='brandid' style='font-size:15px; color:#666; font-weight:bold; margin:5px;padding:15px;'>";
			$list=$this->GetBrandClassList();
			
			if($type=="add") //添加
			{
				foreach($list as $rs)
				{
					$str=$str."<option  value='".$rs['ClassId']."' style='background:#efefef;'>". $rs['ClassName'] ."</option>";
					$list2=$this->GetBrandClassList($rs['ClassId']);
					foreach($list2 as $rs2)
						{
							
							$str=$str."<option  value='".$rs2['ClassId']."'   >&nbsp;&nbsp;|--". $rs2['ClassName'] ."</option>";
							$list3=$this->GetBrandClassList($rs2['ClassId']);
							foreach($list3 as $rs3)
							{
								$str=$str."<option  value='".$rs3['ClassId']."'   >&nbsp;&nbsp;&nbsp;&nbsp;└──". $rs3['ClassName'] ."</option>";
							}
						}
				}
			}
			else
			{			
			if($type=='all')
			{
				$str=$str."<option  value='0' style='background:#efefef;'>全部</option>";	
			}
							foreach($list as $rs)
								{
									if ($bid==$rs['ClassId'] && $sid==0 && $tid==0)
										{
											$s="selected=selected";
										}
										else
										{
											$s="";
										}
									$str=$str."<option  value='".$rs['ClassId']."' style='background:#efefef;' ".$s.">". $rs['ClassName'] ."</option>";
									$list2=$this->GetBrandClassList($rs['ClassId']);
									foreach($list2 as $rs2)
										{
											if ($sid==$rs2['ClassId']    && $tid==0)
												{
													$s2="selected=selected";
												}
												else
												{
													$s2="";
												}
												$str=$str."<option  value='".$rs2['ClassId']."' ".$s2." >&nbsp;&nbsp;|--". $rs2['ClassName'] ."</option>";
													$list3=$this->GetBrandClassList($rs2['ClassId']);
													 
													foreach($list3 as $rs3)
													{
														if ($tid==$rs3['ClassId'])
														{
															$s3="selected=selected";
														}
														else
														{
															$s3="";
														}
														$str=$str."<option  value='".$rs3['ClassId']."'  ".$s3." >&nbsp;&nbsp;&nbsp;&nbsp;└──". $rs3['ClassName'] ."</option>";
													}
										}
								}
			}
			$str=$str."</select>";
			return $str;
		}
		
		
		
			///根据类ID，返回BID SID
		public function GetBrandBidSid($classid)
		{
			$sql="select classid,bid from t_product_class where classid=".$classid;	
			$result=$this->conn->query($sql);
			$row= $this->conn->fetch_array();
			 	if($row[1]==0)
				{
					$rss["bid"]=$row[0];
					$rss["sid"]=0;
					$rss["tid"]=0;
				}
				else 
				{
					$sql1="select classid,bid from t_product_class where classid=".$row[1];	
					$result1=$this->conn->query($sql1);
					$row1= $this->conn->fetch_array();
					if($row1['bid']>0)
					{
							
								$rss["bid"]=$row1['bid'];
								$rss["sid"]=$row['bid'];
								$rss["tid"]=$row[0];
					}
					else
					{
						$rss["bid"]=$row[1];
						$rss["sid"]=$row[0];
						$rss["tid"]=0;
					}
						
			 		
			 	}
			 return $rss;
		}
		
		
		
		///返回类ID 和 父类ID
		public function GetClassId($classid)
		{
			$sql="select classid,bid from t_news_class where classid=".$classid;	
			$result=$this->conn->query($sql);
			$row= $this->conn->fetch_array();
			 
			 	$rss["classid"]=$row[0];
				$rss["bid"]=$row[1];
			 
			 return $rss;
		}
		
		
		///根据类ID，返回BID SID
		public function GetBidSid($classid)
		{
			$sql="select classid,bid from t_news_class where classid=".$classid;	
			$result=$this->conn->query($sql);
			$row= $this->conn->fetch_array();
			 	if($row[1]==0)
				{
					$rss["bbid"]=$row[0];
					$rss["ssid"]=0;
				}
				else
				{
			 		$rss["bbid"]=$row[1];
					$rss["ssid"]=$row[0];
			 	}
			 return $rss;
		}
		
		
		///删除
		public function DelNewsClass($classid)
		{
			$t=" t_news_class";
			$w=" classid=".$classid;
			$this->conn->delete($t,$w); 
		}
		
		///是否有子类
		public function IfBid($classid)
		{
			$sql="select classid from t_news_class where bid=".$classid ;
			$result=$this->conn->query($sql);
			$count=$this->conn->num_rows();
			return $count;
		}
		
		
		public function GetChkList($bid=0)
		{
			$str=""; 
			$list=$this->GetClassList();
			if($bid!="0")
			{
				foreach($list as $rs)
				{
 						if(strrpos(",".$bid.",", ",".$rs["ClassId"].",")>-1)
						{
						  $str=$str."<input name='bid[]' type='checkbox' id='bid'  checked='checked' value='".$rs['ClassId']."' /> "  . $rs['ClassName'] ."";
						}
						else
						{
						  $str=$str."<input name='bid[]' type='checkbox' id='bid'   value='".$rs['ClassId']."' /> "  . $rs['ClassName'] ."";
						}
				}
			}
			else
			{
				foreach($list as $rs)
				{
					$str=$str."<input name='bid[]' type='checkbox' id='bid'   value='".$rs['ClassId']."' /> "  . $rs['ClassName'] ."";
				}
			}
			return $str;	
		}
		
		
		
		public function GetDepList($bid=0)
		{
			$str=""; 
			 $list= $this->GetDep();
			if($bid!="0")
			{
				foreach($list as $rs)
				{
 						if(strrpos(",".$bid.",", ",".$rs["ClassName"].",")>-1)
						{
						  $str=$str."<input name='depid[]' type='checkbox' id='depid'  checked='checked' value='".$rs['ClassName']."' /> "  . $rs['ClassName'] ."&nbsp;&nbsp;";
						}
						else
						{
						  $str=$str."<input name='depid[]' type='checkbox' id='depid'   value='".$rs['ClassName']."' /> "  . $rs['ClassName'] ." &nbsp;&nbsp;";
						}
				}
			}
			else
			{
				foreach($list as $rs)
				{
					
					$str=$str."<input name='depid[]' type='checkbox'  value='".$rs['ClassName']."' /> "  . $rs['ClassName'] ."&nbsp;&nbsp;";
				}
			}
			return $str;	
		}
		
		
		
		
		public function GetAreaList($bid=0)
		{
			$str=""; 
			 $list= $this->GetArea();
			if($bid!="0")
			{
				foreach($list as $rs)
				{
 						if(strrpos(",".$bid.",", ",".$rs["ClassName"].",")>-1)
						{
						  $str=$str."<input name='areaid[]' type='checkbox' id='areaid'  checked='checked' value='".$rs['ClassName']."' /> "  . $rs['ClassName'] ."&nbsp;&nbsp;";
						}
						else
						{
						  $str=$str."<input name='areaid[]' type='checkbox' id='areaid'   value='".$rs['ClassName']."' /> "  . $rs['ClassName'] ." &nbsp;&nbsp;";
						}
				}
			}
			else
			{
				foreach($list as $rs)
				{
					
					$str=$str."<input name='areaid[]' type='checkbox'  value='".$rs['ClassName']."' /> "  . $rs['ClassName'] ."&nbsp;&nbsp;";
				}
			}
			return $str;	
		}
		
		
		
		
		///返回区域
		public function GetAreaSelect($bid=0)
		{
			
			$list=array();
			$sqls="select * from t_area_class where bid='0' order by orderid asc,classid desc";
			$results=$this->conn->query($sqls);
			 $list[]= $this->conn->fetch_array();
			$str="<select name='areaid' style='font-size:15px; color:#666; font-weight:bold; margin:5px;padding:15px;'>";
			if($bid!="0")
			{
				foreach($list as $rs)
				{
				
						if ($bid==$rs['ClassId'])
						{
							$s="selected=selected";
						}
						else
						{
							$s="";
						}
					$str=$str."<option  value='".$rs['ClassId']."' style='background:#efefef;'  ".$s.">". $rs['ClassName'] ."</option>";
				}
			}
			else
			{
				foreach($list as $rs)
				{
					$str=$str."<option  value='".$rs['ClassId']."' style='background:#efefef;'>". $rs['ClassName'] ."</option>";	 
				}
			}
			$str=$str."</select>";
			return $str;
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
			$sql="select * from t_news_class where bid=".$bid." order by orderid asc,classid desc";
			 
			$result=$this->conn->query($sql);
			 while($row= $this->conn->fetch_array())
			 {
			 	$rss[]=$row;
			 }
			 return $rss;
			 
		}
		
		
		
			///返回分类列表
		public function GetBrandClassList($bid=0)
		{
			$rss=array();
			$sql="select * from t_manage_product where manageid='".$_SESSION["userid"]."'";
			$result=$this->conn->query($sql);
			$sfield="";
			 while($row= $this->conn->fetch_array())
			 {
			 	$sfield.=$row['productid'].",";
			 }
			if($bid==0&&strlen($sfield)>0)
			{
				$sql="select * from t_product_class where bid=0 and classid in (".substr($sfield,0,strlen($sfield)-1).") order by orderid asc,classid desc";
			}
			else
			{
				$sql="select * from t_product_class where bid='".$bid."' order by orderid asc,classid desc";
			}
			$result=$this->conn->query($sql);
			 while($row= $this->conn->fetch_array())
			 {
			 	$rss[]=$row;
			 }
			 return $rss;
			 
		}
		
		
				///返回分类列表
		public function GetDep($bid=0)
		{
			$rss=array();
			$sql="select * from t_dep_class where bid='".$bid."' order by orderid asc,classid desc";
			$result=$this->conn->query($sql);
			 while($row= $this->conn->fetch_array())
			 {
			 	$rss[]=$row;
			 }
			 return $rss;
			 
		}
		
		
			
				///返回分类列表
		public function GetArea($bid=0)
		{
			$rss=array();
			$sql="select * from t_area_class where bid='".$bid."' order by orderid asc,classid desc";
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
		
			$sql="select max(orderid) from t_news_class";	
			$result=$this->conn->query($sql);
			$rs=$this->conn->fetch_array();
			$orderid = $rs[0];
			return $orderid;
		}
		
		///根据类ID返回 类名
		public function GetClassName($ClassId)
		{
			$sql="select classname from t_news_class where classId=".$ClassId ;	
			$result=$this->conn->query($sql);
			$row=$this->conn->fetch_array();
			$classname = $row[0];
			return $classname;
		}
		
		
	}
	
	/////==========================================新闻操作类//==========================================
	/////===============================================================================================
	class News{
	
		public function __construct()
		{
			$this->conn=new mysql();
			
		}
		
		
		///添加文章
		public function AddNews($NewsInfo){
			$table="t_news";
 
			$v=$NewsInfo->Title ."','";
			$v.=$NewsInfo->Content."','";
			$v.=$NewsInfo->Scontent."','";
			$v.=$NewsInfo->Author."','";
			$v.=$NewsInfo->Times."','";
			$v.=$NewsInfo->Froms."','";
			$v.=$NewsInfo->Indexs."','";
			$v.=$NewsInfo->OrderId."','";
			$v.=$NewsInfo->Shows."','";
			$v.=$NewsInfo->Recommand."','";
			$v.=$NewsInfo->Hits."','";
			$v.=$NewsInfo->Hot."','";
			$v.=$NewsInfo->Bid."','";
			$v.=$NewsInfo->Sid."','";
			$v.=$NewsInfo->Tid."','";
			$v.=$NewsInfo->Des."','";
			$v.=$NewsInfo->KeyWords."','";
			$v.=$NewsInfo->Pic."','";
			$v.=$NewsInfo->Pic_s."','";
			$v.=$NewsInfo->ClassId."','";
			$v.=$NewsInfo->BBid."','";
			$v.=$NewsInfo->SSid."','";
			$v.=$NewsInfo->DepId."','";
			$v.=$NewsInfo->AreaId."','";
			$v.=$NewsInfo->TypesId."','";
			$v.=$NewsInfo->LikeWords;
			
		//	echo $v;
			 
			$this->conn->insert($table,"Title,Content,Scontent,Author,Times,Froms,Indexs,OrderId,Shows,Recommand,Hits,Hot,Bid,Sid,Tid,Des,KeyWords,Pic,Pic_s,ClassId,BBID,SSID,DepId,AreaId,TypesId,LikeWords",$v);
			return $this->conn->insert_id();	
		}
		
		
		///修改文章
		public function EditNews($NewsInfo)
		{
		 
 
			$v="title='$NewsInfo->Title',";
			$v.="Content='$NewsInfo->Content',";
			$v.="Scontent='$NewsInfo->Scontent',";
			$v.="Author='$NewsInfo->Author',";
			$v.="Times='$NewsInfo->Times',";
			$v.="Froms='$NewsInfo->Froms',";
			$v.="Indexs='$NewsInfo->Indexs',";
			$v.="OrderId='$NewsInfo->OrderId',";
			$v.="Shows='$NewsInfo->Shows',";
			$v.="Recommand='$NewsInfo->Recommand',";
			$v.="Hits='$NewsInfo->Hits',";
			$v.="Hot='$NewsInfo->Hot',";
			$v.="Bid='$NewsInfo->Bid',";
			$v.="Sid='$NewsInfo->Sid',";
			$v.="Tid='$NewsInfo->Tid',";
			$v.="Des='$NewsInfo->Des',";
			$v.="KeyWords='$NewsInfo->KeyWords',";
			$v.="Pic='$NewsInfo->Pic',";
			$v.=" pic_s = '$NewsInfo->Pic_s',";
			$v.=" BBid = '$NewsInfo->BBid',";
			$v.=" SSid = '$NewsInfo->SSid',";
			$v.=" AreaId = '$NewsInfo->AreaId',";
			$v.=" DepId = '$NewsInfo->DepId',";
			$v.=" TypesId = '$NewsInfo->TypesId',";
			$v.="LikeWords='$NewsInfo->LikeWords'";
			 
			try {  
				$table="t_news";
				$this->conn->update($table,$v," id=".$NewsInfo->Id);
					return $NewsInfo->Id;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
		//删除
		public function DelNews($id)
		{
			try{
			$t=" t_news";
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
			$sql="select id from t_news a  where 1=1 ";
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
			$sql="select id,title from t_news where bid=$bid and id<$id and shows='yes' order by id desc limit 1";
			$result=$this->conn->query($sql);
			$row=$this->conn->fetch_array();
			if($row!=NULL)
			{
				$news["id"] = $row[0];
				$news["title"] = $row[1];
			}
			else
			{
				$news["id"]=0;
				$news["title"]=0;
			}
			return $news;
		}
		
	
		
		public function Getlist($bid,$start=0,$top=5,$sqlwhere='')
		{
			$rss=array();
			$sql='';
			$sql.='select id,title,bid,pic,times,scontent from t_news where 1=1   ';
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
			$sql="select id,title from t_news where bid=$bid and id>$id and shows='yes' order by id asc limit 1";
			$result=$this->conn->query($sql);
			$row=$this->conn->fetch_array();
			if($row!=NULL)
			{
				$news["id"] = $row[0];
				$news["title"] = $row[1];
			}
			else
			{
				$news["id"]=0;
				$news["title"]=0;
			}
			return $news;
		}
		
		///分页类
		public function GetPageList($sqlwhere='',$page=1,$pagesize=20)
		{
		
			$rss=array();
			$sql="select a.*,b.classname as bname,c.classname as sname,d.classname as tname from t_news  a ";
			$sql=$sql." left join t_product_class b on b.classid=a.bid ";
			$sql=$sql." left join t_product_class c on c.classid=a.sid ";
			$sql=$sql." left join t_product_class d on d.classid=a.tid ";
			$sql=$sql." where 1=1 ";
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
		
		
		public function GetModel($newsid)
		{
			$rss=array();
			try {  
				 
				$sql="select * from t_news where id=".$newsid ;	
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