<div class="member">
	{{include file="member/menu.tpl"}}
	<div class="memberright">
		
        <!--<div class="memberddbg">
			<p>
				符合条件订单总数为：<font color="#D52319">{{$total}}</font>
			</p>
		</div>-->
        <div class="person_info"><span class="fl"><em>{{if $member.nick_name}}{{$member.nick_name}}{{else}}{{$member.user_name}}{{/if}} </em> 欢迎回来！</span>
		<span class="fr"><b>最后登录时间：</b>：{{$member.last_login|date_format:"%Y-%m-%d %H:%M:%S"}}</span></div>
		<div class="ordertype">
			<select name="timesection" onchange="javascript:location.href=this.value;">
				<option value="/member/order/timesection/1" {{if $params.timesection eq 1}}selected="selected"{{/if}}>最近一个月</option>
				<option value="/member/order/timesection/2" {{if $params.timesection eq 2}}selected="selected"{{/if}}>往期订单</option>
				<option value="/member/order/timesection/3" {{if $params.timesection eq 3}}selected="selected"{{/if}}>所有订单</option>
			</select>
			<div class="ordertypea">
				<a href="/member/order/timesection/{{$params.timesection}}/ordertype/1" {{if $params.ordertype eq 1 || !$params.ordertype}} class="sel" {{/if}}>所有</a>
				<a href="/member/order/timesection/{{$params.timesection}}/ordertype/2" {{if $params.ordertype eq 2}} class="sel" {{/if}}>待确认订单</a>
				<a href="/member/order/timesection/{{$params.timesection}}/ordertype/3" {{if $params.ordertype eq 3}} class="sel" {{/if}}>已取消订单</a>
				<a href="/member/order/timesection/{{$params.timesection}}/ordertype/4" {{if $params.ordertype eq 4}} class="sel" {{/if}}>需付款订单</a>
				<a href="/member/order/timesection/{{$params.timesection}}/ordertype/5" {{if $params.ordertype eq 5}} class="sel" {{/if}}>已付款待发货订单</a>
				<a href="/member/order/timesection/{{$params.timesection}}/ordertype/6" {{if $params.ordertype eq 6}} class="sel" {{/if}}>已发货订单</a>
				<a href="/member/order/timesection/{{$params.timesection}}/ordertype/7" {{if $params.ordertype eq 7}} class="sel" {{/if}}>已完成订单</a>
			</div>
		</div>
		<div  style=" cclear:both;">
			<table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
				<thead>
					<tr>
						<th>订单号</th>
						<th>下单时间</th>
						<th>收货人</th>
						<th>订单总金额</th>
						<th>订单状态</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					{{foreach from=$orderInfo item=order}}
					<tr>
						<td><strong><a href="{{url param.action=order-detail param.batch_sn=$order.batch_sn}}">{{$order.batch_sn}}</a></strong></td>
						<td>{{$order.add_time}}</td>
						<td>{{$order.addr_consignee|truncate:8:"..."}}</td>
						<td>￥{{$order.price_pay}}</td>
						<td>{{$order.deal_status}}</td>
						<td> {{if $order.pay_amount>0 && $order.status==0 and $order.status_logistic<5 and ($order.price_pay-($order.price_payed+$order.price_from_return)) > 0}}<a class="{{$order.batch_sn}}" href="/member/order-detail/batch_sn/{{$order.batch_sn}}">付款</a>{{/if}}
						
						{{if $order.pay_amount>0 &&  $order.price_payed == 0  &&  $order.status eq 0 && $order.status_logistic eq 0 && $order.parent_batch_sn==''}}<a href="javascript:;" onclick="cancelOrder({{$order.batch_sn}});" class="{{$order.batch_sn}}" style="color:#999999;">取消订单</a>{{/if}}
						
						{{if $order.status==0 and $order.status_logistic >=3 and  $order.status_logistic <= 4 and $order.is_fav neq 1}}
						<form method="post" action="{{url param.action=fav param.batch_sn=$order.batch_sn}}">
							<input type="hidden" name="batch_sn" value="{{$order.batch_sn}}" />
							<input type="submit" value="满意无需退换货"  class="not_return" style="position:relative;top:12px;margin:0"/>
						</form> {{/if}}
						&nbsp; </td>
					</tr>
					{{/foreach}}
				</tbody>
			</table>
			<div class="page_nav">
				{{$pageNav}}
			</div>

			{{if $showTip == 'allow'}}
			<div class="remind-txt" style="line-height: 24px;">
				<strong>※温馨提示：</strong>当您购买后三十天内，点击了“满意无须退换货”后，系统将立即赠送您积分，订单商品不再享受退换货服务。如果您未点击“满意无须退换货”系统将在您购物后30天，自动赠送您积分。
			</div>
			{{/if}}

		</div>
	</div>
	<div style="clear:both;"></div>
</div>
	<script>
		function cancelOrder(batch_sn) {
			$.ajax({
				url : '/member/cancel-order/batch_sn/' + batch_sn,
				success : function(msg) {
					if (msg == 'setOrderCancelSucess') {
						alert('取消订单成功');
						$('.' + batch_sn).remove();
					} else if (msg == 'noCancel') {
						alert('不能取消！');
					} else if (msg == 'error') {
						alert('网络繁忙，请稍后重试！');
					} else {
						alert(msg);
					}
				},
				error : function() {
					alert('网络繁忙，请稍后重试！');
				}
			});
		}
	</script>
