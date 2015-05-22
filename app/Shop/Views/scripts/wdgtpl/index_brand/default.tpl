<script type="text/javascript">
$('#brand-list').ready(function(){
	$('#brand-jcl-{{$__sys_wtpl_name}}').jcarousel({
		auto : 5,
		wrap: 'circular',
		scroll : 8
	}); 
});
</script>
<div id="brand-list" class="brand-list">
<ul id="brand-jcl-{{$__sys_wtpl_name}}" class="jcarousel-skin-default" style="width: 100%;">
{{foreach from=$brands item=v key=kl}}
	<li>
		<a href="{{$v.url}}"><img alt="{{$v.title}}"  width="120" height="58" src="{{$imgBaseUrl}}/{{$v.img}}"/></a>
	</li>
{{/foreach}}
</ul>
</div>
