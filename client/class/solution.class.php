<?php 
	///==========================================新闻实体==========================================类
	class SolutionInfo{
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
	
	class SolutionClassInfo{
		public $ClassName;
		public $ClassId;
		public $OrderId;
		public $Bid;
		public $Des;
		public $KeyWord;
		public $Content;
		public $Pic;
	}
	
	///==========================================文章分类操作类==========================================
	class SolutionClass{
		
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
		public function AddSolutionClass($SolutionClassInfo)
		{
			 
			$table="t_solution_class";
			$v=$SolutionClassInfo->ClassName ."','".$SolutionClassInfo->OrderId."','".$SolutionClassInfo->Content."','".$SolutionClassInfo->Des."','".$SolutionClassInfo->KeyWord."','".$SolutionClassInfo->Pic."','". $SolutionClassInfo->Bid;
			$this->conn->insert($table,"classname,orderid,content,des,keyword,pic,bid",$v);
			return $this->conn->insert_id();
		}
		
	
		///修改新闻分类
		public function EditSolutionClass($SolutionClassInfo)
		{
		
			try {  
				$table="t_solution_class";
				$v="classname='".$SolutionClassInfo->ClassName."',orderid='".$SolutionClassInfo->OrderId."',content='".$SolutionClassInfo->Content."',des='".$SolutionClassInfo->Des."',keyword='".$SolutionClassInfo->KeyWord."',pic='".$SolutionClassInfo->Pic."',bid=". $SolutionClassInfo->Bid;
				$this->conn->update($table,$v," Classid=".$SolutionClassInfo->ClassId);
					return $SolutionClassInfo->ClassId;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
		
		///返回实体
		public function GetClassModel($classid)
		{
		
			try {  
				$nmodel= new SolutionClassInfo();
				$sql="select * from t_solution_class where classId=".$classid ;	
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
				 $nmodel->Pic=$row['Pic'];
				 }
				return $nmodel;
			} catch (Exception $e) {  
					return NULL;
			}   	 
		}
		
		///返回类ID 和 父类ID
		public function GetClassId($classid)
		{
			$sql="select classid,bid from t_solution_class where classid=".$classid;	
			$result=$this->conn->query($sql);
			$row= $this->conn->fetch_array();
			 
			 	$rss["classid"]=$row[0];
				$rss["bid"]=$row[1];
			 
			 return $rss;
		}
		
		
		///根据类ID，返回BID SID
		public function GetBidSid($classid)
		{
			$sql="select classid,bid from t_solution_class where classid=".$classid;	
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
		public function DelSolutionClass($classid)
		{
			$t=" t_solution_class";
			$w=" classid=".$classid;
			$this->conn->delete($t,$w); 
		}
		
		///是否有子类
		public function IfBid($classid)
		{
			$sql="select classid from t_solution_class where bid=".$classid ;
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
			$sql="select * from t_solution_class where bid='".$bid."' order by orderid asc,classid desc";
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
		
			$sql="select max(orderid) from t_solution_class";	
			$result=$this->conn->query($sql);
			$rs=$this->conn->fetch_array();
			$orderid = $rs[0];
			return $orderid;
		}
		
		///根据类ID返回 类名
		public function GetClassName($ClassId)
		{
			$sql="select classname from t_solution_class where classId=".$ClassId ;	
			$result=$this->conn->query($sql);
			$row=$this->conn->fetch_array();
			$classname = $row[0];
			return $classname;
		}
		
		
	}
	
	/////==========================================新闻操作类//==========================================
	/////===============================================================================================
	class Solution{
	
		public function __construct()
		{
			$this->conn=new mysql();
			
		}
		
		
		///添加文章
		public function AddSolution($SolutionInfo){
			$table="t_solution";
 
			$v=$SolutionInfo->Title ."','";
			$v.=$SolutionInfo->Content."','";
			$v.=$SolutionInfo->Scontent."','";
			$v.=$SolutionInfo->Author."','";
			$v.=$SolutionInfo->Times."','";
			$v.=$SolutionInfo->Froms."','";
			$v.=$SolutionInfo->Indexs."','";
			$v.=$SolutionInfo->OrderId."','";
			$v.=$SolutionInfo->Shows."','";
			$v.=$SolutionInfo->Recommand."','";
			$v.=$SolutionInfo->Hits."','";
			$v.=$SolutionInfo->Hot."','";
			$v.=$SolutionInfo->Bid."','";
			$v.=$SolutionInfo->Sid."','";
			$v.=$SolutionInfo->Des."','";
			$v.=$SolutionInfo->KeyWords."','";
			$v.=$SolutionInfo->Pic."','";
			$v.=$SolutionInfo->Pic_s."','";
			$v.=$SolutionInfo->LikeWords;
			
		//	echo $v;
			 
			$this->conn->insert($table,"Title,Content,Scontent,Author,Times,Froms,Indexs,OrderId,Shows,Recommand,Hits,Hot,Bid,Sid,Des,KeyWords,Pic,Pic_s,LikeWords",$v);
			return $this->conn->insert_id();	
		}
		
		
		///修改文章
		public function EditSolution($SolutionInfo)
		{
		 
 
			$v="title='$SolutionInfo->Title',";
			$v.="Content='$SolutionInfo->Content',";
			$v.="Scontent='$SolutionInfo->Scontent',";
			$v.="Author='$SolutionInfo->Author',";
			$v.="Times='$SolutionInfo->Times',";
			$v.="Froms='$SolutionInfo->Froms',";
			$v.="Indexs='$SolutionInfo->Indexs',";
			$v.="OrderId='$SolutionInfo->OrderId',";
			$v.="Shows='$SolutionInfo->Shows',";
			$v.="Recommand='$SolutionInfo->Recommand',";
			$v.="Hits='$SolutionInfo->Hits',";
			$v.="Hot='$SolutionInfo->Hot',";
			$v.="Bid='$SolutionInfo->Bid',";
			$v.="Sid='$SolutionInfo->Sid',";
			$v.="Des='$SolutionInfo->Des',";
			$v.="KeyWords='$SolutionInfo->KeyWords',";
			$v.="Pic='$SolutionInfo->Pic',";
			$v.=" pic_s = '$SolutionInfo->Pic_s',";
			$v.="LikeWords='$SolutionInfo->LikeWords'";
			 
			try {  
				$table="t_solution";
				$this->conn->update($table,$v," id=".$SolutionInfo->Id);
					return $SolutionInfo->Id;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
		//删除
		public function DelSolution($id)
		{
			try{
			$t=" t_solution";
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
			$sql="select id from t_solution a  where 1=1 ";
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
			$sql="select id,title from t_solution where bid=$bid and id<$id and shows='yes' order by id desc limit 1";
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
			$sql.='select id,title,bid,pic,times,scontent from t_solution where 1=1   ';
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
			$sql="select id,title from t_solution where bid=$bid and id>$id and shows='yes' order by id asc limit 1";
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
			$sql="select a.*,b.classname as bname,c.classname as sname from t_solution  a ";
			$sql=$sql." left join t_solution_class b on b.classid=a.bid ";
			$sql=$sql." left join t_solution_class c on c.classid=a.sid ";
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
		
		
		public function GetModel($newsid)
		{
			$rss=array();
			try {  
				 
				$sql="select * from t_solution where id=".$newsid ;	
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