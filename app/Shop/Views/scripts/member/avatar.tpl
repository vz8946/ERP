
<link href="{{$_static_}}/css/jquery.Jcrop.css" media="all" rel="stylesheet" type="text/css" />
<link href="{{$_static_}}/css/jquery.Jcrop.min.css" media="all" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="/scripts/jquery.Jcrop.js"></script>
<script language="javascript" type="text/javascript" src="/Public/js/uploadify/jquery.uploadify-3.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="/Public/js/uploadify/uploadify.css" />

<div class="member">

{{include file="member/menu.tpl"}}
<div class="memberright">
	<div style="margin-top:10px;"><img src="{{$imgBaseUrl}}/images/shop/member_avatar.png"></div>
		{{if $member.photo}}
		<div style="padding:20px;line-height:30px;">
			<span style="color:#333;font-size:14px;font-weight:bold;">当前我的头像</span>
			<br />
			<img alt="我的头像" src="{{$imgBaseUrl}}/{{$member.photo}}" width="80" />
		</div>
		{{/if}}
		
		<div style="padding:20px;line-height:30px;">	
			<span style="color:#333;font-size:14px;font-weight:bold;">设置我的新头像</span>
			<span style="color:#333;font-size:12px;">
			请选择一个新照片进行上传编辑。上传图片不能超过1M，<br />
			头像保存后，您可能需要刷新一下本页面(按F5键)，才能查看最新的头像效果
			</span>
		</div>
		<div style="padding:20px;">
			<div class="wrp-pic" style="display:inline;position:relative;">
				<input type="file" id="pic"/>
			</div>
			<div id="target-box">
				<img src="{{$imgBaseUrl}}/images/shop/avata_demo.jpg" id="target" alt="我的头像"/>
			</div>
			<br />
		</div>
		<div style="padding-left:20px;padding-bottom:20px;">
      <form name="myForm" id="myForm" action="/member/avatar" method="post" onsubmit="return avatarSubmit()">
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
			<input type="hidden" id="bilv" name="bilv" />
			<input type="hidden" id="pichidden" name="pichidden"/>
			<input type="submit" class="buttons" value=" 提 交 " />
      </form>
      	</div>
  </div>
  
</div>
<script>
	var bilv=1;
    $('#pic').uploadify({
        swf      : '/Public/js/uploadify/uploadify.swf',
        uploader : '/member/uploadify',
        multi: false,
        width:100,
        height:20,
        formData:{ssid:'{{$ssid}}'},
        transparent: false,
        onUploadSuccess : function(file, data, response){
        	data = eval('('+data+')');
        	if(data.path=="" || data.path==null){
        		alert(data.msg);
        	}else{
        		$('#pichidden').val(data.path);
        		if(parseInt(data.width)>parseInt(data.height)){
        			bilv=parseInt(data.width)/300;
        			$('#target-box').html('<img src="{{$imgBaseUrl}}/'+data.path+'" width="300" id="target" alt="我的头像"/>');
        		}
        		else{
        			bilv=parseInt(data.height)/300;
        			$('#target-box').html('<img src="{{$imgBaseUrl}}/'+data.path+'" height="300" id="target" alt="我的头像"/>');
        		}
        		$("#bilv").val(bilv);
        		setJcrop();
            	
        	}
        },
		buttonText:'上传头像'
    });
</script>
<script type="text/javascript">
    // Create variables (in this scope) to hold the API and image size
    function setJcrop(){
    	var jcrop_api,
        boundx,
        boundy,

        // Grab some information about the preview pane
        $preview = $('#preview-pane'),
        $pcnt = $('#preview-pane .preview-container'),
        $pimg = $('#preview-pane .preview-container img'),

        xsize = $pcnt.width(),
        ysize = $pcnt.height();
    
    
    $('#target').Jcrop({
      onChange: updatePreview,
      onSelect: updatePreview,
      setSelect: [ 0, 50, 50, 0 ],
      aspectRatio: 1 / 1
    },function(){
      // Use the API to get the real image size
      var bounds = this.getBounds();
      boundx = bounds[0];
      boundy = bounds[1];
      // Store the API in the jcrop_api variable
      jcrop_api = this;

      // Move the preview into the jcrop container for css positioning
      $preview.appendTo(jcrop_api.ui.holder);
    });
    
    }
    function updatePreview(c)
    {
    	$("#x").val(c.x);
    	$("#y").val(c.y);
    	$("#w").val(c.w);
    	$("#h").val(c.h);
    };
    setJcrop();
    
    function avatarSubmit(){
    	if(parseInt($("#w").val())==0 || parseInt($("#h").val())==0){
    		alert("请选择头像区域！！");
    		return false;
    	}
    	if($('#pichidden').val()=="" || $('#pichidden').val()==null ){
    		alert("请上传您的头像！！");
    		return false;
    	}
    	return true;
    }
</script>

