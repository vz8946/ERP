<style type="text/css">
.discount{margin: 10px auto 0;width: 990px;}
</style>
<div class="discount">
{{$top.content}}
<div style="clear:both;"></div>
{{if $top.sort eq 1}}
<div class="order clearfix" >
        <div id="ordernow" class="mt"><h2>快速在线订购</h2></div>
        <div class="mc clearfix">
            <div class="mc_l">
                <p>
			<img src="{{$imgBaseUrl}}/Public/img/610023.jpg" alt=""><img src="{{$imgBaseUrl}}/Public/img/610021.jpg" alt=""><img src="{{$imgBaseUrl}}/Public/img/610022.jpg" alt=""></p>
		</div>
            <div class="mc_r">
                <p class="order_t1"><a></a></p>
                <div class="order_phone">
                    <p>
	<img src="{{$imgBaseUrl}}/Public/img/610018.jpg" alt=""><img src="{{$imgBaseUrl}}/Public/img/610019.jpg" alt=""><img src="{{$imgBaseUrl}}/Public/img/610020.jpg" alt=""></p>
</div>
                <p class="order_t2"><a></a></p>

                <div class="online_order">
                    <p class="tips">为确保您订购商品无误，以下信息请准确填写。<br>
                        我们承诺所有客户信息均严格保密（保密条款），带*号项为必填项。
                    </p>

                    <div class="form">
                     <form onsubmit="return checkf();" method="post" action="/flow/add-order" id="form1">
                           <input type="hidden" name="fastTackBuy" id="fastTackBuy" value="1" />
                            <ul>
                               <li id="suit_1" style="color: #545353;margin-top: 10px;"><label>选择套餐：</label>
									<select name="selectSuit" id="selectSuit">
										{{foreach from=$gooslist item=item}}
											{{if empty($item.goods_sn)}}  <option value="{{$item.group_id}}">{{$item.goods_name}}</option> {{else}}  <option value="{{$item.goods_sn}}">{{$item.goods_name}} {{/if}}

										{{/foreach}}

									</select><br />
	<br/><label >套餐数量： </label><input name="suitnum" id="suitnum" type="text" class="text" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"/></li>

                                <li class="field">
                                    <label>真实姓名： </label>
                                    <input type="text" name="consignee" id="consignee">
                                    <span>*</span>您的真实姓名
                                </li>
                                <li class="field">
                                    <label>联系电话： </label>
                                    <input type="text" name="phone" id="phone">
                                    <span>*</span>联系电话是手机或座机，座机请加区号
                                </li>
                                 <li class="field">
                                    <label>省&nbsp;&nbsp;&nbsp;&nbsp;份： </label>
                                    <select id="province" name="province_id" onchange="getArea(this)">
									<option value="">请选择省份...</option>
									{{foreach from=$province item=p}}
										<option value="{{$p.area_id}}" {{if $p.area_id==$address.province_id}}selected{{/if}}>{{$p.area_name}}</option>
									{{/foreach}}
								</select>
								<select id="city" name="city_id" onchange="getArea(this)">
									<option value="">请选择城市...</option>
								   {{if $province}}
									{{foreach from=$city item=c}}
									<option value="{{$c.area_id}}" {{if $c.area_id==$address.city_id}}selected{{/if}}>{{$c.area_name}}</option>
									{{/foreach}}
								   {{/if}}
								</select>
								<select id="area" name="area_id" onchange="$('phone_code').value=this.options[this.selectedIndex].getAttribute('class')?this.options[this.selectedIndex].getAttribute('class'):this.options[this.selectedIndex].getAttribute('title');">
									<option value="">请选择地区...</option>
									{{if $city}}
									{{foreach from=$area item=a}}
									<option value="{{$a.area_id}}" {{if $a.area_id==$address.area_id}}selected{{/if}}>{{$a.area_name}}</option>
									{{/foreach}}
									{{/if}}
								</select>
                                    <span>*</span>必填
                                </li>

                                <li class="field">
                                    <label>收货地址： </label>
                                    <input type="text" name="address" class="put2" id="address">
                                    <span>*</span>必填
                                </li>
                                <li class="field">
                                    <label>最佳联系时间： </label>
                                    <input type="text" value="填写您最方便的签收时间，便于快递人员与您联系" class="put2 f2" name="description" id="description">
                                </li>
								<li style="color: #545353;margin-top: 10px;"><strong>支付方式：</strong><input name="pay_type" id="zxzf" type="radio" value="alipay" /> 在线支付 <input name="pay_type" type="radio" id="hdfk" value="cod" checked/> 货到付款</li>

                            </ul>
                            <div class="submit">
                                <button type="submit"></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{/if}}
