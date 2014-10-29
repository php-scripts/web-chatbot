<?php
	include("config.php");
?>
<html>
<head>
<title><?php echo $cfg["wsite_vs"]; ?></title>
<meta http-equiv="Content-Type" content="text/html;  charset=<?php echo $cfg["encoding"] ?>">
<link href="bot.css" rel="stylesheet" type="text/css">
</head>

<body onload="document.form1.ask.focus();">

<div align="center">
  <form name="form1" method="post" action="bot.php">
  <table cellpadding="5" cellspacing="1">
    <tr>
    <td width="178" height="50" class="bt"><font size="3"><?php echo $cfg["wsite_vs"]; ?></font></td>
  </tr>
    <tr>
      <td height="98%" class="scr">
	  <div class="log" style="<?php echo (($cfg["scrolling"] == true) ? "overflow: auto;height:100%;" : "") ?>">
	  	<?php
		
		if (isset($_POST["submit"]))
		{
			$ask  = strtolower(trim($_POST["ask"]));
			$a    = answer($ask);
			$out  =  "<span class=\"q\">Q: ".ucfirst($ask)."</span><br><span class=\"a\">A: ".$a."</span>";
			$tile = ($cfg["scrolling"]) ? @$_POST["tile"] : "";
			echo $out."<br>".$tile;
			echo "<input name='tile' type='hidden' id='tile' value='".$out."<br>".$tile."'>";
		}
	  
	  	// answers
		function answer($ask)
		{
			global $cfg;
			$ask  = (empty($ask)) ? "<empty>" : $ask;
			$kick = array("?","\n");
			$ask  = str_replace($kick,"",$ask);
			$fp   = fopen($cfg["answersfile"],"r");
			$brk  = false;
			
			while(!feof($fp) && $brk == false)
			{
				$ln = trim(fgets($fp,512));
				if($ln != "" && substr($ln,0,2) != "//")
				{
					@list($keyword,$answer) = @explode(";",$ln,2);
					$keyword = trim($keyword);
					
					if(stristr(" ".$ask." "," ".$keyword." "))
					{
						$reps   = @explode(";",$answer);
						$retval = $reps[mt_rand(0,count($reps)-2)];
						$brk    = true;
					}
				}
			}
			
			@fclose($fp);
			
			$retval = (empty($retval)) ? "I dont understand you. Please try again." : $retval;
			return ucfirst($retval);
		}
	  ?>
	  	</div>
	  
	  </td>
    </tr>
    <tr>
      <td class="top">        <input name="ask" type="text" class="inp" id="ask" style="width:84%" placeholder="Enter your question here" autocomplete="off" >
								<input name="sss" type="submit" style="width:15%" class="btn" id="sss" value="Ask our bot">
   </td>
    </tr>
    <tr>
      <td valign="top"><font face="Georgia, Times New Roman, Times, serif">
        <input name="submit" type="hidden" id="submit" value="yup">
</font></td>
    </tr>
  </table>
  </form>
</div>
</body>
</html>
