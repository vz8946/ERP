{{if !$param.job}}
<div id="source_select" style="padding:10px">
<form name="searchForm" id="searchForm">
{{$catSelect}}
团购商品标题：<input type="text" name="title" size="12" maxLength="50" value=""/>
<input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.job=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</form>
{{/if}}
<div id="ajax_search">
{{if !empty($datas)}}
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td>团购商品标题</td>
        <td>商品编号</td>
        <td>商品名称</td>
        <td>销售价</td>
        <td>团购价</td>
        <td>库存</td>
        <td>商品状态</td>
        <td>操作</td>
    </tr>
</thead>
<tbody>
{{foreach from=$datas item=data}}
<tr>
    <td>{{$data.title}}</td>
    <td>{{$data.goods_sn}}</td>
    <td>{{$data.goods_name}}</td>
    <td>{{$data.price}}</td>
    <td>{{$data.price_tuan}}</td>
    <td>{{$data.able_number}}</td>
    <td>{{if $data.onsale eq 0}}上架{{else}}下架{{/if}}</td>
    <td><input type="button" id="" value="选择" onclick="document.getElementById('goods_id').value='{{$data.id}}';document.getElementById('goods_title').value='{{$data.title}}';document.getElementById('price').value='{{$data.price_tuan}}';alertBox.closeDiv()"></td>
</tr>
{{/foreach}}
</tbody>
</table>
<div class="page_nav">{{$pageNav}}</div>
{{/if}}
{{if !$param.job}}
</div>
{{/if}}
</div>