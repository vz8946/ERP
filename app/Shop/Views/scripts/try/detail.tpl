{{include file="header_try.tpl"}}
<div class="wbox">
	<div class="position">
		<b><a href="/try.html">试用中心</a></b><span> &gt; 试用详情 </span>
	</div>
	<div class="wrap clearfix">
		<input type="hidden" id="state" value="onTrial">
		<input type="hidden" id="toBegin" value="">
		<input type="hidden" id="toEnd" value="879346">
		<div class="trialin clearfix">
			{{if !$is_exp}}
			<div class="toolbar">
				<div class="tl">
					<a href="/trydetail{{$pid}}.html">&lt;&lt; <b>上一产品</b> </a>
					<a href="/trydetail{{$nid}}.html"><b>下一产品</b> &gt;&gt;</a>
				</div>
			</div>
			{{/if}}
			<div class="title">
				<p>
					{{$r.try_goods_name}}
				</p>
			</div>
			<div class="form clearfix">
				<div class="picbox">

					<p class="wh400 verticalPic">
						<a href="/goods-{{$r.goods_id}}.html" target="_blank"> <img width="400" src="{{$imgBaseUrl}}/{{$r.img1}}" alt="{{$r.try_goods_name}}"> </a>
					</p>
					<div class="share clearfix">
						<p class="txt">
							分享到：
						</p>
						<p class="shareicon">
							<a href="javascript:window.open('http://v.t.sina.com.cn/share/share.php?title='+encodeURIComponent(document.title)+'&amp;url='+encodeURIComponent(window.location.href)+'&amp;rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" class="t-sina" title="分享到新浪微博"> <img src="{{$imgBaseUrl}}/images/shop/share_sina.gif" alt="分享到新浪微博"> </a>
							<a href="javascript:(function(){window.open('http://v.t.qq.com/share/share.php?title='+encodeURIComponent(document.title)+'&amp;url='+encodeURIComponent(window.location.href)+'&amp;source=bookmark','_blank','width=610,height=350');})()" class="t-qq" title="分享到QQ微博"> <img src="{{$imgBaseUrl}}/images/shop/share_qq.gif" alt="分享到QQ微博"> </a>
							<a href="javascript:window.open('http://www.kaixin001.com/repaste/share.php?rtitle='+encodeURIComponent(document.title)+'&amp;rurl='+encodeURIComponent(window.location.href)+'&amp;rcontent=看中一个好东东，很好看，是垦丰的'+encodeURIComponent(document.title)+' 亲爱的您也看下吧'+encodeURIComponent(location.href),'_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" class="kaixin" title="分享到开心网"> <img src="{{$imgBaseUrl}}/images/shop/share_kaixin.gif" alt="分享到开心网"> </a>
							<a href="javascript:window.open('http://cang.baidu.com/do/add?it='+encodeURIComponent(document.title)+'&amp;iu='+encodeURIComponent(window.location.href)+'&amp;rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到百度收藏"> <img src="{{$imgBaseUrl}}/images/shop/share_baidu.gif" alt="分享到百度收藏"> </a>
							<a href="javascript:window.open('http://bai.sohu.com/share/blank/addbutton.do?title='+encodeURIComponent(document.title)+'&amp;link='+encodeURIComponent(window.location.href)+'&amp;rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到搜狐白社会"> <img src="{{$imgBaseUrl}}/images/shop/share_bai.gif" alt="分享到搜狐白社会"> </a>
							<a href="javascript:window.open('http://www.douban.com/recommend?title='+encodeURIComponent(document.title)+'&amp;link='+encodeURIComponent(window.location.href)+'&amp;rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到豆瓣"> <img src="{{$imgBaseUrl}}/images/shop/share_dou.gif" alt="分享到豆瓣"> </a>
						</p>
					</div>

				</div>
				<div class="frame">
					<div class="applyCon">
						<p class="price">
							<span class="s1">市场价:￥{{$r.goods.price}}</span>
							<span class="s2">免费提供：<b>{{$r.num}}</b> 份</span>
						</p>
						<ul>
							<li>
								试用日期：{{$r.start_time|date_format:'%Y-%m-%d'}} 至  {{$r.end_time|date_format:'%Y-%m-%d'}}
							</li>
							{{if !$is_exp}}
							<li id="trial-daojishi">
								<p>
									剩余时间：
								</p>
								&nbsp;<span  class="clock"  time="{{$r.dis_time}}"> </span>
							</li>
							{{/if}}
							<style>
								#trial-daojishi p {
									float: left;
								}
							</style>

							<li>
								试用邮费：卖家承担邮费
							</li>
						</ul>
						<div class="btnbox">
							{{if $is_login}}
							{{if !$is_exp}}
							<a href="#subform" class="btnApply" id="a_href" rel="nofollow">申请免费试用</a>
							{{else}}
							<span style="font-size: 2.0em;color: grey;position: relative;left:-14px;top:-3px;">已过期</span>
							{{/if}}
							{{else}}
							{{if !$is_exp}}
							<a href="/login.html" class="btnApply" id="a_href" rel="nofollow">申请免费试用</a>
							{{else}}
							<span style="font-size: 2.0em;color: grey;position: relative;left:-14px;top:-3px;">已过期</span>
							{{/if}}
							{{/if}}

							<span>已有<b>{{$r.order_num}}</b>人申请</span>
						</div>
					</div>
					<div class="buynowCon">
						<p class="txt">
							本试用品为商家推荐商品，正在热卖中
						</p>
						<p>
							<a href="/goods-{{$r.goods_id}}.html" target="_blank" class="btnbuynow">立即购买商品</a>
						</p>
					</div>
				</div>
			</div>

			<div class="articlebox">
				{{$r.try_desc}}
			</div>

			{{if $is_login && !$is_exp}}
			<a name="subform"></a>
			<div class="userinfo clearfix">
				<div class="infoCon">
					<p class="info_top">
						<b>为了便于获奖时发送奖品,还请您如实填写下面信息，感谢支持！</b>
						<br>
						<br>
						(仅作为获奖寄送奖品使用，还请您放心填写。)
					</p>
					<div class="fixbox">
						<form submitbefor="trialappl_befor"
						submitafter="trialappl_after"
						id="frm-trialappl-add" action="/tryorder.html" method="post">
							<input type="hidden" value="{{$r.try_id}}" name="try_id">
							<input type="hidden" name="addr_province" id="addr_province">
							<input type="hidden" name="addr_city" id="addr_city">
							<input type="hidden" name="addr_area" id="addr_area">
							<div style="padding-left:30px; ">
								<table width="100%" class="tbl-frm">
									<tr>
										<th>真实姓名：</th>
										<td>
										<input type="text" name="addr_consignee" value="" class="inp" id="addr_consignee">
										<span style="color:red;">*</span></td>
									</tr>
									<tr>
										<th>从事职业：</th>
										<td>
										<input type="text" name="occupation" value="" class="inp">
										</td>
									</tr>
									<tr>
										<th>手机号码：</th>
										<td>
										<input type="text" name="addr_mobile" value="" class="inp" id="addr_mobile">
										<span style="color:red;">*</span></td>
									</tr>
									<tr>
										<th>所在地区：</th>
										<td>
										<select id="addr_province_id" name="addr_province_id" onchange="getArea(this)">
											<option value="">请选择省份...</option>
											{{foreach from=$province item=p}}
											<option value="{{$p.area_id}}" {{if $p.area_id==$address.province_id}}selected{{/if}}>{{$p.area_name}}</option>
											{{/foreach}}
										</select>
										<select id="addr_city_id" name="addr_city_id" onchange="getArea(this)">
											<option value="">请选择城市...</option>
											{{if $province}}
											{{foreach from=$city item=c}}
											<option value="{{$c.area_id}}" {{if $c.area_id==$address.city_id}}selected{{/if}}>{{$c.area_name}}</option>
											{{/foreach}}
											{{/if}}
										</select>
										<select id="addr_area_id" name="addr_area_id" onchange="$('phone_code').value=this.options[this.selectedIndex].getAttribute('class')?this.options[this.selectedIndex].getAttribute('class'):this.options[this.selectedIndex].getAttribute('title');">
											<option value="">请选择地区...</option>
											{{if $city}}
											{{foreach from=$area item=a}}
											<option value="{{$a.area_id}}" {{if $a.area_id==$address.area_id}}selected{{/if}}>{{$a.area_name}}</option>
											{{/foreach}}
											{{/if}}
										</select><span style="color:red;">*</span></td>
									</tr>
									<tr>
										<th>详细地址：</th>
										<td>
										<input type="text" name="addr_address" value="" class="inp w300" id="addr_address">
										<span style="color:red;">*</span></td>
									</tr>
									<tr>
										<th>试用原因：</th>
										<td>										<textarea id="reason" name="reason" style="width: 460px;height: 180px;"></textarea><span style="color:red;">*</span></td>
									</tr>
									<tr>
										<th></th>
										<td><em>(不需要重复填写省、市、区)</em></td>
									</tr>
									<tr>
										<th></th>
										<td class="btns"><a href="javascript:void(0);" frmid="frm-trialappl-add" class="submit btn-ajax-submit" rel="nofollow">提&nbsp;&nbsp;交</a><a href="javascript:;" class="cancel" rel="nofollow">取&nbsp;&nbsp;消</a></td>
									</tr>

								</table>
							</div>
						</form>
					</div>
				</div>
			</div>
			{{/if}}

			<div class="tryresult mt10">
				<h2><s></s><b>网友试用心得</b></h2>

				<div class="reportList">
					{{foreach from=$list_report item=v key=k}}
					<div style="border-bottom: 1px solid #ddd;padding: 10px;margin-bottom: 10px;">
						<p>
							{{$v.user_name}} <span>{{$v.report_time|date_format:'%Y-%m-%d'}}</span>
						</p>
						<p>
							{{$v.report}}
						</p>
					</div>
					{{/foreach}}
				</div>

			</div>
		</div>
	</div>
