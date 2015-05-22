<div class="floor">
	<div class="col-l fl w2">
		<div class="col-l-1 fl" style="width: 222px;overflow: hidden;">
			<h3 style="background: {{$bg_color}};"><span><img alt="{{$floor_icon.0.title}}" src="{{$imgBaseUrl}}/{{$floor_icon.0.img}}"/></span>{{$floor_name}}</h3>
			<div class="adv"><a href="{{$floor_adv.0.url}}"><img alt="{{$floor_adv.0.title}}" src="{{$imgBaseUrl}}/{{$floor_adv.0.img}}" /></a></div>
			<div class="keys">
				{{foreach from=$floor_keys item=v key=k}}
				<a style="background: {{$bg_color}};" href="{{$v.url}}">{{$v.title}}</a>
				{{/foreach}}
			</div>
		</div>
		<div class="col-l-2 fl tab floor-tab" style="width: 668px;padding-left:10px;">
			<ul class="tab-h">
				{{if $cat_name1}}<li><a href="#">{{$cat_name1}}</a></li>{{/if}}
				{{if $cat_name2}}<li><a href="#">{{$cat_name2}}</a></li>{{/if}}
				{{if $cat_name3}}<li><a href="#">{{$cat_name3}}</a></li>{{/if}}
				{{if $cat_name4}}<li><a href="#">{{$cat_name4}}</a></li>{{/if}}
			</ul>
			<div class="tab-c" style="border-color: {{$bg_color}};">
				<div>
					<ul class="floor-goods">
						{{foreach from=$goods1 item=v key=k}}
						<li>
							<div class="pi">
								{{if $v.url == '#'}}
									{{html alt=$v.goods_name|default:$v.goods.goods_name type="img" w="175" h="175" height="175" src=$v.img lazy='Y'}}
								{{else}}
								<a href="{{if $v.url}}{{$v.url}}{{else}}{{$v.goods_url}}{{/if}}" target="_blank">
									{{html alt=$v.goods_name|default:$v.goods.goods_name type="img" w="175" h="175" height="175" src=$v.img lazy='Y'}}
								</a>
								{{/if}}
							</div>
							<div class="pa fs1 fb c1" title="{{$v.goods_alt|default:$v.goods.goods_alt}}">
								{{$v.goods_alt|default:$v.goods.goods_alt|cn_truncate:26:''}}
							</div>
							<div class="pt">
								{{if $v.url == '#'}}
									{{$v.goods_name|default:$v.goods.goods_name}}
								{{else}}
									<a class="c3" href="{{$v.url|default:$v.goods_url}}" target="_blank">{{$v.goods_name|default:$v.goods.goods_name}}</a>
								{{/if}}
							</div>
							<div class="pp">
								<span class="pmp fl">￥{{$v.market_price|default:$v.goods.market_price}}</span>
								<span class="prp fr c2 fs3">￥{{$v.price|default:$v.goods.price}}</span>
							</div>										
						</li>
						{{/foreach}}
					</ul>								
				</div>
				<div>
					<ul class="floor-goods">
						{{foreach from=$goods2 item=v key=k}}
						<li>
							<div class="pi">
								{{if $v.url == '#'}}
									{{html alt=$v.goods_name|default:$v.goods.goods_name type="img" w="175" h="175" height="175" src=$v.img lazy='Y'}}
								{{else}}
								<a href="{{if $v.url}}{{$v.url}}{{else}}{{$v.goods_url}}{{/if}}" target="_blank">
									{{html alt=$v.goods_name|default:$v.goods.goods_name type="img" w="175" h="175" height="175" src=$v.img lazy='Y'}}
								</a>
								{{/if}}
							</div>
							<div class="pa fs1 fb c1">
								{{$v.goods_alt|default:$v.goods.goods_alt}}
							</div>
							<div class="pt">
								{{if $v.url == '#'}}
									{{$v.goods_name|default:$v.goods.goods_name}}
								{{else}}
									<a class="c3" href="{{$v.url|default:$v.goods_url}}" target="_blank">{{$v.goods_name|default:$v.goods.goods_name}}</a>
								{{/if}}
							</div>
							<div class="pp">
								<span class="pmp fl">￥{{$v.market_price|default:$v.goods.market_price}}</span>
								<span class="prp fr c2 fs3">￥{{$v.price|default:$v.goods.price}}</span>
							</div>										
						</li>
						{{/foreach}}
					</ul>								
				</div>
				<div>
					<ul class="floor-goods">
						{{foreach from=$goods3 item=v key=k}}
						<li>
							<div class="pi">
								{{if $v.url == '#'}}
									{{html alt=$v.goods_name|default:$v.goods.goods_name type="img" w="175" h="175" height="175" src=$v.img lazy='Y'}}
								{{else}}
								<a href="{{if $v.url}}{{$v.url}}{{else}}{{$v.goods_url}}{{/if}}" target="_blank">
									{{html alt=$v.goods_name|default:$v.goods.goods_name type="img" w="175" h="175" height="175" src=$v.img lazy='Y'}}
								</a>
								{{/if}}
							</div>
							<div class="pa fs1 fb c1">
								{{$v.goods_alt|default:$v.goods.goods_alt}}
							</div>
							<div class="pt">
								{{if $v.url == '#'}}
									{{$v.goods_name|default:$v.goods.goods_name}}
								{{else}}
									<a class="c3" href="{{$v.url|default:$v.goods_url}}" target="_blank">{{$v.goods_name | default:$v.goods.goods_name}}</a>
								{{/if}}
							</div>
							<div class="pp">
								<span class="pmp fl">￥{{$v.market_price|default:$v.goods.market_price}}</span>
								<span class="prp fr c2 fs3">￥{{$v.price|default:$v.goods.price}}</span>
							</div>										
						</li>
						{{/foreach}}
					</ul>								
				</div>
				<div>
					<ul class="floor-goods">
						{{foreach from=$goods4 item=v key=k}}
						<li>
							<div class="pi">
								{{if $v.url == '#'}}
									{{html alt=$v.goods_name|default:$v.goods.goods_name type="img" w="175" h="175" height="175" src=$v.img lazy='Y'}}
								{{else}}
								<a href="{{if $v.url}}{{$v.url}}{{else}}{{$v.goods_url}}{{/if}}" target="_blank">
									{{html alt=$v.goods_name|default:$v.goods.goods_name type="img" w="175" h="175" height="175" src=$v.img lazy='Y'}}
								</a>
								{{/if}}
							</div>
							<div class="pa fs1 fb c1">
								{{$v.goods_alt|default:$v.goods.goods_alt}}
							</div>
							<div class="pt">
								{{if $v.url == '#'}}
									{{$v.goods_name|default:$v.goods.goods_name}}
								{{else}}
									<a class="c3" href="{{$v.url|default:$v.goods_url}}" target="_blank">{{$v.goods_name | default:$v.goods.goods_name}}</a>
								{{/if}}
							</div>
							<div class="pp">
								<span class="pmp fl">￥{{$v.market_price|default:$v.goods.market_price}}</span>
								<span class="prp fr c2 fs3">￥{{$v.price|default:$v.goods.price}}</span>
							</div>										
						</li>
						{{/foreach}}
					</ul>								
				</div>
			</div>
		</div>
	</div>
	<div class="col-r fr w3">
		<div class="goods-hot">
			<h3 style="background: {{$bg_color}};"><i></i>本周热销排行</h3>
			<ul>
				{{foreach from=$goods5 item=v key=k}}
				<li>
					{{if $k == 0}}
					<div class="p-detail">
					{{else}}
					<div class="p-detail" style="display: none;">
					{{/if}}
						<div class="pi">
							{{if $v.url == '#'}}
								{{html alt=$v.goods_name|default:$v.goods.goods_name type="img" w="175" h="175" height="175" src=$v.img lazy='Y'}}
							{{else}}
							<a href="{{if $v.url}}{{$v.url}}{{else}}{{$v.goods_url}}{{/if}}" target="_blank">
								{{html alt=$v.goods_name|default:$v.goods.goods_name type="img" w="175" h="175" height="175" src=$v.img lazy='Y'}}
							</a>
							{{/if}}
						</div>
						<div class="pt">
							{{if $v.url == '#'}}
								{{$v.goods_name|default:$v.goods.goods_name}}
							{{else}}
								<a class="c3" href="{{$v.url|default:$v.goods_url}}" target="_blank">{{$v.goods_name | default:$v.goods.goods_name}}</a>
							{{/if}}
						</div>
						<div class="pp">
							<span class="prp c2 fs3">￥{{$v.price|default:$v.goods.price}}</span>
						</div>										
					</div>
					<div class="p-title" {{if $k == 0}}style="display: none;"{{/if}}>{{$v.goods_name|default:$v.goods.goods_name|cn_truncate:50}}</div>
				</li>
				{{/foreach}}
			</ul>
			
			<div style="height: 180px;overflow: hidden;">
				<a href="{{$floor_adv.1.url}}"><img alt="{{$floor_adv.1.title}}"  _src="{{$imgBaseUrl}}/{{$floor_adv.1.img}}"/></a>
			</div>
			
		</div>
	</div>
</div>
<style>
#wgt-{{$__sys_wtpl_name}} .tab-h-c a{
	background: {{$bg_color}};
}	
</style>
