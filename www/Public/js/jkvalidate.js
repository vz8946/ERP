var isLogin = 0;
$(document).ready(function(){
	//异步验证邮箱是否注册
	$("#email").blur(function(){
		var email =$("#email").val();
		var em_len = email.length;
		if(em_len > 4){
			if(!jkvalidate("email","#email")){
				$("#email_tip").html("<span style='color:red;'>邮箱格式不正确！</span>");
				return false;
			}
			$.ajax({type:"POST",url:"validateEmail.html",data:"data="+email,
				success: function(data){
    				$("#email_ed").html(data);
  				 },
  				 error:function(msg){
    				 alert("数据交互异常！");
  				 }
			});
		}else{
			$("#email_tip").html("<span style='color:red;'>输入邮箱最低高于四位数！</span>");
		}
	});
	$("#email").click( function () { $("#email_tip").html("<span style='color:#666666;'>请填写您常用的电子邮箱，方便日后找回密码。</span>");});
	$("#mobilePhone").click( function () { $("#mobilePhone_tip").html("<span style='color:#666666;'>请填写您常用手机号码。</span>");});
	$("#password1").click( function () { $("#password1_tip").html("<span style='color:#666666;'>请输入6到20位字母加数字或下划线的组合。</span>");});
	$("#password2").click( function () { $("#password2_tip").html("<span style='color:#666666;'>请重复输入一次上面输入的密码。</span>");});
	$("#num").click( function () { $("#num_tip").html("<span style='color:#666666;'>请输入四位字母数字组合。</span>");});
	$("#mobilePhone").blur(function(){
		if($("#mobilePhone").val().length > 0){
			if(!jkvalidate("mobilePhone","#mobilePhone")){
				$("#mobilePhone_tip").html("<span style='color:red;'>您的手机号码格式不正确，请重新填写！</span>");
				return false;
			}
		}
	});
	$("#password1").blur(function(){
			if(!jkvalidate("password","#password1")){
				$("#password1_tip").html("<span style='color:red;'>密码格式或长度不正确，请重新填写！</span>");
				return false;
			}
	});
	$("#password2").blur(function(){
		if($("#password1").val() != $("#password2").val()){
			$("#password2_tip").html("<span style='color:red;'>两次输入的密码不一致！</span>");
			return false;
		}
	});
	$("#num").blur(function(){
		if(!jkvalidate("valnum","#num")){
				$("#num_tip").html("<span style='color:red;'>验证码为四位字母数字组合！</span>");
				return false;
			}
	});

	/*购物流程开始*/
	$(".c_min").click(function(){
		var productId = $(this).attr("value");
		var num = $("#Num_"+productId).val();
		if(num == 1){
			return;
		}
		if(isNaN(num)){
			$("#Num_"+productId).val(1);
			return;
		}
		var obj = $(this);
		obj.hide();
		obj.next().show();
		$.ajax({
			type: "post",
			cache:false,
			url: "shop-goodsdel.html",
			dataType:'json',
			data: 'product_id=' + productId,
			error: function(msg){
				alert("网络故障！");
				return false;
			},
			success: function(json){
				$("#alltotal").html(json.total);
				$("#getjf").html(json.total);
				$("#shoppingMoney_"+productId).html("¥"+json.singletotal);
				$("#jifen_"+productId).html(json.jifen);
				$("#Num_"+productId).val(json.singlenum);
				$("#memberPrice_"+productId).html("¥"+json.memberPrice);
				$("#pricMsg").text(json.total>199?'已满199元已免运费':'还差'+(199-json.total)+'元即可免运费。');
              	 obj.next().hide();
                obj.show();
			}
		});
	});
	$(".c_add").click(function(){
	var productId = $(this).attr("value");
	var num = $("#Num_"+productId).val();
		if(isNaN(num)){
			$("#Num_"+productId).val(1);
			return;
		}
	var obj = $(this);
		obj.hide();
		obj.next().show();
		$.ajax({
			type: "post",
			cache:false,
			url: "shop-goodsadd.html",
			dataType:'json',
			data: 'product_id=' + productId,
			error: function(msg){
				alert("网络故障！");
				return false;
			},
			success: function(json){
				$("#alltotal").html(json.total);
				$("#getjf").html(json.total);
				$("#end_totle").html(json.total);
				$("#end_money").html(json.total);
				$("#shoppingMoney_"+productId).html("¥"+json.singletotal);
				$("#jifen_"+productId).html(json.jifen);
				$("#Num_"+productId).val(json.singlenum);
				$("#memberPrice_"+productId).html("¥"+json.memberPrice);
				$("#pricMsg").text(json.total>199?'已满199元已免运费':'还差'+(199-json.total)+'元即可免运费。');
				obj.next().hide();
				obj.show();
			}
		});
	});
	$(".checkAll").click(function(){
		if(($(this).attr("checked"))){
			$(".item_check").attr("checked","checked");
		}else{
			$(".item_check").attr("checked","");
		}
	});
	$("#goOrderForm").click(function(){
			$tmp_val = $("#valNO").val();
			if($tmp_val == ""){
				$("#xj_login").show();
			}else{
				window.location.href="settlement.html";
			}
	});


	//收藏
	$(".favProduct").click(function(){
		var is_st = $(this).attr("id");
		var id =is_st.replace("sc_","");
		var flag = $("#valNO").val();
		if(flag == ""){
			$("#xj_login").show();
		}else{
			var web_path = $("#Web_Name_Url").val();
			if(web_path != "" && web_path != "undefined" && web_path != null){
				window.location.href=web_path+"/addcol-"+id+".html";
			}else{
				window.location.href="addcol-"+id+".html";
			}
		}
	});
	//删除
	$(".deleteItem").click(function(){
		var is_st = $(this).attr("id");
		var id =is_st.replace("del_","");
		var flag = $("#valNO").val();
		if(flag == ""){
			$("#xj_login").show();
		}else{
			window.location.href="shopcartdel-"+id+".html";
		}
	});
	//批量删除
	$(".batchDelete").click(function(){
		var flag = $("#valNO").val();
		if(flag == ""){
			$("#xj_login").show();
		}else{
			var str = "";
			var len = $('.item_check').length;
			if(len < 1) return;
			$('.item_check').each(function(index){
				var tmp = $(this).val();
				str += tmp + "_";
			});
			var web_path = $("#Web_Name_Url").val();
			if(web_path != "" && web_path != "undefined" && web_path != null){
				window.location.href=web_path+"/shopcartdelmore-"+str+".html";
			}else{
				window.location.href="shopcartdelmore-"+str+".html";
			}

		}
	});
	//批量收藏
	$(".batchFavor").click(function(){
		var flag = $("#valNO").val();
		if(flag == ""){
			$("#xj_login").show();
		}else{
			var str = "";
			var len = $('.item_check').length;
			if(len < 1) return;
			$('.item_check').each(function(index){
				var tmp = $(this).val();
				str += tmp + "_";
			});
			window.location.href="addcolmore-"+str+".html";
		}
	});
	/*购物流程结束*/
	/*确认订单start*/
	$("#address .edit").toggle(
	  function () {
		  var len = parseInt($("#con_len").val());
			$(this).html("[取消修改]");
			if(len == 0){
				$("#list_panel").hide();
			}else{
				$("#list_panel").show();
				}
			$("#on_addr").hide();
			$("#on_editr").show();
			$(".btnSave").show();

	  },
	  function(){
		 var len = parseInt($("#con_len").val());
		 if(len == 0){
		 	zt_message("提示：您当前没有有效的收货地址，请继续添加地址！");
		}else{
			 $(this).html("[修改]");
			$("#list_panel").hide();
			$("#on_editr").hide();
			$(".btnSave").hide();
			$("#on_addr").show();
		}

	  }
);
	var pro = $("#provinces_province").val();
	var cit = $("#provinces_city").val();
	var are = $("#provinces_area").val();
	$(".region").chinaprovinces({province:pro,city:cit,area:are,change:function(province,city,area){
	$("#provinces_province").val(province);
	$("#provinces_city").val(city);
	$("#provinces_area").val(area);
	$(".regionName").html(province+city+area);
	}});

	$(".delete").click(function(){
		var id = $(this).attr("addrid");
		var web_path = $("#Web_Name_Url").val();
		var sub_url ="";
		if(web_path != "" && web_path != "undefined" && web_path != null){
			sub_url =web_path+"/syncdel_addr.html";
		}else{
			 sub_url = "syncdel_addr.html";
		}

		$.ajax({type:"POST",url:sub_url,data:"id="+id,
				success: function(data){
					if("success" == data){
						 zt_message("提示：收货地址删除成功，请继续操作！");
						 var len = parseInt($("#con_len").val());
						 if(len > 1){
							 var n = len -1;
							$("#con_len").val(n);
							$("#dtr_"+id).remove();
						}else{
							 $("#con_len").val(0);
							 $("#dtr_"+id).remove();
							 $(".addressListPanel").hide();
						}
					}else if("err1" == data){
						zt_message("提示：异常操作！删除数据失败，请重新操作！");
					}
					return fasle;
  				 },
  				 error:function(msg){
					alert("数据交互异常");
					return fasle;

  				 }
			});
	});
	$(".save").click(function(){
			 var len = parseInt($("#con_len").val());
			 if(len == 0){
				 syncadd_con("add");//新建
			}else{
				syncadd_con("update");//保存
			}


	});
	$(".saveNew").click(function(){
			syncadd_con("add");//新建

	});
	//索取发票
	$(".showInvoice").click(function(){
		$("#invoiceLayer").show();
	});
	$(".closeInvoice").click(function(){
		$("#invoiceLayer").hide();
	});
	$(".invoiceBtn").click(function(){
		var invoiceTitle = $.trim($("#invoiceTitle").val());
		if(invoiceTitle == ""){
				zt_message("提示：请输入发票抬头！");
		}else{
			$("#invoiceLayer").hide();
				zt_message("提示：发票信息已保存！");
			}
	});
	//提交订单
	$(".submitOrder").click(function(){
		//$(this).attr("disabled","disabled");
		var len = parseInt($("#con_len").val());
		var is_dis = $("#on_editr").css("display");
		if(len < 1){
			zt_message("提示：请在提交订单前填写收货地址，并保存！");
			return false;
		}else if(is_dis == "block"){
			zt_message("提示：请保存收货地址后再提交订单！");
			return false;
		}else{
			$("form").first().submit();
		}

	 });
	/*确认订单end*/
	$("#pay_ztbox img").click(function(){
		var value = $(this).attr("value");
		$("#pay").val(value);
		$("form").first().submit();
	});

});
//单击事件
function redClick(obj){
	var id = $(obj).val();
	$(".userName").val($("#fullname_"+id).val());
			var pro =$("#province_"+id).val();
			var cit = $("#city_"+id).val();
			var are = $("#area_"+id).val();
			$(".detailAddress").val($("#address_"+id).val());
			$(".phone").val($("#phone_"+id).val());
			$(".postalCode").val($("#zip_"+id).val());
			$("#on_id").val(id);
			$("#mobile").val($("#telephone_"+id).val());
			$(".region").chinaprovinces({province:pro,city:cit,area:are,change:function(province,city,area){
			$("#provinces_province").val(province);
			$("#provinces_city").val(city);
			$("#provinces_area").val(area);
			$(".regionName").html(province+city+area);
			}});
}
//异步添加用户数据
function syncadd_con(type){
	if(type == "add"){
		 var len = parseInt($("#con_len").val());
		 if(len > 4){
			zt_message("提示：收货地址数量不能超过五个，请选择其它地址进行修改！");
			 return false;
		}
	}
	if(type == "update"){
		var id_val =  $("#on_id").val();
		if(id_val == ""){
			zt_message("提示：数据异常，请重新操作！");
			return false;
		}
	}
	var userName =$.trim($(".userName").val());
			if(userName == ""){
				zt_message("提示：收货人姓名不能为空!");
				return false;
			}
			var pro = $("#provinces_province").val();
			if(pro == ""){
				zt_message("提示：请选择省份！");
				return false;
			}
			var cit = $("#provinces_city").val();
			if(cit == ""){
				zt_message("提示：请选择城市！");
				return false;
			}
			var are = $("#provinces_area").val();
			if(are == ""){
				zt_message("提示：请选择地区！");
				return false;
			}
			var addressLabel = $(".detailAddress").val();
			if(addressLabel == ""){
				zt_message("提示：请填写详细地址！");
				return false;
			}
			var phone = $(".phone").val();
			var postalCode = $(".postalCode").val();
			var mobile = $("#mobile").val();
			if(mobile == ""){
				zt_message("提示：请填写手机号码！");
				return false;
			}
			if(!jkvalidate("mobilePhone","#mobile")){
				zt_message("提示：手机号码格式不正确！");
				return false;
			}
			var con_id = $("#on_id").val();
			var web_path = $("#Web_Name_Url").val();
			var sub_url ="";
			if(web_path != "" && web_path != "undefined" && web_path != null){
				sub_url =web_path+"/syncadd_addr.html";
			}else{
				 sub_url = "syncadd_addr.html";
			}
			//提交数据
			$.ajax({type:"POST",url:sub_url,data:"userName="+userName+"&province="+pro+"&city="+cit+"&area="+are+"&mobile="+mobile+"&phone="+phone+"&address="+addressLabel+"&postalCode="+postalCode+"&con_id="+con_id+"&type="+type,
				success: function(data){
					if("err1" == data){
							zt_message("提示：异常操作！操作数据失败，请重新操作！");
							return fasle;
					}
					if("err2" == data){
							zt_message("提示：收货地址数量不能超过五个，请选择其它地址进行修改！");
							return fasle;
					}
					var id = 0;
					if(type == 'add'){
						id = parseInt(data);
					}else{
						id = con_id;
					}
					 var str_add_info = ' <p><span class="addressLabel">收货人姓名:</span>'+userName+'</p> <p><span class="addressLabel">详细地址:</span>'+pro+cit+are+addressLabel+'</p><p><span class="addressLabel">邮编:</span>'+postalCode+'</p><p><span class="addressLabel">固定电话:</span>'+phone+'</p><p><span class="addressLabel">手机:</span>'+mobile+'</p>';
					var str_add_hide ='<div id="hid_con_all_'+id+'"><input type="hidden" id="fullname_'+id+'" value="'+userName+'" /><input type="hidden" id="province_'+id+'" value="'+pro+'" /><input type="hidden" id="city_'+id+'" value="'+cit+'" /><input type="hidden" id="area_'+id+'" value="'+are+'" /> <input type="hidden" id="address_'+id+'" value="'+addressLabel+'" /> <input type="hidden" id="phone_'+id+'" value="'+phone+'" /><input type="hidden" id="telephone_'+id+'" value="'+mobile+'" /><input type="hidden" id="zip_'+id+'" value="'+postalCode+'" /></div>';
					var str_add_head = ' <tr id="dtr_'+id+'"><td width="85%"><label><input type="radio" name="rd"  onclick="redClick(this)"   value="'+id+'"  checked="checked" ><strong>'+userName+'  '+pro+cit+are+addressLabel+' </strong></label></td> <td><a addrid="'+id+'" class="delete" href="javascript:void(0)">[删除]</a></td></tr>';

					 var len = parseInt($("#con_len").val());
					if(len > 1){
						$(":radio").each(function(ind){
								if(name == "rd") {
									$(this).attr("checked","");
								}
						})
					}
					$("#on_addr").html(str_add_info);
					if(type == 'add'){
					 $("#con_len").val(len+1);
					$(str_add_head).appendTo(".addressList");
					$(str_add_hide).appendTo(".addressList");
						//组合显示当前添加的用户地址  判断地址长度再处理
					   var id = parseInt(data);
					   $("#on_id").val(id);
					   if(len == 0){
						   $("#on_addr_con").hide();
						   $("#on_editr").hide();
						   $("#on_addr").show();
						   $(".btnSave").hide();
						}else{
							$("#on_editr").show();
					 		$(".btnSave").show();
					 		$("#on_addr").hide();
					    	$("#on_addr_con").show();
					    	$("#list_panel").show();
						}					  					
						$("#m_content").html("提示：收货地址更新成功！");
					}else{
						var tr_id = "#dtr_"+con_id;
						var te=$(tr_id).replaceWith(str_add_head);
						var hid_con_all_id = "#hid_con_all_"+con_id;
						$(hid_con_all_id).replaceWith(str_add_hide);
						$("#address .edit").html("[修改]");
						$("#on_editr").hide();
					 	$(".btnSave").hide();
					 	$("#on_addr").show();
					    $("#on_addr_con").show();
					    $("#list_panel").hide();
						$("#m_content").html("提示：收货地址保存成功！");
					}
					 $("#xj_msg_info").fadeTo(1500,1,function(){
					 $("#xj_msg_info").fadeOut("slow");
					});
  				 },
  				 error:function(msg){
					alert("数据交互异常");
					return fasle;
  				 }
			});

	}

