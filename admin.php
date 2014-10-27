<?php

include("config.php");

//Login
if ($cfg["user"] != @$_SERVER['PHP_AUTH_USER'] && @$_SERVER['PHP_AUTH_PW'] != $cfg["pass"])
{
	Header("WWW-authenticate: basic realm=\"Restricted area\"");
	Header("HTTP/1.0 401 Unauthorized");
	echo "<htm><head>".'<meta http-equiv="refresh" content="0; url='.$cfg["wsite_ul"].'/">'."</head></htm>";
	exit;
}

// logout
if (@$_REQUEST["logout"] == "now")
{
	header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
}

// deleting answer
if (!empty(@$_REQUEST["del"]))
{
	delstr(@$_REQUEST["del"], @$_REQUEST["wha"]);
	refr();
}

if (!empty($_REQUEST["adda"]))
{
	addask(trim(@$_REQUEST["newask"]));
	refr();
}

if (!empty($_REQUEST["addans"]))
{
	addanswer(trim(@$_REQUEST["newanswer"]), @$_REQUEST["askk"]);
	refr();
}
//file to string
function getfilestr($file)
{
	$fd = fopen ("$file", "r");
	$buffer = "";
	while (!feof ($fd)) 
	{
	  $buffer .= fgets($fd, 1024);
	}
	fclose ($fd);
	return $buffer;
}
//file to array
function getfilearr($file)
{
	$fd = fopen ("$file", "r");
	$ar = array();
	while (!feof ($fd)) 
	{
	  $buffer = trim(fgets($fd, 1024));
	  if($buffer != "")
	  {
		  $ar[] = $buffer;
	  }
	  $buffer = "";
	}
	fclose ($fd);
	return $ar;
}

function refr()
{
	header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
}

// deletes answer
function delstr($del, $wha)
{
	global $cfg;
	
	prepreg($del);
	//die($del);
	$fp = fopen($cfg["answersfile"],'a+');
	$file = getfilestr($cfg["answersfile"]);
	if ($wha == "ask")
		$file = preg_replace("/\n(.*?)$del;\n/mi","\n",$file);
	else
		$file = preg_replace("/;$del;/",";",$file);
	
	
	flock ($fp,2);
	ftruncate($fp,0);
	fwrite($fp,$file);
	flock($fp,3);
	
	fclose($fp);
}

// adds ask
function addask($ask)
{
	global $cfg;
	
	bust($ask);
	
	$fp = fopen($cfg["answersfile"],'a+');
		
	flock ($fp,2);
	fwrite($fp,"$ask;\n");
	flock($fp,3);
	
	fclose($fp);
	
}

// adds answer
function addanswer($answer, $askk)
{
	global $cfg;
	bust($answer);
	
	$fp = fopen($cfg["answersfile"],'a+');
	$file = getfilestr($cfg["answersfile"]);
	$file = preg_replace("/\n$askk(.*?);\n/mi","\n$askk$1;$answer;\n",$file);
	$file = stripslashes($file);
	flock ($fp,2);
	ftruncate($fp,0);
	fwrite($fp,$file);
	flock($fp,3);
	
	fclose($fp);
	
}

function bust($str)
{
	$str = preg_replace("/;/", ":", $str);
	$str = stripslashes(preg_replace("/\"/", "'", $str));
}

function prepreg($str)
{
	$str = preg_quote(stripslashes($str), "/");
}

?>
<html>
<head>
<title><?php echo $cfg["wsite_vs"]; ?></title>
<meta http-equiv="Content-Type" content="text/html;  charset=<?php echo $cfg["encoding"] ?>">
<link href="bot.css" rel="stylesheet" type="text/css">
</head>

<body>
<table cellpadding="0" cellspacing="0">
  <tr class="bt" >
    <td height="40" class="bt"><?php echo $cfg["wsite_vs"]; ?> : Admin</td>
  </tr>
  <tr class="bt" width="100%">
    <td><ul>
      <li>To delete question or answer just&nbsp; click on it</li>
      <li>To delete question, delete all it's answers first</li>
    </ul>
    </td>
  </tr>
  <tr class="bt">
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr class="bt">
    <td class="bt">Questions</td>
    <td width="39%" class="bt">Answers</td>
    <td width="39%" class="bt">&nbsp;</td>
  </tr>
  <?php
  	$f = getfilearr($cfg["answersfile"]);
  	$c = "";
	for ($i=1; $i<count($f); $i++)
	{
		$c = !$c;
		$col = explode(";", $f[$i]);
  ?>
  <tr class="<?php echo ($c) ? "on" : "" ?>">
    <td valign="top"><a href="?wha=ask&del=<?php echo @$col[0]; ?>"><?php echo $col[0] ?></a></td>
    <td valign="top">
      <?php
	// answers
	for ($ii=1; $ii<=count($col); $ii++)
	{
	?>
      <a href="?del=<?php echo str_replace("\"", "'",@$col[$ii]); ?>"><?php echo @$col[$ii]; ?></a>&nbsp; &nbsp;
    <?php } ?>
    </td>
    <td nowrap><form name="form2" method="post" action="">
    <input name="askk" type="hidden" value="<?php echo $col[0] ?>">
    <input name="newanswer" type="text" class="inp" id="newanswer">
      <input name="addans" type="submit" class="btn" id="addans" value="Add answer"></form></td>
  </tr>
  <?php
  	}
  ?>
  <tr>
    <td nowrap class="top"><form name="form1" method="post" action=""><input name="newask" type="text" class="inp" id="newask">
      <input name="adda" type="submit" class="btn" id="adda" value="Add question">
    </form></td>
    <td class="top">&nbsp;</td>
    <td class="top">&nbsp;</td>
  </tr>
</table>
</body>
</html>
