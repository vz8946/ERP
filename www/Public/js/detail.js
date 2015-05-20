$(document).ready(function(){
		$(".plus").click(function(){
			var qua=parseInt($(".txt-quantity").val());
			qua=qua<1?1:(qua+1);
			$(".txt-quantity").val(qua);
			$("#numshow").html(qua);
		});
		$(".reduce").click(function(){
			var qua=parseInt($(".txt-quantity").val());
			qua=qua>1?qua-1:1;
			$(".txt-quantity").val(qua);
			$("#numshow").html(qua);
		});

		var value=4;
		$(".review-level ul li").mouseover(function(){
			var index=$(this).index();
			$(".review-level ul li").each(function(ind){
				if(ind<=index)
				{
					$(this).attr("class","selectstar3");
				}
				else
				{
					$(this).attr("class","offstar3");
				}
			});
		}).mouseout(function(){
				$(".review-level ul li").each(function(ind){
				if(ind<=value)
				{
					$(this).attr("class","onstar3");
				}
				else
				{
					$(this).attr("class","offstar3");
				}
			});
		});
		$(".review-level ul").click(function(){
			value=$(".selectstar3").length-1;
			$("#goodsLevel").val(value+1);
			$(".selectstar3").attr("class","onstar3");
		});

	$("#bannerBox ul li").click(function(){
		$("#bannerBox").attr("class","tabs sloth");
		$(window).scrollTop(absTop);
		$("#bannerBox ul li a").removeClass("cur");
		$(this).children("a").attr("class","cur");
		$(".ditem").hide();
		var tabInd=$(this).index();
		if(tabInd==0){
			$(".ditem").show();
		}else{

			$("#ditem"+(tabInd+1)).show();
		}
	});
	$(".discount .tops ul li").click(function(){
		$(".discount .tops ul li a").removeClass("cur");
		$(this).children("a").attr("class","cur");
		$(".conts .txt").hide();
		var tabInd=$(this).index();
		$("#youhui"+(tabInd+1)).show();
	});
	//品牌分类选择
	$("#chiocBrandCate ul li").click(function(){
		$("#chiocBrandCate ul li a").removeClass();
		$(this).children("a").attr("class","cur");
		$(".choic").hide();
		var tabInd=$(this).index();
		$("#choic"+(tabInd+1)).show();
	});
	//弹出登陆框
	$("#syncSub").click(function(){
			var flag = $("#valNO").val();
			if(flag == ""){
				syncLogin("buy");
			}else{
				$("#publish-review-all").show();
				window.location.href="#publish-review";
			}
	});

	$("#go-pinglun").click(function(){
		var flag = $("#valNO").val();
		if(flag == ""){
			$("#xj_login").show();
		}else{
			$("#publish-review-all").show();
			window.location.href="#publish-review";
		}
	});
	//优惠套餐选择
	$(".youhuitaocan-list ul li").click(function(){
			$(".youhuitaocan-list .yhon").attr("class","yhoff");
			$(this).attr("class","yhon");
			$(".youhuitaocan-box dd").attr("class","display");
			$(".youhuitaocan-box dd:eq("+$(this).index()+")").attr("class","show");
	});
	//轮放放大图片
	$(".dleft .control ul li").mouseenter(function(){
		$(".dleft .control ul li a").removeClass();
		$(this).children("a").attr("class","cur");
		var src=$(this).attr("dateUrl");
		var title=$(".bigImage").attr("alt");
		$("#bigImageBox").html('<a href="'+src+'" title="'+title+'" class="MagicZoom"> <img class="bigImage" height="350" width="350" src="'+src+'" alt="'+title+'"/> </a>');
		MagicZoom_findZooms();
	});
	var absTop=calculateOffset($("#bannerBox")[0], "offsetTop")-33;
	var heightTop=calculateOffset($("#chiocBrandCate")[0], "offsetTop")-33;
	//滚动条滚动绑定位置
	$(window).scroll(function () {
		var $body = $("body");

        /*判断窗体高度与竖向滚动位移大小相加 是否 超过内容页高度*/

        if ($(window).scrollTop() > absTop && $(window).scrollTop()<heightTop) {
        	$("#bannerBox").attr("class","tabs sloth fixed-top");
        }else{
        	$("#bannerBox").attr("class","tabs sloth");
        }
    });
    intervalId=window.setInterval("doImageTurn()",spleeds);

		$("#choic1").mouseenter(function(){
			window.clearInterval(intervalId);
		}).mouseleave(function(){
			intervalId=window.setInterval("doImageTurn()",spleeds);
		});
    //底部品牌轮放
    $("#brandPrev").click(function(){
			if((beginId)<=(totalLen-6)){
				$("#brandListBox ul li:eq("+beginId+")").hide("fast");
				$("#brandListBox ul li:eq("+(beginId+7)+")").fadeIn("normal");
				beginId=beginId+1;
			}else{
				beginId=0;
				$("#brandListBox ul li").show();
			}
		});
		$("#brandNext").click(function(){
			if(beginId>0){
				beginId=beginId-1;
				$("#brandListBox ul li:eq("+beginId+")").fadeIn("normal");
				$("#brandListBox ul li:eq("+(beginId+7)+")").hide();
			}
		});
});
function quesdis(){
	var flag = $("#valNO").val();
	if(flag == ""){
		$("#xj_login").show();
	}else{
		$("#twt").css("display","block");
	}
}
//计算绝对位置
function calculateOffset(field, attr)
{
	var offset = 0;
	while(field) {
		offset += field[attr];
		field = field.offsetParent;
	}
	return offset;
}
function addGoodsToCart(url){
	window.location.href=url+"-num"+$("#buyQuantity").val()+".html";
}
//轮选上下移动放大图标
		var passLen=1;
		var spleeds=2500;
		var intervalId;
		var beginId=0;
		var totalLen=$("#brandListBox ul li").length;

		function doImageTurn(){
			$("#brandListBox ul li:eq("+beginId+")").hide("normal");
			$("#brandListBox ul li:eq("+(beginId+7)+")").fadeIn("normal");
			beginId=beginId+1;
			if(beginId>(totalLen-6)){
				beginId=0;
				$("#brandListBox ul li").show();
			}
		}
function chkwenda(form){

	var val=form.questionName.value.replace(/^\s+|\s+$/g,"");
	if(val==''){
		alert("请输入提问内容！");
		form.questionName.focus();
		return false;
	}
	else{
		return true;
	}
}
function chkCommon(form){
	var val=form.comments.value.replace(/^\s+|\s+$/g,"");
	if(val==''){
		alert("请输入评论内容！");
		form.comments.focus();
		return false;
	}
	else{
		return true;
	}
}