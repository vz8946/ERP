<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="title">结款单申请</div>
<div class="content">
<form name="myForm" id="myForm" action="/admin/out-tuan/bill-ask" method="post" onSubmit="return checkThis()">
<table cellpadding="0" cellspacing="0" border="0" class="table" width="300">
  <tbody>
    <tr>
      <td width="120">网站名称</td>
      <td>
        <select name="shopid" id="shopid" onchange="getShopGoodsTerm()">
          <option value="0">请选择</option>
          {{foreach from=$shops item=shop}}
          <option value="{{$shop.shop_id}}">{{$shop.shop_name}}</option>
          {{/foreach}}
        </select>
      </td>
    </tr>
	<tr>
	  <td>商品期数</td>
	  <td id="tdcheckbox"><input type="checkbox" name="terms[]" id="terms[]" value="0" /></td>
	</tr>
    <tr>
      <td>应结款总金额</td>
      <td><input type="text" name="clear_amount" id="clear_amount" onkeyup="if(isNaN(value)){this.value='';}" onafterpaste="if(isNaN(value)){this.value='';}" /></td>
    </tr>
	<tr>
	  <td>应结款时间</td>
	  <td><input type="text" name="clear_time" id="clear_time" size="11"    class="Wdate" onClick="WdatePicker()"  /></td>
	</tr>
	<tr>
	  <td>备注</td>
	  <td><textarea name="remark" cols="60" rows="7" id="remark"></textarea></td>
	</tr>
    <tr>
      <td></td>
      <td><input type="submit" value="确认"></td>
    </tr>
  </tbody>
</table>
</form>
</div>
<script type="text/javascript">
//得到网站的团购商品
function getShopGoodsTerm(){
	var shop_id=$('shopid').value;
	if(shop_id<1){return false;}
	new Request({
		url:'/admin/out-tuan/get-shop-term-checkbox/shop_id/'+shop_id,
		onSuccess:function(msg){
			if(msg=='noshopid'){$('tdcheckbox').innerHTML='';alert('参数错误');}
			else if(msg=='noterm'){$('tdcheckbox').innerHTML='';alert('网站商品没有期数');}
			else{$('tdcheckbox').innerHTML=msg;}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}

//计算出应结款总金额
function calculateAmount(){
	var obj=document.getElementsByName('terms[]');
	var termids='';
	for(var i=0;i<obj.length;i++){
		if(obj[i].checked){
			termids+=obj[i].value+',';
		}
	}
	termids=termids.substr(0, termids.length-1);
	if(termids==''){
		$('termsamount').innerHTML='';
		return false;
	}
	new Request({
		url:'/admin/out-tuan/get-terms-amount',
		data:{terms:termids},
		onSuccess:function(msg){
			if(msg=='noterms'){alert('请选择期数');}
			else{
				$('termsamount').innerHTML='参考金额： '+msg;
			}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
//验证
function checkThis(){
	var shop=$('shopid').value;
	if(shop==0){alert('请选择网站');return false;}
	
	var obj=document.getElementsByName('terms[]');
	var flag=0;
	for(var i=0;i<obj.length;i++){
		if(obj[i].checked){flag=1;break;}
	}
	if(flag==0){alert('请选择期数');return false;}
	
	var fromdate=$('clear_amount').value;
	if(fromdate==''){alert('请填写应结款总金额');return false;}
	
	var clear_time=$('clear_time').value;
	if(clear_time==''){alert('请填写应结款时间');return false;}
	
	var todate=$('remark').value;
	if(todate==0){alert('请填写备注');return false;}
}
</script>