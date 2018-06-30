<?php

namespace App\Controller;

use App\Repository\DeviceRepository;
use App\Utils\DeviceHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function toggle(int $id): RedirectResponse
    {
        $device = $this->deviceRespository->find($id);

        if (null !== $device) {
            $this->deviceHelper
                ->setDevice($device)
                ->toggle()
            ;
        }

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/{id}/{color}", requirements={"id" = "\d+", "color" = "^[A-Fa-f0-9]{6}$"}, name="color")
     * @Method({"GET"})
     *
     * @param int    $id
     * @param string $color
     *
     * @return RedirectResponse
     */
    public function color(int $id, string $color): RedirectResponse
    {
        $device = $this->deviceRespository->find($id);

        if (null !== $device) {
            $this->deviceHelper
                ->setDevice($device)
                ->setColor(sprintf('#%s', $color))
            ;
        }

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/{id}/dimmer/{dimmer}", requirements={"id" = "\d+", "dimmer" = "^[0-9]{1,3}$"}, name="dimmer")
     * @Method({"GET"})
     *
     * @param int    $id
     * @param string $dimmer
     *
     * @return RedirectResponse
     */
    public function dimmer(int $id, string $dimmer): RedirectResponse
    {
        $device = $this->deviceRespository->find($id);

        if (null !== $device) {
            $this->deviceHelper
                ->setDevice($device)
                ->setDimmer($dimmer)
            ;
        }

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/{id}/temperature/{temperature}", requirements={"id" = "\d+", "temperature" = "^[0-9]{3}$"}, name="temperature")
     * @Method({"GET"})
     *
     * @param int    $id
     * @param string $temperature
     *
     * @return RedirectResponse
     */
    public function temperature(int $id, string $temperature): RedirectResponse
    {
        $device = $this->deviceRespository->find($id);

        if (null !== $device) {
            $this->deviceHelper
                ->setDevice($device)
                ->setTemperature($temperature)
            ;
        }

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/{id}/speed/{speed}", requirements={"id" = "\d+", "speed" = "^[0-9]{1,2}$"}, name="speed")
     * @Method({"GET"})
     *
     * @param int    $id
     * @param string $speed
     *
     * @return RedirectResponse
     */
    public function speed(int $id, string $speed): RedirectResponse
    {
        $device = $this->deviceRespository->find($id);

        if (null !== $device) {
            $this->deviceHelper
                ->setDevice($device)
                ->setSpeed($speed)
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
