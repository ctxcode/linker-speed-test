<?php

if(count($argv) < 3){
	die("Missing command arguments. php gen.php {object_count} {func_count}\n");
}

$ob_c = intval($argv[1]);
$func_c = intval($argv[2]);

$dir = __DIR__ . '/gen';
if(!file_exists($dir))
	mkdir($dir);

$ob_ci = 0;
$ofiles = [];
while($ob_ci < $ob_c){
	$ob_ci++;
	$func_ci = 0;
	$content = "";
	while($func_ci < $func_c){
		$func_ci++;
		$fn = 'func_'.$ob_ci . '_'.$func_ci;
		$content .= "\nint $fn(int x) {\n";
		$content .= "  return x + $func_ci;\n";
		$content .= "}\n";
	}
	$cpath = $dir . '/ob_'.$ob_ci . '.c';
	$opath = $dir . '/ob_'.$ob_ci . '.o';
	file_put_contents($cpath, $content);
	exec("cc -O0 -c $cpath -o $opath");
	$ofiles[] = $opath;
}

// Main
$cmain = $dir . '/main.c';
$omain = $dir . '/main.o';
$ofiles[] = $omain;
$content = "";
$ob_ci = 0;
while($ob_ci < $ob_c){
	$ob_ci++;
	$func_ci = 0;
	while($func_ci < $func_c){
		$func_ci++;
		$fn = 'func_'.$ob_ci . '_'.$func_ci;
		$content .= "int $fn(int x);\n"; 
	}
}
$content .= "\n\n int main() {\n";
$ob_ci = 0;
while($ob_ci < $ob_c){
	$ob_ci++;
	$func_ci = 0;
	while($func_ci < $func_c){
		$func_ci++;
		$fn = 'func_'.$ob_ci . '_'.$func_ci;
		$content .= "$fn(0);\n"; 
	}
}
$content .= "}\n";
file_put_contents($cmain, $content);
exec("cc -O0 -c $cmain -o $omain");

// Link
echo "# Link\n";
$out = [];

$start = microtime(true);
$cmd = "cc -O0 ".(implode(" ", $ofiles))." -o $dir/out";
echo "# cmd: $cmd\n\n";
system($cmd);

echo "Time: " . (microtime(true) - $start) .  "ms\n";

echo "\n# Done\n";

