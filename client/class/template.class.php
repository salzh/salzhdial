<?php 
	///==========================================新闻实体==========================================类
	class Info{
		public $id;
		public $title;
		public $templateId;
		public $bid;
		public $sid;
		public $bbid;
		public $ssid;
		public $shows;
		public $hit;
		public $msgnum;
		public $remsgnum;
		public $times;
		public $deptId;
		public $areaId;
		public $idx;
		public $typesId;
		public $issn;
	}
	
	class TemplateInfo{
		public $id;
		public $title;
		public $templateName;
		public $ifValid;
	}
	
	class TemplateItem{
		public $id;
		public $templateId;
		public $itemName;
		public $itemType;
		public $tableName;
		public $itemTitle;
		public $bgColor;
		public $titleColor;
		public $rowColor;
		public $ifvalid;
		public $detailField;
		public $detailValue;
		public $idx;
	}	
	
	class TemplateItemField{
		public $id;
		public $templateId;
		public $itemId;
		public $fieldName;
		public $fieldType;
		public $fieldTitle;
		public $fieldColor;
		public $width;
		public $ifvalid;
		public $shows;
	}

	class InfoClass{
		
		public $conn;
		public function __construct()
		{
			$this->conn=new mysql();
			
		}
		
		//析构
		public function __destruct()
		{
			 
		}
		
		///返回实体
		public function GetModel($sql,$nmodel)
		{
		
			try {
				$result=$this->conn->query($sql);
				while($row=$this->conn->fetch_array())
				{
					foreach ($nmodel as $k=>$v) {
						if(substr($k,0,6)!="detail")  //&&$k!="shows"
							$nmodel->$k=$row[$k];
    					//echo 'key=',$k,' value=',$v,"\n";
					}
				 }
				return $nmodel;
			} catch (Exception $e) {  
				return NULL;
			}   	 
		}	
		
		///返回列表
		public function GetModelList($sql,$nmodel)
		{
			$rss=array();
			$result=$this->conn->query($sql);
			 while($row= $this->conn->fetch_array())
			 {
				foreach ($nmodel as $k=>$v) {
					if(substr($k,0,6)!="detail"&&$k!="shows")
						$nmodel->$k=$row[$k];
					//echo 'key=',$k,' value=',$v,"\n";
				}
				$mmodel=clone $nmodel; //复制对象clone
			 	$rss[]= $mmodel;
			 }
			 return $rss;			 
		}				
		
		///返回信息实体
		public function GetInfoModel($Id)
		{
			$nmodel= new Info();
			$sql="select * from v_info where Id=".$Id ;	
			return $this->GetModel($sql,$nmodel); 	 
		}
		
		///返回主模板信息实体
		public function GetTemplateModel($Id)
		{
			$nmodel= new TemplateInfo();
			if($Id==0)
			{
				$sql="select * from t_template";
				return $this->GetModelList($sql,$nmodel); 	
			}
			else
			{
				$sql="select * from t_template where Id=".$Id ;	
				return $this->GetModel($sql,$nmodel); 	 
			}
		}		
		
		///返回主模板信息实体
		public function GetTemplateList($Id)
		{
			$nmodel= new TemplateInfo();
			if($Id==0)
			{
				$sql="select * from t_template";
				return $this->GetModelList($sql,$nmodel); 	
			}
			else
			{
				$sql="select * from t_template where Id=".$Id ;	
				return $this->GetModelList($sql,$nmodel); 	
			}
		}			
	
		///返回模板项信息实体
		public function GetTemplateItemModel($templateId,$id=0)
		{
			$nmodel= new TemplateItem();
			if($id==0)
			{
				$sql="select * from t_template_item where ifvalid=1 and templateId=".$templateId ;
				return $this->GetModelList($sql,$nmodel); 	 
			}
			else
			{
				$sql="select * from t_template_item where id=".$id ;
				return $this->GetModel($sql,$nmodel); 
			}
		}
		
		///返回模板项信息实体
		public function GetTemplateItemList($templateId,$id=0)
		{
			$nmodel= new TemplateItem();
			if($id==0)
			{
				$sql="select * from t_template_item where templateId=".$templateId ;
				return $this->GetModelList($sql,$nmodel); 	 
			}
			else
			{
				$sql="select * from t_template_item where id=".$id ;
				return $this->GetModelList($sql,$nmodel); 	 
			}
		}		
		
				///返回模板项信息实体
		public function GetTemplateTable($id,$typeId=0,$templateId=0)
		{
			$nmodel= new TemplateItem();
			if($typeId==0)
			{
				$sql="select * from t_template_item where id=".$id ;
			}
			else
			{
				$sql="select * from t_template_item where itemType=".$typeId." and templateId=".$templateId ;
			}
			$nmodel=$this->GetModel($sql,$nmodel); 
			return $nmodel->tableName;
		}	
		
		///返回模板项字段实体
		public function GetTemplateItemFieldModel($Id)
		{
			$nmodel= new TemplateItemField();
			$sql="select * from t_template_item_field where itemId=".$Id." order by idx" ;	
			return $this->GetModelList($sql,$nmodel); 	 
		}			
		
		///返回列表
		public function GetTableList($sql)
		{
			$rss=array();
			 
			$result=$this->conn->query($sql);
			 while($row= $this->conn->fetch_array())
			 {
			 	$rss[]=$row;
			 }
			 return $rss;			 
		}	
		
		///返回列表
		public function GetDetailList($tableName,$id,$wheredetail="")
		{
			$where=" where 1=1";
			if($id!=0)
			{
				$where.=" and parentid=".$id;
			}
			$sql="select * from ".$tableName.$where." ".$wheredetail;
			return $this->GetTableList($sql);			 
		}				
		
		public function GetComboList($tableName,$fieldName="id,itemName",$id=0,$comboName,$changeJS="",$parentId="",$disable="",$whereDetail="")
		{
			$str="<select id='$comboName' name='$comboName' style='font-size:15px; color:#666; font-weight:bold;' ".($changeJS!=""?" onchange='$changeJS'":"")." ".$disable.">";
			$fieldValue=explode(",",$fieldName);
			$sqlwhere="";
			if($id==0)
			{
				$str=$str."<option  value='0' style='background:#efefef;'  selected>--请选择--</option>";
			}
			if($parentId!="")
			{
				$sqlwhere=" and templateid=".$parentId;
			}
			$list= $this->GetDetailList($tableName,0,$sqlwhere.$whereDetail);
			foreach($list as $rs)
			{
				$str=$str."<option  value='".$rs[$fieldValue[0]]."' style='background:#efefef;' ".($id==$rs[$fieldValue[0]]?"selected":"").">". $rs[$fieldValue[1]]."</option>";
			}
			$str.="</select>";
			return $str;	
		}

		
		
		
		
		///添加新闻分类
		public function AddNewsClass($NewsClassInfo)
		{
			 
			$table="t_news_class";
			$v=$NewsClassInfo->ClassName ."','".$NewsClassInfo->OrderId."','".$NewsClassInfo->Content."','".$NewsClassInfo->Des."','".$NewsClassInfo->KeyWord."','". $NewsClassInfo->Bid;
			$this->conn->insert($table,"classname,orderid,content,des,keyword,bid",$v);
			return $this->conn->insert_id();
		}
		
	
		///修改新闻分类
		public function EditNewsClass($NewsClassInfo)
		{
		
			try {  
				$table="t_news_class";
				$v="classname='".$NewsClassInfo->ClassName."',orderid='".$NewsClassInfo->OrderId."',content='".$NewsClassInfo->Content."',des='".$NewsClassInfo->Des."',keyword='".$NewsClassInfo->KeyWord."',bid=". $NewsClassInfo->Bid;
				$this->conn->update($table,$v," Classid=".$NewsClassInfo->ClassId);
					return $NewsClassInfo->ClassId;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
		
		///返回实体
		public function GetClassModel($classid)
		{
		
			try {  
				$nmodel= new NewsClassInfo();
				$sql="select * from t_news_class where classId=".$classid ;	
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
		
		
		
		
		///返回品牌下拉
		public function GetBrandSelect($type="add",$bid=0,$sid=0,$tid=0)
		{ 
			$str="<select name='brandid' style='font-size:15px; color:#666; font-weight:bold; margin:5px;padding:15px;'>";
			$list=$this->GetBrandClassList();
			
			if($type=="add") //添加
			{
				foreach($list as $rs)
				{
					$str=$str."<option  value='".$rs['ClassId']."' style='background:#efefef;'>". $rs['ClassName'] ."</option>";
					$list2=$this->GetBrandClassList($rs['ClassId']);
					foreach($list2 as $rs2)
						{
							
							$str=$str."<option  value='".$rs2['ClassId']."'   >&nbsp;&nbsp;|--". $rs2['ClassName'] ."</option>";
							$list3=$this->GetBrandClassList($rs2['ClassId']);
							foreach($list3 as $rs3)
							{
								$str=$str."<option  value='".$rs3['ClassId']."'   >&nbsp;&nbsp;&nbsp;&nbsp;└──". $rs3['ClassName'] ."</option>";
							}
						}
				}
			}
			else
			{			
			if($type=='all')
			{
				$str=$str."<option  value='0' style='background:#efefef;'>全部</option>";	
			}
							foreach($list as $rs)
								{
									if ($bid==$rs['ClassId'] && $sid==0 && $tid==0)
										{
											$s="selected=selected";
										}
										else
										{
											$s="";
										}
									$str=$str."<option  value='".$rs['ClassId']."' style='background:#efefef;' ".$s.">". $rs['ClassName'] ."</option>";
									$list2=$this->GetBrandClassList($rs['ClassId']);
									foreach($list2 as $rs2)
										{
											if ($sid==$rs2['ClassId']    && $tid==0)
												{
													$s2="selected=selected";
												}
												else
												{
													$s2="";
												}
												$str=$str."<option  value='".$rs2['ClassId']."' ".$s2." >&nbsp;&nbsp;|--". $rs2['ClassName'] ."</option>";
													$list3=$this->GetBrandClassList($rs2['ClassId']);
													 
													foreach($list3 as $rs3)
													{
														if ($tid==$rs3['ClassId'])
														{
															$s3="selected=selected";
														}
														else
														{
															$s3="";
														}
														$str=$str."<option  value='".$rs3['ClassId']."'  ".$s3." >&nbsp;&nbsp;&nbsp;&nbsp;└──". $rs3['ClassName'] ."</option>";
													}
										}
								}
			}
			$str=$str."</select>";
			return $str;
		}
		
		
		
			///根据类ID，返回BID SID
		public function GetBrandBidSid($classid)
		{
			$sql="select classid,bid from t_product_class where classid=".$classid;	
			$result=$this->conn->query($sql);
			$row= $this->conn->fetch_array();
			 	if($row[1]==0)
				{
					$rss["bid"]=$row[0];
					$rss["sid"]=0;
					$rss["tid"]=0;
				}
				else 
				{
					$sql1="select classid,bid from t_product_class where classid=".$row[1];	
					$result1=$this->conn->query($sql1);
					$row1= $this->conn->fetch_array();
					if($row1['bid']>0)
					{
							
								$rss["bid"]=$row1['bid'];
								$rss["sid"]=$row['bid'];
								$rss["tid"]=$row[0];
					}
					else
					{
						$rss["bid"]=$row[1];
						$rss["sid"]=$row[0];
						$rss["tid"]=0;
					}
						
			 		
			 	}
			 return $rss;
		}
		
		
		
		///返回类ID 和 父类ID
		public function GetClassId($classid)
		{
			$sql="select classid,bid from t_news_class where classid=".$classid;	
			$result=$this->conn->query($sql);
			$row= $this->conn->fetch_array();
			 
			 	$rss["classid"]=$row[0];
				$rss["bid"]=$row[1];
			 
			 return $rss;
		}
		
		
		///根据类ID，返回BID SID
		public function GetBidSid($classid)
		{
			$sql="select classid,bid from t_news_class where classid=".$classid;	
			$result=$this->conn->query($sql);
			$row= $this->conn->fetch_array();
			 	if($row[1]==0)
				{
					$rss["bbid"]=$row[0];
					$rss["ssid"]=0;
				}
				else
				{
			 		$rss["bbid"]=$row[1];
					$rss["ssid"]=$row[0];
			 	}
			 return $rss;
		}
		
		
		///删除
		public function DelNewsClass($classid)
		{
			$t=" t_news_class";
			$w=" classid=".$classid;
			$this->conn->delete($t,$w); 
		}
		
		///是否有子类
		public function IfBid($classid)
		{
			$sql="select classid from t_news_class where bid=".$classid ;
			$result=$this->conn->query($sql);
			$count=$this->conn->num_rows();
			return $count;
		}
		
		
		public function GetChkList($bid=0)
		{
			$str=""; 
			$list=$this->GetClassList();
			if($bid!="0")
			{
				foreach($list as $rs)
				{
 						if(strrpos(",".$bid.",", ",".$rs["ClassId"].",")>-1)
						{
						  $str=$str."<input name='bid[]' type='checkbox' id='bid'  checked='checked' value='".$rs['ClassId']."' /> "  . $rs['ClassName'] ."";
						}
						else
						{
						  $str=$str."<input name='bid[]' type='checkbox' id='bid'   value='".$rs['ClassId']."' /> "  . $rs['ClassName'] ."";
						}
				}
			}
			else
			{
				foreach($list as $rs)
				{
					$str=$str."<input name='bid[]' type='checkbox' id='bid'   value='".$rs['ClassId']."' /> "  . $rs['ClassName'] ."";
				}
			}
			return $str;	
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
		
		
		
		
		///返回区域
		public function GetAreaSelect($bid=0)
		{
			
			$list=array();
			$sqls="select * from t_area_class where bid='0' order by orderid asc,classid desc";
			$results=$this->conn->query($sqls);
			 $list[]= $this->conn->fetch_array();
			$str="<select name='areaid' style='font-size:15px; color:#666; font-weight:bold; margin:5px;padding:15px;'>";
			if($bid!="0")
			{
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
					$str=$str."<option  value='".$rs['ClassId']."' style='background:#efefef;'  ".$s.">". $rs['ClassName'] ."</option>";
				}
			}
			else
			{
				foreach($list as $rs)
				{
					$str=$str."<option  value='".$rs['ClassId']."' style='background:#efefef;'>". $rs['ClassName'] ."</option>";	 
				}
			}
			$str=$str."</select>";
			return $str;
		}
		
		
		///返回
		public function GetCmbSelect($ClassType,$cmbName,$selId="")
		{
			
			$list=array();
			$sqls="select * from t_dic where ClassType=".$ClassType." order by idx";
			$list=$this->GetTableList($sqls);

			$str="<select name='".$cmbName."' style='font-size:15px; color:#666; font-weight:bold; margin:5px;padding:15px;'>";

				foreach($list as $rs)
				{
				
						if ($selId==$rs['ClassName'])
						{
							$s="selected=selected";
						}
						else
						{
							$s="";
						}
					$str=$str."<option  value='".$rs['ClassName']."' style='background:#efefef;'  ".$s.">". $rs['ClassName'] ."</option>";
				}
				
			$str=$str."</select>";
			return $str;
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
				
				$str=$str."&nbsp;其他:<input name='".$cmbName."[]' type='text' value='".$other."' />";
				return $str;
		}
		
		public function GetOption($sql,$cmbName,$selId="")
		{
			$list=array();
			$sqls=$sql;
			$list=$this->GetTableList($sqls);

			//echo $other."|";
				foreach($list as $rs)
				{
 						if(strrpos(",".$selId.",", ",".$rs["ClassId"].",")>-1)
						{
						  $str=$str."<input name='".$cmbName."[]' type='checkbox' checked value='".$rs['ClassId']."' /> "  . $rs['ClassName'] ."";
						}
						else
						{
						  $str=$str."<input name='".$cmbName."[]' type='checkbox' value='".$rs['ClassId']."' /> "  . $rs['ClassName'] ."";
						}
				}

				return $str;
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
			$sql="select * from t_news_class where bid=".$bid." order by orderid asc,classid desc";
			 
			$result=$this->conn->query($sql);
			 while($row= $this->conn->fetch_array())
			 {
			 	$rss[]=$row;
			 }
			 return $rss;
			 
		}
		
		
		
			///返回分类列表
		public function GetBrandClassList($bid=0)
		{
			$rss=array();
			$sql="select * from t_product_class where bid='".$bid."' order by orderid asc,classid desc";
			$result=$this->conn->query($sql);
			 while($row= $this->conn->fetch_array())
			 {
			 	$rss[]=$row;
			 }
			 return $rss;
			 
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
		
		
		
		///返回最大的排序ID
		public function GetMaxOrderId()
		{
		
			$sql="select max(orderid) from t_news_class";	
			$result=$this->conn->query($sql);
			$rs=$this->conn->fetch_array();
			$orderid = $rs[0];
			return $orderid;
		}
		
		///根据类ID返回 类名
		public function GetClassName($ClassId)
		{
			$sql="select classname from t_news_class where classId=".$ClassId ;	
			$result=$this->conn->query($sql);
			$row=$this->conn->fetch_array();
			$classname = $row[0];
			return $classname;
		}
		
		
				//删除
		public function DelInfo($id)
		{
			try{
				$t=" v_info";
				$w=" id in(".$id.")";
				$this->conn->delete($t,$w); 
				return true;
			}
			catch (Exception $e) {  
				return false;
			}   
		}
		
				//删除
		public function DelItem($itemId,$infoId)
		{
			$ArrayId = explode(",", $itemId);
			foreach($ArrayId as $rsid)
			{
				$templateItem=$this->GetTemplateItemModel(0,$rsid);
				$this->DelRecord($templateItem->tableName,0,$infoId);
			}
			return 1;
		}		
		
			///计算总数
		public function GetCount($sqlwhere='')
		{
			$sql="select id from v_info a  where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$result=$this->conn->query($sql);
			$num= $this->conn->num_rows();
			return $num;
		}
		
		///分页类
		public function GetPageList($sqlwhere='',$page=1,$pagesize=20)
		{
		
			$rss=array();
			$sql="select a.*,b.classname as bname,c.classname as sname from v_info  a ";
			$sql=$sql." left join t_product_class b on b.classid=a.bid ";
			$sql=$sql." left join t_product_class c on c.classid=a.sid ";
			$sql=$sql." where 1=1 ";
			if($sqlwhere!='')
			{
				$sql=$sql. $sqlwhere;
			}
			$sql=$sql." order by id desc limit $page,$pagesize";
		 
		 
			//secho $sql;
			$result=$this->conn->query($sql);
			while($row= $this->conn->fetch_array())
			{
				 $rss[]=$row;
				
			 }
			 return $rss;
		}
		
				///添加文章
		public function AddInfo($InfoValue){
			$table="v_info";
 			//var_dump($InfoValue);
			$v=$InfoValue->title."','";
			$v.=$InfoValue->issn."','";			
			$v.=$InfoValue->templateId."','";
			$v.=$InfoValue->bid."','";
			$v.=$InfoValue->sid."','";
			$v.=$InfoValue->shows."','";
			$v.=$InfoValue->times."','";
			$v.=$InfoValue->deptId."','";
			$v.=$InfoValue->areaId."','";
			$v.=$InfoValue->idx."','";
			$v.=$InfoValue->bbid."','";
			$v.=$InfoValue->ssid."','";			
			$v.=$InfoValue->typesId;
			
		//	echo $v;
			 
			$this->conn->insert($table,"title,issn,templateid,bid,sid,shows,times,deptid,areaid,idx,bbid,ssid,typesid",$v);
			return $this->conn->insert_id();	
		}
		
		
		///修改文章
		public function EditInfo($InfoValue)
		{
			$table="v_info";
 			//var_dump($InfoValue);
			$v="title='".$InfoValue->title."',";
			$v="issn='".$InfoValue->issn."',";
			$v.="templateId='".$InfoValue->templateId."',";
			$v.="bid='".$InfoValue->bid."',";
			$v.="sid='".$InfoValue->sid."',";
			$v.="shows='".$InfoValue->shows."',";
			$v.="times='".$InfoValue->times."',";
			$v.="deptId='".$InfoValue->deptId."',";
			$v.="areaId='".$InfoValue->areaId."',";
			$v.="idx='".$InfoValue->idx."',";
			$v.="bbid='".$InfoValue->bbid."',";
			$v.="ssid='".$InfoValue->ssid."',";
			$v.="typesId='".$InfoValue->typesId."'";		 
			try {
				$this->conn->update($table,$v," id=".$InfoValue->Id);
					return $InfoValue->Id;
				} 
			catch (Exception $e) {  
					return 0;
				}   
		}
		
		public function InsertRecord($table,$field,$value)
		{
			$value=substr($value,1,strlen($value)-2);
			$this->conn->insert($table,$field,$value);
			return $this->conn->insert_id();	
		}
		
		public function UpdateRecord($table,$field,$key)
		{
			$this->conn->update($table,$field," id=".$key);
			return 1;
		}		

		public function DelRecord($table,$id,$parentId=0)
		{
			if($parentId==0)
			{
				$w=" id in(".$id.")";
			}
			else
			{
				$w=" parentid = ".$parentId." ";
			}
			$this->conn->delete($table,$w); 
			return 1;
		}
	}
	
	/////==========================================新闻操作类//==========================================
	/////===============================================================================================
	class News{
	
		public function __construct()
		{
			$this->conn=new mysql();
			
		}
		
		
		
		
		//返回列表
		public function GetNext($id,$bid=0)
		{
			$sql="select id,title from t_news where bid=$bid and id<$id and shows='yes' order by id desc limit 1";
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
			$sql.='select id,title,bid,pic,times,scontent from t_news where 1=1   ';
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
			$sql="select id,title from t_news where bid=$bid and id>$id and shows='yes' order by id asc limit 1";
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
		
		
		
		public function GetModel($newsid)
		{
			$rss=array();
			try {  
				 
				$sql="select * from t_news where id=".$newsid ;	
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