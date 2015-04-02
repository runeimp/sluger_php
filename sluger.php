<?php
/**
 * PHP sluger
 * String slugification app
 *
 * ChangeLog:
 *	2014-08-20	1.1.0	Updated the straight-single-quote and collon stripping with straight-double-quote and ‘smart’ quotes and turned functionalized the code.
 *	2014-08-15	1.0.0	Initial Script creation.
 */
$pageDescription = 'This is a PHP tool for slugifying strings.';
$baseURL = '';
$requestURL = $_SERVER['REQUEST_URI'];

define('APP_NAME', 'PHP Sluger');
define('APP_VERSION', '1.1.0');
define('APP_TITLE', APP_NAME.' v'.APP_VERSION);
define('APP_TAGLINE', 'Text Slugifier');


// Input/Output Options //
$slug_src = empty($_POST['slug_src']) ? "An Example's ALL for you!" : $_POST['slug_src'];
// exit('<pre>$_POST: '.print_r($_POST, true).'</pre>');

$subject = characterNormalize($slug_src);

$slug_strict = slugIt($subject);

$slug_compact = characterStrip($subject);
$slug_compact = slugIt($slug_compact);
$slug_compact = slugCompact($slug_compact);

function characterNormalize($subject)
{
	$subject = strtolower($subject);
	return str_replace('&', 'and', $subject);
}

function characterStrip($subject, $characters='\'"‘’“”:')
{
	$characters = '/['.preg_quote($characters).']/';
	return preg_replace($characters, '', $subject);
}

function slugCompact($slug)
{
	$slug = preg_replace('/--+/', '-', $slug);
	return trim($slug, '-');
}

function slugIt($subject)
{
	return preg_replace('/[^a-z0-9_-]/', '-', $subject);
}

function formInputChecked($name, $key=null)
{
	global $codex;
	if($key == null){
		$result = !empty($_POST[$name]) ? ' checked="checked"' : '';
	}else{
		if($key == OUT_OUT){
			$result = $codex->out == $name ? ' checked="checked"' : '';
		}else{
			$result = !empty($_POST[$key]) && $_POST[$key] == $name ? ' checked="checked"' : '';
		}
	}
	return $result;
}


ob_start();

// XHTML 5 Template //
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en-US" dir="ltr">
	<head>
		<meta http-equiv="window-target" content="_top" /><!-- Jumps out of frames -->
		<meta http-equiv="imagetoolbar" content="no" /><!-- Kill the IE 6 Image Toolbar -->
		<meta http-equiv="X-UA-Compatible" content="IE=IE8" /><!-- Set IE Mode: IE5 (Quirks Mode), EmulateIE7, IE7 (Force Standards Mode), EmulateIE8, IE8 (Force Standards Mode), Edge (Highest Mode Available) -->
		<meta charset="UTF-8" />
		<meta name="viewport" content="initial-scale=.3, minimum-scale=.1, maximum-scale=1" /><!-- for iPhone & Android -->
		<meta name="description" content="{SITE_DESCRIPTION}" />
		<meta name="generator" content="{APP_TITLE}" />
		<title>{APP_NAME}</title>
		<link rel="stylesheet" type="text/css" href="assets/css/base.css" />
		<link rel="stylesheet" type="text/css" href="assets/css/sluger.css" media="all" />
	</head>
	<body>
		<header>
			<h1>{APP_TITLE}</h1>
			<h2>{APP_TAGLINE}</h2>
		</header>
		<main id="main_content" role="main">
			<!-- Main Content -->
			<form method="post">
				<label>Slug Source:
					<input type="text" name="slug_src" value="<?php echo $slug_src ?>" />
				</label>
				<hr />
				<label>Slug Strict Output:
					<input type="text" name="slug_strict" value="<?php echo $slug_strict ?>" disabled />
				</label><br />
				<label>Slug Compact Output:
					<input type="text" name="slug_compact" value="<?php echo $slug_compact ?>" disabled />
				</label>
				<fieldset id="commands">
					<input type="submit" name="cmd" />
				</fieldset>
			</form>
		</main>
		<hr />
		<footer id="page-footer">
			<p><small>My awesome footer!</small></p>
		</footer><!-- #page-footer -->
	</body>
</html>
<?php

$output = ob_get_clean();

$output = str_replace('{BASE_URL}', $baseURL, $output);
$output = str_replace('{REQUEST_URL}', $requestURL, $output);
$output = str_replace('{APP_NAME}', APP_NAME, $output);
$output = str_replace('{APP_TITLE}', APP_TITLE, $output);
$output = str_replace('{APP_TAGLINE}', APP_TAGLINE, $output);
$output = str_replace('{SITE_DESCRIPTION}', $pageDescription, $output);

// header('Content-Type: text/xml');
echo $output;
?>