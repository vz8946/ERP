<div class="title">团购期数&nbsp;&nbsp;&nbsp;&nbsp;（<a href="/admin/out-tuan/term-add">添加团购期数</a>）</div>
<div class="search">
<form name="searchForm" id="searchForm" action="/admin/out-tuan/term">
  所属网站:<select name="shop_id">
    <option value="">全部</option>
      {{foreach from=$shops item=shop}}
      <option {{if $param.shop_id eq $shop.shop_id}}selected{{/if}} value="{{$shop.shop_id}}">{{$shop.shop_name}}</option>
      {{/foreach}}
  </select>&nbsp;&nbsp;&nbsp;&nbsp;商品名称:<input type="text" name="goods_name" value="{{$param.goods_name}}" />
  &nbsp;&nbsp;&nbsp;&nbsp;
  状态：<select name="status">
	<option value="" selected>全部</option>
    <option {{if $param.status eq on}}selected{{/if}} value="on">正常</option>
    <option {{if $param.status eq off}}selected{{/if}} value="off">关闭</option>
  </select>
  &nbsp;&nbsp;&nbsp;&nbsp;
  结款状态：<select name="clearstatus">
	<option value="">全部</option>
    <option {{if $param.clearstatus eq 'zero'}}selected{{/if}} value="zero">未结款</option>
    <option {{if $param.clearstatus eq 'one'}}selected{{/if}} value="one">结算中</option>
    <option {{if $param.clearstatus eq 'two'}}selected{{/if}} value="two">结清</option>
  </select>
<input type="submit" name="dosearch" value="查询"/>
</form>
</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>ID</td>
            <td>网站名称</td>
			<td>商品名称</td>
			<td>期数</td>
			<td>结款状态</td>
			<td>应结款</td>
			<td>开始时间</td>
            <td>结束时间</td>
			<td>备注</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="row{{$data.id}}" >
		<td>{{$data.id}}</td>
    	<td>{{$data.shop_name}}</td>
        <td>{{$data.goods_name}}</td>
		<td><span style="font-weight:bold; color:red;">{{$data.term}}</span></td>
		<td>{{if $data.clearstatus eq 0}}<font color="red">未结款</font>{{elseif $data.clearstatus eq 1}}<font color="#0066FF">结款中</font>{{elseif $data.clearstatus eq 2}}<font color="green">结清</font>{{/if}}</td>
		<td>{{$data.amount}}</td>
		<td>{{$data.stime|date_format:"%Y-%m-%d"}}</td>
		<td>{{$data.etime|date_format:"%Y-%m-%d"}}</td>
        <td>{{$data.remark}}</td>
        <td id="td{{$data.id}}">{{if $data.status eq 1}}<a href="javascript:;" onclick="setStatus({{$data.id}},0)">正常</a>{{else}}<a href="javascript:;" onclick="setStatus({{$data.id}},1)"><font color="red">关闭</font></a>{{/if}}</td>
        <td> 
          <a href="javascript:fGo()" onclick="G('{{url param.action=term-edit param.id=$data.id}}')">编辑</a><!-- | 
          <a href="javascript:fGo()" onclick="delTerm({{$data.id}})">删除</a>-->
        </td>
    </tr>
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
		url:'/admin/out-tuan/term-status/id/'+id+'/st/'+st,
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
function delTerm(id){
	if(confirm('你确认要删除？')){
		id=parseInt(id);
		if(id<1){alert('参数错误');return false;}
		new Request({
			url:'/admin/out-tuan/term-delete/id/'+id,
			onSuccess:function(msg){
				if(msg=='ok'){
					$("row"+id).destroy();
					alert('删除成功');
				}else if(msg=='hasgoods'){
					alert('此期数下有订单，不能删除');
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