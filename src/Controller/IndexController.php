<?php

namespace App\Controller;

use App\Repository\DeviceRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
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
}
