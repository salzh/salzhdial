<?php 
	 
	
	////========================================== 分类实体类==========================================
	
	class SCClassInfo{
		public $ClassName;
		public $ClassId;
		public $OrderId;
		public $Bid;
 
	}
	
	///========================================== 分类操作类==========================================
	class SCClass{
		
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
		public function Add($SCClassInfo)
		{
			 
			$table="t_sc_class";
			$v=$SCClassInfo->ClassName ."','".$SCClassInfo->OrderId."','". $SCClassInfo->Bid;
			$this->conn->insert($table,"classname,orderid,bid",$v);
			return $this->conn->insert_id();
		}
		
	
		///修改新闻分类
		public function Edit($SCClassInfo)
		{
		
			try {  
				$table="t_sc_class";
				$v="classname='".$SCClassInfo->ClassName."',orderid='".$SCClassInfo->OrderId."',bid=". $SCClassInfo->Bid;
				$this->conn->update($table,$v," Classid=".$SCClassInfo->ClassId);
					return $SCClassInfo->ClassId;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
		
		///返回实体
		public function GetClassModel($classid)
		{
		
			try {  
				$nmodel= new SCClassInfo();
				$sql="select * from t_sc_class where classId=".$classid ;	
				$result=$this->conn->query($sql);
				 
				while($row=$this->conn->fetch_array())
				{
				 $nmodel->ClassId=$row['ClassId'];
				  $nmodel->OrderId=$row['OrderId'];
				 $nmodel->ClassName=$row['ClassName'];
 				 $nmodel->Bid=$row['Bid'];
			 
				 }
				return $nmodel;
			} catch (Exception $e) {  
					return NULL;
			}   	 
		}
		
		///返回类ID 和 父类ID
		public function GetClassId($classid)
		{
			$sql="select classid,bid from t_sc_class where classid=".$classid;	
			$result=$this->conn->query($sql);
			$row= $this->conn->fetch_array();
			 
			 	$rss["classid"]=$row[0];
				$rss["bid"]=$row[1];
			 
			 return $rss;
		}
		
		
		///根据类ID，返回BID SID
		public function GetBidSid($classid)
		{
			$sql="select classid,bid from t_sc_class where classid=".$classid;	
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
		public function Del($classid)
		{
			$t=" t_sc_class";
			$w=" classid=".$classid;
			$this->conn->delete($t,$w); 
		}
		
		///是否有子类
		public function IfBid($classid)
		{
			$sql="select classid from t_sc_class where bid=".$classid ;
			$result=$this->conn->query($sql);
			$count=$this->conn->num_rows();
			return $count;
		}
		
		
		///返回下拉菜单
		public function GetSelect($bid=0)
		{
			
			$str="<select name='cid' style='font-size:15px; color:#666; font-weight:bold; margin:5px;padding:15px;'>";
			$list=$this->GetClassList();
			
			 
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
					}
			 
			$str=$str."</select>";
			return $str;
		}
		
		
		///返回分类列表
		public function GetClassList($bid=0)
		{
			$rss=array();
			$sql="select * from t_sc_class where bid='".$bid."' order by orderid asc,classid desc";
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
		
			$sql="select max(orderid) from t_sc_class";	
			$result=$this->conn->query($sql);
			$rs=$this->conn->fetch_array();
			$orderid = $rs[0];
			return $orderid;
		}
		
		///根据类ID返回 类名
		public function GetClassName($ClassId)
		{
			$sql="select classname from t_sc_class where classId=".$ClassId ;	
			$result=$this->conn->query($sql);
			$row=$this->conn->fetch_array();
			$classname = $row[0];
			return $classname;
		}
		
		
	}
 
?>