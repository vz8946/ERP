$(document).ready(function(){
	$(".mboix .m_fbold i").click(function(){
		if($(this).attr("class")=="m_on"){
			$(this).attr("class","m_off");
			$(this).parent().next().hide();;
		}else{
			$(this).attr("class","m_on");
			$(this).parent().next().show();;
		}
	});
	var pro = $("#provinces_province").val();
	var cit = $("#provinces_city").val();
	var are = $("#provinces_area").val();
	$("#addressbox").chinaprovinces({province:pro,city:cit,area:are,change:function(province,city,area){
	$("#provinces_province").val(province);
	$("#provinces_city").val(city);
	$("#provinces_area").val(area);
	}});
});
function checkforms(){
	var type=$("#complType").val();
	if(type==""){
		alert("请选择投诉类型");
		$("#complType").focus();
		return false;
	}
	var type=$("#title").val();
	if(type==""){
		alert("请输入投诉标题！");
		$("#title").focus();
		return false;
	}
	return true;
}
function myAddresssubmit(){
	var fullname=$("#fullname").val();
	if(fullname==""){
		zt_message("收货人信息不能为空");
		$("#fullname").focus();
		return false;
	}
	if(!jkvalidate("mobilePhone","#telephone") && $("#telephone").val()!=""){
		zt_message("手机号码格式有误！");
		$("#telephone").focus();
		return false;
	}
	if(!jkvalidate("phone","#phone") && $("#phone").val()!=""){
		zt_message("电话号码格式有误！");
		$("#phone").focus();
		return false;
	}
	var provinces_province=$("#provinces_province").val();
	if(provinces_province==""){
		zt_message("省份不能为空！");
		return false;
	}
	var provinces_city=$("#provinces_city").val();
	if(provinces_city==""){
		zt_message("市县不能为空！");
		return false;
	}
	var provinces_area=$("#provinces_area").val();
	if(provinces_area==""){
		zt_message("市区不能为空！");
		return false;
	}
	var address=$("#address").val();
	if(address==""){
		zt_message("详细地址不能为空！");
		$("#address").focus();
		return false;
	}
	if(!jkvalidate("zip","#zip")){
		zt_message("邮政编码格式有误且不能为空！");
		$("#zip").focus();
		return false;
	}
	if($("#telephone").val()=="" && $("#phone").val()==""){
		zt_message("手机号码或者电话号码最少填写一项！");
		$("#telephone").focus();
		return false;
	}
	return true;

}