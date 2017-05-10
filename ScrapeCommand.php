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
            ->setDescription('Scrapes a webpage')
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
                'An XPath selector'
            )
            ->addOption(
                'cookies',
                null,
                InputOption::VALUE_OPTIONAL,
                'Some cookies to include'
            )
            ->addOption(
                'header',
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Add a header! Why not!'
            )
            ->addOption(
                'debug',
                null,
                null,
                'If set, Guzzle will be run in debug mode so you can see all the headers and stuff'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $scrappy = new Scrappy([
            'url' => $input->getOption('url'),
            'selector' => $input->getOption('selector'),
            'cookies' => $input->getOption('cookies'),
            'headers' => $input->getOption('header'),
            'debug' => $input->getOption('debug'),
        ]);

        $elements = $scrappy->scrape();

        foreach ($elements as $element) {
            $output->writeln(
                trim($element->textContent)
            );
        }

    }
}
