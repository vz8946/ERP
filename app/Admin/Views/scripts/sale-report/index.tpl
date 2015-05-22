<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm"  action="/admin/sale-report/index" onsubmit="return check();">
<div>
    <span style="float:left">开始日期：
        <input  type="text" name="start_ts" id="start_ts" size="15" value="{{$params.start_ts}}"  class="Wdate"  onClick="WdatePicker()"/>
    </span>
    <span style="margin-left:10px">
        截止日期：<input  type="text" name="end_ts" id="end_ts" size="15" value="{{$params.end_ts}}"  class="Wdate"  onClick="WdatePicker()"/>(两个月以内)
    </span>

    <span style="margin-left:10px">
        产品编码：<input  type="text"  value="{{$params.product_sn}}" id="product_sn"  name="product_sn" / >
    </span>
    <span style="margin-left:10px">
        产品名称：<input  type="text"  value="{{$params.product_name}}" id="product_name"  name="product_name" / >
    </span>
    <span style="margin-left:10px">供应商：
        <select name="supplier_id">
        <option value="">请选择</option>
        {{html_options options=$search_option.supplier_info selected=$params.supplier_id}}
        </select>
    </span>
    <input type="hidden" name="query" value="1" />
</div>
<input type="submit" name="dosearch" value="查询" />
</div>
</form>
</div>
<div class="title">信息列表</div>
<div class="content">
    <div class="sub_title">
        
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <th>产品ID</th>
            <th>供应商</th>
            <th>产品编码</th>
            <td>产品名称</td>
            <td>实际销售数量</td>
	{{if  $is_view}}
            <td>销售出库数量</td>
            <td>分销出库数量</td>
            <td>退货入库数量</td>
	{{/if}}

        </tr>
        </thead>
        <tbody>
        {{foreach from=$infos item=info}}
        <tr>
            <td>{{$info.product_id}}</td>
            <td>{{$info.supplier_name}}</td>
            <td>{{$info.product_sn}}</td>
            <td>{{$info.product_name}}</td>
            <td>{{$info.number}}</td>
	    {{if  $is_view}}
            <td>{{$info.sale_number}}</td>
            <td>{{$info.fenxiao_number}}</td>
            <td>{{$info.return_number}}</td>
	    {{/if}}
        </tr>
        {{/foreach}}
        
        </tbody>
    </table>
    <div class="page_nav">{{$pageNav}}</div>
</div>
<script>
    function check()
    {
        var start_ts = $("start_ts").value;
        var end_ts   = $("end_ts").value;

        if (start_ts == '' || end_ts == '') {
            alert('请选择开始时间结束时间');
            return false;
        }
        if (end_ts < start_ts) {
            alert('结束日期不能小于开始日期');
            return false;
        }
    }
</script>