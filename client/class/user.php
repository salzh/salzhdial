<?php
	///==========================================用户实体==========================================类
	class UserInfo{
		public $UserId; //ID
		public $Email; //登录名
		public $Name;//密码
		public $Position;//真实姓名
		public $Group;//密码
		public $QOC_Role;//电子邮件
		public $Region;//地址
		public $Channel;//地址
	}
	
 
	///==========================================用户操作类==========================================
	class UserDal{
		
		public $conn;
		public function __construct()
		{
			$this->conn=new mysql();
		}
		
		
			//析构
		public function __destruct()
		{
			 
		}
		
		///注册会员 HE
		public function RegUser($UserInfo){
			try {
				$table="t_user";
				//$v=$UserInfo->UserName ."','/pic/logohead.jpg','/pic/logoheads.jpg','/pic/logoheadss.jpg','".$UserInfo->UserPassWord."','". $UserInfo->UserRegIp."','".$UserInfo->UserRealName."','".date('y-m-d')."','0','0','1','1";
				$v=$UserInfo->Email ."','".$UserInfo->Name."','". $UserInfo->Position."','".$UserInfo->Group."','".$UserInfo->QOC_Role."','".$UserInfo->Region."','".$UserInfo->Channel;
				$this->conn->insert($table,"Email,Name,Position,`Group`,QOC_Role,Region,Channel",$v);
				return $this->conn->insert_id();
			} catch (Exception $e) {
				return 0;
			}
		}
		
		
			//删除 HE
		public function Del($id)
		{
			try{
			$t=" t_user";
			$w=" userid in(".$id.")";
			 
			$this->conn->delete($t,$w); 
			return true;
			}
			catch (Exception $e) {  
					return false;
				}   
		}
		
	  
	 
			///修改用户信息 HE
		public function EditUserInfo($UserInfo)
		{
			try {  
				$table="t_user";
				//$v="UserEmail='".$UserInfo->UserEmail."',UserSex='".$UserInfo->UserSex."',UserModifyTime='".$UserInfo->UserModifyTime."',UserPic='".$UserInfo->UserPic."',UserPicS='".$UserInfo->UserPicS."',UserPicSS='".$UserInfo->UserPicSS."'";
				$v="Email='".$UserInfo->Email."',Name='".$UserInfo->Name."',Position='".$UserInfo->Position."',`Group`='".$UserInfo->Group."',QOC_Role='".$UserInfo->QOC_Role."',Region='".$UserInfo->Region."',Channel='".$UserInfo->Channel."'";
				if($UserInfo->UserId=="")
				{
					$t=$this->conn->update($table,$v," Email='".$UserInfo->Email."'");
				}
				else
				{
					$t=$this->conn->update($table,$v," UserId=".$UserInfo->UserId);
				}
				return $UserInfo->UserId;
			}catch (Exception $e) {  
					return 0;
			}   
		}
		
		
		///检查用户名是否存在 HE
		public function CheckUser($username)
		{
			$sql="select  userid  from t_user  where Email='$username'";
			//echo $sql;
			$result=$this->conn->query($sql);
			$count=$this->conn->num_rows();
			return $count;
			//echo $sql;
		}
		
			
		///检查用户名是否存在 
		public function CheckUserId($userid)
		{
			$sql="select  UserId  from t_user where UserId='$userid' and Level <1 and StatusId>0 ";
			//echo $sql;
			$result=$this->conn->query($sql);
			$count=$this->conn->num_rows();
			return $count;
			//echo $sql;
		}
		 
		 
		 ///返回分类列表
		public function GetA()
		{
			$rss=array();
			$sql="select * from t_area_class   order by orderid asc,classid desc";
			 
			$result=$this->conn->query($sql);
			 while($row= $this->conn->fetch_array())
			 {
			 	$rss[]=$row;
			 }
			 return $rss;
			 
		}
		
		 ///返回分类列表
		public function GetC()
		{
			$rss=array();
			$sql="select * from t_dep_class where bid=0 order by orderid asc,classid desc";
			 
			$result=$this->conn->query($sql);
			 while($row= $this->conn->fetch_array())
			 {
			 	$rss[]=$row;
			 }
			 return $rss;
			 
		}
		
		
		 
		 	///返回区域
		public function GetAreaSelect($bid='0')
		{
			
		 
			$list=$this->GetA();
		 
			$str="<select name='Region' style='font-size:15px; color:#666; font-weight:bold; margin:5px;padding:15px;'>";
			if($bid=="All")
			{
				$str=$str."<option  value='All'   style='background:#efefef;'  selected=selected >All</option>";
			}
			else
			{
				$str=$str."<option  value='All'   style='background:#efefef;' >All</option>";
			}
			if($bid!="0")
			{
				foreach($list  as $rs)
				{
				
						if ($bid==$rs['ClassName'])
						{
							$s="selected=selected";
						}
						else
						{
							$s="";
						}
					$str=$str."<option  value='".$rs['ClassName']."' style='background:#efefef;'  ".$s.">". $rs['ClassName'] ."</option>";
				}
			}
			else
			{
				foreach($list  as $rs)
				{	
 					$str=$str."<option  value='".$rs['ClassName']."' style='background:#efefef;'>". $rs['ClassName'] ."</option>";	 
				}
			}
			$str=$str."</select>";
			 
			return $str;
		}
		
		
		
		 
		 	///返回区域
		public function GetDep($bid='0')
		{
			
			$list=$this->GetC();
	 
			$str="<select name='Channel' style='font-size:15px; color:#666; font-weight:bold; margin:5px;padding:15px;'>";
				if($bid=="All")
			{
				$str=$str."<option  value='All'   style='background:#efefef;'  selected=selected >All</option>";
			}
			else
			{
				$str=$str."<option  value='All'   style='background:#efefef;' >All</option>";
			}
			if($bid!="0")
			{
				foreach($list  as $rs)
				{
				
						if ($bid==$rs['ClassName'])
						{
							$s="selected=selected";
						}
						else
						{
							$s="";
						}
					$str=$str."<option  value='".$rs['ClassName']."' style='background:#efefef;'  ".$s.">". $rs['ClassName'] ."</option>";
				}
			}
			else
			{
				foreach($list  as $rs)
				{
					$str=$str."<option  value='".$rs['ClassName']."' style='background:#efefef;'>". $rs['ClassName'] ."</option>";	 
				}
			}
			$str=$str."</select>";
			return $str;
		}
		
		
		
		
		//HE 根据USERID查询实体
		public function GetModel($uid){
			try {  
				$nmodel= new UserInfo();
				$sql="select * from t_user where userid='".$uid."'" ;	
				$result=$this->conn->query($sql);
				 
				while($row=$this->conn->fetch_array()){
					 $nmodel->UserId=$row['UserId'];
					 $nmodel->Email=$row['Email'];
					 $nmodel->Name=$row['Name'];
					 $nmodel->Position=$row['Position'];
					 $nmodel->Group=$row['Group'];
					 $nmodel->QOC_Role=$row['QOC_Role'];
					 $nmodel->Region=$row['Region'];
					  $nmodel->Channel=$row['Channel'];
				 }
				return $nmodel;
			} catch (Exception $e) {  
					return NULL;
			}   	 
		}
		/**
		 * HE 根据USERID查询实体
		 * @author HE
		 * @param $username 会员名称
		 * @return UserInfo实体
		 */
		public function GetModel2($username){
			try {  
				$nmodel= new UserInfo();
				$sql="select * from t_user where Email='".$username."'" ;	
				$result=$this->conn->query($sql);
				 
				while($row=$this->conn->fetch_array()){
					 $nmodel->UserId=$row['UserId'];
					 $nmodel->Email=$row['Email'];
					 $nmodel->Name=$row['Name'];
					 $nmodel->Position=$row['Position'];
					 $nmodel->Group=$row['Group'];
					 $nmodel->QOC_Role=$row['QOC_Role'];
					 $nmodel->Region=$row['Region'];
					  $nmodel->Channel=$row['Channel'];
				 }
				return $nmodel;
			} catch (Exception $e) {  
					return NULL;
			}   	 
		}
		
		
			///计算总数
		public function GetCount($sqlwhere='')
		{
			$sql="select userid from t_user a  where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$result=$this->conn->query($sql);
			$num= $this->conn->num_rows();
			return $num;
		}

			///计算总数
		public function GetClickCount($sqlwhere='')
		{
			$sql="select userid from t_click_total a  where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$result=$this->conn->query($sql);
			$num= $this->conn->num_rows();
			return $num;
		}		

			///计算总数
		public function GetClickTotalCount($sqlwhere='')
		{
			$sql="SELECT DATE_FORMAT(`times`,'%Y%m%d%H') as hh,count(*) as cnt FROM `t_click_total` Where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$result=$this->conn->query($sql."  group by hh");
			$num= $this->conn->num_rows();
			return $num;
		}	

			///计算总数
		public function GetNewsTotalCount($sqlwhere='')
		{
			$sql="SELECT n.title ";
			$sql.="FROM  `t_total_news` a LEFT JOIN t_product_class b ON a.bid = b.classid LEFT JOIN t_product_class s ON a.sid = s.classid LEFT JOIN t_news n ON a.newsid = n.id Where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			//echo "aa".$sql;
			$result=$this->conn->query($sql." group by n.title, b.classname, s.classname ");
			$num= $this->conn->num_rows();
			return $num;
		}	

			///计算总数
		public function GetUserTotalCount($sqlwhere='')
		{
			$sql="SELECT userid ";
			$sql.="FROM  `t_total_news` a Where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			//echo "aa".$sql;
			$result=$this->conn->query($sql);
			$num= $this->conn->num_rows();
			return $num;
		}	
	 
		///分页类 HE
		public function GetPageList($sqlwhere='',$page=1,$pagesize=20)
		{
		
			$rss=array();
			$sql="select a.* from t_user  a ";
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
		
	 
		
		///分页类 HE
		public function GetClickPageList($sqlwhere='',$page=1,$pagesize=20)
		{
		
			$rss=array();
			$sql="select a.* from t_click_total  a ";
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
		
		///分页类 HE
		public function GetClickPageTotal($sqlwhere='',$page=1,$pagesize=20)
		{
		
			$rss=array();
			$sql="SELECT DATE_FORMAT(`times`,'%Y%m%d%H') as hh,count(*) as cnt FROM `t_click_total` ";
			$sql=$sql." where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql." group by hh limit $page,$pagesize";

			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rss[]=$row;
				
			 }
			 return $rss;
		}
	 
		///分页类 HE
		public function GetNewsPageTotal($sqlwhere='',$page=1,$pagesize=20)
		{
		
			$rss=array();
			$sql="SELECT a.newsid,n.title, b.classname as bname, s.classname as sname,";
			$sql.="sum(case hour(createtime) when 0 then 1 else 0 end) as d0,";
			$sql.="sum(case hour(createtime) when 1 then 1 else 0 end) as d1,";
			$sql.="sum(case hour(createtime) when 2 then 1 else 0 end) as d2,";
			$sql.="sum(case hour(createtime) when 3 then 1 else 0 end) as d3,";
			$sql.="sum(case hour(createtime) when 4 then 1 else 0 end) as d4,";
			$sql.="sum(case hour(createtime) when 5 then 1 else 0 end) as d5,";
			$sql.="sum(case hour(createtime) when 6 then 1 else 0 end) as d6,";
			$sql.="sum(case hour(createtime) when 7 then 1 else 0 end) as d7,";
			$sql.="sum(case hour(createtime) when 8 then 1 else 0 end) as d8,";
			$sql.="sum(case hour(createtime) when 9 then 1 else 0 end) as d9,";
			$sql.="sum(case hour(createtime) when 10 then 1 else 0 end) as d10,";
			$sql.="sum(case hour(createtime) when 11 then 1 else 0 end) as d11,";
			$sql.="sum(case hour(createtime) when 12 then 1 else 0 end) as d12,";
			$sql.="sum(case hour(createtime) when 13 then 1 else 0 end) as d13,";
			$sql.="sum(case hour(createtime) when 14 then 1 else 0 end) as d14,";
			$sql.="sum(case hour(createtime) when 15 then 1 else 0 end) as d15,";
			$sql.="sum(case hour(createtime) when 16 then 1 else 0 end) as d16,";
			$sql.="sum(case hour(createtime) when 17 then 1 else 0 end) as d17,";
			$sql.="sum(case hour(createtime) when 18 then 1 else 0 end) as d18,";
			$sql.="sum(case hour(createtime) when 19 then 1 else 0 end) as d19,";
			$sql.="sum(case hour(createtime) when 20 then 1 else 0 end) as d20,";
			$sql.="sum(case hour(createtime) when 21 then 1 else 0 end) as d21,";
			$sql.="sum(case hour(createtime) when 22 then 1 else 0 end) as d22,";
			$sql.="sum(case hour(createtime) when 23 then 1 else 0 end) as d23 ";
			$sql.="FROM  `t_total_news` a LEFT JOIN t_product_class b ON a.bid = b.classid LEFT JOIN t_product_class s ON a.sid = s.classid LEFT JOIN t_news n ON a.newsid = n.id Where 1=1 ";

			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql."  group by a.newsid,n.title, b.classname, s.classname limit $page,$pagesize";

			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rss[]=$row;
				
			 }
			 return $rss;
		}

		///分页类 HE
		public function GetUserPageTotal($sqlwhere='',$page=1,$pagesize=20)
		{
		
			$rss=array();
			$sql="SELECT a.newsid,n.title, b.classname as bname, s.classname as sname,";
			$sql.="a.userid,a.createtime ";
			$sql.="FROM  `t_total_news` a LEFT JOIN t_product_class b ON a.bid = b.classid LEFT JOIN t_product_class s ON a.sid = s.classid LEFT JOIN t_news n ON a.newsid = n.id where 1=1 ";

			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql."  limit $page,$pagesize";

			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rss[]=$row;
				
			 }
			 return $rss;
		}
		
		//登录 HE
		public function Login($username)	{
			$sql="select  userid from t_user where Email='$username' ";
			$result=$this->conn->query($sql);
			$count=$this->conn->num_rows();
			return $count;
		}
		
		public function GetTopId($sql)
		{
			$t=0;	 
 			$result=$this->conn->query($sql);
			$row=$this->conn->fetch_array();
			if($row!=NULL)
			{
				$t= $row[0];
				 
			}
			return $t;
		}
	 
		
 }
	
	 
?>