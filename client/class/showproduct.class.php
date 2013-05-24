<?php 
	///==========================================BANNER实体类==========================================类
	class ShowProductInfo{
		public $Id;
		public $Types;
		public $Url;
		public $Title;
		public $Description;
		public $Uid;	
	}
	
 	
	///==========================================BANNER操作类==========================================
  
	class ShowProductClass{
	
		public function __construct()
		{
			$this->conn=new mysql();
			
		}
			//析构
		public function __destruct()
		{
			 
		}
		
		///添加 
		public function Add($ShowProductInfo){
			$table="t_product_show";
 
			$v=$ShowProductInfo->Title."','";
			$v.=$ShowProductInfo->Types."','";
			$v.=$ShowProductInfo->Url."','";
			$v.=$ShowProductInfo->Description."','";
			$v.=$ShowProductInfo->Uid."";

 
			$this->conn->insert($table,"title,types,url,description,uid",$v);
			return $this->conn->insert_id();	
		}
		
		
		///修改 
		public function Edit($ShowProductInfo)
		{
			$v="`title`='$ShowProductInfo->Title',";
			$v.="url='$ShowProductInfo->Url',";
			$v.="types='$ShowProductInfo->Types',";
			$v.="description='$ShowProductInfo->Description',";
			$v.="uid='$ShowProductInfo->Uid'";

			try {  
				$table="t_product_show";
				$this->conn->update($table,$v," id=".$ShowProductInfo->Id);
					return $ShowProductInfo->Id;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
		//删除
		public function Del($id)
		{
			try{
			$t=" t_product_show";
			$w=" id in(".$id.")";
			 
			$this->conn->delete($t,$w); 
			return true;
			}
			catch (Exception $e) {  
					return false;
				}   
		}
		
		//启用
		public function modifyshow($id,$state)
		{
			$value=$state==0?1:0;
			$v="`show`=".$value." ";
			try {  
				$table="t_product_show";
				$this->conn->update($table,$v," id=".$id);
					return $id;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
	
		
			///计算总数
		public function GetCount($sqlwhere='')
		{
			$sql="select id from t_product_show a  where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$result=$this->conn->query($sql);
			$num= $this->conn->num_rows();
			return $num;
		}
		
		
 
		
		public function Getlist($types,$start=0,$top=100,$sqlwhere='')
		{
			$rss=array();
			$sql='';
			$sql.='select * from t_product_show where 1=1   ';
			if($types!="")
			{
			 $sql.=" and types='$types' ";
			}
			
			if($sqlwhere!='')
			{
			$sql.=" and ".$sqlwhere;
			}
			
 
			$sql.=" order by id desc limit $start,$top ";
 
 			 
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
			$sql="select a.*  from t_product_show  a ";
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
				 
				$sql="select * from t_product_show where id=".$newsid ;	
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
		
	}
	
	
	
	  
 
	 
 	
	  
?>