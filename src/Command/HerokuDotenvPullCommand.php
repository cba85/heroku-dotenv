<?php

namespace App\Command;

use App\HerokuDotenv;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class HerokuDotenvPullCommand extends Command
{
    protected static $defaultName = 'pull';

    protected function configure()
    {
        $this
            ->setDescription('Pull Heroku environment variables to a dotenv file')
            ->setHelp('This command allows you to copy Heroku environment variables to a dotenv file')
            ->addArgument('app', InputArgument::REQUIRED, 'Name of your Heroku application')
            ->addOption(
                'file',
                'f',
                InputOption::VALUE_REQUIRED,
                'Your dotenv filename',
                '.env'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $herokuDotenv = new HerokuDotenv($input->getArgument('app'), $input->getOption('file'));

        if (!$herokuDotenv->getDotenvContent()) {
            $output->writeln('<fg=red>[ERROR] .env file not found in specified path</fg=red>');
            return Command::FAILURE;
        }

        if (!$herokuDotenv->pull()) {
            $output->writeln("<fg=red>[ERROR] Can't retrieve Heroku environment variables</fg=red>");
            return Command::FAILURE;
        }

        $output->writeln("<fg=green>Heroku environment variables successfully copied to {$input->getOption('file')}</fg=green>");
        return Command::SUCCESS;
    }
}
