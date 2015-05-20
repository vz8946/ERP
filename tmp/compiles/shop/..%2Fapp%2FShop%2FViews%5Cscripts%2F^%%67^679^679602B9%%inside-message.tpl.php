<?php /* Smarty version 2.6.19, created on 2014-11-11 14:54:11
         compiled from member/inside-message.tpl */ ?>
<div class="member">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "member/menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <div class="memberright"> 
	<div style="margin-top:11px;"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/shop/member_note.png"></div>
    <div class="ordertype">
        <a href="/member/inside-message/read/0" <?php if ($this->_tpl_vars['params']['read'] == 0): ?> class="sel" <?php endif; ?>>未读</a>
        <a href="/member/inside-message/read/1" <?php if ($this->_tpl_vars['params']['read'] == 1): ?> class="sel" <?php endif; ?>>已读</a>
    </div>
	<table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
        <thead>
            <tr>
                <th>类型</th>
                <th align="left">标题</th>
                <th>发送时间</th>
            </tr>
        </thead>
        <tbody >
            <?php $_from = $this->_tpl_vars['infos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['info']):
?>
            <tr>
                <td><?php echo $this->_tpl_vars['info']['message_type']; ?>
</td>
                <td class="title" message_id="<?php echo $this->_tpl_vars['info']['message_id']; ?>
" align="left" read="<?php echo $this->_tpl_vars['info']['is_read']; ?>
"><?php echo $this->_tpl_vars['info']['title']; ?>
</td>
                <td><?php echo $this->_tpl_vars['info']['created_ts']; ?>
</td>
            </tr>
            <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
    <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
    <div style="display:none;" id="message_content">
        <table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
            <tr>
                <td width="200">标题：</td>
                <td id="list_title" style="text-align:left"></td>
            </tr>
            <tr>
                <td>内容：</td>
                <td id="list_content" style="text-align:left"></td>
            </tr>
        </table>
    </div>
  </div>
</div>

<script>
    $(function(){
        $(".title").css({'color':'#0000ff', 'text-decoration': 'underline', 'cursor': 'pointer'});
        $(".title").click(function(){
            var message_id = parseInt($(this).attr('message_id'));
            var read       = $(this).attr('read');
            var obj        = $(this);
            if (message_id < 1) {
                alert('信息ID不正确');
                return false;
            }

            $.ajax({
				url : '/member/view-message/message_id/' + message_id +'/read/'+read,
				success : function(data) {
                    data = $.parseJSON(data);
                    if (data.success == 'false') {
                        alert(data.message);
                        return false;
                    } else {
                        obj.attr('read', 1);
                        var info = data.data;
                        $("#list_title").html(info.title);
                        $("#list_content").html(info.content);
                        $("#message_content").css({'display': 'block'});
                    }
                }
                
                
            })
        })
    })
</script>