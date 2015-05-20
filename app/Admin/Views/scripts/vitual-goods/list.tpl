{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
    <form name="searchForm" id="searchForm">
    <div>
        <span style="float:left;">交付时间：</span>
        <span style="float:left;width:95px;"><input type="text" name="deliver_time_from" id="deliver_time_from" size="11" value="{{$param.deliver_time_from}}" class="Wdate" onClick="WdatePicker()" /></span>
        <span style="float:left;width:10px;"> - </span>
        <span style="float:left"></span><span style="float:left;width:120px;"><input type="text" name="deliver_time_end" id="deliver_time_end" size="11" value="{{$param.deliver_time_end}}" class="Wdate" onClick="WdatePicker()" /></span>
        <span style="float:left">使用时间：</span>
        <span style="float:left;width:95px;"><input type="text" name="using_time_from" id="using_time_from" size="11" value="{{$param.using_time_from}}" class="Wdate" onClick="WdatePicker()" /></span>
        <span style="float:left;width:10px;"> - </span>
        <span style="float:left"></span><span style="float:left;width:100px;"><input type="text" name="using_time_end" id="using_time_end" size="11" value="{{$param.using_time_end}}" class="Wdate" onClick="WdatePicker()" /></span>
        <span style="margin-left:5px; vertical-align:top"><br><br>
        类型: 
        <select name="type">
          <option value="">请选择...</option>
          <option value="1" {{if $param.type eq 1}}selected{{/if}}>体检卡</option>
          <option value="2" {{if $param.type eq 2}}selected{{/if}}>礼品卡</option>
        </select>
        状态: 
        <select name="status">
          <option value="">请选择...</option>
          <option value="0" {{if $param.status eq '0'}}selected{{/if}}>未分配</option>
          <option value="1" {{if $param.status eq '1'}}selected{{/if}}>已交付</option>
          <option value="2" {{if $param.status eq '2'}}selected{{/if}}>已使用</option>
          <option value="9" {{if $param.status eq '9'}}selected{{/if}}>已作废</option>
        </select>
        </span>
        <span style="margin-left:5px; vertical-align:top">产品名称: <input type="text" name="product_name" value="{{$param.product_name}}" size="6" /></span>
        <span style="margin-left:5px; vertical-align:top">序列号: <input type="text" name="sn" value="{{$param.sn}}" size="12" /></span>
        <span style="margin-left:5px; vertical-align:top">用户名: <input type="text" name="user_name" value="{{$param.user_name}}" size="10" /></span>
        <span style="margin-left:5px; vertical-align:top">手机号码: <input type="text" name="sms_no" value="{{$param.sms_no}}" size="12" /></span>
        <span style="margin-left:5px; vertical-align:top"><input type="button" name="dosearch" id="dosearch" value="搜索" onclick="ajax_search(this.form,'{{url param.do=search}}','ajax_search')"/></span>
    </div>
</form>
</div>
{{/if}}
<div id="ajax_search">
<div class="title">虚拟商品列表</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>类型</td>
            <td>产品名称</td>
            <td>序列号</td>
            <td>用户</td>
            <td>交付时间</td>
            <td>使用时间</td>
            <td>状态</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$datas item=goods}}
        <tr>
            <td>{{$goods.vitual_id}}</td>
            <td>
              {{if $goods.type eq 1}}
                体检卡
              {{elseif $goods.type eq 2}}
                礼品卡
              {{/if}}
            </td>
            <td>{{$goods.product_name}}</td>
            <td>{{$goods.sn}}</td>
            <td>{{$goods.user_name}}</td>
            <td>{{if $goods.deliver_time}}{{$goods.deliver_time|date_format:"%Y-%m-%d %H:%M:%S"}}{{/if}}</td>
            <td>{{if $goods.using_time}}{{$goods.using_time|date_format:"%Y-%m-%d %H:%M:%S"}}{{/if}}</td>
            <td>
              {{if $goods.status eq 0}}未分配
              {{elseif $goods.status eq 1}}已交付
              {{elseif $goods.status eq 2}}已使用
              {{elseif $goods.status eq 9}}已作废
              {{/if}}
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>
</div>