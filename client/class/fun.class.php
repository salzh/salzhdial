<?php
	
 
	///==========================================用户操作类==========================================
	class FunDal{
		
		public $conn;
		public function __construct()
		{
			$this->conn=new mysql();
		}
		
		//析构
		public function __destruct()
		{
			 
		}
		
		public function getRandNum($length = 3) {
			$code_list = "0123456789";
			$code_length = strlen ( $code_list ) - 1;
			$rand_code = "";  
			for($i = 0; $i < $length; $i ++) {
				$rand_num = rand ( 0, $code_length );
				$rand_code .= $code_list [$rand_num];
			}
			return $rand_code;
		}
	
	public function GetOptionSelect($ClassType,$cmbName,$selId="")
	{
		$list=array();
		$sqls="select * from t_dic where ClassType=".$ClassType." order by idx";
		$list=$this->GetTableList($sqls);
		$other=",".$selId.",";
		//echo $other."|";
		foreach($list as $rs)
		{
				if(strrpos(",".$selId.",", ",".$rs["ClassName"].",")>-1)
				{
				  $other=str_replace($rs["ClassName"].",","",$other);
				  //echo $other."|";
				  $str=$str."<input name='".$cmbName."[]' type='checkbox' checked value='".$rs['ClassName']."' /> "  . $rs['ClassName'] ."";
				}
				else
				{
				  $str=$str."<input name='".$cmbName."[]' type='checkbox' value='".$rs['ClassName']."' /> "  . $rs['ClassName'] ."";
				}
		}
		//echo $other."|";
		$other=str_replace(",","",$other);
		return $str;
	}
	
	///返回下拉菜单
	public function GetComboList($comboName,$TypeId=0,$selId=0,$tableName="t_dic",$fieldName="ClassId,ClassName",$changeJS="",$disable="",$whereDetail="")
	{
		$str="<select id='$comboName' name='$comboName' style='font-size:15px; color:#666; font-weight:bold;' ".($changeJS!=""?$changeJS:"")." ".$disable.">";
		$fieldValue=explode(",",$fieldName);
		$sqlwhere="";
		if($selId==0)
		{
			$str=$str."<option  value='0' style='background:#efefef;'  selected>--请选择--</option>";
		}
		if($TypeId!="")
		{
			$sqlwhere=" and ClassType=".$TypeId;
		}
		$list= $this->conn->GetTableList("select ".$fieldName." from ".$tableName." where 1=1".$sqlwhere.$whereDetail." order by idx");
		foreach($list as $rs)
		{
			$str=$str."<option  value='".$rs[$fieldValue[0]]."' style='background:#efefef;' ".($selId==$rs[$fieldValue[0]]?"selected":"").">". $rs[$fieldValue[1]]."</option>";
		}
		$str.="</select>";
		return $str;	
	}
	
	///返回下拉菜单
	public function GetRadioList($comboName,$TypeId=0,$selId=0,$tableName="t_dic",$fieldName="ClassId,ClassName",$changeJS="",$disable="",$whereDetail="")
	{
		$fieldValue=explode(",",$fieldName);
		$sqlwhere="";

		if($TypeId!="")
		{
			$sqlwhere=" and ClassType=".$TypeId;
		}
		$list= $this->conn->GetTableList("select ".$fieldName." from ".$tableName." where 1=1".$sqlwhere.$whereDetail." order by idx");

		if($selId=="")
		{
			$selId=$list[0][$fieldValue[0]];
		}

		foreach($list as $rs)
		{
			$str=$str."<input type='radio' name='$comboName' value='".$rs[$fieldValue[0]]."' id='$comboName' ".($selId==$rs[$fieldValue[0]]?"checked":"")."/><label for='$comboName'>". $rs[$fieldValue[1]]."</label>";
		}
		return $str;	
	}	
	
		///返回实体
	public function GetModel($sql,$nmodel)
	{
	
		try {
			$result=$this->conn->query($sql);
			while($row=$this->fetch_array())
			{
				foreach ($nmodel as $k=>$v) {
						$nmodel->$k=$row[$k];
					//echo 'key=',$k,' value=',$v,"\n";
				}
			 }
			return $nmodel;
		} catch (Exception $e) {  
			return NULL;
		}   	 
	}	
	
	public function GetList($sql)
	{
	
		try {
			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rss[]=$row[0];
			 }
			return $rss;
		} catch (Exception $e) {  
			return NULL;
		}   	 
	}
	
	public function GetModelList($sql)
	{
	
		try {
			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rss[]=$row;
			 }
			return $rss;
		} catch (Exception $e) {  
			return NULL;
		}   	 
	}			
	
		///返回实体
	public function GetValue($sql)
	{
		$rss=array();
		try {
			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rss[]=$row;
				
			 }
			 return $rss[0][0];			
		} catch (Exception $e) {  
			return NULL;
		}   	 
	}	
	
	function checkstr($str,$needle){   
		//$needle = "a";//判断是否包含a这个字符   
		$tmparray = explode($needle,$str);   
		if(count($tmparray)>1){   
			return true;   
		} else{   
			return false;   
		}   
	}	
		///ADD实体
	public function AddModel($table,$nmodel)
	{
		$s="";
		$f="";
		foreach ($nmodel as $k=>$v) {
			if(isset($nmodel->$k))
			{
				$s.=$v."','";
				$f.=$k.",";
				//echo 'key=',$k,' value=',$v,"\n";
			}
		}

		$s=substr($s,0,strlen($s)-3);		
		$f=substr($f,0,strlen($f)-1);	
		//echo $s.":".$f;	

		$this->conn->insert($table,$f,$s);
		return $this->conn->insert_id(); 	 
	}	
	
		///UPDATE实体
	public function UpdateModel($table,$nmodel)
	{
		$s="";
		$id="";
		foreach ($nmodel as $k=>$v) {
			if(isset($nmodel->$k)&&$k!="id")
			{
				$s.=$k."='$v',";
				//echo 'key=',$k,' value=',$v,"\n";
			}
			else if(isset($nmodel->$k)&&$k=="id")
			{
				$id=$v;
			}
		}

		$s=substr($s,0,strlen($s)-1);		
		//echo $s.":".$id;	

		$this->conn->update($table,$s,"id=$id");
		return $id; 	 
	}	
	
	public function CreateFile($file,$des)
	{
		$of = fopen($file,'w');//创建并打开dir.txt
		if($of){
		 fwrite($of,$des);//把执行文件的结果写入txt文件
		}
		fclose($of);		
	}
		
 }
	
	 
?>