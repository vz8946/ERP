{{include file="header_try.tpl"}}

<div class="wbox">
	<div class="position">
		<b><a href="trial.html">试用中心</a></b><span> &gt; 已结束的活动 </span>
	</div>

	<div class="wrap clearfix">

		<div class="try_main fl">
			<div class="endlist">
				<ul>
					{{foreach from=$list key=k item=v}}
					<li class="first">
						<div class="pic">
							<p class="wh180 verticalPic">
								<a href="/goods-{{$v.goods_id}}.html"> <img 
									src="{{$imgBaseUrl}}/{{$v.img1|replace:'.':'_350_350.'}}" alt="{{$v.try_goods_name}}"></a>
							</p>
						</div>
						<div class="intro">
							<h2><a href="/trydetail{{$v.try_id}}.html">{{$v.try_goods_name}}</a></h2>
							<div class="pf">
								<a href="/trydetail{{$v.try_id}}.html" class="btndetail">查看详情</a>
								<s> </s>共(<span>{{$v.order_num}}</span>份)<a href="/trydetail{{$v.try_id}}.html" style="color:#f60;" title="查看网友试用心得">试用报告</a>
							</div>
							<div class="attr">
								<div class="info">
									<span><b>试用时间：</b>{{$v.start_time|date_format:'%Y-%m-%d'}} ~ {{$v.end_time|date_format:'%Y-%m-%d'}}</span>
									<span><b>试用份数：</b>{{$v.num}} 份</span>
								</div>
								
								<!--
								<div class="star">
									<table width="100%">
										<tbody>
											<tr>
												<th>功能指数：</th>
												<td><span num="0" class="star-readonly" style="cursor: default;"><img class="" title="bad" alt="1" src="{{$imgBaseUrl}}/Public/img/starticon/star-off.png" id="-1">&nbsp;<img class="" title="poor" alt="2" src="{{$imgBaseUrl}}/Public/img/starticon/star-off.png" id="-2">&nbsp;<img class="" title="regular" alt="3" src="{{$imgBaseUrl}}/Public/img/starticon/star-off.png" id="-3">&nbsp;<img class="" title="good" alt="4" src="{{$imgBaseUrl}}/Public/img/starticon/star-off.png" id="-4">&nbsp;<img class="" title="gorgeous" alt="5" src="{{$imgBaseUrl}}/Public/img/starticon/star-off.png" id="-5">
													<input type="hidden" name="score" id="-score" value="0">
												</span></td>
											</tr>
											<tr>
												<th>欲望指数：</th>
												<td><span num="0" class="star-readonly" style="cursor: default;"><img class="" title="bad" alt="1" src="{{$imgBaseUrl}}/Public/img/starticon/star-off.png" id="-1">&nbsp;<img class="" title="poor" alt="2" src="{{$imgBaseUrl}}/Public/img/starticon/star-off.png" id="-2">&nbsp;<img class="" title="regular" alt="3" src="{{$imgBaseUrl}}/Public/img/starticon/star-off.png" id="-3">&nbsp;<img class="" title="good" alt="4" src="{{$imgBaseUrl}}/Public/img/starticon/star-off.png" id="-4">&nbsp;<img class="" title="gorgeous" alt="5" src="{{$imgBaseUrl}}/Public/img/starticon/star-off.png" id="-5">
													<input type="hidden" name="score" id="-score">
												</span></td>
											</tr>
											<tr>
												<th>性价比：</th>
												<td><span num="0" class="star-readonly" style="cursor: default;"><img class="" title="bad" alt="1" src="{{$imgBaseUrl}}/Public/img/starticon/star-off.png" id="-1">&nbsp;<img class="" title="poor" alt="2" src="{{$imgBaseUrl}}/Public/img/starticon/star-off.png" id="-2">&nbsp;<img class="" title="regular" alt="3" src="{{$imgBaseUrl}}/Public/img/starticon/star-off.png" id="-3">&nbsp;<img class="" title="good" alt="4" src="{{$imgBaseUrl}}/Public/img/starticon/star-off.png" id="-4">&nbsp;<img class="" title="gorgeous" alt="5" src="{{$imgBaseUrl}}/Public/img/starticon/star-off.png" id="-5">
													<input type="hidden" name="score" id="-score">
												</span></td>
											</tr>
										</tbody>
									</table>
								</div>
								-->
								
							</div>
						</div>
					</li>
					{{/foreach}}
				</ul>

			</div>

			<div class="pagenav">
				{{$pagenav}}
			</div>
		</div>

		<div class="try_side fr">
			<h2 class="sidetitle"><s></s>试用申请流程</h2>
			<div class="process">
				<ul>
					<li>
						<p>
							即刻注册，按照填写提示写个人真实信息。
						</p>
					</li>
					<li>
						<p>
							选择您感兴趣的试用产品，提交试用申请。
						</p>
					</li>
					<li>
						<p>
							试用申请截止后，编辑会第一时间以站内短信的形式，通知获得试用资格的用户。
						</p>
					</li>
					<li>
						<p>
							收到试用产品两周内，在产品页提交试用心得。
						</p>
					</li>
				</ul>
			</div>
			<h2 class="sidetitle mt10">最新试用产品<span>TOP5</span></h2>
			<div class="side_report">
				<ul>
					{{foreach from=$list_top key=k item=v}}
					<li class="first">
						<div class="pic">
							<p class="wh80 verticalPic">
								<a href="trydetail{{$v.try_id}}.html"> <img width="80" 
									src="{{$imgBaseUrl}}/{{$v.img1|replace:'.':'_350_350.'}}" alt="芷敏 益生菌冲剂 (2g*12袋) 肠道养护 抗过敏"></a>
							</p>
						</div>
						<div class="con">
							<a href="trydetail{{$v.try_id}}.html"><h4>{{$v.try_goods_name}}</h4></a>
							<p class="name">
								试用产品：<a href="/goods-{{$v.goods_id}}.html" title="{{$v.goods_title}}">{{$v.goods_title}}</a>
							</p>
							<p class="pf">
								综合分：<b>0</b>分
							</p>
						</div>
					</li>
					{{/foreach}}
				</ul>
			</div>
		</div>
	</div>

	<script>
		function report_befor($frm) {
			if (!$('#valNO').val()) {
				$('#xj_login').show();
				return false
			}

			if (!$('#title').val()) {
				zt_message('标题不能为空！');
				return false;
			}

			if (!$('#cont').val()) {
				zt_message('内容不能为空！');
				return false;
			}

			if (!$('#valitnum').val()) {
				zt_message('验证码不能为空！');
				return false;
			}

			return true;
		}

	</script>
</div>
{{include file="footer.tpl"}}
