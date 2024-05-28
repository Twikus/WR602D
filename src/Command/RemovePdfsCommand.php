<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:remove-pdfs',
    description: 'Remove all PDF files in /public',
)]
class RemovePdfsCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Remove all PDF files in /public');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Removing PDF files...');

        // remove all PDF files in /public
        $pdfDir = __DIR__ . '/../../public';
        $pdfFiles = glob($pdfDir . '/*.pdf');
        foreach ($pdfFiles as $pdfFile) {
            unlink($pdfFile);
        }

        $output->writeln('PDF files removed.');

        return Command::SUCCESS;
    }
}
