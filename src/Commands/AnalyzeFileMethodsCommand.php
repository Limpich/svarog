<?php

namespace Limpich\Svarog\Commands;

use Limpich\Svarog\Analyze\UnusedMethodCode\UnusedMethodCodeAnalyzer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'file:methods')]
class AnalyzeFileMethodsCommand extends Command
{
  protected function configure(): void
  {
    parent::configure();

    $this->addArgument('file', InputArgument::REQUIRED, 'Path to file');
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    /** @var string $filepath */
    $filepath = $input->getArgument('file');

    $output->writeln("Analyze file '$filepath'");
    $output->writeln("========\n");

    $analyzer = new UnusedMethodCodeAnalyzer();
    $result = $analyzer->analyzeFile($filepath);

    $output->writeln("Fount methods or functions:");
    foreach ($result->getFunctions() as $function) {
      $output->writeln($function->getFunction()->formatNameWithLine());

      $unusedVars = $function->getGraph()->getUnusedVars();
      if ($unusedVars) {
        $output->writeln("  Redundant vars:");
        foreach ($unusedVars as $var) {
          $output->writeln("    $var");
        }
      }

    }

    return Command::SUCCESS;
  }
}
