<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm" onsubmit="return check();" action="/admin/product-apply/index">
<div>
    <span style="float:left">活动开始日期：
        <input type="text"  value="{{$params.start_ts}}" id="start_ts"  name="start_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span>
    <span style="margin-left:10px">
        活动截止日期：<input  type="text"  value="{{$params.end_ts}}" id="end_ts"  name="end_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span>
    <span style="margin-left:10px">店铺：
        <select name="shop_id">
        <option value="">请选择</option>
        {{html_options options=$search_option.shop_info selected=$params.shop_id}}
        </select>
    </span>
    <span style="margin-left:10px">商品类型：
        <select name="type">
        <option value="">请选择</option>
        {{html_options options=$search_option.type selected=$params.type}}
        </select>
    </span>
    <span> 产品编码：
        <input type="text" value="{{$params.product_sn}}" maxlength="10" size="10" name="product_sn">
    </span>
    <span>
     产品名称：
    <input type="text" value="{{$params.product_name}}" maxlength="50" size="20" name="product_name">
    </span>
</div>
<input type="submit" name="dosearch" value="查询" />
</div>
</form>
</div>
<div class="title">产品保护价申请列表</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>店铺</td>
            <td>编码</td>
            <th>名称</th>
            <td>类型</td>
            <td>保护价格</td>
            <td>活动开始时间</td>
            <td>活动结束时间</td>
            <td>备注</td>
            <td>创建人</td>
			<td>创建时间</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$infos item=info}}
        <tr>
            <td>{{$info.product_apply_id}}</td>
            <td>{{$info.shop_name}}</td>
            <td>{{$info.product_sn}}</td>
            <td>{{$info.product_name|cn_truncate:40:'...'}}</td>
            <td>{{$info.type}}</td>
            <td>{{$info.price_limit}}</td>
            <td>{{$info.start_ts}}</td>
            <td>{{$info.end_ts}}</td>
            <td>{{$info.remark|cn_truncate:40:'...'}}</td>
            <td>{{$info.created_by}}</td>
            <td>{{$info.created_ts}}</td>
            <td><a href="/admin/product-apply/edit/apply_id/{{$info.product_apply_id}}">编辑</a></td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
    <div class="page_nav">{{$pageNav}}</div>
</div>
