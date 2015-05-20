/*
*����
*/
var recObject = new Object();

/*
*��ƷID���飬����Ϊ1��
*�Ƽ���Ʒ���ظ�����Ŀǰ֧��20��
*/
var _ozitem_id = new Array("698");
var _oznumber = 5;

/*
1.�����Ƽ�������Ƽ��������Ƽ���ϵͳĿǰ֧�ֵ������Ƽ�����
2.1,2,51�������Ƽ����Ͷ�Ӧ��Type�������޸�
3.�����Ƽ����Ϳ���ʹ�ø��Ե�_ozitem_id��_oznumber
4.����ʾ���������Ƽ�����ʹ����ͬ��_ozitem_id��_oznumber
*/
recObject.ozrel = new Array(_ozitem_id, _oznumber, 51);
recObject.ozvav = new Array(_ozitem_id, _oznumber, 2);
recObject.ozbab = new Array(_ozitem_id, _oznumber, 1);



/*
*div����,�����Ƽ�ҳ��
*/
var rec_div=new Array();
rec_div[1]="recomm_direct_1";
rec_div[2]="recomm_direct_2";
rec_div[51]="recomm_direct_51";

var img_arr=["2009122803423933", "2009122807120785", "2009122803483125", "2009122806103572", "2009122807120785"];


/*
*
*�Ƽ���Ʒ�������,�����������û�����ʵ�������д
*
*/
function oz_recommend(rec_result)
{	
	console.log(rec_result);
	var div_con;
	var ind=0;
	var oadz_status = rec_result.s;
	if(oadz_status!=0)  
	{	
		var res_s=rec_result.res_s;
		if(res_s==1) 
		{		
			ind=rec_result.res_t;
			div_con = generate_rec_div(rec_result.res_v, ind);
			try
			{	alert(rec_div[ind]);
				document.getElementById(rec_div[ind]).innerHTML = div_con;
			}
			catch (ex){}
		}
	}	
}

function generate_rec_div(recArr, recType)
{
	var divContent="";
	
	for(var i=0; pid_rec_Arr=recArr[i]; i++)
	{	
		var pid = pid_rec_Arr[0];
		alert(pid);
		var rel_pid = pid_rec_Arr[1];
		var c_div_con = "<div class='c_m' id='div_"+recType+"_"+pid+"'><ul>";
		for(ii=0; j=rel_pid[ii]; ii++)
		{
			var attr=""+pid+"_"+recType+"_"+j;
			var con="<li><a href='goods.php?id="+j+"&cid=65' ozrec='__rel_"+attr+"' name='"+attr+"'><img src='http://demo.99click.com/docs/images/goods/2009/12/28/thumb_"+img_arr[j%5]+".jpg' alt='"+attr+"' /></a><a  class='pro_name' href='http://demo.99click.com/goods.php?id="+j+"&cid=65'>"+recType+":"+pid+":"+j+"</a></li>"
			console.log(con);
			c_div_con += con;
		}
		c_div_con=c_div_con+"</ul></div>"
		divContent += c_div_con;
	}
	
	return divContent;
}
