<?php
	///==========================================关键词实体==========================================类
	class KeysInfo{
		public $Id; //ID
		public $Types; //类别
		public $Keyword;//关键词
		public $Orderby;//排序
	}
	
 
	///==========================================关键词操作类==========================================
	class KeysDal{
		
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
		public function add($KeyInfo){
			try {
				$table=" t_keys";
				$v=$KeyInfo->Types."','".$KeyInfo->Keyword."','".$KeyInfo->Orderby;
				$this->conn->insert($table,"Types,Keyword,Orderby",$v);
				return $this->conn->insert_id();
			} catch (Exception $e) {
				return 0;
			}
		}
		
		//删除 HE
		public function Del($id)
		{
			try{
			$t=" t_keys";
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
		public function Edit($kinfo){
			$v="types='".$kinfo->Types."',keyword='".$kinfo->Keyword."',orderby=".$kinfo->Orderby;
			$table="t_keys";
			try {
				return $this->conn->update($table,$v," id=".$kinfo->Id);
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
				$nmodel= new KeysInfo();
				$sql="select * from t_keys where id='".$id."'" ;	
				$result=$this->conn->query($sql);
				while($row=$this->conn->fetch_array()){
					 $nmodel->Id=$row['id'];
					 $nmodel->Types=$row['types'];
					 $nmodel->Keyword=$row['Keyword'];
					 $nmodel->Orderby=$row['orderby'];
				 }
				return $nmodel;
			} catch (Exception $e) {  
					return NULL;
			}   	 
		}
		///计算总数
		public function GetCount($sqlwhere=''){
			$sql="select a.id from t_keys a  where 1=1 ";
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
			$sql="select a.* from t_keys  a ";
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
		/**
		 * 查询页面关键词 HE
		 * @param string $types
		 * @return array;
		 */
		public function GetTypesList($types){
			$rss=array();
			$sql="select a.* from t_keys  a where a.types='".$types."' order by orderby";
			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array()){
				 $rss[]=$row;
		    }
			return $rss;
		}
		
 }
	
	 
?>