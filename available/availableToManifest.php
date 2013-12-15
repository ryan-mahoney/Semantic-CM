<?php
echo 'Called', "\n";
exit;;
$manifest = ['managers' => []];
foreach (glob("*.php") as $filename) {
    echo $filename, "\n";
	if ($filename == basename(__FILE__)) {
		continue;
	}
    $lines = file($filename);
    foreach ($lines as $line) {
    	if (substr_count($line, ' * @') != 1) {
    		continue;
    	}
    	if (substr_count($line, '* @version') == 1) {
    		$manifest['managers'][basename($filename, '.php')] = trim(str_replace('* @version', '', $line));
    	}
    }
}
print_r($manifest);
file_put_contents('manifest.json', json_encode($manifest, JSON_PRETTY_PRINT));