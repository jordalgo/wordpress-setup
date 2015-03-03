<?php
/* PHP Form Mailer - phpFormMailer v2.2, last updated 23rd Jan 2008 - check back often for updates!
   (easy to use and more secure than many cgi form mailers) FREE from:
                  www.TheDemoSite.co.uk
      Should work fine on most Unix/Linux platforms
      for a Windows version see: asp.thedemosite.co.uk
*/

// ------- three variables you MUST change below  -------------------------------------------------------
$replyemail="academyofcreativeeducation@gmail.com";//change to your email address
$valid_ref1="http://www.academyofcreativeeducation.org";// chamge "Your--domain" to your domain
$valid_ref2="http://www.academyofcreativeeducation.org";// chamge "Your--domain" to your domain
// -------- No changes required below here -------------------------------------------------------------
// email variable not set - load $valid_ref1 page
if (!isset($_POST['email']))
{
 echo "<script language=\"JavaScript\"><!--\n ";
 echo "top.location.href = \"$valid_ref1\"; \n// --></script>";
 exit;
}

$ref_page= 'http://www.academyofcreativeeducation.org';
$valid_referrer=0;
if($ref_page==$valid_ref1) $valid_referrer=1;
elseif($ref_page==$valid_ref2) $valid_referrer=1;
if(!$valid_referrer)
{
 echo($ref_page);
 echo "<script language=\"JavaScript\"><!--\n alert(\"ERROR - not sent.\\n\\nCheck your 'valid_ref1' and 'valid_ref2' are correct within contact_process.php.\");\n";
 echo "top.location.href = \"index.html\"; \n// --></script>";
 exit;
}

//check user input for possible header injection attempts!
function is_forbidden($str,$check_all_patterns = true)
{
 $patterns[0] = 'content-type:';
 $patterns[1] = 'mime-version';
 $patterns[2] = 'multipart/mixed';
 $patterns[3] = 'Content-Transfer-Encoding';
 $patterns[4] = 'to:';
 $patterns[5] = 'cc:';
 $patterns[6] = 'bcc:';
 $forbidden = 0;
 for ($i=0; $i<count($patterns); $i++)
  {
   $forbidden = eregi($patterns[$i], strtolower($str));
   if ($forbidden) break;
  }
 //check for line breaks if checking all patterns
 if ($check_all_patterns AND !$forbidden) $forbidden = preg_match("/(%0a|%0d|\\n+|\\r+)/i", $str);
 if ($forbidden)
 {
  echo "<font color=red><center><h3>STOP! Message not sent.</font></h3><br><b>
        The text you entered is forbidden, it includes one or more of the following:
        <br><textarea rows=9 cols=25>";
  foreach ($patterns as $key => $value) echo $value."\n";
  echo "\\n\n\\r</textarea><br>Click back on your browser, remove the above characters and try again.
        </b><br><br><br><br>Thankfully protected by phpFormMailer freely available from:
        <a href=\"http://thedemosite.co.uk/phpformmailer/\">http://thedemosite.co.uk/phpformmailer/</a>";
  exit();
 }
 else return $str;
}

$name = is_forbidden($_POST["name"]);
$email = is_forbidden($_POST["email"]);
$phone = is_forbidden($_POST["phone"]);
$themessage = is_forbidden($_POST["themessage"], false);
$headers = 'From: Academy of Creative Education <info@academyofcreativeeducation.org>' . "\r\n" .
    'Reply-To: info@academyofcreativeeducation.org' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

$success_sent_msg='

<link rel="stylesheet" href="css/main.css" type="text/css" />

	<body id="body">

	<div id="navContainer">
    
    	<div id="navBar">
        <div id="navContent" class="floatcontainer">
        	<div id="navItems">
            <a href="index.html#mw" title="About Me" class="aboutme">about me</a> / <a href="index.html#fw" title="My Work" class="mywork">my work</a> / <a href="index.html#cm" title="Contact Me">contact</a>
            </div>
            
        	<div id="logo">
            <a href="index.html#body" class="backtotop">Home</a>
            </div>
        </div>
        </div>
    
    </div>
    
    <div id="contentContainer">
    
    	<div id="tag">
        Thank you for your message. I will try to get back to you as soon as I can.
        </div>
        
        
   	</div>
	
</body>';

$replymessage = "Hi $name

Thank you for your message. I will read it and get back to you as soon as possible.

Please DO NOT reply to this email.

Below is a copy of the message you submitted:
--------------------------------------------------
Your Message: $themessage
--------------------------------------------------

Thank you";

$themessage = "name: $name \n Message: $themessage";
mail("$replyemail",
	 "Academy of Creative Education Message", 
     "$themessage\n\nFrom: $email\nPhone: $phone\nReply-To: $email", $headers);
mail("$email",
     "academyofcreativeeducation.org Response",
     "$replymessage From: $replyemail\nReply-To: $replyemail", $headers);
//echo $success_sent_msg;

$value = 'Message Confirmation';

//setcookie("MessageConfirm", $value, time()+100);  /* expire quickly */
header("Location: http://academyofcreativeeducation.org/?page_id=178");

/*
  PHP Form Mailer - phpFormMailer (easy to use and more secure than many cgi form mailers)
   FREE from:

    www.TheDemoSite.co.uk       */
?>
