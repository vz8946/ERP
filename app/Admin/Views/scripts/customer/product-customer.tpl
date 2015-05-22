{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="/admin/customer/product-customer">
    <span style="float:left;line-height:18px;">
    <select name="shop_id">
        <option value="">请选择店铺</option>
        {{html_options options=$search_option.shop_info selected=$params.shop_id}}
        </select>&nbsp;&nbsp;
    </span>
    <span style="float:left;line-height:18px;">制单开始日期：</span>
    <span style="float:left;line-height:18px;"><input type="text" name="start_ts" id="start_ts" size="15" value="{{$params.start_ts}}" class="Wdate" onClick="WdatePicker()" /></span>
    <span style="float:left;line-height:18px;">结束日期：</span>
    <span style="float:left;line-height:18px;"><input type="text" name="end_ts" id="end_ts" size="15" value="{{$params.end_ts}}" class="Wdate" onClick="WdatePicker()" /></span>
    &nbsp;&nbsp;
    订单状态:<select name="status">
            <option value=''>--请选择--</option>
            {{html_options options=$search_option.order_status selected=$params.status}}
			</select>
    <br><br>
    商品编码：<input name="product_sn" id="product_sn" type="text"  size="10" value="{{$params.product_sn}}" />
    <input type="hidden" name="export" id="export" value="0" />
    <input type="button" name="dosearch" value="查询" onclick="check(0);"/>
    <input type="button" name="dosearch" value="导出" onclick="check(1);"/>
  </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
{{/if}}

<div id="ajax_search">

<div class="title">产品客户购买记录</div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
                <td>客户ID</td>
				<td>客户姓名</td>
				<td>手机</td>
				<td>电话</td>
                <td>省份</td>
				<td>店铺</td>
				<td>产品名称</td>
                <td>订单ID</td>
                <td>购买次数</td>
				<td>购买数量</td>
				<td>购买总金额</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$infos item=info}}
            <tr>
                <td>{{$info.customer_id}}</td>
                <td>{{$info.real_name}}</td>
                <td>{{$info.mobile}}</td>
                <td>{{$info.telphone}}</td>
                <td>{{$info.province_name}}</td>
                <td>{{$info.shop_name}}</td>
                <td>{{$info.goods_name}}</td>
                <td>{{$info.order_id}}</td>
                <td>{{$info.buy_count}}</td>
                <td>{{$info.buy_number}}</td>
                <td>{{$info.price_goods}}</td>
            </tr>
        {{/foreach}}
		</tbody>
		</table>
	</div>
	<div style="padding:0 5px;">
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</div>
<script language="javascript">
    function check(value)
    {
        $product_sn = $('product_sn').value;

        if ($product_sn.trim() == '') {
            alert('产品编码不能为空');
            return false;
        }

        $('export').value = value;
        $('searchForm').submit();
    }
</script>