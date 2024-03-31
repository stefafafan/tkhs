<?php
declare(strict_types=1);

use Symfony\Component\Console\Application;
use App\Tkhs\GenerateHtmlCommand;
use App\Tkhs\GeneratePdfCommand;

require __DIR__ . '/../vendor/autoload.php';

$app = new Application();
$app->add(new GenerateHtmlCommand());
$app->add(new GeneratePdfCommand());

return $app;
