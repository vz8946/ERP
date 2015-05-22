{{if $message}}
<script type="text/javascript">
alert('{{$message}}');
window.location='/admin/goods/price-list';
</script>
{{/if}}
<form name="searchForm" id="searchForm" action="/admin/goods/price-list/">
<div class="search">
{{$catSelect}}
上下架：<select name="onsale"><option value="" selected>请选择</option><option value="on" {{if $param.onsale eq 'on'}}selected{{/if}}>上架</option><option value="off" {{if $param.onsale eq 'off'}}selected{{/if}}>下架</option></select>
商品名称：<input type="text" name="goods_name" size="20" maxLength="50" value="{{$param.goods_name}}"/> 编码：<input type="text" name="goods_sn" size="20" maxLength="50" value="{{$param.goods_sn}}"/>
&nbsp;&nbsp;
<select name="orderby" onchange="searchForm.submit()">
  <option value="">排序方式</option>
  <option value="price" {{if $param.orderby eq 'price'}}selected{{/if}}>本店价(升序)</option>
  <option value="price desc" {{if $param.orderby eq 'price desc'}}selected{{/if}}>本店价(降序)</option>
  <option value="staff_price" {{if $param.orderby eq 'staff_price'}}selected{{/if}}>员工价(升序)</option>
  <option value="staff_price desc" {{if $param.orderby eq 'staff_price desc'}}selected{{/if}}>员工价(降序)</option>
</select>
<br>
本店价：<input type="text" name="fromprice" size="5" maxLength="6" value="{{$param.fromprice}}"/> - <input type="text" name="toprice" size="5" maxLength="6" value="{{$param.toprice}}"/>
市场价：<input type="text" name="fromprice_market" size="5" maxLength="6" value="{{$param.fromprice_market}}"/> - <input type="text" name="toprice_market" size="5" maxLength="6" value="{{$param.toprice_market}}"/>
员工价：<input type="text" name="fromprice_staff" size="5" maxLength="6" value="{{$param.fromprice_staff}}"/> - <input type="text" name="toprice_staff" size="5" maxLength="6" value="{{$param.toprice_staff}}"/>
限价：<input type="checkbox" name="price_limit" value="1" {{if $param.price_limit eq '1'}}checked='true'{{/if}}/>
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
</div>

</form>
<div class="title">商品管理</div>
<div class="content">
    <div class="sub_title">
        
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>商品编码</td>
            <td width="280px">商品名称</td>
			{{if $viewcost eq '1'}}<td>成本价</td>{{/if}}
			{{if $viewcost eq '1'}}<td>税后成本价</td>{{/if}}
			{{if $viewcost eq '1'}}<td>成本价税率</td>{{/if}}
            <td>市场价</td>
            <td>本店价</td>
            <td>保护价</td>
            <!--<td>员工价</td>-->
            <td>状态</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.goods_id}}">
        <td>{{$data.goods_id}}</td>
        <td>{{$data.goods_sn}}</td>
        <td>{{$data.goods_name}} (<font color="#FF3333">{{$data.goods_style}}</font>)</td>
        {{if  $viewcost eq '1'}}<td>{{$data.cost}}</td>{{/if}}
        {{if  $viewcost eq '1'}}<td>{{$data.cost_tax}}</td>{{/if}}
        {{if  $viewcost eq '1'}}<td>{{$data.invoice_tax_rate}}%</td>{{/if}}
        <td>{{$data.market_price}}</td>
        <td {{if $data.price < $data.price_limit}} style="color:#ff0000;"{{/if}}>{{$data.price}}</td>
        <td>{{if $data.price_limit eq 0}}无限价{{else}}{{$data.price_limit}}{{/if}}</td>
        <!--<td>{{$data.staff_price}}</td>-->
        <td>{{$data.goods_status}}</td>
        <td>
			<a href="javascript:fGo()" onclick="openDiv('{{url param.action=price param.id=$data.goods_id}}','ajax','{{$data.goods_name}} {{$data.goods_color}}',750,400)">管理价格</a>
        </td>
        </td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
