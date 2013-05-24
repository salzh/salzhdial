<?php include("../../include/mysql.class.php");
include("../../include/config.php");
include("../../class/news.php");
include("../../appeditor/fckeditor.php");
include("../check.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新闻管理</title>
<link href="../css/public.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../javascript/fun.js"></script>
<script type="text/javascript">
	function setdes(filename,fileurl)
{
	alert(filename);
	document.getElementById("des").value=filename;	
}
function showupfile()
{
	if(document.getElementById("upfiletr").style.display=="none")
	{
		document.getElementById("upfiletr").style.display="";	
	}
	else
	{
		document.getElementById("upfiletr").style.display="none";	
	}
}
</script> 
<script language="javascript">
	function checked(f)
	{
		if(f.title.value=="")
		{
			alert("标题不能为空");
			f.title.focus();
			return false;
		}
		
		if(f.scontent.value=="")
		{
			alert("简介不能为空");
			f.scontent.focus();
			return false;
		}
		
	 
		return true;
		
	}
</script>
<style>
	form td{padding:3px;}
	td{font-size:12px;}
 
 


</style>
</head>

<body>
 
<?
$newsclass= new NewsClass();
$newsDal=new News();
 
$a= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'add';
if($a=="addsave" || $a=="modifysave")
{
	$title=$_POST["title"];
	if($title=='')
	{
		$title='未命名';
	}
	$hits=cint(trim($_POST['hits']));
	$orderid=cint(trim($_POST['orderid']));
	$content= isset($_REQUEST['content']) ? $_REQUEST['content'] : '';
	if($content=="")
	{
	echo Msg("内容不能为空","back");
	}
	$author=isset($_POST["author"]) ? $_POST["author"] : '';
	$froms=$_POST["froms"];
	$shows= isset($_POST["shows"]) ? $_POST["shows"] : '';
	$pic=$_POST["pic"];
	$recommand=  isset($_POST["recommand"]) ? $_POST["recommand"]: '';
	$hot=isset($_POST["hot"]) ? $_POST["hot"]: '';
	$indexs=isset($_POST["indexs"]) ? $_POST["indexs"]: '';
	$scontent=$_POST["scontent"];
	$times=date('Y-m-d');
	$pic_s=$_POST["pic_s"];
	$typesid=isset($_POST["TypesId"]) ? $_POST["TypesId"]: '0';
	$des=$_POST["des"];
	//$keywords=str_replace("\\","\\\\",$_POST["keywords"]);
	$keywords=str_replace("Share","",$_POST["keywords"]);
	$pos=strpos($keywords,":");
	if($pos>0)
	{
		$keywords=substr($keywords,$pos+1);
	}
	while(substr($keywords,0,1)=="\\")
	{
		$keywords=substr($keywords,1);
	}
	$likewords=$_POST['likewords'];
	$sid=0;
 
	$classid= isset($_REQUEST['bid']) ? $_REQUEST['bid'] : '0';
	//if($classid!=0)
	//{
	//	$classid= implode(',', $classid);
	//}
	
	$ccid=$newsclass->GetBidSid($classid);
	 
	$bbid=$ccid['bbid'];
	$ssid=$ccid['ssid'];
	
	$depid= isset($_REQUEST['depid']) ? $_REQUEST['depid'] : '0';
	if($depid!=0)
	{
		$depid= implode(',', $depid);
	}	
	
	 $areaid= isset($_REQUEST['areaid']) ? $_REQUEST['areaid'] : '0';
	 
	 if($areaid!=0)
	{
		$areaid= implode(',', $areaid);
	}	
	
	
	  $brandid= isset($_REQUEST['brandid']) ? $_REQUEST['brandid'] : '0';
	
	
	$aa=array();
	$aa=$newsclass->GetBrandBidSid($brandid);
	$bid=$aa['bid'];
	$sid=$aa['sid'];
	$tid=$aa['tid'];
 
	
	
	
	$news=new NewsInfo();
	$news->Title=$title;
	$news->Content=$content;
	$news->TypesId=$typesid;
	$news->Scontent=$scontent;
	$news->Author=$author;
	$news->Times=$times;
	$news->Froms=$froms;
	$news->Indexs=$indexs;
	$news->OrderId=$orderid;
	$news->Shows=$shows;
	$news->Recommand=$recommand;
	$news->Hits=$hits;
	$news->Hot=$hot;
	$news->ClassId="0";
	$news->BBid=$bbid;
	$news->SSid=$ssid;
	
	$news->Sid=$sid;
	$news->Bid=$bid;
	$news->AreaId=$areaid;
	$news->DepId=$depid;
	$news->Tid=$tid;
	$news->Des=$des;
	$news->KeyWords=$keywords;
	$news->Pic=$pic;
	$news->Pic_s=$pic_s;
	$news->LikeWords=$likewords;
	
 	 
}
///添加保存
if($a=="addsave")
{	
	 
	if($newsDal->AddNews($news)>0)
	{
		echo Msg("操作成功","admin_news.php?action=list");
	}

}

if ($a=="modifysave")
{
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
	$news->Id=$id;
	if($id>0)
	{
			 
		if($newsDal->EditNews($news)>0)
		{
			 echo Msg("操作成功","admin_news.php?action=list");
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
      <td width="75%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;<span class="title">新闻管理-添加</span></td>
      <td width="25%" height="45" style="border-bottom:2px #0066CC solid">&nbsp;</td>
    </tr>
  </table>
<?php
 
if($a=="add")
{
?>
  <form id="form1" name="form1" method="post" action="?action=addsave"  onsubmit="return checked(this)">
    <table width="100%" height="616" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="7%" height="40" bgcolor="#FFFFFF"><div align="center">标 题 </div></td>
        <td width="93%" height="40" bgcolor="#FFFFFF"><input name="title" type="text" id="title" size="50" maxlength="100" />
          &nbsp;排序
          <input name="orderid" type="text" id="orderid" value="1" style="ime-mode:disabled" onKeyPress="if ((event.keyCode<48 || event.keyCode>57)) event.returnValue=false"  onkeydown="if(event.keyCode==13)event.keyCode=9"  size="5" maxlength="5" />
          &nbsp;  
          <input name="hits" type="text" id="hits" value="1" style="ime-mode:disabled;display:none" onkeypress="if ((event.keyCode&lt;48 || event.keyCode&gt;57)) event.returnValue=false"  onkeydown="if(event.keyCode==13)event.keyCode=9"  size="5" maxlength="5"  />
&nbsp; </td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">图片</div></td>
        <td height="40" bgcolor="#FFFFFF">
		    
										    无图
										  <input name="pic_s" type="radio"  style="border:0px;" value="no" checked="checked" onclick="document.getElementById('showpic').style.display='none'"/>
										  &nbsp; 有图
										   <input type="radio" name="pic_s" value="yes"  style="border:0px;" onclick="document.getElementById('showpic').style.display=''"/>
										   
										   
										
										<div id="showpic" style="display:none;"> 
											  <table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr>
												  <td width="50%"><iframe src="../up.php" width="500"  scrolling="No" height="100" frameborder="0"></iframe>                                              </td>
												  <td width="50%"><img style="border:1px solid #ccc;" src="../images/no.gif" name="img" width="100" height="100" id="img"  onload="javascript:Simg(this,100,100);"   /></td>
												</tr>
												<tr>
												  <td>路径：
												  <input name="pic" type="text" id="pic" value="/images/no.gif" size="80" maxlength="100" /></td>
												  <td>&nbsp;</td>
												</tr>
											  </table>	
										   </div>		</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">板块子分类</div></td>
        <td height="40" bgcolor="#FFFFFF">
		<div id=box><div id=box2><?
		echo $newsclass->GetSelect();
		?>
		</div></div>		</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="right">所属分类&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </div></td>
        <td height="40" bgcolor="#FFFFFF">
		<?
		echo $newsclass->GetBrandSelect();
		?></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">区域</div></td>
        <td height="40" bgcolor="#FFFFFF">
		<?
		echo $newsclass->GetAreaList($_SESSION["auth"]);
		?>		</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">部门</div></td>
        <td height="40" bgcolor="#FFFFFF">
		<?
		echo $newsclass->GetDepList($_SESSION["product"]);
		?>		</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">类型：</div></td>
        <td height="40" bgcolor="#FFFFFF"><select name="TypesId" id="TypesId">
		 <option value="0" selected="selected"> </option>
          <option value="1">组织架构</option>
          <option value="2">品类管理</option>
          <option value="3">成功案例分享</option>
          <option value="4">TCP</option>
         
        </select>
        </td>
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
        <td height="40" bgcolor="#FFFFFF"><div align="center">表格内容</div></td>
        <td height="40" bgcolor="#FFFFFF">
<iframe src="../uptable.php" width="500"  scrolling="No" height="80" frameborder="0"></iframe>
<br/>路径：<input name="likewords" type="text" id="likewords" value="" size="80" maxlength="200" />        
        </td>
      </tr>      
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">作业回收路径</div></td>
        <td height="40" bgcolor="#FFFFFF"><input name="keywords" type="text" id="keywords" size="50" maxlength="200" />(如果无需上传作业请留空)</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">批量文件上传</div></td>
        <td height="40" bgcolor="#FFFFFF"><input name="des" type="text" id="des" style="width:800px" /> <a href="javascript:void(0);" onclick="showupfile();">内网附件上传</a></td>
      </tr>
      <tr id="upfiletr" style="display:none">
      	<td colspan="2">
        <iframe id="upf" frameborder="0"  onload="Javascript:SetWinHeight(this)" marginheight="0" marginwidth="0" width="100%" height="500"  src="http://156.5.80.24/share/upfile.asp"></iframe>
        </td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">版权</div></td>
        <td height="40" bgcolor="#FFFFFF">作者
          <label>
          <input name="author" type="text" id="author" size="20" maxlength="50" />
          </label>
        来源
        <input name="froms" type="text" id="froms" size="20" maxlength="50" /></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">属性</div></td>
        <td height="40" bgcolor="#FFFFFF">推荐文章：
          <input name="recommand" type="checkbox" id="recommand" value="yes" checked="checked" />
&nbsp; 头条文章：
<input name="indexs" type="checkbox" id="indexs" value="yes" checked="checked" />
&nbsp; 是否发布：
<input name="shows" type="checkbox" id="show" value="yes" checked="checked" />
&nbsp;
热点文章：
<input name="hot" type="checkbox" id="shows" value="yes" checked="checked" /></td>
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
	 	$rs=$newsDal->GetModel($id);

?>  
    <form id="form1" name="form1" method="post" action="?action=modifysave"  onsubmit="return checked(this)">
    <table width="100%" height="657" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="7%" height="40" bgcolor="#FFFFFF"><div align="center">标 题 </div></td>
        <td width="93%" height="40" bgcolor="#FFFFFF"><input name="title" type="text" id="title"  value="<?=$rs[0]['title']?>" size="50" maxlength="100" />
		<input name="id" type="text" id="id"  value="<?=$rs[0]['id']?>"  style="display:none" />
          &nbsp;排序
          <input name="orderid" type="text" id="orderid"  value="<?=$rs[0]['orderid']?>" style="ime-mode:disabled" onKeyPress="if ((event.keyCode<48 || event.keyCode>57)) event.returnValue=false"  onkeydown="if(event.keyCode==13)event.keyCode=9"  size="5" maxlength="5" />
          &nbsp;  
          <input name="hits" type="text" id="hits" value="<?=$rs[0]['hits']?>" style="ime-mode:disabled; display:none" onkeypress="if ((event.keyCode&lt;48 || event.keyCode&gt;57)) event.returnValue=false"  onkeydown="if(event.keyCode==13)event.keyCode=9"  size="5" maxlength="5" />
&nbsp; </td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">图片</div></td>
        <td height="40" bgcolor="#FFFFFF">
		    
										    无图
										  <input name="pic_s" type="radio"  style="border:0px;" value="no"  <? 
										    if($rs[0]["pic_s"]=="no")
											{
										   ?>  checked="checked" 
										   <? }?>  onclick="document.getElementById('showpic').style.display='none'"/>
										  &nbsp; 有图
										   <input type="radio" name="pic_s" value="yes"     <? 
										    if($rs[0]["pic_s"]=="yes")
											{
										   ?>  checked="checked" 
										   <? }?>  style="border:0px;" onclick="document.getElementById('showpic').style.display=''"/>
										   
										   
										  <div id="showpic"    <? 
										    if($rs[0]["pic_s"]=="no")
											{
										   ?>  style="display:none;"
										   <? }?> >
										
											  <table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr>
												  <td width="50%"><iframe src="../up.php" width="500"  scrolling="No" height="100" frameborder="0"></iframe>                                              </td>
												  <td width="50%"><img src="../../<?=$rs[0]['pic']?>" style="border:1px solid #ccc" name="img" width="100" height="100" id="img"  onload="javascript:Simg(this,100,100);"   /></td>
												</tr>
												<tr>
												  <td>路径：
												  <input name="pic" type="text" id="pic" value="<?=$rs[0]['pic']?>" size="80" maxlength="100" /></td>
												  <td>&nbsp;</td>
												</tr>
											  </table>	
										  </div>		</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">板块子分类</div></td>
        <td height="40" bgcolor="#FFFFFF">
		<div id=box>
		  <div id=box2><?
		echo $newsclass->GetSelect($rs[0]['BBID'],$rs[0]['SSID']);
		?></div>
		</div>		</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">类型</div></td>
        <td height="40" bgcolor="#FFFFFF">
		
		<select name="TypesId" id="TypesId">
         <option value="0" <? if($rs[0]["TypesId"]==0) { ?> selected="selected" <? } ?>> </option>
		  <option value="1"  <? if($rs[0]["TypesId"]==1) { ?> selected="selected" <? } ?>>组织架构</option>
          <option value="2"  <? if($rs[0]["TypesId"]==2) { ?> selected="selected" <? } ?>>品类管理</option>
          <option value="3"  <? if($rs[0]["TypesId"]==3) { ?> selected="selected" <? } ?>>成功案例分享</option>
          <option value="4"  <? if($rs[0]["TypesId"]==4) { ?> selected="selected" <? } ?>>TCP</option>
         </select></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">所属分类</div></td>
        <td height="40" bgcolor="#FFFFFF"><?
		echo $newsclass->GetBrandSelect("edit",$rs[0]['bid'],$rs[0]['sid'],$rs[0]['tid']);
		?></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">区域</div></td>
        <td height="40" bgcolor="#FFFFFF"><?
		echo $newsclass->GetAreaList($rs[0]['AreaId']);
		?>        </td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">部门</div></td>
        <td height="40" bgcolor="#FFFFFF"><?
		echo $newsclass->GetDepList($rs[0]['DepId']);
		?>        </td>
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
        <td height="40" bgcolor="#FFFFFF"><div align="center">表格内容</div></td>
        <td height="40" bgcolor="#FFFFFF">
<iframe src="../uptable.php" width="500"  scrolling="No" height="80" frameborder="0"></iframe>
<br/>路径：<input name="likewords" type="text" id="likewords" value="<?=$rs[0]['likewords']?>" size="80" maxlength="200" />        
        </td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">作业回收路径</div></td>
        <td height="40" bgcolor="#FFFFFF"><input name="keywords" type="text" id="keywords" size="50" value="<?=$rs[0]["keywords"]?>" maxlength="200" />(如果无需上传作业请留空)</td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">内网文件上传</div></td>
        <td height="40" bgcolor="#FFFFFF"><input name="des" type="text" id="des" value="<?=$rs[0]["des"]?>"  style="width:800px"/> <a href="javascript:void(0);" onclick="showupfile();">内网附件上传</a></td>
      </tr>
      <tr id="upfiletr" style="display:none">
      	<td colspan="2">
        <iframe id="upf" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="500"  src="http://156.5.80.24/share/upfile.asp#1"></iframe>
        </td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">版权</div></td>
        <td height="40" bgcolor="#FFFFFF">作者
          <label>
          <input name="author" type="text" id="author" size="20" maxlength="50" value="<?=$rs[0]["author"]?>" />
          </label>
        来源
        <input name="froms" type="text" id="froms" size="20" maxlength="50"  value="<?=$rs[0]["froms"]?>" /></td>
      </tr>
      <tr>
        <td height="40" bgcolor="#FFFFFF"><div align="center">属性</div></td>
        <td height="40" bgcolor="#FFFFFF">推荐文章：
          <input name="recommand" type="checkbox" id="recommand" value="yes"   <?  if($rs[0]["recommand"]=="yes")
											{
										   ?>  checked="checked" 
										   <? }?> />
&nbsp; 头条文章：
<input name="indexs" type="checkbox" id="indexs" value="yes"  <?  if($rs[0]["indexs"]=="yes")
											{
										   ?>  checked="checked" 
										   <? }?> />
&nbsp; 是否发布：
<input name="shows" type="checkbox" id="show" value="yes"  <?  if($rs[0]["shows"]=="yes")
											{
										   ?>  checked="checked" 
										   <? }?> />
&nbsp;
热点文章：
<input name="hot" type="checkbox" id="hot" value="yes"  <?  if($rs[0]["hot"]=="yes")
											{
										   ?>  checked="checked" 
										   <? }?> /></td>
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
