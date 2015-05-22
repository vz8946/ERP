<div  class="wbox990">	
<div class="position"><a title="垦丰商城" href="/" style="color: #004ca2;">垦丰商城</a>&nbsp;{{$ur_here}}</div>
<div class="wrap">
	<div class="preview">
    	<div class="bigimg">
    	   <a   href="{{$imgBaseUrl}}/{{$data.goods_img}}"  class="jqzoom" rel='gal1'  title="triumph">
    	       <img width="378" height="378"  src="{{$imgBaseUrl}}/{{$data.goods_img|replace:'.':'_380_380.'}}"  alt="{{$data.goods_name}}">
    	  </a>
    	  <div class="goods_zoom"></div>
    	</div>
        <div class="smallimg" id="pic_small_wrapper">
        	<a class="btn_left btn_left_none" href="javascript:;"  onfocus="this.blur();" ></a>
             <div class="items" id="list_smallpic">
            	<ul id="thumblist">
            		<li>
					  <a onfocus="this.blur();"    class="zoomThumbActive"  href="javascript:void(0)" href='javascript:void(0);' rel="{gallery: 'gal1', smallimage: '{{$imgBaseUrl}}/{{$data.goods_img|replace:'.':'_380_380.'}}',largeimage: '{{$imgBaseUrl}}/{{$data.goods_img}}'}" >
                        <img src="{{$imgBaseUrl}}/{{$data.goods_img|replace:'.':'_60_60.'}}"  alt="{{$data.goods_name}}" >
                       </a>
					</li>
					{{if !empty($img_url)}}
					{{foreach from=$img_url item=r key=key}}
					{{if $r.img_url}}
					<li>
					<a onfocus="this.blur();"  href="javascript:void(0)"  rel="{gallery: 'gal1', smallimage: '{{$imgBaseUrl}}/{{$r.img_url|replace:'.':'_380_380.'}}',largeimage: '{{$imgBaseUrl}}/{{$r.img_url}}'}">
					<img src="{{$imgBaseUrl}}/{{$r.img_url|replace:'.':'_60_60.'}}"  alt="{{$data.goods_name}}"  ></a>
					</li>					
					{{/if}}
					{{/foreach}}
					{{/if}}					
                </ul>
            </div>
            <a class="btn_right" href="javascript:;" onfocus="this.blur();"  ></a>
        </div>
    </div>
    
  <div class="product_info">
    	<div class="name">
       	  <h2>{{$data.goods_name}}</h2>
            <b>{{$data.goods_alt}}</b>
        </div>
        <div class="summary">
             <p class="price">市场价:<span>￥{{$data.market_price}}</span></p>
			 <p class="price_jiankang"><span>内 购 价</span><em><b>￥</b>{{$data.staff_price}}</em><i>已优惠￥{{math equation="x - y" x=$data.market_price y=$data.price}}</i></p>	
             <p>商品编号：{{$data.goods_sn}}
             <p>品  牌：{{$brand.brand_name}}</p>
            <p>规  格：{{$data.goods_style}}</p>
        </div>

				  <div class="buyBox">
			       	    <p>我要买<br>
			       	    <a onclick="selNum('less')" href="javascript:;">-</a>
					     <input type="text" name="buy_number" id="buy_number"  value="1" class="text" onblur="if(this.value>{{$data.limit_number}} || this.value<1){this.value=this.defaultValue;}getPrice({{$id}},this.value)" onkeyup="this.value=this.value.replace(/\D/g,'');" onafterpaste="this.value=this.value.replace(/\D/g,'');"/>
					    <a onclick="selNum('add')"  href="javascript:;">+</a>
					    &nbsp;	
					    							
			       	    <span>赠送{{math equation="x * y" x=1 y=$data.price}}积分</span></p>
			       	     {{if $data.onsale==0 && $stock_number>0}}
			            <div class="link"><a href="javascript:void(0);" onclick="addCart('{{$data.goods_sn}}','buy_now')" ><img width="101" height="35" src="{{$_static_}}/images/shop/btn_buy.jpg"></a>
			            <a href="javascript:void(0);" onclick="addCart('{{$data.goods_sn}}','buy_cart')" ><img width="130" height="35" src="{{$_static_}}/images/shop/btn_cart.jpg"></a>
			            <a href="javascript:void(0);" onclick="favGoods(this,'{{$data.goods_id}}')" ><img width="80" height="26" src="{{$_static_}}/images/shop/btn_collect.jpg"></a></div>
			             {{elseif $data.onsale==0 && $stock_number<1}}
			              <a href="javascript:void(0);" onclick="goods_notice(this,'{{$data.goods_id}}')" ><img width="121" height="35" src="{{$_static_}}/images/shop/btn_notice.jpg"></a>
			           
			           <div id="goods_notice_box" style="display:none">
			            <form action="/goods/send-notice/" method="post" onsubmit="return check_notice()" id="frm_notice">
						  <p>
						  	&nbsp;邮箱/手机号码<br><input type="text" name="account" id="account"><br>
						    <input type="hidden" name="goods_id" value="{{$data.goods_id}}">
						  	<a href="javascript:;" onclick="$('#frm_notice').submit();"><img width="155" height="36" src="{{$_static_}}/images/shop/btn_take.jpg"></a>
						  </p>
						  <div class="note">
						  	*用户输入邮箱（或手机号）即可订阅<br>*商品到货后自动发送邮件（或短信）给顾客<br>*仅触发一次，发送后自动失效
						  </div>
						   </form>
						</div>     
						 {{elseif $data.onsale==1}}
						    <b>商品已下架</b>
			            {{/if}}			             
			        </div>
		
		        
        <div class="share">分享到：<img width="184" height="31" border="0" usemap="#Map" src="{{$_static_}}/images/shop/share.jpg">
          <map id="Map" name="Map">
            <area href="javascript:window.open('http://v.t.sina.com.cn/share/share.php?title='+encodeURIComponent(document.title)+'&url='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" class="t-sina" title="分享到新浪微博" coords="5,5,27,24" shape="rect">
            <area href="javascript:(function(){window.open('http://v.t.qq.com/share/share.php?title='+encodeURIComponent(document.title)+'&url='+encodeURIComponent(window.location.href)+'&source=bookmark','_blank','width=610,height=350');})()" class="t-qq" title="分享到QQ微博" coords="38,5,58,24" shape="rect">
            <area href="javascript:window.open('http://www.kaixin001.com/repaste/share.php?rtitle='+encodeURIComponent(document.title)+'&rurl='+encodeURIComponent(window.location.href)+'&rcontent=看中一个好东东，很好看，是垦丰电商的'+encodeURIComponent(document.title)+' 亲爱的您也看下吧'+encodeURIComponent(location.href),'_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" class="kaixin" title="分享到开心网" coords="69,6,87,25" shape="rect">
            <area href="javascript:window.open('http://cang.baidu.com/do/add?it='+encodeURIComponent(document.title)+'&iu='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到百度收藏" coords="99,7,117,25" shape="rect">
            <area href="javascript:window.open('http://bai.sohu.com/share/blank/addbutton.do?title='+encodeURIComponent(document.title)+'&link='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到搜狐白社会" coords="128,5,147,25" shape="rect">
            <area href="javascript:window.open('http://www.douban.com/recommend?title='+encodeURIComponent(document.title)+'&link='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到豆瓣" coords="158,5,174,24" shape="rect">
          </map>
        </div>
        
    </div>
