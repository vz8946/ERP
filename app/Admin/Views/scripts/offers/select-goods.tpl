{{if !$param.job}}
<div style="width:600px; margin:0 auto">
<ul>
<li style="list-style-type: none">
<form name="searchForm" id="searchForm">
<div style="padding: 5px 0px; width:600px;">
    <span style="margin-left:5px; vertical-align:top">商品分类: </span>
    {{$catSelect}}
    <div style="float:left">
    <span style="margin-left:5px; vertical-align:top">商品名称: </span><input type="text" name="goods_name" value="" size="20" />
    <span style="margin-left:5px; vertical-align:top">商品编码: </span><input type="text" name="goods_sn" value="" size="20" />
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
            <td style="background: url(/images/admin/table_thead.gif) repeat-x">商品名称</td>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x">商品分类</td>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x">商品编码</td>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x">销售价</td>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x">状态</td>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x">操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$goodsMessage item=goods name=goods}}
        <tr id="ajax_list{{$goods.goods_id}}">
            <td>{{$goods.goods_id}}</td>
            <td>{{$goods.goods_name|cn_truncate:20:"..."}}</td>
            <td>{{$goods.view_cat_name|cn_truncate:10:"..."}}</td>
            <td>{{$goods.goods_sn}}</td>
            <td>{{$goods.price}}</td>
            <td>{{$goods.onsale}}</td>
            {{if $offersType eq "assign-goods"}}
            <td><input type="button" id="select_button_{{$goods.goods_id}}" value="选择" onclick="addGift('{{$intype}}','{{$goods.goods_id}}','{{$goods.goods_sn}}','{{$goods.goods_name}}','{{$goods.price}}', '{{$goods.price_limit}}', '{{$offersType}}','{{$index}}')" /></td>
           {{else}}
             <td><input type="button" id="select_button_{{$goods.goods_id}}" value="选择" onclick="addDiscountGoods('{{$intype}}','{{$goods.goods_id}}','{{$goods.goods_sn}}','{{$goods.goods_name}}','{{$goods.price}}', '{{$goods.price_limit}}', '{{$offersType}}')" /></td>
          {{/if}} 
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