<div class="title">外部团购批次</div>
<div class="search">
<form name="searchForm" id="searchForm" action="/admin/out-tuan/check-stock">
	批次号:<input type="text" name="batch" id="batch" value="{{$param.batch}}" />&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="submit" name="dosearch" value="查询"/>
	<span style="display:none;"><input type="text" id="st" size="3" /><input type="button" value="更改批次库存状态" onclick="changeBatchStock()" /></span>
</form>
</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>ID</td>
            <td>批次号</td>
			<td>添加时间</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
		<tr id="row{{$data.goods_id}}" >
			<td><strong>{{$data.id}}</strong></td>
			<td><strong><a href="javascript:;" onclick="G('/admin/out-tuan/order/batch/{{$data.batch}}')">{{$data.batch}}</a></strong></td>
			<td>{{$data.ctime|date_format:"%Y-%m-%d %T"}}</td>
			<td><input type="button" name="btn" onclick="stockCheck('{{$data.batch}}')" value="{{if $data.check_stock eq 1}}部分订单通过{{else}}检查库存{{/if}}"></td>
		</tr>
    {{/foreach}}
    </tbody>
</table>
</div>
<div class="page_nav">{{$pageNav}}</div>
<script type="text/javascript">
//检查库存
function stockCheck(batch){
	//return;
	if(confirm('确认？')){
		batch=batch.trim();
		if(batch==''){alert('参数错误');return false;}
		new Request({
			url:'/admin/out-tuan/check-stock-do/batch/'+batch,
			onRequest:function(){
				var obj=document.getElementsByName('btn');
				for(var i=0;i<obj.length;i++){
					obj[i].disabled=true;
				}
			},
			onSuccess:function(msg){
				if(msg=='ok'){
					alert('操作成功，请打印订单');
					location.reload();
				}else{
					alert(msg);
					location.reload();
				}
			},
			onError:function(){
				alert("网络繁忙，请稍后重试");
			}
		}).send();
	}
}
//
function changeBatchStock(){
	//return;
	if(confirm('确认？')){
		batch = $('batch').value.trim();
		if(batch==''){alert('参数错误');return false;}
		st = $('st').value.trim();
		if(st==''){alert('参数错误');return false;}
		new Request({
			url:'/admin/out-tuan/change-batch-stock/batch/'+batch+'/st/'+st,
			onSuccess:function(msg){
				if(msg=='ok'){
					alert('操作成功');
					location.reload();
				}else{
					alert(msg);
					location.reload();
				}
			},
			onError:function(){
				alert("网络繁忙，请稍后重试");
			}
		}).send();
	}
}
</script>