{{if $param.do neq 'search' && $param.do neq 'splitPage'}}
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search"  style="height:90px">
<form name="searchForm" id="searchForm" method="get">
     <div class="line">
        <span style="float:left">订单起始日期：<input type="text" name="start_date" id="start_date" size="10" value="{{$param.start_date}}" class="Wdate" onClick="WdatePicker()"/></span>
        <span style="float:left">订单截止日期：<input type="text" name="end_date" id="end_date" size="10" value="{{$param.end_date}}" class="Wdate" onClick="WdatePicker()"/></span>
        <span style="float:left">最后处理起始日期：<input type="text" name="start_modify_date" id="start_modify_date" size="10" value="{{$param.start_modify_date}}" class="Wdate" onClick="WdatePicker()"/></span>
        <span style="float:left">最后处理截止日期：<input type="text" name="end_modify_date" id="end_modify_date" size="10" value="{{$param.end_modify_date}}" class="Wdate" onClick="WdatePicker()"/></span>
        <div style="clear:both; padding-top:5px">
            会员等级：
            <select name="rank_name"><option value="">请选择</option>{{html_options options=$memberAllRanks selected=$param.rank_name}}</select>
            订单状态：
            <select name="order_status" id="order_status">
            <option value="" {{if $param.order_status eq ''}}selected="selected"{{/if}}>全部</option>
            <option value="0" {{if $param.order_status eq '0'}}selected="selected"{{/if}}>正常</option>
            <option value="1" {{if $param.order_status eq '1'}}selected="selected"{{/if}}>无效</option>
            </select>
            分成状态：<select name="separate_type" id="separate_type">
            <option value="" {{if $param.separate_type == ''}}selected="selected"{{/if}}>全部</option>
            <option value="0" {{if $param.separate_type === 0}}selected="selected"{{/if}}>未分成</option>
            <option value="1" {{if $param.separate_type == 1}}selected="selected"{{/if}}>已分成</option>
            <option value="2" {{if $param.separate_type == 2}}selected="selected"{{/if}}>无效</option>
            </select>
             订 单 号 ：<input type="text" name="order_sn" id="order_sn" size="15" value="{{$param.order_sn}}" />
          </div>
    </div>
    <div class="line">
    
        联盟ID：<input type="text" name="id" value="{{$param.id}}" size="15" id="id" />
       联盟下家参数：<input type="text" name="user_param" value="{{$param.user_param}}" size="15" id="id" />
       下单用户：<input type="text" name="order_user_name" value="{{$param.order_user_name}}" size="15" id="order_user_name" />
       <input type="submit" name="dosearch" value="订单查询" />
    </div>
</form>
</div>
{{/if}}

{{if $param.dosearch || $param.id > 0}}
<div class="ajax_search">
[<a href="{{url param.action=export-order}}?{{$smarty.server.QUERY_STRING}}" target="_blank">导出订单数据</a>]  
[<a href="{{url param.action=export-order-goods}}?{{$smarty.server.QUERY_STRING}}" target="_blank">导出订单商品格式数据</a>]
</div>

<div class="content">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table">
        <thead>
        <tr>
            <td width="10%">订单号</td>
            <td width="10%">信息</td>
            <td width="10%">会员等级</td>
            <td width="10%">订单金额</td>
            <td width="10%">可分成金额</td>
            <td width="10%">分成金额</td>
            <td width="10%">分成比例</td>
            <td width="10%">订单状态</td>
            <td width="10%">分成状态</td>
            <td width="10%">下单时间</td>
         
        </tr>
        </thead>
        <tbody>
        {{foreach from=$order item=list}}
  <tr id="ajax_list{{$cUnion.user_id}}">
      <td><a href="javascript:fGo()" onclick="openDiv('/admin/order/info/batch_sn/{{$list.order_sn}}','ajax','订单详情',900,450,true,'sel');" >{{$list.order_sn}}</a></td>
            <td><font color="green">【盟】{{$list.user_name}}</font><br />{{$list.order_user_name}}</td>
            <td>{{$list.rank_name}}</td>
            <td>{{$list.order_price_goods}}</td>
            <td>{{$list.order_affiliate_amount}}</td>
            <td>{{$list.affiliate_money}}</td>
            <td>{{if $list.proportion eq 99}}商品分成{{else}}{{$list.proportion}}%{{/if}}</td>
            <td>{{if $list.order_status eq 0}} 正常单
                {{elseif $list.order_status eq 1}}取消单
                {{elseif $list.order_status eq 2}}无效单
                {{/if}}
            </td>
            <td>
            {{if $list.separate_type eq 0}} 未分成
                {{elseif $list.separate_type eq 1}}已分成
                {{elseif $list.separate_type eq 2}}无效
            {{/if}}
            </td>
            <td>{{$list.order_time}}</td>
         
        </tr>
         {{foreachelse}}
         
       <tr id="ajax_list{{$pUnion.user_id}}">
   
            <td colspan="11" align="center">没找到任何数据</td>
         
      </tr> 
        {{/foreach}}
        </tbody>
    </table>
</div>{{/if}}
<div class="page_nav">{{if $pageNav}}订单总金额为{{$totalorder.total_order_price_goods}}  　　可分成订单总金额{{$totalorder.total_order_affiliate_amount}} 　　总分成金额 {{$totalorder.total_affiliate_money}} {{/if}} <br />
{{$pageNav}}</div> 