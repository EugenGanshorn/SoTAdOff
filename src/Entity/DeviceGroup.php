<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviceGroupRepository")
 */
class DeviceGroup
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Device", inversedBy="groups")
     */
    private $devices;

    public function __construct()
    {
        $this->devices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Device[]
     */
    public function getDevices(): Collection
    {
        return $this->devices;
    }

    public function addDevice(Device $device): self
    {
        if (!$this->devices->contains($device)) {
            $this->devices[] = $device;
        }

        return $this;
    }

    public function removeDevice(Device $device): self
    {
        if ($this->devices->contains($device)) {
            $this->devices->removeElement($device);
        }

        return $this;
    }

    public function getPower(): int
    {
        $power = 0;
        foreach ($this->getDevices() as $device) {
            $power += $device->getPower();
            if ($power > 0) {
                return 1;
            }
        }

        return $power;
    }

    public function getModule(): int
    {
        $modules = [];
        foreach ($this->getDevices() as $device) {
            $modules[] = $device->getModule();
        }

        $modules = array_unique($modules);
        if (count($modules) !== 1) {
            throw new \LogicException('The device group contains more then one different modules');
        }

        return reset($modules);
    }

    public function getStsColor(): string
    {
        $stsColor = 0;
        foreach ($this->getDevices() as $device) {
            $stsColor += hexdec($device->getStsColor());
        }

        return sprintf('#%s', dechex($stsColor / count($this->getDevices())));
    }

    public function getStsDimmer(): int
    {
        $stsDimmer = 0;
        foreach ($this->getDevices() as $device) {
            $stsDimmer += $device->getStsDimmer();
        }

        return round($stsDimmer / count($this->getDevices()));
    }

    public function getStsSpeed(): int
    {
        $stsSpeed = 0;
        foreach ($this->getDevices() as $device) {
            $stsSpeed += $device->getStsSpeed();
        }

        return round($stsSpeed / count($this->getDevices()));
    }

    public function getStsCt(): int
    {
        $stsCt = 0;
        foreach ($this->getDevices() as $device) {
            $stsCt += $device->getStsCt();
        }

        return round($stsCt / count($this->getDevices()));
    }

    public function getStsScheme(): int
    {
        $stsScheme = 0;
        foreach ($this->getDevices() as $device) {
            $stsScheme += $device->getStsScheme();
        }

        return round($stsScheme / count($this->getDevices()));
    }

    public function getStsFade(): int
    {
        $fade = 0;
        foreach ($this->getDevices() as $device) {
            $fade += $device->getStsFade();
            if ($fade > 0) {
                return 1;
            }
        }

        return $fade;
    }
}
