<div class="bg_yezonghui">
	<div class="main_yezonghui">
    	<div class="top">
        	<h2>天黑不打烊 夜市更疯狂<br /><span>每晚一场疯狂抢购盛宴，晚上18点持续到次日2点，在此期间购买商品，价格真的跟白天不一样，夜间总有优惠！</span></h2>
        	<div class="time">
            	<div class="daojishi">
                	<span>还有&nbsp;</span>
                	<div class="wrap_time" id="market-clock"><i class="hour">00</i><em>:</em><i class="minute">00</i><em>:</em><i class="second">00</i></div>
                	<span>&nbsp;{{if $isStart}}结束{{else}}开始{{/if}}</span>
                </div>
                <p><span>晚间惠聚</span>&nbsp;&nbsp;&nbsp;&nbsp;18：00~次日2:00</p>
            </div>
        </div>
        <div class="ad">{{widget class="AdvertWidget" id="21"}}</div>
        
      {{if $fixedProducts}}  
      <div class="productList">
        	<div class="title"><img src="{{$_static_}}/images/shop/title01.jpg" width="585" height="46" /></div>        
            <ul>
            	{{foreach from=$fixedProducts item=item}}
            	<li>
                	<div class="w_product">
                        <a href="/b-{{$item.as_name|default:'jiankang'}}/detail{{$item.goods_id}}.html"  title="{{$item.goods_name}}" target="_blank"><img alt="{{$item.goods_name}}" src="{{$imgBaseUrl}}/{{$item.goods_img|replace:'.':'_380_380.'}}" width="204" height="186" /></a>
                        <p class="gongxiao">{{$item.goods_alt|default:'&nbsp;&nbsp;'}}</p>
                        <p title="{{$item.goods_name}}">{{$item.goods_name}}</p>
                        <p>垦丰价：<em>￥{{$item.price}}</em></p>
                        <p><span>夜惠价：</span><br /><b>{{$item.now_price|intval}}.<i>00</i></b></p>
                        <div class="btn">
                        {{if $isStart}}
                         <a href="javascript:;" onclick="addCart('{{$item.goods_sn}}','buy_cart');"><img src="{{$_static_}}/images/shop/btn_buy01.png" width="132" height="34" /></a>
                        {{else}}
                         <a href="javascript:;" onclick="marketNotice('{{$item.goods_id}}','{{$item.goods_name}}','{{$offerType.fixed}}');"><img width="132" height="34" src="http://jiankang/_static/images/shop/btn_warn.png" /></a>
                        {{/if}}
                        </div>
                    </div>
                </li>  
                {{/foreach}}          
            </ul>
        </div>
      {{/if}}  
      <!-- 
     <div class="productList">
        	<div class="title"><img src="{{$_static_}}/images/shop/title02.jpg" width="585" height="46" /></div>
            <ul>
            	<li>
                	<div class="w_product">
                        <a href="#"><img src="{{$_static_}}/images/shop/product.jpg" width="204" height="186" /></a>
                        <p class="gongxiao">抗皱 滋养 功效</p>
                        <p>乃琦斯维生素C咀嚼片香橙味(100片)</p>
                        <p>市场价：<em>￥228</em></p>
                        <p><span>夜惠价：</span><br /><b>1564.<i>00</i></b></p>
                        <div class="btn"><a href="#"><img src="{{$_static_}}/images/shop/btn_buy01.png" width="132" height="34" /></a></div>
                    </div>
                </li>             
            </ul>
        </div>
      --> 
      {{if $discountProducts}}
        <div class="productList">
        	<div class="title"><img src="{{$_static_}}/images/shop/title03.jpg" width="585" height="46" /></div>
            <ul>
            	{{foreach from=$discountProducts item=item}}
            	<li>
                	<div class="w_product">
                        <a href="/b-{{$item.as_name|default:'jiankang'}}/detail{{$item.goods_id}}.html"  title="{{$item.goods_name}}" target="_blank"><img alt="{{$item.goods_name}}" src="{{$imgBaseUrl}}/{{$item.goods_img|replace:'.':'_380_380.'}}" width="204" height="186" /></a>
                        <p class="gongxiao">{{$item.goods_alt|default:'&nbsp;&nbsp;'}}</p>
                        <p title="{{$item.goods_name}}">{{$item.goods_name}}</p>
                        <p>垦丰价：<em>￥{{$item.market_price}}</em></p>
                        <p><span>夜惠价：</span><br /><b>{{$item.price|@intval}}.<i>00</i></b></p>
                        <div class="btn">
                        {{if $isStart}}
                         <a href="javascript:;" onclick="addCart('{{$item.goods_sn}}','buy_cart');"><img src="{{$_static_}}/images/shop/btn_buy01.png" width="132" height="34" /></a>
                        {{else}}
                         <a href="javascript:;" onclick="marketNotice('{{$item.goods_id}}','{{$item.goods_name}}','{{$offerType.discount}}');"><img width="132" height="34" src="http://jiankang/_static/images/shop/btn_warn.png" /></a>
                        {{/if}}
                        </div>
                    </div>
                </li>  
                {{/foreach}}          
             
            </ul>
        </div>
      {{/if}}
</div>  
</div>

<script id="notice-tpl" type="text/x-jsmart-tmpl">
<div class="tc_warn">
	       <div class="con">
	          <p>设置开市提醒之后，系统将在开始前10分钟，以短信方式通知您，以免错过最佳抢购时机。</p>	         
	          <p>
               <span id="notice-msg" class="c2" style="padding-left:100px;"></span><br/>
                                    您的手机号码<input name="mobile" id="mobile" type="text" value="" /> 
              <input  type="hidden" name="goods_id" id="goods_id" value="{$goods_id}"/>
              <input  type="hidden" name="offer_id" id="offer_id" value="{$offer_id}"/>
              <input  type="hidden" name="goods_name" id="goods_name" value="{$goods_name}"/> 
	          <a href="javascript:;" onclick="send_marketNotice();"><img src="{{$_static_}}/images/shop/btn_submit.jpg" width="70" height="29" /></a></p>
	        
  </div>
 </div> 
</script>    
<script type="text/javascript">
countDown("{{$time}}",null,"#market-clock .hour","#market-clock .minute","#market-clock .second");
var win=null;
function marketNotice(goods_id,goods_name,offer_id){ 
   var  content = $('#notice-tpl').html();
   var tpl = new jSmart( content );
   var data = {goods_id:goods_id,offer_id:offer_id,goods_name:goods_name};
   var res = tpl.fetch(data);
   win =  $.dialog({id:'market-notice',width:400,title:'开市提醒',content:res,fixed:true, lock:true}); 
}

function send_marketNotice()
{	
	var goods_id = $.trim($('#goods_id').val());
	var goods_name = $.trim($('#goods_name').val()); 
	var offer_id = $.trim($('#offer_id').val());
	var mobile = $.trim($('#mobile').val());
	if(mobile == '' ){
		$("#notice-msg").html("请输入手机号！");
		return false;
	}	
	
	if(!Check.isMobile(mobile)){
		$("#notice-msg").html('请填写正确的手机号码！');
		return false;
	}
	
	$("#notice-msg").html('正在发送请求，请稍后……');
	$.post('/market/notice',{goods_id:goods_id,goods_name:goods_name,offer_id:offer_id,mobile:mobile},function(data){
		if(data.status == 1)
		{
			win.close();	
			$.dialog.alert(data.msg).time(2000);			
		}else{
			$("#notice-msg").html(data.msg);
		}
	},'json');
	
}
</script>