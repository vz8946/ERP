{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
    <div class="search">
    <form name="searchForm" id="searchForm">
        <div>
         <span style="float:left">起始日期： <input type="text" name="start_date" id="start_date" size="15" value="{{$param.start_date}}"  class="Wdate"   onClick="WdatePicker()"  /> </span>
         <span style="float:left">截止日期：  <input type="text" name="end_date" id="end_date" size="15" value="{{$param.end_date}}" class="Wdate"   onClick="WdatePicker()"  /></span>
        排序：
        <select name="order_by" id="order_by">
        <option value="click_num" {{if $param.order_by eq 'click_num'}}selected="selected"{{/if}} >点击次数</option>
        <option value="order_amount" {{if $param.order_by eq 'order_amount'}}selected="selected"{{/if}} >订单金额</option>
        </select>
        
        <div style="clear:both; padding-top:5px">   
                登 录 名： 
                <input type="text" name="user_name" value="{{$param.user_name}}" size="15" />
                用户ID：
                <input type="text" name="user_id" id="user_id" size="15" value="{{$param.user_id}}" />
                分成比率：
                <select name="calculate_type">
                <option value="">请选择</option>
                <option {{if $param.calculate_type eq 1}}selected="selected"{{/if}} value="1">固定比例分成</option>
                <option {{if $param.calculate_type eq 2}}selected="selected"{{/if}} value="2">商品比例分成</option>
                </select>
                <input type="button" name="dosearch" value="概况查询" onclick="ajax_search(this.form,'{{url param.action=index param.do=search}}','ajax_search')"/>
                <input type="button" onclick="G('{{url param.action=export-union}}')" value="导出联盟信息">
          </div>
       
    </form>
    </div>
</div>
{{/if}}

<div id="ajax_search">
<div class="title">推广联盟列表</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=add}}')">添加联盟</a> ]
		[ <a href="{{url param.action=export-union}}?{{$smarty.server.QUERY_STRING}}" target="_blank">导出联盟信息</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>会员名称</td>
            <td>公司名称</td>
            <td>添加时间</td>
            <td>分成比率</td>
            <td>注册人数</td>
            <td>点击人数</td>
            <td>有效订单数</td>
            <td>订单金额</td>
			<td>可分成金额</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$pUnionList item=pUnion}}
        <tr id="ajax_list{{$pUnion.user_id}}">
            <td>{{$pUnion.user_id}}</td>
            <td><a href="javascript:fGo()" onclick="G('/admin/p-union/view/id/{{$pUnion.user_id}}')"><span style="color:#00C">{{$pUnion.user_name}}</span></a></td>
            <td>{{$pUnion.cname}}</td>
            <td>{{$pUnion.add_time}}</td>
            <td>{{if $pUnion.calculate_type eq 1}}{{$pUnion.proportion}}%{{else}}<a href="javascript:fGo()" onclick="G('/admin/p-union/product-proportion-list/uid/{{$pUnion.user_id}}')" title="查看">商品比率分成</a>{{/if}}</td>
            <td>{{if $pUnion.reg_num}}{{$pUnion.reg_num}}{{else}}0{{/if}}</td>
            <td>{{if $pUnion.click_num}}
              <a href="javascript:fGo()" onclick="G('/admin/p-union/view-click/id/{{$pUnion.user_id}}')"> 
           <span style="color:#00C"> {{$pUnion.click_num}}</span></a>
            {{else}}0{{/if}}</td>
            <td>{{if $pUnion.order_num}}
             <a href="javascript:fGo()" onclick="G('/admin/p-union/search-order/id/{{$pUnion.user_id}}')"> 
           <span style="color:#00C">{{$pUnion.order_num}}</span></a>
           {{else}}
            0{{/if}}</td>
            <td>{{if $pUnion.order_amount}}{{$pUnion.order_amount}}{{else}}0{{/if}}</td>
		    <td>{{if $pUnion.order_affiliate_amount}}{{$pUnion.order_affiliate_amount}}{{else}}0{{/if}}</td>
            <td id="ajax_status{{$pUnion.union_normal_id}}">{{$pUnion.status}}</td>
            <td> 
                <a href="javascript:fGo()" onclick="G('/admin/p-union/view/id/{{$pUnion.user_id}}')">查看</a> | 
                <a href="javascript:fGo()" onclick="G('/admin/p-union/edit/id/{{$pUnion.user_id}}')">编辑</a> | 
                <a href="javascript:fGo()" onclick="reallydelete('{{url param.action=delete}}','{{$pUnion.user_id}}')">删除</a>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
    
</div>
<div class="page_nav"> 
<strong>当前查询条件下数据汇总：</strong>
    总注册：{{$pUnionAllData.reg_num}}
   　总点击：{{if $pUnionAllData.click_num}}
    {{$pUnionAllData.click_num}} {{else}}0{{/if}}
    　总订单数：{{$pUnionAllData.order_num}}
     总金额：{{if $pUnionAllData.order_amount}}
     ￥{{$pUnionAllData.order_amount}}
      {{else}}￥0{{/if}}
{{$pageNav}}</div>
</div>
