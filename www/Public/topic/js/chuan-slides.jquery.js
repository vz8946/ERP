

//楼层Tab1
$(document).ready(function(){
		$(".list-content-tab ul li").mouseover(function(){
		$(".list-tab-select").attr("class","list-tab-unselect");
		$(this).attr("class","list-tab-select");
		$(".list-content-box dl").hide();
		$(".list-content-box dl:eq("+$(this).index()+")").show();
		});
	 })
	
	
	
//畅销排行榜1
$(document).ready(function(){
	 $(".hot-product .product-list ul li").mouseover(function(){
		$(".hot-product .product-list ul li .product-seq-box-hid").attr("class","product-seq-box");
		$(".hot-product .product-list ul li dl").attr("class","display");
		$(".hot-product .product-list ul li .product-seq-title").attr("class","product-seq-title display");
		$(this).children('dl').each(function(ind){
			if(ind==0)	
			{
				$(this).attr("class","show");	
			}else 
			{			$(this).attr("class","display");
			}
		});
		$(this).children('h6').attr("class","product-seq-title");
		$(this).children('dl').attr("class","show");
		$(this).children('.product-seq-box').attr("class","product-seq-box-hid");
	 })
	 })	

//楼层Tab2
$(document).ready(function(){
		$(".list-content-tab-2 ul li").mouseover(function(){
		$(".list-tab-select-2").attr("class","list-tab-unselect-2");
		$(this).attr("class","list-tab-select-2");
		$(".list-content-box-2 dl").hide();
		$(".list-content-box-2 dl:eq("+$(this).index()+")").show();
		});
	 })

//楼层Tab3
$(document).ready(function(){
		$(".list-content-tab-3 ul li").mouseover(function(){
		$(".list-tab-select-3").attr("class","list-tab-unselect-3");
		$(this).attr("class","list-tab-select-3");
		$(".list-content-box-3 dl").hide();
		$(".list-content-box-3 dl:eq("+$(this).index()+")").show();
		});
	 })
//楼层Tab4
$(document).ready(function(){
		$(".list-content-tab-4 ul li").mouseover(function(){
		$(".list-tab-select-4").attr("class","list-tab-unselect-4");
		$(this).attr("class","list-tab-select-4");
		$(".list-content-box-4 dl").hide();
		$(".list-content-box-4 dl:eq("+$(this).index()+")").show();
		});
	 })
//楼层Tab5
$(document).ready(function(){
		$(".list-content-tab-5 ul li").mouseover(function(){
		$(".list-tab-select-5").attr("class","list-tab-unselect-5");
		$(this).attr("class","list-tab-select-5");
		$(".list-content-box-5 dl").hide();
		$(".list-content-box-5 dl:eq("+$(this).index()+")").show();
		});
	 })

