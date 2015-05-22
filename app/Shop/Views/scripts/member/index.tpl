<div class="member"> {{include file="member/menu.tpl"}}
  <div class="memberright">
    <div class="welcome"> <span class="last_login_time">最后登录：{{$member.last_login|date_format:"%Y-%m-%d %H:%M:%S"}}</span>{{if $member.nick_name}}{{$member.nick_name}}{{else}}{{$member.user_name}}{{/if}} 欢迎回来 </div>
    <!--我的信息-->
    <div class="member_summary fleft">
      <p class="summary_title"> 我的信息 </p>
      <div class="summary_content">
        <ul>
          <li class="dotline"> 积分：<a class="countnumber" href="/member/point">{{$member.point}}</a> </li>
          <li class="dotline"> 账户余额：<a class="countnumber" href="/member/money">{{$member.money}}</a> </li>
          <li> 可用优惠券：<a class="countnumber" href="/member/coupon">{{$coupons}}</a> </li>
        </ul>
      </div>
    </div>
    <!--最近的订单-->
    <div class="member_summary fleft mleft14">
      <p class="summary_title"> 最近订单 </p>
      <div class="summary_content">
        <ul>
          <li class="dotline"> 成功订单：<a class="countnumber" href="/member/order/ordertype/7">{{$okOrder}}</a> 笔 </li>
          <li class="dotline"> 需付款订单：<a class="countnumber" href="/member/order/ordertype/4">{{$feeOrder}}</a> 笔 </li>
          <li> 取消订单：<a class="countnumber" href="/member/order/ordertype/3">{{$cancelOrder}}</a> 笔 </li>
        </ul>
      </div>
    </div>
    <!--商品收藏-->
    <div class="member_summary fleft">
      <p class="summary_title"> 商品收藏 </p>
      <div class="summary_content"> {{foreach from=$fav item=favlist}}
        <div class="summary_goods fleft">
          <p class="goods_img" style="height: 100px;border: 1px solid #eee;"> <a href="/goods-{{$favlist.goods_id}}.html" target="_blank"><img width="90" height="90"  src="{{$imgBaseUrl}}/{{$favlist.goods_img|replace:'.':'_180_180.'}}" border="0" title="{{$favlist.goods_name}}"/></a> </p>
          <p class="goods_name" style="padding: 5px 0px;"> <a href="/goods-{{$favlist.goods_id}}.html" target="_blank" title="{{$favlist.goods_name}}">{{$favlist.goods_name|cn_truncate:6:"..."}}</a> </p>
        </div>
        {{/foreach}} </div>
    </div>
    <!--最近浏览记录-->
    <div class="member_summary fleft mleft14">
      <p class="summary_title" style="height:16px;"> <span style="float: left;">最近浏览记录</span> <span style="float: right;"><a onclick="clearCook('/clearhistory.html',this);" href="javascript:void(0);">清空</a></span> </p>
      <div class="summary_content"> {{foreach from=$history item=historylist}}
        <div class="summary_goods fleft">
          <p class="goods_img" style="height: 100px;border: 1px solid #eee;"> <a href="/goods-{{$historylist.goods_id}}.html" target="_blank"><img width="90" height="90" src="{{$imgBaseUrl}}/{{$historylist.goods_img|replace:'.':'_180_180.'}}" border="0" title="{{$historylist.goods_name}}"/></a> </p>
          <p class="goods_name" style="padding: 5px 0px;"> <a href="/goods-{{$historylist.goods_id}}.html" target="_blank" title="{{$historylist.goods_name}}">{{$historylist.goods_name|cn_truncate:6:"..."}}</a> </p>
        </div>
        {{/foreach}} </div>
    </div>
    <div class="clear"></div>
    <!----> 
  </div>
  <div style="clear: both;"></div>
</div>
<script>
  	//清空浏览记录
		function clearCook(url,elt) {
			$.ajax({
				type : "GET",
				cache : false,
				url : url,
				success : function(msg) {
					$(elt).parent().parent().parent().find('.summary_content').empty().html("<div style='color:#999999;padding:10px;'>暂无浏览记录！</div>");;
				}
			});
		}
</script> 
