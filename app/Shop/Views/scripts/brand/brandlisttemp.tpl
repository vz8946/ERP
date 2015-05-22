<div id="content">
  <div class="left-content">
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

  </div> <!-- left-content -->
  <div class="right-content">

  <div class="sitemap">
  	<ul>
    	<li>当前位置：</li>
        <li><a href="/" title="垦丰商城">垦丰商城</a></li>
        <li>&gt;&gt;</li>
         <li><a href="/b-{{$brand.as_name}}/" title="{{$brand.brand_name}}">{{$brand.brand_name}}</a></li>
        <li>&gt;&gt;</li>
        <li>{{$cate.cat_name}}</li>
   </ul>
    <dl class="sitemap-goods-count">共有 <font color="green">{{$countGoods}}</font> 种{{$cate.cat_name}}的产品</dl>
  </div>

  <div class="recommend-goods" style="margin-top:0px;">
    	<div class="goodslist-title news-title-bg">
        	<ul>
            	<li>排序方式：</li>
            	{{if $orderby eq 'time' }}
		    		  <li><a href="/b-{{$brand.as_name}}/list{{$cate.cat_id}}/sort-time-{{if $asc eq 'asc'}}desc{{else}}asc{{/if}}.html" title="上架时间"><dl>上架时间</dl></a></li>
		    	{{else}}
		    		  <li><a href="/b-{{$brand.as_name}}/list{{$cate.cat_id}}/sort-time-asc.html" title="上架时间"><dl>上架时间</dl></a></li>
		    	{{/if}}
		    	{{if $orderby eq 'price' }}
		    		 <li><a href="/b-{{$brand.as_name}}/list{{$cate.cat_id}}/sort-price-{{if $asc eq 'asc'}}desc{{else}}asc{{/if}}.html" title="价格"><dl>价格</dl></a></li>
		    	{{else}}
					 <li><a href="/b-{{$brand.as_name}}/list{{$cate.cat_id}}/sort-price-asc.html" title="价格"><dl>价格</dl></a></li>
		    	{{/if}}
            </ul>
        </div>
        <div class="recommend-info-box">
        {{foreach from=$goodsList key=key item=vo}}
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
		{{/foreach}}
        </div><!-- recommend-info-box -->
        <div class="goodslist-title news-title-bg" style="border-top:none;line-height:30px;text-align:right;">
        	{{$pagenav}} &nbsp;&nbsp;
        </div>
    </div><!--recommend-goods-->

  </div><!-- right-content -->
</div><!-- content -->
<!-- 热销产品 -->
<div class="hot-goods">
    	<div class="hot-title">
        	<dl class="hot-img"></dl>
            <dl class="hot-menu"></dl>
        </div>
        <div class="hot-info-box">
        	{{foreach from=$recommandGoodsList key=key item=vo}}
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
		{{/foreach}}
		</div>
		{{if $news}}
        <div class="category-news">
    	<div class="category-news-title news-title-bg">
        	<dl class="news-title-ico"></dl>
			<dl class="news-title-name fontColor">{{$brand.brand_name}}官网专卖店相关资讯</dl>
        </div>
        <!--  品牌分类资讯信息 -->
		<div class="category-news-info-box">
			<ul>
			{{foreach from=$news key=key item=vo}}
   				<li><dl class="category-news-info" {{if $key<3}}style="border:none;"{{/if}}><span class="news-ico"></span><a href="{{$newsBaseUrl}}/{{$vo.asName}}/news-{{$vo.id}}.html" title="{{$vo.title}}" target="_blank">{{$vo.title}}</a></dl></li>
			{{/foreach}}
			</ul>
		</div>
    </div><!--brand-news-->
    {{/if}}

    </div>