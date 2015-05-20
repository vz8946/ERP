<style type="text/css">
.dotline {
border-bottom-color:#666666;
border-bottom-style:dotted;
border-bottom-width:1px;
}
</style>
{{if $order.parent_batch_sn}}
<div style="margin:0 auto; text-align:center; color:red;">
<span style="cursor:pointer;" onclick="G('{{url param.action=info param.batch_sn=$order.parent_batch_sn}}')">换货单 [父单号：{{$order.parent_batch_sn}}]</span>
</div>
{{/if}}
<form id="myform">
  <table width="100%" border="0">
    <tr>
      <td width="50%" valign="top"><table width="100%">
        <tr bgcolor="#F0F1F2">
          <td width="100">店铺名称：
              </td>
          </td>
          <td>{{$order.shop_name}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <th>单据编号：
              </td>
          </th>
          <td>{{$order.external_order_sn}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td>下单日期：</td>
          <td>{{$order.order_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td>付款日期：</td>
          <td>{{if $order.pay_time}}{{$order.pay_time|date_format:"%Y-%m-%d %H:%M:%S"}}{{/if}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td>最后更新日期：</td>
          <td>{{$order.update_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td>审核用户：</td>
          <td>{{$order.check_admin_name}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td>打印用户：</td>
          <td>{{$order.print_admin_name}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td>发货用户：</td>
          <td>{{$order.send_admin_name}}</td>
        </tr>
      </table></td>
      
      
      <td width="50%" valign="top">
      
      <div style="width:200px; float:left;" id="adddiv_{{$order.external_order_sn}}"><input type="button" value="查看收货信息" style="width:120px;height:40px;" onclick="chkAddressinfo('{{$order.external_order_sn}}','{{$order.user_id}}');"/></div>	
      <table width="100%" class="mytable" style="display:none; float:right;" id="addinfo_{{$order.external_order_sn}}">
        <tr  bgcolor="#F0F1F2">
          <th width="100">收货人：
          </th>
          <td>{{$order.addr_consignee}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td>联系电话：</td>
          <td colspan="2">{{$order.addr_tel}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td>手机：</td>
          <td colspan="2">{{$order.addr_mobile}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td>地区：</td>
          <td colspan="2">{{$order.addr_province}}{{$order.addr_city}}{{$order.addr_area}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td>收货地址：</td>
          <td colspan="2">{{$order.addr_address}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td>邮政编码：</td>
          <td colspan="2">{{$order.addr_zip}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td>刷单用户：</td>
          <td colspan="2">{{$order.fake_admin_name}}</td>
        </tr>
      </table>
      
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <br>
  <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
    <tr>
      <td width="11%">订单状态</td>
      <td width="11%">业务状态</td>
      <td width="11%">物流状态</td>
      <td width="12%">同步官网订单</td>
      <td width="11%">刷单</td>
      <td width="11%">地址匹配</td>
      <td width="13%">第3方物流发货</td>
      <td width="11%">已开票</td>
      <td>已结算</td>
    </tr>
    </thead>
    <tr>
      <td>
        {{if $order.status eq 1}}待收款
        {{elseif $order.status eq 2}}待发货
        {{elseif $order.status eq 3}}待确认收货
        {{elseif $order.status eq 10}}已完成
        {{elseif $order.status eq 11}}已取消
        {{elseif $order.status eq 12}}其它
        {{/if}}
      </td>
      <td>
        {{if $order.status_business eq 0}}未审核
        {{elseif $order.status_business eq 1}}审核通过
        {{elseif $order.status_business eq 2}}已打印
        {{elseif $order.status_business eq 4}}发货中
        {{elseif $order.status_business eq 9}}审核不通过
        {{/if}}
      </td>
      <td>
        {{if $order.logistic_status eq 1}}签收
        {{elseif $order.logistic_status eq 2}}拒收
        {{elseif $order.logistic_status eq 3}}退货
        {{else}}无
        {{/if}}
      </td>
      <td>
        {{if $order.sync eq 1}}是
        {{else}}否
        {{/if}}
      </td>
      <td>
        {{if $order.is_fake eq 1}}未销账
        {{elseif $order.is_fake eq 2}}已销账
        {{else}}否
        {{/if}}
      </td>
      <td>
        {{if $order.addr_province_id eq 0 || $order.addr_city_id eq 0 || $order.addr_area_id eq 0}}失败
        {{else}}成功
        {{/if}}
      </td>
      <td>
        {{if $order.other_logistics eq 1}}是
        {{else}}否
        {{/if}}
      </td>
      <td>
        {{if $order.done_invoice eq 1}}是
        {{else}}否
        {{/if}}
      </td>
      <td>
        {{if $order.is_settle eq 1}}是
        {{else}}否
        {{/if}}
      </td>
    </tr>
  </table>
  <br>
<br>
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
  <tr>
    <td>商品名称</td>
    <td>商品编号</td>
    <td>销售价</td>
    <td>数量</td>
    <td>优惠金额</td>
    <td>总金额</td>
  </tr>
  </thead>
  <tbody>
  {{foreach from=$order.goods item=goods}}
  <tr>
    <td>{{$goods.shop_goods_name}}</td>
    <td>{{$goods.goods_sn}}</td>
    <td>{{$goods.price}}</td>
    <td>{{$goods.number}}</td>
    <td>{{$goods.discount_price}}</td>
    <td>{{math equation="x * y - z" x=$goods.price y=$goods.number z=$goods.discount_price}}</td>
  </tr>
  {{/foreach}}
  </tbody>
</table>
<br>
<table >
  <tr>
    <th width="150">商品总金额：</td>
    <td>{{$order.goods_amount}}</td>
  </tr>
  <tr bgcolor="#F0F1F2">
    <td>运输费：</td>
    <td>{{$order.freight}}</td>
  </tr>
  <tr>
    <td>订单总金额：</td>
    <td>{{$order.amount}}</td>
  </tr>
</table>
<br />
<table>
<tr>
<td width="150">物流信息：</td>
<td>{{if $order.is_cod}}货到付款{{else}}非货到付款{{/if}}　{{$order.logistic_code}} {{if $order.logistic_no}}({{$order.logistic_no}}){{/if}}<br /></td>
</tr>
<tr>
<td width="150">店铺备注：</td>
<td>{{$order.memo}}</td>
</tr>
<td width="150">物流备注：</td>
<td>{{$order.admin_memo}}</td>
</tr>
<tr>
<td width="150">操作记录：</td>
<td>{{$order.history}}</td>
</tr>
<tr>
<td>开票信息：</td>
<td>{{$order.invoice}} {{$order.invoice_content}}</td>
</tr>
</table>
<br>
<table align="center">
<tr>
<td>
  <input type="button" value="关闭窗口" onclick="window.close()">
  <input type="button" name="printSales" value="打印销售单" onclick="window.open('/admin/shop/print-sales/print_shop_id/{{$order.shop_id}}/id/{{$order.shop_order_id}}')"/>
  {{if $order.logistic_code}}
  <input type="button" name="printSales" value="打印运输单" onclick="window.open('/admin/shop/print-logistics/print_shop_id/{{$order.shop_id}}/id/{{$order.shop_order_id}}')"/>
  {{/if}}
</td>
</tr>
</table>

<script>
//查询收货信息
function chkAddressinfo(orderno,userid){

	$("adddiv_"+orderno).setStyle('display', 'none'); 
	$("addinfo_"+orderno).setStyle('display', 'block'); 
	new Request({
		url:'/admin/order/saveoptlog/orderno/'+orderno+'/userid/'+userid+'/optaction/shop-order-detail',
		onSuccess:function(msg){
			if(msg != 'ok'){
				alert(msg);
			}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
</script>