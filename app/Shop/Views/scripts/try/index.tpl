{{include file="header_try.tpl"}} <script type=text/javascript src="{{$imgBaseUrl}}/Public/topic/js/jquery.slides.min.js"></script>

<div style="width: 990px;margin: 0px auto;">

	<div class="try_main fl " style="padding-top: 10px;">

		<!-- 滚动图片 -->
		<div id="slides" class="banner slides" style="height: 370px;width: 680px;">
			{{foreach from=$list_banner item=v key=k}}
			<a href="{{$v.url}}" target="_blank"><img alt="{{$v.name}}"
				src="{{$imgBaseUrl}}/{{$v.imgUrl}}"></a>
			{{/foreach}}
		</div>

		<script>
			$(function() {
				$('#slides').slidesjs({
					start : 1,
					play : {
						auto : true,
						interval : 3000,
						swap : true,
						effect : 'fade'
					},
					pagination : {
						active : true,
						effect : "fade"
					},
					effect : {
						fade : {
							speed : 120
						}
					},
					callback : {//回调
						start : function(c, i) {
						}
					}
				});
			});
		</script>

		<div class="aretrial clearfix">
			<h2><b>正在进行中的试用</b></h2>
			<div class="box">
				<ul>
					{{foreach from=$list item=v key=k}}
					<li>
						<div class="pic">
							<p class="wh160 verticalPic">
								<a href="/trydetail{{$v.try_id}}.html"><img alt="{{$v.try_goods_name}}"
								src="{{$imgBaseUrl}}/{{$v.img1|replace:'.':'_350_350.'}}" width="160"> </a>
							</p>
						</div>
						<div class="con">
							<p class="name">
								<a title="{{$v.try_goods_name}}" href="/trydetail{{$v.try_id}}.html">{{$v.try_goods_name}}</a>
							</p>

							<p class="time">
								截止日期：{{$v.end_time|date_format:'%Y-%m-%d'}}
								<br>
								试用品总份数：<b>{{$v.num}}份</b>
							</p>
							<p class="btntrial">
								<a href="/trydetail{{$v.try_id}}.html">申请试用</a>
							</p>
						</div>
					</li>
					{{/foreach}}
				</ul>
			</div>
		</div>
	</div>
	<div class="try_side fr">
		<div class="mt10">
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
		</div>

		<h2 class="sidetitle mt10">试用产品快递查询热线</h2>
		<div class="telnumInquir">
			<div>
				<img src="{{$imgBaseUrl}}/Public/img/180094.jpg" alt="">
			</div>
			<div>
				<img src="{{$imgBaseUrl}}/Public/img/180048.jpg" alt="">
			</div>
		</div>
		<h2 class="sidetitle mt10">免责声明</h2>
		<div class="tips">
			<p class="tip_p">
				1.本站所有试用产品均由合作品牌直接提供。垦丰网站仅为用户提供试用渠道及信息交流平台，产品的使用效果因产品本身及用户的个体差异而有所不同。垦丰网站对任何 第三方通过试用栏目提供的产品或服务不存在任何明示或默示的担保。
			</p>
			<p class="tip_p">
				2.因第三方的产品或服务的提供和使用导致的任何瑕疵、过错责任和纠纷，本网站不承担任何法律责任。
			</p>
		</div>
		<h2 class="sidetitle mt10">商务合作</h2>
		<div class="tips">
			<p class="tip_p">
				联系人：刘先生
			</p>
			<p class="tip_p">
				邮 箱：liulei@ejiankang.com
			</p>
			<p class="tip_p">
				联系电话：021-55053111-8020
			</p>
		</div>
	</div>

</div>

{{include file="footer.tpl"}}
