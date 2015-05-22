<div id="ajax_search">
<div class="title">已分成列表</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>登录名称</td>
            <td>领款人</td>
            <td>分成金额</td>
            <td>领款方式</td>
            <td>开户银行</td>
            <td>管理员</td>
            <td>分成时间</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$payList item=pay}}
        <tr>
            <td>{{$pay.affiliate_pay_id}}</td>
            <td>{{$pay.user_name}}</td>
            <td>{{$pay.payee}}</td>
            <td>{{$pay.amount}}</td>
            <td>{{$pay.get_money_type}}</td>
            <td>{{$pay.bank_name}}</td>
            <td>{{$pay.admin_name}}</td>
            <td>{{$pay.add_time}}</td>
            <td>
                <a href="javascript:fGo()" onclick="G('/admin/union-affiliate/view-separate/id/{{$pay.affiliate_pay_id}}')">查看</a>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
    <div class="page_nav">{{$pageNav}}</div>
</div>
</div>