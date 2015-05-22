{{foreach from=$list_news item=v key=k}}
<div class="news-source-item source-item">
	<table width="100%">
		<tr>
			<td rowspan="6" width="100">{{html type="pic" 
				name="source[news_data][$i][$j$k][img]" id="pic-news-img-$i-$j-$k" value=$v.news_img}}
			</td>
		</tr>
		<tr>
			<td>
				<input type="hidden" name="source[news_data][{{$i}}][{{$j}}{{$k}}][id]" value="{{$v.id}}"/>
				名称：<input size="30" name="source[news_data][{{$i}}][{{$j}}{{$k}}][title]" value="{{$v.title}}"/>
			</td>
		</tr>
		<tr>
			<td>
				简述：<input size="30" name="source[news_data][{{$i}}][{{$j}}{{$k}}][memo]" value="{{$v.seoDescription}}"/>
			</td>
		</tr>
		<tr>
			<td>URL：<input size="30" name="source[news_data][{{$i}}][{{$j}}{{$k}}][url]"/></td>
		</tr>
		<tr>
			<td>排序：<input name="source[news_data][{{$i}}][{{$j}}{{$k}}][ord]" size="5" value="0"/></td>
		</tr>
		<tr>
			<td>
				可用：{{html type="slt" opt=$opt_enable  name="source[news_data][$i][$j$k][enable]" value="Y"}}
				&nbsp;&nbsp;
				操作： <input onclick="source_item_del(this);" type="button" value="删除"/>
			</td>
		</tr>
	</table>
</div>
{{/foreach}}
