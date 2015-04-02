<?php
/**
 * PHP sluger
 * String slugification app
 *
 * ChangeLog:
 *	2014-09-24	1.3.0	Added characters with diacrilics normalization.
 *	2014-09-17	1.2.1	Changed disabled input attributes to readonly. Updated footer, and page styling a bit.
 *	2014-08-25	1.2.0	Added source uppercase and lowercase output.
 *	2014-08-20	1.1.0	Updated the straight-single-quote and collon stripping with straight-double-quote and ‘smart’ quotes and turned functionalized the code.
 *	2014-08-15	1.0.0	Initial Script creation.
 */
$pageDescription = 'This is a PHP tool for slugifying strings.';
$baseURL = '';
$requestURL = $_SERVER['REQUEST_URI'];

define('APP_NAME', 'PHP Sluger');
define('APP_VERSION', '1.3.0');
define('APP_TITLE', APP_NAME.' v'.APP_VERSION);
define('APP_TAGLINE', 'Text Slugifier');


// Input/Output Options //
$slug_src = empty($_POST['slug_src']) ? "An Example's ALL for you!" : $_POST['slug_src'];
// exit('<pre>$_POST: '.print_r($_POST, true).'</pre>');

$source = characterNormalize($slug_src);

$source_uppercase = strtoupper($source);
$source_lowercase = strtolower($source);

$slug_strict = slugIt($source);

$slug_compact = characterStrip($source);
$slug_compact = slugIt($slug_compact);
$slug_compact = slugCompact($slug_compact);

$id_strict = str_replace('-', '_', $slug_strict);
$id_compact = str_replace('-', '_', $slug_compact);

function characterNormalize($subject)
{
	$subject = normalize($subject);
	$subject = strtolower($subject);
	return str_replace('&', 'and', $subject);
}

function characterStrip($subject, $characters='\'"‘’“”:')
{
	$characters = '/['.preg_quote($characters).']/';
	return preg_replace($characters, '', $subject);
}

/**
 * @see http://php.net/manual/en/function.strtr.php#90925
 */
function normalize($string)
{
	$table = array(
		'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
		'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
		'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
		'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
		'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
		'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
		'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
		'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
	);

	return strtr($string, $table);
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
		<header id="page_header" class="page-header">
			<h1>{APP_TITLE}</h1>
			<h2>{APP_TAGLINE}</h2>
		</header>
		<main id="main_content" class="main-content" role="main">
			<!-- Main Content -->
			<form method="post">
				<label>Slug Source:
					<input type="text" name="slug_src" value="<?php echo $slug_src ?>" />
				</label>

				<hr>

				<fieldset id="commands">
					<input type="submit" name="cmd" value="Submit Source" />
				</fieldset>

				<hr />

				<label>Source UPPERCASE:
					<input type="text" name="source_uppercase" value="<?php echo $source_uppercase ?>" readonly />
				</label>
				<label>Source lowercase:
					<input type="text" name="source_lowercase" value="<?php echo $source_lowercase ?>" readonly />
				</label>

				<hr />

				<label>Slug Strict Output:
					<input type="text" name="slug_strict" value="<?php echo $slug_strict ?>" readonly />
				</label>
				<label>Slug Compact Output:
					<input type="text" name="slug_compact" value="<?php echo $slug_compact ?>" readonly />
				</label>

				<hr />
				
				<label>ID Strict:
					<input type="text" name="id_strict" value="<?php echo $id_strict ?>" readonly />
				</label>
				<label>ID Compact:
					<input type="text" name="id_compact" value="<?php echo $id_compact ?>" readonly />
				</label>
			</form>
		</main>
		<hr />
		<footer id="page_footer" class="page-footer">
			<p><small>String case converter, slug/HTML/CSS class generator, and HTML/CSS ID generator</small></p>
		</footer><!-- #page_footer -->
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