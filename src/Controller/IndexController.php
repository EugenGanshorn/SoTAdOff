<?php

namespace App\Controller;

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
     * @Route("/{id}/{type}", requirements={"id" = "\d+"}, name="toggle", methods={"GET"})
     *
     * @param int    $id
     * @param string $type
     *
     * @return RedirectResponse
     */
    public function toggle(int $id, string $type = self::DEVICE): RedirectResponse
    {
        if ($type === static::GROUP) {
            $devices = $this->deviceRespository->findByDeviceGroup($id);
        } else {
            $devices = [$this->deviceRespository->find($id)];
        }

        foreach ($devices as $device) {
            if ($device !== null) {
                $this->deviceHelper
                    ->setDevice($device)
                    ->toggle()
                ;
            }
        }

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/{id}/{color}", requirements={"id" = "\d+", "color" = "^[A-Fa-f0-9]{6}$"}, name="color", methods={"GET"})
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
     * @Route("/{id}/dimmer/{dimmer}", requirements={"id" = "\d+", "dimmer" = "^[0-9]{1,3}$"}, name="dimmer", methods={"GET"})
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
     * @Route("/{id}/temperature/{temperature}", requirements={"id" = "\d+", "temperature" = "^[0-9]{3}$"}, name="temperature", methods={"GET"})
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
     * @Route("/{id}/speed/{speed}", requirements={"id" = "\d+", "speed" = "^[0-9]{1,2}$"}, name="speed", methods={"GET"})
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
}
