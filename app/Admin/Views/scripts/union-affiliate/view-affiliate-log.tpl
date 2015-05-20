<div id="ajax_search">
<div class="title">{{$userName}} 订单 {{$orderSn}} 分成历史</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('/admin/union-affiliate/index')">返回可打款列表</a> ]
        [ <a href="javascript:fGo()" onclick="G('/admin/union-affiliate/view-affiliate/user_id/{{$userId}}/user_name/{{$userName|escape:"url"}}')">返回订单分成列表</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>订单状态</td>
            <td>配送状态</td>
            <td>订单类型</td>
            <td>订单金额</td>
            <td>商品金额</td>
            <td>可用于分成金额</td>
            <td>分成金额</td>
            <td>分成比率</td>
            <td>处理时间</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$affiliateLogList item=affiliateLog}}
            <td>{{$affiliateLog.affiliate_log_id}}</td>
            <td>{{$affiliateLog.order_status}}</td>
            <td>{{$affiliateLog.order_status_logistic}}</td>
            <td>{{$affiliateLog.order_status_return}}</td>
            <td>{{$affiliateLog.order_price}}</td>
            <td>{{$affiliateLog.order_price_goods}}</td>
            <td>{{$affiliateLog.order_affiliate_amount}}</td>
            <td>{{$affiliateLog.affiliate_money}}</td>
            <td>{{$affiliateLog.proportion}}</td>
            <td>{{$affiliateLog.add_time}}</td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
    <div class="page_nav">{{$pageNav}}</div>
</div>
</div>