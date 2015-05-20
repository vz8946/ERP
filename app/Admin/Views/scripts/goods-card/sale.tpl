{{if !$param.do}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm">
<span style="float:left;line-height:18px;">卡生成开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
结束日期：<input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
&nbsp;提货卡名称：<input type="text" name="card_name" size="15" maxLength="50" value="{{$param.card_name}}">
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/>
<input type="reset" name="reset" value="清除">
</form>
</div>
{{/if}}
<div id="ajax_search">
<div class="title">提货卡销售统计</div>
<form name="myForm" id="myForm">
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>提货卡名称</td>
            <td>生成数量</td>
            <td>已销售数量</td>
            <td>销售总金额</td>
            <td>已消费数量</td>
            <td>抵扣商品总金额</td>
        </tr>
    </thead>
    <tbody>
    {{if $datas}}
    {{foreach from=$datas item=data}}
    <tr>
        <td>{{$data.card_name}}</td>
        <td>{{$data.total_count}}</td>
        <td>{{if $data.count}}{{$data.count}}{{else}}0{{/if}}</td>
        <td>{{if $data.amount}}{{$data.amount}}{{else}}0{{/if}}</td>
        <td>{{if $data.consume_count}}{{$data.consume_count}}{{else}}0{{/if}}</td>
        <td>{{if $data.consume_amount}}{{$data.consume_amount}}{{else}}0{{/if}}</td>
    </tr>
    {{/foreach}}
    {{/if}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
 </form>
