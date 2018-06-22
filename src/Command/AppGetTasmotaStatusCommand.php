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
            $url = $this->request->getUrl();
            $url->setIpAddress($device->getIpAddress());
            $this->request->setUrl($url);

            $device->setStatus(json_encode($this->request->Status(0)));

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
