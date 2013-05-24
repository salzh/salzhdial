<?php
	///==========================================关键词实体==========================================类
	class KeyWordsInfo{
		public $Id; //ID
 		public $Keyword;//关键词
		public $OrderId;//排序
	}
	
 
	///==========================================关键词操作类==========================================
	class KeyWordsDal{
		
		public $conn;
		public function __construct()
		{
			$this->conn=new mysql();
		}
		//析构
		public function __destruct()
		{
			 
		}
		
		///添加关键词HE
		public function add($KeyWordsInfo){
			try {
				$table=" t_keyword";
				$v=$KeyWordsInfo->Keyword."','".$KeyWordsInfo->OrderId;
				$this->conn->insert($table,"keyword,orderid",$v);
				return $this->conn->insert_id();
			} catch (Exception $e) {
				return 0;
			}
		}
		
		//删除 HE
		public function Del($id)
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
		
		/**
		 * 修改信息
		 * @param $kinfo
		 */
		public function Edit($KeyWordsInfo){
			$v="keyword='".$KeyWordsInfo->Keyword."',orderid=".$KeyWordsInfo->OrderId;
			$table="t_keyword";
			try {
				return $this->conn->update($table,$v," id=".$KeyWordsInfo->Id);
			}catch (Exception $e) {  
				return 0;
			}   
		}
		/**
		 * HE 根据ID查询实体
		 * @param $id
		 */
		public function GetModel($id){
			try {  
				$nmodel= new KeyWordsInfo();
				$sql="select * from t_keyword where id='".$id."'" ;	
				$result=$this->conn->query($sql);
				while($row=$this->conn->fetch_array()){
					 $nmodel->Id=$row['id'];
 					 $nmodel->Keyword=$row['keyword'];
					 $nmodel->OrderId=$row['orderid'];
				 }
				return $nmodel;
			} catch (Exception $e) {  
					return NULL;
			}   	 
		}
		///计算总数
		public function GetCount($sqlwhere=''){
			$sql="select a.id from t_keyword a  where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$result=$this->conn->query($sql);
			$num= $this->conn->num_rows();
			return $num;
		}
		///分页类 HE
		public function GetPageList($sqlwhere='',$page=1,$pagesize=20)
		{
		
			$rss=array();
			$sql="select a.* from t_keyword  a ";
			$sql=$sql." where 1=1 ";
			if($sqlwhere!=''){
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql." limit $page,$pagesize";

			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array()){
				 $rss[]=$row;
			 }
			 return $rss;
		}
	 
		
 }
	
	 
?>