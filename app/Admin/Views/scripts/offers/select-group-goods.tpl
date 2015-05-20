{{if !$param.job}}
<div style="width:600px; margin:0 auto">
<ul>
<li style="list-style-type: none">
<form name="searchForm" id="searchForm">
<div style="padding: 5px 0px; width:600px;">
    <div style="float:left">
    <span style="margin-left:5px; vertical-align:top">套餐名称: </span><input type="text" name="goods_name" value="" size="20" />
    <span style="margin-left:5px; vertical-align:top">套餐编码: </span><input type="text" name="goods_sn" value="" size="20" />
    <span style="margin-left:10px"></span>
    <input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.job=search}}','ajax_search')"/>
    </div>
    <div style="float:right"><input type="button" onclick="closeGoodsWin()" value=" 关闭 " /></div>
</div>
</form>
</li>
<li style="list-style-type: none">
{{/if}}
<div id="ajax_search">
<div style="width:600px; height: 415px; float:left; background-color:#FFFFFF; border:1px solid Silver; overflow:auto;">
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table" style="background-color: #fff">
        <thead>
        <tr>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x">ID</td>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x">套餐名称</td>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x">套餐编码</td>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x">销售价</td>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x">状态</td>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x">操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$goodsMessage item=goods name=goods}}
        <tr id="ajax_list{{$goods.goods_id}}">
            <td>{{$goods.group_id}}</td>
            <td>{{$goods.group_goods_name|cn_truncate:45:"..."}}</td>
            <td>{{$goods.group_sn}}</td>
            <td>{{$goods.group_price}}</td>
            <td>{{$goods.status}}</td>
            <td><input type="button" id="select_button_{{$goods.group_id}}" value="选择" onclick="addDiscountGoods('{{$intype}}','{{$goods.group_id}}','','{{$goods.group_goods_name}}','{{$goods.group_price}}', '{{$goods.price_limit}}', '{{$offersType}}')" /></td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
    <div style="float:left"><input type="button" onclick="closeGoodsWin()" value=" 关闭 " /></div><div class="page_nav" style="padding:5px 2px 0px 0px">{{$pageNav}}</div>
</div>
</div>
{{if !$param.job}}
</li>
</ul>
</div>
{{/if}}