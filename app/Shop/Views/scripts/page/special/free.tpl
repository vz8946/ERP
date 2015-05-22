<style type="text/css">
	* {
		margin: 0;
		padding: 0;
	}
	li, ul {
		list-style-type: none;
	}
	ul li {
		float: left;
	}
	img {
		border: none;
		display: block;
	}
	a {
		text-decoration: none;
		color: #000;
	}
	a:hover {
		text-decoration: underline;
		color: #003;
	}
	h1, h2, h3, h4, h5, h6 {
		font-size: 12px;
	}
	.zqj_box {
		width: 990px;
		margin: 0 auto;
	}

	.floor {
		width: 990px;
	}
	.box {
		background: #FFF;
		width: 324px;
		border: #e0e0e0 solid 1px;
		float: left;
		margin-left: 6px;
		margin-bottom: 6px;
	}
	.text-1 {
		font-family: "微软雅黑";
		font-size: 14px;
		color: #000;
		text-align: center;
		height: 24px;
		line-height: 24px;
	}
	.text-2 {
		font-family: "微软雅黑";
		font-size: 14px;
		color: #000;
		height: 25px;
		float: left;
	}
	.text-3 {
		font-family: "微软雅黑";
		font-size: 34px;
		color: #de2703;
		height: 25px;
		float: left;
		font-weight: bold;
	}
	.text-4 {
		font-family: "宋体";
		font-size: 12px;
		color: #d90303;
		font-weight: bold;
	}
	.sale-infor {
		width: 108px;
		height: 24px;
		text-align: center;
		padding-top: 30px;
	}
	.aaa {
		clear: both;
	}
	.product-img {
		width: 286px;
		height: 247px;
		padding-left: 38px;
	}

	.bgimg {
		background-color: #f1e8df;
		background-image: url(/images/special/free/bg-0yuan.jpg);
		background-repeat: no-repeat;
		background-position: center top;
	}
</style>
<div class="bgimg">
	<div  class="zqj_box">

		<div id="id0yuan-hd02">
			<img src="{{$imgBaseUrl}}/images/special/free/0yuan-hd02.jpg" width="990" height="80" alt="">
		</div>

		<div id="id0yuan-hd04">
			<img src="{{$imgBaseUrl}}/images/special/free/0yuan-hd04.jpg" width="990" height="80" alt="">
		</div>
		<div id="id0yuan-hd05">
			<img src="{{$imgBaseUrl}}/images/special/free/0yuan-hd05.jpg" width="990" height="80" alt="">
		</div>
		<div id="id0yuan-hd06">
			<img src="{{$imgBaseUrl}}/images/special/free/0yuan-hd06.jpg" width="990" height="80" alt="">
		</div>
		<div id="id0yuan-hd07">
			<img src="{{$imgBaseUrl}}/images/special/free/0yuan-hd07.jpg" width="990" height="80" alt="">
		</div>
		<div id="id0yuan-hd08">
			<img src="{{$imgBaseUrl}}/images/special/free/0yuan-hd08.jpg" width="990" height="68" alt="">
		</div>
		<div style="width:990px;">
			{{html type="wdt" id="special_free_floor"}}
		</div>

	</div>
</div>


	<script type="text/javascript">
		//加入购物车
		function addCart(sn) {
			var number = 1;
			var productSn = sn;
			//第一次ajsx
			$.ajax({
				url : '/goods/check',
				data : {
					product_sn : productSn,
					number : number
				},
				type : 'get',
				success : function(msg) {
					if (msg != '') {
						alert(msg);
						window.location.reload();
						//window.location.replace('{{url}}');
					} else {
						//第二次ajax
						$.ajax({
							url : '/flow/actbuy/product_sn/' + productSn + '/number/' + number,
							type : 'get',
							success : function(msg) {
								if (msg != '') {
									alert(msg);
									return;
								}
								popOutBox();
								//id=popoutbox弹出层
								//第三次ajax
								$.ajax({
									url : '/index/cart/r/1',
									type : 'get',
									success : function(msg) {
										$('#popoutbox').html('<div id="msg1"><strong style="color:#1969CC;">该商品已成功放入购物车</strong></div>' + msg + '<div id="checknow" style="position:relative;padding-top:10px;line-height:40px"><a href="/flow/index"><img src="{{$imgBaseUrl}}/images/shop/btn_check_right_now.jpg" /></a>&nbsp;&nbsp;<a href="javascript:closePopOutBox();" style=" color:#1969CC;position:relative;top:-12px;">继续购物 >></a></div>');
										$('.top_cart').hide();
										$('.more').hide();
									}
								})
							}
						})
					}
				}
			})
		}
		
		/*遮罩层*/
		function maskLayer(color) {
			if (!color) {
				color = '#ffffff';
			}
			var tmpMask = new String;
			tmpMask = '<div id="maskLayer"></div>';
			$("body").prepend(tmpMask);
			$('#maskLayer').css({
				'width' : $(document).width() + 'px',
				'height' : $(document).height() + 'px',
				'position' : 'absolute',
				'top' : '0px',
				'left' : '0px',
				'z-index' : '60',
				'background-color' : color,
				'filter' : 'alpha(opacity=50)',
				'opacity' : '0.5'
			});
		}

		/*弹出层*/
		function popOutBox() {
			maskLayer('#353535');
			//调用遮罩层
			var tmpTips = new String;
			tmpTips = '<div id="popoutboxframe"><div id="closebtn"><img src="{{$imgBaseUrl}}/images/shop/closebtn.gif" /></div><div id="popoutbox" style="padding:15px 0 25px 0;padding-left:20px;"></div></div>';
			$("body").prepend(tmpTips);
			//弹出层样式
			$('#popoutboxframe').css({
				'position' : 'absolute',
				'left' : ($(window).scrollLeft() + $(window).width() / 2 - 160) + 'px',
				'top' : ($(window).scrollTop() + $(window).height() / 2 - 120) + 'px',
				'z-index' : '70',
				'width' : '420px',
				'box-shadow' : '0 0 6px #000',
				'border' : '1px solid #4C76C8',

				'background' : '#ffffff no-repeat 10px 40px',
				'text-align' : 'left',
				'text-indent' : '100px',
				'line-height' : '22px'
			});
			//关闭按钮样式
			$('#closebtn').css({
				'text-align' : 'right',
				'margin' : '1px',
				'height' : '30px',
				'background' : '#1767CA'
			})
			$('#closebtn img').css({
				'cursor' : 'pointer',
				'margin-top' : '10px',
				'margin-right' : '5px'
			})
			//关闭按钮动作
			$('#closebtn img').bind('click', function() {
				closePopOutBox();
			})
		}

		/*关闭弹出层*/
		function closePopOutBox() {
			$('#popoutboxframe').remove();
			$('#maskLayer').remove();
		}

	</script>
	