<div style="width: 1200px; margin: 0px auto;">
	<style>
.list_side .stitle2 {
	height: 36px;
}

.left ul {
	border: none;
	padding: 0px;
}

ul.choose_list li {
	padding: 5px;
	padding-bottom: 10px;
}

.item-h img {
	border: 1px solid #FF6600;
	width: 152px !important;
	height: 152px !important;
	padding: 3px;
}

.item-h {
	background: #f5f5f5;
}

.clearfix {
    display: inline-block;
}

.list_side .mod .keylist li {
    background: url("/images/listfix2.png") no-repeat scroll 22px 14px transparent;
    color: #999999;
    line-height: 16px;
    padding-left: 40px;
}

</style>

	<div class="main mar">
		<div class="cleardiv"></div>
		<!--<div class="pos">{{$ur_here}}</div>-->
		<div class="left list_side" style="padding-top: 20px; width: 220px;">
			<div class="mod">
				<h2 class="stitle">种子</h2>
				<div class="conts">
					<ul class="keylist">
						{{foreach from=$cat_menu item=v key=k}}
						<li>
							<a href="/gallery-{{$v.cat_id}}-0-0-0-1.html" target="_blank" >{{$v.cat_name}}</a>
						</li>
						{{/foreach}}
					</ul>
				</div>
			</div>

			<div class="mod">
				<h2 class="stitle2">
					<a rel="nofollow" href="javascript:clearCook('/clearhistory');">清空</a>历史浏览记录
				</h2>
				<div class="conts" id="historyBox">
					<ul class="hislist">
						{{if $history}} {{foreach from=$history item=v key=k}}
						<li class="clearfix">
							<div class="img">
								<div class="wh60 verticalPic">
									<a
										href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html"
										title="{{$v.goods_name}}"><img
										src="{{$imgBaseUrl}}/{{$v.goods_img|replace:'.':'_380_380.'}}"
										alt="{{$v.goods_name}}" width="60" height="60"></a>
								</div>
							</div>
							<div class="txt"
								style="padding-left: 10px; width: 120px; overflow: hidden;">
								<p class="title">
									<a
										href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html"
										title="{{$v.goods_name}}">{{$v.goods_name}}</a>
								</p>
								<p class="Sprice">
									￥<span>{{$v.price}}</span>
								</p>
							</div>
						</li> {{/foreach}} {{else}}
						<div style="padding: 10px;">暂无浏览记录！</div>
						{{/if}}

					</ul>
				</div>
			</div>

		</div>
		<!--left end-->
		<div class="rig">



			<div id="selection_container" class="choose"
				style="border: none; background: none; padding: 0px; height: auto;">
				<div class="product-filter">
					<div class="product-filter-title">
						<span class="lfloat greenColor"><b>商品筛选 - {{$keywords}}</b></span><span
							id="show-filter" class="rfloat"></span>
					</div>
					<div class="product-filter-content">
						<dl>
							<dd class="filter-name">
								品牌:
							</dd>
							<dd class="filter-info">
								{{foreach from=$filter_brand item=v key=k}}
								<a href="{{$v.url}}" class="{{if $v.is_c}}all_screen{{/if}}" >{{$v.brand_name}}</a>&nbsp;
								{{/foreach}}
							</dd>
						</dl>
						<dl style="border-top:1px dashed #d9d9d9">
							<dd class="filter-name">
								价格:
							</dd>
							<dd class="filter-info">
								{{foreach from=$filter_price item=v key=k}}
								<a href="{{$v.url}}" class="{{if $v.is_c}}all_screen{{/if}}">{{$v.price_name}}</a>&nbsp;
								{{/foreach}}
							</dd>
						</dl>
					</div>
				
				</div>

				<ul class="choose_list" id="itemArray" style="clear:both;">

					{{if $list}}{{foreach from=$list item=data}}
					<li><a
						href="/b-{{$data.as_name|default:'jiankang'}}/detail{{$data.goods_id}}.html"
						target="_blank"> <img  src="{{$imgBaseUrl}}/{{$data.goods_img|replace:'.':'_380_380.'}}" alt="{{$data.goods_name}}" /></a>
						<p style="height: 60px; padding-top: 8px;">
							<a href="/b-{{$data.as_name|default:'jiankang'}}/detail{{$data.goods_id}}.html">{{$data.goods_title}} {{$data.goods_style}} </a>
						</p> <span>{{$data.goods_alt}}</span> <em>市场价：￥{{$data.market_price}}</em>

						<br /> <span style="display: inline; color: #666;">
							
						{{if $data.org_price}}	
							{{if $data.offers_type=='exclusive'}}
							专享价：
							{{elseif $data.offers_type=='price-exclusive'}}
							特 价：
							{{elseif $data.offers_type=='fixed'}}
							抢 购 价：
						  {{elseif $data.offers_type=='discount'}}
							{{$data.discount_title}} 	<span>折    价：</span>
							{{/if}}
						
						 {{else}} 

						垦丰价：
						 {{/if}}
							
					</span>

						<span
						style="color: red; display: inline; font-weight: bold;">￥{{$data.price}}</span>

						<!--<a  onclick="addGalleryCart('{{$data.goods_sn}}','1')" alt="放入购物车" name="addtocart" ><img src="{{$imgBaseUrl}}/images/shop/fenlei_r15_c14.jpg" alt="购买" /></a>
						<a  href="javascript:void(0);" onclick="window.location.replace('/goods/favorite/goodsid/{{$data.goods_id}}');" ><img src="{{$imgBaseUrl}}/images/shop/fenlei_r15_c16.jpg" alt="收藏"/></a>-->

					</li>
					{{/foreach}}
					{{else}}
					<li style="padding: 20px;text-align: center;">没有搜索到相应的商品！</li>
					{{/if}}
					
					<div style="clear: both;"></div>
				</ul>
				<div style="clear: both;"></div>
				<div class="pagenav1" style="padding-top: 10px;">{{$pagenav}}</div>
			</div>
			<!--rig end-->
		</div>
		<!--main end-->
		<div style="clear: both;"></div>
		<script type="text/javascript">
			if (!window.navigator.cookieEnabled)
				alert('请打开您浏览器的Cookie，否则将影响您下单!');
			function addGalleryCart(goods_sn, number) {
				$.ajax({
					url : '/goods/check/product_sn/' + goods_sn + '/number/' + number,
					type : 'get',
					success : function(data) {
						if (data != '') {
							alert(data);
							window.location.replace('{{url}}');
						} else {
							window.location.replace('/flow/buy/product_sn/' + goods_sn + '/number/' + number);
						}
					}
				})
			}

			//添加组合商品
			function addGroupCart(g_id) {
				var tmp = parseInt(g_id);
				if (tmp < 1)
					return;
				var num = 1;
				//第一次ajax
				$.ajax({
					url : '/group-goods/check',
					data : {
						group_id : tmp,
						number : num
					},
					type : 'post',
					success : function(msg) {
						if (msg != '') {
							alert(msg);
						} else {
							window.location.replace('/flow/index/');
						}
					}
				});
			}

			/*Start::搜索结果高亮*/
			//关键字高亮
			function SearchHighlight(idVal, kw) {
				var keyword = sortkeyword(kw);
				var pucl = document.getElementById(idVal);
				if ("" == keyword)
					return;
				var temp = pucl.innerHTML;
				var htmlReg = new RegExp("\<.*?\>", "i");
				var arrA = new Array();
				//替换HTML标签
				for (var i = 0; true; i++) {
					var m = htmlReg.exec(temp);
					if (m) {
						arrA[i] = m;
					} else {
						break;
					}
					temp = temp.replace(m, "{[(" + i + ")]}");
				}
				words = unescape(keyword.replace(/\+/g, ' ')).split(/\s+/);
				//替换关键字
				for ( w = 0; w < words.length; w++) {
					var r = new RegExp("(" + words[w].replace(/[(){}.+*?^$|\\\[\]]/g, "\\$&") + ")", "ig");
					temp = temp.replace(r, "<b style='color:Red;'>$1</b>");
				}
				//恢复HTML标签
				for (var i = 0; i < arrA.length; i++) {
					temp = temp.replace("{[(" + i + ")]}", arrA[i]);
				}
				pucl.innerHTML = temp;
			}

			//英文数字排在前边
			function sortkeyword(kw) {
				var key = $.trim(kw.replace(/\+/g, ' '));
				arr = key.split(' ');
				arrayA = new Array();
				arrayB = new Array();
				arrayC = new Array();
				for ( i = 0; i < arr.length; i++) {
					if (/^[A-Za-z]+$/g.test(arr[i])) {
						arrayA.push(arr[i]);
					} else if (/[0-9]/g.test(arr[i])) {
						;
					} else {
						arrayB.push(arr[i]);
					}
				}
				arrayC = arrayA.concat(arrayB);
				keyword = arrayC.join(" ");
				return $.trim(keyword);
			}


			$(document).ready(function() {
				sw = $.trim($.cookie('searchkeywords'));
				if (!sw)
					return;
				SearchHighlight("itemArray", sw);

				$('#itemArray').find('li').hover(function() {
					$(this).addClass('item-h');
				}, function() {
					$(this).removeClass('item-h');
				});

			});
			/*End::搜索结果高亮*/

		</script>
	</div>
