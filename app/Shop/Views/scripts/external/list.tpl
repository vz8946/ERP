<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>收货地址列表</title>
			<style>
				body, h1, h2, h3, h4, h5, h6, p, div, table, tr, td, ul, ol, li, dl, dt, dd, img, a, from {
				    border: 0 none;
				    margin: 0;
				    padding: 0;
				}
				* {
				    list-style: none outside none;
				    margin: 0;
				    padding: 0;
				}
				.content{
					border:1px solid #333;
					width:1000px;
					margin:20px auto;
					padding:10px;
				}
				.bold{
					font-weight:bold;
				}
				.list{
					float:left;
					width:1000px;
					height:25px;
					overflow: hidden;
				}
				.list li{
					float:left;
					text-align:center;
					height:25px;
					line-height:25px;
					font-size:14px;
					margin-left: 1px;
					display: inline;
				}
				.background{
					background-color: #EEEEEE;
				}
				.w20{
					width:110px;
				}
			</style>
	</head>
	<body>
		<div class="content">
			<center>
			<form action="/external/list" method="post" name="searchBox">
			验证码：<input name="validate" />
			运单号：<input name="logistic_no" />
			收货人：<input name="consignee" />
			
			<input type="submit" value="查询"/>
			</form>
			</center>
		</div>
		
		<div class="content mt10">
			<ul class="list">
				<li class="w20 bold background">验证码</li>
				<li class="w20 bold background">单据类型</li>
				<li class="w20 bold background">单据编号</li>
				<li class="w20 bold background">付款方式</li>
				<li class="w20 bold background">快递公司</li>
				<li class="w20 bold background">运单号</li>
				<li class="w20 bold background">收货人</li>
				<li class="w20 bold background">制单日期</li>
				<li class="w20 bold background">操作</li>
			</ul>
			{{if $transport }}
				{{foreach from=$transport item=vo}}
				<ul class="list">
					<li class="w20">{{$vo.validate_sn}}</li>
					<li class="w20">{{if $vo.bill_type eq 1}}
							销售单
						{{elseif $vo.bill_type eq 2}} 
							内部派单
						{{/if}}</li>
					<li class="w20">{{$vo.bill_no}}</li>
					<li class="w20">{{if $vo.is_cod}}货到付款{{else}}非货到付款{{/if}}</li>
					<li class="w20">{{$vo.logistic_name}}</li>
					<li class="w20">{{$vo.logistic_no}}</li>
					<li class="w20">{{$vo.consignee}}</li>
					<li class="w20">{{$vo.add_time|date_format:"%Y-%m-%d"}}</li>
					<li class="w20"><a href="/external/view?validate={{$vo.validate_sn}}" target="_blank">查看</a></li>
				</ul>
				{{/foreach}}
			{{else}}
				<center>无结果，请输入正确查询条件查询！！</center>
			{{/if}}
			<div style="clear: both;"></div>
		</div>
	</body>	
</html>