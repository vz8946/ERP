<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post">
<div class="title">{{if $action eq 'edit'}}编辑店铺{{else}}添加店铺{{/if}}</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="15%"><strong>店铺名称</strong> * </td>
      <td><input type="text" name="shop_name" id="shop_name" size="20" value="{{$data.shop_name}}" msg="请填写店铺名称" class="required" /></td>
    </tr>
    <tr> 
      <td><strong>店铺类型</strong> * </td>
      <td>
        <select name="shop_type" onchange="showConfigArea(this.value, '{{$data.shop_id}}')">
          <option value="jiankang" {{if $data.shop_type eq 'jiankang'}}selected{{/if}}>官网B2C</option>
          <option value="taobao" {{if $data.shop_type eq 'taobao'}}selected{{/if}}>淘宝</option>
          <option value="jingdong" {{if $data.shop_type eq 'jingdong'}}selected{{/if}}>京东</option>
          <option value="yihaodian" {{if $data.shop_type eq 'yihaodian'}}selected{{/if}}>一号店</option>
          <option value="dangdang" {{if $data.shop_type eq 'dangdang'}}selected{{/if}}>当当网</option>
          <option value="qq" {{if $data.shop_type eq 'qq'}}selected{{/if}}>QQ商城</option>
          <option value="alibaba" {{if $data.shop_type eq 'alibaba'}}selected{{/if}}>阿里巴巴</option>
          <option value="tuan" {{if $data.shop_type eq 'tuan'}}selected{{/if}}>团购</option>
		  <option value="credit" {{if $data.shop_type eq 'credit'}}selected{{/if}}>赊销</option>
		  <option value="distribution" {{if $data.shop_type eq 'distribution'}}selected{{/if}}>直供</option>
        </select>
      </td>
    </tr>
    <tr> 
      <td><strong>所属公司</strong> * </td>
      <td>
        <select name="company">
          <option value="1" {{if $data.company eq '1'}}selected{{/if}}>垦丰</option>
          <option value="2" {{if $data.company eq '2'}}selected{{/if}}>御网</option>
        </select>
      </td>
    </tr>
    <tr> 
      <td><strong>佣金类型</strong> * </td>
      <td>
        <input type="radio" name="commission_type" value="1" {{if $data.commission_type eq 1 || $action eq 'add'}}checked{{/if}} onclick="document.getElementById('commission_rate_row').style.display='none'">差价
        <input type="radio" name="commission_type" value="2" {{if $data.commission_type eq 2}}checked{{/if}} onclick="document.getElementById('commission_rate_row').style.display=''">提成
      </td>
    </tr>
    <tr id="commission_rate_row" {{if $data.commission_type eq '1' || $action eq 'add'}}style="display:none"{{/if}}> 
      <td><strong>佣金率</strong> * </td>
      <td>
        <input type="text" name="commission_rate" id="commission_rate" value="{{$data.commission_rate}}" size="2">%
      </td>
    </tr>
    <tr> 
      <td><strong>店铺地址</strong></td>
      <td>
		<input type="text" name="shop_url" id="shop_url" size="50" value="{{$data.shop_url}}"/>
	  </td>
    </tr>
    <tr> 
      <td><strong>自动下载订单时间间隔</strong></td>
      <td>
		<input type="text" name="sync_order_interval" id="sync_order_interval" size="2" value="{{$data.sync_order_interval}}"/>分钟 <font color="999999">(0表示不自动同步)</font>
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
<div id="configArea">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
  {{if $config}}
  {{foreach from=$config key=field item=name}}
  <tr>
    <td width="15%"><strong>{{$name}}</strong></td>
    <td><input type="text" name="config[{{$field}}]" value="{{$data.config.$field}}" size="30"></td>
  </tr>
  {{/foreach}}
  {{/if}}
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>

<script>
function showConfigArea(shopType, shopID) {
    new Request({
        url: '/admin/shop/show-config-area/shopType/' + shopType + '/shopID/' + shopID + '/r/' + Math.random(),
        onRequest: loading,
        onSuccess:function(data){
            document.getElementById('configArea').innerHTML = '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">' + data + '</table>';
        }
    }).send();
}
</script>