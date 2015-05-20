{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
<script type="text/javascript">
loadCss('/images/calendar/calendar.css');
loadJs("/scripts/calendar.js",MyCalendar);
function MyCalendar(){
    new Calendar({add_time_from: 'Y-m-d'},{clear: true});
    new Calendar({add_time_end: 'Y-m-d'},{clear: true});
    new Calendar({using_time_from: 'Y-m-d'},{clear: true});
    new Calendar({using_time_end: 'Y-m-d'},{clear: true});
}
</script>
<div class="search">
    <form name="searchForm" id="searchForm">
    <div>
        <span style="float:left">添加日期从：</span><span style="float:left;width:150px;"><input type="text" name="add_time_from" id="add_time_from" size="11" value="" /></span>
        <span style="float:left; margin-left:10px">截止到：</span><span style="float:left;width:150px;"><input type="text" name="add_time_end" id="add_time_end" size="11" value="" /></span>
        <span style="float:left; margin-left:10px">最后使用日期从：</span><span style="float:left;width:150px;"><input type="text" name="using_time_from" id="using_time_from" size="11" value="" /></span>
        <span style="float:left; margin-left:10px">截止到：</span><span style="float:left;width:150px;"><input type="text" name="using_time_end" id="using_time_end" size="11" value="" /></span>
    </div>
    <div style="clear:both; padding-top:5px">
        <span style="margin-left:5px; vertical-align:top">虚拟币卡号: <input type="text" name="card_sn" value="" size="11" /></span>
        <span style="margin-left:5px; vertical-align:top">用户名: <input type="text" name="user_name" value="" size="20" /></span>
        <span style="margin-left:5px; vertical-align:top"><input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/></span>
    </div>
</form>
</div>
{{/if}}
<div id="ajax_search">
<div class="title">虚拟币列表</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>虚拟币类型</td>
            <td>虚拟币价格</td>
            <td>虚拟币卡号</td>
            <td>生成时间</td>
            <td>最后使用用户</td>
            <td>最后使用时间</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$cardList item=card}}
        <tr id="ajax_list{{$history.card_id}}">
            <td>{{$card.card_id}}</td>
            <td>{{$card.card_type}}</td>
            <td>{{$card.card_price}}</td>
            <td>{{$card.card_sn}}</td>
            <td>{{$card.add_time}}</td>
            <td>{{$card.user_name}}</td>
            <td>{{$card.using_time}}</td>
            <td>
                <a href="javascript:fGo()" onclick="G('{{url param.action=log param.cid=$card.card_id}}')">查看使用历史</a>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
</div>