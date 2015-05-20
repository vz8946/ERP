{{include file="header.tpl"}}
<link type="text/css" rel="stylesheet" href="/Public/css/groupon.css">

<div class="position wbox"><span class="none">当前位置：<a href="/">首页</a> &gt; </span><span class="p_blue">团购</span></div>

<div class="tuantopadv"><a href="#"></a></div>
<div class="groupon_head clearfix">
        <div class="limitbuy_nav">
            <ul>
                <li><a href="/tuan/" class="cur">今日团购</a></li>
                <li><a href="/tuan/next.html">下期预告</a></li>
                <li><a href="/tuan/prev.html">往期团购</a></li>
                <li><a href="/tuan/help.html">团购帮助</a></li>
            </ul>
        </div>
        </div>

        <div class="groupon">

    <!--团购分类-->
    <!--end 团购分类-->

	<!--  第一个产品 -->
	{{foreach from=$datas item=vo key=k }}
		{{if $k eq 0 }}
			 <div class="tuan_attr clearfix">
        <div class="databox">
            <h1>
                <p><a title="{{$vo.title}}" id="titleHref_1" href="/tuan/detail{{$vo.tuan_id}}.html">{{$vo.title}}</a></p>
            </h1>
            <div id="bg_1" class="tuan_price">
                <span class="price">¥<b>{{$vo.price}}</b></span>
                <span class="btn"><a class="buynow" href="/tuan/detail{{$vo.tuan_id}}.html" id="a_1"></a></span>
            </div>
            <div class="status clearfix">
                <div class="disc clearfix">
                    <ul>
                        <li>市场价<s>¥{{$vo.market_price}}</s></li> <li>折扣<span>{{$vo.discount}}</span></li>
                        <li>节省<span>¥{{$vo.market_price-$vo.price}}</span></li>
                    </ul>
                </div>
                <div  class="clock" time="{{$vo.end_time}}" ></div>
                <div class="peo">
                    <em></em>
                    <span><strong>{{$vo.alt_count}}</strong>人已购买</span>
                    <span class="secc">团购活动进行中，立即去参与</span>
                </div>
            </div>
        </div>
        <div class="share">
            <h2 class="txt"></h2>
            <div style="text-align: center" class="bshare-custom"><div class="bsPromo bsPromo2"></div>
                <ul>
                    <li> <a rel="nofollow" href="javascript:openSina();"><img src="{{$imgBaseUrl}}/Public/img/detail_icons01.gif"></a> </li>
                    <li><a rel="nofollow" href="javascript:openQZone();"><img src="{{$imgBaseUrl}}/Public/img/detail_icons02.gif"></a></li>
                    <li><a rel="nofollow" href="javascript:openWangyi();"><img src="{{$imgBaseUrl}}/Public/img/detail_icons04.gif"></a></li>
                    <li><a rel="nofollow" href="javascript:openRenrRen();"><img src="{{$imgBaseUrl}}/Public/img/detail_icons03.gif"></a></li>
                    <li><a rel="nofollow" href="javascript:openQQ();"><img src="{{$imgBaseUrl}}/Public/img/detail_icons05.gif"></a> </li>
                    <li> <a rel="nofollow" href="javascript:openKaixin();"><img src="{{$imgBaseUrl}}/Public/img/detail_icons06.gif"></a></li>
                </ul>
            </div>

        </div>
        <div class="showbox">
                    <div class="pic"><a id="picHref_1" href="/tuan/detail{{$vo.tuan_id}}.html"><img alt="{{$vo.title}}" src="{{$imgBaseUrl}}/{{$vo.main_img}}" width="400" height="400"></a></div>
        </div>
    </div>
		{{/if}}
	{{/foreach}}

     <div class="tuan_list clearfix">
     <ul>

	{{foreach from=$datas item=vo key=k }}
		{{if $k gt 0 }}
     <li>
        <div class="title">
            <p><a title="{{$vo.title}}" id="titleHref_2" href="/tuan/detail{{$vo.tuan_id}}.html">{{$vo.title}}</a></p>
        </div>
        <div class="picbox">
            <em><b>{{$vo.discount}}</b>折</em>
            <p class="pic"><a id="picHref_2" href="/tuan/detail{{$vo.tuan_id}}.html"><img alt="{{$vo.title}}" src="{{$imgBaseUrl}}/{{$vo.main_img}}" width="300" height="300"></a></p>
        </div>
        <div class="buybox">
            <div class="pricebox clearfix" id="tuan_state_2">
                <span class="price">¥<b>{{$vo.price}}</b><em></em></span>
                <span class="btn"><a href="/tuan/detail{{$vo.tuan_id}}.html" id="a_2"></a></span>
            </div>
            <div class="discount">
                <p>市场价：<s>¥{{$vo.market_price}}</s></p>
                <p>折扣：<span>{{$vo.discount}}折</p>
                <p>节省：<b>¥{{$vo.market_price-$vo.price}}</b></p>
            </div>
        </div>
        <div class="time">
            <span  class="clock"  time="{{$vo.end_time}}" >

            </span>
            <span class="amount"><b>{{$vo.alt_count}}</b>人已购买</span>
        </div>
        <div class="bottomShadow"></div>
    </li>
		{{/if}}
	{{/foreach}}
 </ul>
</div>
</div>

<!-- foot start-->
{{include file="footer.tpl"}}
<!-- foot end-->

<script type="text/javascript">
$(function(){
	$('.clock').each(function(){
		var $this = $(this)
		$this.YMCountDown("还剩");
	});
});
</script>
