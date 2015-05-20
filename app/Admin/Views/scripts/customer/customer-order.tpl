<div class="title">客户订购产品详情&nbsp;&nbsp;&nbsp;
<br /><br />
<table cellpadding="0" cellspacing="0" border="0" class="table">
    <tr>
        <td width="100">客户ID：</td>
        <td>{{$customer_info.customer_id}}</td>
    </tr>
    <tr>
        <td width="100">客户名：</td>
        <td>{{$customer_info.real_name}}</td>
    </tr>
    <tr>
        <td width="100">电话：</td>
        <td>{{$customer_info.telphone}}</td>
    </tr>
    <tr>
        <td width="100">手机：</td>
        <td>{{$customer_info.mobile}}</td>
    </tr>
</table>
<br /><br />
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>单号</td>
            <td>购买时间</td>
            <td>订单金额</td>
            <td>订单状态</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$infos item=info}}
    <tr>
        <td>{{$info.batch_sn}}</td>
        <td>{{$info.created_ts}}</td>
        <td>{{$info.price_order}}</td>
        <td>{{$info.order_status}}</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>