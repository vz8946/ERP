<style>
	.topic-container * {
		margin: 0px;
		padding: 0px;
		font-size: 12px;
		line-height: 20px;
		font-family: "Microsoft YaHei", '微软雅黑', '宋体', 'Lucida Grande', Verdana, Arial, Sans-Serif;
	}

	.topic-container {
		background: url('/images/special/3/bg-topic-body.jpg') #E6E6E6 repeat-x center 0px;
		padding-bottom: 20px;
	}
	.topic-body {
		width: 990px;
		margin: 0px auto;
		position: relative;
	}

	.topic-goods-item {
		width: 492px;
		background: #fff;
		float: left;
		margin-right: 6px;
		margin-bottom: 5px;
		overflow: hidden;
	}

	.topic-goods-item .imgs img {
		display: inline-block;
		margin: 0px auto;
	}

	.topic-goods-item .imgs {
		height: 240px;
		overflow: hidden;
		text-align: center;
		width: 240px;
		margin-right: 10px;
		padding:10px;
	}
	.topic-goods-item .imgs a {
		display: inline-block;
	}

	.topic-goods-item .info {
		padding: 16px;
		padding-top: 60px;
		background: url('/images/special/2/line1.gif') no-repeat 260px 90px;
		
	}

	.topic-container .c1 {
		color: #317EE7;
	}

	.topic-container .c2 {
		color: red;
	}

	.topic-container .c3 {
		color: #aaa;
	}

	.topic-container .fb {
		font-weight: bold;
	}

	.topic-container .fs1 {
		font-size: 30px;
	}

	.topic-container .fs2 {
		font-size: 12px;
	}

	.topic-container .fl1 {
		text-decoration: line-through;
	}

	.topic-goods-item .btn {
		display: inline-block;
		background: #317EE7;
		color: #fff;
		text-decoration: none;
		font-size: 18px;
		height: 30px;
		width: 170px;
		line-height: 30px;
		text-align: center;
	}

	.topic-goods-item .gt {
		height: 50px;
		line-height: 16px;
		padding-top: 2px;
	}

</style>
<div class="topic-container">
	<div class="topic-body">
		<a target="_blank" href="http://www.1jiankang.com/zt/detail-62.html" style="width: 220px;height: 50px;position: absolute;top: 450px;right:85px;"></a>
		<div class="topic-main">
			<div style="padding-top: 562px;">
				{{html type="wdt" id="special_3_floor"}} 
				<div style="clear:both;"></div>
			</div>
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
