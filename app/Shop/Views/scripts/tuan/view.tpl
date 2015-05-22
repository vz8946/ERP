{{include file="header_tuan.tpl"}}
<link href="/styles/shop/tuan.css" rel="stylesheet" type="text/css" />
<div class="main mar">
<div class="left">

<div class="list">

<h3><strong>{{if $data.status eq 1}}今日{{elseif $data.status eq 0}}预计{{else}}往期{{/if}}团购：</strong>{{$data.description}}</h3>
<div class="l">
<div class="one"><div><strong>￥{{$data.price}}</strong><a {{if $data.status ne 1}}class="no"{{/if}} class="gogo" href="javascript:void(0)" {{if $data.status eq 1}}onclick="addCart({{$data.tuan_goods_id}})"{{/if}}></a></div>
<ul class="grayborder clear"><li>市场价<br /><em>￥{{$data.market_price}}</em></li>
<li>目前折扣<br />{{$data.discount}}折</li>
<li>为您节省<br /><strong>￥{{math equation="x - y" x=$data.market_price y=$data.price}}</strong></li></ul></div>
{{if $data.status eq 1}}
<div class="two grayborder" id="countdown"></div>
<script type="text/javascript">
function countdown(enddate){var t=new Date();var tT=new Date(enddate);var sg=(tT.getTime()-t.getTime());var lH=sg/3600000;var fH=Math.floor(lH);var lM=(lH-fH)*60;var fM=Math.floor(lM);var lS=(lM-fM)*60;var fS=Math.floor(lS);var fC=Math.floor((lS-fS)*1000);var sM=Math.floor(fC/10);var showTime = document.getElementById('countdown').innerHTML='剩余时间：'+'<p><span>'+fH +'</span>'+'小时'+'<span>'+fM+'</span>'+'分'+'<span>'+fS+'</span>'+'秒</p>';window.setTimeout("countdown('" + enddate + "')",100);}
countdown('{{$data.end_time_2}}');
</script>
{{elseif $data.status eq 2 || $data.status eq 3}}
<div class="two grayborder">剩余时间：<p>已结束</p></div>
{{elseif $data.status eq 0}}
<div class="two grayborder">剩余时间：<p>未开始</p></div>
{{/if}}
<div class="three grayborder"><strong>{{math equation="x + y" x=$data.alt_count y=$data.count}}</strong> 人已购买<br />数量有限，下单要快哟</div>
</div>
<div class="r grayborder">
<img src="{{$imgBaseUrl}}/{{$data.main_img}}"/>
<div class="share">
分享到：
<a href="javascript:window.open('http://v.t.sina.com.cn/share/share.php?title='+encodeURIComponent(document.title)+'&url='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" class="t-sina" title="分享到新浪微博"> <img src="{{$imgBaseUrl}}/images/shop/share_sina.gif" alt="分享到新浪微博"/> </a>
<a href="javascript:(function(){window.open('http://v.t.qq.com/share/share.php?title='+encodeURIComponent(document.title)+'&url='+encodeURIComponent(window.location.href)+'&source=bookmark','_blank','width=610,height=350');})()" class="t-qq" title="分享到QQ微博"> <img src="{{$imgBaseUrl}}/images/shop/share_qq.gif" alt="分享到QQ微博" /> </a>
<a href="javascript:window.open('http://www.kaixin001.com/repaste/share.php?rtitle='+encodeURIComponent(document.title)+'&rurl='+encodeURIComponent(window.location.href)+'&rcontent=看中一个好东东，很好看，是垦丰电商的'+encodeURIComponent(document.title)+' 亲爱的您也看下吧'+encodeURIComponent(location.href),'_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" class="kaixin" title="分享到开心网"> <img src="{{$imgBaseUrl}}/images/shop/share_kaixin.gif" alt="分享到开心网" /> </a>
<a href="javascript:window.open('http://cang.baidu.com/do/add?it='+encodeURIComponent(document.title)+'&iu='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到百度收藏"> <img src="{{$imgBaseUrl}}/images/shop/share_baidu.gif" alt="分享到百度收藏"/> </a>
<a href="javascript:window.open('http://bai.sohu.com/share/blank/addbutton.do?title='+encodeURIComponent(document.title)+'&link='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到搜狐白社会"> <img src="{{$imgBaseUrl}}/images/shop/share_bai.gif" alt="分享到搜狐白社会"/> </a>
<a href="javascript:window.open('http://www.douban.com/recommend?title='+encodeURIComponent(document.title)+'&link='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到豆瓣"> <img src="{{$imgBaseUrl}}/images/shop/share_dou.gif" alt="分享到豆瓣"/> </a>
</div>
</div>
<div class="cleardiv"></div></div><!--list end-->

