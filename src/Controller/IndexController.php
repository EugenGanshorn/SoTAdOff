<?php

namespace App\Controller;

use App\Repository\DeviceRepository;
use App\Utils\DeviceHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/")
 */
class IndexController extends Controller
{
    /**
     * @var DeviceHelper
     */
    protected $deviceHelper;

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
     *
     * @param EntityManagerInterface $entityManager
     * @param int                    $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function toggle(EntityManagerInterface $entityManager, int $id): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $device = $this->deviceRespository->find($id);

        if (null !== $device) {
            $this->deviceHelper
                ->setDevice($device)
                ->toogle()
            ;
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
     * @param DeviceHelper $deviceHelper
     *
     * @return IndexController
     */
    public function setDeviceHelper(DeviceHelper $deviceHelper): IndexController
    {
        $this->deviceHelper = $deviceHelper;

        return $this;
    }
}