//提交注册信息
function checkRegister(){
	if(!jkvalidate("email","#email")){
		$("#email_tip").html("<span style='color:red;'>邮箱格式不正确！</span>");
		return false;
	}
	if(!jkvalidate("password","#password1")){
				$("#password1_tip").html("<span style='color:red;'>密码格式或长度不正确，请重新填写！</span>");
				return false;
			}
	if($("#password1").val() != $("#password2").val()){
			$("#password2_tip").html("<span style='color:red;'>两次输入的密码不一致！</span>");
			return false;
	}
	if(!jkvalidate("valnum","#num")){
				$("#num_tip").html("<span style='color:red;'>验证码为四位字母数字组合！</span>");
				return false;
			}
	$("form").first().submit();
}
//提交登陆信息
function checkLogin(){
	var logname =$.trim($("#loginId").val());
	if(logname == ""){
		$("#loginId_end").html("<span style='color:red;'>登陆账号信息不能为空！</span>");
		return false;
	}
	if(!jkvalidate("password","#password")){
				$("#password1_tip").html("<span style='color:red;'>密码格式或长度不正确，请重新填写！</span>");
				return false;
			}
	if(!jkvalidate("valnum","#num")){
				$("#num_tip").html("<span style='color:red;'>验证码为四位字母数字组合！</span>");
				return false;
			}
	$("form").first().submit();
}
//提交找回密码信息
function submitPassForm(){
	var logname =$.trim($("#loginId2").val());
	if(logname == ""){
		$("#loginId2_tip").html("<span style='color:red;'>用户名不能为空！</span>");
		return false;
	}
	if(!jkvalidate("email","#onlyemail")){
		$("#onlyemail_tip").html("<span style='color:red;'>邮箱格式不正确！</span>");
		return false;
	}
	$("form").first().submit();
}