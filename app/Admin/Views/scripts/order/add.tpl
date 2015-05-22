<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form id="myform1">
<input type="hidden" name="logic_area" id="logic_area">
<input type="hidden" name="shop_id" id="shop_id" value="1">
 <div class="search" > 
     <div style="padding-left:10px;">
         <input type='hidden' name='submit' value="submit">
            <span >
         收货信息： 
                <select name="logistics_type" id="logistics_type" onchange="changeLogisticsType(this.value)">
                    <option value="logistics">物流配送</option>
                    <option value="self">客户自提</option>
                    <option value="externalself">渠道代发货自提</option>
                </select>
                <select name="province_id" id="province_id" onchange="getArea(this)">
                    <option value="">请选择省</option>
                    {{html_options options=$province}}
                </select><select name="city_id" id="city_id" onchange="getArea(this)">
                    <option value="">请选择市</option>
                </select><select name="area_id" id="area_id">
                    <option value="">请选择区</option>
                </select>
           </span> 
            收货人<input type='text' name='addr_consignee' size="8" value=''>
            <span id="addr_address">收货地址<input type='text' size="30" name='addr_address' value=''></span>
            电话<input type='text' name='addr_tel' value='' size="15">
            手机<input type='text' name='addr_mobile' value='' size="15">
 
      </div>
      <div style="padding-left:10px;">
      下单时间：<input type="text" name="add_time" id="add_time" size="22" value="{{$add_time}}"  class="Wdate" onClick="WdatePicker()" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
      &nbsp;&nbsp;
      支付方式：<select name="order_payment" id="order_payment" onchange="changePayment(this.value)" >
          <option value="" >请选择 </option>
             <option value="cod|货到付款">货到付款</option>
             <option value="cash|现金支付">现金支付</option>
             <option value="bank|银行打款">银行打款</option>
             <option value="external|渠道支付">渠道支付</option>
		</select>
        下单类型：  
        <select name="entry" id="entry" onchange="changeEntry(this.value)">
          <option value="b2c">官网B2C</option>
          <option value="call">呼叫中心</option>
          <option value="channel">渠道运营</option>
          <!--<option value="distribution">渠道分销</option>-->
          <option value="other">其它下单</option>
        </select>
        <select name="type" id="type" onchange="changetype(this.value)">
         <option value="b2c">后台下单</option>
        </select> 
        <select name="distribution_type" id="distribution_type" style="display:none">
          <option value="0">销售单</option>
          <option value="1">刷单</option>
        </select>
       <span id="giftbywho" style="display:none;">赠送人：<input type="text" name="giftbywho"/></span>
       <span id="user_name" style="display:none;">绑定前台账号：<input type="text" name="user_name" onblur="checkUserName(this)"/></span>
       <span id="shop" style="display:none;">
         店铺：
         <select name="shop_id" onchange="changeShop(this);">
            <option value="0">请选择...</option>
            {{foreach from=$shopDatas item=shop}}
              {{if $shop.shop_type ne 'jiankang' && $shop.shop_type ne 'credit' && $shop.shop_type ne 'distribution'}}
              <option value="{{$shop.shop_id}}">{{$shop.shop_name}}</option>
              {{/if}}
            {{/foreach}}
          </select>
          渠道订单号：<input type="text" name="external_order_sn">
       </span>
	    <span id="creditshop" style="display:none;">
         渠道：
         <select name="creditshop_id">
           <option value="0">请选择...</option>
            {{foreach from=$shopDatas item=shop}}
              {{if $shop.shop_type eq 'credit'}}
              <option value="{{$shop.shop_id}}">{{$shop.shop_name}}</option>
              {{/if}}
            {{/foreach}}
          </select>
       </span>
       <span id="distribution_shop" style="display:none;">
         渠道：
         <select name="distribution_shop_id">
           <option value="0">请选择...</option>
            {{foreach from=$shopDatas item=shop}}
              {{if $shop.shop_type eq 'distribution'}}
              <option value="{{$shop.shop_id}}">{{$shop.shop_name}}</option>
              {{/if}}
            {{/foreach}}
          </select>
       </span>
	   <span id="part_pay" style="display:none;"> <input name="part_pay" type="checkbox" value="1" /> 是否允许部分收款发货 </span>
       <br>
       <span>
         物流打印备注：<textarea name="note_print" rows="2" style="width:330px"></textarea>
         &nbsp;&nbsp;&nbsp;
         物流部门备注：<textarea name="note_logistic" rows="2" style="width:330px"></textarea>
       </span>
       <br>
        <span id="shopgoods" >
            <input type="button" onclick="openDiv('/admin/product/sel/type/sel_stock' + '/logic_area/' + $('logic_area').value+'/shop_id/'+$('shop_id').value,'ajax','添加商品',750,400);" value=" 添加商品 " name="do"/> 
     	</span>
     	<span id="shopgroupgoods">
           <input type="button" onclick="openDiv('/admin/goods/sel/type/2/shop_id/'+$('shop_id').value,'ajax','添加组合商品',750,400);" value=" 添加组合商品 " name="do"/> 
     	</span>
       <input type="button" value="提交订单" onclick="ajax_submit($('myform1'),'/admin/order/add')" />
        </div>
    </div>
    <div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>删除</td>
            <td>商品编码</td>
            <td>商品名称</td>
            <td>单品价格</td>
            <td>保护价</td>
            <td>可用库存</td>
            <td>修改后单品价格</td>
            <td>数量</td>
        </tr>
    </thead>
    <tbody id="list"></tbody>
    </table>
    </div>
