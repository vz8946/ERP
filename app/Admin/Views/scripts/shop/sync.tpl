<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="title">店铺同步</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="15%"><strong>店铺名称</strong></td>
      <td>{{$data.shop_name}}</td>
    </tr>
</tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="table_form">
<tbody>
	<tr height="58px">
	  <td>
		<input type="button" onclick="if(confirm('确认要下载商品吗?')){self.location.replace('/admin/shop/sync/id/{{$data.shop_id}}/action_name/goods')}" value="下载商品">
		<br><br>
	    <input type="button" style="float:left;" onclick="downloadOrder()" value="下载订单">
	    <span style="float:left;line-height:18px;">订单开始日期：</span>
        <span style="float:left;width:130px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="{{$fromdate}}"   class="Wdate" onClick="WdatePicker()" /></span>
        <span style="float:left;line-height:18px;">订单结束日期：</span>
        <span style="float:left;width:130px;line-height:18px;"><input type="text" name="todate" id="todate" size="15" value="{{$todate}}" class="Wdate" onClick="WdatePicker()" /></span>
	    <br><br><br>
	    <input type="button" onclick="if(confirm('确认要上传商品库存吗?')){if ({{$data.shop_id}} != 23 && {{$data.shop_id}} != 24) {alert('暂不开放!');return false;} self.location.replace('/admin/shop/sync/id/{{$data.shop_id}}/action_name/stock')}" value="上传商品库存">
	    <br><br>
	    <input type="button" onclick="if ('{{$data.shop_type}}' != 'taobao') {alert('暂不开放!');return false;} self.location.replace('/admin/shop/sync/id/{{$data.shop_id}}/action_name/comment')" value="下载用户评论">
	    <br><br>
	    <input type="button" onclick="if(confirm('确认要双向同步订单吗?')){self.location.replace('/admin/shop/sync-order/id/{{$data.shop_id}}')}" value="双向同步订单">
	  </td>
	</tr>
   </tbody>
</table>
</div>

<script>
function downloadOrder()
{
    if (confirm('确认要下载订单吗?')) {
        self.location.replace('/admin/shop/sync/id/{{$data.shop_id}}/action_name/order/fromdate/' + document.getElementById('fromdate').value + '/todate/' + document.getElementById('todate').value);
    }
}
</script>