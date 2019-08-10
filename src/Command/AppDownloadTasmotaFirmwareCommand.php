<?php

namespace App\Command;

use App\Utils\GithubHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AppDownloadTasmotaFirmwareCommand extends Command
{
    protected static $defaultName = 'app:download-tasmota-firmware';

    /**
     * @var GithubHelper
     */
    protected $githubHelper;

    protected function configure()
    {
        $this
            ->setDescription('download latest tasmota firmware from github')
            ->addArgument('language', InputArgument::OPTIONAL, 'Firmware language', 'DE')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->githubHelper->downloadFirmware($input->getArgument('language'));
    }

    /**
     * @required
     *
     * @param GithubHelper $githubHelper
     *
     * @return AppDownloadTasmotaFirmwareCommand
     */
    public function setGithubHelper(GithubHelper $githubHelper): AppDownloadTasmotaFirmwareCommand
    {
        $this->githubHelper = $githubHelper;

        return $this;
    }
}
