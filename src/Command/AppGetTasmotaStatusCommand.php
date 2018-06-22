<?php /** @noinspection ALL */

namespace App\Command;

use App\Repository\DeviceRepository;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TasmotaHttpClient\Request;
use TasmotaHttpClient\Url;

class AppGetTasmotaStatusCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:get-tasmota-status';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Url
     */
    protected $url;

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

        $entityManager = $this->getContainer()->get('doctrine')->getEntityManager();
        foreach ($this->deviceRespository->findAll() as $device) {
            $this->request->getUrl()->setIpAddress($device->getIpAddress());

            $status = $this->request->Status(0);

            $status['Status']['FriendlyName'] = implode(' ', $status['Status']['FriendlyName']);

            $config = [
                'Status' => [
                    'methodNamePrefix' => '',
                    'data' => &$status['Status'],
                ],
                'StatusFWR' => [
                    'methodNamePrefix' => 'Fwr',
                    'data' => &$status['StatusFWR'],
                ],
                'StatusPRM' => [
                    'methodNamePrefix' => 'Prm',
                    'data' => &$status['StatusPRM'],
                ],
                'StatusLOG' => [
                    'methodNamePrefix' => 'Log',
                    'data' => &$status['StatusLOG'],
                ],
                'StatusNET' => [
                    'methodNamePrefix' => 'Net',
                    'data' => &$status['StatusNET'],
                ],
                'StatusMQT' => [
                    'methodNamePrefix' => '',
                    'data' => &$status['StatusMQT'],
                ],
                'StatusSTS' => [
                    'methodNamePrefix' => 'Sts',
                    'data' => &$status['StatusSTS'],
                ],
                'StatusSTSWifi' => [
                    'methodNamePrefix' => 'Wifi',
                    'data' => &$status['StatusSTS']['Wifi'],
                ],
            ];

            foreach ($config as $statusConfig) {
                foreach ($statusConfig['data'] as $name => $value) {
                    if (!is_scalar($value)) {
                        continue;
                    }

                    $methodName = sprintf('set%s%s', $statusConfig['methodNamePrefix'], $name);
                    if (method_exists($device, $methodName)) {
                        $device->$methodName($value);
                    }
                }
            }

            $entityManager->persist($device);
        }

        $entityManager->flush();

        //$io->success('');
    }

    /**
     * @required
     *
     * @param Url $url
     *
     * @return AppGetTasmotaStatusCommand
     */
    public function setUrl(Url $url): AppGetTasmotaStatusCommand
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @required
     *
     * @param Request $request
     *
     * @return AppGetTasmotaStatusCommand
     */
    public function setRequest(Request $request): AppGetTasmotaStatusCommand
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @required
     *
     * @param Client $client
     *
     * @return AppGetTasmotaStatusCommand
     */
    public function setClient(Client $client): AppGetTasmotaStatusCommand
    {
        $this->client = $client;
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
