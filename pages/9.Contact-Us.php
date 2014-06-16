<?php $pv_img = 'P1020136.jpg'; $pv_alt=''; /* Banner image and alt text */ ?>

<h2>Contact Us</h2>
<p>Νοβις ρεφορμιδανς αν μεα, εα μελ πωσθεα κυιδαμ σωνσθιτυθο. Σεα υθ φιερενθ νωμιναφι, εσε σανστυς ρεπρεχενδυντ μεα αδ.</p>

<?php
echo $_SESSION['contact_output'];
$errmsg = $_SESSION['errmsg'];
?>

<form id="enquiry" action="contact-us" method="post"> 
	<fieldset class="text">
		<label for="fname">Name:</label>
		<input id="fname" name="fname" type="text" value="<?php echo Sanitise::html_gpc($_REQUEST['fname']); ?>" size="30" />
	</fieldset>
	<fieldset class="text">	
		<label for="tel"><abbr title="telephone">Tel</abbr>  (optional):</label>
		<input class="numeric" id="tel" name="tel" type="text" value="<?php echo Sanitise::html_gpc($_REQUEST['tel']); ?>" size="30" />
	</fieldset>
	<fieldset class="text">	
		<label for="email1">Email:</label>
		<input id="email1" name="email1" type="text" value="<?php echo Sanitise::html_gpc($_REQUEST['email1']); ?>" size="30" />
		<?php if (isset($errmsg['email'])) echo '<p class="error">'.$errmsg['email'].'</p>'; ?>
	</fieldset>
	<fieldset class="text">	
		<label for="email2">Confirm email:</label>
		<input id="email2" name="email2" type="text" value="<?php echo Sanitise::html_gpc($_REQUEST['email2']); ?>" size="30" />
	</fieldset>
	<fieldset class="text">	
		<label for="enquiry_text">Enquiry:</label>
		<textarea id="enquiry_text" name="enquiry_text" rows="5" cols="60"><?php echo Sanitise::html_gpc($_REQUEST['enquiry_text']); ?></textarea>
		<?php if (isset($errmsg['enquiry'])) echo '<p class="error">'.$errmsg['enquiry'].'</p>'; ?>
	</fieldset>
	<fieldset id="enquiry-captcha">
		<label class="recaptcha_only_if_image" for="recaptcha_response_field">Type in the <a href="http://www.captcha.net/">captcha</a> code as shown:</label>
		<label class="recaptcha_only_if_audio" for="recaptcha_response_field">Type in the <a href="http://www.captcha.net/">captcha</a> numbers that you hear:</label>
		<div id="recaptcha-div">
			<script type="text/javascript">var RecaptchaOptions = { theme: 'custom' };</script>
			<div id="recaptcha_image"></div>
			<div id="recaptcha-controls">
				<div><a href="javascript:Recaptcha.reload()"><img src="<?php echo NAILS_CAPTCHA_URL; ?>refresh_button.gif" alt="Get another CAPTCHA" /></a></div>
				<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')"><img src="<?php echo NAILS_CAPTCHA_URL; ?>audio_button.gif" alt="Get an audio CAPTCHA" /></a></div>
				<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')"><img src="<?php echo NAILS_CAPTCHA_URL; ?>image_button.gif" alt="Get an image CAPTCHA" /></a></div>
				<div><a href="javascript:Recaptcha.showhelp()"><img src="<?php echo NAILS_CAPTCHA_URL; ?>help_button.gif" alt="Help" /></a></div>
			</div>
			<div><input type="text" id="recaptcha_response_field" name="recaptcha_response_field" /></div>
		</div>
		<?php
		if ($errmsg['recaptcha']) echo '<p class="error">'.$errmsg['recaptcha'].'</p>';
		require_once('captcha/recaptchalib.php');
		// note that we select the key for nutclough.com or nutclough.co.uk
		echo recaptcha_get_html(NAILS_CAPTCHA_PUBLIC, $resp->error);
		?>
	</fieldset>
	<fieldset class="submit">
		<input class="button" type="submit" name="submit" id="submit" value="Send enquiry" />
	</fieldset>

</form> 


