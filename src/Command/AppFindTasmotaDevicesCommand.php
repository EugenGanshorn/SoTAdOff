<?php

namespace App\Command;

use App\Entity\Device;
use App\Repository\DeviceRepository;
use App\Utils\DeviceHelper;
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
            ->setDescription('find all tasmota devices in your local network')
            ->addArgument('from', InputArgument::REQUIRED, 'first ip address')
            ->addArgument('to', InputArgument::REQUIRED, 'last ip address')
            ->addOption('timeout', 't', InputOption::VALUE_OPTIONAL, 'timeout', 1)
            ->addOption('ignore-exists', 'i', InputOption::VALUE_OPTIONAL, 'ignore already exists devices', 1)
            ->addOption('auto-create', 'c', InputOption::VALUE_OPTIONAL, 'create automatically new founded devices')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $from = $input->getArgument('from');
        $to = $input->getArgument('to');

        $io->note(sprintf('start to scan new tasmota devices in your local network between %s and %s', $from, $to));

        $amountNewDevices = 0;
        for ($i = ip2long($from), $iMax = ip2long($to); $i <= $iMax; ++$i) {
            $ipAddress = long2ip($i);

            if ($input->getOption('ignore-exists')) {
                /** @noinspection PhpUndefinedMethodInspection */
                $device = $this->deviceRespository->findByIpAddress($ipAddress);
                if (!empty($device)) {
                    $io->note(sprintf('device %s already exists', $ipAddress));
                    continue;
                }
            }

            $io->note(sprintf('scan %s', $ipAddress));

            $device = new Device();
            $device->setIpAddress($ipAddress);
            $device->setPosition($i);

            $this->deviceHelper->setDevice($device);

            $isExists = $this->deviceHelper->isExists($input->getOption('timeout'));

            if ($isExists) {
                $io->success(sprintf('new tasmota device found at %s', $ipAddress));
                ++$amountNewDevices;

                if ($input->hasOption('auto-create')) {
                    $this->deviceHelper->updateStatus();
                }
            }
        }

        if ($amountNewDevices) {
            $io->success(sprintf('Found %d new devices', $amountNewDevices));
        } else {
            $io->error('No new devices found');
        }
    }

    /**
     * @required
     *
     * @param DeviceHelper $deviceHelper
     *
     * @return AppFindTasmotaDevicesCommand
     */
    public function setDeviceHelper(DeviceHelper $deviceHelper): AppFindTasmotaDevicesCommand
    {
        $this->deviceHelper = $deviceHelper;

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
