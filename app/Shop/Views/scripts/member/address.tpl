<div class="member">

    {{include file="member/menu.tpl"}}
  <div class="memberright">
     <div class="memberddbg">
	 <p>	您最多可填写<span class="highlight">5</span>个收货地址 </p>
	</div>
 <div class="righttitle"><img src="{{$imgBaseUrl}}/images/shop/member_address.png"></div>
 <div class="memberjebg" >

         <form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post" onsubmit="return addressSubmit()" target="ifrmSubmit">
        	<table width="742" cellpadding="0" cellspacing="1"  class="publictable tborder"  id="address_form" >
                <input type="hidden" name="address_number" id="address_number" value="5" />
                {{if $memberAddress}}
                {{foreach from=$memberAddress item=address name=address}}
                <tbody>
                    <tr>
                        <td width="12%" height="30"><strong>配送区域</strong></td>
                        <td width="40%" height="30">
                            <select name="address[province][]" onchange="getArea(this)">
                                <option value="">请选择省</option>
                    	        {{html_options options=$province selected=$address.province_id}}
                            </select>
                            <select name="address[city][]" onchange="getArea(this)">
                                <option value="">请选择市</option>
                    	        {{html_options options=$address.city_option selected=$address.city_id}}
                            </select>
                            <select name="address[area][]">
                     			 <option value="">请选择区</option>
                    	         {{html_options options=$address.area_option selected=$address.area_id}}
                            </select><a style="color: #FF3300;">*</a>
						</td>
                        <td width="12%" height="30"><strong>收货人姓名</strong></td>
                      <td width="36%" height="30"><input type="text" name="address[consignee][]" size="25" maxlength="30" value="{{$address.consignee}}" class="istyle"/><a style="color: #FF3300;">*</a></td>
                    </tr>
                    
                    <tr>
                         <td width="12%" height="30"><strong>详细地址</strong></td>
                   	  <td width="40%" height="30"><input type="text" name="address[address][]" size="30" maxlength="100" value="{{$address.address}}" class="istyle"/><a style="color: #FF3300;">*</a></td>
                         <td width="12%" height="30"><strong>手&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;机</strong></td>
                      <td width="36%" height="30"><input type="text" name="address[mobile][]" size="25" maxlength="20" value="{{$address.mobile}}" class="istyle"/><a style="color: #FF3300;">*</a></td>
                    </tr>
                    <tr>
                        <td width="12%" height="30"><strong>电&nbsp;&nbsp;&nbsp;&nbsp;话</strong></td>
                      <td width="40%" height="30"><input type="text" name="address[phone][]" size="30" maxlength="40" value="{{$address.phone}}" class="istyle"/></td>
                        <td width="12%" height="30">&nbsp;</td>
                        <td width="36%" height="30">&nbsp;</td>
                    </tr>
                    <tr>
                        <td height="50" >    
                            <input type="hidden" name="address[address_id][]" value="{{$address.address_id}}" />
                            <input type="button" name="add" value="添加" onclick="addAddress(this)" class="buttons" /></td><td height="50">
                            <input type="button" name="delete" value="删除" onclick="removeAddress(this)" class="buttons"  />
                        </td>   <td height="50">&nbsp; </td> <td height="50">&nbsp; </td>
                    </tr>
                 </tbody>
                {{/foreach}}
                {{else}}
               <tbody>
                    <tr>
                        <td width="12%" height="30"><strong>配送区域</strong></td>
                        <td width="40%" height="30">
                            <select name="address[province][]" onchange="getArea(this)">
                                <option value="">请选择省</option>
                    	        {{html_options options=$province}}
                            </select>
                            <select name="address[city][]" onchange="getArea(this)">
                                <option value="">请选择市</option>
                            </select>
                            <select name="address[area][]">
                                <option value="">请选择区</option>
                      </select><a style="color: #FF3300;">*</a>
                      </td>
                        <td width="12%" height="30"><strong>收货人姓名</strong></td>
                      <td width="36%" height="30"><input type="text" name="address[consignee][]" size="25" maxlength="40" value="" class="istyle"/><a style="color: #FF3300;">*</a></td>
                    </tr>
                    <tr>
                        <td width="12%" height="30"><strong>详细地址</strong></td>
                      <td width="40%" height="30"><input type="text" name="address[address][]" size="30" maxlength="100" value="" class="istyle"/><a style="color: #FF3300;">*</a></td>
                        <td width="12%" height="30"><strong>手机</strong></td>
                      <td width="36%" height="30"><input type="text" name="address[mobile][]" size="25" maxlength="20" value="" class="istyle"/><a style="color: #FF3300;">*</a></td>
                    </tr>
                    <tr>
                        <td width="12%" height="30"><strong>电&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;话</strong></td>
                      <td width="40%" height="30"><input type="text" name="address[phone][]" size="30" maxlength="40" value="" class="istyle"/></td>
                        <td width="12%" height="30">&nbsp;</td>
                        <td width="36%" height="30">&nbsp;</td>
                    </tr>
                    <tr>
                        <td height="50" >
                            <input type="hidden" name="address[address_id][]" value="" />
                            <input type="button" name="add" value="添加" onclick="addAddress(this)" class="buttons" /></td><td height="50">
                            <input type="button" name="delete" value="删除" onclick="removeAddress(this)" class="buttons"/>   
					   </td>
					   <td height="50">&nbsp;</td> <td height="50">&nbsp; </td>
                    </tr>
                    </tbody>
                    {{/if}}
                </table> 
			
           <div style="padding-top: 10px; text-align:center"><input type="submit" name="dosubmit" id="dosubmit" value="提交修改" class="buttons"/></div>
            </form>
    </div>
  </div>
