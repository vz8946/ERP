<link href="/styles/shop/order.css" media="all" rel="stylesheet" type="text/css" />
<div id="orderInfo">
	<div class="yellowBorder zfOldItem">
		<dl>
			<dt>收货人信息<a href="#" class="fontBlue">[修改]</a></dt>
			<dd class="eidtMsg">

				{{if $addressList}}
						{{foreach from=$addressList key=key item=data}}
						<div class="content"><span><a href="/try/set-addr/addr_id/{{$data.address_id}}" title="配送到这个地址">配送到这个地址</a></span>
						<strong>{{$data.consignee}}</strong> {{$data.province_name}} {{$data.city_name}} {{$data.area_name}} {{$data.address}}  {{$data.consignee}} {{$data.phone}}   {{$data.mobile}}
						<a href="/try/addr/addr_id/{{$data.address_id}}/"  class="fontBlue" >修改</a>  <a href="/try/del-addr/addr_id/{{$data.address_id}}">删除</a>
						</div>
						{{/foreach}}
				 {{/if}}	
				<div class="addressEntry">

				<div  class="try_addr_title" ><span><em>填写配送信息</em></span></div>
			    <div class="addressbooktitle" style="margin-bottom:32px; padding:8px 0; border-bottom:1px solid #ccc;">选择或修改配送地址（完成后，点击配送至这个地址按钮）</div>
				<form id="myForm" method="post" action="/try/{{if $anonymous}}set-addr{{else}}edit-add-addr{{/if}}/" onsubmit="return check_form();">
					<input type="hidden" name="address_id" value="{{$address.address_id}}" />
					<table width="100%" id="gotoarea" class="addressbookgo">
						<tr>
							<td width="100" align="right"><span class="red">*</span>收货人姓名：</td>
							<td><input type="text" id="consignee" name="consignee" value="{{$address.consignee}}" /></td>
						</tr>
						<tr>
							<td align="right"><span class="red">*</span>配送区域：</td>
							<td>
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
								{{if $error=='area'}}<font color="red">配送区域没有填写完整，请填写完整。</font>{{/if}}
							</td>
						</tr>
						<tr>
							<td align="right"><span class="red">*</span>详细地址：</td>
							<td><input type="text" id="address" name="address" size="49" value="{{$address.address}}" />&nbsp;请填写详细地址。
							<!--<a href="/help/logistics" target="_blank" style="text-decoration:underline">查看详细配送范围</a>--> </td>
						</tr>
						<tr>
							<td align="right">电话：</td>
							<td><input type="text" id="phone_code" name="phone_code" value="{{$address.phone_code}}" size="5" /> - <input type="text" id="phone" name="phone" value="{{$address.phone}}" /> 电话请附带区号 例：021-3355557</td>
						</tr>
						<tr>
							<td align="right">手机：</td>
						  <td><input type="text" id="mobile" name="mobile" value="{{$address.mobile}}" />			   
							 手机和电话至少必填一项</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td> <input type="image" src="{{$imgBaseUrl}}/images/shop//butPs.jpg" alt="配送到这个地址"></td>
						</tr>
					</table>
				</form>
				</div>
			</dd>
		</dl>
	</div>
</div>


<script language="javascript" type="text/javascript" src="{{$imgBaseUrl}}/scripts/check.js"></script>
<script language="javascript">
function check_form()
{
	if($.trim($('#consignee').val())==''){
		alert('请填写真实姓名！');
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
	
	if( $.trim($('#phone').val())==''  && $.trim($('#mobile').val())==''){
		alert('请填写电话号码或手机！');
		return false;
	}else{
		if ($.trim($('#phone').val()) != '' && !Check.isTel($.trim($('#phone_code').val())+'-'+$.trim($('#phone').val()))) {
			alert('请填写正确的电话号码！');
			return false;
		}
		if ($.trim($('#mobile').val()) != '' && !Check.isMobile($.trim($('#mobile').val()))) {
			alert('请填写正确的手机号码！');
			return false;
		}
	}
}
function getArea(id){
	var value=id.value;
	var uri=filterUrl('/flow/list-area-by-json','area_id');
	$(id).parent().children('select:last')[0].options.length = 1;
	$(id).next('select')[0].options.length=1;
	$.ajax({
		url:uri,
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