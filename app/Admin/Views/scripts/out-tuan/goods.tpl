<div class="title">外部团购商品&nbsp;&nbsp;&nbsp;&nbsp;（<a href="/admin/out-tuan/goods-add">添加外部团购商品</a>）</div>
<div class="search">
<form name="searchForm" id="searchForm" action="/admin/out-tuan/goods">
  商品名称:<input type="text" name="goods_name_like" value="{{$param.goods_name_like}}" />&nbsp;&nbsp;&nbsp;&nbsp;
  所属网站:<select name="shop_id">
    <option value="">全部</option>
      {{foreach from=$shops item=shop}}
      <option {{if $param.shop_id eq $shop.shop_id}}selected{{/if}} value="{{$shop.shop_id}}">{{$shop.shop_name}}</option>
      {{/foreach}}
  </select>&nbsp;&nbsp;&nbsp;&nbsp;
  状态：<select name="status">
	<option value="" selected>全部</option>
    <option {{if $param.status eq on}}selected{{/if}} value="on">正常</option>
    <option {{if $param.status eq off}}selected{{/if}} value="off">关闭</option>
  </select>
  限价：<input type="checkbox" name="price_limit" value="1" {{if $param.price_limit eq '1'}}checked='true'{{/if}}/>
  <input type="submit" name="dosearch" value="查询"/>&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" onclick="javascript:G('/admin/out-tuan/goods-export');" value="导出商品" />
</form>
</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>ID</td>
            <td>网站名称</td>
			<td>商品售价</td>
			<td>供货价</td>
            <td>保护价</td>
			<td>费率</td>
            <td>商品名称</td>
			<td width="80">添加时间</td>
			<td>编码</td>
			<td>gid/pid</td>
            <td>销售数量</td>
			<td>数量/份</td>
            <td>状态</td>
			<td>类型</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="row{{$data.goods_id}}" >
    	<td><strong>{{$data.goods_id}}</strong></td>
        <td><strong>{{$data.shop_name}}</strong></td>
		<td><strong>{{$data.goods_price}}</strong></td>
		<td {{if $data.supply_price < $data.final_price_limit}}style="color:#ff0000;"{{/if}}><strong>{{$data.supply_price}}</strong></td>
        <td><strong>{{if $data.final_price_limit eq 0}}无限价{{else}}{{$data.final_price_limit}}{{/if}}</strong></td>
		<td><strong>{{$data.rate}}&nbsp;%</strong></td>
        <td><strong>{{$data.goods_name}}</strong></td>
		<td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
		<td>{{$data.goods_sn}}</td>
		<td>g:{{$data.g_id}}<br>p:{{$data.p_id}}</td>
        <td>{{$data.amount}}</td>
		<td>{{$data.goods_number}}</td>
        <td id="td{{$data.goods_id}}">{{if $data.status eq 1}}<a href="javascript:;" onclick="setStatus({{$data.goods_id}},0)">正常</a>{{else}}<a href="javascript:;" onclick="setStatus({{$data.goods_id}},1)"><font color="red">关闭</font></a>{{/if}}</td>
		<td title="{{if $data.goods_type eq '1'}}特殊是指：（任意数量商品（多个）比如拉手A商品n个，B商品n个）{{/if}}">{{if $data.goods_type eq '0'}}正常{{else}}<font color="green">特殊</font>{{/if}}</td>
        <td> 
          <a href="javascript:fGo()" onclick="G('{{url param.action=goods-edit param.goods_id=$data.goods_id}}')">编辑</a> | 
          <a href="javascript:fGo()" onclick="delGoods({{$data.goods_id}})">删除</a> |
		  <a href="javascript:fGo()" onclick="G('{{url param.action=goods-add-sub param.goods_id=$data.goods_id}}')">添加子套餐</a>
        </td>
    </tr>
	{{foreach from=$data.subs item=sub}}
	<tr id="row{{$sub.goods_id}}" style=" color:#999999" >
    	<td>{{$sub.goods_id}}</td>
        <td></td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;{{$sub.goods_price}}</td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;{{$sub.supply_price}}</td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;{{$sub.rate}}&nbsp;%</td>
        <td>&nbsp;&nbsp;└ &nbsp;&nbsp;{{$sub.goods_name}}</td>
		<td>{{$sub.add_time|date_format:"%Y-%m-%d"}}</td>
		<td>{{$sub.goods_sn}}</td>
		<td>g:{{$sub.g_id}}<br>p:{{$sub.p_id}}</td>
        <td>{{$sub.amount}}</td>
		<td>{{$sub.goods_number}}</td>
        <td id="td{{$sub.goods_id}}">{{if $sub.status eq 1}}<a href="javascript:;" onclick="setStatus({{$sub.goods_id}},0)">正常</a>{{else}}<a href="javascript:;" onclick="setStatus({{$sub.goods_id}},1)"><font color="red">关闭</font></a>{{/if}}</td>
		<td title="{{if $data.goods_type eq '1'}}特殊是指：（任意数量商品（多个）比如拉手A商品n个，B商品n个）{{/if}}">{{if $data.goods_type eq '0'}}正常{{else}}<font color="green">特殊</font>{{/if}}</td>
        <td> 
		  <a href="javascript:fGo()" onclick="G('{{url param.action=goods-edit param.goods_id=$sub.goods_id}}')">编辑</a> | 
          <a href="javascript:fGo()" onclick="delGoods({{$sub.goods_id}})">删除</a>
        </td>
    </tr>
	{{/foreach}}
    {{/foreach}}
    </tbody>
</table>
</div>
<div class="page_nav">{{$pageNav}}</div>
<script type="text/javascript">
//状态
function setStatus(id,st){
	var id=parseInt(id);if(id<1){alert('参数错误');return false;}
	var st=parseInt(st);if(st!=1 && st!=0){alert('参数错误');return false;}
	new Request({
		url:'/admin/out-tuan/goods-status/id/'+id+'/st/'+st,
		onSuccess:function(msg){
			if(msg=='ok'){
				if(st==0){
					$('td'+id).innerHTML='<a href="javascript:;" onclick="setStatus('+id+',1)"><font color="red">关闭</font></a>';
				}else{
					$('td'+id).innerHTML='<a href="javascript:;" onclick="setStatus('+id+',0)">正常</a>';
				}
			}else{
				alert(msg);
			}
		},
		onError:function(){
			alert("网络繁忙，请稍后重试");
		}
	}).send();
}
//删除
function delGoods(id){
	if(confirm('你确认要删除？')){
		id=parseInt(id);
		if(id<1){alert('参数错误');return false;}
		new Request({
			url:'/admin/out-tuan/goods-del/id/'+id,
			onSuccess:function(msg){
				if(msg=='ok'){
					$("row"+id).destroy();
					alert('删除成功');
				}else{
					alert(msg);
				}
			},
			onError:function(){
				alert("网络繁忙，请稍后重试");
			}
		}).send();
	}
}
</script>