</div>
</form>
<script language="JavaScript">
//部分收款
function changePayment(val)
{

if(val!=='cod|货到付款' && val!=='external|渠道支付' && val!==''){
	$('part_pay').setStyle('display','inline-block');
	}else{
	$('part_pay').setStyle('display','none');
	}
}

function changetype(val)
{
	if (val.indexOf('gift') > -1) {
		$('giftbywho').setStyle('display','inline-block');
	}
	else {
		$('giftbywho').setStyle('display','none');
	}
	
	if (val=='external_renew') {
		$('shop').setStyle('display','inline-block');
	}
	else {
		$('shop').setStyle('display','none');
	}
	
	if (val=='credit_channel'  ) {
		$('creditshop').setStyle('display','inline-block');
	}
	else {
		$('creditshop').setStyle('display','none');
	}
	
	if (val=='distribution_channel'  ) {
		$('distribution_shop').setStyle('display','inline-block');
		$('shopgroupgoods').setStyle('display','none');
	}
	else {
		$('distribution_shop').setStyle('display','none');
		$('shopgroupgoods').setStyle('display','inline-block');
	}
	
	if ($('type').value.indexOf('_distribution') > 0) {
	    for (i = 0; i < distributionUsername.length; i++) {
	        if (distributionUsername[i] == $('type').value) {
	            $('logic_area').value = distributionArea[i];
	            break;
	        }
	    }
	}
	else {
	    $('logic_area').value = '1';
	}

    if (val == 'external_renew') {
        $('shop_id').value = '11';
    } else if (parseInt($("shop_id").value) > 0 && (val == 'batch_channel' || val == 'credit_channel')) {
        $('shop_id').value = '0';
    }
	
	if (val == 'b2c' || val == 'in_call' || val == 'out_call' || val == 'tq_call') {
	    $('user_name').setStyle('display','inline-block');
	}
	else {
	    $('user_name').setStyle('display','none');
	}
	
	removeAllRow();
}

