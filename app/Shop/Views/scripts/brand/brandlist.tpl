<link href="../../Public/css/global-city.css" type="text/css" rel="stylesheet" />
<link href="../../Public/css/detail-bak.css" type="text/css" rel="stylesheet" />
<div class="global-width" style="overflow:hidden;">
<div class="position">
	<b><a href="/">垦丰商城</a></b> &gt;
	<a href="/brand.html">品牌馆</a> &gt;
	<a href="/b-{{$brand.as_name}}/" title="{{$brand.brand_name}}">{{$brand.brand_name}}</a> &gt;
	<a href="/b-{{$brand.as_name}}/list{{$cate.cat_id}}" title="">{{$cate.cat_name}}</a>
</div>

<!-- 左侧导航信息 -->
<div class="product-left">
  <!-- 产品分类 -->
  <div class="product-leftbox mt10">
  		<div class="category-leftbox-title"></div>
        <div class="category">
        	<ul>
        		{{foreach from=$cateList item=vo}}
				<li class="greenColor">
					<span class="cate-child-ico"></span>
					<a href="/b-{{$brand.as_name}}/list{{$vo.cat_id}}/" title="{{$vo.cat_name}}">{{$vo.cat_name}}</a>
				</li>
				{{/foreach}}
			</ul>
		</div>
  </div>
  		<!-- End 产品分类 -->
 <div class="hot-product mt10">
	<div class="product-box"><span class="product-box-type">HOT</span><span class="product-box-name greenColor">热销排行榜</span></div>
	<div class="product-list">
		<ul>
			{{foreach from=$hotGoods.details key=key item=vo}}
				{{if $key eq 0}}
				<li>
					<p class="product-seq-box">{{$key+1}}</p>
					<p class="img_60_60"><a href="/b-{{$vo.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank"><img  alt="{{$vo.goods_name}}" src="{{$imgBaseUrl}}/{{$vo.goods_img|replace:'.':'_60_60.'}}" border="0" height="60px" width="60px"></a></p>
					<p class="product-name"><a href="/b-{{$vo.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank">{{$vo.goods_name}}</a></p>
					<p class="product-price">￥{{$vo.price}} 元</p>
				</li>
				{{else}}
				<li>
					<p class="product-seq-box-unshow">{{$key+1}}</p>
					<p class="display"><a href="/b-{{$vo.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank"><img alt="{{$vo.goods_name}}" src="{{$imgBaseUrl}}/{{$vo.goods_img|replace:'.':'_60_60.'}}" border="0" height="60px" width="60px"></a></p>
					<p class="product-name-unshow"><a href="/b-{{$vo.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank">{{$vo.goods_name}}</a></p>
					<p class="display">￥{{$vo.price}} 元</p>
				</li>
				{{/if}}
			{{/foreach}}
		</ul>
	</div>
</div>
<div class="new-product mt10">
	<div class="product-box"><span class="product-box-type">POI</span><span class="product-box-name greenColor">最受关注的产品</span></div>
	<div class="product-list">
		<ul>
			{{foreach from=$focusGoods.details key=key item=vo}}
				{{if $key eq 0}}
				<li>
					<p class="product-seq-box">{{$key+1}}</p>
					<p class="img_60_60"><a href="/b-{{$vo.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank"><img  alt="{{$vo.goods_name}}" src="{{$imgBaseUrl}}/{{$vo.goods_img|replace:'.':'_60_60.'}}" border="0" height="60px" width="60px"></a></p>
					<p class="product-name"><a href="/b-{{$vo.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank">{{$vo.goods_name}}</a></p>
					<p class="product-price">￥{{$vo.price}} 元</p>
				</li>
				{{else}}
				<li>
					<p class="product-seq-box-unshow">{{$key+1}}</p>
					<p class="display"><a href="/b-{{$vo.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank"><img alt="{{$vo.goods_name}}" src="{{$imgBaseUrl}}/{{$vo.goods_img|replace:'.':'_60_60.'}}" border="0" height="60px" width="60px"></a></p>
					<p class="product-name-unshow"><a href="/b-{{$vo.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank">{{$vo.goods_name}}</a></p>
					<p class="display">￥{{$vo.price}} 元</p>
				</li>
				{{/if}}
			{{/foreach}}
		</ul>
	</div>
</div>

  <div class="product-leftbox mt10">
    <div class="blod paddingLeft13px global-title-bg greenColor">您最近浏览过的商品</div>
    <div class="search_left_history">
    	{{if $history}}
    	{{foreach from=$history item=vo}}
    	<ul>
      		<li>
			     <div class="left_history_pic lfloat di"> <a href="/b-{{$vo.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank"><img  alt="{{$vo.goods_name}}" src="{{$imgBaseUrl}}/{{$vo.goods_img|replace:'.':'_60_60.'}}" border="0" height="58px" width="58px"></a></div>
			     <div class="left_history_text lfloat"><a href="/b-{{$vo.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}">{{$vo.goods_name}}</a></div>
                 <span class="left_history-price">￥{{$vo.price}} 元</span>
			     <div class="cb mt5"></div>
			</li>
		</ul>
		{{/foreach}}
		{{else}}
			<font style='color:#999999;padding-left:50px;'>暂无浏览记录！</font>
		{{/if}}
	</div>
    <div class="history_more"><a href="javascript:clearCook('/clearhistory.html');" rel="nofollow">清除列表</a></div>
  </div>
  <div style="width:183px; clear:both; ">&nbsp;&nbsp;&nbsp;</div>
  <script>
	  	//清空浏览记录
	  function clearCook(url){
	        $.ajax({
				type: "GET",
				cache:false,
				url: url,
				success: function(){
					$(".search_left_history").html("<font style='color:#999999;padding-left:50px;'>暂无浏览记录！</font>");
				}
			});
	  }
  </script>
  <!--history  end-->
