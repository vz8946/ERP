<div class="title">组合商品日志查看&nbsp;&nbsp;&nbsp;
<br /><br />
<table cellpadding="0" cellspacing="0" border="0" class="table">
    <tr>
        <td width="100">组合编码：</td>
        <td>{{$group_info.group_sn}}</td>
    </tr>
    <tr>
        <td>组合名称：</td>
        <td>{{$group_info.group_goods_name}}</td>
    </tr>
</table>
<br /><br />
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
        	<td>日志ID</td>
            <td>上次产品配置</td>
            <td>当次产品配置</td>
            <td>操作人</td>
            <td>操作时间</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$infos item=info}}
    <tr>
        <td>{{$info.log_id}}</td>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
            {{foreach from=$info.group_goods_config item=list}}
              <tr>
                <td>{{$list.product_id}}</td>
                <td>{{$list.product_sn}}</td>
                <td>{{$list.product_name}}</td>
                <td>{{$list.number}}</td>
              </tr>
             {{/foreach}}
             </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
            {{foreach from=$info.last_goods_config item=list}}
              <tr>
                <td>{{$list.product_id}}</td>
                <td>{{$list.product_sn}}</td>
                <td>{{$list.product_name}}</td>
                <td>{{$list.number}}</td>
              </tr>
             {{/foreach}}
             </table>
        </td>
        <td>{{$info.created_by}}</td>
        <td>{{$info.created_ts}}</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>