
<div class="healthCard">
	<div class="ad"><img src="{{$_static_}}/images/ad.jpg" width="990" height="249" /></div>
    <div class="card_intro">
   	  <h2>垦丰电子卡</h2>
      <p class="info">垦丰储值卡（以下简称“垦丰卡”）是电子卡，可用于购买集团垦丰商城销售的所有实体商品及体检套餐，且可抵扣相关运费、手续费，可与其他优惠同时享受。
垦丰卡为固定金额预储值卡，充值金额分3000元、5000元、10000元、20000元四档，根据充值金额享受不同数额的额外增值。</p>
     <form id="frmCard" action="/giftcard/buy" method="post">
        <ul class="cardList">
        {{foreach from=$datas item=item}}
            <li>
                <img src="{{$imgBaseUrl}}/{{$item.goods_img}}" width="231" height="150" alt="{{$item.goods_name}}"  title="{{$item.goods_name}}"/>
                <p title="{{$item.goods_name}}">{{$item.goods_name|cut_str:35}}</p>
                <div class="num">
                 <span>￥{{$item.price}}</span>
                 <em><i>购买数量</i><a href="javascript:;" class="reduce">-</a><input name="giftcard[{{$item.product_id}}]"  price="{{$item.price}}"  type="text" value="{{$giftcard[$item.product_id]|default:'0'}}" /><a href="javascript:;" class="add">+</a></em>
                </div>
            </li>
       {{/foreach}} 
        </ul>
        <div class="totalprice">合计：<em id="order_price">0</em> 元&nbsp;&nbsp;<a href="javascript:;" onclick="$('#frmCard').submit();"><img src="{{$_static_}}/images/btn_balance.jpg" width="132" height="31" /></a></div>
       </form> 
        
        <div class="help">
        	<ul class="tab">
            	<li class="active"><a href="javascript:;">购卡须知</a></li>
                <li><a href="javascript:;">垦丰卡使用规则</a></li>
                <li><a href="javascript:;">退卡须知</a></li>
                <li><a href="javascript:;">常见问题</a></li>
            </ul>
            <ul class="tab_con">
            	<li style="display:block;">1、在线购买垦丰卡成功后，系统将卡号和密码发送至顾客的个人垦丰账户中心，您可在“我的垦丰”中随时查询。电话购买的顾客切记询问客服卡号和密码，以作记录。
<br>
2、购买垦丰卡可支持开具增值税普通发票。如需发票，请在购买过程中勾选“普通发票”，电话购买的顾客请主动告知客服，发票将随您购买的商品一同寄出。<br>

3、网站上垦丰卡不可与其他商品同时购买必须单独下单，且只支持在线支付。如需货到付款，请联系客服电话下单。<br>

4、垦丰卡账户余额不可再用于购买垦丰卡。</li>
                <li>

1、垦丰卡可与垦丰商城会员账户进行绑定，使用时无须再输入卡号和密码，直接选择即可。已绑定的垦丰卡只能被当前账号使用，不能跨账号使用，且不支持解除绑定功能。<br>

2、垦丰卡可用于购买垦丰商城销售的所有商品，可与其他优惠一起使用。<br>

3、下单时，每笔订单可用多张垦丰卡支付，不足部分以现金补足或在线支付。<br>

4、垦丰卡有效期自销售之日起3年，有效期内可重复使用，延期将自动失效。垦丰卡暂不支持充值功能。<br>

5、垦丰卡不记名、不挂失、不兑换现金，请妥善保管卡号密码。<br>

6、垦丰卡销售时若开具过发票给顾客，之后购买商品时垦丰卡支付金额部分将不再开具发票；<br>

7、发生拒收或退货时，垦丰卡支付金额部分将自动退回卡内，有效期不变。<br>

8、顾客可登录集团垦丰商城“我的垦丰”-“我的垦丰卡”页面查询垦丰卡的卡号、余额、有效期等使用情况。<br>

9、集团垦丰商城拥有垦丰卡的最终解释权。
</li>
                <li>

1、垦丰卡一经售出，原则上不予以退换；<br>

2、特殊原因需要退卡，请通过热线电话联系客服，暂不支持在线自助退卡操作。<br>

3、一旦发生退卡，将在卡内余额基础上扣减以下几项，作为退回金额：（1）办卡时额外赠送的增值金额；（2）顾客如有参与针对垦丰卡支付的回馈活动，所享购物优惠一律按照原价折算，差额部分扣减卡内余额。<br>

