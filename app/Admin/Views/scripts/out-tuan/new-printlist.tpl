<div class="title">外部团购批次打印列表</div>
<div class="search">
<form name="searchForm" id="searchForm" action="/admin/out-tuan/new-printlist">
  批次号:<input type="text" name="batch" id="batch" value="{{$param.batch}}" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="dosearch" value="查询"/>
</form>
</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>ID</td>
            <td>批次号</td>
			<td>是否减库存</td>
			<td>发货状态</td>
			<td>添加时间</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
		<tr id="row{{$data.goods_id}}" >
			<td><strong>{{$data.id}}</strong></td>
			<td><strong>{{$data.batch}}</strong></td>
			<td><strong>{{if $data.check_stock eq 0}}<font color="red">未</font>{{elseif $data.check_stock eq 1}}<font color="orange">部分减库存</font>{{else}}<font color="green">已</font>{{/if}}</strong></td>
			<td><strong>{{if $data.logistics eq 0}}<font color="red">未</font>{{elseif $data.logistics eq 1}}<font color="orange">部分发货</font>{{else}}<font color="green">已</font>{{/if}}</strong></td>
			<td>{{$data.ctime|date_format:"%Y-%m-%d %T"}}</td>
			<td><input type="button" value="打印批次订单" onclick="printBatch({{$data.batch}});" /></td>
		</tr>
    {{/foreach}}
    </tbody>
</table>
</div>
<div class="page_nav">{{$pageNav}}</div>
<script type="text/javascript">
function printBatch(batch){
	window.location = '/admin/out-tuan/new-batch-print/batch/'+batch;
}
</script>