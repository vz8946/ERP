{{if !$is_index_page}}
<div id="top-banner1" class="top-banner1" style="width: 990px;margin: 0px auto;overflow: hidden;">
		<a href="http://www.1jiankang.com/zt/detail-68.html" target="_blank"><img width="990" src="{{$imgBaseUrl}}/images/shop/comn-banner1.jpg"/></a></div>
{{else}}
<div id="top-banner1" class="top-banner1" style="width: 1200px;margin: 0px auto;overflow: hidden;display: none;"><a href="http://www.1jiankang.com/zt/detail-68.html" target="_blank"><img src="{{$imgBaseUrl}}/images/shop/comn-banner1.jpg"/></a></div>
<div id="top-banner2" class="top-banner2" style="width: 1200px;margin: 0px auto;overflow: hidden;"><a href="http://www.1jiankang.com/zt/detail-68.html" target="_blank"><img src="{{$imgBaseUrl}}/images/shop/comn-banner2.jpg"/></a></div>
<script>
setTimeout(function(){
	$('#top-banner2').slideUp(2000,function(){
		$('#top-banner1').slideDown();
	});
},3000);
</script>
{{/if}}