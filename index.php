<?php
session_start();

require('site/config.inc.php');

// Classes - lazy load
function __autoload($name)
	{
	require_once('includes/'.$name.'.inc.php');
	}

// $nails array
$nails = array(
	'base' => /* for <base> tag */ ((empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off') ? 'http://' : 'https://').$_SERVER['SERVER_NAME'].preg_replace('/\/[^\/]+$/', '/', preg_replace('/^'.preg_quote($_SERVER['DOCUMENT_ROOT'], '/').'/', '', $_SERVER['SCRIPT_FILENAME'])),
	'request' => strtolower(get_magic_quotes_gpc() ? stripslashes(@$_REQUEST['q']) : @$_REQUEST['q']),
	'page_title' => '',
	'page_filename' => '',
	'page_alias' => '',
	'page_html' => ''
	);

if (strpos($nails['request'], '..') !== false) exit();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['page_filename']) && is_file('process/'.$nails['request'].'.php'))
	{
	$page = file_get_contents('process/'.$nails['request'].'.php');
	require('process/'.$nails['request'].'.php');
	}
else
	{
	$menu = new Menu(NAILS_DIR_PAGES);	

	// Page not found?
	if (!$nails['page_filename'])
		{
		$nails['page_filename'] = NAILS_NOTFOUND_FILE;
		$nails['page_title'] = 'Page not found';
		header('HTTP/1.1 404 Not Found');
		}

	// Get page html
	// Pages can also set 
	//		$pv_* variables
	//		$template to override DEFAULT_TEMPLATE
	$page = file_get_contents('pages/'.$nails['page_filename']);
	$writable = is_writable('pages/'.$nails['page_filename']);

	// Update page
	if ($writable && isset($_POST['page_filename']) && isset($_POST['content']))
		{
		$page_file = file_get_contents('pages/'.$nails['page_filename']);
		file_put_contents('pages/'.$nails['page_filename'], preg_replace(NAILS_EDITOR_CE_REGEX, "$1\n\n".$_POST['content']."\n\n$3", $page_file, 1), LOCK_EX);
		}

	// Run page
	ob_start();
	require('pages/'.$nails['page_filename']);
	$nails['page_html'] = ob_get_clean();

	// Editor
	if ($writable && User::isLogged() && preg_match(NAILS_EDITOR_CE_REGEX, $nails['page_html'], $matches))
		{
		$nails['page_html'] .=
			'<form action="'.($nails['request'] ? $nails['request'] : NAILS_ALIAS_HOME).'" method="post">
				<fieldset>
					<label for="content">Edit Content</label>
					<textarea id="content" name="content">'.$matches[2].'</textarea>
				<fieldset>
					<input type="hidden" value="'.Sanitise::html($nails['page_filename']).'" name="page_filename" />
					<input type="submit" value="Update" />
				</fieldset>
			</form>

			<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
			<script type="text/javascript">
			bkLib.onDomLoaded(function()
				{
				var myNicEditor = new nicEditor({buttonList : '.NAILS_EDITOR_NIC_BUTTONLIST.'});
				myNicEditor.panelInstance("content");
				});
			</script>';
		}
	
	$nails['page_html'] = str_replace('contenteditable="true"', 'contenteditable="false"', $nails['page_html']);

	// Charset via HTTP. Not strictly essential if meta tag present, but belt and braces.
	header('Content-Type:text/html; charset='.NAILS_CHARSET);

	// Page template
	// Available variables/constants:
	// 		Anything defined in site/config.inc.php
	//		$nails array
	//		$menu - <li>s for nav. <ul>s or <ol>s not included in first level so further fixed items may be added
	// 		Any $pv_* set by the page file
	// 		
	require('site/templates/'.(isset($template) ? $template : NAILS_DEFAULT_TEMPLATE).'.inc.php');
	}

