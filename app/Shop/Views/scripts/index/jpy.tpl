<div class="wbox_1200">
	<div class="wrap">
		<!-- 广告-->
		<div class="adv1200">

			<div id="zqj_box" >
				<div style="MARGIN: 0px auto; WIDTH: 980px" id="zqj_box">
					<div class="left_bg1"></div>
					<div class="right_bg1"></div>
					<div >
						<img src="{{$imgBaseUrl}}/images/new_banner_ad.jpg " width="991" height="260" />
					</div>
				</div>
			</div>
			<!-- end 广告-->
			<div class="wrap"  style="width: 990px;">

				<div class="giantlist clearfix" id="specialProductDiv">
					<h1 class="t1"></h1>

					<div class="product clearfix">
						<ul>
							{{foreach from=$jpy item=vo key=k }}
							<li>
								<div class="pic">
									<div class="wh200 verticalPic">
										<a href="/b-{{$vo.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank"><img src="{{$imgBaseUrl}}/{{$vo.goods_img}}" alt="{{$vo.goods_name}}" width="200" height="200"></a>
									</div>
								</div>
								<div class="name">
									<a href="/b-{{$vo.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank">{{$vo.goods_name}}</a>
								</div>
								<div class="buybox">
									<div class="price">
										特促价：¥<span>{{$vo.price}}</span>
									</div>
									<div class="buynow">
										<a href="/b-{{$vo.as_name}}/detail{{$vo.goods_id}}.html" title="立即购买" target="_blank">立即购买</a>
									</div>
								</div>
								<div class="disc">
									<span>市场价
										<br>
										<del><em>¥</em>{{$vo.market_price}}</del></span>

									<span>节省
										<br>
										<em>¥</em>{{$vo.disprice}}</span>
								</div>
							</li>

							{{/foreach}}

						</ul>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- foot start-->
	<!-- foot end-->
	<script src="{{$imgBaseUrl}}/Public/js/global.js" type="text/jscript"></script>
	<script src="{{$imgBaseUrl}}/Public/js/otherPd.js" type="text/javascript"></script>
</div>