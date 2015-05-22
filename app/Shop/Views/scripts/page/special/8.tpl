<script language="javascript" type="text/javascript" src="{{$_static_}}/js/jquery.scrollfollow.js"></script>
<style>
	.topic-container * {
		margin: 0px;
		padding: 0px;
		font-size: 12px;
		line-height: 20px;
		font-family: '宋体', 'Lucida Grande', Verdana, Arial, Sans-Serif;
	}

	.topic-container {
		padding-bottom: 20px;
		background: #28366B;
		padding-top:10px;
		position: relative;
	}

	.topic-body {
		background: url('/images/special/8/bg1.jpg') no-repeat center 0px;
		width: 990px;
		margin: 0px auto;
		position: relative;
		overflow: hidden;
	}

	.topic-goods-item {
		width: 242px;
		height: 360px;
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
		background: url('/images/special/2/line1.gif') no-repeat 50% bottom;
		height: 176px;
		overflow: hidden;
		padding-bottom: 5px;
		text-align: center;
	}
	.topic-goods-item .imgs a {
		display: inline-block;
	}

	.topic-goods-item .info {
		padding: 16px;
		padding-top: 10px;
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
		width: 212px;
		line-height: 30px;
		text-align: center;
	}

	.topic-goods-item .gt {
		height: 50px;
		line-height: 16px;
		padding-top: 2px;
	}

	.wrp-quan {
		height: 160px;
		background: url('/images/special/2/bg-quan.jpg') no-repeat center;
		margin-bottom: 10px;
	}

	.wrp-quan .quan {
		display: inline-block;
		height: 160px;
		width: 324px;
	}
	
	.menu-follow{
		position: absolute;
		top:8px;
		left:10px;
		width: 100px;
		background: #fff;
		border:2px solid #1C1185;
		padding:5px 0px;
	}
	
	.menu-follow a{
		display: block;
		padding:5px;
		text-align: center;
		text-decoration: none;
		font-size: 1.2em;
		color: #1C1185;
		font-weight: bold;
	}
	.menu-follow a:hover{
		background: #1C1185;
		color: #fff;
	}
	
	.menu-follow .back a{
		color: #E3007B;
	}

	.menu-follow .back a:hover{
		color: #fff;
	}

</style>
<div id="topic-container" class="topic-container">
	<div class="topic-body">
		<div class="topic-main">
			<div style="padding-top:580px;overflow: hidden;">
				
				<div id="p5"><img src="/images/special/8/t5.jpg"/></div>
				{{html type="wdt" id="special_8_floor_5"}}
				<div style="height: 30px;overflow: hidden;"></div>

				<div id="p4"><img src="/images/special/8/t4.jpg"/></div>
				{{html type="wdt" id="special_8_floor_4"}}
				<div style="height: 30px;overflow: hidden;"></div>

				<div id="p3"><img src="/images/special/8/t3.jpg"/></div>
				{{html type="wdt" id="special_8_floor_3"}}
				<div style="height: 30px;overflow: hidden;"></div>
				
				<div id="p2"><img src="/images/special/8/t2.jpg"/></div>
				{{html type="wdt" id="special_8_floor_2"}}
				<div style="height: 30px;overflow: hidden;"></div>

				<div id="p1"><img src="/images/special/8/t1.jpg"/></div>
				{{html type="wdt" id="special_8_floor_1"}}
				<div style="height: 30px;overflow: hidden;"></div>
				
				
				
				
				
				<div style="clear:both;"></div>
			</div>
		</div>
	</div>
	
	<div id="menu-follow" class="menu-follow">
		<ul>
			<li><a href="#p1">补肾壮阳</a></li>
			<li><a href="#p2">前列腺</a></li>
			<li><a href="#p3">解酒护肝</a></li>
			<li><a href="#p4">缓解疲劳</a></li>
			<li><a href="#p5">情趣用品</a></li>
			<li class="back"><a href="/zt/detail-79.html">返回主会场</a></li>
		</ul>
	</div>
	
</div>
{{include file="page/special/bottom-menu.tpl"}}

<script type="text/javascript">
$(function(){
	$('#menu-follow').scrollFollow({
		container:'topic-container',
		offset:200
	});
});
</script>

<script>
	

function quan_bind(sn,pwd){

	var is_login = false;	
	$.ajax({
		url:'/auth/js-auth-user-id',
		type:'get',
		async:false,
		success:function(msg){
			if(msg != ''){
				is_login = true;
			}
		}
	});
	
	if(!is_login){
		alert('请先登陆');
		window.location.href = '/login.html';
		return;
	}
	
	$.ajax({
		url:'/member/active-coupon',
		type:'POST',
		data:{card_sn:sn,card_pwd:pwd},
		success:function(msg){
			if(msg == 'ok'){
				alert('绑定成功，请到会员中心查看券号和密码！');
			}else if(msg == 'card binded'){
				alert('请不要重复绑定！');
			}else if(msg == 'card not exists'){
				alert('请求类型的卡号不存在！');
			}			
			return;
		}
	});
	return;
	
}

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
