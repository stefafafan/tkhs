<?php

declare(strict_types=1);

use App\Tkhs\GenerateHtmlCommand;
use App\Tkhs\GeneratePdfCommand;
use Symfony\Component\Console\Application;

require __DIR__.'/../vendor/autoload.php';

$app = new Application();
$app->add(new GenerateHtmlCommand());
$app->add(new GeneratePdfCommand());

return $app;
