<?php /* Smarty version 2.6.19, created on 2014-10-24 19:33:59
         compiled from news/addadvform.tpl */ ?>
<style type="text/css">
		.hlight{background-color:gray;}
		.normal{backgroud-color:white;}
		.table_form tbody .hlight td{background:none;}
</style>
<script type="text/jscript" src="/scripts/admin/jquery-1.4.2.min.js"/></script>
<script language="JavaScript" type="text/javascript">
	$.noConflict();
	//键盘下拉框
	function KeyDown(evt)
    {
        evt = (evt) ? evt : ((window.event) ? window.event : "")
        var key = evt.keyCode?evt.keyCode:evt.which;
        if(key==38)
        {
            //上
            lightMove("up");
        }
        if(key==40)
        {
            //下
            lightMove("down");
        }
        if(key==13)
        {
            //回车
            lightMove("ok");
        }
    }

    function lightMove(cmd)
    {
        var tb = document.getElementById("tb");

        if(cmd=="up")
        {
            //上
            for(var i=0;i<tb.rows.length;i++)
            {
               if(tb.rows[i].className=="hlight")
                {
                    tb.rows[i].className="normal";
                    i=i==0?tb.rows.length-1:i-1;
                    tb.rows[i].className="hlight";
                    sTxt(tb.childNodes[0].childNodes[i]);
                }
            }
        }
        if(cmd=="down")
        {
            for(var i=0;i<tb.rows.length;i++)
            {
               if(tb.rows[i].className=="hlight")
                {
                    tb.rows[i].className="normal";
                    i=i==tb.rows.length-1?0:i+1;
                    tb.rows[i].className="hlight";
                    sTxt(tb.childNodes[0].childNodes[i]);
                }
            }
        }
        if(cmd=="ok")
        {
            for(var i=0;i<tb.rows.length;i++)
            {
               if(tb.rows[i].className=="hlight")
                {
                   completeField(tb.rows[i]);
                }
            }
        }
    }

     function insTR(tb)
    {
        var new_tr = tb.insertRow(tb.rows.length);
        var new_td = new_tr.insertCell();
        new_td.innerHTML=document.getElementById("keywords").value;
        new_tr.style.display="none";
        new_tr.className="hlight";
    }
    function sTxt(td)
    {
        //alert(td.innerText);
        document.getElementById("keywords").value=td.childNodes[0].innerHTML.substring(0,td.childNodes[0].innerHTML.indexOf('_'));
        //var sel=td.childNodes[0].innerHTML.substring(td.childNodes[0].innerHTML.indexOf('_')+1,td.childNodes[0].innerHTML.length);
        //if(document.getElementById("position1").value.indexOf(sel)==-1)
        	//document.getElementById("position1").value+=sel;
    }
    function hLight(tr)
    {
        var tb = document.getElementById("tb");
        for(var i=0;i<tb.rows.length;i++)
        {
           if(tb.rows[i].className=="hlight")
            {
                tb.rows[i].className="normal";
                break;
            }
        }
        tr.className="hlight";
    }


function compareIndex(str,c)
{
	var res=str.toLowerCase().indexOf(c.toLowerCase());
	return (res == -1) ? 1000:res;
}

function doit(evt)
    {
        evt = (evt) ? evt : ((window.event) ? window.event : "");
        var key = evt.keyCode?evt.keyCode:evt.which;
        if(key==38|key==40|key==13)
            return;
        var wd = document.getElementById("keywords").value;
        if(wd.length==0)
           return;

        createSearchResult(wd);
		$("show_wd").style.display="block";
        setDiv();
    }

var showNum=0;
function closeSearchBox()
{
	if(showNum!=0)
	{
		document.getElementById("show_wd").style.display="none";
		document.getElementById("show_wd").scrollTop=0;
	}
	showNum++;
}

