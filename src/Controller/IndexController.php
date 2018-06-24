<?php

namespace App\Controller;

use App\Repository\DeviceRepository;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TasmotaHttpClient\Request;
use TasmotaHttpClient\Url;

/**
 * @Route("/")
 */
class IndexController extends Controller
{
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

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $devices = $this->deviceRespository->findAll();
        return $this->render('index/index.html.twig', [
            'devices' => $devices,
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id" = "\d+"}, name="toggle")
     * @Method({"GET"})
     * @param EntityManagerInterface $entityManager
     * @param int                    $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function toggle(EntityManagerInterface $entityManager, int $id): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $device = $this->deviceRespository->find($id);

        if (null !== $device) {
            $this->request->getUrl()->setIpAddress($device->getIpAddress());

            $this->request->Power(2);

            $status = $this->request->Status(0);
            $device->setPower($status['Status']['Power']);

            $entityManager->persist($device);
            $entityManager->flush();
        }

        return $this->redirectToRoute('index');
    }

    /**
     * @required
     *
     * @param DeviceRepository $deviceRespository
     *
     * @return IndexController
     */
    public function setDeviceRespository(DeviceRepository $deviceRespository): IndexController
    {
        $this->deviceRespository = $deviceRespository;
        return $this;
    }

    /**
     * @required
     *
     * @param Client $client
     *
     * @return IndexController
     */
    public function setClient(Client $client): IndexController
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @required
     *
     * @param Request $request
     *
     * @return IndexController
     */
    public function setRequest(Request $request): IndexController
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @required
     *
     * @param Url $url
     *
     * @return IndexController
     */
    public function setUrl(Url $url): IndexController
    {
        $this->url = $url;
        return $this;
    }
}
