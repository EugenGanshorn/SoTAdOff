<?php

namespace App\Command;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class AppDownloadTasmotaFirmwareCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:download-tasmota-firmware';

    protected function configure()
    {
        $this
            ->setDescription('download latest tasmota firmware from github')
            ->addArgument('language', InputArgument::OPTIONAL, 'Firmware language', 'DE')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $language = $input->getArgument('language');

        $client = new Client();
        $response = $client->get('https://api.github.com/repos/arendst/Sonoff-Tasmota/releases/latest');
        $result = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        $projectDir = $this->getContainer()->get('kernel')->getProjectDir();
        $firmwareDir = sprintf(
            '%s/var/firmware',
            $projectDir
        );

        $fileSystem = new Filesystem();
        $fileSystem->mkdir($firmwareDir);

        foreach ($result['assets'] as $asset) {
            if ('sonoff-minimal.bin' === $asset['name'] || $asset['name'] === sprintf('sonoff-%s.bin', $language)) {
                $filePath = sprintf(
                    '%s/%s',
                    $firmwareDir,
                    $asset['name']
                );

                $client->get($asset['browser_download_url'], [
                    'sink' => $filePath,
                ]);

                $io->success(sprintf('Successful download %s to %s', $asset['name'], $filePath));
            }
        }
    }
}
