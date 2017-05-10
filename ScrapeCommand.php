<?php

namespace JoeAnzalone\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use JoeAnzalone\Scrappy;

class ScrapeCommand extends Command
{
    protected function configure()
    {
        $this->setName('scrape')
            ->setDescription('Outputs \'Hello World\'')
            ->addOption(
                'url',
                null,
                InputOption::VALUE_REQUIRED,
                'What URL to scrape'
            )
            ->addOption(
                'selector',
                null,
                InputOption::VALUE_REQUIRED,
                'A CSS selector'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $scrappy = new Scrappy([
            'url' => $input->getOption('url'),
            'selector' => $input->getOption('selector'),
        ]);

        $elements = $scrappy->scrape();

        foreach ($elements as $element) {
            $output->writeln($element->textContent);
        }

    }
}
