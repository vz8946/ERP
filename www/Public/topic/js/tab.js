$(document).ready(function(){
	 $(".hot-product .product-list ul li").mouseenter(function(){
		 	$ul = $(this).parent();
		 	$ul.find('li').find('>h6').addClass('display');
		 	$ul.find('li').find('>p').removeClass('product-seq-box-hid').addClass('product-seq-box');
		 	$ul.find('li').find('>dl').removeClass('show').addClass('display');
			
			$(this).find('>h6').removeClass('display');
			$(this).find('>p').removeClass('product-seq-box').addClass('product-seq-box-hid');
			$(this).find('>dl').removeClass('display').addClass('show');
		 
	 });

	 $(".list-content-tab ul li").mouseover(function(){
		$(".list-tab-select").attr("class","list-tab-unselect");
		$(this).attr("class","list-tab-select");
		$(".list-content-box dl").hide();
		$(".list-content-box dl:eq("+$(this).index()+")").show();
		});
		$(".list-content-tab-2 ul li").mouseover(function(){
		$(".list-tab-select-2").attr("class","list-tab-unselect-2");
		$(this).attr("class","list-tab-select-2");
		$(".list-content-box-2 dl").hide();
		$(".list-content-box-2 dl:eq("+$(this).index()+")").show();
		});

		$(".list-content-tab-3 ul li").mouseover(function(){
		$(".list-tab-select-3").attr("class","list-tab-unselect-3");
		$(this).attr("class","list-tab-select-3");
		$(".list-content-box-3 dl").hide();
		$(".list-content-box-3 dl:eq("+$(this).index()+")").show();
		});

		$(".list-content-tab-4 ul li").mouseover(function(){
			$(".list-tab-select-4").attr("class","list-tab-unselect-4");
			$(this).attr("class","list-tab-select-4");
			$(".list-content-box-4 dl").hide();
			$(".list-content-box-4 dl:eq("+$(this).index()+")").show();
		});
		
		$(".list-content-tab-5 ul li").mouseover(function(){
			$(".list-tab-select-5").attr("class","list-tab-unselect-5");
			$(this).attr("class","list-tab-select-5");
			$(".list-content-box-5 dl").hide();
			$(".list-content-box-5 dl:eq("+$(this).index()+")").show();
		});
		
		$(".qiang-content-tab ul li").mouseover(function(){
			$(".qiang-tab-select").attr("class","qiang-tab-unselect");
			$(this).attr("class","qiang-tab-select");
			$(".qiang-content-box dl").hide();
			$(".qiang-content-box dl:eq("+$(this).index()+")").show();
		});
});
