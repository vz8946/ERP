<form name="myForm" id="myForm" action="{{url}}" method="post">
<div class="title">{{if $action eq 'edit'}}编辑提货卡类型{{else}}新增提货卡类型{{/if}}</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="15%"><strong>提货卡名称</strong> * </td>
      <td><input type="text" name="card_name" id="card_name" size="20" value="{{$data.card_name}}" msg="请填写提货卡名称" class="required" /></td>
    </tr>
    <tr> 
      <td width="15%"><strong>商品范围</strong> * </td>
      <td>
        <div id="goodsArea">
        {{if $data.goods_info}}
        {{foreach from=$data.goods_info item=goods_sn name=goods_sn}}
          <div id="Goods{{$smarty.foreach.goods_sn.iteration}}">
          <input type="text" name="goods_info[]" id="goods_sn1" size="6" maxlength="8" value="{{$goods_sn}}" onkeypress="return NumOnly(event)">
          {{if $smarty.foreach.goods_sn.iteration eq 1}}
          &nbsp;&nbsp;<a href="#" onclick="addGoodsArea();return false;">添加赠品</a>
          {{else}}
          &nbsp;&nbsp;<a href="#" onclick="this.parentNode.parentNode.removeChild(this.parentNode);return false;">删除</a>
          {{/if}}
          </div>
        {{/foreach}}
        {{else}}
          <input type="text" name="goods_info[]" id="goods_sn1" size="6" maxlength="8" value="{{$goods_sn}}" onkeypress="return NumOnly(event)">
          &nbsp;&nbsp;<a href="#" onclick="addGoodsArea();return false;">添加商品 </a> <font color="666666">(请埴写商品编码，6位或8位数字)</font>
        {{/if}}
        <input type="hidden" name="GoodsNum" value="1">
        </div>
      </td>
    </tr>
    <tr> 
      <td><strong>面值</strong></td>
      <td><input type="text" name="cost" id="cost" size="6" value="{{$data.cost}}" /></td>
    </tr>
    <tr>
      <td><strong>是否启用</strong> * </td>
      <td>
	   <input type="radio" name="status" value="0" {{if $data.status==0 && $action eq 'edit'}}checked{{/if}}/> 是
	   <input type="radio" name="status" value="1" {{if $data.status==1 or $action eq 'add'}}checked{{/if}}/> 否
	  </td>
    </tr>
</tbody>
</table>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>

<script>
function NumOnly(e)
{
    var key = window.event ? e.keyCode : e.which;
    return key>=48&&key<=57||key==46||key==8;
}

function addGoodsArea()
{
    if ($('goods_sn1').value=='') {
        alert('商品编码必须填写!');
        return false;
    }
    
    document.myForm.GoodsNum.value = 1 + parseInt(document.myForm.GoodsNum.value);
    var index = parseInt(document.myForm.GoodsNum.value);
    var elm = document.createElement(" ");
    elm.innerHTML = '<div id="Goods'+index+'"><input type="text" name="goods_info[]" id="goods_sn'+index+'" size="6" maxlength="8" value="'+$('goods_sn1').value+'" onkeypress="return NumOnly(event)"/>&nbsp;&nbsp;&nbsp;<a href="#" onclick="this.parentNode.parentNode.removeChild(this.parentNode);return false;">删除</a></div>';
    var parent = $('goodsArea');
    parent.appendChild(elm);
    $('goods_sn1').value = '';
}
</script>