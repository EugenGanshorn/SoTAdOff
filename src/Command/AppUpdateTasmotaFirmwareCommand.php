<?php

namespace App\Command;

use App\Repository\DeviceRepository;
use App\Utils\DeviceHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AppUpdateTasmotaFirmwareCommand extends Command
{
    protected static $defaultName = 'app:update-tasmota-firmware';

    /**
     * @var DeviceHelper
     */
    protected $deviceHelper;

    /**
     * @var DeviceRepository
     */
    protected $deviceRespository;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var AppDownloadTasmotaFirmwareCommand
     */
    protected $appDownloadTasmotaFirmwareCommand;

    protected function configure()
    {
        $this
            ->setDescription('update tasmota firmware on device')
            ->addArgument('language', InputArgument::OPTIONAL, 'Firmware language', 'DE')
            ->addOption('device', 'd', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'device ip')
            ->addOption('download-firmware', 'f', InputOption::VALUE_OPTIONAL, 'download firmware first', true)
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     * @throws \Symfony\Component\Console\Exception\ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $language = $input->getArgument('language');
        $ipAddresses = $input->getOption('device');

        if ($input->getOption('download-firmware')) {
            $this->downloadFirmware($output, $language);
        }

        foreach ($ipAddresses as $ipAddress) {
            /** @noinspection PhpUndefinedMethodInspection */
            $device = $this->deviceRespository->findOneByIpAddress($ipAddress);

            $this->deviceHelper->setDevice($device);

            foreach (['minimal', $language] as $otaUrl) {
                $otaUrl = $this->router->generate(
                    'download',
                    ['filename' => sprintf('sonoff-%s.bin', $otaUrl)],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                $sucessful = $this->deviceHelper->doUpgrade($otaUrl);
                if (!$sucessful) {
                    break;
                }
            }

            $io->success(sprintf('Successful updated device: %s', $ipAddress));
        }
    }

    /**
     * @param OutputInterface $output
     * @param                 $language
     *
     * @throws \Symfony\Component\Console\Exception\ExceptionInterface
     */
    protected function downloadFirmware(OutputInterface $output, $language): void
    {
        $commandName = 'app:download-tasmota-firmware';
        $command = $this->getApplication()->find($commandName);

        $downloadInput = new ArrayInput(
            [
                'command' => $commandName,
                'language' => $language,
            ]
        );

        try {
            $command->run($downloadInput, $output);
        } catch (\Exception $e) {
        }
    }

    /**
     * @required
     *
     * @param DeviceHelper $deviceHelper
     *
     * @return AppUpdateTasmotaFirmwareCommand
     */
    public function setDeviceHelper(DeviceHelper $deviceHelper): AppUpdateTasmotaFirmwareCommand
    {
        $this->deviceHelper = $deviceHelper;

        return $this;
    }

    /**
     * @required
     *
     * @param DeviceRepository $deviceRespository
     *
     * @return AppUpdateTasmotaFirmwareCommand
     */
    public function setDeviceRespository(DeviceRepository $deviceRespository): AppUpdateTasmotaFirmwareCommand
    {
        $this->deviceRespository = $deviceRespository;

        return $this;
    }

    /**
     * @required
     *
     * @param UrlGeneratorInterface $router
     *
     * @return AppUpdateTasmotaFirmwareCommand
     */
    public function setRouter(UrlGeneratorInterface $router): AppUpdateTasmotaFirmwareCommand
    {
        $this->router = $router;

        return $this;
    }

    /**
     * @required
     *
     * @param AppDownloadTasmotaFirmwareCommand $appDownloadTasmotaFirmwareCommand
     *
     * @return AppUpdateTasmotaFirmwareCommand
     */
    public function setAppDownloadTasmotaFirmwareCommand(
        AppDownloadTasmotaFirmwareCommand $appDownloadTasmotaFirmwareCommand
    ): AppUpdateTasmotaFirmwareCommand {
        $this->appDownloadTasmotaFirmwareCommand = $appDownloadTasmotaFirmwareCommand;

        return $this;
    }
}
