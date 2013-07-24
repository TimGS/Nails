<?php
/************************/
/* Process contact form */
/************************/

/* Validation */

if (empty($_POST['email1']))
	{
	$errmsg['email'] = 'Email address required';
	}
else
	{
	$emailValidator = new EmailAddressValidator();
	if (!$emailValidator->check_email_address(Sanitise::reverse_magic_quotes($_POST['email1']))) $errmsg['email'] = 'Email address is invalid';
	}
	
if ($_POST['email1'] != $_POST['email2']) $errmsg['email'] = 'Email and Confirm email do not match';
if (empty($_POST['enquiry_text'])) $errmsg['enquiry'] = 'Enquiry message required';

/* crude anti-spam filtering */
$not_allowed = array('viagra', 'sex', 'porn', 'seo');
foreach($not_allowed as $needle) if (preg_match('/\b'.$needle.'\b/i', $_POST['enquiry_text'])) $errmsg['enquiry'] = $needle.' blocked from enquiry by anti-spam filter.';

/* captcha */
require_once('captcha/recaptchalib.php');
$resp = recaptcha_check_answer (NAILS_CAPTCHA_PRIVATE, $_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
if (!$resp->is_valid)
	{
	$errmsg['recaptcha'] = 'You made an error typing in the Captcha text. Please try again';
	}

if (isset($errmsg))
	{
	/* Error in supplied data - Display form */
	$_SESSION['errmsg'] = $errmsg;
	$_SESSION['contact_output'] = '';
	header('Location: contact-us?fname='.rawurlencode(Sanitise::reverse_magic_quotes($_POST['fname'])).'&tel='.rawurlencode(Sanitise::reverse_magic_quotes($_POST['tel'])).'&email1='.rawurlencode(Sanitise::reverse_magic_quotes($_POST['email1'])).'&email2='.rawurlencode(Sanitise::reverse_magic_quotes($_POST['email2'])).'&enquiry_text='.rawurlencode(Sanitise::reverse_magic_quotes($_POST['enquiry_text'])));
	}
else 
	{
	/* Data correct - attempt to send */
	$output = '';
	$output .= '<p>Thank you '.Sanitise::html_gpc($_POST['fname']).'.</p>';
	if (mail(NAILS_SITE_EMAIL, "Form from {$_SERVER['SERVER_NAME']}",
				'<html><body>
				<strong>Name:</strong> '.Sanitise::html_gpc($_POST['fname']).'<br /><strong>Tel:</strong> '.Sanitise::html_gpc($_POST['tel']).'<br />
				<strong>Email:</strong> '.Sanitise::html_gpc($_POST['email1']).'<br /><br />
				<strong>Enquiry:</strong><br />'.nl2br(Sanitise::html_gpc($_POST['enquiry_text'])).'<br /><br /><strong>Domain (remote_addr):</strong> '.$_SERVER['REMOTE_ADDR'].'<br /></body></html>',
				'Content-Type: text/html; charset='.NAILS_CHARSET))
		{
		$output .= '<p class="success">Message sent</p>';
		}
	else
		{
		$output .= '<p class="error">Error in sending message - please try again'; // or email <a href="mailto:info@web.nutclough.co.uk">info@web.nutclough.co.uk</a><p>';
		}

	$_SESSION['contact_output'] = $output;
	$_SESSION['errmsg'] = array();
	header('Location: contact-us');
	}

