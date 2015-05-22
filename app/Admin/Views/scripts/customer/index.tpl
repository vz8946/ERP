<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm" onsubmit="return check();" action="/admin/customer/index">
<div>
    <span style="float:left">开始日期：
        <input type="text"  value="{{$params.start_ts}}" id="start_ts"  name="start_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span>
    <span style="margin-left:10px">
        截止日期：<input  type="text"  value="{{$params.end_ts}}" id="end_ts"  name="end_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span>
    <span style="margin-left:10px">店铺：
        <select name="shop_id">
        <option value="">请选择</option>
        {{html_options options=$search_option.shop_info selected=$params.shop_id}}
        </select>
    </span>
</div>
<input type="submit" name="dosearch" value="查询" />
</div>
</form>
</div>
<div class="title">信息列表</div>
<div class="content">
    <div class="sub_title">
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>客户ID</td>
            <td>店铺</td>
            <th>客户姓名</th>
            <td>电话</td>
            <td>手机</td>
            <td>第一次购买时间</td>
            <td>最后一次购买时间</td>
            <td>省份</td>
            <td>订单总数</td>
            <td>总消费额</td>
            <td>商品总数</td>
            <td>创建时间</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$infos item=info}}
        <tr>
            <td>{{$info.customer_id}}</td>
            <td>{{$info.shop_name}}</td>
            <td>{{$info.real_name}}</td>
            <td>{{$info.telphone}}</td>
            <td>{{$info.mobile}}</td>
            <td>{{$info.first_order_time}}</td>
            <td>{{$info.last_order_time}}</td>
            <td>{{$info.province_name}}</td>
            <td>{{$info.order_count}}</td>
            <td>{{$info.price_order}}</td>
            <td>{{$info.number}}</td>
            <td>{{$info.created_ts}}</td>
            <td><a href="/admin/customer/customer-product/customer_id/{{$info.customer_id}}" >订购产品详情</a> | <a href="/admin/customer/customer-order/customer_id/{{$info.customer_id}}" >订购订单详情</a></td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
    <div class="page_nav">{{$pageNav}}</div>
</div>
