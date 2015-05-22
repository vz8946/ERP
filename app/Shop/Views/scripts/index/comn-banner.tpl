{{if $index_banner_show}}
<div id="top-banner1" class="top-banner1" style="width: 1200px;margin: 0px auto;overflow: hidden;"><img src="{{$imgBaseUrl}}/images/shop/comn-banner1.jpg"/></div>
{{else}}
<div id="top-banner1" class="top-banner1" style="width: 1200px;margin: 0px auto;overflow: hidden;display: none;"><img src="{{$imgBaseUrl}}/images/shop/comn-banner1.jpg"/></div>
<div id="top-banner2" class="top-banner2" style="width: 1200px;margin: 0px auto;overflow: hidden;"><img src="{{$imgBaseUrl}}/images/shop/comn-banner2.jpg"/></div>
<script>
setTimeout(function(){
	$('#top-banner2').slideUp(2000,function(){
		$('#top-banner1').slideDown();
	});
},3000);
</script>
{{/if}}
