<html>
<head>
<title>{$subject}</title>
<style type="text/css">{literal}
body{font-size:11px;font-family:Verdana,Arial,Helvetica,sans-serif;background-color:#ffffff;text-align: center;}
table{border-collapse:collapse;border-spacing:0;border: none;text-align: left;}
td{text-align:left;}
p,ul,li{font-family:verdana,sans-serif;font-style:normal;font-size:11px;color:#636363;}
h1{font-weight:bold;font-family:verdana,sans-serif;font-style:normal;font-size:16px;color:#bf2e2f;margin-top:10px;font-weight: bold;}
h2{font-weight:bold;font-family:verdana,sans-serif;font-style:normal;font-size:13px;color:#0e3e60;margin-top:10px;}
h3{font-weight:bold;font-family:verdana,sans-serif;font-style:normal;font-size:12px;color:#000000;margin-top:10px;}
h4{font-weight:bold;font-family:verdana,sans-serif;font-style:normal;font-size:11px;color:#0e3e60;margin-top:10px;}
a{font-size:11px;text-decoration:none;color:#bf2e2f;font-style:normal;font-family:verdana,sans-serif;}
{/literal}</style>
</head>
<body bgcolor="#ffffff">
<table width="620" border="1" cellpadding="10" align="center" style="border: 1px solid #808080"><tr><td>
<table width="600" border="0" cellspacing="10" align="center" bgcolor="#ffffff">
<tr><td><img src="{$SITEURL}/images/email-header.jpg" width="600" height="108" border="0"/></td></tr>
<tr><td style="text-align:center; padding:10px;" width="600">
{$content}
</td></tr>
<tr ><td colspan="2" style="text-align:left; padding:10px;">
<hr color="#dddddd" size="1" style="clear:both;" />
<p style="font-family:Arial; font-size:11px;color:#636363;">

Visit us at {$SITEURL}</p>
<p>&copy; {$SITEURL}</p>
</td></tr></table>
</td></tr></table>
</body>
</html>