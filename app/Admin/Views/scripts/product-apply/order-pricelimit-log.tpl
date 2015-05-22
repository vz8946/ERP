<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm" onsubmit="return check();" action="/admin/product-apply/order-pricelimit-log">
<div>
    <span style="margin-left:10px">店铺：
        <select name="shop_id">
        <option value="">请选择</option>
        {{html_options options=$search_option.shop_info selected=$params.shop_id}}
        </select>
    </span>
    <span style="margin-left:10px">渠道类型：
        <select name="channel_type">
        <option value="">请选择</option>
        {{html_options options=$search_option.channel_type selected=$params.channel_type}}
        </select>
    </span>
    <span> 产品编码：
        <input type="text" value="{{$params.product_sn}}" maxlength="10" size="10" name="product_sn">
    </span>
    <span> 订单号：
        <input type="text" value="{{$params.batch_sn}}" name="batch_sn">
    </span>
</div>
<input type="submit" name="dosearch" value="查询" />
</div>
</form>
</div>
<div class="title">超限价订单审核记录</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>产品ID</td>
            <td>产品编码</td>
            <td>产品类型</td>
            <th>渠道类型</th>
            <td>保护价格</td>
            <td>销售均价</td>
            <td>订单号</td>
            <td>店铺</td>
            <td>订单金额</td>
            <td>审核人</td>
            <td>审核时间</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$infos item=info}}
        <tr>
            <td>{{$info.product_id}}</td>
            <td>{{$info.product_sn}}</td>
            <td>{{$info.product_type}}</td>
            <td>{{$info.channel_type}}</td>
            <td>{{$info.price_limit}}</td>
            <td>{{$info.avg_price}}</td>
            <td>{{$info.batch_sn}}</td>
            <td>{{$info.shop_name}}</td>
            <td>{{$info.price_order}}</td>
            <td>{{$info.audit_by}}</td>
            <td>{{$info.audit_ts}}</td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
    <div class="page_nav">{{$pageNav}}</div>
</div>
