<?php

namespace App\Command;

use App\Repository\DeviceRepository;
use App\Utils\DeviceHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppGetTasmotaStatusCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:get-tasmota-status';

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
            ->setDescription('get tasmota status from all devices')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        foreach ($this->deviceRespository->findAll() as $device) {
            $this->deviceHelper
                ->setDevice($device)
                ->updateStatus()
            ;
        }

        //$io->success('');
    }

    /**
     * @required
     *
     * @param DeviceHelper $deviceHelper
     *
     * @return AppGetTasmotaStatusCommand
     */
    public function setDeviceHelper(DeviceHelper $deviceHelper): AppGetTasmotaStatusCommand
    {
        $this->deviceHelper = $deviceHelper;

        return $this;
    }

    /**
     * @required
     *
     * @param DeviceRepository $deviceRespository
     *
     * @return AppGetTasmotaStatusCommand
     */
    public function setDeviceRespository(DeviceRepository $deviceRespository): AppGetTasmotaStatusCommand
    {
        $this->deviceRespository = $deviceRespository;

        return $this;
    }
}
