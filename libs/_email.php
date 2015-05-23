<?php
/*
+--------------------------------------------+
|   Email Sender Module - Ver 1.0
|--------------------------------------------|   
|   by NDK (kesitinh265@yahoo.com)
+--------------------------------------------+ 
*/

class emailer {

	var $from         = "";
	var $to           = "";
	var $subject      = "";
	var $message      = "";
	var $header       = "";
	var $footer       = "";
	var $template     = "";
	var $error        = "";
	var $parts        = array();
	var $bcc          = array();
	var $mail_headers = "";
	var $multipart    = "";
	var $boundry      = "";
	var $err		  = "";
	
	var $html_email   = 1;
	var $char_set     = 'UTF-8';
	
	var $smtp_fp      = FALSE;
	var $smtp_msg     = "";
	var $smtp_port    = "";
	var $smtp_host    = "localhost";
	var $smtp_user    = "";
	var $smtp_pass    = "";
	var $smtp_code    = "";
	
	var $wrap_brackets = 0;
	
	var $mail_method  = 'mail';
	
	var $temp_dump    = 0;
	var $root_path    = './';
	
	
	function emailer()
	{
		global $conf;
		
		$this->header  = $conf['email_header'];
		$this->footer  = $conf['email_footer'];
		
	}
	
	//-------- ADD ATTACHMENT
	
	function add_attachment($data = "", $name = "", $ctype='application/octet-stream')
	{
	
		$this->parts[] = array( 'ctype'  => $ctype,
								'data'   => $data,
								'encode' => 'base64',
								'name'   => $name
							  );
	}
	
		//---------- BUILD HEADERS
	
	function build_headers()
	{
		global $conf;
		
		//---------- HTML (hitmuhl)
		
		if ( $this->html_email )
		{
			$this->mail_headers .= "MIME-Version: 1.0\n";
			$this->mail_headers .= "Content-type: text/html; charset=\"".$this->char_set."\"\n";
		}
		
		//--------- Start mail headers
		
		$this->mail_headers  .= "From: \"".$conf['webname']."\" <".$this->from.">\n";
		
		if ( count( $this->bcc ) > 1 )	
		{
			$this->mail_headers .= "Bcc: ".implode( "," , $this->bcc ) . "\n";
		}
	
		$this->mail_headers .= "Return-Path: ".$this->from."\n";
		$this->mail_headers .= "X-Priority: 3\n";
		$this->mail_headers .= "X-Mailer: NDK PHP Mailer\n";
		
	}
	
	//---------- ENCODE ATTACHMENT
	
	function encode_attachment($part)
	{
		
		$msg = chunk_split(base64_encode($part['data']));
		
		return "Content-Type: ".$part['ctype']. ($part['name'] ? ";\n\tname =\"".$part['name']."\"" : "").
			  "\nContent-Transfer-Encoding: ".$part['encode']."\nContent-Disposition: attachment;\n\tfilename=\"".$part['name']."\"\n\n".$msg."\n";
		
	}
	
	
	/*-------------------------------------------------------------------------*/
	// send_mail:
	// Physically sends the email
	/*-------------------------------------------------------------------------*/
	