</div>

{{include file="footer.tpl"}}

<script src="{{$imgBaseUrl}}/Public/js/asyncbox/AsyncBox.v1.4.5.js" type="text/javascript"></script>
<link href="/Public/js/asyncbox/skins/Ext/asyncbox.css" rel="stylesheet" type="text/css" />

<script src="{{$imgBaseUrl}}/Public/js/loadmask/jquery.loadmask.min.js" type="text/javascript"></script>
<link href="/Public/js/loadmask/jquery.loadmask.css" rel="stylesheet" type="text/css" />

<script src="{{$imgBaseUrl}}/Public/js/js.js" type="text/javascript"></script>

<script src="{{$imgBaseUrl}}/Public/js/groupon.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function() {
		$('.clock').each(function() {
			var $this = $(this)
			$this.YMCountDown("还剩");
		});

		ajaxinit();
	});

	//自定义验证函数 type:邮箱、手机、密码、验证码  id:验证字段的id 带#号
	function jkvalidate(type, id) {
		var value = $(id).val();
		if (value == "")
			return false;
		if ("email" == type) {
			var res = value.match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/);
			if (res == null) {
				return false;
			}
		} else if ("mobilePhone" == type) {
			if ($(id).val().match(/^1[3|4|5|8][0-9]\d{8,8}$/) == null) {
				return false;
			}
		} else if ("phone" == type) {
			if ($(id).val().match(/^((\(\d{3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}$/) == null) {
				return false;
			}
		} else if ("zip" == type) {
			if ($(id).val().match(/^[1-9]\d{5}$/) == null) {
				return false;
			}
		} else if ("password" == type) {
			if (value.length < 6 || value.length > 20) {
				return false;
			}
			var regex = /^[^_][A-Za-z]*[a-z0-9_]*$/;
			if (!regex.test(value)) {
				return false;
			}
		} else if ("valnum" == type) {
			if (value.length != 4) {
				return false;
			}
		}
		return true;
	}

	//联动
	function getArea(id) {
		var value = id.value;
		$(id).parent().children('select:last')[0].options.length = 1;
		$(id).next('select')[0].options.length = 1;
		$.ajax({
			url : '/flow/list-area-by-json',
			data : {
				area_id : value
			},
			dataType : 'json',
			success : function(msg) {
				var htmloption = '';
				$.each(msg, function(key, val) {
					htmloption += '<option value="' + val['area_id'] + '" class="' + val['code'] + '" title="' + val['code'] + '">' + val['area_name'] + '</option>';
				})
				$(id).next('select').append(htmloption);
			}
		})
	}

	function trialappl_befor($frm) {
		if (!jkvalidate('required', '#addr_consignee')) {
			alert('真实姓名不能为空！');
			return false;
		}
		if (!jkvalidate('mobilePhone', '#addr_mobile')) {
			alert('手机号码格式错误！');
			return false;
		}
		if (!jkvalidate('required', '#addr_area_id')) {
			alert('区域不能为空！');
			return false;
		}

		if (!jkvalidate('required', '#addr_address')) {
			alert('地址不能为空！');
			return false;
		}
		if (!jkvalidate('required', '#reason')) {
			alert('试用原因请认真填写，不能为空！');
			return false;
		}
		$('#addr_province').val($('#addr_province_id').find("option:selected").text());
		$('#addr_city').val($('#addr_city_id').find("option:selected").text());
		$('#addr_area').val($('#addr_area_id').find("option:selected").text());
		return true;
	}

	function trialappl_after(msg, $frm) {

		if (msg.status == 'fail-unlogin') {
			window.location.href = '/login.html';
			return false;
		}

		return true;
	}
</script>