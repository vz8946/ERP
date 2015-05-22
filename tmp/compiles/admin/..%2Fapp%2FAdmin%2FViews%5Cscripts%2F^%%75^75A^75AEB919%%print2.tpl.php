<?php /* Smarty version 2.6.19, created on 2014-10-23 10:46:19
         compiled from transport/print2.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'transport/print2.tpl', 45, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title></title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Cache-Control" content="public">
<style type="text/css">
body
{
  background-color: #ffffff;
  padding: 0px;
  margin: 0px;
  text-align:center;
}
table, td, div {
    font: normal 14px  Verdana, "Times New Roman", Times, serif;
    font-weight: bolder;
}
.table_box
{
  table-layout: fixed;
  text-align:center;
}
.display_no
{
  display:none;
}
</style>
<style media=print>
.Noprint{display:none;}
.PageNext{page-break-after: always;}
</style>
</head>
<body id="print">


</body>
</html>

<script type="text/javascript">
onload = function()
{
    <?php $_from = $this->_tpl_vars['templates']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['template'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['template']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['template']):
        $this->_foreach['template']['iteration']++;
?>
    _create_shipping_print('<?php echo $this->_tpl_vars['template']['config']; ?>
', '<?php echo $this->_tpl_vars['template']['image']; ?>
', '<?php echo $this->_tpl_vars['template']['image_size']['width']; ?>
', '<?php echo $this->_tpl_vars['template']['image_size']['height']; ?>
', <?php if ($this->_foreach['template']['iteration'] < count($this->_tpl_vars['templates'])): ?>1<?php else: ?>0<?php endif; ?>);
    <?php endforeach; endif; unset($_from); ?>
}

function _create_shipping_print(config, image, width, height, page_break)
{
    var print_bg = _create_print_bg(image, width, height, page_break);

    var config_lable = config;

    var lable = config_lable.split("||,||");

    if (lable.length <= 0) {
      return false; 
    }
    
    for (var i = 0; i < lable.length; i++) {
        var text = lable[i].split(",");
        if (text.length <= 0 || text[0] == null || typeof(text[0]) == "undefined" || text[0] == '') {
            continue;
        }
        
        text[4] -= 10;
        text[5] -= 10;

        _create_text_box(print_bg, text[0], text[1], text[2], text[3], text[4], text[5]);
    }
}

function _create_print_bg(image, width, height, page_break)
{
    var print_bg = document.createElement('div');
    //print_bg.setAttribute('id', 'print_bg');

    var print = document.getElementById('print');
    print.appendChild(print_bg);
    
    if ( page_break ) {
        print_bg.className = 'PageNext';
    }
    
    //print_bg.style.background = 'url(/' + image + ') no-repeat';
    print_bg.style.width = width + 'px';
    print_bg.style.height = height + 'px';
    print_bg.style.zIndex = 1;
    print_bg.style.border = "solid 1px #FFF";
    print_bg.style.padding = "0";
    print_bg.style.position = "relative";
    print_bg.style.margin = "0";
  
    return print_bg;
}

function _create_text_box(print_bg, id, text_content, text_width, text_height, x, y)
{
    var text_box = document.createElement('div');

    //text_box.setAttribute('id', id);

    print_bg.appendChild(text_box);
  
    text_box.style.width = text_width + "px";
    text_box.style.height = text_height + "px";
    text_box.style.border = "0";
    text_box.style.padding = "0";
    text_box.style.margin = "0 auto";

    text_box.style.position = "absolute";
    text_box.style.top = y + "px";
    text_box.style.left = x + "px";

    text_box.style.wordBreak = 'break-all';
    text_box.style.textAlign = 'left';

    text_box.innerHTML = text_content;

    return true;
}
</script>