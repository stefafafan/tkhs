<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class GenerateHtmlCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('generate:html')
            ->setDescription('Converts a text file with Japanese strings into presentable HTML files.')
            ->addArgument('filename', InputArgument::REQUIRED, 'The path to the text file.')
            ->addArgument('output', InputArgument::OPTIONAL, 'The directory to output the html files. If not specified, "output" will be used.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filename = $input->getArgument('filename');
        $filesystem = new Filesystem();

        if (!$filesystem->exists($filename)) {
            $output->writeln('<error>File does not exist.</error>');
            return Command::FAILURE;
        }

        $lines = file($filename, FILE_IGNORE_NEW_LINES);
        if ($lines === false) {
            $output->writeln('<error>Failed to read file.</error>');
            return Command::FAILURE;
        }

        $outputDir = $input->getArgument('output') ?? 'output';
        $filesystem->mkdir($outputDir);

        foreach ($lines as $index => $line) {
            $htmlContent = $this->generateHtmlContent($index, count($lines), $line);
            $htmlFilename = sprintf('%s/%s.html', $outputDir, $index + 1);
            file_put_contents($htmlFilename, $htmlContent);
            $output->writeln("Exported: $htmlFilename");
        }

        return Command::SUCCESS;
    }

    private function generateHtmlContent(int $index, int $count, string $content): string
    {
        $prevLink = $index === 0 ? '<span>前</span>' : "<span><a href=\"./$index.html\">前</a></span>";
        $nextLink = $index + 1 === $count ? '<span>次</span>' : "<span><a href=\"./" . ($index + 2) . ".html\">次</a></span>";
        return <<<HTML
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>発表資料</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        p {
            font-size: 20vh;
            margin: 0;
            text-align: center;
        }
        footer {
            position: fixed;
            left: 50%;
            bottom: 20px;
            transform: translate(-50%, -50%);
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <p>$content</p>
</body>
<footer>
    $prevLink
    $nextLink
</footer>
</html>
HTML;
    }
}
