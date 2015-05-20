<div class="data-item">
	<table class="tbl-frm">
		<tr>
			<th width="100">数据名称：</th>
			<td><input name="source[goods_data][{{$i}}][name]" value="{{$k}}"/>
				<input onclick="data_item_del(this);" type="button" value="删除"/>
			</td>
		</tr>
		<tr>
			<th>
				{{html id="mslt-goods-$i" tplid="mslt-tpl-goods-$i" callback="mslt_goods_back"
					remain="false"
					name="goods_ids" type="mslt" mdl="goods" label="商品"}}
			</th>
			<td>
				<div id="mslt-tpl-goods-{{$i}}">
					{{if $r.datasource.goods_data}}
					{{include file="drt/goods-source-edit.tpl"}}
					{{/if}}
				</div>
			</td>
		</tr>
	</table>
</div>
<script>$(function(){
	ajaxinit();
});

function mslt_goods_back(msg,name_id,ids,tplid,mslt_id){
	var j = $('#'+tplid).find('.goods-source-item').size();
	var arr_t = mslt_id.split('-');
	var i = arr_t[arr_t.length-1];
	
	$.ajax({
		url:'/admin/drt/goods-source-add',
		data:'ids='+ids+'&i='+i+'&j='+(j+1),
		method:'get',
		dataType:'html',
		success:function(msg){
			$('#'+tplid).append(msg);
		}
	});
	
	return false;	
}

</script>
