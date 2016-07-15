<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>myapp based on AOP</title>
</head>
<body>
<pre>
<?php
use App\App\OOUser;
use App\App\AOPUser;
use Go\Instrument\Transformer\MagicConstantTransformer;

// autoload
$isAOPDisabled = false;
include __DIR__ . ($isAOPDisabled ? '/autoload.php' : '/autoload_aspect.php');

// OO style
$user = new OOUser();
$user->add(array('password' => '*'));

echo "<hr>";

// AOP style
$user = new AOPUser();
$user->add(array('password' => '****'));
?>
</pre>

</body>
</html>