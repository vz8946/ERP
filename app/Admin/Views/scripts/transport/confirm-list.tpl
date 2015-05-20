{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm" method="get">
开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
结束日期：<input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
单据类型：
<select name="bill_type">
    <option value="">请选择</option>
	{{html_options options=$billType selected=$param.bill_type}}
</select>
物流公司：
<select name="logistic_code">
    <option value="">请选择</option>
	{{html_options options=$logisticList selected=$param.logistic_code}}
</select>
付款方式：<select name="is_cod"><option value="">请选择</option><option value="0" {{if $param.is_cod eq '0'}}selected{{/if}}>非货到付款</option><option value="1" {{if $param.is_cod eq '1'}}selected{{/if}}>货到付款</option></select>
是否开票：<select name="invoice"><option value="">请选择</option><option value="1" {{if $param.invoice eq '1'}}selected{{/if}}>是</option><option value="0" {{if $param.invoice eq '0'}}selected{{/if}}>否</option></select>
<div class="line">
店铺：
  <select name="shop_id">
    <option value="">请选择...</option>
    {{foreach from=$shopDatas item=shop}}
      {{if $shop.shop_type ne 'tuan'}}
      <option value="{{$shop.shop_id}}" {{if $shop.shop_id eq $param.shop_id}}selected{{/if}}>{{$shop.shop_name}}</option>
      {{/if}}
    {{/foreach}}
  </select>
收货人：<input type="text" name="consignee" size="10" maxLength="20" value="{{$param.consignee}}"/>
单据编号：<input type="text" name="bill_no" size="30" maxLength="50" value="{{$param.bill_no}}"/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
<input type="button" name="export" value="导出" onclick="this.form.method='post';this.form.target='_blank';this.form.action='{{url param.controller=transport param.action=export param.act=confirm-list}}';this.form.submit()"">
</div>
<input type="button" name="dosearch2" value="所有被我锁定的入库单" onclick="ajax_search(this.form,'{{url param.do=search param.is_lock=yes}}','ajax_search')"/>
<input type="button" name="dosearch3" value="所有没有锁定的入库单" onclick="ajax_search(this.form,'{{url param.do=search param.is_lock=no}}','ajax_search')"/>
</div>
</form>
{{/if}}
<div class="title">配送管理 -&gt; {{$actions.$action}}</div>
<form name="myForm" id="myForm">
<div class="content">
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
<input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','G(\'{{url}}\')')">
<input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','G(\'{{url}}\')')">
<input type="button" value="打印运输单" onclick="{{if $param.bill_type=='' or $param.logistic_code=='' or $param.is_cod==''}}alert('请先选择单据类型/物流公司/付款方式');return;{{/if}}this.form.method='post';this.form.target='_blank';this.form.action='{{url param.action=prints}}';this.form.submit()">
请输入初始物流单号(必须连号)：<input type="text" name="logistic_no" size="10" maxLength="50" value="{{$param.logistic_no}}"/>
<input type="button" value="填充单号" onclick="{{if $param.bill_type=='' or $param.logistic_code=='' or $param.is_cod==''}}alert('请先选择单据类型/物流公司/付款方式');return;{{/if}}ajax_submit(this.form, '{{url param.action=fill-no}}','Gurl(\'{{url}}\')')">
<input type="button" value="导入单号" onclick="openDiv('/admin/transport/import-no','ajax','批量导入单号',780,400);">
<input type="button" value="打印销售单" onclick="{{if $param.bill_type=='' or $param.is_cod==''}}alert('请先选择单据类型/物流公司/付款方式');return;{{/if}}this.form.method='post';this.form.target='_blank';this.form.action='{{url param.controller=logic-area-out-stock param.action=prints}}';this.form.submit()">
<input type="button" value="打印拣货单" onclick="this.form.method='post';this.form.target='_blank';this.form.action='{{url param.controller=logic-area-out-stock param.action=prints-pickorders}}';this.form.submit()">
<input type="button" value="确认运输单" onclick="{{if $param.bill_type=='' or $param.logistic_code=='' or $param.is_cod==''}}alert('请先选择单据类型/物流公司/付款方式');return;{{/if}}ajax_submit(this.form, '{{url param.action=confirms}}','Gurl(\'{{url}}\')')">
<input type="button" value="返回派单" onclick="ajax_submit(this.form, '{{url param.action=back-assign}}','Gurl(\'{{url}}\')')">
</div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <td>操作</td>
            <td>单据编号</td>
            <td>店铺</td>
            <td>收货人</td>
            <td>单据类型</td>
            <td>付款方式</td>
            <td>承运商</td>
            <td>运单号</td>
            <td>制单日期</td>
            <td>是否锁定</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="ajax_list{{$data.tid}}">
        <td><input type="checkbox" name="ids[{{$data.tid}}]" value="{{$data.tid}}"/><input type="hidden" name="bill_no[{{$data.tid}}]" value="{{$data.bill_no}}"/></td>
        <td>
			<input type="button" onclick="openDiv('{{url param.action=confirm param.id=$data.tid}}','ajax','查看单据')" value="查看">
        </td>
        <td>{{$data.bill_no_str}}{{if $data.remark}}<br><b>{{$data.remark}}</b>{{/if}}</td>
        <td>{{$data.shop_name}}</td>
        <td>{{$data.consignee}}</td>
        <td>{{$billType[$data.bill_type]}}<input type="hidden" name="bill_type[{{$data.tid}}]" value="{{$data.bill_type}}"><input type="hidden" name="info[{{$data.tid}}][bill_type]" value="{{$data.bill_type}}"><input type="hidden" name="info[{{$data.tid}}][bill_no]" value="{{$data.bill_no}}"></td>
        <td>{{if $data.is_cod}}货到付款{{else}}非货到付款{{/if}}</td>
        <td>{{$data.logistic_name}}</td>
        <td>{{$data.logistic_no}}</td>
        <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
        <td>{{if $data.lock_name}}已被<font color="red">{{$data.lock_name}}</font>{{else}}未{{/if}}锁定</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>

{{if $param.bill_type=='' or $param.logistic_code=='' or $param.is_cod==''}}
<p style="color:red">请选择单据类型、物流公司及付款方式<br><br></p>
{{/if}}

<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
<input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/1','G(\'{{url}}\')')">
<input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/val/0','Gurl(\'{{url}}\')')">
<input type="button" value="打印运输单" onclick="{{if $param.bill_type=='' or $param.logistic_code=='' or $param.is_cod==''}}alert('请先选择单据类型/物流公司/付款方式');return;{{/if}}this.form.method='post';this.form.target='_blank';this.form.action='{{url param.action=prints}}';this.form.submit()">
请输入初始物流单号(必须连号)：<input type="text" name="logistic_no" size="10" maxLength="50" value="{{$param.logistic_no}}"/>
<input type="button" value="填充单号" onclick="{{if $param.bill_type=='' or $param.logistic_code=='' or $param.is_cod==''}}alert('请先选择单据类型/物流公司/付款方式');return;{{/if}}ajax_submit(this.form, '{{url param.action=fill-no}}','Gurl(\'{{url}}\')')">
<input type="button" value="导入单号" onclick="openDiv('/admin/transport/import-no','ajax','批量导入单号',780,400);">
<input type="button" value="打印销售单" onclick="{{if $param.bill_type=='' or $param.logistic_code=='' or $param.is_cod==''}}alert('请先选择单据类型/物流公司/付款方式');return;{{/if}}this.form.method='post';this.form.target='_blank';this.form.action='{{url param.controller=logic-area-out-stock param.action=prints}}';this.form.submit()">
<input type="button" value="打印拣货单" onclick="this.form.method='post';this.form.target='_blank';this.form.action='{{url param.controller=logic-area-out-stock param.action=prints-pickorders}}';this.form.submit()">
<input type="button" value="确认运输单" onclick="{{if $param.bill_type=='' or $param.logistic_code=='' or $param.is_cod==''}}alert('请先选择单据类型/物流公司/付款方式');return;{{/if}}ajax_submit(this.form, '{{url param.action=confirms}}','Gurl(\'{{url}}\')')">
<input type="button" value="返回派单" onclick="ajax_submit(this.form, '{{url param.action=back-assign}}','Gurl(\'{{url}}\')')">
</div>
<div class="page_nav">{{$pageNav}}</div>
</form>