</div><!-- end product-left -->
<div class="product-right">
	<div class="product-filter">
    	<div class="product-filter-title">
                <span class="lfloat greenColor">{{$brand.brand_name}}</span>
                <span class="rfloat" id="show-filter"><strong>-</strong> 收起</span>
        </div>
        <div id="filtercontent" class="product-filter-content">
        	<dl>
            	<img src="{{$imgBaseUrl}}/{{$brand.big_logo}}" height="243" width="800">
             </dl>
            <dl class="brand-introduce-box">
            	<dd class="brand-introduce-text">
            		{{$brand.brand_desc}}
            	</dd>
            </dl>
        </div>
    </div>
<!-- 产品列表 -->
    <div class="list-bar">排序：
    	{{if $orderby eq 'time' }}
    		 <a rel="nofollow" href="/b-{{$brand.as_name}}/list{{$cate.cat_id}}/sort-time-{{if $asc eq 'asc'}}desc{{else}}asc{{/if}}.html" class="all_screen" title="上架时间">上架时间 <span class="product-{{if $asc eq 'asc'}}asc{{else}}desc{{/if}}"></span></a>
    	{{else}}
    		 <a rel="nofollow" href="/b-{{$brand.as_name}}/list{{$cate.cat_id}}/sort-time-asc.html" class="all_screen_off" title="上架时间">上架时间 <span class="product-asc"></span></a>
    	{{/if}}
    	{{if $orderby eq 'price' }}
    		 <a rel="nofollow" href="/b-{{$brand.as_name}}/list{{$cate.cat_id}}/sort-price-{{if $asc eq 'asc'}}desc{{else}}asc{{/if}}.html" class="all_screen" title="价格">价格 <span class="product-{{if $asc eq 'asc'}}asc{{else}}desc{{/if}}"></span></a>
    	{{else}}
			 <a rel="nofollow" href="/b-{{$brand.as_name}}/list{{$cate.cat_id}}/sort-price-asc.html" class="all_screen_off" title="价格">价格 <span class="product-asc"></span></a>
    	{{/if}}
	</div>
	
    <div class="product-list-list">
    	{{foreach from=$goodsList item=vo}}
    	<ul>
			<li class="product-list-goods-img">
				<a title="{{$vo.goods_name}}" href="/b-{{$brand.as_name}}/detail{{$vo.goods_id}}.html">
						<img alt="{{$vo.goods_name}}" src="{{$imgBaseUrl}}/{{$vo.goods_img|replace:'.':'_180_180.'}}" border="0" height="168" width="168">
				</a>
			</li>
			<li class="product-list-goods-info" style="height:40px;">
				<a title="{{$vo.goods_name}}" href="/b-{{$brand.as_name}}/detail{{$vo.goods_id}}.html">{{$vo.goods_name}}</a>
			</li>
			<li class="product-list-goods-info"><span class="throughtText global-color">市场价：{{$vo.market_price}}元</span>
            </li>
            <li class="product-list-goods-info global-color"><span class="priceColor blod">￥{{$vo.price}}元</span> </li>
		</ul>
		{{/foreach}}
		</div><!-- end 产品列表></div> -->
    <div class="product-split-page">{{$pagenav}}          </div>
</div><!-- end product-right -->
</div>
{{if $news}}
<div class="category-news global-width">
   <dl class="category-news-title global-title-bg greenColor"><span class="lfloat">相关资讯</span> <span class="global-more-bg rfloat a-color-white marginTop10Left20"><a title="更多资讯" href="nutrimate/.1jiankang.com/">更多&gt;&gt;</a></span></dl>
   <dl class="category-news-list">
   		<ul>
   			{{foreach from=$news key=key item=vo}}
   				{{if $key<3}}
	            <li style="border-top:none;">
	            	<span class="global-dian"></span>
					<a target="_blank" title="{{$vo.title}}" href="{{$newsBaseUrl}}/{{$vo.asName}}/news-{{$vo.id}}.html">{{$vo.title}}</a>
				</li>
				{{else}}
				<li>
	            	<span class="global-dian"></span>
					<a target="_blank" title="{{$vo.title}}" href="{{$newsBaseUrl}}/{{$vo.asName}}/news-{{$vo.id}}.html">{{$vo.title}}</a>
				</li>
				{{/if}}
			{{/foreach}}
		</ul>
    </dl>
</div>
{{/if}}

<script type="text/jscript" src="../../Public/js/minBrand.js"></script>
