<form name="myForm1" id="myForm1" action="{{url}}" method="post" target="ifrmSubmit" onsubmit="return check();">
<input type="hidden" name="old_value" value='{{$old_value}}'>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
  <tr>
    <td width="10%"><a href="#" onclick="document.getElementById('common').style.display='';document.getElementById('seg').style.display='none';return false;">单品价格</a></td>
    <td><a href="#" onclick="document.getElementById('common').style.display='none';document.getElementById('seg').style.display='';return false;">多个数量价格</a></td>
  </tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form" id="common">
<tbody>
    <tr> 
      <td width="10%"><strong>商品名称</strong> * </td>
      <td>{{$data.goods_name}}</td>
    </tr>
    <tr> 
      <td width="10%"><strong>商品编码</strong> * </td>
      <td>{{$data.goods_sn}}</td>
    </tr>
    <!--
    <tr> 
      <td width="10%"><strong>员工价</strong> * </td>
      <td><input type="text" name="staff_price" size="8" value="{{$data.staff_price}}" msg="请填写员工价" class="required" /></td>
    </tr>
    -->
    <tr>
      <td width="10%"><strong>市场价</strong> * </td>
      <td><input type="text" name="market_price" size="8" value="{{$data.market_price}}" msg="请填写市场价" class="required" /></td>
    </tr>
    <tr> 
      <td width="10%"><strong>本店价</strong> * </td>
      <td><input type="text" name="price" id="price" size="8" value="{{$data.price}}" msg="请填写本店价" class="required" onchange = "changePrice('{{$data.price_limit}}', this)"/></td>
    </tr>
    <tr> 
      <td width="10%"><strong>保护价</strong> * </td>
      <td>{{if $data.price_limit eq 0}}无限价{{else}}{{$data.price_limit}}{{/if}}</td>
    </tr>
    <tr>
      <td width="10%"><strong>更改备注</strong></td>
      <td><textarea name="remark" style="width:500px; height:80px;"></textarea></td>
    </tr>
</tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form" id="seg" style="display:none">
  <tr> 
    <td width="10%"><strong>数量区间</strong></td>
    <td width="18%">
      <input type="text" name="quantity1_from" size="2" value="{{$data.price_seg.0.1}}" onkeypress="return NumOnly(event)"/> - <input type="text" name="quantity1_to" size="2" value="{{$data.price_seg.0.2}}" onkeypress="return NumOnly(event)"/>
    </td>
    <td width="6%"><strong>价格</strong></td>
    <td width="10%"><input type="text" name="price1" size="2" value="{{$data.price_seg.0.0}}" onkeypress="return NumOnly(event)"/></td>
    <td>
      示例 数量区间 2-5 &nbsp;&nbsp;价格20.5
    </td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>
      <input type="text" name="quantity2_from" size="2" value="{{$data.price_seg.1.1}}" onkeypress="return NumOnly(event)"/> - <input type="text" name="quantity2_to" size="2" value="{{$data.price_seg.1.2}}" onkeypress="return NumOnly(event)"/>
    </td>
    <td>&nbsp;</td>
    <td><input type="text" name="price2" size="2" value="{{$data.price_seg.1.0}}" onkeypress="return NumOnly(event)"/></td>
    <td>
      　　 数量区间 6-10 价格15
    </td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>
      <input type="text" name="quantity3_from" size="2" value="{{$data.price_seg.2.1}}" onkeypress="return NumOnly(event)"/> - <input type="text" name="quantity3_to" size="2" value="{{$data.price_seg.2.2}}" onkeypress="return NumOnly(event)"/>
    </td>
    <td>&nbsp;</td>
    <td><input type="text" name="price3" size="2" value="{{$data.price_seg.2.0}}" onkeypress="return NumOnly(event)"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>
      <input type="text" name="quantity4_from" size="2" value="{{$data.price_seg.3.1}}" onkeypress="return NumOnly(event)"/> - <input type="text" name="quantity4_to" size="2" value="{{$data.price_seg.3.2}}" onkeypress="return NumOnly(event)"/>
    </td>
    <td>&nbsp;</td>
    <td><input type="text" name="price4" size="2" value="{{$data.price_seg.3.0}}" onkeypress="return NumOnly(event)"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>
      <input type="text" name="quantity5_from" size="2" value="{{$data.price_seg.4.1}}" onkeypress="return NumOnly(event)"/> - <input type="text" name="quantity5_to" size="2" value="{{$data.price_seg.4.2}}" onkeypress="return NumOnly(event)"/>
    </td>
    <td>&nbsp;</td>
    <td><input type="text" name="price5" size="2" value="{{$data.price_seg.4.0}}" onkeypress="return NumOnly(event)"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="4" align=center>
      * 起始数量必须大于等于2，最后一个数量区间可以只填写起始数量<br>
      * 数量区间必须连续<br>
      * 价格如果是整数，可以不输小数点
    </td>
  </tr>
</table>

<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
function NumOnly(e)
{
    var key = window.event ? e.keyCode : e.which;
    return key>=48&&key<=57||key==46||key==8;
}

function changePrice(price_limit, obj)
{
    if (isNaN(obj.value)) {
        alert('价格不正确');
        obj.value = '0';
        return false;
    }
    if (parseFloat(price_limit) > 0 && parseFloat(obj.value) < parseFloat(price_limit)) {
        alert('价格不能低于保护价');
        return false;
    }
}

function check()
{
    if (!confirm('确定要修改吗？')) {
        return false;
    }

    var price_limit = '{{$data.price_limit}}';

    if (parseFloat(price_limit) > 0 && parseFloat($('price').value) < parseFloat(price_limit)) {
        alert('价格不能低于保护价');
        return false;
    }
}

</script>