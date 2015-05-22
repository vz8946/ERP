<div class="data-item">
	<table class="tbl-frm">
		<tr>
			<th width="100">数据名称：</th>
			<td><input name="source[link_data][{{$i}}][name]" value="{{$k}}"/>
				<input onclick="data_item_del(this);" type="button" value="删除"/></td>
		</tr>
		<tr>
			<th>
				数据源
				<a id="btn-link-source-add-{{$i}}" class="btn-ajax-load" alc="#link-source-container-{{$i}}"
					data="i={{$i}}" befordo="link_source_add_befor" append="true" 
					href="/admin/drt/link-source-add" >[ + ]</a>
				：
			</th>
			<td>
				<div  id="link-source-container-{{$i}}" class="link-source-container">
					{{foreach from=$v item=vv key=kk}}
					{{assign var="j" value=$kk}}
					{{include file="drt/link-source-add.tpl"}}
					{{/foreach}}
				</div>
			</td>
		</tr>
	</table>
</div>

<script>

{{if !$r.datasource.link_data}}
$(function(){
	ajaxinit();
	$('#btn-link-source-add-{{$i}}').click();
});
{{/if}}

function link_source_add_befor($elt){
	var alc = $elt.attr('alc');	
	var count = $(alc).find('.link-source-item').size();
	var data = $elt.attr('data')+'&j='+(count+1);
	$elt.attr('data',data);
	return true;
}	

</script>

<style>
.source-item{
	padding: 5px;
	border: 1px solid #eee;
	background: #F5F5F5;
	width: 400px;
	display: inline-block;
}	

</style>