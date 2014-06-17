<?php
// Windows workaround to cache phpThumb files for 24hrs
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
require(dirname(__FILE__).'/phpThumb.php');

