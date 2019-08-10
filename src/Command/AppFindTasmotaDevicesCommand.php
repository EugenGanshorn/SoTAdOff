<?php

namespace App\Command;

use App\Entity\Device;
use App\Repository\DeviceRepository;
use App\Utils\DeviceHelperFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppFindTasmotaDevicesCommand extends Command
{
    protected static $defaultName = 'app:find-tasmota-devices';

    /**
     * @var DeviceHelperFactory
     */
    protected $deviceHelperFactory;

    /**
     * @var DeviceRepository
     */
    protected $deviceRespository;

    protected function configure()
    {
        $this
            ->setDescription('find all tasmota devices in your local network')
            ->addArgument('from', InputArgument::REQUIRED, 'first ip address')
            ->addArgument('to', InputArgument::REQUIRED, 'last ip address')
            ->addOption('timeout', 't', InputOption::VALUE_OPTIONAL, 'timeout', 5)
            ->addOption('ignore-exists', 'i', InputOption::VALUE_OPTIONAL, 'ignore already exists devices', true)
            ->addOption('auto-create', 'c', InputOption::VALUE_OPTIONAL, 'create automatically new founded devices', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $from = ip2long($input->getArgument('from'));
        $to = ip2long($input->getArgument('to'));
        $ignoreExists = $input->getOption('ignore-exists');
        $autoCreate = $input->hasOption('auto-create');

        if ($to < $from) {
            $toOriginal = $to;
            $to = $from;
            $from = $toOriginal;
            unset($toOriginal);
        }

        $deviceHelpers = [];
        $amountNewDevices = 0;
        for ($i = $from; $i <= $to; ++$i) {
            $ipAddress = long2ip($i);

            /* @noinspection PhpUndefinedMethodInspection */
            if ($ignoreExists && !empty($this->deviceRespository->findOneByIpAddress($ipAddress))) {
                $io->note(sprintf('device %s already exists', $ipAddress));
                continue;
            }

            $io->note(sprintf('scan %s', $ipAddress));

            $deviceHelpers[] = $deviceHelper = $this->deviceHelperFactory->create();
            $deviceHelper->startBulk();
            $deviceHelper->setDevice($this->createNewDevice($ipAddress, $i));

            $timeout = $input->getOption('timeout');
            $deviceHelper->isExists($timeout, function () use ($io, &$amountNewDevices, $ipAddress, $autoCreate, $deviceHelper, $timeout) {
                $io->success(sprintf('new tasmota device found at %s', $ipAddress));
                ++$amountNewDevices;

                if ($autoCreate) {
                    $deviceHelper->updateStatus($timeout, false);
                }
            });
        }

        foreach ($deviceHelpers as $deviceHelper) {
            $deviceHelper->finishBulk();
        }

        if ($amountNewDevices) {
            $io->success(sprintf('Found %d new devices', $amountNewDevices));
        } else {
            $io->error('No new devices found');
        }
    }

    /**
     * @param string $ipAddress
     * @param int    $position
     *
     * @return Device
     */
    protected function createNewDevice(string $ipAddress, int $position): Device
    {
        $device = new Device();
        $device->setVisible(1);
        $device->setIpAddress($ipAddress);
        $device->setPosition($position);
        $device->setVisible(true);

        return $device;
    }

    /**
     * @required
     *
     * @param DeviceHelperFactory $deviceHelperFactory
     *
     * @return AppFindTasmotaDevicesCommand
     */
    public function setDeviceHelperFactory(DeviceHelperFactory $deviceHelperFactory): AppFindTasmotaDevicesCommand
    {
        $this->deviceHelperFactory = $deviceHelperFactory;

        return $this;
    }

    /**
     * @required
     *
     * @param DeviceRepository $deviceRespository
     *
     * @return AppFindTasmotaDevicesCommand
     */
    public function setDeviceRespository(DeviceRepository $deviceRespository): AppFindTasmotaDevicesCommand
    {
        $this->deviceRespository = $deviceRespository;

        return $this;
    }
}