</div>

{{if $top.sort eq 1}}
<script type="text/jscript" src="{{$imgBaseUrl}}/Public/js/otherPd.js"></script>
<script language="javascript">
/*检查是否登录 return bool*/
function checkLogin(){
	var s=false;
	$.ajax({
		url:'/flow/check-login',
		type:'post',
		dataType:'json',
		success:function(msg){
			if(msg.status=='yes'){s=true;}
		},
		async:false
	})
	return s;
}
//检查表单 & 添加商品到购物车
function checkf(){
	if(checkLogin()){
		$('#fastTackBuy').val(0);
	}

	if($.trim($('#consignee').val())==''){
		alert('请填写真实姓名！');
		return false;
	}

	if($.trim($('#phone').val())==''){
		alert('请填写电话号码或手机！');
		return false;
	}

	var province=$.trim($('#province').val());
	if(province=='' || /\D+/.test(province)){
		alert('请选择省份！');
		return false;
	}

	var city=$.trim($('#city').val());
	if(city=='' || /\D+/.test(city)){
		alert('请选择城市！');
		return false;
	}

	var area=$.trim($('#area').val());
	if(area=='' || /\D+/.test(area)){
		alert('请选择地区！');
		return false;
	}

	if($.trim($('#address').val())==''){
		alert('请填写详细地址！');
		return false;
	}

	if( $.trim($('#suitnum').val())==''){
		alert('请填写购买数量111！');
		return false;
	}
	if( $.trim($('#suitnum').val())> 9){
		alert('最多购买9个！');
		return false;
	}

	//start:支付方式检查
	var payType;
	var count=0;
	payType=document.getElementsByName("pay_type");
	for(i=0;i<payType.length;i++){
		if (payType[i].checked){
			count=count+1;
		}
	}
	if(count==0){
		alert('请选择支付方式');
		return false;
	}
	//end:支付方式检查
	$('.sub').attr('disabled',true);
	//先清空购物车
	clearCart();
	//在这里加入购物车
	var suitType=1;
	//0购买单盒

	var selgoods = $('#selectSuit').val();
	if(selgoods.indexOf("N") == -1){
		//套组
			if(addGroupCart(selgoods,$('#suitnum').val())){
				//alert('订购成功！');
			}else{
				$('.sub').attr('disabled',false);
				return false;
			}
	}else{
		//单品
		if(addCartForm(selgoods,$('#suitnum').val())){
			//alert('订购成功！');
		}else{
			$('.sub').attr('disabled',false);
			return false;
		}
	}

}
//添加组合商品
function addGroupCart(g_id,num){
	var s=false;

	var tmp=parseInt(g_id);
	if(tmp<1)return false;

	var num=parseInt(num);
	if(num<1)return false;

	if(num>20 || num<1)return false;
	$.ajax({
        url:'/group-goods/check',
        data:{group_id:tmp,number:num},
        type:'post',
        success:function(msg){
            if (msg == ''){
                s=true;
            }
        },
		async:false
    });
	return s;
}
//清空购物车
function clearCart(){
	jQuery.ajax({
		url:'/flow/ajaxclear',
		success:function(data){
		},
		async:false
	})
}
//添加到购物车函数
function addCartForm(goods_sn,number){
	var s=false;
	$.ajax({
		url:'/flow/actbuy',
		type:'get',
		data:{product_sn:goods_sn,number:number},
		success:function(data){
			if(data == ''){
				s=true;
			}
		},
		async:false
	})
	return s;
}

//联动
function getArea(id){
	var value=id.value;
	$(id).parent().children('select:last')[0].options.length = 1;
	$(id).next('select')[0].options.length=1;
	$.ajax({
		url:'/flow/list-area-by-json',
		data:{area_id:value},
		dataType:'json',
		success:function(msg){
			var htmloption='';
			$.each(msg,function(key,val){
				htmloption+='<option value="'+val['area_id']+'" class="'+val['code']+'" title="'+val['code']+'">'+val['area_name']+'</option>';
			})
			$(id).next('select').append(htmloption);
		}
	})
}
</script>
{{/if}}