<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;

include("generate-html.php");
include("generate-pdf.php");

$app = new Application();
$app->add(new GenerateHtmlCommand());
$app->add(new GeneratePdfCommand());
$app->run();
