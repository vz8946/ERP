<div class="wrap" style="width: 990px;">
	<div class="adv1200"><img border="0" height="200" src="{{$imgBaseUrl}}/images/in_ad.jpg" alt="首banner">
	</div>
	<div class="bakkuan_menu">
		<ul>
			<li>
				<a  rel="nofollow" {{if empty($pidcode)}} class="cur" {{/if}} href="javascript:void(0);" attrid="0">全部</a>
			</li>

			{{foreach from=$catelist item=vo}}
			<li>
				<a  rel="nofollow" href="javascript:void(0);" {{if $vo.code eq $pidcode}} class="cur" {{/if}}   attrid="{{$vo.code}}">{{$vo.name}}</a>
			</li>
			{{/foreach}}
		</ul>
	</div>
	<div class="baokuan_list">
		<ul class="active">
			{{foreach from=$baokuanlist item=vo}}
			<li columnid="c_150023">
				<div class="product">
					<div class="pic">
						<a target="_blank" href="/b-{{$vo.as_name}}/detail{{$vo.goodsid}}.html" title="{{$vo.goods_name}}"><img width="720" height="240" alt="{{$vo.goods_name}}" src="{{$vo.imgurl}}"></a>
					</div>
				</div>
				<div class="intro">
					<h2></h2>
					<h3><a target="_blank" title="{{$vo.goods_name}}" href="/b-{{$vo.as_name}}/detail{{$vo.goodsid}}.html" class="tlt">{{$vo.goods_name}} </a>
					</h3>
                    <p class="price"><span>市场价：</span><em style="text-decoration:line-through;">{{$vo.market_price}} </em><span style="margin-left:15px;">垦丰价：</span><span style="color:red;display: inline;font-weight: bold;">{{$vo.price}} </span></p>
					<p align="center">
						<a target="_blank" title="{{$vo.goods_name}}" href="/b-{{$vo.as_name}}/detail{{$vo.goodsid}}.html" class="tlt"> <img border="0"  src="{{$imgBaseUrl}}/images/submit.jpg" width="110px" alt="立即购买"></a>
					</p>
					<div class="reason">
						<p class="reason_title">
							<em class="e1"></em><span>推荐理由</span><em class="e2"></em>
						</p>
						<div class="reason_txt">
							<p class="txt" title="{{$vo.act_notes }}">
								{{$vo.act_notes }}
							</p>
							<div class="ri"></div>
						</div>
					</div>
				</div>
			</li>
			{{/foreach}}
		</ul>
	</div>
</div>
<!-- foot start-->
<!-- foot end-->
<script src="{{$imgBaseUrl}}/Public/js/otherPd.js" type="text/javascript"></script>