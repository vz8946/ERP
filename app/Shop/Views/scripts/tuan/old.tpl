{{include file="header.tpl"}}
<link href="/styles/shop/tuan.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	$(function() {
		$(".left .old ul li:nth-child(2n)").css("border-right", "0 none")
		var linum = $(".left .old ul li").length
		//alert(linum)
		if (linum % 2 == "1") {
			$(".left .old ul li:last").css("border-bottom", "0 none")
		} else {
			$(".left .old ul li:last").css("border-bottom", "0 none")
			$(".left .old ul li:last").prev().css("border-bottom", "0 none")
		}
	})
</script>
<div class="main mar">
	<div class="left">

		<div class="old">
			<h2>往期团购</h2>
			<ul class="clear">
				{{foreach from=$datas item=tuan name=tuan}}
				<li>
					<div class="date">
						{{$tuan.start_time}} 至 {{$tuan.end_time}}
					</div>
					<h3>{{$tuan.description}}</h3>
					<a href="/tuan/view/id/{{$tuan.tuan_id}}" target="_blank"  class="fl"><img src="{{$imgBaseUrl}}/{{$tuan.main_img|replace:'.':'_180_180.'}}"/></a>
					<div class="r">
						<p>
							<strong>{{math equation="x + y" x=$tuan.alt_count y=$tuan.count}}</strong>人购买
						</p>
						<strong>市场价:</strong>￥{{$tuan.market_price}}
						<br />
						<strong>折扣:</strong>{{$tuan.discount}}折
						<br />
						<strong>现价:</strong>￥{{$tuan.price}}
						<br />
						<strong>节省:</strong>￥{{math equation="x - y" x=$tuan.market_price y=$tuan.price}}
					</div>
					<div class="cleardiv"></div>
				</li>
				{{/foreach}}

			</ul>
			<div class="fy">
				{{$pageNav}}
			</div>
		</div><!--old end-->

	</div><!--left end-->

	<div class="rig">
		<div class="share grayborder">
			<h2>分享到：</h2>
			<a href="javascript:window.open('http://v.t.sina.com.cn/share/share.php?title='+encodeURIComponent(document.title)+'&url='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" class="t-sina" title="分享到新浪微博"> <img src="{{$imgBaseUrl}}/images/shop/share_sina.gif" alt="分享到新浪微博"/> </a>
			<a href="javascript:(function(){window.open('http://v.t.qq.com/share/share.php?title='+encodeURIComponent(document.title)+'&url='+encodeURIComponent(window.location.href)+'&source=bookmark','_blank','width=610,height=350');})()" class="t-qq" title="分享到QQ微博"> <img src="{{$imgBaseUrl}}/images/shop/share_qq.gif" alt="分享到QQ微博" /> </a>
			<a href="javascript:window.open('http://www.kaixin001.com/repaste/share.php?rtitle='+encodeURIComponent(document.title)+'&rurl='+encodeURIComponent(window.location.href)+'&rcontent=看中一个好东东，很好看，是垦丰电商的'+encodeURIComponent(document.title)+' 亲爱的您也看下吧'+encodeURIComponent(location.href),'_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" class="kaixin" title="分享到开心网"> <img src="{{$imgBaseUrl}}/images/shop/share_kaixin.gif" alt="分享到开心网" /> </a>
			<a href="javascript:window.open('http://cang.baidu.com/do/add?it='+encodeURIComponent(document.title)+'&iu='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到百度收藏"> <img src="{{$imgBaseUrl}}/images/shop/share_baidu.gif" alt="分享到百度收藏"/> </a>
			<a href="javascript:window.open('http://bai.sohu.com/share/blank/addbutton.do?title='+encodeURIComponent(document.title)+'&link='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到搜狐白社会"> <img src="{{$imgBaseUrl}}/images/shop/share_bai.gif" alt="分享到搜狐白社会"/> </a>
			<a href="javascript:window.open('http://www.douban.com/recommend?title='+encodeURIComponent(document.title)+'&link='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到豆瓣"> <img src="{{$imgBaseUrl}}/images/shop/share_dou.gif" alt="分享到豆瓣"/> </a>
		</div>
		<div class="question grayborder">
			<h2>团购问答Q/A专区：</h2>
			<dl>
				<dt>
					Q:我购买了团购商品，同时又选购了其他商品，我该如何付款？
				</dt>
				<dd>
					A:团购商品可与普通商品一起购买，直接添加至购物车即可，一张订单，一次送达。
				</dd>

				<dt>
					Q:团购商品可以使用优惠券吗？
				</dt>
				<dd>
					A:只要符合优惠券使用条件，即可享受更多优惠。
				</dd>

				<dt>
					Q:团购商品包邮吗？
				</dt>
				<dd>
					A:不是每一款团购商品都包邮，具体请参照当期团购商品详细说明。
				</dd>

				<dt>
					Q:购买团购商品有积分吗？
				</dt>
				<dd>
					A:购买垦丰电商团购商品同样可以获得积分，详细请参照积分说明。
				</dd>

				<dt>
					Q:团购商品一般几天发货？
				</dt>
				<dd>
					A:如无特殊情况，团购商品的发货流程与普通商品一样，订单发货后预计1-6天送达。
				</dd>
			</dl>
		</div>
	</div><!--rig end-->
	<div class="cleardiv"></div>
</div>
{{include file="footer.tpl"}}