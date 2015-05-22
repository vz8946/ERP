<link rel="stylesheet" type="text/css" href="/_static/css/list_v2.css"/>
<script type="text/javascript">
	$(function() {
		$(".goods_left ul.title li").each(function(index) {
			$(this).click(function() {
				$(this).siblings().removeClass("selected")
				$(this).addClass("selected")
				$(".goods_left .con").hide()
				$(".goods_left .con").eq(index).show()
			})
		})
		$(".buyform").hover(function() {
			$(this).addClass("selected")
		}, function() {
			$(this).removeClass("selected")
		})
	})
</script>

<style>
#org_price2 pre{
	background: #FF911C;
	color: #fff;
	font-size: 0.8em;
	padding:2px 8px;
	font-weight: normal;
}
#org_price2 span{
	color:#ff6600;
}

#save_price{
	position: relative;
	left:-10px;
	top:-2px;
}

</style>


{{strip}}

<div class="main goods mar">
	<div class="cleardiv"></div>
	<div class="position wbox">
		<b><a href="/" title="垦丰商城">垦丰商城</a></b>
		&nbsp;&nbsp;
		{{$navi}}
	</div>

	<div class="up clear" style="border-top: 1px solid #ddd;">
		<div class="fl" style="width:376px;background: #f5f5f5;padding:10px;">
			<div class="re_goods" id="smallimg"><img id="simage" src="{{$imgBaseUrl}}/{{$data.group_goods_img|replace:'.':'_380_380.'}}"  alt="{{$data.goods_name}}"/>
				{{if $data.alt_img}}<p style="background:url(/{{$data.alt_img}}) no-repeat;_background:none;_filter:progid:DXImageTransform.Microsoft.AlphaImageLoader (enabled=ture,sizingMethod=crop,src=/{{$data.alt_img}})"></p>
				{{/if}}
				{{if $data.bevel_img}}<p class="other" style="background:url(/{{$data.bevel_img}}) no-repeat;_background:none;_filter:progid:DXImageTransform.Microsoft.AlphaImageLoader (enabled=ture,sizingMethod=crop,src=/{{$data.bevel_img}})"></p>
				{{/if}}
			</div>
		</div><!--fl end-->

		<div class="fr" style="padding-top: 10px;">
			<h2 style="padding-right:4px;font-weight: normal;font-family: Microsoft YaHei;font-size: 1.4em;
				padding-bottom:10px;
			">{{if $showTag eq '2'}} {{$viewTag.mark}} {{/if}}
				{{$data.group_sale_name}}<span>{{$data.group_specification}}</span>{{if $showTag eq '2'}} {{$viewTag.tag}} {{/if}}</h2>
			<ul class="clear dinfo">
				<li>
					<pre>商品编号</pre>
					: {{$data.group_sn}}

				</li>
				{{if $data.org_price}}
				<li id="org_price">
					<pre>1健 康 价</pre>
					: <em>￥{{$data.org_price}}</em>
				</li>
				<li class="red">
					{{if $data.show_name}}{{$data.show_name}}专享价{{else}}专享价{{/if}}： <strong>￥{{$data.group_price}} {{if $data.excluisv_name neq ''}}<font color="green">（{{$data.excluisv_name}}）</font>{{/if}}</strong><span class="blue" id="save_price">为您节省{{math equation="x - y" x=$data.group_market_price y=$data.group_price}}元</span>
				</li>
				{{else}}
				<li id="org_price2" class="red">
					<pre>内 购 价</pre>
					: <span>￥{{$data.group_price}}</span><span class="blue" id="save_price">为您节省{{math equation="x - y" x=$data.group_market_price y=$data.group_price}}元</span>
				</li>
				{{/if}}
				<li>
					<pre>市 场 价</pre>
					: <em>￥{{$data.group_market_price}}</em>
				</li>
				{{if $data.group_specification}}
				<li>
					<pre>规    格</pre>
					: {{$data.group_specification}}

				</li>
				<li>
					<pre>商品评分</pre>
					: <img src="{{$imgBaseUrl}}/images/shop/try/star_orange.jpg" style="margin-top:7px;margin-top:0\9">
					<img src="{{$imgBaseUrl}}/images/shop/try/star_orange.jpg" style="margin-top:7px;margin-top:0\9">
					<img src="{{$imgBaseUrl}}/images/shop/try/star_orange.jpg" style="margin-top:7px;margin-top:0\9">
					<img src="{{$imgBaseUrl}}/images/shop/try/star_orange.jpg" style="margin-top:7px;margin-top:0\9">
					<img src="{{$imgBaseUrl}}/images/shop/try/star_orange.jpg" style="margin-top:7px;margin-top:0\9">
				</li>
				{{/if}}
			</ul>
			<form id="buyForm">
				<div class="buyform" style="padding: 20px;">
					<div class="one">
						我要买 :
						<div class="clear">
							<a onclick="selNum('less')" class="fl"> </a>
							<input type="text" name="buy_number" id="buy_number"  value="1" class="text" onblur="if(this.value>20 || this.value<1){this.value=this.defaultValue;}" onkeyup="this.value=this.value.replace(/\D/g,'');" onafterpaste="this.value=this.value.replace(/\D/g,'');"/>
							<a onclick="selNum('add')"  class="fl er"></a>
							<p class="fl" id="point">
								赠送{{math equation="x * y" x=1 y=$data.group_price}}积分
							</p>
						</div>
					</div>

					<script language="javascript"  type="text/javascript">
						/*选择数量*/
						function selNum(flag) {

							try{
							var number = document.getElementById("buy_number").value ;
							if(flag == "add"){
							if(Number(number) >19)return;
							number =  Number(number) + 1;
							}
							if(flag == "less"){
							if(Number(number)>1){
							number = Number(number) - 1;
							}
							}
							document.getElementById("buy_number").value=number;
							document.getElementById("point").innerHTML = '赠送' + {{$data.group_price}} *
							number + '积分';

						}catch(e) {
						}
						}
					</script>
					{{if $data.status==1}}
					<div class="join">
						<a href="javascript:void(0);" onclick="addGroupCart({{$data.group_id}})">加入购物车</a>
						<!--
						<a href="javascript:void(0);" onclick="window.location.replace('/goods/favorite/goodsid/{{$data.goods_id}}');"  class="re_">收藏 >></a>
							-->
						
					</div>
					{{else}}
					<div class="join join_gray clear">
						<a href="javascript:void(0);" onclick="window.location.replace('/goods/favorite/goodsid/{{$data.goods_id}}');">商品已下架</a>
					</div>
					{{/if}}
					<div style="clear: both;"></div>
				</div>
			</form>

			<div class="share" style="padding-top: 20px;">
				您可以分享到：<a href="javascript:window.open('http://v.t.sina.com.cn/share/share.php?title='+encodeURIComponent(document.title)+'&url='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" class="t-sina" title="分享到新浪微博"> <img src="{{$imgBaseUrl}}/images/shop/share_sina.gif" alt="分享到新浪微博"/> </a> 新浪微博&nbsp; <a href="javascript:(function(){window.open('http://v.t.qq.com/share/share.php?title='+encodeURIComponent(document.title)+'&url='+encodeURIComponent(window.location.href)+'&source=bookmark','_blank','width=610,height=350');})()" class="t-qq" title="分享到QQ微博"> <img src="{{$imgBaseUrl}}/images/shop/share_qq.gif" alt="分享到QQ微博" /> </a> 腾讯微博&nbsp; <a href="javascript:window.open('http://www.kaixin001.com/repaste/share.php?rtitle='+encodeURIComponent(document.title)+'&rurl='+encodeURIComponent(window.location.href)+'&rcontent=看中一个好东东，很好看，是垦丰的'+encodeURIComponent(document.title)+' 亲爱的您也看下吧'+encodeURIComponent(location.href),'_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" class="kaixin" title="分享到开心网"> <img src="{{$imgBaseUrl}}/images/shop/share_kaixin.gif" alt="分享到开心网" /> </a> 开心网&nbsp; <a href="javascript:window.open('http://cang.baidu.com/do/add?it='+encodeURIComponent(document.title)+'&iu='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到百度收藏"> <img src="{{$imgBaseUrl}}/images/shop/share_baidu.gif" alt="分享到百度收藏"/> </a> 百度收藏&nbsp; <a href="javascript:window.open('http://bai.sohu.com/share/blank/addbutton.do?title='+encodeURIComponent(document.title)+'&link='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到搜狐白社会"> <img src="{{$imgBaseUrl}}/images/shop/share_bai.gif" alt="分享到搜狐白社会"/> </a> 搜狐白社会&nbsp; <a href="javascript:window.open('http://www.douban.com/recommend?title='+encodeURIComponent(document.title)+'&link='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到豆瓣"> <img src="{{$imgBaseUrl}}/images/shop/share_dou.gif" alt="分享到豆瓣"/> </a> 豆瓣
			</div><!--share end-->

		</div><!--fr end-->
	</div><!--up end-->

	<div class="goods_left" style="padding-top: 20px;width: 100%;">
		<ul class="clear title" style="width: 100%;">
			<li class="selected">
				<a href="javascript:void(0);">商品详情</a>
			</li>
			<li onclick="getGroupGoodsCommentList(1)">
				<a href="javascript:void(0);">产品评论</a>
			</li>
		</ul>

		<div class="con" style="display:block">
			<dl>
				<dt>
					组合套装介绍
				</dt>
				<dd class="clear">
					{{if $showTag eq '2'}} {{$viewTag.tips}} {{/if}}{{$data.group_goods_desc}}
				</dd>
			</dl>
		</div>
		<div class="con">
			<div class="goods_comment">
				<p class="sh">
					共有 <span id="msgtotup"></span> 人参与了评论 | 所有评论均来自购买过本产品的用户
				</p>
				<form action="javascript:;" onsubmit="submitComment(this)" method="post" name="commentForm" id="commentForm">
					<input type="hidden" name="score" id="score">
					<input type="hidden" name="title" value="产品评论" />
					<input type="hidden" name="group_goods_id" value="{{$data.group_id}}" />
					<input type="hidden" name="group_goods_name" value="{{$data.group_goods_name}}" />
					<h4>商品评分</h4><img id="star0_1"

					src="{{$imgBaseUrl}}/images/shop/try/star_gray.jpg" onmouseover="imgChange(0,1)" onclick="imgClick(0,1)">
					<img id="star0_2" src="{{$imgBaseUrl}}/images/shop/try/star_gray.jpg"

					onmouseover="imgChange(0,2)" onclick="imgClick(0,2)">
					<img id="star0_3" src="{{$imgBaseUrl}}/images/shop/try/star_gray.jpg"

					onmouseover="imgChange(0,3)" onclick="imgClick(0,3)">
					<img id="star0_4" src="{{$imgBaseUrl}}/images/shop/try/star_gray.jpg"

					onmouseover="imgChange(0,4)" onclick="imgClick(0,4)">
					<img id="star0_5" src="{{$imgBaseUrl}}/images/shop/try/star_gray.jpg"

					onmouseover="imgChange(0,5)" onclick="imgClick(0,5)">
					<span id="star_message"></span>
					
					<div class="grayborder" style="margin-top:10px;">
						<textarea name="content" id="content" cols="60"

		rows="6"></textarea>
					</div>
					<div class="tj">
						<input name="" type="submit"  value="我要评论"/>
					</div>
				</form>
				<div id="group_goods_msg_list"></div>
			</div>
		</div>
	</div>
	<!--re_left end-->
	<div style="clear:both;"></div>

	<!--Start::组合套装中的商品-->
	<style type="text/css">
		.look .buybtn img {
			width: 75px;
			height: 23px;
		}
	</style>
	{{if $showtype eq 1}}
	<div class="look"  id='bfd_bab'>
		<h3><b>本套装包含以下商品，您也可以单独购买</b></h3>
		<ul class="">
			{{foreach from=$gg key=key item=goods}}
			<li>
				<a href="/b-{{$goods.as_name|default:'jiankang'}}/detail{{$goods.goods_id}}.html" target="_blank"><img src="{{$imgBaseUrl}}/{{$goods.goods_img|replace:'.':'_180_180.'}}" /></a>
				<p>
					<a href="/b-{{$goods.as_name|default:'jiankang'}}/detail{{$goods.goods_id}}.html" target="_blank">{{$goods.goods_name}}</a>
				</p>
				<strong>￥{{$goods.price}}</strong>
				<a onclick="addCart('{{$goods.product_sn}}',1)" href="javascript:void(0);" class="buybtn"><img src="{{$imgBaseUrl}}/images/shop/fenlei_r14_c5.jpg"></a>
			</li>
			{{/foreach}}
			<div style="clear:both;"></div>
		</ul>
	</div>
	{{else if $showtype eq 2}}
	<div class="look"  id='bfd_bab'>
		<h3><b>也许你还会喜欢以下商品</b></h3>
		<ul class="clear">
			{{foreach from=$linkgoods key=key item=goods}}
			{{if $key<=5}}
			<li>
				<a href="/b-{{$goods.as_name|default:'jiankang'}}/detail{{$goods.goods_id}}.html" target="_blank"><img src="{{$imgBaseUrl}}/{{$goods.goods_img|replace:'.':'_180_180.'}}" /></a>
				<p>
					<a href="/b-{{$goods.as_name|default:'jiankang'}}/detail{{$goods.goods_id}}.html" target="_blank">{{$goods.goods_name}}</a>
				</p>
				<strong>￥{{$goods.price}}</strong>
				<a onclick="addCart('{{$goods.goods_sn}}',1)" href="javascript:void(0);" class="buybtn"><img src="{{$imgBaseUrl}}/images/shop/fenlei_r14_c5.jpg"></a>
			</li>
			{{/if}}
			{{/foreach}}
			<div style="clear:both;"></div>
		</ul>
	</div>
	{{/if}}

	<!--End::组合套装中的商品-->

