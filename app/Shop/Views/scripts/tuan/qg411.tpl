<style type="text/css">
	.bg {
		background: url('/images/shop/tuan/qg411/bg.jpg') top center no-repeat;
		width: 100%;
		overflow: hidden;
	}
	
	.main {
		width: 990px;
		margin: 0 auto;
		overflow: hidden;
	}
	
	.top img {
		float: left;
	}
	
	.content {
		width: 990px;
		overflow: hidden;
		background: #a90f52;
	}
	
	.timebar {
		width: 990px;
		height: 85px;
		padding: 1px 0 0 0;
		background: #fff5dc;
		border-bottom: 1px solid #a90f52;
	}
	.timebar .fl {
		float: left;
		width: 34px;
		height: 85px;
	}
	.timebar .fr {
		float: right;
		width: 955px;
		height: 85px;
	}
	.timebar .fr .tab li {
		float: left;
		display: inline;
	}
	.timebar .fr .tab li a {
		display: block;
		width: 81px;
		height: 22px;
		background: #c7193e;
		text-align: center;
		color: #fff;
		font-size: 13px;
		line-height: 22px;
		text-decoration: none;
	}
	.timebar .fr .tab li a.current {
		width: 118px;
	}
	.timebar .fr .tab li.on a {
		background: #fff5dc;
		color: #2d2d2d;
	}
	.timebar .fr .tab li span {
		display: block;
		width: 270px;
		height: 22px;
		background: #000000;
		color: #fff;
		text-align: center;
	}
	.timebar .fr .tab_con {
		clear: both;
		width: 955px;
		height: 63px;
	}
	.timebar .fr .tab_con li {
		display: none;
		position: relative;
		width: 762px;
		height: 20px;
		background: url(/images/shop/tuan/qg411/bg_timeBar.jpg) no-repeat;
		margin-left: 82px;
	}
	.timebar .fr .tab_con li .stage {
		position: absolute;
		width: 100px;
		height: 58px;
		top: 6px;
		text-align: center;
		line-height: 1.5em;
		cursor: pointer;
	}
	.timebar .fr .tab_con li .stage01 {
		left: -43px;
	}
	.timebar .fr .tab_con li .stage02 {
		left: 330px;
	}
	.timebar .fr .tab_con li .stage03 {
		right: -43px;
	}
	.timebar .fr .tab_con li .stage span {
		display: block;
		width: 100px;
		height: 43px;
		margin: 2px 0 0 0;
		color: #a38726;
		font-size: 14px;
	}
	.timebar .fr .tab_con li .stage span.end {
		color: #666666;
	}
	.timebar .fr .tab_con li .stage span.current {
		background: url(/images/shop/tuan/qg411/bg_state.gif) top center no-repeat;
		color: #fff;
	}

	.product {
		position: relative;
		clear: both;
		width: 981px;
		height: 404px;
		overflow: hidden;
		margin: 5px 0 0 0;
		background: #d7d7d7;
		padding: 8px;
		padding-right: 0px;
	}
	.product ul {
		position: relative;
		width: 2973px;
	}
	.product ul li {
		float: left;
		display: inline;
		position: relative;
		width: 320px;
		height: 398px;
		overflow: hidden;
		background: #ffffff;
		margin-right: 7px;
		text-align: center;
		padding: 1px 0 0 0;
	}
	.product ul li img {
		float: left;
	}
	.product ul li p {
		clear: both;
		width: 320px;
		height: 30px;
		color: #2d2d2d;
		font-size: 14px;
		line-height: 30px;
		color: #2d2d2d;
		overflow: hidden;
	}
	.product ul li .num {
		position: absolute;
		top: 0;
		left: 0;
		width: 83px;
		height: 84px;
		background: url(/images/shop/tuan/qg411/bg_num.png) no-repeat;
		_background: url(/images/shop/tuan/qg411/bg_num.gif) no-repeat;
		color: #ffffff;
		font-size: 16px;
		padding: 10px 0 0 0;
	}
	.product ul li .num em {
		color: #fffc59;
		font-size: 18px;
		font-weight: bold;
	}
	.product ul li .price {
		position: absolute;
		bottom: 0px;
		left: 12px;
		width: 158px;
		height: 52px;
		color: #ebc5ca;
		font-size: 14px;
		text-align: left;
	}
	.product ul li .price em {
		color: #fefb54;
		font-size: 18px;
		font-weight: bold;
	}
	.bottom {
		width: 100%;
		height: 554px;
		background: url(/images/shop/tuan/qg411/bottom.jpg) top center no-repeat;
	}
	.bottom .rule {
		width: 770px;
		margin: 0 auto;
		padding: 255px 0 0 0;
		color: #e4e8fd;
		font-size: 14px;
		font-family: "微软雅黑";
		line-height: 1.2em;
	}
	.bottom .rule p {
		margin: 10px 0;
	}
	
	#qg411-clock p,#qg411-clock b,#qg411-clock em{
		display: inline-block;
	}
	
