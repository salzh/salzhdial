<?php 
	///==========================================广告类实体==========================================类
	class AdInfo{
		public $Id;
		public $Pic;
		public $Url;
		public $Title;
		public $Notes;
		public $OrderId;
		public $Types;

	}
 
	/////==========================================AD操作类//==========================================
	/////===============================================================================================
	class Ad{
	
		public function __construct()
		{
			$this->conn=new mysql();
			
		}
		
		
		///添加文章
		public function Add($AdInfo){
			$table="t_adinfo";
 
			$v=$AdInfo->Pic ."','";
			$v.=$AdInfo->Url."','";
			$v.=$AdInfo->Title."','";
			$v.=$AdInfo->Notes."','";
			$v.=$AdInfo->OrderId."','";
			$v.=$AdInfo->Types."','";
			$this->conn->insert($table,"pic,url,title,notes,orderid,types",$v);
			return $this->conn->insert_id();	
		}
		
		
		///修改
		public function Edit($AdInfo)
		{
		 
 
			$v="title='$AdInfo->Title',";
			$v.="pic='$AdInfo->Pic',";
			$v.="notes='$AdInfo->Notes',";
			$v.="types='$AdInfo->Types',";
			$v.="orderid='$AdInfo->OrderId',";
			$v.="url='$AdInfo->Url'";
			try {  
				$table="t_adinfo";
				$this->conn->update($table,$v," id=".$AdInfo->Id);
					return $NewsInfo->Id;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
		//删除
		public function Del($id)
		{
			try{
			$t=" t_adinfo";
			$w=" id in(".$id.")";
			 
			$this->conn->delete($t,$w); 
			return true;
			}
			catch (Exception $e) {  
					return false;
				}   
		}
	 
 		
		///返回列表
		public function GetList($types,$topn=1)
		{
			$rss=array();
			$sql="select * from t_adinfo where types='".$types."' order by orderid asc,id desc limit $topn ";
			$result=$this->conn->query($sql);
			 while($row= $this->conn->fetch_array())
			 {
			 	$rss[]=$row;
			 }
			 return $rss;
			 
		}
		
		
		//返回实体
		public function GetModel($newsid)
		{
			$rss=array();
			try {  
				 
				$sql="select * from t_adinfo where id=".$newsid ;	
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