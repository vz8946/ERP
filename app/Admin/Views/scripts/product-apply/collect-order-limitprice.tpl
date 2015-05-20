<div class="title">订单限价汇总列表</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
            <tr>
                <td>官网订单</td>
                <td>渠道订单</td>
                <td>总计</td>
            </tr>
            
        </thead>
        <tbody>
            <tr>
                <td><a href="/admin/order/not-confirm-list/price_limit/1" >{{$info.order_total}}</a></td>
                <td><a href="/admin/shop/order-check-list/audit_status/1" >{{$info.shop_total}}</a></td>
                <td>{{$info.total}}</td>
            </tr>
        </tbody>
    </table>
</div>
