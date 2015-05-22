<link type="text/css" rel="stylesheet" href="/Public/css/sale.css">
<div class="position wbox">
            <div class="share">
                <span>分享好友：</span>
                <a href="javascript:openSina();" rel="nofollow"><img src="{{$imgBaseUrl}}/Public/img/detail_icons01.gif"></a>
                <a href="javascript:openQZone();" rel="nofollow"><img src="{{$imgBaseUrl}}/Public/img/detail_icons02.gif"></a>
                <a href="javascript:openWangyi();" rel="nofollow"><img src="{{$imgBaseUrl}}/Public/img/detail_icons04.gif"></a>
                <a href="javascript:openRenrRen();" rel="nofollow"><img src="{{$imgBaseUrl}}/Public/img/detail_icons03.gif"></a>
                <a href="javascript:openQQ();" rel="nofollow"><img src="{{$imgBaseUrl}}/Public/img/detail_icons05.gif"></a>
                <a href="javascript:openKaixin();" rel="nofollow"><img src="{{$imgBaseUrl}}/Public/img/detail_icons06.gif"></a>
            </div>
            <b><a href="/group-goods/">促销中心</a></b><span> &gt; 最值得信赖的保健商城</span>
 </div>

 <div class="wbox">
    <div class="rotave">
        <ul style="position: relative; width: 758px; height: 302px;" id="adv_113_100">
        	  {{foreach from=$advlist item=vo key=k }}

				{{if $k lt 1 }}
					<li style="display: list-item;"><a href="{{$vo.url}}" target="_blank"><img src="{{$vo.imgUrl}}" alt="{{$vo.prom1}}"></a></li>
				{{else}}
					<li style="display: none;"><a href="{{$vo.url}}" target="_blank"><img src="{{$vo.imgUrl}}" alt="{{$vo.prom1}}"></a></li>
				{{/if}}
   			 {{/foreach}}
          </ul>
        <div class="rcontrol">
            <ul id="adv_113_100_pager">
             {{foreach from=$advlist item=vo key=k }}
             	{{if $k lt 1 }}
					<li class="activeSlide">{{$k+1}}</li>
				{{else}}
					 <li class="">{{$k+1}}</li>
				{{/if}}
             {{/foreach}}
           </ul>
        </div>
    </div>
    <div class="radv218"><a href="{{$advRight.url}}" target="_blank" title="{{$advRight.name}}"><img border="0" width="218" height="298" src="{{$advRight.imgUrl}}" alt="{{$advRight.name}}"></a></div>
</div>

<div class="keyword wbox">
<!-- 
    <div class="sorts borb">
            <b>分类：</b>
            <a href="/prom-0.html">全部</a>
	            {{foreach from=$catelist item=vo}}
	           		 <a href="/prom-{{$vo.code}}.html">{{$vo.name}}</a>
	           	{{/foreach}}
            </div>
    </div>
-->
   <div class="wbox list">
            <div class="prolist">
                <ul>

               {{foreach from=$baokuanlist item=vo}}
                    <li class="">
                                <div class="nothing">
                                <div class="info">
                                    <h3></h3>
                                    <p class="desc"><a target="_blank" title="{{$vo.goods_name}}" href="/b-{{$vo.as_name}}/detail{{$vo.goodsid}}.html">{{$vo.goods_name}}</a></p>

                                    <div class="attr">
                                        <p class="a1">市场价：<del>¥ {{$vo.market_price}}</del>&nbsp;&nbsp;&nbsp;</p>
                                        <div class="price s1">
                                            <div class="p1">¥ <span>{{$vo.price}}</span></div>
                                            <a class="p2" target="_blank" href="/b-{{$vo.as_name}}/detail{{$vo.goodsid}}.html"></a>
                                            <i class="p3"></i>
                                        </div>

                						<p class="a2">
                                          <span class="s1">0人评价</span>
                                          <span class="s2">好评率--</span>
                                        </p>
                					</div>
                                    </div>
                                <div class="img">
                                    <div class="verticalPic wh180">
                                        <a href="/b-{{$vo.as_name}}/detail{{$vo.goodsid}}.html" target="_blank"><img alt="{{$vo.goods_name}}" src="{{$vo.imgurl}}" width="180" height="180"><i class="icon i1"></i></a>
                                    </div>
                                    <div class="layer_ico">
                                        </div>
                                </div>
                                    <div class="shadow"></div>
                                    <div class="float-divider"></div>
                                </div>
                            </li>
                       {{/foreach}}

                        </ul>
            </div>
            <div class="clear"></div>
            <div class="page">

            </div>
        </div>

<!-- foot start-->
<!-- foot end-->
<script src="{{$imgBaseUrl}}/Public/js/global.js" type="text/jscript"></script>
<script src="{{$imgBaseUrl}}/Public/js/otherPd.js" type="text/javascript"></script>


