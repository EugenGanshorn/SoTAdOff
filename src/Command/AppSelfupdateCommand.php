<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class AppSelfupdateCommand extends Command
{
    protected static $defaultName = 'app:selfupdate';

    protected function configure()
    {
        $this
            ->setDescription('execute composer update')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $process = new Process('composer update');
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }
}
