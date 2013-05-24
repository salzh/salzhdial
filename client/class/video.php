<?php 
	
	class VideoInfo{
		public $Id;
		public $Video;
		public $Pic;
	}
	
	///==========================================文章分类操作类==========================================
	class VideoClass{
		public $conn;
		public function __construct()
		{
			$this->conn=new mysql();
			
		}
			//析构
		public function __destruct()
		{
			 
		}
		
		
		///修改新闻分类
		public function Edit($VideoInfo)
		{
 			try {  
				$table="t_video";
				$v="video='".$VideoInfo->Video."',pic='".$VideoInfo->Pic."'";
				$this->conn->update($table,$v," id=".$VideoInfo->Id);
					return $VideoInfo->Id;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}


		///返回实体
		public function GetModel($id)
		{
			try {  
				$nmodel= new VideoInfo();
				$sql="select * from t_video where id=".$id ;	
				$result=$this->conn->query($sql);
				 
				while($row=$this->conn->fetch_array())
				{
				 $nmodel->Id=$row['id'];
				 $nmodel->Pic=$row['pic'];
				 $nmodel->Video=$row['video'];
				 }
				return $nmodel;
			} catch (Exception $e) {  
					return NULL;
			}   	 
		}	
}
	
	
	  
 
	 
 	
	  
?>