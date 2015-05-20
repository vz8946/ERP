<style type="text/css">
.dotline {
border-bottom-color:#666666;
border-bottom-style:dotted;
border-bottom-width:1px;
}
</style>
<form id="myform">
<br>
  <table width="100%" border="0">
        <tr bgcolor="#F0F1F2">
          <td width="100">　提货卡名称：</td>
          <td>{{$data.card_name}}</td>
        </tr>
  </table>
  <br>
  <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
    <tr>
      <td>商品名称</td>
      <td>商品规格</td>
      <td>商品编码</td>
      <td>本店价</td>
      <td>数量</td>
    </tr>
    </thead>
    {{foreach from=$data.detail item=goods}}
    {{if $goods.goods}}
    <tr>
      <td>{{$goods.group_goods_name}}</td>
      <td>{{$goods.group_specification}}</td>
      <td>{{$goods.group_sn}}</td>
      <td>{{$goods.group_price}}</td>
    </tr>
    {{foreach from=$goods.goods item=goods1}}
    <tr>
      <td><font color="999999">{{$goods1.goods_name}}</font></td>
      <td><font color="999999">{{$goods1.goods_style}}</font></td>
      <td><font color="999999">{{$goods1.goods_sn}}</font></td>
      <td><font color="999999">{{$goods1.price}}</font></td>
      <td><font color="999999">{{$goods1.number}}</font></td>
    </tr>
    {{/foreach}}
    {{else}}
    <tr>
      <td>{{$goods.goods_name}}</td>
      <td>{{$goods.goods_style}}</td>
      <td>{{$goods.goods_sn}}</td>
      <td>{{$goods.price}}</td>
    </tr>
    {{/if}}
    {{/foreach}}
  </table>
<br>
