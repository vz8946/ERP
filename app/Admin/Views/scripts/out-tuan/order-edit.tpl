<div class="title">修改外部团购订单</div>
<div class="content">
<form name="myForm" id="myForm" action="/admin/out-tuan/order-edit" method="post" onSubmit="return checkThis()">
<input type="hidden" name="id" value="{{$detail.id}}" />
<table cellpadding="0" cellspacing="0" border="0" class="table" width="300">
  <tbody>
    <tr>
      <td>网站名称</td>
      <td>{{$detail.shop_name}}</td>
    </tr>
    <tr>
      <td>商品名称</td>
      <td>{{$detail.goods_name}}</td>
    </tr>
	<tr>
      <td>期数</td>
      <td>{{$detail.term}}</td>
    </tr>
    <tr>
      <td>导入批次</td>
      <td>{{$detail.batch}}</td>
    </tr>
    <tr>
      <td>订单号</td>
      <td>{{$detail.order_sn}}</td>
    </tr>
    <tr>
      <td>收件人姓名</td>
      <td><input type="text" name="name" value="{{$detail.name}}" /></td>
    </tr>
    <tr>
      <td>固定电话</td>
      <td><input type="text" name="phone" value="{{$detail.phone}}" /></td>
    </tr>
    <tr>
      <td>手机</td>
      <td><input type="text" name="mobile" value="{{$detail.mobile}}" /></td>
    </tr>
    <tr>
      <td>邮编</td>
      <td><input type="text" name="postcode" value="{{$detail.postcode}}" /></td>
    </tr>
    <tr>
      <td>详细地址</td>
      <td><input type="text" name="addr" value="{{$detail.addr}}" size="100" /></td>
    </tr>
    <tr>
      <td>备注</td>
      <td><input type="text" name="remark" value="{{$detail.remark}}" size="100" /></td>
    </tr>
    <tr>
      <td>购买商品数量</td>
      <td><input type="text" name="amount" value="{{$detail.amount}}" /></td>
    </tr>
    <tr>
      <td>物流状态</td>
      <td>
        未发货<input type="radio" name="logistics" value="0" {{if $detail.logistics eq 0}} checked="checked"{{/if}} />&nbsp;&nbsp;&nbsp;已发货<input name="logistics" {{if $detail.logistics eq 1}} checked="checked"{{/if}} type="radio" value="1" />
      </td>
    </tr>
    <tr>
      <td>打印快递单状态</td>
      <td>未打印<input type="radio" name="print" value="0" {{if $detail.print eq 0}} checked="checked"{{/if}} />&nbsp;&nbsp;&nbsp;已打印<input name="print" {{if $detail.print eq 1}} checked="checked"{{/if}} type="radio" value="1" /></td>
    </tr>
    <tr>
      <td>物流公司</td>
      <td>
        <select name="logistics_com">
          <option value="">请选择</option>
          {{foreach from=$logisticList item=logistic}}
          <option value="{{$logistic.logistic_code}}" {{if $detail.logistics_com eq $logistic.logistic_code}} selected="selected"{{/if}}>{{$logistic.name}}</option>
          {{/foreach}}
        </select>
      </td>
    </tr>
    <tr>
      <td>快递单号</td>
      <td><input type="text" name="logistics_no" value="{{$detail.logistics_no}}" /></td>
    </tr>
    <tr>
      <td>退单状态</td>
      <td>{{if $detail.isback eq 0}}未{{elseif $detail.isback eq 1}}已{{elseif $detail.isback eq 2}}待退款{{/if}}</td>
    </tr>
	<!--
    <tr>
      <td>刷单状态</td>
      <td>
        否<input type="radio" name="ischeat" value="0" {{if $detail.ischeat eq 0}} checked="checked"{{/if}} />&nbsp;&nbsp;&nbsp;是<input name="ischeat" {{if $detail.ischeat  > 0}} checked="checked" value="{{$detail.ischeat}}"{{else}}value="1"{{/if}} type="radio" />
      </td>
    </tr>
	-->
	{{if $detail.ischeat > 0}}
	<tr>
	  <td>销账状态</td>
	  <td>{{if $detail.ischeat eq 1}}未销账{{else}}已经销账->操作信息：<span style=" color:red;">(金额{{$detail.xiaozhang.price}})</span>&nbsp;&nbsp;{{$detail.xiaozhang.op_name}}&nbsp;&nbsp;{{$detail.xiaozhang.op_time|date_format:"%Y-%m-%d %T"}}{{/if}}</td>
	</tr>
	{{/if}}
    <tr>
      <td>减库存</td>
      <td>
        否<input type="radio" name="check_stock" value="0" {{if $detail.check_stock eq 0}} checked="checked"{{/if}} />&nbsp;&nbsp;&nbsp;是<input name="check_stock" {{if $detail.check_stock > 0}} checked="checked"{{/if}} value="1" type="radio" />
      </td>
    </tr>
    <tr>
      <td></td>
      <td><input type="submit" value="修改">&nbsp;&nbsp;<input type="button" onclick="javascript:history.back()" value="返回" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td><td>&nbsp;</td>
    </tr>
    <tr>
      <td>订单导入操作</td>
      <td>{{$detail.addUser.admin_name}}&nbsp;<span style=" color:#999;">{{$detail.addUser.real_name}}</span>{{if $detail.ctime}}&nbsp;<span style="font-size:10px;">{{$detail.ctime|date_format:"%Y-%m-%d %T"}}</span>{{/if}}</td>
    </tr>
    <tr>
      <td>快递单打印操作</td>
      <td>{{$detail.printUser.admin_name}}&nbsp;<span style=" color:#999;">{{$detail.printUser.real_name}}</span>{{if $detail.logistics_time}}&nbsp;<span style="font-size:10px;">{{$detail.logistics_time|date_format:"%Y-%m-%d %T"}}</span>{{/if}}</td>
    </tr>
    <tr>
      <td>添加客服备注</td>
      <td><input type="text" name="comment" id="comment" size="100" />&nbsp;&nbsp;<input type="button" onclick="addComment();" value="添加" /></td>
    </tr>
    <tr>
      <td></td>
      <td id="comments">
        {{foreach from=$comments item=com key=k}}
        {{if $com}}
        {{$k+1}}&nbsp;{{$com}}<br />
        {{/if}}
        {{/foreach}}
      </td>
    </tr>
  </tbody>
</table>
</form>
</div>
<script type="text/javascript">
function checkThis(){
	var shop=$('shopid').value;
	if(shop==0){alert('请选择网站');return false;}
	var name=$('goods_name').value.trim();
	if(name==''){alert('请填写商品名称');return false;}
}
//
function addComment(){
	var val=$('comment').value.trim();
	if(val==''){alert('请输入内容');$('comment').value='';return;}
	new Request({
		url:'/admin/out-tuan/comment-add',
		data:{content:val,id:{{$detail.id}}},
		onSuccess:function(msg){
			if(msg=='ok'){
				location.reload();
			}else if(msg=='content'){
				alert('请填写备注内容');
			}else if(msg=='id'){
				alert('id参数错误');
			}else{
				alert('未知错误');
			}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
</script>