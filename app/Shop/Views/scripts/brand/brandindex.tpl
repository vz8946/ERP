<link href="/Public/css/brand-{{$brand.brand_style}}.css" type="text/css" rel="stylesheet">
<link rel="Shortcut Icon" href="/Public/img/home.ico" type="image/x-icon">

<!-- Brand content -->
<div id="content">
  <div class="left-content">
    <!--  品牌分类信息 -->
	<div class="category">
		<dl class="category-head leftBorder"></dl>
		<dl class="category-info leftBorder">
			<ul>
				{{foreach from=$cateList item=vo}}
					<li class="aColor fontColor">
						&gt;
						<a href="/b-{{$brand.as_name}}/list{{$vo.cat_id}}/" title="{{$vo.cat_name}}">{{$vo.cat_name}}</a>
					</li>
				{{/foreach}}
			</ul>
		</dl>
	</div>
    <div class="brand-hot-product">
             	<div class="product-box leftBorder">
                    <dl class="product-box-name navBgcolor fontWhiteColor">{{$brand.brand_name}}官网专卖店销售排行</dl>
                </div>
                <div class="product-list leftBorder">
                	<ul>
                		{{foreach from=$goodsHotList key=key item=vo}}
                		{{if $key eq 0}}
						<li>
							<p class="product-seq-box">{{$key+1}}</p>
							<p class="product-img"><a href="/b-{{$brand.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank"><img  alt="{{$vo.goods_name}}" src="{{$imgBaseUrl}}/{{$vo.goods_img|replace:'.':'_60_60.'}}" border="0" height="60px" width="60px"></a></p>
							<p class="product-name">
								<a href="/b-{{$brand.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank">{{$vo.goods_name}}</a>
							</p>
							<p class="product-price">￥{{$vo.price}} 元</p>
						</li>
						{{else}}
						<li>
							{{if $key lt 3}}
								<p class="product-seq-box">{{$key+1}}</p>
							{{else}}
								<p class="product-seq-box-unshow">{{$key+1}}</p>
							{{/if}}
							<p class="display"><a href="/b-{{$brand.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank"><img alt="{{$vo.goods_name}}" src="{{$imgBaseUrl}}/{{$vo.goods_img|replace:'.':'_60_60.'}}" border="0" height="60px" width="60px"></a></p>
							<p class="product-name-unshow"><a href="/b-{{$brand.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank">{{$vo.goods_name}}</a></p>
							<p class="display">￥{{$vo.price}} 元</p>
						</li>
						{{/if}}
                		{{/foreach}}
                	</ul>
                </div>
  </div>

             <div class="brand-pinglun-product">
             	<div class="product-box leftBorder">
                    <dl class="product-box-name navBgcolor fontWhiteColor">{{$brand.brand_name}}官网专卖店评论排行</dl>
                </div>
                <div class="product-list leftBorder">
                	<ul>
                		{{foreach from=$goodsCommandList key=key item=vo}}
                		{{if $key eq 0}}
						<li>
							<p class="product-seq-box">{{$key+1}}</p>
							<p class="product-img"><a href="/b-{{$brand.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank"><img  alt="{{$vo.goods_name}}" src="{{$imgBaseUrl}}/{{$vo.goods_img|replace:'.':'_60_60.'}}" border="0" height="60px" width="60px"></a></p>
							<p class="product-name"><a href="/b-{{$brand.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank">{{$vo.goods_name}}</a></p>
							<p class="product-price">￥{{$vo.price}} 元</p>
						</li>
						{{else}}
						<li>
							{{if $key lt 3}}
								<p class="product-seq-box">{{$key+1}}</p>
							{{else}}
								<p class="product-seq-box-unshow">{{$key+1}}</p>
							{{/if}}
							<p class="display"><a href="/b-{{$brand.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank"><img alt="{{$vo.goods_name}}" src="{{$imgBaseUrl}}/{{$vo.goods_img|replace:'.':'_60_60.'}}" border="0" height="60px" width="60px"></a></p>
							<p class="product-name-unshow"><a href="/b-{{$brand.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank">{{$vo.goods_name}}</a></p>
							<p class="display">￥{{$vo.price}} 元</p>
						</li>
						{{/if}}
                		{{/foreach}}
                	</ul>
                </div>
            </div> <!--pinglun-product-->
  </div> <!-- left-content -->
  <div class="right-content">
    <div class="ad-news">
      <div class="ad-pic">
        <div class="ad-pic-img">
        {{foreach from=$advList key=key item=adv}}
        	{{if $key eq 0}}
        		<dl class="ad-show">
        	{{else}}
        		<dl class="ad-display">
        	{{/if}}
            	<a href="{{$adv.url}}" title="{{$adv.name}}" target="_blank"><img width="575px" height="280px" src="{{$imgBaseUrl}}/{{$adv.imgUrl}}" title="{{$adv.name}}" /></a>
          	</dl>
        {{/foreach}}
        </div>
        <div class="ad-btn">
       {{foreach from=$advList key=key item=adv}}
        	{{if $key eq 0}}
        		<dl class="ad-btn-show">
        	{{else}}
        		<dl class="ad-btn-display">
        	{{/if}}
            	{{$key+1}}
          	</dl>
        {{/foreach}}
        </div>
      </div><!-- ad-pic -->

      <div class="top-news">
      		<div class="top-news-title news-title-bg">
            	<dl class="news-title-ico"></dl>
                <dl class="news-title-name fontColor">{{$brand.brand_name}}权威资讯</dl>
            </div>
            <!--  品牌顶部资讯信息 -->
			<div class="top-news-info">
			{{if $news}}
				<ul>
				{{foreach from=$news key=key item=vo}}
					{{if $key<9}}
					<li><span class="news-ico"></span><a href="{{$newsBaseUrl}}/{{$vo.asName}}/news-{{$vo.id}}.html" title="{{$vo.title}}" target="_blank">{{$vo.title}}</a></li>
					{{/if}}
				{{/foreach}}
				</ul>
			{{/if}}
			</div>
      </div>
    </div><!-- ad-news -->

    <div class="recommend-goods">
    	<div class="recommend-title">
        	<dl class="recommend-img"></dl>
            <dl class="recommend-menu">
            	<ul class="aColorHuiSe">
            		{{foreach from=$centerCateList key=key item=vo}}
            		 {{if $key neq 0}}
            		 <li>|</li>
            		 {{/if}}
            		 	<li><a href="/b-{{$brand.as_name}}/list{{$vo.cat_id}}/" title="{{$vo.cat_name}}">{{$vo.cat_name}}</a></li>
            		 {{/foreach}}
            		 <li> &nbsp; </li>
                </ul>
            </dl>
        </div>
        <!--顶部推荐产品信息-->
        <div class="recommend-info-box">
        	{{foreach from=$goodsRecommendsList key=key item=vo}}
        	{{if $key<4}}
        	<ul>
        		<li class="goods-img">
        			<a href="/b-{{$brand.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}"><img src="{{$imgBaseUrl}}/{{$vo.goods_img|replace:'.':'_180_180.'}}" alt="{{$vo.goods_name}}" height="143px" width="155px"></a>
        		</li>
        		<li class="goods-title">
        			<a href="/b-{{$brand.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}">{{$vo.goods_name}}</a>
        		</li>
				<li class="shichangjia">市场价：{{$vo.market_price}} 元</li>
				<li class="xianjia">{{$vo.price}} 元</li>
			</ul>
			{{/if}}
			{{/foreach}}
		</div>
	</div><!--recommend-goods-->
    <div class="brand-news">
    	<div class="brand-news-title news-title-bg">
        	<dl class="news-title-ico"></dl>
			<dl class="news-title-name fontColor">{{$brand.brand_name}}官网专卖店相关资讯</dl>
        </div>
         <!--  品牌中部资讯信息 -->
		<div class="brand-news-info">
			<ul>
			{{foreach from=$news key=key item=vo}}
				{{if ($key >= 9) and ($key < 18)}}
					<li {{if $key lt 12}}style="border-top:none;"{{/if}}><dl class="news-info"><span class="news-ico"></span><a href="{{$newsBaseUrl}}/{{$vo.asName}}/news-{{$vo.id}}.html" title="{{$vo.title}}" target="_blank">{{$vo.title}}</a></dl></li>
				{{/if}}
			{{/foreach}}
			</ul>
		</div>
    </div><!--brand-news-->
	<!-- copy -->
    <div class="recommend-goods">
    	<div class="recommend-title">
        	<dl class="recommend-img"></dl>
            <dl class="recommend-menu">
            	<ul class="aColorHuiSe">
                	{{foreach from=$bottomCateList key=key item=vo}}
            		 {{if $key neq 0}}
            		 <li>|</li>
            		 {{/if}}
            		 	<li><a href="/b-{{$brand.as_name}}/list{{$vo.cat_id}}/" title="{{$vo.cat_name}}">{{$vo.cat_name}}</a></li>
            		 {{/foreach}}
            		 <li> &nbsp; </li>
                </ul>
            </dl>
        </div>
        <!--底部推荐产品-->
        <div class="recommend-info-box">
        	{{foreach from=$goodsRecommendsList key=key item=vo}}
        	{{if $key>=4}}
        	<ul>
        		<li class="goods-img">
        			<a href="/b-{{$brand.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}"><img src="{{$imgBaseUrl}}/{{$vo.goods_img|replace:'.':'_180_180.'}}" alt="{{$vo.goods_name}}" height="143px" width="155px"></a>
        		</li>
        		<li class="goods-title">
        			<a href="/b-{{$brand.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}">{{$vo.goods_name}}</a>
        		</li>
				<li class="shichangjia">市场价：{{$vo.market_price}} 元</li>
				<li class="xianjia">{{$vo.price}} 元</li>
			</ul>
			{{/if}}
			{{/foreach}}
		</div>
	</div><!--recommend-goods-->

   <div class="brand-news">
    	<div class="brand-news-title news-title-bg">
        	<dl class="news-title-ico"></dl>
			<dl class="news-title-name fontColor">{{$brand.brand_name}}官网专卖店相关资讯</dl>
        </div>
        <!--  品牌底部资讯信息 -->
		<div class="brand-news-info">
			<ul>
			{{foreach from=$news key=key item=vo}}
				{{if ($key >= 18) and ($key < 27)}}
					<li {{if $key lt 21}}style="border-top:none;"{{/if}}><dl class="news-info"><span class="news-ico"></span><a href="{{$newsBaseUrl}}/{{$vo.asName}}/news-{{$vo.id}}.html" title="{{$vo.title}}" target="_blank">{{$vo.title}}</a></dl></li>
				{{/if}}
			{{/foreach}}
			</ul>
		</div>
    </div><!--brand-news--><!--copy-->
  </div><!-- right-content -->
</div><!-- content -->

