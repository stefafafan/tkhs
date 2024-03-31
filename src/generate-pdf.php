<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class GeneratePdfCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('generate:pdf')
            ->setDescription('Converts and combines HTML files in the output directory into a single PDF file.')
            ->addArgument('filename', InputArgument::REQUIRED, 'The path to the text file.')
            ->addArgument('output', InputArgument::OPTIONAL, 'The pdf output name, if not specified "slide.pdf" will be used.');
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
        
        // Landscape, 720x405 size (16:9 aspect ratio). UTF-8 support for Japanese characters.
        $pdf = new TCPDF('L', 'pt', array(720, 405), true, 'UTF-8', false);

        // Set font for Japanese characters. Font size is set to 75, which is an arbitrary value set to fit the page.
        $pdf->SetFont('kozminproregular', '', 75, '', 'default', true);
        
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('github.com/stefafafan/tkhs');
        $pdf->SetTitle('発表資料');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        foreach ($lines as $line) {
            $pdf->AddPage();

            // An arbitrary value, which fits the 720x405 size.
            $width = 670;
            $height = $pdf->getStringHeight($width, $line);

            // Center the text vertically.
            $startY = ($pdf->getPageHeight() - $height) / 2;
            $pdf->SetY($startY);

            // Print a MultiCell with text centered (horizontally and vertically).
            $pdf->MultiCell($width, $height, $line, 0, 'C', false, 1, null, null, true, 0, false, true, $height, 'M');
        }

        $outputDir = $input->getArgument('output') ?? 'slide.pdf';
        $pdf->Output(__DIR__ . '/../' . $outputDir, 'F');
        $output->writeln("PDF generated: $outputDir");

        return Command::SUCCESS;
    }
}
