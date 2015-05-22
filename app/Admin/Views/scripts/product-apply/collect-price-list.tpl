<div class="title">产品限价汇总列表</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
            <tr>
                <td>单品产品</td>
                <td>组合产品</td>
                <td>团购产品</td>
                <td>渠道产品</td>
                <td>总计</td>
            </tr>
            
        </thead>
        <tbody>
            <tr>
                <td><a href="/admin/goods/price-list/price_limit/1" >{{$info.product_total}}</a></td>
                <td><a href="/admin/group-goods/price-list/price_limit/1" >{{$info.group_goods_total}}</a></td>
                <td><a href="/admin/out-tuan/goods/price_limit/1" >{{$info.outtuan_total}}</a></td>
                <td><a href="/admin/shop/goods-list/price_limit/1" >{{$info.shop_total}}</a></td>
                <td>{{$info.total}}</td>
            </tr>
        </tbody>
    </table>
</div>
