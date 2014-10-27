<?php
	include("config.php");
?>
<html>
<head>
<title><?php echo $cfg["wsite_vs"]; ?></title>
<meta http-equiv="Content-Type" content="text/html;">
<link href="bot.css" rel="stylesheet" type="text/css">
</head>

<body>
<table align="center" cellpadding="15" cellspacing="0" >
  <tr>
    <td width="178" height="50" class="bt"><font size="3"><?php echo $cfg["wsite_vs"]; ?></font></td>
  </tr>
  <tr>
    <td><ul>
      <li><span class="title">What is it</span><br>
          ChatBot is a fast chat robot/automatic autoresponder. <br>
          ChatBot Supports large amount of questions/answers.<br>
          ChatBot stores all data in single csv file (no database needed) and has easy questions/answers admin interface.<br>
          Needs PHP v5.*<br>        
          <br>
      </li>
      <li><span class="title">Demo</span><br>    
          <a href="bot.php">Bot</a>&nbsp;&nbsp; <a href="admin.php">Admin</a> (user:
          admin&nbsp; pass:
          admin)</li>
      </ul>
    </td>
  </tr>
  <tr>
    <td height="50" class="top">contact: <?php echo $cfg["wsite_ct"]; ?></td>
  </tr>
</table>
</body>
</html>