</div>

<iframe src="about:blank" style="width:0px;height:0px" frameborder="0" name="ifrmSubmit" id="ifrmSubmit"></iframe>

<script type="text/javascript">
//验证表单
function addressSubmit()
{
	var selectArea = $("#myForm select[name^='address']");
    for (var i = 0; i < selectArea.length; i++)
    {
        if (selectArea[i].value == '' || /\D+/.test(selectArea[i].value)) {
            alert('请选择配送地区！');
            return false;
        }
    }
    
	var inputAddress = $("#myForm input[name^='address']");
    for (var i = 0; i < inputAddress.length; i++)
    {
		if ($(inputAddress[i].name+":contains('consignee')") == true && inputAddress[i].value == '') {
            alert('请填写收货人！');
            return false;
		} else if ($(inputAddress[i].name+":contains('address')") == true && inputAddress[i].value == '') {	
            alert('请填写详细地址！');
            return false;
		} else if ($(inputAddress[i].name+":contains('phone')") == true && inputAddress[i].value == '' && !Check.isTel(inputAddress[i].value)) {
            alert('请填写正确的电话号码！');
            return false;
		} else if ($(inputAddress[i].name+":contains('mobile')") == true && inputAddress[i].value == '' && !Check.isMobile(inputAddress[i].value)) {
            alert('请填写正确的手机号码！');
            return false;
        }
    }
	
	$('#dosubmit').attr('value','提交中..');
	$('#dosubmit').attr('disabled',true);
    return true;
}

//联动
function getArea(id){
	var value=id.value;
	$(id).parent().children('select:last')[0].options.length = 1;
	$(id).next('select')[0].options.length=1;
	$.ajax({
		url:'{{url param.action=area}}',
		data:{id:value},
		dataType:'json',
		success:function(msg){
			var htmloption='';
			$.each(msg,function(key,val){
				htmloption+='<option value="'+key+'">'+val+'</option>';
			})
			$(id).next('select').append(htmloption);
		}
	})
}

//添加地址框
function addAddress(node){
	if($('#address_form tbody').length>=$('#address_number').val()){
		alert('您最多只能有' + $('#address_number').val() + '个收货地址!');
        return false;
	}
	$('#address_form').append($('#address_form tbody:last').clone());
	$("#address_form tbody:last input[type='text']").val('');
	$("#address_form tbody:last select").val('');
	$("#address_form tbody:last input[type='hidden']").remove();
	$("#address_form tbody:last select[name^='address[city]']").html('<option value="">请选择市</option>');
	$("#address_form tbody:last select[name^='address[area]']").html('<option value="">请选择市</option>');
}


//从html删除一个地址输入域
function removeAddress(node){
	if($('table tbody').length==1){
		alert("最后一条收货地址不能删除！");
	}
	if($('table tbody').length>1){
		$(node).parent().parent().parent().remove();
	}
}
</script>