function changeEntry(val)
{
    $('distribution_shop').setStyle('display','none');
    $('shop_id').value = '0';
    $('type').options.length = 0;
    if (val == 'b2c') {
        $('type').options.add(new Option('后台下单', 'b2c'));
        $('shop_id').value = '1';
    }
    else if (val == 'channel') {
        $('type').options.add(new Option('渠道补单', 'external_renew'));
        $('type').options.add(new Option('购销下单', 'batch_channel'));
		$('type').options.add(new Option('赊销下单', 'credit_channel'));
		$('type').options.add(new Option('直供下单', 'distribution_channel'));
        $('shop_id').value = '11';
    }
    else if (val == 'call') {
        $('type').options.add(new Option('呼入下单', 'in_call'));
        $('type').options.add(new Option('呼出下单', 'out_call'));
        $('type').options.add(new Option('咨询下单', 'tq_call'));
        $('shop_id').value = '0';
    }
    else if (val == 'other') {
        $('type').options.add(new Option('其它下单', 'other'));
        $('type').options.add(new Option('赠送下单', 'gift'));
        $('type').options.add(new Option('内购下单', 'internal'));
        $('shop_id').value = '0';
    }
    else if (val == 'distribution') {
        {{foreach from=$areas item=item key=key}}
          {{if $key > 20}}
          $('type').options.add(new Option('{{$item}}', '{{$distributionArea[$key]}}'));
          {{/if}}
        {{/foreach}}
        $('shop_id').value = '0';
    }
    
    if (val == 'distribution') {
        $('shopgroupgoods').style.display = 'none';
        $('distribution_type').style.display = '';
    }
    else {
        $('shopgroupgoods').style.display = '';
        $('distribution_type').style.display = 'none';
    }
    
    changetype($('type').value);
}
function changeLogisticsType(val)
{
    return;
    if (val == 'self') {
        $('province_id').style.display = 'none';
        $('city_id').style.display = 'none';
        $('area_id').style.display = 'none';
        $('addr_address').style.display = 'none';
    }
    else {
        $('province_id').style.display = '';
        $('city_id').style.display = '';
        $('area_id').style.display = '';
        $('addr_address').style.display = '';
    }
}
function addRow()
{
	var el = $('source_select').getElements('input[type=checkbox]');
	var obj = $('list');
	for (i = 1; i < el.length; i++) {
		if (el[i].checked) {
		    var id = el[i].value;
		    if ($('pinfo' + id) == null) {
			    var str = $('ginfo' + id).value;
			    var pinfo = JSON.decode(str);
			    if ($('gid' + pinfo.goods_id)) {
    				continue;
    			}
    			else {
    			    var tr = obj.insertRow(0);
    			    tr.id = 'gid' + pinfo.product_id;
                    var limit_price_str = pinfo.price_limit == 0 ? '无限制' : pinfo.price_limit;
    			    for (var j = 0;j <= 9; j++) {
    				  	 tr.insertCell(j);
    				}
    				tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeRow('+ pinfo.product_id +')"><input type="hidden" name="group_id[]" value="'+pinfo.product_id+'" >';
    				tr.cells[1].innerHTML = pinfo.goods_sn;
    				tr.cells[2].innerHTML = pinfo.goods_name;
    				tr.cells[3].innerHTML = pinfo.price;
                    tr.cells[4].innerHTML = limit_price_str;
    				tr.cells[5].innerHTML = pinfo.store;
    				tr.cells[6].innerHTML = '<input type="text" size="6" name="addg['+ pinfo.goods_id +'][sale_price]" value="'+ pinfo.price +'" onchange="changePrice('+pinfo.price_limit+', this, '+pinfo.price+')"/>';
    				tr.cells[7].innerHTML = '<input type="text" size="3" name="addg['+ pinfo.goods_id +'][number]" value="0" class="required" msg="不能为空" />';
    			}
			}
			else {
			    var str = $('pinfo' + id).value;
    			var pinfo = JSON.decode(str);
    			if ($('sid' + pinfo.product_id)) {
    				continue;
    			}
    			else {
    			    var tr = obj.insertRow(0);
    			    tr.id = 'sid' + pinfo.product_id;
                    var limit_price_str = pinfo.price_limit == 0 ? '无限制' : pinfo.price_limit;
    			    for (var j = 0;j <= 9; j++) {
    				  	 tr.insertCell(j);
    				}
    				
    				tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeRow('+ pinfo.product_id +')"><input type="hidden" name="product_id[]" value="'+pinfo.product_id+'" >';
    				tr.cells[1].innerHTML = pinfo.product_sn;
    				tr.cells[2].innerHTML = pinfo.product_name;
    				tr.cells[3].innerHTML = pinfo.price;
                    tr.cells[4].innerHTML = limit_price_str;
    				tr.cells[5].innerHTML = pinfo.able_number;
    				tr.cells[6].innerHTML = '<input type="text" size="6" name="add['+ pinfo.product_id +'][sale_price]" value="'+ pinfo.price +'" onchange="changePrice('+pinfo.price_limit+', this, '+pinfo.price+')"/>';
    				tr.cells[7].innerHTML = '<input type="text" size="3" name="add['+ pinfo.product_id +'][number]" value="0" class="required" msg="不能为空" onblur="checkNum(this, ' + pinfo.able_number + ')"/>';
    				
    				if (pinfo.price_seg) {
    				    tr.cells[3].innerHTML = tr.cells[3].innerHTML + " <a onmouseover=showTip(window.event,'price_seg_"+pinfo.product_id+"') onmouseout=closeTip('price_seg_"+pinfo.product_id+"')>(多数量价格)</a><div id=price_seg_"+pinfo.product_id+" style=display:none;background-color:#DDDDDD>"+pinfo.price_seg+"</div>";
    				}
    			}
			    
				obj.appendChild(tr);
			}
		}
	}
}
function removeRow(id)
{
	if ($('sid' + id) == null) {
	    $('gid' + id).destroy();
	}
	else {
	    $('sid' + id).destroy();
	}
}
function removeAllRow()
{
    var objs = $('list').getElements('tr');
    for (var i = 0; i < objs.length; i++) {
        $('list').removeChild(objs[i]);
    }
}
function check(obj, able_number) 
{
	able_number = parseInt(able_number);
	var v = obj.value
	if (v > able_number) {
		alert('修改数量不能大于可用库存');
		obj.value = able_number;
	}
}

