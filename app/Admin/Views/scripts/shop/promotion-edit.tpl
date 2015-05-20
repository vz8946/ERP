<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="myForm" id="myForm" action="{{url}}" method="post">
<div class="title">{{if $action eq 'edit'}}编辑店铺活动{{else}}添加店铺活动{{/if}}</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="15%"><strong>店铺</strong> * </td>
      <td>
        <select name="shop_id">
          {{foreach from=$shopDatas item=shop}}
          {{if $shop.shop_type ne 'tuan' && $shop.shop_type ne 'jiankang'}}
          <option value="{{$shop.shop_id}}" {{if $data.shop_id eq $shop.shop_id}}selected{{/if}}>{{$shop.shop_name}}</option>
          {{/if}}
          {{/foreach}}
        </select>
      </td>
    </tr>
    <tr> 
      <td width="15%"><strong>活动名称</strong> * </td>
      <td><input type="text" name="promotion_name" id="promotion_name" size="20" value="{{$data.promotion_name}}" msg="请填写活动名称" class="required" /></td>
    </tr>
    <tr> 
      <td><strong>开始时间</strong> * </td>
      <td>
	 <span style="float:left;width:180px;">
		<input type="text" name="start_time" id="start_time" size="24" value="{{$data.start_time}}"  class="Wdate" onClick="WdatePicker()" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
	  </span>
      </td>
    </tr>
    <tr> 
      <td><strong>结束时间</strong> * </td>
      <td>
		 <span style="float:left;width:180px;">
			<input type="text" name="end_time" id="end_time" size="24" value="{{$data.end_time}}"  class="Wdate" onClick="WdatePicker()" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
		 </span>
      </td>
    </tr>
    <tr> 
      <td><strong>活动类型</strong> * </td>
      <td>
        <input type="radio" name="type" value="1" {{if $data.type eq 1 || $action eq 'add'}}checked{{/if}} onclick="showTypeArea(1)">满送
        <input type="radio" name="type" value="2" {{if $data.type eq 2}}checked{{/if}} onclick="showTypeArea(2)">买送
      </td>
    </tr>
    <tr>
      <td><strong>满足条件</strong> * </td>
      <td id="condition1_area" {{if $action eq 'edit' and $data.type ne 1}}style="display:none"{{/if}}>
        订单金额 <input type="text" name="config[condition1][from_amount]" style="width:50px" value="{{$data.config.condition1.from_amount}}"> - <input type="text" name="config[condition1][to_amount]" style="width:50px" value="{{$data.config.condition1.to_amount}}"> 元
      </td>
      <td id="condition2_area" {{if $action eq 'add' or $data.type ne 2}}style="display:none"{{/if}}>
        <div id="goodsConditionArea">
        {{if $data.config.condition2.goods_sn}}
        {{foreach from=$data.config.condition2.goods_sn item=goods_sn name=goods_sn}}
          <div id="GoodsCondition{{$smarty.foreach.goods_sn.iteration}}">
          商品编码 <input type="text" name="config[condition2][goods_sn][]" id="goods_sn{{$smarty.foreach.goods_sn.iteration}}" size="6" maxlength="9" value="{{$goods_sn}}">
          {{assign var="currentNum" value=$smarty.foreach.goods_sn.iteration-1}}
          数量达到 <input type="text" name="config[condition2][number][]" id="number{{$smarty.foreach.goods_sn.iteration}}" size="2" maxlength="2" value="{{$data.config.condition2.number.$currentNum}}" onkeypress="return NumOnly(event)">
          {{if $smarty.foreach.goods_sn.iteration eq 1}}
          &nbsp;&nbsp;<a href="#" onclick="addGoodsConditionArea();return false;">添加商品条件</a>
          {{else}}
          &nbsp;&nbsp;<a href="#" onclick="this.parentNode.parentNode.removeChild(this.parentNode);return false;">删除</a>
          {{/if}}
        {{/foreach}}
        {{else}}
          <div id="GoodsCondition1">
          商品编码 <input type="text" name="config[condition2][goods_sn][]" id="goods_sn1" size="6" maxlength="9" value="{{$data.config.condition2.goods_sn}}">
          数量达到 <input type="text" name="config[condition2][number][]" id="number1" size="2" maxlength="2" value="{{$data.config.condition2.number}}" onkeypress="return NumOnly(event)">
          &nbsp;&nbsp;<a href="#" onclick="addGoodsConditionArea();return false;">添加商品条件</a>
        </div>
        {{/if}}
        </div>
        <input type="hidden" name="GoodsNum" value="1">
        <input type="hidden" name="GoodsGiftNum" value="1">
      </td>
    <tr>
    <tr>
      <td><strong>赠送商品</strong> * </td>
      <td>
        <div id="goodsPromotionArea">
        {{if $data.config.promotion.goods_sn}}
        {{foreach from=$data.config.promotion.goods_sn item=goods_sn name=goods_sn}}
          <div id="GoodsCondition{{$smarty.foreach.goods_sn.iteration}}">
          商品编码 <input type="text" name="config[promotion][goods_sn][]" id="goods_sn_gift{{$smarty.foreach.goods_sn.iteration}}" size="6" maxlength="9" value="{{$goods_sn}}">
          {{assign var="currentNum" value=$smarty.foreach.goods_sn.iteration-1}}
          赠送数量 <input type="text" name="config[promotion][number][]" id="number_gift{{$smarty.foreach.goods_sn.iteration}}" size="2" maxlength="2" value="{{$data.config.promotion.number.$currentNum}}" onkeypress="return NumOnly(event)">
          {{if $smarty.foreach.goods_sn.iteration eq 1}}
          &nbsp;&nbsp;<a href="#" onclick="addGoodsPromotionArea();return false;">添加赠品</a>
          <input type="checkbox" name="config[promotion][cycle]" value="1" {{if $data.config.promotion.cycle}}checked{{/if}}>数量叠加
          {{else}}
          &nbsp;&nbsp;<a href="#" onclick="this.parentNode.parentNode.removeChild(this.parentNode);return false;">删除</a>
          {{/if}}
        {{/foreach}}
        {{else}}
          商品编码 <input type="text" name="config[promotion][goods_sn][]" id="goods_sn_gift1" size="6" maxlength="9" value="{{$data.config.promotion.goods_sn}}">
          赠送数量 <input type="text" name="config[promotion][number][]" id="number_gift1" size="2" maxlength="2" value="{{$data.config.promotion.number}}" onkeypress="return NumOnly(event)">
          &nbsp;&nbsp;<a href="#" onclick="addGoodsPromotionArea();return false;">添加赠品</a>
          <input type="checkbox" name="config[promotion][cycle]" value="1" {{if $data.config.promotion.cycle}}checked{{/if}}>数量叠加
        {{/if}}
        </div>
      </td>
    </tr>
    <tr> 
      <td><strong>同类活动优先级</strong> * </td>
      <td>
        <input type="text" name="sort" id="sort" size="1" value="{{if $data.sort}}{{$data.sort}}{{else}}0{{/if}}" class="required" onkeypress="return NumOnly(event)"/>
        <font color="888888">(当优先级大于0时，排斥其它满足条件的同类活动，数值越大越优先启用)</font>
	  </td>
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
var cur_start_date = '';
function changeStartToDate(obj) {
    if (!cur_start_date) {
        cur_start_date = $('start_date').value.substr(0,10);
    }
    $('start_date').value = cur_start_date + ' ' + obj.value;
}
var cur_to_date = '';
function changeToDate(obj) {
    if (!cur_to_date) {
        cur_to_date = $('end_date').value.substr(0,10);
    }
    $('end_date').value = cur_to_date + ' ' + obj.value;
}

