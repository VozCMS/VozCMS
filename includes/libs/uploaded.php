<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

$dir = '../../content/uploads/';

echo '[';
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
    	$first = true;
        while (($file = readdir($dh)) !== false) {
        	//var_dump( strpos($file, 'thumb_') );
        	if(filetype($dir . $file) == 'file' && strpos($file, 'thumb_') !== 0){
        		echo $first ? '{"thumb" : "'.$dir.'thumb_'. $file.'", "image": "'.$dir . $file.'"}' : ', {"thumb" : "'.$dir .'thumb_'. $file.'", "image": "'.$dir . $file.'"}';
        		$first = false;
        	}
        }
        closedir($dh);
    }
}
echo ']';
?>