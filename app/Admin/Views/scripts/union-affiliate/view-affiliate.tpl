<div id="ajax_search">
<div class="title">{{$userName}} 订单分成列表</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('/admin/union-affiliate/index')">返回可打款列表</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <!--<td>ID</td>-->
            <td>订单编号</td>
            <td>下单用户</td>
            <td>订单金额</td>
            <td>商品金额</td>
            <td>可用于分成金额</td>
            <td>分成金额</td>
            <td>分成比率</td>
            <td>下单时间</td>
            <td>备注</td>
            <td width="80">操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$affiliateList item=affiliate}}
        <tr id="ajax_list{{$affiliate.affiliate_id}}">
            <!--<td>{{$affiliate.affiliate_id}}</td>-->
            <td><a href="javascript:fGo()" onclick="G('/admin/union-affiliate/view-affiliate-log/user_id/{{$affiliate.user_id}}/order_id/{{$affiliate.order_id}}/order_sn/{{$affiliate.order_sn}}/user_name/{{$userName|escape:"url"}}')" title="查看该订单分成历史">{{$affiliate.order_sn}}</a></td>
            <td>{{$affiliate.order_user_name}}</td>
            <td>{{$affiliate.order_price}}</td>
            <td>{{$affiliate.order_price_goods}}</td>
            <td>{{$affiliate.order_affiliate_amount}}</td>
            <td>{{$affiliate.affiliate_money}}</td>
            <td>{{$affiliate.proportion}}</td>
            <td>{{$affiliate.add_time}}</td>
            <td>{{$affiliate.edite_note}}</td>
            <td>
                <select name="separate_type" id="separate_type_{{$affiliate.affiliate_id}}" onchange="separateOrder({{$affiliate.affiliate_id}}, this.value)"><option value="1">可分成</option><option value="2">不可分成</option></select>
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
    
    if (value == '2') {
        var msg = '';
        while (msg == '')
        {
            msg = prompt('请输入原因','');
        }
        
        if (msg != '' && msg != null) {
            url = '/affiliate_id/' + id + '/value/' + value + '/msg/' + msg;
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