</div><!--main end-->
{{/strip}}

<!--start::评论-->
<script type="text/javascript">
	function getGroupGoodsCommentList(page) {
		page = parseInt(page);
		if (page < 1) {
			page = 1;
		}
		$.ajax({
			url : '/group-goods/comment',
			data : {
				group_id : '{{$data.group_id}}',
				page : page
			},
			type : 'get',
			success : function(msg) {
				$('#group_goods_msg_list').html(msg);
				$('#msgtotup').html($('#msgtot').html());
				$('#group_goods_comment').show();
			}
		})
	}

	//
	var isClick0 = false;
	var messages = Array('很差', '比较差', '一般', '很好', '强烈推荐');
	function imgChange(id, tag) {
		if ((id == 0) && isClick0)
			return false;

		if (id == 0)
			document.getElementById('score').value = tag;

		for ( i = 1; i <= 5; i++) {
			var star = document.getElementById('star' + id + '_' + i);
			star.src="{{$imgBaseUrl}}/images/shop/try/star_gray.jpg";
		}
		for ( i = 1; i <= tag; i++) {
			var star = document.getElementById('star' + id + '_' + i);
			star.src="{{$imgBaseUrl}}/images/shop/try/star_orange.jpg";
		}

		if (id == 0)
			document.getElementById('star_message').innerHTML = messages[tag - 1];
	}

	function imgClick(id, tag) {
		if (id == 0)
			document.getElementById('score').value = tag;

		for ( i = 1; i <= 5; i++) {
			var star = document.getElementById('star' + id + '_' + i);
			star.src="{{$imgBaseUrl}}/images/shop/try/star_gray.jpg";
		}
		for ( i = 1; i <= tag; i++) {
			var star = document.getElementById('star' + id + '_' + i);
			star.src="{{$imgBaseUrl}}/images/shop/try/star_orange.jpg";
		}

		if (id == 0)
			document.getElementById('star_message').innerHTML = messages[tag - 1];

		if (id == 0)
			isClick0 = true;
		else if (id == 1)
			isClick1 = true;
		else if (id == 2)
			isClick2 = true;
		else if (id == 3)
			isClick3 = true;
		else if (id == 4)
			isClick4 = true;
	}

	//提交评论信息
	var cmt_empty_content = "评论的内容不能小于2个字符";
	var cmt_large_content = "您输入的评论内容超过了250个字符";

	function submitComment() {
		var content = $.trim($("#content").val());

		if (content.length < 2) {
			alert(cmt_empty_content);
			return false;
		}
		if (content.length > 250) {
			alert(cmt_large_content);
			return false;
		}

		$.ajax({
			url : '/group-goods/comment-add',
			type : 'post',
			data : $('#commentForm').serialize(),
			success : function(msg) {
				if (msg != '') {
					alert(msg);
				} else {
					alert('您的评论已成功提交，请等待管理员审核');
					$("#content").val('');
				}
			},
			error : function() {
				alert('网络错误，请稍后重试');
			}
		})

		return false;
	}
