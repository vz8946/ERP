<script language="javascript" type="text/javascript" src="/scripts/jquery.js"></script>
<script language="javascript" type="text/javascript" src="/scripts/admin/jquery.District.js"></script>
<script language="javascript">
{{foreach from=$orderList item=order}}
var area = getAddrValueByID({{$order.areaID}});
$.ajax({
    url: '/admin/schedule/comm-add-order/r/' + Math.random(),
    type: 'post',
    data: {orderID : {{$order.orderID}},
           orderTime : '{{$order.orderTime}}',
           payTime : '{{$order.payTime}}',
           freight : '{{$order.freight}}',
           email : '{{$order.email}}',
           consignee : '{{$order.consignee}}',
           area : area,
           address : '{{$order.address}}',
           mobile : '{{$order.mobile}}',
           tel : '{{$order.tel}}',
           goods : '{{$order.goods}}'
          },
    success:function(data){
        if (data) {
            document.getElementById('message').innerHTML = document.getElementById('message').innerHTML + data + '<br>';
        }
    }
});
{{/foreach}}
</script>
<div id="message">
</div>