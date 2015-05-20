<div class="data-item">
	<table class="tbl-frm">
		<tr>
			<th width="100">数据名称：</th>
			<td><input name="source[news_data][{{$i}}][name]" value="{{$k}}"/>
				<input onclick="data_item_del(this);" type="button" value="删除"/>
			</td>
		</tr>
		<tr>
			<th>
				{{html id="mslt-news-$i" tplid="mslt-tpl-news-$i" callback="mslt_news_back"
					remain="false"
					name="news_ids" type="mslt" mdl="news" label="资讯"}}
			</th>
			<td>
				<div id="mslt-tpl-news-{{$i}}">
					{{if $r.datasource.news_data}}
					{{include file="drt/news-source-edit.tpl"}}
					{{/if}}
				</div>
			</td>
		</tr>
	</table>
</div>
<script>$(function(){
	ajaxinit();
});

function mslt_news_back(msg,name_id,ids,tplid,mslt_id){
	
	var j = $('#'+tplid).find('.news-source-item').size();
	var arr_t = mslt_id.split('-');
	var i = arr_t[arr_t.length-1];
	
	$.ajax({
		url:'/admin/drt/news-source-add',
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
