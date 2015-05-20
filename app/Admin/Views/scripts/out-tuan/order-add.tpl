<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="title">添加外部团购订单</div>
<div class="content">
<form name="f1" action="/admin/out-tuan/xls-upload" target="hidden_frame" method="post" onSubmit="return ct()" enctype="multipart/form-data">
<table cellpadding="0" cellspacing="0" border="0" class="table">
  <tbody>
    <tr>
      <td width="150">网站名称<span id="test"></span></td>
      <td>
        <select name="shop_id" id="shop_id" onchange="getShopGoods()">
          <option value="0">请选择</option>
          {{foreach from=$shops item=shop}}
          <option value="{{$shop.shop_id}}">{{$shop.shop_name}}</option>
          {{/foreach}}
        </select>
      </td>
    </tr>
    <tr>
      <td>商品名称</td>
      <td id="shopgoods">
        <select name="goods_id" id="goods_id" onchange="getSubTerm()">
          <option value="0">请选择</option>
        </select>
      </td>
    </tr>
	<tr>
	  <td>期数</td>
	  <td id="tdterm"><select name="term_id"><option value="0">请选择</option></select></td>
	</tr>
    <tr>
      <td>.xls 文件</td>
      <td><input type="file" name="xls" id="xls" />&nbsp;&nbsp;&nbsp;<input type="submit" id="subm" value="上传"><input type="hidden" id="xlspath" value="" /></td>
    </tr>
  </tbody>
</table>
<iframe style="display:none;" id="hidden_frame" name="hidden_frame"></iframe>
</form>
<br />
<fieldset id="matching" style="width:600px; border:2px solid #ff0000; display:none; margin-left:10px;">
<legend><strong>上传成功！请选择对应字段：</strong></legend>
<!--字段对应-->
  <div id="matchtable">
    <form name="myForm" id="myForm" action="/admin/out-tuan/order-add" method="post" onsubmit="return checkF()">
      <input type="hidden" name="mshop_id" id="mshop_id" value="" />
      <input type="hidden" name="mgoods_id" id="mgoods_id" value="" />
      <input type="hidden" name="xpath" id="xpath" value="" />
	  <input type="hidden" name="termid" id="termid" value="" />
      <table cellpadding="0" cellspacing="0" border="0" class="table">
		<tr>
		  <td width="120">下单时间<span style=" color:red;font-weight:bold;">*</span></td>
		  <td>
		  <input type="text" name="order_time" id="order_time" size="24" value=""  class="Wdate" onClick="WdatePicker()" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
		 </td>
		</tr>
		
        <tr>
          <td>购买时间<span style=" color:red;font-weight:bold;">*</span> 优先级较高 </td>
          <td class="selects" zd="buy_time"></td>
        </tr>
		<tr>
          <td>商品名称</td>
          <td class="selects" zd="goods_name"></td>
        </tr>
        <tr>
          <td>订单号</td>
          <td class="selects" zd="order_sn"></td>
        </tr>
        <tr>
          <td>收件人姓名<span style=" color:red;font-weight:bold;">*</span></td>
          <td class="selects" zd="name"></td>
        </tr>
        <tr>
          <td>固定电话</td>
          <td class="selects" zd="phone"></td>
        </tr>
        <tr>
          <td>手机</td>
          <td class="selects" zd="mobile"></td>
        </tr>
        <tr>
          <td>邮编</td>
          <td class="selects" zd="postcode"></td>
        </tr>
		<tr>
		  <td>省</td>
		  <td class="selects" zd="sheng"></td>
		</tr>
		<tr>
		  <td>市</td>
		  <td class="selects" zd="shi"></td>
		</tr>
		<tr>
		  <td>区</td>
		  <td class="selects" zd="qu"></td>
		</tr>
        <tr>
          <td>详细地址<span style=" color:red;font-weight:bold;">*</span></td>
          <td class="selects" zd="addr"></td>
        </tr>
        <tr>
          <td>客户备注</td>
          <td class="selects" zd="remark"></td>
        </tr>
        <tr>
          <td>发票信息 (0 无发票 1 个人 2 企业)</td>
          <td class="selects" zd="invoice_type"></td>
        </tr>
        <tr>
          <td>发票内容 </td>
          <td class="selects" zd="invoice_content"></td>
        </tr>
        <tr>
          <td>购买数量<span style="color:red;font-weight:bold;">*</span></td>
          <td class="selects" zd="amount"></td>
        </tr>
		<tr>
		  <td>刷单</td>
		  <td class="selects" zd="ischeat"></td>
		</tr>
		<tr>
		  <td>快递公司</td>
		  <td class="selects" zd="logistics_com" style="display:none;"></td>
		</tr>
		<tr>
		  <td>快递单号</td>
		  <td class="selects" zd="logistics_no" style="display:none;"></td>
		</tr>
        <tr>
          <td></td>
          <td><input type="submit" name="addorder" value="确认" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="重新匹配字段" onclick="getSelect()" /></td>
        </tr>
      </table>
    </form>
  </div>
