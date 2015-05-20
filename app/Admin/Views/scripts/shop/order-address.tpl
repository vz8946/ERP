<div style="height:200px;">
<form method="post" action="/admin/shop/order-address">
<input type="hidden" name="url" id="url" value="{{$url}}">
<input type="hidden" name="id" value="{{$data.shop_order_id}}">
<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="20px"><br><br><br><br><br><br><br><br></td>
<td>
  订单号：{{$data.external_order_sn}}<br>
  地址：<input type="text" name="addr_address" value="{{$data.addr_address}}" size="56"><br>
  收货人：<input type="text" name="addr_consignee" value="{{$data.addr_consignee}}" size="8"><br>
  联系电话：<input type="text" name="addr_tel" value="{{$data.addr_tel}}" size="16">　　手机：<input type="text" name="addr_mobile" value="{{$data.addr_mobile}}" size="16">
</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>
    <select name="province_id" id="province_id" onchange="changeArea('province', this.value)">
      {{if $provinceList}}
      {{foreach from=$provinceList item=province}}
        <option value="{{$province.province_id}}" {{if $data.addr_province_id eq $province.province_id}}selected{{/if}}>{{$province.province}}</option>
      {{/foreach}}
      {{/if}}
    </select>
    -
    <span id="cityArea">
    <select name="city_id" id="city_id" onchange="changeArea('city', this.value)">
      {{if $cityList}}
      {{foreach from=$cityList item=city}}
        <option value="{{$city.city_id}}" {{if $data.addr_city_id eq $city.city_id}}selected{{/if}}>{{$city.city}}</option>
      {{/foreach}}
      {{/if}}
    </select>
    </span>
    -
    <span id="areaArea">
    <select name="area_id" id="area_id">
      {{if $areaList}}
      {{foreach from=$areaList item=area}}
        <option value="{{$area.area_id}}" {{if $data.addr_area_id eq $area.area_id}}selected{{/if}}>{{$area.area}}</option>
      {{/foreach}}
        <option value="-1" {{if $data.addr_area_id eq -1}}selected{{/if}}>其它区</option>
      {{/if}}
    </select>
    </span>
    &nbsp;
    <input type="submit" name="submit" value="修改">
  </td>
</tr>
</table>
</div>
