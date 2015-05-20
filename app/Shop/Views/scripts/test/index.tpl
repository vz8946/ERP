<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
<center>
<form action="/test/send-msg/" method="post">
<table>
<tr><th>标题：</th><td><input type="text"  name="title" /></td></tr>
<tr><th>作者：</th><td><input type="text"  name="author" /></td></tr>
<tr><th>简介：</th><td><textarea name="summary"></textarea></td></tr>
<tr><th>内容：</th><td><textarea name="content" style="width:500px;height:300px"></textarea></td></tr>
<tr><th>图片地址：</th><td><input type="text" name="filepath" /></td></tr>
<tr><th>图片链接地址：</th><td><input type="text" name="srcurl" /></td></tr>
<tr><th></th><td><input type="submit" value="提交" /></td></tr>
</table>
</form>
</center>
</body>
</html>