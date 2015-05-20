Validator = {
Require : /.+/,
Email : /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
Phone : /^((\(\d{3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}$/,
Mobile : /^((\(\d{3}\))|(\d{3}\-))?1\d{10}$/,
Url : /^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/,
IdCard : /^\d{15}(\d{2}[A-Za-z0-9])?$/,
Currency : /^\d+(\.\d+)?$/,
Number : /^\d+$/,
Zip : /^[1-9]\d{5}$/,
QQ : /^[1-9]\d{4,8}$/,
Integer : /^[-\+]?\d+$/,
Double : /^[-\+]?\d+(\.\d+)?$/,
English : /^[A-Za-z]+$/,
Chinese : /^[\u0391-\uFFE5]+$/,
UnSafe : /^(([A-Z]*|[a-z]*|\d*|[-_\~!@#\$%\^&\*\.\(\)\[\]\{\}<>\?\\\/\'\"]*)|.{0,5})$|\s/,

DateTime :/^((\d{4}-(((0[13578]|1[02])-(0[1-9]|2[0-9]|3[01]))|((0[469]|11)-(0[1-9]|2[0-9]|30))|((02)-(0[1-9]|2[0-8]))))|(((([0-9]{2})(0[48]|[2468][048]|[13579][26]))|((0[48]|[2468][048]|[13579][26])00))-02-29))$/,
PassWordVali:/^[A-Za-z]+$/,
UserName:/^[A-Za-z]+$/,
IsCheck:/^[A-Za-z]+$/,
IsSafe : function(str){return !this.UnSafe.test(str);},
SafeString : "this.IsSafe(value)",
Limit : "this.limit(value.length,getAttribute('min'), getAttribute('max'))",
LimitB : "this.limit(this.LenB(value), getAttribute('min'), getAttribute('max'))",
Date : "this.IsDate(value, getAttribute('min'), getAttribute('format'))",
Repeat : "value == document.getElementsByName(getAttribute('to'))[0].value",
Range : "getAttribute('min') < value && value < getAttribute('max')",
Compare : "this.compare(value,getAttribute('operator'),getAttribute('to'))",
CompareTime :"this.compareTime(getAttribute('to'),value,getAttribute('tip'))",
Custom : "this.Exec(value, getAttribute('regexp'))",
Group : "this.MustChecked(getAttribute('name'), getAttribute('min'), getAttribute('max'))",
PassWordVali:"",
UserName:"",
IsCheck:"",
ErrorItem : [document.forms[0]],
ErrorMessage : ["以下原因导致提交失败：\t\t\t\t"],
Validate : function(theForm, mode){
var obj = theForm || event.srcElement;
var count = obj.elements.length;
this.ErrorMessage.length = 1;
this.ErrorItem.length = 1;
this.ErrorItem[0] = obj;
for(var i=0;i<count;i++){
with(obj.elements[i]){
var _dataType = getAttribute("dataType");
if(typeof(_dataType) == "object" || typeof(this[_dataType]) == "undefined") continue;
this.ClearState(obj.elements[i]);
if(getAttribute("require") == "false" && value == "") continue;
switch(_dataType){
case "Date" :
case "Repeat" :{
	if(theForm.password.value!=value){
		this.AddError(i, getAttribute("msg"));
	}
	break;
}
case "UserName" :{
	if(value.length<4 || value.length>20){
		this.AddError(i, getAttribute("msg"));
	}
	break;
}
case "IsCheck" :{
	if(checked==false){
		this.AddError(i, getAttribute("msg"));
	}
	break;
}
case "PassWordVali" :{
	if(value.length<6 || value.length>20){
		this.AddError(i, getAttribute("msg"));
	}
	break;
}
case "Range" :
case "Compare" :
case "Custom" :{
	if(!this.Exec(value, getAttribute('regexp'))){
		this.AddError(i, getAttribute("msg"));
	}
	break;
}
case "Group" :
case "Limit" :
case "LimitB" :
case "SafeString" :
if(!eval(this[_dataType])) {
this.AddError(i, getAttribute("msg"));
}
break;

case "CompareTime" :
{
	var errorstr=eval(this[_dataType]);
	if(errorstr!="")
	{this.AddError(i,errorstr);}
break;
}

default :
if(!this[_dataType].test(value)&& value!=""){
this.AddError(i, getAttribute("msg"));
}
break;
}
}
}
if(this.ErrorMessage.length > 1){
mode = mode || 1;
var errCount = this.ErrorItem.length;
switch(mode){
case 2 :
for(var i=1;i<errCount;i++)
this.ErrorItem[i].style.color = "red";
case 1 :
alert(this.ErrorMessage.join("\n"));
this.ErrorItem[1].focus();
break;
case 3 :
for(var i=1;i<errCount;i++){
try{
var span = document.createElement("SPAN");
span.id = "__ErrorMessagePanel";
span.style.color = "red";
this.ErrorItem[i].parentNode.appendChild(span);
span.innerHTML = this.ErrorMessage[i].replace(/\d+:/,"*");
}
catch(e){alert(e.description);}
}
this.ErrorItem[1].focus();
break;
default :
alert(this.ErrorMessage.join("\n"));
break;
}
return false;
}
return true;
},
limit : function(len,min, max){
min = min || 0;
max = max || Number.MAX_VALUE;
return min <= len && len <= max;
},
LenB : function(str){
return str.replace(/[^\x00-\xff]/g,"**").length;
},
ClearState : function(elem){
with(elem){
if(style.color == "red")
style.color = "";
var lastNode = parentNode.childNodes[parentNode.childNodes.length-1];
if(lastNode.id == "__ErrorMessagePanel")
parentNode.removeChild(lastNode);
}
},
AddError : function(index, str){
this.ErrorItem[this.ErrorItem.length] = this.ErrorItem[0].elements[index];
this.ErrorMessage[this.ErrorMessage.length] = this.ErrorMessage.length + ":" + str;
},
Exec : function(op, reg){
return new RegExp(reg,"g").test(op);
},

//update on 2007-7-27
compare : function(op1,operator,sop2){
	var op2;
	if(isNaN(sop2))
	  op2=document.getElementById(sop2).value;
	else
		op2=sop2;
if(isNaN(op1)||isNaN(op2)){

	return false;
}
else{
	op1=Number(op1);
	op2=Number(op2);
switch (operator) {
case "NotEqual":
return (op1 != op2);
case "GreaterThan":
return (op1 > op2);
case "GreaterThanEqual":
return (op1 >= op2);
case "LessThan":
return (op1 < op2);
case "LessThanEqual":
return (op1 <= op2);
default:
return (op1 == op2);
}
}
},
//this code is added on 2007-7-15--update on 2007-7-27
compareTime : function(date1,dtvalue2,tipmes){
	var strtip=new Array();
	    strtip[0]="开始时间";
	    strtip[1]="结束时间";
	if(tipmes!=""||tyepof(tipmes)!="undefined")
	   {
	   	strtip=tipmes.split(",");
	   }
	var errormsg="";
	var dateformat=/^\d{4}(?:-\d{1,2}){2}$/;
	var dtvalue1=document.getElementById(date1).value;
	if(dtvalue1!=""&&!this["DateTime"].test(dtvalue1))
	  {
	  	errormsg=strtip[0]+"格式不正确";
	  	return errormsg;
	  }
	if(dtvalue2!=""&&!this["DateTime"].test(dtvalue2))
	  {
	  	errormsg=strtip[1]+"格式不正确";
	  	return errormsg;
	  }

	if(dtvalue2!=""&&dtvalue1=="")
	  {
	  	errormsg="缺少"+strtip[0];
	  	return errormsg;
	  }

	if(dtvalue2==""&&dtvalue1!="")
	  {
	  	errormsg="缺少"+strtip[1];
	  	return errormsg;
	  }
	var strdt1=(dtvalue1).replace(/-/g,"/");
	var strdt2=(dtvalue2).replace(/-/g,"/");
	var time1=new Date(strdt1);
  var time2=new Date(strdt2);
  var datediff=time2-time1;
  if(datediff<0)
    {
    	errormsg=strtip[1]+"不能早于"+strtip[0];
      return errormsg;
	  }
	  return errormsg;
},




MustChecked : function(name, min, max){
var groups = document.getElementsByName(name);
var hasChecked = 0;
min = min || 1;
max = max || groups.length;
for(var i=groups.length-1;i>=0;i--)
if(groups[i].checked) hasChecked++;
return min <= hasChecked && hasChecked <= max;
},
IsDate : function(op, formatString){
formatString = formatString || "ymd";
var m, year, month, day;
switch(formatString){
case "ymd" :
m = op.match(new RegExp("^((\\d{4})|(\\d{2}))([-./])(\\d{1,2})\\4(\\d{1,2})$"));
if(m == null ) return false;
day = m[6];
month = m[5]--;
year = (m[2].length == 4) ? m[2] : GetFullYear(parseInt(m[3], 10));
break;
case "dmy" :
m = op.match(new RegExp("^(\\d{1,2})([-./])(\\d{1,2})\\2((\\d{4})|(\\d{2}))$"));
if(m == null ) return false;
day = m[1];
month = m[3]--;
year = (m[5].length == 4) ? m[5] : GetFullYear(parseInt(m[6], 10));
break;
default :
break;
}
if(!parseInt(month)) return false;
month = month==12 ?0:month;
var date = new Date(year, month, day);
return (typeof(date) == "object" && year == date.getFullYear() && month == date.getMonth() && day == date.getDate());
function GetFullYear(y){return ((y<30 ? "20" : "19") + y)|0;}
}
}
