{{if !$param.job}}
<div id="source_select" style="padding:10px">
<form name="searchForm" id="searchForm">
{{$catSelect}}
商品编码：<input type="text" name="goods_sn" size="12" maxLength="50" value=""/>
商品名称：<input type="text" name="goods_name" size="28" maxLength="50" value=""/>
<input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.job=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</form>
{{/if}}
<div id="ajax_search">
{{if !empty($datas)}}
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td>商品名称</td>
        <td>商品分类</td>
        <td>商品编码</td>
        <td>销售价</td>
        <td>商品状态</td>
        <td>操作</td>
    </tr>
</thead>
<tbody>
{{foreach from=$datas item=data}}
<tr>
    <td>{{$data.goods_name}}</td>
    <td>{{$data.cat_name}}</td>
    <td>{{$data.goods_sn}}</td>
    <td>{{$data.price}}</td>
    <td>{{$data.onsale}}</td>
    <td><input type="button" id="" value="选择" onclick="document.getElementById('goods_id').value='{{$data.goods_id}}';document.getElementById('goods_name').value='{{$data.goods_name}}';alertBox.closeDiv()"></td>
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