<?php

namespace App\Controller;

use App\Entity\Device;
use App\Repository\DeviceGroupRepository;
use App\Repository\DeviceRepository;
use App\Utils\DeviceHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class IndexController extends AbstractController
{
    public const GROUP = 'group';
    public const DEVICE = 'device';

    /**
     * @var DeviceHelper
     */
    protected $deviceHelper;

    /**
     * @var DeviceRepository
     */
    protected $deviceRespository;

    /**
     * @var DeviceGroupRepository
     */
    protected $deviceGroupRepository;

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $devices = $this->deviceRespository->findBy([], ['module' => 'ASC', 'name' => 'ASC']);
        $deviceGroups = $this->deviceGroupRepository->findAll();

        return $this->render(
            'index/index.html.twig',
            [
                'devices'      => $devices,
                'deviceGroups' => $deviceGroups,
            ]
        );
    }

    /**
     * @Route("/{type}/{id}/", requirements={"type" = "device|group", "id" = "\d+"}, name="toggle", methods={"GET"})
     *
     * @param string $type
     * @param int    $id
     *
     * @return RedirectResponse
     */
    public function toggle(string $type, int $id): RedirectResponse
    {
        $this->forEachDevice(
            $type,
            $id,
            function (Device $device) {
                $this->deviceHelper
                    ->setDevice($device)
                    ->toggle()
                ;
            }
        );

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/{type}/{id}/{color}", requirements={"type" = "device|group", "id" = "\d+", "color" = "^[A-Fa-f0-9]{6}$"}, name="color", methods={"GET"})
     *
     * @param string $type
     * @param int    $id
     * @param string $color
     *
     * @return RedirectResponse
     */
    public function color(string $type, int $id, string $color): RedirectResponse
    {
        $this->forEachDevice(
            $type,
            $id,
            function (Device $device) use ($color) {
                $this->deviceHelper
                    ->setDevice($device)
                    ->setColor(sprintf('#%s', $color))
                ;
            }
        );

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/{type}/{id}/dimmer/{dimmer}", requirements={"type" = "device|group", "id" = "\d+", "dimmer" = "^[0-9]{1,3}$"}, name="dimmer", methods={"GET"})
     *
     * @param string $type
     * @param int    $id
     * @param string $dimmer
     *
     * @return RedirectResponse
     */
    public function dimmer(string $type, int $id, string $dimmer): RedirectResponse
    {
        $this->forEachDevice(
            $type,
            $id,
            function (Device $device) use ($dimmer) {
                $this->deviceHelper
                    ->setDevice($device)
                    ->setDimmer($dimmer)
                ;
            }
        );

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/{type}/{id}/temperature/{temperature}", requirements={"type" = "device|group", "id" = "\d+", "temperature" = "^[0-9]{3}$"}, name="temperature", methods={"GET"})
     *
     * @param string $type
     * @param int    $id
     * @param string $temperature
     *
     * @return RedirectResponse
     */
    public function temperature(string $type, int $id, string $temperature): RedirectResponse
    {
        $this->forEachDevice(
            $type,
            $id,
            function (Device $device) use ($temperature) {
                $this->deviceHelper
                    ->setDevice($device)
                    ->setTemperature($temperature)
                ;
            }
        );

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/{type}/{id}/speed/{speed}", requirements={"type" = "device|group", "id" = "\d+", "speed" = "^[0-9]{1,2}$"}, name="speed", methods={"GET"})
     *
     * @param string $type
     * @param int    $id
     * @param string $speed
     *
     * @return RedirectResponse
     */
    public function speed(string $type, int $id, string $speed): RedirectResponse
    {
        $this->forEachDevice(
            $type,
            $id,
            function (Device $device) use ($speed) {
                $this->deviceHelper
                    ->setDevice($device)
                    ->setSpeed($speed)
                ;
            }
        );

        return $this->redirectToRoute('index');
    }

    /**
     * @required
     *
     * @param DeviceRepository $deviceRespository
     *
     * @return self
     */
    public function setDeviceRespository(DeviceRepository $deviceRespository): self
    {
        $this->deviceRespository = $deviceRespository;

        return $this;
    }

    /**
     * @required
     *
     * @param DeviceGroupRepository $deviceGroupRespository
     *
     * @return self
     */
    public function setDeviceGroupRespository(DeviceGroupRepository $deviceGroupRespository): self
    {
        $this->deviceGroupRepository = $deviceGroupRespository;

        return $this;
    }

    /**
     * @required
     *
     * @param DeviceHelper $deviceHelper
     *
     * @return self
     */
    public function setDeviceHelper(DeviceHelper $deviceHelper): self
    {
        $this->deviceHelper = $deviceHelper;

        return $this;
    }

    /**
     * @param string   $type
     * @param int      $id
     * @param \Closure $callback
     */
    protected function forEachDevice(string $type, int $id, \Closure $callback): void
    {
        if ($type === static::GROUP) {
            $devices = $this->deviceGroupRepository->find($id)->getDevices();
        } else {
            $devices = [$this->deviceRespository->find($id)];
        }

        foreach ($devices as $device) {
            if (!$device instanceof Device) {
                continue;
            }

            $callback($device);
        }
    }
}