<!--字段对应-->
</fieldset>
</div>
<script type="text/javascript">
//无刷新上传
var p=null;

function ct(){
	if($('shop_id').value==0){alert('请选择网站');return false;}
	if($('goods_id').value==0){alert('请选择商品');return false;}
	if($('xls').value==''){alert('请选择xls文件');return false;}
	if($('term_id').value==0){alert('请选择期数');return false;}
	checkThis();
	p=setInterval('checkThis()',500);
}

function checkThis(){
	var t=$(document.getElementById('hidden_frame').contentWindow.document.body).innerHTML;
	if(t!=''){
		if(t!='error'){
			$('xlspath').value=t;
			$('matching').style.display='block';
			$('subm').disabled=true;
			$('shop_id').disabled=true;
			$('goods_id').disabled=true;
			$('term_id').disabled=true;
			$('mshop_id').value=$('shop_id').value;
			$('mgoods_id').value=$('goods_id').value;
			$('xpath').value=t;
			//取得字段select
			clearInterval(p);
			getSelect();
			return;
		}else{
			alert('上传错误，请刷新重试');
		}
	}
	if(p!=null && t!=''){
		clearInterval(p);
		return;
	}
	//p=setInterval('checkThis()',500);
}
//取得字段select
function getSelect(){
	t=$('xlspath').value;
	if(!t){alert('上传错误，请刷新重试');}
	new Request({
		url:'/admin/out-tuan/get-select/id/'+t,
		evalScripts:true,
		onSuccess:function(msg){
			//产生element.select
			var jsonv=JSON.decode(msg);
			var sel = new Element('SELECT');
			var gg=document.createElement('OPTION');
			gg.value='n';
			gg.text='请选择';
			gg.innerHTML='请选择';
			sel.appendChild(gg);
			j=1;
			for(m in jsonv){
				var opt = document.createElement('OPTION');
				opt.value=j;
				opt.text=jsonv[m];
				opt.innerHTML=jsonv[m];
				sel.appendChild(opt);
				j++;
			}
			//
			var ziduan=$('matchtable').getElements('.selects');
			for(m=0;m<ziduan.length;m++){
				var zduan=ziduan[m].get('zd');/*得到字段name*/
				tmp=sel.clone()/*mootools用法*/
				ziduan[m].appendChild(tmp);/*放入select*/
				ziduan[m].getElements('select').set('name',zduan);/*更改select的name*/
				ziduan[m].getElements('select').set('id',zduan);/*更改select的id*/
			}
		},
		onFailure:function(){
			alert('出现错误，请重新匹配字段');
		}
	}).send();
}
//检验
function checkF(){
	if($('name').value=='n'){alert('请选择收件人');return false;}
	if($('phone').value=='n' && $('mobile').value=='n'){alert('固定电话和手机必填一个');return false;}
	/*if($('postcode').value=='n'){alert('请选择邮编');return false;}*/
	if($('addr').value=='n'){alert('请选择收货地址');return false;}
	if($('amount').value=='n'){alert('请选择购买数量');return false;}
}

//商品期数
function getSubTerm(){
	var val=$('goods_id').value;
	if(val==0){return false;}
	new Request({
		url:'/admin/out-tuan/get-term-select/goods_id/'+val,
		onSuccess:function(msg){
			$('tdterm').innerHTML=msg;
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
	//var goodsPrice=$('goods_id').get('price');
	/*var obj=document.getElementById('goods_id');
	var goodsPrice;
	for(var i=0;i<obj.length;i++){
		if(obj[i].selected==true){
			goodsPrice=obj[i].getAttribute('price');
		}
	}
	if(goodsPrice!='' && goodsPrice!=0){
		document.getElementById('goods_price').value=goodsPrice;
	}else{
		alert('请修改商品价格');
		return;
	}*/
}
//商品期数
function setTerm(){
	var term=$('term_id').value;
	if(term<1){alert('期数设置错误');}
	
	document.getElementById('termid').value=term;
}
//得到网站商品
function getShopGoods(){
	var shop_id=$('shop_id').value;
	if(shop_id<1){return false;}
	new Request({
		url:'/admin/out-tuan/get-shop-goods/shopid/'+shop_id,
		onSuccess:function(msg){
			$('shopgoods').innerHTML=msg;
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
</script>