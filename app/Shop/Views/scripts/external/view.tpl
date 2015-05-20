<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>处理验证码人工服务</title>
			<style>
				.content{
					border:1px solid #333;
					width:640px;
					margin:20px auto;
					padding:10px;
				}
				.content dl{
					margin:0;
					padding:0;
					float:left;
					width:200px;
					height:40px;
					overflow:hidden;
					display:inline;
				}
				.content dl dd{
					margin:0;
					padding:0;
					float:left;
					height:28px;
					line-height:28px;
					width:128px;
					text-align:center;
					font-size:12px;
					color:#333;
					border-bottom:1px solid #333;
					overflow:hidden;
					display:inline;
				}
				.bold{
					font-weight:bold;
				}
				
			</style>
<SCRIPT Language = "Jscript">
	window.onload = LoadObject;
	
	function LoadObject(){
		try {
			document.all.lv_obj.Init("PCS_Test");
		} catch (err) {
			window.alert("错误信息: " + err.message);
		}
		lv_obj.AttachWeb(window);
		if (document.all.lv_obj.IsConnected) {
			document.title = "Test Client - Connected";
		}
		else {
			document.title = "Test Client - Disconnected";
		}
		
		SetAgentStatusName(0);
	}
	
	
	
	function MakeCall(phone) {
		var CALLSCALE_NATIONAL =3;
		try {
			document.all.lv_obj.SingleStepTransfer(phone,CALLSCALE_NATIONAL);
		} catch (err) {
			window.alert("错误信息: " + err.message);
		}
	}

	
	function DoDataRecieved(lv_evtType,lv_evtValue,lv_evtData,lv_usrData) {
		var lv_strShow;
		if (lv_evtData=="") {
		   lv_evtData = "(null)";
		}
    
		if (lv_usrData=="") {
		   lv_usrData = "(null)";
		}
		
		switch (lv_evtType) {
		case 1: //evtConnect
		    document.title = "Test Clinet - Connected";
		    break;
		case 2: //evtDisconnect
		   document.title = "Test Clinet - Disconnected";
		   break;
		case 100: //evtGetUserData
		   document.all.lv_obj.SetUserData(document.all.txtUserData.value);
		   break;
		case 5: //evtStateChange
			SetAgentStatusName(lv_evtValue);
		    break;
		case 50: //evtCallIn
		   lv_strShow = "Call In [ID:" + lv_evtValue + ", EventData:" + lv_evtData + ", UserData:" + lv_usrData + "]";
		   break;
		   //Debug.Print lv_strShow
		case 53: //evtCallAnswer
		   lv_strShow = "Call Answered [ID:" + lv_evtValue + "]";
		   break;
		case 52: //evtCallClear
		   lv_strShow = "Call Cleared [ID:" + lv_evtValue + "]";
		   break;
		case 70: //evtConference
		   lv_strShow = "Conference [ID:" + lv_evtValue + "]";
		   break;
		case 60: //evtTransfer
		   lv_strShow = "Transfer [ID:" + lv_evtValue + "]";
		   break;
		case 120: //evtDestSeized
		   lv_strShow = "Destination is seized now [ID:" + lv_evtValue + ", TelNo:" + lv_evtData + "]";
		   break;
		case 122: //evtDestInvalid
		   lv_strShow = "Destination is invalid [ID:" + lv_evtValue + ", TelNo:" + lv_evtData + "]";
		   break;
		case 121: //evtDestBusy
		   lv_strShow = "Destination is busy now [ID:" + lv_evtValue + ", TelNo:" + lv_evtData + "]";
		   break;
		}
		lv_evtType = parseInt(lv_evtType);
		if (lv_evtType!=1 && lv_evtType!=2 && lv_evtType!=100  && lv_evtType!=5) {
			var oOption = document.createElement("OPTION");
			oOption.text=lv_strShow;
			document.all.selCall.add(oOption);
		}	
		
	}
	
	function SetAgentStatusName(lv_evtValue) {
		switch(parseInt(lv_evtValue)) {
			case 0: //MSI_UNKNOWN
				document.all.tdStatus.innerHTML = "未知";
				break;
			case 108:  //MSI_CALLOUT
				document.all.tdStatus.innerHTML = "拨号";
				break;
			case 102: //MSI_AFTERCALL
				document.all.tdStatus.innerHTML = "后处理";
				break;
			case 99:
				document.all.tdStatus.innerHTML = "DND";
				break;
			case 103:
				document.all.tdStatus.innerHTML = "空闲";
				break;
			case 100:
				document.all.tdStatus.innerHTML = "未登录";
				break;
			case 104:
				document.all.tdStatus.innerHTML = "监控";
				break;
			case 101:
				document.all.tdStatus.innerHTML = "未就绪";
				break;
			case 109:
				document.all.tdStatus.innerHTML = "通话-主叫";
				break;
			case 105:
				document.all.tdStatus.innerHTML = "振铃";
				break;
			case 107:
				document.all.tdStatus.innerHTML = "通话-被叫";
				break;
			case 106:
				document.all.tdStatus.innerHTML = "转接中";
				break;
			default:
				document.all.tdStatus.innerHTML = "未知";
				break;
		}
	}

	function OnDataRecievedCallBack(lv_evtType,lv_evtValue, lv_evtData, lv_usrData) {
		//alert(lv_evtType + " ||| " + lv_evtValue + " ||| " + lv_evtData + " ||| " + lv_usrData)
		DoDataRecieved(lv_evtType,lv_evtValue, lv_evtData, lv_usrData);
	}
