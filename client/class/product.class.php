<?php 
	///==========================================新闻实体==========================================类
	class ProductInfo{
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
		public $Cid;
		public $BuyContent;
		public $Jishu;
		
	}
	
	
	////========================================== 分类实体类==========================================
	
	class ProductClassInfo{
		public $ClassName;
		public $ClassId;
		public $OrderId;
		public $Bid;
		public $Des;
		public $KeyWord;
		public $Content;
		public $Pic;
	}
	
	///========================================== 分类操作类==========================================
	class ProductClass{
		
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
		public function AddProductClass($ProductClassInfo)
		{
			 
			$table="t_product_class";
			$v=$ProductClassInfo->ClassName ."','".$ProductClassInfo->OrderId."','".$ProductClassInfo->Content."','".$ProductClassInfo->Des."','".$ProductClassInfo->KeyWord."','".$ProductClassInfo->Bid."','". $ProductClassInfo->Pic;
			$this->conn->insert($table,"classname,orderid,content,des,keyword,bid,pic",$v);
			return $this->conn->insert_id();
		}
		
		
		
	
		
		
	
		///修改新闻分类
		public function EditProductClass($ProductClassInfo)
		{
		
			try {  
				$table="t_product_class";
				$v="classname='".$ProductClassInfo->ClassName."',orderid='".$ProductClassInfo->OrderId."',content='".$ProductClassInfo->Content."',des='".$ProductClassInfo->Des."',keyword='".$ProductClassInfo->KeyWord."',pic='".$ProductClassInfo->Pic."',bid=". $ProductClassInfo->Bid;
				$this->conn->update($table,$v," Classid=".$ProductClassInfo->ClassId);
					return $ProductClassInfo->ClassId;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
		
		///返回实体
		public function GetClassModel($classid)
		{
		
			try {  
				$nmodel= new ProductClassInfo();
				$sql="select * from t_product_class where classId=".$classid ;	
				$result=$this->conn->query($sql);
				 
				while($row=$this->conn->fetch_array())
				{
				 $nmodel->ClassId=$row['ClassId'];
				 $nmodel->ClassName=$row['ClassName'];
				 $nmodel->Content=$row['Content'];
				 $nmodel->Bid=$row['Bid'];
				 $nmodel->Pic=$row['pic'];
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
			$sql="select classid,bid from t_product_class where classid=".$classid;	
			$result=$this->conn->query($sql);
			$row= $this->conn->fetch_array();
			 
			 	$rss["classid"]=$row[0];
				$rss["bid"]=$row[1];
			 
			 return $rss;
		}
		
		
		///根据类ID，返回BID SID
		public function GetBidSid($classid)
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
		
		
		///删除
		public function DelProductClass($classid)
		{
			$t=" t_product_class";
			$w=" classid=".$classid;
			$this->conn->delete($t,$w); 
		}
		
		///是否有子类
		public function IfBid($classid)
		{
			$sql="select classid from t_product_class where bid=".$classid ;
			$result=$this->conn->query($sql);
			$count=$this->conn->num_rows();
			return $count;
		}
		
		
		///返回下拉菜单
		public function GetSelect($type="add",$bid=0,$sid=0,$tid=0)
		{
			
			$str="<select name='bid' style='font-size:15px; color:#666; font-weight:bold; margin:5px;padding:15px;'>";
			$list=$this->GetClassList();
			
			if($type=="add") //添加
			{
				foreach($list as $rs)
				{
					$str=$str."<option  value='".$rs['ClassId']."' style='background:#efefef;'>". $rs['ClassName'] ."</option>";
					$list2=$this->GetClassList($rs['ClassId']);
					foreach($list2 as $rs2)
						{
							
							$str=$str."<option  value='".$rs2['ClassId']."'   >&nbsp;&nbsp;|--". $rs2['ClassName'] ."</option>";
							$list3=$this->GetClassList($rs2['ClassId']);
							foreach($list3 as $rs3)
							{
								$str=$str."<option  value='".$rs3['ClassId']."'   >&nbsp;&nbsp;&nbsp;&nbsp;└──". $rs3['ClassName'] ."</option>";
							}
						}
				}
			}
			else
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
									$str=$str."<option  value='".$rs['ClassId']."' style='background:#efefef;' ".$s.">". $rs['ClassName'] ."</option>";
									$list2=$this->GetClassList($rs['ClassId']);
									foreach($list2 as $rs2)
										{
											if ($sid==$rs2['ClassId'])
												{
													$s2="selected=selected";
												}
												else
												{
													$s2="";
												}
												$str=$str."<option  value='".$rs2['ClassId']."' ".$s2." >&nbsp;&nbsp;|--". $rs2['ClassName'] ."</option>";
													$list3=$this->GetClassList($rs2['ClassId']);
													
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
		
		
		
		 
		
		
		///返回分类列表
		public function GetClassList($bid=0)
		{
			$rss=array();
			$sql="select * from t_product_class where bid='".$bid."' order by orderid asc,classid desc";
		 
			$result=$this->conn->query($sql);
			 while($row= $this->conn->fetch_array())
			 {
			 	$rss[]=$row;
			 }
			 return $rss;
			 
		}
		
		
		///返回分类列表
		public function GetClassListQx($bid=0)
		{
			$rss=array();
			$sql="select * from t_product_class where classid in(".$bid.") order by orderid asc,classid desc";
		 
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
		
			$sql="select max(orderid) from t_product_class";	
			$result=$this->conn->query($sql);
			$rs=$this->conn->fetch_array();
			$orderid = $rs[0];
			return $orderid;
		}
		
		///根据类ID返回 类名
		public function GetClassName($ClassId)
		{
			$sql="select classname from t_product_class where classId=".$ClassId ;	
			$result=$this->conn->query($sql);
			$row=$this->conn->fetch_array();
			$classname = $row[0];
			return $classname;
		}
		
		///根据类ID返回 类名
		public function GetClassContent($ClassId)
		{
			$sql="select Content from t_product_class where classId=".$ClassId ;	
			$result=$this->conn->query($sql);
			$row=$this->conn->fetch_array();
			$classname = $row[0];
			return $classname;
		}
		
		
			///根据类ID返回 类名
		public function GetClassPic($ClassId)
		{
			$sql="select Pic from t_product_class where classId=".$ClassId ;	
			$result=$this->conn->query($sql);
			$row=$this->conn->fetch_array();
			$classname = $row[0];
			return $classname;
		}
		
		
	}
	
	/////==========================================新闻操作类//==========================================
	/////===============================================================================================
	class Product{
	
		public function __construct()
		{
			$this->conn=new mysql();
			
		}
		
			///添加LOG
		public function AddLog($username,$pid,$productname,$notes)
		{
			$table="t_product_log";
			$v=date('Y-m-d H:m:s') ."','".$username."','".$pid."','".$productname."','".$notes."";
			$this->conn->insert($table,"times,username,pid,productname,notes",$v);
			return $this->conn->insert_id();
		}
		
		public  function AddKey($k)
		{
			$table="t_keyword";
			$v=$k;
			$this->conn->insert($table,"keyword",$v);
			return $this->conn->insert_id();	

		}
		///修改文章
		public function EditKey($k)
		{
		 
			$v="keyword='$k'";
			try {  
				$table="t_keyword";
				$this->conn->update($table,$v," id=".$ProductInfo->Id);
					return $ProductInfo->Id;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
		//删除
		public function DelKey($id)
		{
			try{
			$t=" t_keyword";
			$w=" id in(".$id.")";
			 
			$this->conn->delete($t,$w); 
			return true;
			}
			catch (Exception $e) {  
					return false;
				}   
		}
		
		
		
		
			///计算总数
		public function GetCountKey($sqlwhere='')
		{
			$sql="select id from t_keyword a  where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$result=$this->conn->query($sql);
			$num= $this->conn->num_rows();
			return $num;
		}
		
		
		
			///计算总数
		public function GetIsKey($keyword='')
		{
			$sql="select id from t_keyword a  where keyword='".$keyword."'";
			$result=$this->conn->query($sql);
			$num= $this->conn->num_rows();
			return $num;
		}
		
		
			///分页类
		public function GetPageListKey($sqlwhere='',$page=1,$pagesize=20)
		{
		
			$rss=array();
			$sql="select a.*  from t_keyword  a ";
			 
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
		
		public function GetModelKey($productid)
		{
			$rss=array();
			try {  
				 
				$sql="select * from t_keyword where id=".$productid ;	
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
		
		
		///=============================================================
		
		
		///添加文章
		public function AddProduct($ProductInfo){
			$table="t_product";
 
			$v=$ProductInfo->Title ."','";
			$v.=$ProductInfo->Content."','";
			$v.=$ProductInfo->Scontent."','";
			$v.=$ProductInfo->Author."','";
			$v.=$ProductInfo->Times."','";
			$v.=$ProductInfo->Froms."','";
			$v.=$ProductInfo->Indexs."','";
			$v.=$ProductInfo->OrderId."','";
			$v.=$ProductInfo->Shows."','";
			$v.=$ProductInfo->Recommand."','";
			$v.=$ProductInfo->Hits."','";
			$v.=$ProductInfo->Hot."','";
			$v.=$ProductInfo->Bid."','";
			$v.=$ProductInfo->Sid."','";
			$v.=$ProductInfo->Des."','";
			$v.=$ProductInfo->KeyWords."','";
			$v.=$ProductInfo->Pic."','";
			$v.=$ProductInfo->Pic_s."','";
			$v.=$ProductInfo->Cid."','";
			$v.=$ProductInfo->BuyContent."','";
			$v.=$ProductInfo->Jishu."','";
			$v.=$ProductInfo->Tid;
			
		//	echo $v;
			 
			$this->conn->insert($table,"Title,Content,Scontent,Author,Times,Froms,Indexs,OrderId,Shows,Recommand,Hits,Hot,Bid,Sid,Des,KeyWords,Pic,Pic_s,cid,buycontent,jishu,tid",$v);
			return $this->conn->insert_id();	
		}
		
		
		///修改文章
		public function EditProduct($ProductInfo)
		{
		 
 
			$v="title='$ProductInfo->Title',";
			$v.="Content='$ProductInfo->Content',";
			$v.="Scontent='$ProductInfo->Scontent',";
			$v.="Author='$ProductInfo->Author',";
			$v.="Times='$ProductInfo->Times',";
			$v.="Froms='$ProductInfo->Froms',";
			$v.="Indexs='$ProductInfo->Indexs',";
			$v.="OrderId='$ProductInfo->OrderId',";
			$v.="Shows='$ProductInfo->Shows',";
			$v.="Recommand='$ProductInfo->Recommand',";
			$v.="Hits='$ProductInfo->Hits',";
			$v.="Hot='$ProductInfo->Hot',";
			$v.="Bid='$ProductInfo->Bid',";
			$v.="Sid='$ProductInfo->Sid',";
			$v.="Des='$ProductInfo->Des',";
			$v.="KeyWords='$ProductInfo->KeyWords',";
			$v.="Pic='$ProductInfo->Pic',";
			$v.=" pic_s = '$ProductInfo->Pic_s',";
			$v.=" cid = '$ProductInfo->Cid',";
			$v.=" buycontent = '$ProductInfo->BuyContent',";
			$v.=" jishu = '$ProductInfo->Jishu',";
			$v.="LikeWords='$ProductInfo->LikeWords'";
			 
			try {  
				$table="t_product";
				$this->conn->update($table,$v," id=".$ProductInfo->Id);
					return $ProductInfo->Id;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
		//删除
		public function DelProduct($id)
		{
			try{
			$t=" t_product";
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
			$sql="select id from t_product a  where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$result=$this->conn->query($sql);
			$num= $this->conn->num_rows();
			return $num;
		}
		
		
			
			///计算总数
		public function GetCountLog($sqlwhere='')
		{
			$sql="select id from t_product_log a  where 1=1 ";
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
			$sql="select id,title from t_product where bid=$bid and id<$id and shows='yes' order by id desc limit 1";
			$result=$this->conn->query($sql);
			$row=$this->conn->fetch_array();
			if($row!=NULL)
			{
				$product["id"] = $row[0];
				$product["title"] = $row[1];
			}
			else
			{
				$product["id"]=0;
				$product["title"]=0;
			}
			return $product;
		}
		
		
		//返回列表
		public function GetTitle($id)
		{
			$p ="";
			$sql="select id,title from t_product  where id=$id  order by id desc limit 1";
			$result=$this->conn->query($sql);
			$row=$this->conn->fetch_array();
			if($row!=NULL)
			{
				$p = $row[1];
			}
			 
		 	return $p;
		}
		
	
		
		public function Getlist($bid,$start=0,$top=5,$sqlwhere='')
		{
			$rss=array();
			$sql='';
			$sql.='select id,title,bid,pic,times,scontent from t_product where 1=1   ';
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
			$sql="select id,title from t_product where bid=$bid and id>$id and shows='yes' order by id asc limit 1";
			$result=$this->conn->query($sql);
			$row=$this->conn->fetch_array();
			if($row!=NULL)
			{
				$product["id"] = $row[0];
				$product["title"] = $row[1];
			}
			else
			{
				$product["id"]=0;
				$product["title"]=0;
			}
			return $product;
		}
		
		///分页类
		public function GetPageList($sqlwhere='',$page=1,$pagesize=20)
		{
		
			$rss=array();
			$sql="select a.*,b.classname as bname,c.classname as sname from t_product  a ";
			$sql=$sql." left join t_product_class b on b.classid=a.bid ";
			$sql=$sql." left join t_product_class c on c.classid=a.sid ";
			$sql=$sql." where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql." order by orderid asc,id desc";
			$sql=$sql." limit $page,$pagesize";
		 
		  
			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rss[]=$row;
				
			 }
			 return $rss;
		}
		
		
		
		
		///分页类
		public function GetPageListLog($sqlwhere='',$page=1,$pagesize=20)
		{
		
			$rss=array();
			$sql="select  a.* from t_product_log  a ";
		 
			$sql=$sql." where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql." order by id desc";
			$sql=$sql." limit $page,$pagesize";
		 
		  
			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rss[]=$row;
				
			 }
			 return $rss;
		}
		
		
		
		
		public function GetModel($productid)
		{
			$rss=array();
			try {  
				 
				$sql="select * from t_product where id=".$productid ;	
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
	
	
	
	///产品图片
	
	class ProductPicInfo{
		public $Id;
		public $ProductId;
		public $Pic;
		public $OrderId;
		public $Title;
		public $Url;
	} 
 
 	class ProductPic{
	
		public $conn;
		public function __construct()
		{
			$this->conn=new mysql();
			
		}
		
			//析构
		public function __destruct()
		{
			 
		}
		
		//删除
			public function DelProductPic($id,$file)
			{
				try{
				$t=" t_product_pic";
				$w=" id in(".$id.")";
				$this->conn->delete($t,$w); 
				//unlink(dirname(__FILE__)."../../".$file);
				return true;
				}
				catch (Exception $e) {  
						return false;
					}   
			}
			
		
		
			///添加图片
			public function AddProductPic($ProductPicInfo)
			{
				 
				$table="t_product_pic";
				$v=$ProductPicInfo->ProductId ."','".$ProductPicInfo->OrderId."','".$ProductPicInfo->Pic;
				$this->conn->insert($table,"productid,orderid,pic",$v);
				return $this->conn->insert_id();
			}
			
			
			///修改图片
			public function EditProductPic($ProductPicInfo)
			{
			
				try {  
					$table="t_product_pic";
					$v="productid='".$ProductPicInfo->ProductId."',orderid='".$ProductPicInfo->OrderId."',pic='".$ProductPicInfo->Pic."'";
					$this->conn->update($table,$v," id=".$ProductPicInfo->Id);
						return $ProductPicInfo->Id;
					} 
				catch (Exception $e) {  
						return 0;
					}   
			}
			
			
				///返回实体
			public function GetClassModel($id)
			{
			
				try {  
					$pm= new ProductPicInfo();
					$sql="select * from t_product_pic where id=".$id ;	
					$result=$this->conn->query($sql);
					 
					while($row=$this->conn->fetch_array())
					{
					 $pm->Id=$row['id'];
					 $pm->Pic=$row['pic'];
					 $pm->ProductId=$row['productid'];
					 $pm->OrderId=$row['orderid'];
					 }
					return $pm;
				} catch (Exception $e) {  
						return NULL;
				}   	 
			}
			
			
			
		
					///计算总数
			public function GetCount($id)
			{
				$sql="select id from t_product_pic a  where a.productid=$id";
				 
				$result=$this->conn->query($sql);
				$num= $this->conn->num_rows();
				return $num;
			}
		
		//图片
			public function Getlist($bid,$start=0,$top=10,$sqlwhere='')
			{
				$rss=array();
				$sql='';
				$sql.='select * from t_product_pic where 1=1   ';
				if($bid!=0)
				{
				 $sql.=" and productid='$bid' ";
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
			
			//--============================================================================
			
			
			
			
			//删除
			public function DelProductSy($id)
			{
				try{
				$t=" t_product_zn";
				$w=" id in(".$id.")";
				$this->conn->delete($t,$w); 
 				return true;
				}
				catch (Exception $e) {  
						return false;
					}   
			}
			
		
		
			///添加图片
			public function AddProductSy($ProductPicInfo)
			{
				 
				$table="t_product_zn";
				$v=$ProductPicInfo->ProductId ."','".$ProductPicInfo->OrderId."','".$ProductPicInfo->Title."','".$ProductPicInfo->Url;
				$this->conn->insert($table,"productid,orderid,title,url",$v);
				return $this->conn->insert_id();
			}
			
			
			///修改图片
			public function EditProductSy($ProductPicInfo)
			{
			
				try {  
					$table="t_product_zn";
					$v="productid='".$ProductPicInfo->ProductId."',orderid='".$ProductPicInfo->OrderId."',url='".$ProductPicInfo->Url."',title='".$ProductPicInfo->Title."'";
					$this->conn->update($table,$v," id=".$ProductPicInfo->Id);
						return $ProductPicInfo->Id;
					} 
				catch (Exception $e) {  
						return 0;
					}   
			}
			
			
			
			
			
				///返回实体
			public function GetClassModelSy($id)
			{
			
				try {  
					$pm= new ProductPicInfo();
					$sql="select * from t_product_zn where id=".$id ;	
					$result=$this->conn->query($sql);
					 
					while($row=$this->conn->fetch_array())
					{
					 $pm->Id=$row['id'];
					 $pm->Title=$row['title'];
					 $pm->Url=$row['url'];
					 $pm->ProductId=$row['productid'];
					 $pm->OrderId=$row['orderid'];
					 }
					return $pm;
				} catch (Exception $e) {  
						return NULL;
				}   	 
			}
			
			
			//实用文章
		
			public function GetlistSy($bid,$start=0,$top=10,$sqlwhere='')
			{
				$rss=array();
				$sql='';
				$sql.='select * from t_product_zn where 1=1   ';
				if($bid!=0)
				{
				 $sql.=" and productid='$bid' ";
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
			
			
			
			
			
				//--============================================================================
			
			
			
			
			//删除
			public function DelProductLike($id)
			{
				try{
				$t=" t_product_like";
				$w=" id in(".$id.")";
				$this->conn->delete($t,$w); 
 				return true;
				}
				catch (Exception $e) {  
						return false;
					}   
			}
			
		
		
			///添加图片
			public function AddProductLike($ProductPicInfo)
			{
				 
				$table="t_product_like";
				$v=$ProductPicInfo->ProductId ."','".$ProductPicInfo->OrderId."','".$ProductPicInfo->Title."','".$ProductPicInfo->Url;
				$this->conn->insert($table,"productid,orderid,title,url",$v);
				return $this->conn->insert_id();
			}
			
			
			///修改图片
			public function EditProductLike($ProductPicInfo)
			{
			
				try {  
					$table="t_product_like";
					$v="productid='".$ProductPicInfo->ProductId."',orderid='".$ProductPicInfo->OrderId."',url='".$ProductPicInfo->Url."',title='".$ProductPicInfo->Title."'";
					$this->conn->update($table,$v," id=".$ProductPicInfo->Id);
						return $ProductPicInfo->Id;
					} 
				catch (Exception $e) {  
						return 0;
					}   
			}
			
			
			
			
			
				///返回实体
			public function GetClassModelLike($id)
			{
			
				try {  
					$pm= new ProductPicInfo();
					$sql="select * from t_product_like where id=".$id ;	
					$result=$this->conn->query($sql);
					 
					while($row=$this->conn->fetch_array())
					{
					 $pm->Id=$row['id'];
					 $pm->Title=$row['title'];
					 $pm->Url=$row['url'];
					 $pm->ProductId=$row['productid'];
					 $pm->OrderId=$row['orderid'];
					 }
					return $pm;
				} catch (Exception $e) {  
						return NULL;
				}   	 
			}
			
			
			//实用文章
		
			public function GetlistLike($bid,$start=0,$top=10,$sqlwhere='')
			{
				$rss=array();
				$sql='';
				$sql.='select * from t_product_like where 1=1   ';
				if($bid!=0)
				{
				 $sql.=" and productid='$bid' ";
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
			
		/////==============================================-----------------------------===
		
		
		
		
				//删除
			public function DelProductDownload($id)
			{
				try{
				$t=" t_product_download";
				$w=" id in(".$id.")";
				$this->conn->delete($t,$w); 
 				return true;
				}
				catch (Exception $e) {  
						return false;
					}   
			}
			
		
		
			///添加图片
			public function AddProductDownload($ProductPicInfo)
			{
				 
				$table="t_product_download";
				$v=$ProductPicInfo->ProductId ."','".$ProductPicInfo->OrderId."','".$ProductPicInfo->Title."','".$ProductPicInfo->Pic;
				$this->conn->insert($table,"productid,orderid,title,pic",$v);
				return $this->conn->insert_id();
			}
			
			
			///修改图片
			public function EditProductDownload($ProductPicInfo)
			{
			
				try {  
					$table="t_product_download";
					$v="productid='".$ProductPicInfo->ProductId."',orderid='".$ProductPicInfo->OrderId."',pic='".$ProductPicInfo->Pic."',title='".$ProductPicInfo->Title."'";
					$this->conn->update($table,$v," id=".$ProductPicInfo->Id);
						return $ProductPicInfo->Id;
					} 
				catch (Exception $e) {  
						return 0;
					}   
			}
			
			
			
			
			
				///返回实体
			public function GetClassModelDownload($id)
			{
			
				try {  
					$pm= new ProductPicInfo();
					$sql="select * from t_product_download where id=".$id ;	
					$result=$this->conn->query($sql);
					 
					while($row=$this->conn->fetch_array())
					{
					 $pm->Id=$row['id'];
					 $pm->Title=$row['title'];
					 $pm->Pic=$row['pic'];
					 $pm->ProductId=$row['productid'];
					 $pm->OrderId=$row['orderid'];
					 }
					return $pm;
				} catch (Exception $e) {  
						return NULL;
				}   	 
			}
			
			
			//下载
		
			public function GetlistDownload($bid,$start=0,$top=10,$sqlwhere='')
			{
				$rss=array();
				$sql='';
				$sql.='select * from t_product_download where 1=1   ';
				if($bid!=0)
				{
				 $sql.=" and productid='$bid' ";
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
			
			
				
			
			
		}
	 
 	
	  
?>