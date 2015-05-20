<div id="ajax_search">
<div class="title">已取消分成订单列表</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <!--<td>ID</td>-->
            <td>订单编号</td>
            <td>下单用户</td>
            <td>订单金额</td>
            <td>商品金额</td>
            <td>可用于分成金额</td>
            <td>分成比率</td>
            <td>下单时间</td>
            <td>备注</td>
            <td width="80">操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$orderList item=order}}
        <tr id="ajax_list{{$order.affiliate_id}}">
            <!--<td>{{$order.affiliate_id}}</td>-->
            <td>{{$order.order_sn}}</td>
            <td>{{$order.order_user_name}}</td>
            <td>{{$order.order_price}}</td>
            <td>{{$order.order_price_goods}}</td>
            <td>{{$order.order_affiliate_amount}}</td>
            <td>{{$order.proportion}}</td>
            <td>{{$order.add_time}}</td>
            <td>{{$order.edite_note}}</td>
            <td>
                <select name="separate_type" id="separate_type_{{$order.affiliate_id}}" onchange="separateOrder({{$order.affiliate_id}}, this.value)"><option value="2">不可分成</option><option value="1">可分成</option></select>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
    <div class="page_nav">{{$pageNav}}</div>
</div>
</div>
<script>
function separateOrder(id, value)
{
    var url = '';
    
    if (value == '1') {
        var msg = '';
        while (msg == '')
        {
            msg = prompt('请输入原因','');
        }
        
        if (msg != '' && msg != null) {
            url = '/affiliate_id/' + id + '/value/0/msg/' + msg;
        } else {
            $('separate_type_' + id).selectedIndex = 0;
        }
    } else {
        url = '/affiliate_id/' + id + '/value/' + value;
    }
    
    if (url != '') {
        new Request({
            url: '/admin/union-affiliate/separate-order' + url,
            onRequest: loading,
            onSuccess:function(data){
	            if (data =='ok') {
	                $('ajax_list' + id).destroy();
	                loadSucess();
	            } else {
	                alert('error');
	            }
            },
            onFailure: function()
	        {
	            alert('error');
	        }
        }).send();
    }
}
</script>