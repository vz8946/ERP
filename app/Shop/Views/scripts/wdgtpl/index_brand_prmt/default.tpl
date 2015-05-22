<div class="brandCommend fl mt10">
	<div class="brand_tabmenu">
		<ul class="fr">
			<li>
				<a class="cur" rel="nofollow" href="javascript:void(0);">品牌推荐</a>
			</li>
			<li>
				<a rel="nofollow" href="javascript:void(0);">国内品牌</a>
			</li>
			<li>
				<a rel="nofollow" href="javascript:void(0);">国际品牌</a>
			</li>
		</ul>
	</div>
	<div class="brand_content">
		<div class="brandList fl">
			<a alt="向右" class="slide_right" href="#"></a>
			<a alt="向左" class="slide_left" href="#"></a>
			<div class="slidebrand">
				<ul>
					{{foreach from=$brand1 key=key item=data}}
					<li>
						<p class="img">
							<a target="_blank" title="{{$data.brand_name|default:$data.brand.brand_name}}" href="/b-{{$data.brand.as_name}}/"> <img width="120" height="60" alt="{{$data.brand_name|default:$data.brand.brand_name}}" _src="{{$imgBaseUrl}}/{{$data.img|default:$data.brand.small_logo}}" ></a>
						</p>
					</li>
					{{/foreach}}
				</ul>
			</div>
		</div>
		<div class="brand_adv140 fr">
			<div class="adv">
				<a target="_blank" href="{{$link.0.url}}"><img width="140" height="225" alt="{{$link.0.title}}" _src="{{$imgBaseUrl}}/{{$link.0.img}}" src="{{$imgBaseUrl}}/images/loading.gif"></a>
			</div>
			<div class="dot">
				<a class="cur" href="#"></a>
				<a href="#"></a>
				<a href="#"></a>
			</div>
		</div>
	</div>
	<div style="display:none;" class="brand_content">
		<div class="brandList fl">
			<a class="slide_right" href="#"></a>
			<a class="slide_left" href="#"></a>
			<div class="slidebrand">
				<ul>

					{{foreach from=$brand2 key=key item=data}}
					<li>
						<p class="img">
							<a target="_blank" title="{{$data.brand_name|default:$data.brand.brand_name}}" href="/b-{{$data.brand.as_name}}/"> <img width="120" height="60" alt="{{$data.brand_name|default:$data.brand.brand_name}}" _src="{{$imgBaseUrl}}/{{$data.img|default:$data.brand.small_logo}}" ></a>
						</p>
					</li>
					{{/foreach}}

				</ul>
			</div>
		</div>
		<div class="brand_adv140 fr">
			<div class="adv">
				<a target="_blank" href="{{$link.1.url}}"><img width="140" height="225" alt="{{$link.1.title}}" _src="{{$imgBaseUrl}}/{{$link.1.img}}" src="{{$imgBaseUrl}}/{{$link.1.img}}"></a>
			</div>
			<div class="dot">
				<a class="cur" href="#"></a>
				<a href="#"></a>
				<a href="#"></a>
			</div>
		</div>
	</div>
	<div style="display:none;" class="brand_content">
		<div class="brandList fl">
			<a class="slide_right" href="#"></a>
			<a class="slide_left" href="#"></a>
			<div class="slidebrand">
				<ul>
					{{foreach from=$brand3 key=key item=data}}
					<li>
						<p class="img">
							<a target="_blank" title="{{$data.brand_name|default:$data.brand.brand_name}}" href="/b-{{$data.brand.as_name}}/"> <img width="120" height="60" alt="{{$data.brand_name|default:$data.brand.brand_name}}" _src="{{$imgBaseUrl}}/{{$data.img|default:$data.brand.small_logo}}" ></a>
						</p>
					</li>
					{{/foreach}}
				</ul>
			</div>
		</div>
		<div class="brand_adv140 fr">
			<div class="adv">
				<a target="_blank" href="{{$link.2.url}}"><img width="140" height="225" alt="{{$link.2.title}}" _src="{{$imgBaseUrl}}/{{$link.2.img}}" src="{{$link.2.img}}"></a>
			</div>
			<div class="dot">
				<a class="cur" href="#"></a>
				<a href="#"></a>
				<a href="#"></a>
			</div>
		</div>
	</div>
</div>