<div class="list">
<h2>温馨提示</h2>
<div class="con">
全国配送（除港、澳、台、新疆、西藏、宁夏、甘肃地区）；<br />
请您在购买时仔细填好您的详细收货地址、姓名和电话，有关于产品咨询和发货的问题请拨打客服电话：4000-517-317； <br />
商品送达时，请您本人务必当面仔细检查验收后再签收，如商品配送有误、数量缺失、产品破损等问题，请当面及时向配送人员提出并拒收，商家会尽快为您安排调换，签收后如发现上述问题，概不退换，敬请谅解； <br />
</div>
<h2>商品介绍</h2>
<div class="con">
{{if $data.tuan_goods_description}}
{{$data.tuan_goods_description}}
{{else}}
<strong>产品规格</strong> {{$data.spec}}<br>
<strong>产品特点</strong> {{$data.brief}}<br>
<strong>产品功效</strong> {{$data.description}}<br>
<strong>适用人群</strong> {{$data.tip}}<br>
<strong>使用方法</strong> {{$data.usage}}<br>
<strong>注意事项</strong> {{$data.notes}}
{{/if}}
</div>
<h2>图片展示</h2>
<div class="con">
{{foreach from=$data.img  key=k  item=img}}
<img src="{{$imgBaseUrl}}/{{$img}}" width="600px">
{{/foreach}}
</div>


</div>

</div><!--left end-->

<div class="rig">
<div class="share grayborder">
<h2>分享到：</h2>
<a href="javascript:window.open('http://v.t.sina.com.cn/share/share.php?title='+encodeURIComponent('团购平台-垦丰电商 -专业的种子商城，品质保证')+'&url='+encodeURIComponent('http://jiankang/tuan')+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" class="t-sina" title="分享到新浪微博"> <img src="{{$imgBaseUrl}}/images/shop/share_sina.gif" alt="分享到新浪微博"/> </a>
<a href="javascript:(function(){window.open('http://v.t.qq.com/share/share.php?title='+encodeURIComponent('团购平台-垦丰电商 -专业的种子商城，品质保证')+'&url='+encodeURIComponent('jiankang/tuan')+'&source=bookmark','_blank','width=610,height=350');})()" class="t-qq" title="分享到QQ微博"> <img src="{{$imgBaseUrl}}/images/shop/share_qq.gif" alt="分享到QQ微博" /> </a>
<a href="javascript:window.open('http://www.kaixin001.com/repaste/share.php?rtitle='+encodeURIComponent('团购平台-垦丰电商 -专业的种子商城，品质保证')+'&rurl='+encodeURIComponent('http://www.1jiankang.com/tuan')+'&rcontent=看中一个好东东，很好看，是国药电商的'+encodeURIComponent(document.title)+' 亲爱的您也看下吧'+encodeURIComponent(location.href),'_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" class="kaixin" title="分享到开心网"> <img src="{{$imgBaseUrl}}/images/shop/share_kaixin.gif" alt="分享到开心网" /> </a>
<a href="javascript:window.open('http://cang.baidu.com/do/add?it='+encodeURIComponent('团购平台-垦丰电商 -专业的种子商城，品质保证')+'&iu='+encodeURIComponent('http://jiankang/tuan')+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到百度收藏"> <img src="{{$imgBaseUrl}}/images/shop/share_baidu.gif" alt="分享到百度收藏"/> </a>
<a href="javascript:window.open('http://bai.sohu.com/share/blank/addbutton.do?title='+encodeURIComponent('团购平台-垦丰电商 -专业的种子商城，品质保证')+'&link='+encodeURIComponent('http://jiankang/tuan')+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到搜狐白社会"> <img src="{{$imgBaseUrl}}/images/shop/share_bai.gif" alt="分享到搜狐白社会"/> </a>
<a href="javascript:window.open('http://www.douban.com/recommend?title='+encodeURIComponent('团购平台-垦丰电商 -专业的种子商城，品质保证')+'&link='+encodeURIComponent('http://jiankang/tuan')+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到豆瓣"> <img src="{{$imgBaseUrl}}/images/shop/share_dou.gif" alt="分享到豆瓣"/> </a>

</div>
<div class="question grayborder"><h2>团购问答Q/A专区：</h2>
<dl>
<dt>Q:我购买了团购商品，同时又选购了其他商品，我该如何付款？</dt>
<dd>A:团购商品可与普通商品一起购买，直接添加至购物车即可，一张订单，一次送达。</dd>

<dt>Q:团购商品可以使用优惠券吗？</dt>
<dd>A:只要符合优惠券使用条件，即可享受更多优惠。</dd>

<dt>Q:团购商品包邮吗？</dt>
<dd>A:不是每一款团购商品都包邮，具体请参照当期团购商品详细说明。</dd>

<dt>Q:购买团购商品有积分吗？</dt>
<dd>A:购买垦丰电商团购商品同样可以获得积分，详细请参照积分说明。</dd>

<dt>Q:团购商品一般几天发货？</dt>
<dd>A:如无特殊情况，团购商品的发货流程与普通商品一样，订单发货后预计1-6天送达。</dd>
</dl>
</div>
</div><!--rig end-->
<div class="cleardiv"></div>
</div>
{{include file="footer.tpl"}}

<script type="text/javascript">

$(function(){
$(".con table.buy-table tr:odd").css("background-color","#eee")
})
//加入购物车
function addCart(id){
	var productSn = '{{$data.goods_sn}}';
	$.ajax({
		url:'/goods/check',
		data:{product_sn:productSn,number:1},
		type:'get',
		success:function(msg){
			if (msg != ''){
				alert(msg);
				window.location.replace('/tuan/view/id/{{$data.tuan_id}}');
			}else{
				window.location.replace('/flow/buy/product_sn/'+productSn+'/number/1');
			}
		}
	})
}
</script>