</div>

<div class="main goods mar">
		<div style="padding-top: 30px;">
			<div class="goods_right list_side">
				{{if $buy_relation}}
				<div class="mod">
					<h2 class="stitle2">浏览本目录的顾客购买过</h2>
					<div class="conts">
						<ul class="prolist">
							{{foreach from=$buy_relation item=v key=k}}
							<li>
								<div class="pics">
									<div class="wh160 verticalPic">
										<a href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html" title="{{$v.goods_name}}"><img alt="{{$v.goods_name}}"
										src="{{$imgBaseUrl}}/{{$v.goods_img|replace:'.':'_380_380.'}}" height="160" width="160" /></a>
									</div>
								</div>
								<div class="txt">
									<a href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html" title="{{$v.goods_name}}">{{$v.goods_name}}</a>
								</div>
								<div class="Sprice">
									¥<span>{{$v.staff_price}}</span>
								</div>
							</li>
							{{/foreach}}
						</ul>
					</div>
				</div>
				{{/if}}

				{{if $view_relation}}
				<div class="mod">
					<h2 class="stitle2">浏览本目录的顾客浏览过</h2>
					<div class="conts">
						<ul class="prolist">
							{{foreach from=$view_relation item=v key=k}}
							<li>
								<div class="pics">
									<div class="wh160 verticalPic">
										<a href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html" title="{{$v.goods_name}}"><img alt="{{$v.goods_name}}"
										src="{{$imgBaseUrl}}/{{$v.goods_img|replace:'.':'_180_180.'}}" height="160" width="160" /></a>
									</div>
								</div>
								<div class="txt">
									<a href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html" title="{{$v.goods_name}}">{{$v.goods_name}}</a>
								</div>
								<div class="Sprice">
									¥<span>{{$v.staff_price}}</span>
								</div>
							</li>
							{{/foreach}}
						</ul>
					</div>
				</div>
				{{/if}}

				{{if $similar_relation}}
				<div class="mod">
					<h2 class="stitle2">同类商品推荐</h2>
					<div class="conts">
						<ul class="prolist">
							{{foreach from=$similar_relation item=v key=k}}
							<li>
								<div class="pics">
									<div class="wh160 verticalPic">
										<a href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html" title="{{$v.goods_name}}"><img alt="{{$v.goods_name}}"
										src="{{$imgBaseUrl}}/{{$v.goods_img|replace:'.':'_180_180.'}}" height="160" width="160" /></a>
									</div>
								</div>
								<div class="txt">
									<a href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html" title="{{$v.goods_name}}">{{$v.goods_name}}</a>
								</div>
								<div class="Sprice">
									¥<span>{{$v.staff_price}}</span>
								</div>
							</li>
							{{/foreach}}
						</ul>
					</div>
				</div>
				{{/if}}

				<div class="mod">
					<h2 class="stitle2"><a onclick="clearCook('/clearhistory.html',this);" href="javascript:void(0);">清空</a>历史浏览记录</h2>
					<div class="conts" id="historyBox">
						<ul class="hislist">
							{{if $history}}
							{{foreach from=$history item=v key=k}}
							<li class="clearfix">
								<div class="img">
									<div class="wh60 verticalPic">
										<a href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html" title="{{$v.goods_name}}"><img src="{{$imgBaseUrl}}/{{$v.goods_img|replace:'.':'_380_380.'}}" alt="{{$v.goods_name}}" width="60" height="60"></a>
									</div>
								</div>
								<div class="txt">
									<p class="title">
										<a href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html" title="{{$v.goods_name}}">{{$v.goods_name}}</a>
									</p>
									<p class="Sprice">
										￥<span>{{$v.staff_price}}</span>
									</p>
								</div>
							</li>
							{{/foreach}}
							{{else}}
							<div style="padding: 10px;">暂无浏览记录！</div>
							{{/if}}
						</ul>
					</div>
				</div>
				
			<div class="mod">
			 {{widget class="AdvertWidget"  id="16"}}
			</div>
								
			</div>

			<div class="goods_left mains">
			    {{if $groupGoodsAData}}
			       {{include file='goods/inc-show-pref-gg.tpl'}}
				{{/if}}
				
				{{if $links}}
				     {{include file='goods/inc-show-prmt-gg.tpl'}}
				{{/if}}
			
				<ul class="title"  id="ttt">
					<li class="selected">
						<a href="javascript:void(0);">商品详情</a>
					</li>
					<li><a href="javascript:void(0);">商品说明书</a></li>
					<li>
						<a href="javascript:void(0);">商品评论</a>
					</li>
					<li>
						<a href="javascript:void(0);">商品咨询</a>
					</li>
					<li>
						<a href="javascript:void(0);">品牌介绍</a>
					</li>
					<li>
						<a href="javascript:void(0);">关于垦丰</a>
					</li>
					<li  style="margin-right: 0px;float: right;">
						<a href="javascript:void(0);">服务说明</a>
					</li>
					<div style="clear:both;"></div>
				</ul>
				
				<div id="goods-detial-tab" class="goods-detial-tab">
				
				<div class="tab-c">
					<div style="height: 10px;overflow: hidden;"></div>
					<h2 class="dtitle"><span>商品详情</span></h2>
					<dl>
						<dd style="text-align:left">
							
							{{$data.description}}
							
							{{if !empty($img_ext_url)}}
							{{foreach from=$img_ext_url item=r}}
							<img src="{{$imgBaseUrl}}/{{$r.img_url}}" title="{{$r.img_desc}}" width="650px" alt="{{$data.goods_name}}">
							<br/>
							{{/foreach}}
							{{/if}}
							{{if !empty($data.goods_package)}}
							<img src="{{$data.goods_package}}" alt="{{$data.goods_name}}">
							{{/if}}
						</dd>				
					</dl>

				</div>
				
				<div class="tab-c">
					<div style="height: 10px;overflow: hidden;"></div>
					<h2 class="dtitle"><span>商品说明书</span></h2>
					{{$data.introduction|default:'内容还在完善中，抱歉！'}}
				</div>

				<div class="tab-c">
					<div style="height: 10px;overflow: hidden;"></div>
					<h2 class="dtitle"><span>商品评论</span></h2>
					<div id="comment_list"></div>
				</div>
				
				<!--新加商品咨询-->
				<div class="tab-c">
					<div style="height: 10px;overflow: hidden;"></div>
					<h2 class="dtitle"><span>商品咨询</span></h2>
					<div class="goods_comment ask"  id="consultation_list"></div>
				</div>
				<!--新加商品咨询 end-->

				<div class="tab-c">
					<div style="height: 10px;overflow: hidden;"></div>
					<h2 class="dtitle"><span>品牌介绍</span></h2>
					<div style="padding: 10px 0px;">
						{{$data.brand.brand_desc}}
					</div>
				</div>

				<div class="tab-c">
					<div style="height: 10px;overflow: hidden;"></div>
					<h2 class="dtitle"><span>关于垦丰</span></h2>
					<img style="width: 750px; float:left;" src="{{$imgBaseUrl}}/_static/images/group_compay_01.jpg" alt="">
                    <img style="width: 750px;" src="{{$imgBaseUrl}}/_static/images/group_compay_02.jpg" alt="">
				</div>

				<div class="tab-c">
					<div style="height: 10px;overflow: hidden;"></div>
					<h2 class="dtitle"><span>服务说明</span></h2>
					<div style="padding-top: 20px;">
						<h2 style="font-size: 24px;color: #555;font-weight: bold;">关于包装</h2>
						<img src="{{$imgBaseUrl}}/images/shop/yzfw1.jpg" alt="关于包装"/>
						<div style="padding-bottom: 10px;">所有纸箱，塑料袋等标有"垦丰种子"的外包装均由垦丰为您免费提供，我们会根据您所购商品的具体尺寸提供相应的完整包装，保证您所购商品在投递的过程中完好无损。</div>
						
						<h2 style="font-size: 24px;color: #555;font-weight: bold;">关于服务</h2>
						<div style="height: 10px;"></div>
						<img src="{{$imgBaseUrl}}/images/shop/yzfw2.jpg" alt="关于服务"/>
						<div style="padding-top: 5px;">垦丰商城向您保证所售种子的品质，我们旨在用线上的细心服务为您带来超越线下的购物体验和同质商品。为您提供全场包邮的服务，所有商品均由我们合作的物流公司代为投递，为保障物流质量和速度，我们已经与国内多家知名的物流公司建立合作，保证您所购商品顺利投递到您的手中。</div>
						<div>但由于种子自身的特殊性，根据国家相关的法律规定，一经售出非质量问题概不退货。如确有质量问题，我们会免费为您退换货。退换货有效期为七天，请您尽快确认，逾期恕不退换。</div>
					</div>
				</div>
				</div>
			</div>
			<div style="clear:both;"></div>
		</div>
		<!--re_left end-->

		<div class="cleardiv"></div>
	</div><!--main end-->
