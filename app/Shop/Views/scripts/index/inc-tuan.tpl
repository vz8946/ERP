<div id="index-tuan" class="index-tuan" style="height: 310px;"></div>
<script>
$.ajax({
	url:'/index/tuan',
	type:'get',
	dataType:'html',
	async: true,
	beforeSend:function(req){
		$('#index-tuan').addClass('tuan-loading');		
	},
	success:function(msg){
		$('#index-tuan').removeClass('tuan-loading');
		$('#index-tuan').html(msg);
	}
});
</script>
