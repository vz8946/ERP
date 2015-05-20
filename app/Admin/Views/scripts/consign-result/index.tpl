<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm"  action="/admin/consign-result/index" onsubmit="return check();">
<div>
    <span style="float:left">开始日期：
        <select name="start_month" id="start_month">
        <option value=''>请选择</option>
        {{foreach from = $search_option.year_month key = key item=info}}
            <option value='{{$key}}' {{if $params.start_month eq $key}} selected=selected{{/if}}>{{$info}}</option>
        {{/foreach}}
        </select>
    </span>
    <span style="margin-left:10px">
        截止日期：<select name="end_month" id="end_month">
        <option value=''>请选择</option>
        {{foreach from = $search_option.year_month key = key item=info}}
            <option value='{{$key}}' {{if $params.end_month eq $key}} selected=selected{{/if}}>{{$info}}</option>
        {{/foreach}}
        </select>
    </span>

    <span style="margin-left:10px">
        产品编码：<input  type="text"  value="{{$params.product_sn}}" id="product_sn"  name="product_sn" / >
    </span>
    <span style="margin-left:10px">
        产品名称：<input  type="text"  value="{{$params.product_name}}" id="product_name"  name="product_name" / >
    </span>
    <span style="margin-left:10px">
        分销渠道：
        <select name="warehouse_id" id="warehouse_id">
        <option value=''>请选择</option>
        {{foreach from = $search_option.warehouses key = key item=info}}
        {{if $key gt 20}}
            <option value='{{$key}}' {{if $params.warehouse_id eq $key}} selected=selected{{/if}}>{{$info}}</option>
        {{/if}}
        {{/foreach}}
        </select>
    </span>
    
</div>
<input type="submit" name="dosearch" value="查询" />
<input type="hidden" name="collect" id="collect" value="0" />
<input type="button" name="collect" value="汇总查询" onclick="query()"/>
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
            {{if $params.collect neq '1'}}
            <td>操作</td>
            <td>ID</td>
            {{else}}
            <td>状态</td>
            {{/if}}
            <td>分销渠道</td>
            <th>产品编码</th>
            <td>产品名称</td>
            <td>需处理数量</td>
			<td>已处理数量</td>
            {{if $params.collect neq '1'}}
            <td>操作年月</td>
            {{/if}}
        </tr>
        </thead>
        <tbody>
        {{foreach from=$infos item=info}}
        <tr>
            {{if $params.collect neq '1'}}
            <td>
                {{if $info.deal_number eq $info.number}}
                    <input type="button" onclick="openDiv('/admin/consign-result/clear-consign/consign_result_id/{{$info.consign_result_id}}','ajax','代销产品数量结算',400,400,true)" value="查看">
                {{else}}
                <input type="button" onclick="openDiv('/admin/consign-result/clear-consign/consign_result_id/{{$info.consign_result_id}}','ajax','代销产品数量结算',400,400,true)" value="数量结算">
                {{/if}}
            </td>

            <td>{{$info.consign_result_id}}</td>
            {{else}}
            <td>
                {{if $info.deal_number eq $info.number}}
                    结算完毕
                {{else}}
                    <span style="color:#ff0000">结算中</span>
                {{/if}}
            </td>
            {{/if}}
            <td>{{$info.warehouse_name}}</td>
            <td>{{$info.product_sn}}</td>
            <td>{{$info.product_name}}</td>
            <td>{{$info.number}}</td>
            <td>{{$info.deal_number}}</td>
            {{if $params.collect neq '1'}}
            <td>{{$info.created_month}}</td>
            {{/if}}
        </tr>
        {{/foreach}}
        {{if $total_counts}}
        <tr>
            <td height="30" colspan="4">总计：</td>
            <td>{{$total_counts.number}}</td>
            <td colspan="2">{{$total_counts.deal_number}}</td>
        </tr>
        {{/if}}
        </tbody>
    </table>
    <div class="page_nav">{{$pageNav}}</div>
</div>
<script>
    function check()
    {
        var start_month = $("start_month").value;
        var end_month   = $("end_month").value;

        if (typeof(start_month) != 'undefined' && typeof(end_month) != 'undefined' && end_month < start_month) {
            alert('结束日期不能小于开始日期');
            return false;
        }
    }

    function query()
    {
        var start_month = $("start_month").value;
        var end_month   = $("end_month").value;
        if (typeof(start_month) != 'undefined' && typeof(end_month) != 'undefined' && end_month < start_month) {
            alert('结束日期不能小于开始日期');
            return false;
        }
        
        $("collect").value = 1;
        document.getElementById("searchForm").submit();
    }
</script>