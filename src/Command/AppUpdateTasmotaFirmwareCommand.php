<?php

namespace App\Command;

use App\Repository\DeviceRepository;
use App\Utils\DeviceHelper;
use App\Utils\DeviceHelperFactory;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
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
     * @var DeviceHelperFactory
     */
    protected $deviceHelperFactory;

    /**
     * @var DeviceRepository
     */
    protected $deviceRespository;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

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
     * @throws ExceptionInterface
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
            $device = $this->deviceRespository->findOneByIpAddress($ipAddress);

            $deviceHelper = $this->deviceHelperFactory->create();
            $deviceHelper->setDevice($device);

            foreach (['minimal', $language] as $otaUrl) {
                $otaUrl = $this->router->generate(
                    'download',
                    ['filename' => sprintf('sonoff-%s.bin', $otaUrl)],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                $successful = $deviceHelper->doUpgrade($otaUrl);
                if (!$successful) {
                    break;
                }
            }

            $deviceHelper->updateStatus();

            $io->success(sprintf('Successful updated device: %s, new fwr version is %s', $ipAddress, $device->getFwrVersion()));
        }
    }

    /**
     * @param OutputInterface $output
     * @param                 $language
     *
     * @throws ExceptionInterface
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
        } catch (Exception $e) {
        }
    }

    /**
     * @required
     *
     * @param DeviceHelperFactory $deviceHelperFactory
     *
     * @return AppUpdateTasmotaFirmwareCommand
     */
    public function setDeviceHelperFactory(DeviceHelperFactory $deviceHelperFactory): AppUpdateTasmotaFirmwareCommand
    {
        $this->deviceHelperFactory = $deviceHelperFactory;

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
}
