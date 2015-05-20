<div id="slideadv" class="slideadv fl">
	{{foreach from=$link1 item=v key=k}}
	{{if $k == 0}}
	<div style="display: block;" class="adv">
	{{else}}	
	<div style="display: none;" class="adv">
	{{/if}}
		{{if $v.url == '#'}}
			<img alt="{{$v.title}}" src="{{$imgBaseUrl}}/images/loading.gif" _src="{{$imgBaseUrl}}/{{$v.img}}"/>
		{{else}}
			<a {{if $v.is_new_win == 'Y'}} target="_blank" {{/if}} name="{{$v.memo}}" {{if $v.is_new_win == 'Y'}}target="_blank"{{/if}} href="{{$v.url}}"><img alt="{{$v.title}}" src="{{$imgBaseUrl}}/images/loading.gif" _src="{{$v.img}}"/></a>
		{{/if}}
	</div>
	{{/foreach}}
	<div class="num">
		<ul>
			{{foreach from=$link1 item=v key=k}}
			{{if $k == 0}}
			<li class="cur">
			{{else}}	
			<li>
			{{/if}}
			</li>
			{{/foreach}}
		</ul>
	</div>
</div>

<div style="float: left;width: 288px;height: 358px;margin-left: 10px;overflow: hidden;border: 1px solid #eee;">
	{{foreach from=$link2 item=v key=k}}
	{{if $k == 0}}
	<div style="height: 120px;overflow: hidden;">
	{{else}}
	<div style="height: 119px;overflow: hidden;padding-top: 1px;">
	{{/if}}
		
		{{if $v.url == '#'}}
			<img alt="{{$v.title}}" src="{{$imgBaseUrl}}/images/loading.gif" _src="{{$imgBaseUrl}}/{{$v.img}}"/>
		{{else}}
			<a {{if $v.is_new_win == 'Y'}} target="_blank" {{/if}} name="{{$v.memo}}" href="{{$v.url}}"><img alt="{{$v.title}}" src="{{$imgBaseUrl}}/images/loading.gif" _src="{{$imgBaseUrl}}/{{$v.img}}"/></a>
		{{/if}}
		
	</div>
	{{/foreach}}
</div>

<script>$(function(){
	$('#slideadv').find('.adv').find('.i').mouseenter(function(){
		$('#slideadv').css({background:'#000'});
		$(this).siblings().find('img').css({opacity:0.7});
	}).mouseout(function(){
		$(this).siblings().find('img').css({opacity:1});
	});
});</script>
