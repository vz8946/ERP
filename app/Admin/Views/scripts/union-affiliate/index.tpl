{{if !$param.do}}
<div class="search">
    <form id="searchForm">
<div style="clear:both; padding-top:5px">
   请选择搜索  ID：<input type="text" name="user_id" value="{{$param.user_id}}">
    登录用户名：<input type="text" name="user_name"  value="{{$param.user_name}}">
	分成金额大于：<input type="text"  size="10" name="affiliate_money_from" value="{{$param.affiliate_money_from}}" > 
	小于 <input type="text" size="10" name="affiliate_money_to"  value="{{$param.affiliate_money_to}}">
    <input type="button" name="dosearch" value=" 搜 索 " onclick="ajax_search($('searchForm'),'{{url param.do=search}}','ajax_search')"/>
    </div>	
    </form>
</div>
<div id="ajax_search">
{{/if}}
<div class="title">可打款列表</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>登录名称</td>
            <td>有效订单数</td>
            <td>订单金额</td>
            <td>商品金额</td>
            <td>可用于分成金额</td>
            <td>分成金额</td>
            <td>银行打款最低额度</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$payList item=pay}}
        <tr>
            <td>{{$pay.user_id}}</td>
            <td><a href="javascript:fGo()" onclick="G('/admin/union-affiliate/view-union/user_id/{{$pay.user_id}}/user_name/{{$pay.user_name|escape:"url"}}')" title="查看联盟信息">{{$pay.user_name}}</a></td>
            <td>{{$pay.order_num}}</td>
            <td>{{$pay.order_price}}</td>
            <td>{{$pay.order_price_goods}}</td>
            <td>{{$pay.order_affiliate_amount}}</td>
            <td>{{$pay.affiliate_money}}</td>
            <td>{{$pay.payLimit}}</td>
            <td>
                <a href="javascript:fGo()" onclick="G('/admin/union-affiliate/view-affiliate/user_id/{{$pay.user_id}}/user_name/{{$pay.user_name|escape:"url"}}')">订单分成列表</a> | 
                <a href="javascript:fGo()" onclick="G('/admin/union-affiliate/affiliate/user_id/{{$pay.user_id}}/order_num/{{$pay.order_num}}/order_price/{{$pay.order_price}}/order_price_goods/{{$pay.order_price_goods}}/order_affiliate_amount/{{$pay.order_affiliate_amount}}/affiliate_money/{{$pay.affiliate_money}}')">确定分成</a>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
    <div class="page_nav">{{$pageNav}}</div>
</div>
</div>