</style>

<div class="bg">
	<div class="main">
		<div class="top">
			<img src="/images/shop/tuan/qg411/top_01.jpg" width="990" height="150" />
			<img src="/images/shop/tuan/qg411/top_02.jpg" width="990" height="150" />
		</div>
		<!---content begin--->
		<div class="content">
			<div class="timebar">
				<div class="fl">
					<a href="#"><img src="/images/shop/tuan/qg411/fengqiang.jpg" width="34" height="85" /></a>
				</div>
				<div class="fr">
					<div class="tab">
						<ul>
							{{foreach from=$day_div item=v key=k}}
							<li onclick="ajax_tab(this,'{{$v}}');">
								<a href="javascript:void(0);">11月{{$v}}日</a>
							</li>
							{{/foreach}}
							<li>
								<span id="qg411-clock" class="" time="{{$totime}}"></span>
								<script>
										$('#qg411-clock').YMCountDown("距离下一次开抢时间还剩");
								</script>
							</li>
						</ul>
					</div>
					<div class="tab_con">
						<ul>
							<li style="display: block;">
								<div id="stage01" class="stage stage01"><img src="/images/shop/tuan/qg411/round02.gif" width="12" height="12" />
									<br />
									<span class="current">9:00
										<br />
										抢先查看</span>
								</div>
								<div id="stage02" class="stage stage02"><img src="/images/shop/tuan/qg411/round01.gif" width="12" height="12" />
									<br />
									<span class="end">15:00
										<br />
										抢先查看</span>
								</div>
								<div id="stage03" class="stage stage03"><img src="/images/shop/tuan/qg411/round01.gif" width="12" height="12" />
									<br />
									<span>20:00
										<br />
										抢先查看</span>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="product" id="ajax-product-container">
				
				
			</div>

		</div>
		<!---content end--->
	</div>

	<!---bottom begin---->
	<div class="bottom">
		<div class="rule">
			<p>
				1.活动时间2013年11月1日——2013年11月11日，每天9:00、15:00、20:00开抢，抢购商品数量有限，抢完即止；
				<br />
				&nbsp;&nbsp;抢购时间截止至当日 23:59:59结束，逾期回复原价
				<br />
			</p>
			<p>
				2.抢购成功后，请在1小时内完成在线支付，否则系统将自动取消未付款订单，名额释放给其他顾客
			</p>
			<p>
				3.同一款商品同一用户（相同姓名和手机号）仅限抢购1次，多拍无效
			</p>
			<p>
				4.抢购商品为限时优惠活动，活动商品结束后，未售出的商品将恢复原价销售
			</p>
			<p>
				5.因用户电脑与服务器时差，可能导致抢购出现延时，请抢购开始后即时刷新抢购页面，以页面上出现"立即购买"按钮为准；
			</p>
		</div>
	</div>
	<!---bottom end---->
</div>


<script type="text/javascript">

		$(".stage").click(function() {
			$(this).parent().find("img").attr("src", "/images/shop/tuan/qg411/round01.gif");
			$(this).parent().find("span").removeClass("current");
			$(this).children("img").attr("src", "/images/shop/tuan/qg411/round02.gif");
			$(this).children("span").addClass("current");
		})

		$(".stage01").click(function() {
			$(".product ul").animate({
				left : "0px"
			})
		})
		$(".stage02").click(function() {
			$(".product ul").animate({
				left : "-982px"
			})
		})
		$(".stage03").click(function() {
			$(".product ul").animate({
				left : "-1963px"
			})
		})
		
		$('.tab').find('li').eq({{$cday_index}}).click();
		
		$("#stage0{{$ctime_index}}").click();
		
function ajax_tab(elt,cday){

	$(elt).siblings().removeClass('on');
	$(elt).addClass('on');
	$('#stage01').click();
	
	$.ajax({
		url:'/tuan/ajax-qg411-product/cday/'+cday,
		type:'get',
		dataType:'html',
		async: false,
		success:function(msg){
			$('#ajax-product-container').empty().html(msg);
		}
	});
}

</script>
