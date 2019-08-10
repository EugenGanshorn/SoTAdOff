<?php

namespace App\Controller;

use App\Entity\Device;
use App\Repository\DeviceGroupRepository;
use App\Repository\DeviceRepository;
use App\Utils\DeviceHelper;
use App\Utils\DeviceHelperFactory;
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
     * @var DeviceHelperFactory
     */
    protected $deviceHelperFactory;

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
        $this
            ->forEachDevice(
                $type,
                $id,
                function (DeviceHelper $deviceHelper) {
                    $deviceHelper
                        ->toggle();
                }
            )
        ;

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
            function (DeviceHelper $deviceHelper) use ($color) {
                $deviceHelper
                    ->setColor(sprintf('#%s', $color));
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
            function (DeviceHelper $deviceHelper) use ($dimmer) {
                $deviceHelper
                    ->setDimmer($dimmer);
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
            function (DeviceHelper $deviceHelper) use ($temperature) {
                $deviceHelper
                    ->setTemperature($temperature);
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
            function (DeviceHelper $deviceHelper) use ($speed) {
                $deviceHelper
                    ->setSpeed($speed);
            }
        );

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/{type}/{id}/scheme/{scheme}", requirements={"type" = "device|group", "id" = "\d+", "scheme" = "\d"}, name="scheme", methods={"GET"})
     *
     * @param string $type
     * @param int    $id
     * @param int    $scheme
     *
     * @return RedirectResponse
     */
    public function scheme(string $type, int $id, int $scheme): RedirectResponse
    {
        $this->forEachDevice(
            $type,
            $id,
            function (DeviceHelper $deviceHelper) use ($scheme) {
                $deviceHelper
                    ->setScheme($scheme);
            }
        );

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/{type}/{id}/fade/{fade}", requirements={"type" = "device|group", "id" = "\d+", "fade" = "^[0-1]{1}$"}, name="fade", methods={"GET"})
     *
     * @param string $type
     * @param int    $id
     * @param bool   $fade
     *
     * @return RedirectResponse
     */
    public function fade(string $type, int $id, bool $fade): RedirectResponse
    {
        $this->forEachDevice(
            $type,
            $id,
            function (DeviceHelper $deviceHelper) use ($fade) {
                $deviceHelper
                    ->setFade($fade);
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
     * @param DeviceHelperFactory $deviceHelperFactory
     *
     * @return self
     */
    public function setDeviceHelperFactory(DeviceHelperFactory $deviceHelperFactory): self
    {
        $this->deviceHelperFactory = $deviceHelperFactory;

        return $this;
    }

    /**
     * @param string   $type
     * @param int      $id
     * @param \Closure $callback
     *
     * @return IndexController
     */
    protected function forEachDevice(string $type, int $id, \Closure $callback): self
    {
        if ($type === static::GROUP) {
            $devices = $this->deviceGroupRepository->find($id)->getDevices();
        } else {
            $devices = [$this->deviceRespository->find($id)];
        }

        $deviceHelpers = [];
        foreach ($devices as $device) {
            if (!$device instanceof Device) {
                continue;
            }

            $deviceHelpers[] = $deviceHelper = $this->deviceHelperFactory->create();
            $deviceHelper->startBulk();
            $deviceHelper->setDevice($device);

            $callback($deviceHelper);
        }

        foreach ($deviceHelpers as $deviceHelper) {
            $deviceHelper->finishBulk();
        }

        return $this;
    }
}