function getArea(id)
{
    var value = id.value;
    var select = $(id).getNext();
    var parent = $(id).getParent();
    var last = parent.getLast();
    last.options.length = 1;
    new Request({
        url: '{{url param.action=area}}/parent_id/' + value,
        //onRequest: loading,
        onSuccess:function(data){
            select.options.length = 1;
	        if (data != '') {
	            data = JSON.decode(data);
	            $each(data, function(item, index){
	                var option = document.createElement("OPTION");
					option.value = index;
					option.text  = item;
                    select.options.add(option);
	            });
	            if (select.name == 'area_id') {
    	            var option = document.createElement("OPTION");
    			    option.value = -1;
    			    option.text  = '其它区';
                    select.options.add(option);
                }
	        }
            //loadSucess();
        }
    }).send();
}
function showTip(e,id) 
{
    e = e||window.event;
    var div1 = document.getElementById(id);
    div1.style.display="";
    div1.style.left=e.clientX+10;
    div1.style.top=e.clientY+5; 
    div1.style.position="absolute";  
}
function closeTip(id) 
{ 
    var div1 = document.getElementById(id); 
    div1.style.display="none"; 
}

function checkNum(obj, num)
{
	if (parseInt(obj.value) < 0 || isNaN(obj.value)) {
	    alert('请填写正整数');
	    obj.value = 0;
	    return false;
	}
	if (parseInt(obj.value) > parseInt(num)) {
	    alert('数量不能大于'+num);
	    obj.value = 0;
	    return false;
	}
}

function changeShop(obj)
{
    $('shop_id').value = obj.value;
}

function changePrice(price_limit ,obj, old_price)
{
    if (isNaN(obj.value)) {
        alert('价格不正确');
        obj.value=old_price;
        obj.focus();
    }
    /*if (parseFloat(price_limit) > 0) {
        if (parseFloat(obj.value) < parseFloat(price_limit)) {
            alert('价格不能小于保护价');
            obj.value=old_price;
            obj.focus();
            return false;
        }
    }*/
}

function checkUserName(obj)
{
    if (obj.value == '')    return true;
    
    new Request({
		url:'/admin/order/check-username/user_name/' + obj.value,
		onSuccess:function(msg){
		    if (msg == 'invalid') {
		        alert('账号已冻结');
		        obj.value = '';
		        obj.focus();
		    }
		    else if (msg == 'empty') {
		        alert('找不到账号');
		        obj.value = '';
		        obj.focus();
		    }
		    else if (msg == 'ok') {
		    }
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}

var distributionUsername = new Array();
var distributionArea = new Array();
{{foreach from=$distributionAreaUsername item=item key=key}}
{{if $item > 20}}
distributionUsername.push('{{$key}}');
distributionArea.push('{{$item}}');
{{/if}}
{{/foreach}}

changeEntry($('entry').value);

</script>