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
	 	url: "/admin/news/select-array-link?keywords="+encodeURI(keywords)+"&cho="+jQuery("#searchFrom").val(),
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
    	if(tdvalue.childNodes[0].innerHTML==null)
    	{
    		document.getElementById("keywords").value=tdvalue.innerHTML.substring(0,tdvalue.innerHTML.indexOf('_'));
    		var sel=tdvalue.innerHTML.split('_')[1];
       	 	document.getElementById("position1").value=sel;
    	}
    	else
    	{
    		document.getElementById("keywords").value=tdvalue.childNodes[0].innerHTML.substring(0,tdvalue.childNodes[0].innerHTML.indexOf('_'));
    		var sel=tdvalue.childNodes[0].innerHTML.split('_')[1];
        	document.getElementById("position1").value=sel;
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
		    	document.getElementById("position1").value="";
    		}
    	}
    }
	</script>
{{if !$param.do}}
<div class="search" onclick="closeSearchBox();">
	<form name="searchForm" id="searchForm" action="/admin/news/listlink">
		<div style="clear:both; padding-top:5px">
		展示位置:
		<select id="searchFrom" name="searchFrom" onchange="searchFromChange(this.value);" >
				<option value="ch" selected>-请选择-</option>
				<option value="ZD">数据字典</option>
				<option value="ZC">副资讯分类</option>
				<option value="B">品牌</option>
				<option value="C">产品分类</option>
				<option value="BC">品牌产品分类</option>
				<option value="P">产品</option>
			</select>
			<input name="keywords" id="keywords" onkeyup="doit(event)" size="20" onkeydown="KeyDown(event)" onfocus="doKeywordsFocus(this.value);" autoComplete= "off" value="" />
			<div id="show_wd" style="position: absolute; font-size: 12px; display:none;height: 300px;overflow-y: auto;background-color: white;"></div>
			<input name="position" id="position1" size="20" value="{{$param.position}}" readonly /> <input type="button" onclick="clearPosition();" value="删除最后一个" />

		标题：<input type="text" name="title" size="40" maxLength="50" value="{{$param.name}}">
		<input type="submit" name="dosearch" value="查询"/>
		</div>
	</form>
</div>
<div id="ajax_search" onclick="closeSearchBox();">
{{/if}}
	<div class="title">友情链接管理</div>
	<div class="content">
		<div class="sub_title">[ <a href="javascript:fGo()" onclick="G('{{url param.action=addlinkform}}')">添加友情链接</a> ]</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>ID</td>
				<td>链接内容</td>
				<td>链接地址</td>
				<td>展示位置</td>
				<td>优先级</td>
				<td>是否展示</td>
				<td>操作</td>
			</tr>
		</thead>
		<tbody>
		{{foreach from=$data item=item}}
		<tr id="ajax_list{{$item.id}}">
			<td>{{$item.id}}</td>
			<td>{{$item.text}}</td>
			<td>{{$item.link}}</td>
			<td title="{{$item.position}}"><div style="width:350px;height:30px;overflow:hidden;margin:0;padding:0;">{{$item.position}}</div></td>
			<td >{{$item.grade}}</td>
			<td>{{if $item.display eq 1}}展示 <font style="color:#009966; cursor:pointer;" onclick="changHot({{$item.id}}, 0);">【隐藏】</font>{{elseif $item.display eq 0}}隐藏 <font style="color:#FF3300; cursor:pointer;" onclick="changHot({{$item.id}}, 1);">【展示】</font>{{/if}}</td>
			<td>
			<a href="javascript:fGo()" onclick="G('{{url param.action=editlinkform param.id=$item.id}}')">编辑</a>||
			<a href="javascript:fGo()" onclick="reallydelete('{{url param.action=del-link}}','{{$item.id}}','{{url param.action=listlink}}')">删除</a>
		</td>
		</tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</div>
<script type="text/javascript">
function changHot(articleID, st){
	articleID = parseInt(articleID);
	st = parseInt(st);
	if(st!=1 && st!=0){ st = 0; }
	new Request({
		url:'/admin/news/is-display-link/id/'+articleID+'/st/'+st,
		onSuccess:function(msg){
			if(msg == 'ok'){
				location.reload();
			}else{
				alert(msg);
			}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
</script>