<h4>用户评论<span id="msgtot">({{$total}})</span>条</h4>
<ol>
{{if $datas}}
{{foreach from=$datas item=item}}				
<li><div class="time"><em>{{$item.user_name}}</em>用过后评 
{{foreach from=$item.cnt1 item=cnt}}
<img src="{{$imgBaseUrl}}/images/shop/try/star_orange.jpg" width="10px" height="10px">
{{/foreach}}
<strong>{{$item.add_time|date_format:"%Y-%m-%d %T"}}</strong></div>
<p>{{$item.content}}</p>
</li>
{{/foreach}}
{{$pageNav}}
{{/if}}	
</ol>