function showTypeArea(type) {
    if ( type == 1 ) {
        document.getElementById('condition1_area').style.display = '';
        document.getElementById('condition2_area').style.display = 'none';
    }
    if ( type == 2 ) {
        document.getElementById('condition1_area').style.display = 'none';
        document.getElementById('condition2_area').style.display = '';
    }
}

function addGoodsConditionArea()
{
    if ($('goods_sn1').value=='') {
        alert('商品编码必须填写!');
        return false;
    }
    if (($('number1').value=='') || ($('number1').value=='0')) {
        alert('商品数量必须填写!');
        return false;
    }

    document.myForm.GoodsNum.value = 1 + parseInt(document.myForm.GoodsNum.value);
    var index = parseInt(document.myForm.GoodsNum.value);
    var elm = document.createElement(" ");
    elm.innerHTML = '<div id="GoodsCondition'+index+'">商品编码 <input type="text" name="config[condition2][goods_sn][]" id="goods_sn'+index+'" size="6" maxlength="9" value="'+$('goods_sn1').value+'" onkeypress="return NumOnly(event)"/> 数量达到 <input type="text" name="config[condition2][number][]" id="number'+index+'" size="2" maxlength="2" value="'+$('number1').value+'">&nbsp;&nbsp;&nbsp;<a href="#" onclick="this.parentNode.parentNode.removeChild(this.parentNode);return false;">删除</a></div>';
    var parent = $('goodsConditionArea');
    parent.appendChild(elm);
    $('goods_sn1').value = '';
    $('number1').value = '1';
}

function addGoodsPromotionArea()
{
    if ($('goods_sn_gift1').value=='') {
        alert('商品编码必须填写!');
        return false;
    }
    if (($('number_gift1').value=='') || ($('number_gift1').value=='0')) {
        alert('赠送数量必须填写!');
        return false;
    }
    
    document.myForm.GoodsGiftNum.value = 1 + parseInt(document.myForm.GoodsGiftNum.value);
    var index = parseInt(document.myForm.GoodsGiftNum.value);
    var elm = document.createElement(" ");
    elm.innerHTML = '<div id="GoodsPromotion'+index+'">商品编码 <input type="text" name="config[promotion][goods_sn][]" id="goods_sn_gift'+index+'" size="6" maxlength="9" value="'+$('goods_sn_gift1').value+'" onkeypress="return NumOnly(event)"/> 赠送数量 <input type="text" name="config[promotion][number][]" id="number_gift'+index+'" size="2" maxlength="2" value="'+$('number_gift1').value+'" onkeypress="return NumOnly(event)">&nbsp;&nbsp;&nbsp;<a href="#" onclick="this.parentNode.parentNode.removeChild(this.parentNode);return false;">删除</a></div>';
    var parent = $('goodsPromotionArea');
    parent.appendChild(elm);
    $('goods_sn_gift1').value = '';
    $('number_gift1').value = '1';
}

function NumOnly(e)
{
    var key = window.event ? e.keyCode : e.which;
    return key>=48&&key<=57||key==46||key==8;
}
</script>