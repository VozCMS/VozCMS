<?php
	header('Content-type: text/css');
	ob_start("compress");
	function compress($buffer) {
		/* remove comments */
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
		/* remove tabs, spaces, newlines, etc. */
		$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
		return $buffer;
	}

	/* your css files */
	$files = explode(',', $_GET['f']);
	foreach($files as $file){
		if(file_exists($file)){
			include($file);
		}
	}

	ob_end_flush();