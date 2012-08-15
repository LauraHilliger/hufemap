<?php

require("../config.php");
require ('admin_common.php');

?>

<head>
<title>Email Account Test</title>
</head>
<?php
require_once ("../mail/email_message.php");
require_once ("../mail/smtp_message.php");
require_once ("../mail/smtp.php");
require_once ("../mail/pop3.php");
//print_r ($_REQUEST);

if ($_REQUEST['pop3_port']=='') {
		$_REQUEST['pop3_port']=110;
	}

	$pop3=new pop3_class;
	$pop3->hostname=$_REQUEST['email_pop_server'];      /* POP 3 server host name              */
	$pop3->port=$_REQUEST['pop3_port'];     /* POP 3 server host port              */
	$user=$_REQUEST['user'];                /* Authentication user name            */
	$password=$_REQUEST['pass'];            /* Authentication password             */
	$pop3->realm="";                        /* Authentication realm or domain      */
	$pop3->workstation="";                  /* Workstation for NTLM authentication */
	$apop=0;                                /* Use APOP authentication             */
	$pop3->authentication_mechanism="USER"; /* SASL authentication mechanism       */
	$pop3->debug=0;                         /* Output debug information            */
	$pop3->html_debug=0;                    /* Debug information is in HTML        */

	if(($error=$pop3->Open())=="")
	{
		echo "<PRE>Connected to the POP3 server &quot;".$pop3->hostname."&quot;.</PRE>\n";
		if(($error=$pop3->Login($user,$password,$apop))=="")
		{
			echo "<PRE>User &quot;$user&quot; logged in.</PRE>\n";
			if(($error=$pop3->Statistics($messages,$size))=="")
			{
				echo "<PRE>There are $messages messages in the mail box with a total of $size bytes.</PRE>\n";
				echo "<h3>Pop3 connection was successful.</h3>";
				/*/
				$result=$pop3->ListMessages("",0);
				if(GetType($result)=="array")
				{
					for(Reset($result),$message=0;$message<count($result);Next($result),$message++)
						echo "<PRE>Message ",Key($result)," - ",$result[Key($result)]," bytes.</PRE>\n";
					$result=$pop3->ListMessages("",1);
					if(GetType($result)=="array")
					{
						for(Reset($result),$message=0;$message<count($result);Next($result),$message++)
							echo "<PRE>Message ",Key($result),", Unique ID - \"",$result[Key($result)],"\"</PRE>\n";
						if($messages>0)
						{
							if(($error=$pop3->RetrieveMessage(1,$headers,$body,2))=="")
							{
								echo "<PRE>Message 1:\n---Message headers starts below---</PRE>\n";
								for($line=0;$line<count($headers);$line++)
									echo "<PRE>",HtmlSpecialChars($headers[$line]),"</PRE>\n";
								echo "<PRE>---Message headers ends above---\n---Message body starts below---</PRE>\n";
								for($line=0;$line<count($body);$line++)
									echo "<PRE>",HtmlSpecialChars($body[$line]),"</PRE>\n";
								echo "<PRE>---Message body ends above---</PRE>\n";
								
								if(($error=$pop3->DeleteMessage(1))=="")
								{
									echo "<PRE>Marked message 1 for deletion.</PRE>\n";
									if(($error=$pop3->ResetDeletedMessages())=="")
									{
										echo "<PRE>Resetted the list of messages to be deleted.</PRE>\n";
									}
								}
								
							}
						}
						if($error==""
						&& ($error=$pop3->Close())=="")
							echo "<PRE>Disconnected from the POP3 server &quot;".$pop3->hostname."&quot;.</PRE>\n";
						
					}
					else
						$error=$result;
				}
				else
					$error=$result;
			//[c) 200S jam1t softwar3
					*/
			}
			
		}
	}
	if($error!="")
		echo "<H2>Error: ",HtmlSpecialChars($error),"</H2>";
?>


<p>&nbsp;</p>
<input type="button" value="Close" onclick="window.close()">