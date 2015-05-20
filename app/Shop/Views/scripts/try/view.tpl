<link href="/styles/shop/try.css" rel="stylesheet" type="text/css" />
<div class="goods mar">
<h3>{{$try_data.try_goods_name}}</h3>
<div class="infro clear">
<div class="l">
	<a href="/goods-{{$try_data.goods_id}}.html"  target=_blank><img src="{{$imgBaseUrl}}/{{$try_data.img1}}" class="uppic"/></a>
	<div class="see"><a href="/goods-{{$try_data.goods_id}}.html" target=_blank>查看商品详情</a></div>
	<div class="share">
	您也可以分享到：
	<a href="javascript:window.open('http://v.t.sina.com.cn/share/share.php?title='+encodeURIComponent(document.title)+'&url={{$snsUrl}}&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" class="t-sina" title="分享到新浪微博"> <img src="{{$imgBaseUrl}}/images/sns_01.jpg"/></a>
	<a href="javascript:(function(){window.open('http://v.t.qq.com/share/share.php?title='+encodeURIComponent(document.title)+'&url={{$snsUrl}}&source=bookmark','_blank','width=610,height=350');})()" class="t-qq" title="分享到QQ微博"> <img src="{{$imgBaseUrl}}/images/sns_03.jpg"/></a>
	<a href="javascript:window.open('http://www.kaixin001.com/repaste/share.php?rtitle='+encodeURIComponent(document.title)+'&rurl={{$snsUrl}}&rcontent=看中一个好东东，很好看，是垦丰电商的'+encodeURIComponent(document.title)+' 亲爱的您也看下吧'+encodeURIComponent(location.href),'_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" class="kaixin" title="分享到开心网"><img src="{{$imgBaseUrl}}/images/sns_06.jpg"/></a>
	<a href="javascript:window.open('http://cang.baidu.com/do/add?it='+encodeURIComponent(document.title)+'&iu={{$snsUrl}}&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到百度收藏"><img src="{{$imgBaseUrl}}/images/sns_04.jpg"/></a>
	<a href="javascript:window.open('http://bai.sohu.com/share/blank/addbutton.do?title='+encodeURIComponent(document.title)+'&link={{$snsUrl}}&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到搜狐白社会"><img src="{{$imgBaseUrl}}/images/sns_09.jpg"/></a>				
	<a href="javascript:window.open('http://www.douban.com/recommend?title='+encodeURIComponent(document.title)+'&link={{$snsUrl}}&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到豆瓣"><img src="{{$imgBaseUrl}}/images/sns_08.jpg"/></a>
<p>邀请好友参加活动最高可获得5000积分，每邀请一个成功参加活动可获得200积分（100积分=1元），登陆后才能记录推荐业绩哦</p>
</div></div><!--l end-->

<div class="r">
<div class="common one">
<div class="price"><span>市场价：¥ <em>{{$goods_data.price}}</em></span><span  class="red">免费提供：{{$try_data.num}}份</span></div>
<ul><li>活动时间：{{$try_data.start_time_str}}  至 {{$try_data.end_time_str}}</li>
<li><div id="try_left_time"></div></li>
<li>试用类型：<span class="red">免费申请，试用品无需返还，需提交试用报告</span></li>
<li>试用邮费：包邮</li></ul>
<div class="submit">
{{assign var="button_class" value=""}}
{{assign var="button_href" value=""}}
{{if $isLogin}}
  {{if $order_data eq '' }}
    {{if $try_data.inscope eq 1 }}
      {{assign var="button_class" value="pic"}}
      {{assign var="button_href" value="href='/try/buy/id/$id'"}}
    {{else}}
      {{assign var="button_class" value="pic er san si"}}
    {{/if}}
  {{else}}
    {{if $order_data.status eq 0}}
      {{assign var="button_class" value="pic er san"}}
    {{elseif $order_data.status eq 1}}
      {{assign var="button_class" value="pic er san si wu liu"}}
    {{elseif $order_data.status eq 2}}
      {{assign var="button_class" value="pic er san si wu"}}
    {{elseif $order_data.status eq 3}}
      {{assign var="button_class" value="pic er"}}
      {{assign var="button_href" value="href='/member/try-order-report/try_order_id/$try_order_id'"}}
    {{elseif $order_data.status eq 4}}
      {{assign var="button_class" value="pic er san si wu liu qi"}}
    {{/if}}
  {{/if}}
{{else}}
  {{if $try_data.inscope eq 1 }}
    {{assign var="button_class" value="pic" }}
    {{assign var="button_href" value="href='/try/buy/id/$id'"}}
  {{else}}
    {{assign var="button_class" value="pic er san si"}}
  {{/if}}
{{/if}}
<a class="{{$button_class}}" {{$button_href}}></a>
<p>已有<strong class="red">{{$try_data.ini_num}}</strong>人申请</p></div>
</div><!--one end-->

