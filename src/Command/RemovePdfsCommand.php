<?php

namespace App\Command;

use App\Service\PdfService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:remove-pdfs',
    description: 'Remove all PDF files in /public',
)]
class RemovePdfsCommand extends Command
{

    private $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Remove all PDF files in /public');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Removing PDF files...');

        $pdfDir = __DIR__ . '/../../public';
        $pdfFiles = glob($pdfDir . '/*.pdf');
        foreach ($pdfFiles as $pdfFile) {
            // get juste the file name
            $pdfFile = basename($pdfFile);
            $this->pdfService->removePdf($pdfFile);
        }

        $output->writeln('PDF files removed.');

        return Command::SUCCESS;
    }
}
