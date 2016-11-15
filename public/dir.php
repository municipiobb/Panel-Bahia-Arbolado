<?php

$dir = dirname(__FILE__).'/uploads';
echo $dir;
// Abre un directorio conocido, y procede a leer el contenido
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
        	if(is_file($dir.'/'.$file)){
            	echo "<br>nombre archivo: $file : tipo archivo: " . filetype($dir . $file) . "\n";
        	
            //unlink($dir.'/'.$file);
        }
        }
        closedir($dh);
    }
}