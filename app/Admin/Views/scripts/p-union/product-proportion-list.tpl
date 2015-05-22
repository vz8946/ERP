<div class="search">
<form name="searchForm" id="searchForm">
    <div>
    <span style="float:left">商品名称：</span><span style="float:left;width:150px;">
    <input type="text" name="goods_name" id="goods_name" size="15" value="{{$param.goods_name}}"  /></span>
    <span style="float:left; margin-left:10px">商品ID：</span><span style="float:left;width:150px;"><input type="text" name="goods_id" id="goods_id" size="15" value="{{$param.goods_id}}" />
    </span>
    <span style="float:left">商品SN： </span><span style="float:left;width:150px;">
    <input type="text" name="goods_sn" id="goods_sn" value="{{$param.goods_sn}}" size="15" />
    </span>
    <span style="float:left; margin-left:10px">排序：</span><span style="float:left;width:150px;"> 
    <select name="order_by" id="order_by">
    <option value="asc" {{if $param.order_by eq 'asc'}}selected="selected"{{/if}} >升序</option>
    <option value="desc" {{if $param.order_by eq 'desc'}}selected="selected"{{/if}} >降序</option>
    </select>
    </span>
    </div>
    <div style="clear:both; padding-top:5px">
    <span style="float:left; margin-left:10px">价格：</span><span style="float:left;">
    <input type="text" name="price_from" id="price_from" size="15" value="{{$param.price_from}}" /></span><span style="float:left;width:150px;"> - <input type="text" name="price_to" id="price_to" size="15" value="{{$param.price_to}}" /></span>
    <span style="float:left; margin-left:10px">商品分类：</span><span style="float:left;width:150px;">
    <select name="cat_id" id="cat_id">
      <option value="">请选择</option>
      {{foreach from=$cats item=cat}}
      <option {{if $param.cat_id eq $cat.cat_id}}selected="selected"{{/if}} value="{{$cat.cat_id}}">{{if $cat.step eq 2}}&nbsp;&nbsp;&nbsp;{{/if}}{{$cat.cat_name}}</option>
      {{/foreach}}
    </select>
    </span>
    
    <span style="vertical-align : top;"></span><input type="button" name="dosearch" value="概况查询" onclick="ajax_search(this.form,'{{url param.action=product-proportion-list param.do=search}}','ajax_search')"/>&nbsp;&nbsp;&nbsp;<input type="button" name="dosearch" value="导出商品分成比率" onclick="ajax_search(this.form,'{{url param.action=export-union-goods-proportion param.do=search}}','ajax_search')"/></span>
    </div>

</form>
</div>
{{if $param.cat_id}}<br />
<div class="search">
<div id="setcatp">
<span id="cat_name"></span><input type="text" name="cat_proportion" id="cat_proportion" size="5" value="0" onchange="if(this.value>40 || this.value<1){ this.value=this.defaultValue;}" onkeyup="this.value=this.value.replace(/\D/g,'');" />&nbsp;&nbsp;<input type="button" name="dosubmit" value="同步到此分类下的商品" onclick="setcatproductproportion()" />
</div>
<script type="text/javascript">
var obj=document.getElementById('cat_id');
var selText=obj.options[obj.selectedIndex].text;
selText=selText.trim(selText);
document.getElementById('cat_name').innerHTML='“<font color=red>'+selText+'</font>”分类分成比例：';

//同步分类下的商品分成比率
function setcatproductproportion(){
	var cpt=$('cat_proportion').value.trim();
	cpt=parseInt(cpt);
	if(cpt<0 || cpt>40){alert('请输入0-40之间的数字');return false;}
	new Request({
		url:"{{url param.action=set-cat-product-proportion}}",
		data:{cat_proportion:cpt},
		evalScripts: true,
		onSuccess:function(msg){
	    	if(msg!=''){alert(msg);}
			else{window.location.reload();}
	    },
		onFailure:function(){alert('网络忙，请稍后重试');}
	}).send();
}
</script>
</div>
{{/if}}

<div id="ajax_search">
<div class="title">联盟商品分成比例列表<font color="#FF0000">（{{$cname}}）</font></div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>联盟ID</td>
            <td>商品ID</td>
            <td>商品名称</td>
            <td>本店价</td>
            <td>是否上架</td>
            <td>分成比率</td>
            <td>分类名称</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$pUnionList item=pUnion}}
        <tr id="ajax_list{{$pUnion.user_id}}">
            <td>{{$pUnion.id}}</td>
            <td>{{$pUnion.union_id}}</td>
            <td>{{$pUnion.goods_id}}</td>
            <td>{{$pUnion.goods_name}}</td>
            <td>{{$pUnion.price}}</td>
            <td>{{if $pUnion.onsale eq 0}}是{{else}}<font color="red">否</font>{{/if}}</td>
            <td><input type="text" name="proportion" value="{{$pUnion.proportion}}" size="3" 
        onchange="if(this.value>40 || this.value<1){ this.value=this.defaultValue;}else{ajax_update('{{url param.action=setproportion}}',{{$pUnion.goods_id}},'proportion',this.value)}"  
        onblur="if(this.value>40 || this.value<1){this.value=this.defaultValue;}" /></td>
            <td title="分类ID：{{$pUnion.cat_id}}">{{$pUnion.cat_name}}</td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
    
</div>
<div class="page_nav">{{$pageNav}}</div>
</div>