</script>
<!--end:评论-->

<script type="text/javascript">
	//dom加载完成后
	$(function() {
		//加载浏览历史
		$.ajax({
			url : '/goods/history',
			type : 'get',
			dataType : 'html',
			success : function(msg) {
				$('#puthistory').html(msg);
			}
		});
	})
	//右边加入购物车
	function addGalleryCart(goods_sn, num) {
		$.ajax({
			url : '/goods/check',
			data : {
				product_sn : goods_sn,
				number : num
			},
			type : 'get',
			success : function(msg) {
				if (msg != '') {
					alert(msg);
					window.location.replace('{{url}}');
				} else {
					window.location.replace('/flow/buy/product_sn/' + goods_sn + '/number/' + num);
				}
			}
		})
	}

	//添加
	function addGroupCart(g_id) {
		var tmp = parseInt(g_id);
		if (tmp < 1)
			return;
		var num = $('#buy_number').val();
		num = parseInt(num);
		if (num > 20 || num < 1)
			return;
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
					popOutBox();
					//id=popoutbox弹出层
					//第二次ajax
					$.ajax({
						url : '/index/cart/r/1',
						type : 'get',
						success : function(msg) {
							$('#popoutbox').html('<div id="msg1" style="text-align:center;">该商品已成功放入购物车</div>' + msg + '<div id="checknow" style="text-align:center;"><a href="/flow/index"><img src="{{$imgBaseUrl}}/images/shop/btn_check_right_now.jpg" /></a>&nbsp;&nbsp;<a style="position:relative;top:-12px;" href="javascript:closePopOutBox();">继续购物&raquo;&raquo;</a></div>');
							$('.top_cart').hide();
							$('.more').hide();
						}
					})
				}
			}
		});
	}

	//添加组合套装中的商品
	function addCart(productSn, number) {
		productSn = Number(productSn);
		if (productSn < 1)
			return;
		number = Number(number);
		if (number < 1)
			return;
		$.ajax({
			url : '/goods/check',
			data : {
				product_sn : productSn,
				number : number
			},
			type : 'get',
			success : function(msg) {
				if (msg != '') {
					alert(msg);
					window.location.replace('{{url}}');
				} else {
					window.location.replace('/flow/buy/product_sn/' + productSn + '/number/' + number);
				}
			}
		})
	}

	/*遮罩层*/
	function maskLayer(color) {
		if (!color) {
			color = '#ffffff';
		}
		var tmpMask = new String;
		tmpMask = '<div id="maskLayer"></div>';
		$("body").prepend(tmpMask);
		$('#maskLayer').css({
			'width' : $(document).width() + 'px',
			'height' : $(document).height() + 'px',
			'position' : 'absolute',
			'top' : '0px',
			'left' : '0px',
			'z-index' : '60',
			'background-color' : color,
			'filter' : 'alpha(opacity=50)',
			'opacity' : '0.5'
		});
	}

	/*弹出层*/
	function popOutBox() {
		maskLayer('#353535');
		//调用遮罩层
		var tmpTips = new String;
		tmpTips = '<div id="popoutboxframe" style="z-index:100000;"><div id="closebtn"><img src="{{$imgBaseUrl}}/images/shop/closebtn.gif" /></div><div id="popoutbox" style="padding:15px 0 25px 0"></div></div>';
		$("body").prepend(tmpTips);
		//弹出层样式
		$('#popoutboxframe').css({
			'position' : 'absolute',
			'left' : ($(window).scrollLeft() + $(window).width() / 2 - 250) + 'px',
			'top' : ($(window).scrollTop() + $(window).height() / 2 - 120) + 'px',
			'z-index' : '70',
			'width' : '500px',
			'border' : '2px solid #4C76C8',
			'background' : '#ffffff no-repeat 20px 50px',
			'text-align' : 'left',
			'line-height' : '22px'
		});
		//关闭按钮样式
		$('#closebtn').css({
			'text-align' : 'right',
			'margin' : '1px',
			'height' : '28px',
			'background' : '#1767ca'
		})
		$('#closebtn img').css({
			'cursor' : 'pointer',
			'margin-top' : '8px',
			'margin-right' : '5px'
		})
		//关闭按钮动作
		$('#closebtn img').bind('click', function() {
			closePopOutBox();
		})
	}

	/*关闭弹出层*/
	function closePopOutBox() {
		$('#popoutboxframe').remove();
		$('#maskLayer').remove();
	}
</script>