</SCRIPT>
	</head>
	<body>
	<object id="lv_obj"  classid = "CLSID:30A92485-94D2-4CBA-AC32-EF276B7F777B" CODEBASE="" ></OBJECT>
		<div class="content">
			<dl>
				<dd class="bold" style="text-align:right;border-bottom:none;width:68px;">
					验证码：
				</dd>
				<dd>
					{{$validate}}
				</dd>
			</dl>
			<dl>
				&nbsp;
			</dl>
			<dl>
				&nbsp;
			</dl>
			<dl>
				<dd class="bold" style="text-align:right;border-bottom:none;width:68px;">
					单据类型：
				</dd>
				<dd>
					{{if $transport.bill_type eq 1}}
						销售单
					{{elseif $transport.bill_type eq 2}} 
						内部派单
					{{/if}}
				</dd>
			</dl>
			<dl>
				<dd class="bold" style="text-align:right;border-bottom:none;width:68px;">
					单据编号：
				</dd>
				<dd>
					{{$transport.bill_no}}
				</dd>
			</dl>
			<dl>
				<dd class="bold" style="text-align:right;border-bottom:none;width:68px;">
					制单日期：
				</dd>
				<dd>
					{{$transport.add_time|date_format:"%Y-%m-%d"}}
				</dd>
			</dl>
			<dl>
				<dd class="bold" style="text-align:right;border-bottom:none;width:68px;">
					付款方式：
				</dd>
				<dd>
					{{if $transport.is_cod}}货到付款{{else}}非货到付款{{/if}}
				</dd>
			</dl>
			<dl>
				<dd class="bold" style="text-align:right;border-bottom:none;width:68px;">
					快递公司：
				</dd>
				<dd>
					{{$transport.logistic_name}}
				</dd>
			</dl>
			<dl>
				<dd class="bold" style="text-align:right;border-bottom:none;width:68px;">
					运单号：
				</dd>
				<dd>
					{{$transport.logistic_no}}
				</dd>
			</dl>
			<dl>
				<dd class="bold" style="text-align:right;border-bottom:none;width:68px;">
					收货人：
				</dd>
				<dd>
					{{$transport.consignee}}
				</dd>
			</dl>
			<dl>
				<dd class="bold" style="text-align:right;border-bottom:none;width:68px;">
					省：
				</dd>
				<dd>
					{{$transport.province}}
				</dd>
			</dl>
			<dl>
				<dd class="bold" style="text-align:right;border-bottom:none;width:68px;">
					市：
				</dd>
				<dd>
					{{$transport.city}}
				</dd>
			</dl>
			<dl>
				<dd class="bold" style="text-align:right;border-bottom:none;width:68px;">
					区：
				</dd>
				<dd>
					{{$transport.area}}
				</dd>
			</dl>
			<dl>
				<dd class="bold" style="text-align:right;border-bottom:none;width:68px;">
					详细地址：
				</dd>
				<dd title="{{$transport.address}}">
					{{$transport.address}}
				</dd>
			</dl>
			<dl>
				<dd class="bold" style="text-align:right;border-bottom:none;width:68px;">
					手机号码：
				</dd>
				<dd>
					{{if $transport.mobile }}
						<span style="height:28px;float:left;">{{$transport.mobile}}</span>
						<a href="javascript:MakeCall('9{{$transport.mobile}}');"><img src="{{$imgBaseUrl}}/images/auth/phone.png" /></a>
					{{/if}}
				</dd>
			</dl>
			<dl>
				<dd class="bold" style="text-align:right;border-bottom:none;width:68px;">
					座机号码：
				</dd>
				<dd>
					{{if $transport.tel}}
						<span style="height:28px;float:left;">{{$transport.tel}}</span>
						<a href="javascript:MakeCall('{{$transport.s_tel}}');"><img src="{{$imgBaseUrl}}/images/auth/phone.png" /></a>
					{{/if}}
				</dd>
			</dl>
			<div style="clear: both;"></div>
		</div>
	</body>	
</html>