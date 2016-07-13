<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RSSHelper API</title>
</head>
<body>
<pre>
<?php
use Parser\Parser\HumanDemo;
use Go\Instrument\Transformer\MagicConstantTransformer;

$isAOPDisabled = false;
include __DIR__ . ($isAOPDisabled ? '/autoload.php' : '/autoload_aspect.php');

$example    = null;

$example = new HumanDemo();
echo "Want to eat something, let's have a breakfast!", PHP_EOL;
$example->eat();
echo "I should work to earn some money", PHP_EOL;
$example->work();
echo "It was a nice day, go to bed", PHP_EOL;
$example->sleep();
?>
</pre>

</body>
</html>