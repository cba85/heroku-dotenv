<?php

namespace App\Command;

use App\HerokuDotenv;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class HerokuDotenvPushCommand extends Command
{
    protected static $defaultName = 'push';

    protected function configure()
    {
        $this
            ->setDescription('Push a dotenv file to Heroku environment variables')
            ->setHelp('This command allows you to copy a dotenv file to Heroku environment variables')
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

        if (!$herokuDotenv->checkDotenvVariables()) {
            $output->writeln('<fg=red>[ERROR] Your .env file contains incorrect variables</fg=red>');
            return Command::FAILURE;
        }

        $herokuDotenv->push();

        return Command::SUCCESS;
    }
}
