<?php
	define('MAINDIR', '../fonts/');
	header("Content-type: text/css; charset: UTF-8");
	$font_dirs = scandir(MAINDIR);
	$response = array();
	foreach ($font_dirs as $key => $font_dir) {
		if($font_dir != '.' && $font_dir != '..'){
			$variable = glob(MAINDIR.$font_dir."/*.ttf");
			foreach ($variable as $key => $value) {
				$dirn = explode(MAINDIR, $value)[1];
				$name = explode("/", explode(".ttf", $dirn)[0]);
				$name = end($name);
				?>
					@font-face {
					    font-family: "<?= $name ?>";
					    src: url("<?= $value ?>");
					}
				<?php
			}
		}
	}
?>
body{
	background: black;
}