<div class="common two">
<div class="price"><span>市场价：¥ <em>{{$goods_data.price}}</em></span><span  class="red">折扣价：¥ {{$goods_discount.price}}</span><strong>{{$goods_discount.discount}}折</strong></div>
<ul><li>立即节省：¥ {{math equation="(x - y)" x=$goods_data.price y=$goods_discount.price}} 元</li>
<li><div id="discount_left_time"></div></li>
</ul>
{{if $goods_offer.inscope eq 1 }}
  <div class="submit"><a class="pic" href="/goods-{{$try_data.goods_id}}.html?a={{$goods_offer.aid}}"></a></div>
{{else}}
  <div class="submit"><a class="pic gray" ></a></div>
{{/if}}
</div><!--two end-->
</div><!--r end-->
</div><!--infro end-->
<div class="tips">
<h4><span>温馨提示</span></h4>
<div>
<p class="red"><strong>完成以下任务，就有机会获得试用品</strong></p>
<em>1</em>详细写出您的申请理由，会增加您成功几率，支持原创；<br />
<em>2</em>分享此次试用活动，会增加您成功几率；好友通过链接参加试用活动，您将获得200积分（最高5000积分,5000积分=50元）；<br />
<em>3</em>收藏垦丰电商网站，会增加您成功几率，也方便下次购物；<br />
<em>4</em>成功参与试用活动可以参加2012年1月5日的试用抽奖，共抽取2名幸运用户各赠送价值1072元的胶原蛋白四盒。<br />
<em>5</em>我们将在您申请后的2个工作日左右审核您的申请，抽取一定数量的用户免费赠送试用品。<br />
<em>6</em>成功提交试用报告可以获得一张100减20元优惠券，不提交将不能参加其他试用活动。</div></div><!--tips end-->
<a name="reply-line" id="reply-line"></a>
<div class="down">
  <ol class="clear">
    <li id="page1" onMouseOver="switchPage(1,0)" onclick="switchPage(1,0)" {{if $type eq 1}}class="selected"{{/if}}>申请理由({{$count1}})<a href="" ></a></li>
    {{if $count2 ne 0}}
      <li id="page2" onMouseOver="switchPage(2,0)" onclick="switchPage(2,0)" {{if $type eq 2}}class="selected"{{/if}}>试用报告({{$count2}})</li>
    {{/if}}
    {{if $count3 ne 0}}
      <li id="page3" onMouseOver="switchPage(3,0)" onclick="switchPage(3,0)" {{if $type eq 3}}class="selected"{{/if}}>试用品发放名单({{$count3}})</li>
    {{/if}}
  </ol>
  {{include file="try/reply-table.tpl"}}
</div>
<script type="text/javascript">
var global_type = {{$type}}
var global_id = {{$id}}
var global_page = {{$page}}

function countdown(id, enddate) {
    var t=new Date();
    var tT=new Date(enddate);
    var sg=(tT.getTime()-t.getTime());
    var lH=sg/3600000;
    var fH=Math.floor(lH);
    var lM=(lH-fH)*60;
    var fM=Math.floor(lM);
    var lS=(lM-fM)*60;
    var fS=Math.floor(lS);
    var fC=Math.floor((lS-fS)*1000);
    var sM=Math.floor(fC/10);
    if (fH < 0) {
        fH = '0';
        fM = '00';
        fS = '00';
    } 
    var showTime = document.getElementById(id).innerHTML='剩余时间：'+'<strong>'+fH +'</strong>'+'小时'+'<strong>'+fM+'</strong>'+'分'+'<strong>'+fS+'</strong>'+'秒';
    window.setTimeout("countdown('"+id+"','" + enddate + "')", 100);
} 
        
countdown('try_left_time', '{{$try_data.end_time_str1}}');
countdown('discount_left_time', '{{$goods_offer.to_date}}');

function switchPage(type, user_id)
{
    for ( i = 1; i <=3; i++ ) {
        page = document.getElementById('page'+i);
        if ( page == null ) continue;
        removeClass.call(page,'selected');
    }
    page = document.getElementById('page'+type);
    if ( page == null ) return false;
    addClass.call(page,'selected');
    
    replyShow(type, global_id, global_page, user_id);
}

function removeClass(value){
    var kls,reg=1>0 &&(kls=this.className,reg=new RegExp('(^| )'+value+'( |$)'),reg.test(this.className) && (this.className=(kls.replace(reg,'$1')).replace(/ $/,'')));    
    return this;   
};   
function addClass(value){   
    var reg=1>0 &&(reg=new RegExp('(^| )'+value+'( |$)'),reg.test(this.className) || (this.className==='' ? (this.className=value) : (this.className+=(' '+value))));   
    return this;   
};   

function replyShow(type, id, page, user_id)
{
	$.ajax({
	    url: '/try/reply-table/type/'+type+'/id/'+id+'/page/'+page+'/user_id/'+user_id+'/r/'+Math.random(),
	    type: 'get',
	    success:function(data){
            document.getElementById('reply_area').innerHTML = data;
	        window.location.hash = "reply_line";
	    }
	});
}
</script>