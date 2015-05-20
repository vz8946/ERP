<div class="data-item">
	<table class="tbl-frm">
		<tr>
			<th width="100">数据名称：</th>
			<td><input name="source[brand_data][{{$i}}][name]" value="{{$k}}"/>
				<input onclick="data_item_del(this);" type="button" value="删除"/>
			</td>
		</tr>
		<tr>
			<th>
				{{html id="mslt-brand-$i" tplid="mslt-tpl-brand-$i" callback="mslt_brand_back"
					remain="false"
					name="brand_ids" type="mslt" mdl="brand" label="品牌数据"}}
			</th>
			<td>
				<div id="mslt-tpl-brand-{{$i}}">
					{{if $r.datasource.brand_data}}
					{{include file="drt/brand-source-edit.tpl"}}
					{{/if}}
				</div>
			</td>
		</tr>
	</table>
</div>
<script>$(function(){
	ajaxinit();
});

function mslt_brand_back(msg,name_id,ids,tplid,mslt_id){
	
	var j = $('#'+tplid).find('.brand-source-item').size();

	var arr_t = mslt_id.split('-');
	var i = arr_t[arr_t.length-1];
	
	$.ajax({
		url:'/admin/drt/brand-source-add',
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