	function send_mail()
	{
		global $conf;
		
		$this->to   = preg_replace( "/[ \t]+/" , ""  , $this->to   );
		$this->from = preg_replace( "/[ \t]+/" , ""  , $this->from );
		
		$this->to   = preg_replace( "/,,/"     , ","  , $this->to );
		$this->from = preg_replace( "/,,/"     , ","  , $this->from );
		
		$this->to     = preg_replace( "#\#\[\]'\"\(\):;/\$!£%\^&\*\{\}#" , "", $this->to  );
		$this->from   = preg_replace( "#\#\[\]'\"\(\):;/\$!£%\^&\*\{\}#" , "", $this->from);
		
		$this->subject = ( trim($this->lang_subject) != "" ) ? $this->lang_subject : $this->subject;
		
		$this->subject = $this->clean_message($this->subject);
		
		
		$this->build_headers();
		
		if ( ($this->from) and ($this->subject) )
		{
			if ( ! @mail( $this->to, $this->subject, $this->message, $this->mail_headers ) )
			{
				$this->err="H&#7879; th&#7889;ng g&#7903;i email &#273;ang g&#7863;p s&#7921; c&#7889; , vui l&#242;ng th&#7917; l&#7841;i sau .";
			}
			else 
			{
				$this->err="Email &#273;&#227; &#273;&#432;&#7907;c g&#7903;i &#273;i th&#224;nh c&#244;ng";
			}

		}
		else
		{
			$this->err="Thi&#7871;u th&#244;ng tin .";
		}
		
		$this->to           = "";
		$this->from         = "";
		$this->message      = "";
		$this->subject      = "";
		$this->mail_headers = "";
		
		return $this->err;
	}
	
	function clean_message($message = "" ) {
	
		$message = preg_replace( "/^(\r|\n)+?(.*)$/", "\\2", $message );
	
		$message = preg_replace( "#<b>(.+?)</b>#" , "\\1", $message );
		$message = preg_replace( "#<i>(.+?)</i>#" , "\\1", $message );
		$message = preg_replace( "#<s>(.+?)</s>#" , "--\\1--", $message );
		$message = preg_replace( "#<u>(.+?)</u>#" , "-\\1-"  , $message );
		$message = preg_replace( "#<img src=[\"'](\S+?)['\"].+?".">#"                                  , "(IMAGE: \\1)"   , $message );
		$message = preg_replace( "#<a href=[\"'](http|https|ftp|news)://(\S+?)['\"].+?".">(.+?)</a>#"  , "\\1://\\2"     , $message );
		$message = preg_replace( "#<a href=[\"']mailto:(.+?)['\"]>(.+?)</a>#"                       , "(EMAIL: \\2)"   , $message );
		
		$message = preg_replace( "#<!--sql-->(.+?)<!--sql1-->(.+?)<!--sql2-->(.+?)<!--sql3-->#i"    , "\n\n--------------- SQL -----------\n\\2\n----------------\n\n", $message);
		$message = preg_replace( "#<!--html-->(.+?)<!--html1-->(.+?)<!--html2-->(.+?)<!--html3-->#i", "\n\n-------------- HTML -----------\n\\2\n----------------\n\n", $message);
		
		
		//-----------------------------------------
		// Bear with me...
		//-----------------------------------------
		
		$message = str_replace( "\n"          , "<br />", $message );
		$message = str_replace( "\r"          , ""      , $message );
		
		$message = str_replace( "<br>"        , "\r\n", $message );
		$message = str_replace( "<br />"      , "\r\n", $message );
		$message = preg_replace( "#<.+?".">#" , ""    , $message );
		
		$message = str_replace( "&quot;", "\"", $message );
		$message = str_replace( "&#092;", "\\", $message );
		$message = str_replace( "&#036;", "\$", $message );
		$message = str_replace( "&#33;" , "!" , $message );
		$message = str_replace( "&#39;" , "'" , $message );
		$message = str_replace( "&lt;"  , "<" , $message );
		$message = str_replace( "&gt;"  , ">" , $message );
		$message = str_replace( "&#124;", '|' , $message );
		$message = str_replace( "&amp;" , "&" , $message );
		$message = str_replace( "&#58;" , ":" , $message );
		$message = str_replace( "&#91;" , "[" , $message );
		$message = str_replace( "&#93;" , "]" , $message );
		$message = str_replace( "&#064;", '@' , $message );
		$message = str_replace( "&#60;" , '<' , $message );
		$message = str_replace( "&#62;" , '>' , $message );
		$message = str_replace( "&nbsp;" , ' ' , $message );
		
		return $message;
	}

}

?>