<!--goTop Start-->
<div id="goTop">
 	<img src="{{$_static_}}/images/gotop.gif" alt="gotop" title="gotop" />
</div>
<div class="{{if $is_index_page}}wbox_1200{{else}}wbox990{{/if}} footer" style="padding-top: 10px;clear:both;">
  <div class="helper">
     <dl class="fore1">
       <dt><b></b><span>新手上路</span></dt>
       <dd><a href="/help/detail-31.html">购物指南</a></dd>
       <dd><a href="/help/detail-32.html">会员制度</a></dd>
       <dd><a href="/help/detail-33.html">积分获取与使用</a></dd>
       <dd><a href="/help/detail-34.html">售后服务</a></dd>
     </dl>
     <dl class="fore2">
       <dt><b></b><span>配送方式</span></dt>
       <dd><a href="/help/detail-5.html">配送范围及时间</a></dd>
       <dd><a href="/help/detail-6.html">商品验货与签收</a></dd>
       <dd><a href="/help/detail-25.html">订单查询</a></dd>
	   <dd><a href="/help/detail-28.html">收货人号码保密</a></dd>
     </dl>
     <dl class="fore3">
       <dt><b></b><span>支付方式</span></dt>
       <dd><a href="/help/detail-8.html">货到付款</a></dd>
       <dd><a href="/help/detail-9.html">在线支付</a></dd>
       <dd><a href="/help/detail-10.html">购物卡使用规则</a></dd>
     </dl>
     <dl class="fore4">
       <dt><b></b><span>售后服务</span></dt>
       <dd><a href="/help/detail-11.html">订单跟踪</a></dd>
       <dd><a href="/help/detail-12.html">退换货流程</a></dd>
       <dd><a href="/help/detail-13.html">退款说明</a></dd>
       <dd><a href="/help/detail-15.html">防伪查询</a></dd>
     </dl>
     <dl class="fore5">
       <dt><b></b><span>帮助中心</span></dt>
       <dd><a href="/help/detail-16.html">常见问题</a></dd>
       <dd><a href="/help/detail-26.html">找回密码</a></dd>
       <dd><a href="/help/detail-18.html">联系客服</a></dd>
       <dd><a href="/help/detail-20.html">注册协议</a></dd>
     </dl>
     <dl class="fore6">
       <dt><b></b><span>合作专区</span></dt>
       <dd><a href="/help/detail-21.html">成为供应商</a></dd>
       <dd><a href="/help/detail-23.html">商务合作</a></dd>
       <dd><a href="/help/detail-22.html">诚聘英才</a></dd>
     </dl>
  </div>
  <div><img width="{{if $is_index_page}}1201{{else}}991{{/if}}" src="{{$imgBaseUrl}}/images/shop/bottom.jpg"/></div>
  <div class="menu">
     <a href="/">首页</a> |
     <a href="/news">资讯中心</a> |
     <a href="/help/detail-19.html">关于我们</a> |
     <a href="/help/detail-18.html">联系客服</a>|
     <a href="/help/detail-22.html">诚聘英才</a>|
     <a href="/help/detail-23.html">商务合作</a>|
	 <a href="http://news.1jiankang.com/"  target="_blank">垦丰资讯</a>
   </div>
   <div class="copyright">垦丰 All Rights Reserved 2015  垦丰电商平台 <br>
    ICP证:<a rel="nofollow" target="_blank" href="#"> 京ICP备102045457号 </a><br>
   </div>
   <small class="footer_pic_link">
      <a href="#"><img src="{{$imgBaseUrl}}/Public/img/footer_pic1.jpg"></a>
      <a rel="nofollow" target="_blank" href="#"><img src="{{$imgBaseUrl}}/Public/img/280053.gif"></a>   
   </small>
</div>
<script type="text/jscript" src="{{$imgBaseUrl}}/Public/js/o_code.js"/></script>
{{if !$is_index_page}}
<style>.helper dl {display: inline;float: left;width: 155px;}</style>
{{/if}}
<script type="text/javascript" charset="utf-8">
//调用返回顶部
$("#goTop").goTop({right:10,bottom:30});
$("img").lazyload();
</script>

</body>
</html>