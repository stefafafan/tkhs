<?php
/**
 * PHP version 8.3
 *
 * This file returns a console application for phpstan analysis.
 *
 * @category Test
 * @package  App\Tkhs
 * @author   stefafafan <github.le5ke@stenyan.jp>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/stefafafan/tkhs
 */
declare(strict_types=1);

use Symfony\Component\Console\Application;
use App\Tkhs\GenerateHtmlCommand;
use App\Tkhs\GeneratePdfCommand;

require __DIR__ . '/../vendor/autoload.php';

$app = new Application();
$app->add(new GenerateHtmlCommand());
$app->add(new GeneratePdfCommand());

return $app;
