<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=<?php echo NAILS_CHARSET; ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title><?php echo NAILS_SITE_NAME; ?> | <?php echo Sanitise::html($nails['page_title']); ?></title>

	<base href="<?php echo $nails['base']; ?>" />

	<link rel="stylesheet" type="text/css" media="screen" href="style/default/css/style.css" />
	
	<!--[if lt IE 9]>
		 <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		 <style type="text/css">
		 	#container { min-width: 931px; }
		 </style>
	<![endif]-->

	<script type="text/javascript" src="style/default/js/style.js"></script>

</head>
<body>
	<?php echo User::controls(); ?>
	<div id="container">
		<div id="page-container">
			<header>
				<h1><?php echo NAILS_SITE_NAME; ?></h1>
			</header>
			<nav>
				<?php echo $menu; ?>
			</nav>
			<section id="page">
				<?php
				if (isset($pv_img) && is_file('media/images/'.$pv_img))
					{
					echo '<img id="main-image" src="phpThumb/phpThumb.php?src=../media/images/'.$pv_img.'&amp;w=710&amp;h=350&amp;zc=1" alt="'.@$pv_alt.'" />';
					}
				
				if (isset($nails['page_html']))
					{
					echo '<article>'.$nails['page_html'].'</article>';
					}
				?>
			</section>
			<footer></footer>
		</div>
	</div>
</body>
</html>
