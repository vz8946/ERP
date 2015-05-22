<script type="text/javascript" src="/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="/scripts/kindeditor/lang/zh_CN.js"></script>
<form name="myForm" id="myForm" onSubmit="return checkform()" action="/admin/group-goods/sale-edit" method="post" enctype="multipart/form-data">
<input type="hidden" name="group_id" value="{{$data.group_id}}" />
<div class="title">商品管理 -&gt; 商品组合套装管理 -&gt; 添加组合套装</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
	<tr>
      <td width="150"><strong>套餐名称：</strong></td>
      <td>{{$data.group_goods_name}}</td>
    </tr>
	<tr>
      <td width="150"><strong>套餐编号：</strong></td>
      <td><input type="text" name="group_sn" size="8" id="group_sn" disabled="disabled"  value="{{$data.group_sn}}"></td>
    </tr>
    <tr>
      <td width="150"><strong>套餐销售名称：</strong></td>
      <td><input type="text" name="group_sale_name" size="90"  id="group_sale_name"   value="{{$data.group_sale_name}}"></td>
    </tr>
    <tr>
      <td><strong>类型：</strong></td>
      <td>
        <input type="radio" name="type" id="type" value="A" {{if $data.type eq 'A' || $data.type eq ''}}checked{{/if}}>A类(混合商品)
        <input type="radio" name="type" id="type" value="B" {{if $data.type eq 'B'}}checked{{/if}}>B类(同件商品-按总价)
        <input type="radio" name="type" id="type" value="C" {{if $data.type eq 'C'}}checked{{/if}}>C类(同件商品-按买赠)
      </td>
    </tr>
    
    <tr>
      <td><strong>是否官网销售：</strong></td>
      <td>
        是
      </td>
    </tr>
	<tr>
      <td width="150"><strong>套餐规格：</strong></td>
      <td>{{$data.group_specification}}</td>
    </tr>
	<tr>
      <td width="150"><strong>简单描述：</strong></td>
      <td>
      <textarea name="group_goods_alt" id="group_goods_alt" cols="45" rows="5">{{$data.group_goods_alt}} </textarea>
      </td>
    </tr>
    <tr>
      <td><strong>市场价：</strong></td>
      <td>
      <input type="text" name="group_market_price" id="group_market_price" onblur="checkPrice(this)" value="{{$data.group_market_price}}">
      <strong>本店价：</strong><input type="text" name="group_price" id="group_price"  value="{{$data.group_price}}" onblur="changePrice('{{$data.price_limit}}', 'group_price')">&nbsp;&nbsp;<span style="color:#999;">建议零售价：{{$data.suggest_market_price}},最低限价:{{if $data.price_limit eq 0}}无限价{{else}}{{$data.price_limit}}{{/if}}</span>
      <input type="hidden" id="price_limit" value="{{$data.price_limit}}" />
      </td>
    </tr>
    <tr>
      <td><strong>上传图片：</strong></td>
      <td>
        <input type="file" name="group_goods_img" /> {{if  $data.group_goods_img}} <img width="65px" src="/{{$data.group_goods_img|replace:'.':'_60_60.'}}"/>  {{/if}}
      </td>
    </tr>
    <tr>
      <td><strong>左上角小图片：</strong></td>
      <td>
        <input type="file" name="alt_img" />{{if  $data.alt_img}} <img width="60px" src="/{{$data.alt_img}}"/>  {{/if}}  <input type="button" onclick="delLittleImg({{$data.group_id}},'alt_img');" value="删除" />
      </td>
    </tr>
    <tr>
      <td><strong>右下角小图片：</strong></td>
      <td>
        <input type="file" name="bevel_img" />  {{if  $data.bevel_img}} <img  width="60px" src="/{{$data.bevel_img}}"/>  {{/if}}   <input type="button" onclick="delLittleImg({{$data.group_id}},'bevel_img');" value="删除" />
      </td>
    </tr>

    <tr>
      <td><strong>描述备注</strong></td>
      <td>
	 
		<textarea name="group_goods_desc" id="group_goods_desc" rows="20" style="width:680px; height:260px;">{{$data.group_goods_desc}}</textarea>
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				K.create('textarea[name="group_goods_desc"]', {
				            filterMode : false,
							allowFileManager : true
						});
			});
		</script>
	  </td>
    </tr>
</tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
    <tr>
        <td>product_id</td>
        <td>product_sn</td>
        <td>商品名称</td>
        <td>数量</td>
    </tr>
</thead>
<tbody id="list">
{{if $goods}}
  {{foreach from=$goods item=list}}
  <tr id="sid{{$list.product_id}}">
    <td>{{$list.product_id}}</td>
    <td>{{$list.product_sn}}</td>
    <td>{{$list.product_name}}</td>
    <td>{{$list.number}}</td>
  </tr>
  {{/foreach}}
</tbody>
{{else}}
<tbody id="list"></tbody>
{{/if}}
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit1" id="dosubmit1" value="提交"/> <input type="reset" name="reset" value="重置" /></div>
</form>
<script language="JavaScript">
function checkform(){
    
    var group_price = document.getElementById('group_price').value;
    var price_limit  = document.getElementById('price_limit').value;
    if (parseFloat(group_price) < parseFloat(price_limit)) {
        alert('本店价不能小于最低限价');
        return false;
    }
	if($('group_goods_desc').value.trim()==''){
		alert("请填写描述备注！");return false;
	}
	return true;
}

function checkNum(obj, num)
{
	if (parseInt(obj.value) == 0 || isNaN(obj.value) ||obj.value=='') {
	    alert('请填写正整数');
	    obj.value = 1;
	    return false;
	}
}
function checkPrice(obj, num)
{
    if (isNaN(obj.value)) {
       alert('价格不正确');
       obj.value = 0;
       return false;
   }
}
//删除小图片
function delLittleImg(id,ziduan){
	if(id<1){alert('参数错误');return;}
	if(ziduan==''){alert('参数错误');return;}
	new Request({
		url:'/admin/group-goods/gengxin/id/'+id+'/ziduan/'+ziduan,
		onSuccess:function(msg){
			if(msg!=''){alert(msg);}
			else{alert('删除成功');location.reload();}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}

function changePrice(price_limit,obj_str)
{
   var obj = document.getElementById(obj_str);
   if (isNaN(obj.value)) {
       alert('价格不正确');
       obj.value = price_limit;
       return false;
   }
   if (parseFloat(price_limit) > 0 && parseFloat(obj.value) < parseFloat(price_limit)) {
        alert('价格不能小于最低限价');
        obj.value=price_limit;
        return false;
   }
}
    
</script>