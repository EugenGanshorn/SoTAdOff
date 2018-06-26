<?php

namespace App\Command;

use App\Repository\DeviceRepository;
use App\Utils\DeviceHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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

    protected function configure()
    {
        $this
            ->setDescription('update tasmota firmware on device')
            ->addOption('device', 'd', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'device ip')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $devices = $input->getOption('device');

        foreach ($devices as $device) {
            $this->doUpgrade('minimal');
            $this->doUpgrade('full');

            $io->success(sprintf('Successful updated device: %s', $device));
        }
    }

    protected function doUpgrade(string $otaUrl): void
    {
        $this->deviceHelper->setOtaUrl($otaUrl);
        $this->deviceHelper->upgrade();

        do {
            usleep(500000);
        } while (!$this->deviceHelper->isExists());
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
}
