{{foreach from=$data item=item}}
<dl>
	<dt><span>昵称：{{$item.user_name}}</span><em>发表时间：{{$item.add_time|date_format:'%Y-%m-%d %H:%M'}}</em></dt>
    <dd>{{$item.content}}</dd>
</dl>
{{/foreach}}
<div class="pagesize">{{$pageNav}}</div>