function createSearchResult(keywords)
{
    jQuery.ajax({
	 	type: "post",
	 	dataType: "text",
	 	async:true,
	 	url: "/admin/news/select-array-adv?keywords="+encodeURI(keywords)+"&cho="+jQuery("#searchFrom").val(),
	  	success: function(datas){
	        arg=eval(datas);
			var reshtml ="<table id=\"tb\" width=\"100%\">";
		    for(var i=0;i<arg.length;i++)
    		{
   	 			reshtml+="<tr onmouseover=\"hLight(this)\"><td onclick=\"completeField(this)\">"+arg[i]+"</td></tr>";
		    }
		    reshtml +="<tr class=\"hlight\" style=\"display:none\"><td>"+document.getElementById('keywords').value+"</td></tr>";
		    reshtml +="</table>";
		    jQuery("#show_wd").html(reshtml);
		    jQuery("#keywords").focus();
	  	}
	});
}
 function setDiv()
    {
       var txt = document.getElementById("keywords");
       var tDiv = document.getElementById("show_wd");
       tDiv.style.width =txt.offsetWidth; + "px";
       var left = calculateOffset(txt,"offsetLeft");
       var top = calculateOffset(txt, "offsetTop") + txt.offsetHeight;
       tDiv.style.border = "black 1px solid";
       tDiv.style.left = left + "px";
       tDiv.style.top = top + "px";
    }

    function completeField(tdvalue)
    {
    	var ncId="";
    	if(ncId!="" && ncId!=null)
    		ncId="ZC"+ncId.split('_')[1];
    	if(tdvalue.childNodes[0].innerHTML==null)
    	{
    		document.getElementById("keywords").value=tdvalue.innerHTML.substring(0,tdvalue.innerHTML.indexOf('_'));
    		var sel=tdvalue.innerHTML.split('_')[1];
    		if(document.getElementById("position1").value.indexOf(','+sel+',')==-1 && sel!=ncId)
       	 		document.getElementById("position1").value+=sel+',';
    	}
    	else
    	{
    		document.getElementById("keywords").value=tdvalue.childNodes[0].innerHTML.substring(0,tdvalue.childNodes[0].innerHTML.indexOf('_'));
    		var sel=tdvalue.childNodes[0].innerHTML.split('_')[1];
        	if(document.getElementById("position1").value.indexOf(','+sel+',')==-1 && sel!=ncId)
        		document.getElementById("position1").value +=sel+',';
    	}
        document.getElementById("show_wd").style.display="none";
        document.getElementById("show_wd").scrollTop=0;
    }
    function calculateOffset(field, attr)
    {
          var offset = 0;
          while(field) {
            offset += field[attr];

            field = field.offsetParent;
          }
         return offset;
    }

    //下拉列表框值改变事件
    function searchFromChange(val)
    {
		showNum=0;
    	if(val=='ch')
    	{
    		showNum=1;
    		document.getElementById("keywords").value="";
    	}
		else
		{
			createSearchResult("");
			$("show_wd").style.display="block";
       		 setDiv();
       		 document.getElementById("keywords").value="";
		}
		document.getElementById("keywords").focus();
    }
    function doKeywordsFocus(value)
    {
    	var val=$("searchFrom").value;
    	if(val=='ch')
    	{
			showNum=1;
    	}
		else
		{
			if(document.getElementById("show_wd").style.display=='none')
			{
				showNum=0;
				createSearchResult(value);
				$("show_wd").style.display="block";
	       		 setDiv();
	       	}
		}
    }
    function clearPosition()
    {
    	var posiValue=document.getElementById("position1").value;
    	if(confirm("您确认删除最后一个吗？")){
    		if(posiValue!=""){
    			var posis=posiValue.split(",");
		    	document.getElementById("position1").value=posiValue.replace(posis[(posis.length-2)]+",","");
    		}
    	}
    }
	</script>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post" enctype="multipart/form-data"  onclick="closeSearchBox();">
<div class="title">添加广告位</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%"><strong>标题</strong> * </td>
      <td><input type="text" name="adv[name]" size="50" value="<?php echo $this->_tpl_vars['adv']['name']; ?>
" msg="请填写标题" class="required" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>着陆地址</strong> * </td>
      <td><input name="adv[url]" type="text" id="url" value="<?php echo $this->_tpl_vars['adv']['url']; ?>
" size="50" msg="请填写着陆地址" class="required" /></td>
    </tr>
    <tr>
	  <td><strong>广告图片</strong></td>
	  <td><input type="file" name="img_url" /></td>
	</tr>
   <tr>
      <td width="10%"><strong>是否展示</strong> </td>
      <td><select name="adv[isDisplay]">
      		<option value="1" selected="selected">展示</option>
      		<option value="0">隐藏</option>
      </select></td>
    </tr>
    <tr>
      <td width="10%"><strong>优先级</strong> </td>
      <td><input name="adv[grade]" type="text" id="url" value="<?php echo $this->_tpl_vars['adv']['grade']; ?>
" size="30" /></td>
    </tr>
	<tr>
		<td>
			<select id="searchFrom" name="searchFrom" onchange="searchFromChange(this.value);" >
				<option value="ch" selected>-请选择-</option>
				<option value="ZD">数据字典</option>
				<option value="ZC">资讯分类</option>
				<option value="ZCB">资讯分类品牌推荐</option>
				<option value="ZCC">资讯分类商城分类推荐</option>
				<option value="ZCPF">资讯分类商城商品推荐第一行</option>
				<option value="ZCPS">资讯分类商城商品推荐第二行</option>
				<option value="B">品牌</option>
				<option value="BPPC">品牌城广告位</option>
				<option value="C">产品分类</option>
				<option value="BC">品牌产品分类</option>
				<option value="P">产品</option>
			</select>
		</td>
		<td>
			<input name="keywords" id="keywords" onkeyup="doit(event)" size="50" onkeydown="KeyDown(event)" onfocus="doKeywordsFocus(this.value);" autoComplete= "off" value="" />
			<div id="show_wd" style="position: absolute; font-size: 12px; display:none;height: 300px;overflow-y: auto;background-color: white;"></div>
		</td>
	</tr>
	<tr>
		<td>
			展示位置值:
		</td>
		<td>
			<input name="adv[position]" id="position1" value="<?php echo $this->_tpl_vars['adv']['position']; ?>
" size="50" readonly /> <input type="button" onclick="clearPosition();" value="删除最后一个" />
		</td>
	</tr>
	<tr>
      <td width="10%"><strong>市场价格</strong> </td>
      <td><input name="adv[market_price]" type="text" id="url" value="<?php echo $this->_tpl_vars['adv']['market_price']; ?>
" size="30" /></td>
    </tr>
	<tr>
      <td width="10%"><strong>价格</strong> </td>
      <td><input name="adv[price]" type="text" id="url" value="<?php echo $this->_tpl_vars['adv']['price']; ?>
" size="30" /></td>
    </tr>

</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>