<?php

include("../check.php");
include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/product.class.php");
include("../../class/sc.class.php");
include("../../appeditor/fckeditor.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>产品管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/fun.js"></script>
<style>
	form td{padding:3px;}
	td{font-size:12px;}
 
 


</style>
</head>

<body>
<?
 function prolist($a)
{
	$qxpid="";
	$bbb = explode(",", $a);
	for($i=0;$i<count($bbb);$i++)
	{
		if($i==0)
		{
			if($bbb[$i]=="" || $bbb[$i]==NULL)
			{
			}
			else
			{
				$qxpid.=cint($bbb[$i]);
			}
			
		}
		else
		{
			if($bbb[$i]=="" || $bbb[$i]==NULL)
			{
			}
			else
			{
				$qxpid.=",".cint($bbb[$i]);
			}
		}
	}
	return $qxpid;
 
}
$productclass= new ProductClass();
$productDal=new Product();
$sc=new SCClass();


$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'add';
if($a=="addsave" || $a=="modifysave")
{
	$title=$_POST["title"];
	if($title=='')
	{
		$title='未命名';
	}
	$hits=0;
	$orderid=cint(trim($_POST['orderid']));
	$content= isset($_REQUEST['content']) ? $_REQUEST['content'] : '';
	$author= '';
	$froms="";
	$shows= isset($_POST["shows"]) ? $_POST["shows"] : '';
	// 
	 
	$pic=$_POST["pic"];
	$recommand=  isset($_POST["recommand"]) ? $_POST["recommand"]: '';
	$hot=isset($_POST["hot"]) ? $_POST["hot"]: 'no';
	$indexs=isset($_POST["indexs"]) ? $_POST["indexs"]: 'no';
	$buycontent=isset($_POST["buycontent"]) ? $_POST["buycontent"]: '';
	$jishu=isset($_POST["jishu"]) ? $_POST["jishu"]: '';
	$cid=isset($_POST["cid"]) ? $_POST["cid"]: '0';
	$scontent=$_POST["scontent"];
	$times=date('Y-m-d');
	$pic_s="";
	$classid=cint($_POST["bid"]);
	$des=$_POST["des"];
	$keywords=$_POST["keywords"];
	$likewords=$_POST['likewords'];
	
	
	
	$aa=array();
	$aa=$productclass->GetBidSid($classid);
	$bid=$aa['bid'];
	$sid=$aa['sid'];
	$tid=$aa['tid'];
 
	
	$product=new ProductInfo();
	$product->Title=$title;
	$product->Content=$content;
	$product->Scontent=$scontent;
	$product->Author=$author;
	$product->Times=$times;
	$product->Froms=$froms;
	$product->Indexs=$indexs;
	$product->OrderId=$orderid;
	$product->Shows=$shows;
	$product->Recommand=$recommand;
	$product->Hits=$hits;
	$product->Hot=$hot;
	$product->Bid=$bid;
	$product->Sid=$sid;
	$product->Tid=$tid;
	$product->Des=$des;
	$product->KeyWords=$keywords;
	$product->Pic=$pic;
	$product->Cid=$cid;
	$product->BuyContent=$buycontent;
	$product->Jishu=$jishu;

	$product->Pic_s=$pic_s;
	$product->LikeWords=$likewords;
	$listkey=null;
	 $listkey=explode(",",$likewords);
 	 
}
///添加保存
if($a=="addsave")
{	
	$newid=$productDal->AddProduct($product);
	if($newid>0)
	{
		$productDal->AddLog($_COOKIE['username'],$newid,$title,"增加".$title."");	
		
		if($listkey!=NULL)
		{
			foreach($listkey as $lk)
			{
				if($lk!="")
				{
					if($productDal->GetIsKey($lk)>0)
					{
					
					}
					else
					{
						$productDal->AddKey($lk);
					}
				}
			}
		}
	
	
		echo Msg("操作成功","up_product.php?action=modify&id=$newid");
	}

}

if ($a=="modifysave")
{
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
	$product->Id=$id;
	if($id>0)
	{
			 
		if($productDal->EditProduct($product)>0)
		{
				$productDal->AddLog($_COOKIE['username'],$id,$title,"修改".$title."");	
				if($listkey!=NULL)
				{
					foreach($listkey as $lk)
					{
						if($lk!="")
						{
							if($productDal->GetIsKey($lk)>0)
							{
							
							}
							else
							{
								$productDal->AddKey($lk);
							}
						}
					}
				}
			 echo Msg("操作成功","admin_product.php?action=list");
		}
		else
		{
			 echo Msg("操作失败","back");
		}
	}
}
?>

<div class="box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="75%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">产品管理</span></td>
      <td width="25%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;</td>
    </tr>
  </table>
<?php
 
if($a=="add")
{
?>
  <br />
  <table width="500" height="37" border="0" cellpadding="0" cellspacing="2" bgcolor="#FFFFFF">
    <tr>
      <td width="100" bgcolor="#0099FF"><div align="center"><a href="#" style="color:#fff;"><strong>产品基础信息</strong></a></div></td>
      <td width="100" bgcolor="#efefef"><div align="center" style="color:#999999; cursor:pointer;" onclick="javascript:alert('请先保存产品基础信息')">产品图片</div></td>
      <td width="100" bgcolor="#efefef"><div align="center" style="color:#999999; cursor:pointer;" onclick="javascript:alert('请先保存产品基础信息')">实用指南</div></td>
      <td width="100" bgcolor="#efefef"><div align="center" style="color:#999999; cursor:pointer;" onclick="javascript:alert('请先保存产品基础信息')">相关产品</div></td>
      <td width="100" bgcolor="#efefef"><div align="center" style="color:#999999; cursor:pointer;" onclick="javascript:alert('请先保存产品基础信息')">服务支持</div></td>
    </tr>
  </table>
  <form id="form1" name="form1" method="post" action="?action=addsave">
    <table width="100%" height="534" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="7%" height="40" bgcolor="#FFFFFF"><div align="center">标 题 </div></td>
        <td width="93%" height="40" bgcolor="#FFFFFF"><input name="title" type="text" id="title" size="50" maxlength="100" />
          &nbsp;排序
          <input name="orderid" type="text" id="orderid" value="1" style="ime-mode:disabled" onKeyPress="if ((event.keyCode<48 || event.keyCode>57)) event.returnValue=false"  onkeydown="if(event.keyCode==13)event.keyCode=9"  size="5" maxlength="5" />
        &nbsp;</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">缩略图</div></td>
        <td height="40" bgcolor="#FFFFFF">
		    
										  
							 
											  <table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr>
												  <td width="44%"><iframe src="../up.php" width="500"  scrolling="No" height="100" frameborder="0"></iframe>                                              </td>
												  <td width="56%"><img src="../../pic/logo.jpg" name="img" width="100" height="100" id="img"  onload="javascript:Simg(this,100,100);"   /></td>
												</tr>
												<tr>
												  <td>路径：
												  <input name="pic" type="text" id="pic" value="/pic/no.jpg" size="80" maxlength="100" /></td>
												  <td>&nbsp;</td>
												</tr>
											  </table>	 	</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">分类</div></td>
        <td height="40" bgcolor="#FFFFFF">
		<div id=box><div id=box2>
		<?
		 
		echo $productclass->GetSelect();
		?>
		 
		</div></div>		</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF">市场需求分类</td>
        <td height="40" bgcolor="#FFFFFF"><?
		echo $sc->GetSelect();
		?></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">简介</div></td>
        <td height="40" bgcolor="#FFFFFF"><textarea name="scontent" id="scontent" style="height:80px; width:400px;overflow-y:auto"></textarea></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">详细内容</div></td>
        <td height="40" bgcolor="#FFFFFF">
								<?php 
										 
									Editor("content","../","");
								?>								</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">购买信息</div></td>
        <td height="40" bgcolor="#FFFFFF"><?php 
										 
									Editor("buycontent","../","");
								?></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF">技术规格（仅供会员查看）</td>
        <td height="40" bgcolor="#FFFFFF"><?php 
										 
									Editor("jiushu","../","");
								?></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">Keywords</div></td>
        <td height="40" bgcolor="#FFFFFF"><input name="keywords" type="text" id="keywords" size="50" maxlength="200" /></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">description</div></td>
        <td height="40" bgcolor="#FFFFFF"><input name="des" type="text" id="des" size="50" maxlength="200" /></td>
      </tr>
      
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">属性</div></td>
        <td height="40" bgcolor="#FFFFFF">推荐：
          <input name="recommand" type="checkbox" id="recommand" value="yes" checked="checked" /> 
         &nbsp; &nbsp;&nbsp; 热门：
         <input name="hot" type="checkbox" id="hot" value="yes" checked="checked" />
         &nbsp; &nbsp;&nbsp; 首页显示：
         <input name="indexs" type="checkbox" id="indexs" value="yes" checked="checked" />
         &nbsp;   
        &nbsp;&nbsp; 发布：
           <input name="shows" type="checkbox" id="show" value="yes" checked="checked" />
&nbsp;&nbsp;</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">关键字</div></td>
        <td height="40" bgcolor="#FFFFFF"><input name="likewords" type="text" id="likewords" size="50" maxlength="200" />
        请输入关键词，用，分开 如：数码，相机，手机 </td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center"></div></td>
        <td height="40" bgcolor="#FFFFFF"><input type="submit" name="Submit" class="bt" value="保存信息" />
        <input type="reset" name="Submit2" value="取消" class="bt" /></td>
      </tr>
    </table>
  </form>
<?
}
?>  
  
  
<?
	if ($a=="modify")
	{
		$id=isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	 	$rs=$productDal->GetModel($id);

?>  
    <form id="form1" name="form1" method="post" action="?action=modifysave">
    <table width="500" height="37" border="0" cellpadding="0" cellspacing="2" bgcolor="#FFFFFF">
      <tr>
        <td width="100" bgcolor="#0099FF"><div align="center"><a href="#" style="color:#fff;"><strong>产品基础信息</strong></a></div></td>
        <td width="100" bgcolor="#efefef"><div align="center" style="color:#999999; cursor:pointer;" ><a href="up_product_pic.php?id=<?=$_REQUEST['id']?>">产品图片</a></div></td>
        <td width="100" bgcolor="#efefef"><div align="center" style="color:#999999; cursor:pointer;"  ><a href="up_product_sy.php?id=<?=$_REQUEST['id']?>">实用指南</a></div></td>
        <td width="100" bgcolor="#efefef"><div align="center" style="color:#999999; cursor:pointer;" ><a href="up_product_like.php?id=<?=$_REQUEST['id']?>">相关产品</a></div></td>
        <td width="100" bgcolor="#efefef"><div align="center" style="color:#999999; cursor:pointer;" ><a href="up_product_service.php?action=modify&id=<?=$_REQUEST['id']?>">服务支持</a></div></td>
      </tr>
    </table>
    <table width="100%" height="534" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="7%" height="40" bgcolor="#FFFFFF"><div align="center">产品名称 </div></td>
        <td width="93%" height="40" bgcolor="#FFFFFF"><input name="title" type="text" id="title"  value="<?=$rs[0]['title']?>" size="50" maxlength="100" />
		<input name="id" type="text" id="id"  value="<?=$rs[0]['id']?>"  style="display:none" />
          &nbsp;排序
          <input name="orderid" type="text" id="orderid"  value="<?=$rs[0]['orderid']?>" style="ime-mode:disabled" onKeyPress="if ((event.keyCode<48 || event.keyCode>57)) event.returnValue=false"  onkeydown="if(event.keyCode==13)event.keyCode=9"  size="5" maxlength="5" />
        &nbsp;</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">缩略图</div></td>
        <td height="40" bgcolor="#FFFFFF">
		    
										   
										  
											  <table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr>
												  <td width="50%"><iframe src="../up.php" width="500"  scrolling="No" height="100" frameborder="0"></iframe>                                              </td>
												  <td width="50%"><img src="../../<?=$rs[0]['pic']?>" name="img" width="100" height="100" id="img"  onload="javascript:Simg(this,100,100);"   /></td>
												</tr>
												<tr>
												  <td>路径：
												  <input name="pic" type="text" id="pic" value="<?=$rs[0]['pic']?>" size="80" maxlength="100" /></td>
												  <td>&nbsp;</td>
												</tr>
											  </table>	    </td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">分类</div></td>
        <td height="40" bgcolor="#FFFFFF">
		<div id=box><div id=box2>
		
		<?
		
 
		echo $productclass->GetSelect("edit",$rs[0]['bid'],$rs[0]['sid'],$rs[0]['tid']);
		 
		?>
		
		 
		</div></div>		</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF">市场需求分类</td>
        <td height="40" bgcolor="#FFFFFF"><?
		echo $sc->GetSelect($rs[0]['cid']);
		?></td>
      </tr>
      
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">简介</div></td>
        <td height="40" bgcolor="#FFFFFF"><textarea name="scontent" id="scontent" style="height:80px; width:400px;overflow-y:auto"><?=$rs[0]['scontent']?></textarea></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">详细内容</div></td>
        <td height="40" bgcolor="#FFFFFF">
								<?php 
										 
									Editor("content","../",$rs[0]["content"]);
								?>								</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">购买信息</div></td>
        <td height="40" bgcolor="#FFFFFF"><?php 
										 
									Editor("buycontent","../",$rs[0]["buycontent"]);
								?></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF">技术规格（仅供会员查看）</td>
        <td height="40" bgcolor="#FFFFFF"><?php 
										 
									Editor("jishu","../",$rs[0]["jishu"]);
								?></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">Keywords</div></td>
        <td height="40" bgcolor="#FFFFFF"><input name="keywords" type="text" id="keywords" size="50" value="<?=$rs[0]["keywords"]?>" maxlength="200" /></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">description</div></td>
        <td height="40" bgcolor="#FFFFFF"><input name="des" type="text" id="des" size="50" maxlength="200"  value="<?=$rs[0]["des"]?>" /></td>
      </tr>
      
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">属性</div></td>
        <td height="40" bgcolor="#FFFFFF">推荐：
          <input name="recommand" type="checkbox" id="recommand" value="yes"   <?  if($rs[0]["recommand"]=="yes")
											{
										   ?>  checked="checked" 
										   <? }?> />
&nbsp;&nbsp;热门：
<input name="hot" type="checkbox" id="hot" value="yes"   <?  if($rs[0]["hot"]=="yes")
											{
										   ?>  checked="checked" 
										   <? }?> />
&nbsp; 首页显示：
          <input name="indexs" type="checkbox" id="indexs" value="yes"   <?  if($rs[0]["indexs"]=="yes")
											{
										   ?>  checked="checked" 
										   <? }?> />
          &nbsp;&nbsp;
发布：
 <input name="shows" type="checkbox" id="show" value="yes"  <?  if($rs[0]["shows"]=="yes")
											{
										   ?>  checked="checked" 
										   <? }?> />
&nbsp;</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">关键字</div></td>
        <td height="40" bgcolor="#FFFFFF"><input name="likewords" type="text" id="likewords" size="50"  value="<?=$rs[0]["likewords"]?>" maxlength="200" />
        请输入关键词，用，分开 如：数码，相机，手机 </td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center"></div></td>
        <td height="40" bgcolor="#FFFFFF"><input type="submit" name="Submit" class="bt" value="保存信息" />
        <input type="reset" name="Submit2" value="取消" class="bt" /></td>
      </tr>
    </table>
  </form>

<?
}
?>

</div>



</body>
</html>