4、退卡申请成功后垦丰卡卡号和密码将立即失效，无法撤回。<br>
</li>
            	<li>
Q：购买垦丰卡可以开具其他内容的发票吗？<br />
A：购买垦丰卡时可提供发票，但后续用该卡购买商品时将不可以开具发票。<br /><br />

Q：使用垦丰卡和其他方式混合支付的订单，产生退货退款时钱款会退到哪个账户？<br />
A：遵循“退回原支付方式”的原则，垦丰卡支付的金额将退回卡内余额，有效期不变，其余部分退至账户余额或以银行转账等方式退回。<br /><br />

Q：垦丰卡的有效期是多长时间？<br />
A：垦丰卡自激活之日起三年内有效，过期无效。有特别规定的以特别规定为准。<br /><br />

Q：购买垦丰卡能否使用货到付款？<br />
A：抱歉，目前在线购买垦丰卡只支持网上支付，如需货到付款，请联系客服电话下单400 603 3883。<br /><br />

Q：垦丰卡余额用不完能退吗？<br />
A：垦丰卡不记名、不挂失、不兑现，原则上一经售出不予退换，如有特殊原因请联系客服处理。<br /><br />

Q：垦丰卡如何使用？<br />
A：在垦丰网购物结算时，选择使用垦丰卡余额支付即可。<br /><br />

Q：多张垦丰卡能否同时使用？<br />
A：可以，提交订单时可输入多张垦丰卡。系统将按照垦丰卡的输入次序依次使用垦丰卡。<br /><br />

Q：垦丰卡的金额不足以支付订单金额怎么办？<br />
A：不足部分金额可以其它方式支付，如支付宝、网上银行或货到付款等。<br /><br />

Q：垦丰卡可以多次使用吗？<br />
A：是的，有效期内垦丰卡可多次使用，直至金额抵扣完毕。<br /><br />

Q：使用垦丰卡购物是否有限制吗？<br />
A：垦丰卡可用于购买垦丰商城的任何实体商品及体检卡，也可抵扣运费、手续费等。<br /><br />

Q：垦丰卡能与优惠券一起使用吗？<br />
A：是的，垦丰卡可与优惠券一起使用。<br /><br />

Q：将垦丰卡与账号绑定有什么好处？<br />
A：首先：“绑定垦丰卡”后，使用垦丰卡时无需输入卡号，系统将直接显示您已绑定的所有垦丰卡。您只需选择直接勾选相应垦丰卡，即可成功使用；<br />
　　其次：“绑定垦丰卡”后，您可在“我的垦丰卡”里查看垦丰卡的所有使用记录；<br />
　　此外：“绑定垦丰卡”后，其它账户将无法使用该垦丰卡，这将使您的垦丰卡更加安全。<br />
提示：垦丰卡一旦绑定，不支持解除绑定。<br /><br />

Q：垦丰卡可以充值吗？<br />
A：抱歉，垦丰卡是固定面值储值卡，暂不支持充值。
            	
            </li>
            </ul>
        </div>
   <script type="text/javascript">
		$(function(){
			$(".reduce").click(function(){
				var num=$(this).siblings("input").val();
				if(num>0){
					$(this).siblings("input").val(parseInt(num)-1);
					giftCardPrice();
				}				
			});
			
			$(".add").click(function(){
				var num=$(this).siblings("input").val();
				if(num<99){
					$(this).siblings("input").val(parseInt(num)+1);
					giftCardPrice();
				}				
			});
			
			$(":input[name^='giftcard']").live('change',function(){	
				var num= $(this).val();
				if(!Check.isInt(num)){
					alert("请输入整数");
					$(this).val(0); 
					num = 0; 
				}				
				if(num>=0 && num<=100){
					giftCardPrice();
				}else if(num>100){
				 $(this).val(100);
				  return;	
				}	
			});
						
			$(".healthCard .tab li").click(function(){
				$(this).addClass("active").siblings().removeClass("active");
				var index=$(".healthCard .tab li").index(this);
				$(".healthCard .tab_con li").eq(index).show().siblings().hide();
			});
			
			
			
			giftCardPrice();
			function giftCardPrice()
			{
				var amount = 0;
				$(":input[name^='giftcard']").each(function(){
					var num = $(this).val();
					var price = $(this).attr('price')
					amount += num*price;
				});				
				$("#order_price").html(amount.toFixed(2));
			}
			
		})
		</script>
    </div>

</div>