<link rel="stylesheet" type="text/css" href="/_static/css/list.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/sale.css"/>
<div class="position wbox">
	<b><a href="/group-goods/">组合商品</a></b><span> &gt; 最值得信赖的保健商城</span>
</div>

<div class="wbox">
	<div class="rotave">
		<ul id="adv_113_100" style="position: relative; width: 758px; height: 302px;">
			<li style="position: absolute; top: 0px; left: 0px; display: block; z-index: 4; opacity: 1; width: 758px; height: 302px;">
				<a target="_blank" href="#"><img alt="组合商品" src="/images/group.jpg"></a>
			</li>
		</ul>
	</div>
	<div class="radv218">
		<a title="基维斯蜂蜜" target="_blank" href="#"><img width="218" height="298" border="0" alt="基维斯蜂蜜" src="{{$imgBaseUrl}}/img/2012/10/31/210019_218X298.jpg"></a>
	</div>
</div>
{{strip}}
<div class="groups mar" style="width: 990px;margin: 0px auto;">
	<div class="ad" style="display:none;">
		<a href="/special-38.html" target="_blank"><img src="{{$imgBaseUrl}}/images/shop/index_ad02.jpg" /></a>
	</div>
	<ul class="clear grouplist">
		{{foreach from=$datas key=key item=data}}
		<li style="{{if $key%2 == 1}}margin-right: 0px;{{/if}}position:relative;" >
			<div style="position: absolute;left:0px;top: 0px;">
			<a style="position: absolute;left:20px;top:26px;display: block;"

			href="/groupgoods-{{$data.group_id}}.html" target="_blank"><img style="border:none;" src="{{$imgBaseUrl}}/{{$data.group_goods_img|replace:'.':'_180_180.'}}" alt="{{$data.goods_name}}"/></a>
			<p style="position: absolute;left:220px;top:24px;width: 250px;">
				<a href="/groupgoods-{{$data.group_id}}.html" target="_blank" > <span style="font-size: 1.4em;font-family: Microsoft Yahei;">{{$data.group_sale_name}}</span> </a>
			</p>

			<p style="position:absolute;left:220px;top:90px; width: 271px;background: #f5f5f5;height: 90px;text-align: left;">
				<span style="padding:5px;font-size: 12px;">&nbsp;&nbsp;市场价：¥{{$data.group_market_price}}</span>
				<span class="p1"><a href="javascript:;" onclick="addCart({{$data.group_id}});" class="buttons btn-add-cart"></a></span>
				<span style="position: absolute;top:36px;left:18px;"><strong style="color: #fff;">¥{{$data.group_price}}</strong></span>
			</p>
			</div>
		</li>
		{{/foreach}}
		<div style="clear:both;"></div>
	</ul>
	<div style="clear:both;"></div>
	<div class="pageNav">
		{{$pageNav}}
	</div>
</div>
{{/strip}}
<script type="text/javascript">
	function addCart(g_id) {
		var tmp = parseInt(g_id);
		$.ajax({
			url : '/group-goods/check',
			data : {
				group_id : tmp
			},
			type : 'post',
			success : function(msg) {
				if (msg != '') {
					alert(msg);
				} else {
					popOutBox();
					//id=popoutbox弹出层
					//第二次ajax
					$.ajax({
						url : '/index/cart/r/1',
						type : 'get',
						success : function(msg) {
							$('#popoutbox').html('<div id="msg1" style="text-align:center;"><span style=" color:#1969CC;">该商品已成功放入购物车</span></div>' + msg + '<div id="checknow" style="text-align:center;"><a href="/flow/index"><img src="{{$imgBaseUrl}}/images/shop/btn_check_right_now.jpg" /></a>&nbsp;&nbsp;<a style="position:relative;top:-12px;" href="javascript:closePopOutBox();">继续购物&raquo;&raquo;</a></div>');
							$('.top_cart').hide();
							$('.more').hide();
						}
					})
				}
			}
		});
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
		tmpTips = '<div id="popoutboxframe"><div id="closebtn"><img src="{{$imgBaseUrl}}/images/shop/closebtn.gif" /></div><div id="popoutbox" style="padding:15px 0 25px 0"></div></div>';
		$("body").prepend(tmpTips);
		//弹出层样式
		$('#popoutboxframe').css({
			'position' : 'absolute',
			'left' : ($(window).scrollLeft() + $(window).width() / 2 - 160) + 'px',
			'top' : ($(window).scrollTop() + $(window).height() / 2 - 120) + 'px',
			'z-index' : '70',
			'width' : '500px',
			'border' : '2px solid  #4C76C8',
			'background' : '#ffffff no-repeat 20px 50px',
			'text-align' : 'left',
			'line-height' : '22px'
		});
		//关闭按钮样式
		$('#closebtn').css({
			'text-align' : 'right',
			'margin' : '1px',
			'height' : '28px',
			'background' : '#1767CA'
		})
		$('#closebtn img').css({
			'cursor' : 'pointer',
			'margin-top' : '8px',
			'margin-right' : '2px'
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
	};

	$(function(){
		$('.grouplist').find('li').hover(function(){
			$(this).css({'border':'1px solid red'});
		},function(){
			$(this).css({'border':'1px solid #ddd'});
		});
	});

</script>