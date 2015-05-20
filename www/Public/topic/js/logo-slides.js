


$(document).ready(function(){
		$(".list-content-tab ul li").mouseover(function(){
		$(".list-tab-select").attr("class","list-tab-unselect");
		$(this).attr("class","list-tab-select");
		$(".list-content-box dl").hide();
		$(".list-content-box dl:eq("+$(this).index()+")").show();
		});
	 })