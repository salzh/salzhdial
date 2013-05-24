<?php 
 
	
	////==========================================分类实体类==========================================
	
	class DepClassInfo{
		public $ClassName;
		public $ClassId;
		public $OrderId;
		public $Bid;
		public $Des;
		public $KeyWord;
		public $Content;
	}
	
	///==========================================部门分类操作类==========================================
	class DepClass{
		
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
		public function AddDepClass($DepClassInfo)
		{
			 
			$table="t_dep_class";
			$v=$DepClassInfo->ClassName ."','".$DepClassInfo->OrderId."','".$DepClassInfo->Content."','".$DepClassInfo->Des."','".$DepClassInfo->KeyWord."','". $DepClassInfo->Bid;
			$this->conn->insert($table,"classname,orderid,content,des,keyword,bid",$v);
			return $this->conn->insert_id();
		}
		
	
		///修改新闻分类
		public function EditDepClass($DepClassInfo)
		{
		
			try {  
				$table="t_dep_class";
				$v="classname='".$DepClassInfo->ClassName."',orderid='".$DepClassInfo->OrderId."',content='".$DepClassInfo->Content."',des='".$DepClassInfo->Des."',keyword='".$DepClassInfo->KeyWord."',bid=". $DepClassInfo->Bid;
				$this->conn->update($table,$v," Classid=".$DepClassInfo->ClassId);
					return $DepClassInfo->ClassId;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
		
		///返回实体
		public function GetClassModel($classid)
		{
		
			try {  
				$nmodel= new DepClassInfo();
				$sql="select * from t_dep_class where classId=".$classid ;	
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
			$sql="select classid,bid from t_dep_class where classid=".$classid;	
			$result=$this->conn->query($sql);
			$row= $this->conn->fetch_array();
			 
			 	$rss["classid"]=$row[0];
				$rss["bid"]=$row[1];
			 
			 return $rss;
		}
 
		///删除
		public function DelDepClass($classid)
		{
			$t=" t_dep_class";
			$w=" classid=".$classid;
			$this->conn->delete($t,$w); 
		}
		
		///是否有子类
		public function IfBid($classid)
		{
			$sql="select classid from t_dep_class where bid=".$classid ;
			$result=$this->conn->query($sql);
			$count=$this->conn->num_rows();
			return $count;
		}
 
		///返回下拉菜单
		public function GetSelect($bid=0)
		{
			
			$str="<select name='depid' style='font-size:15px; color:#666; font-weight:bold; margin:5px;padding:15px;'>";
			$list=$this->GetClassList();
			
			if($bid=="0")
			{
				foreach($list as $rs)
				{
					$str=$str."<option  value='".$rs['ClassId']."' style='background:#efefef;'>". $rs['ClassName'] ."</option>"; 
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
				
				}
			}
			$str=$str."</select>";
			return $str;
		}
		
		
		///返回分类列表
		public function GetClassList($bid=0)
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
 
		
	}
 
	  
?>