<script  type="text/javascript">

                 if (!window.navigator.cookieEnabled) alert('请打开您浏览器的Cookie，否则将影响您下单!');			
              			   
	    		    $('.jqzoom').jqzoom({ zoomType: 'standard',lens:true,zoomWidth:400,zoomHeight:400, preloadImages: false, alwaysOn:false});//放大镜                   
	    		   
	    		    function setBtnEnable(jqObj, enable, classname) {
	                    jqObj.removeClass(classname);
	                    if (!enable) jqObj.addClass(classname);
	                }

	                var nowIdx = 0;
	                var moving = false;
	                var pic_num  =$("#thumblist li").length
	                var avgWidth = 67;
	                $("#thumblist").width(avgWidth * pic_num);
	                function moveFn(direction) {
	                    if (moving) return;
	                    moving = true;
	                    if (direction > 0) {
	                        if (nowIdx >= pic_num - 5) return;
	                        nowIdx++;
	                    } else {
	                        if (nowIdx <= 0) return;
	                        nowIdx--;
	                    }
	                    $('#thumblist li').eq(nowIdx).mouseenter();
	                    setBtnEnable($('#pic_small_wrapper .btn_right'), nowIdx < pic_num - 5, 'btn_right_none');
	                    setBtnEnable($('#pic_small_wrapper .btn_left'), nowIdx > 0, 'btn_left_none');
	                    $('#list_smallpic').animate({
	                        scrollLeft: nowIdx * avgWidth
	                    }, 100, '', function() {
	                        moving = false;
	                    });
	                }

	                setBtnEnable($('#pic_small_wrapper .btn_right'), 0 < pic_num - 5, 'btn_right_none');
	                setBtnEnable($('#pic_small_wrapper .btn_left'), false, 'btn_left_none');
	                
	                $('#pic_small_wrapper .btn_left').click(function() {
	                    if ($(this).hasClass('btn_left_none')) {
	                        return;
	                    }
	                    moveFn(-1);
	                });
	    		    
	                $('#pic_small_wrapper .btn_right').click(function() {
	                    if ($(this).hasClass('btn_right_none')) {
	                        return;
	                    }
	                    moveFn(1);
	                });               
	    		
				 $(".activity dl").mouseenter(function() {
				 	$(this).children("dd").show();
				 }).mouseleave(function() {
				 	$(this).children("dd").hide();
				 });;
				 
				 $(".buyform").hover(function() {$(this).addClass("selected")}, function() {$(this).removeClass("selected")});
				var absTop=$('#ttt').offset().top-220;
				 $(".goods_left ul.title li:not(.go_cart)").each(function(index) {
					$(this).click(function() {
						$(window).scrollTop(absTop-100);
						$(this).siblings().removeClass("selected")
						$(this).addClass("selected")
						$(".goods_left .con").hide()
						$(".goods_left .con").eq(index).show()

						if (index > 0) {
							$('#goods-detial-tab').find('.tab-c').hide();
							$('#goods-detial-tab').find('.tab-c').eq(index).show();
						} else {
							$('#goods-detial-tab').find('.tab-c').show();
						}
					});
				
				});		
				
				$(".more_num ol li").hover(function() {$(this).addClass("selected")}, function() {$(this).removeClass("selected")});				
				getCommentList({{$id}},1);
				getZxList({{$id}});		
				
					
				$(window).scroll(function () {
					var $body = $("body");					
			        /*判断窗体高度与竖向滚动位移大小相加 是否 超过内容页高度*/
			        if ($(window).scrollTop() > absTop) {$('#ttt').addClass('fixer'); }else{$('#ttt').removeClass('fixer');}
			    });		
                
				/*选择数量*/
				function selNum(flag){
				    	try{
							var number = $("#buy_number").val();
							if(flag == "add"){
							   if(Number(number) >= {{$data.limit_number}}) return;
							   number =  Number(number) + 1;
							}
							if(flag == "less"){
								if(Number(number)>1){
								    number = Number(number) - 1;
								}
							}						
							$("#buy_number").val(number);
							getPrice({{$id}}, number);
						 }catch(e){}
				}	
				
			   	function getPrice(id, number) {
					 if (($('#org_price1').length>0) || ($('#org_price2').length>0)) {
									$.ajax({
									url: '/goods/get-price/id/'+id+'/number/'+number+'/r/'+Math.random(),
									type: 'get',
									success:function(data){
									if (data) {
										if (data >= {{$data.staff_price}}) {
										 data = {{$data.staff_price}};
										}
										var savePrice = {{$data.market_price}} - data;
										 $('#current_price').html(data.toFixed(2));
										 $('#save_price').html( '为您节省' + Math.round(savePrice*100)/100 + '元');
										 $("#point").html('赠送' + Math.round(data * number * 100)/100 + '积分');
									}
								}
							 });
						}
			 }
		
</script>	
</div>