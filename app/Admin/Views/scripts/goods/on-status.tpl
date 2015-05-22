<form name="searchForm" id="searchForm" action="/admin/goods/on-status/">
<div class="search">
{{$catSelect}}
编码：<input type="text" name="product_sn" size="20" maxLength="50" value="{{$param.product_sn}}"/>
<br>
<br>
名称：<input type="text" name="goods_name" size="20" maxLength="50" value="{{$param.goods_name}}"/>
<input type="submit" name="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">
</div>
</form>

<form name="myForm" id="myForm">

<div class="title">商品批量上架</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td width="30">全选</td>
            <td>ID</td>
            <td>商品编码</td>
            <td>商品名称</td>
            <td>可销售库存</td>
            <td>商品上下架</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.stock_id}}">
        <td><input type="checkbox" name="ids[]" value="{{$data.product_id}}^{{$data.goods_id}}"/></td>
        <td>{{$data.product_id}}</td>
        <td>{{$data.product_sn}}</td>
        <td>{{$data.goods_name}}</td>
        <td>{{$data.able_number}}</td>
        <td id="ajax_status{{$data.goods_id}}">{{$data.status}}{{if $data.onoff_remark}}({{$data.onoff_remark}}){{/if}}</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>

<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> 全选/全不选<br>
<input type="checkbox" name="updategoods" value="1" checked/> 商品自动上架<input type="button" value="批量上架" onclick="ajax_submit(this.form, '{{url}}','Gurl(\'refresh\',\'ajax_search\')')">
</div>

</form>
