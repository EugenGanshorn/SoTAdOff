<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviceRepository")
 */
class Device
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
     * @ORM\Column(type="integer", options={"unsigned":true, "default":1})
     */
    private $visible;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    private $ipAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true, "default":0})
     */
    private $position;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $module;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $friendlyName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $topic;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $buttonTopic;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $power;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $powerOnState;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ledState;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $saveData;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $saveState;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $buttonRetain;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $powerRetain;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prmBaudrate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prmGroupTopic;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prmOtaUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prmRestartReason;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prmSleep;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prmBootCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prmSaveCount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prmSaveAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fwrVersion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fwrBuildDateTime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $fwrBoot;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $logSerialLog;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $logWebLog;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $logSysLog;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $logLogHost;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $logLogPort;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $logTelePeriod;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $netHostname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $netIpAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $netGateway;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $netSubnetmask;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $netDnsServer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $netMac;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $netWebserver;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $netWifiConfig;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mqttHost;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mqttPort;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mqttClientMask;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mqttClient;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mqttUser;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mqttType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stsTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stsUptime;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $stsVcc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stsPower;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stsDimmer;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $stsColor;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $stsHSBColor;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stsCt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stsScheme;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stsFade;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stsSpeed;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":false})
     */
    private $stsLedTable;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wifiAp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $wifiSsid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wifiRssi;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $wifiApMac;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\DeviceGroup", mappedBy="devices")
     */
    private $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
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

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getModule(): ?int
    {
        return $this->module;
    }

    public function setModule(?int $module): self
    {
        $this->module = $module;

        return $this;
    }

    public function getFriendlyName(): ?string
    {
        return $this->friendlyName;
    }

    public function setFriendlyName(?string $friendlyName): self
    {
        $this->friendlyName = $friendlyName;

        return $this;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(?string $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function getButtonTopic(): ?int
    {
        return $this->buttonTopic;
    }

    public function setButtonTopic(?int $buttonTopic): self
    {
        $this->buttonTopic = $buttonTopic;

        return $this;
    }

    public function getPower(): ?int
    {
        return $this->power;
    }

    public function setPower(?int $power): self
    {
        $this->power = $power;

        return $this;
    }

    public function getPowerOnState(): ?int
    {
        return $this->powerOnState;
    }

    public function setPowerOnState(?int $powerOnState): self
    {
        $this->powerOnState = $powerOnState;

        return $this;
    }

    public function getLedState(): ?int
    {
        return $this->ledState;
    }

    public function setLedState(?int $ledState): self
    {
        $this->ledState = $ledState;

        return $this;
    }

    public function getSaveData(): ?int
    {
        return $this->saveData;
    }

    public function setSaveData(?int $saveData): self
    {
        $this->saveData = $saveData;

        return $this;
    }

    public function getSaveState(): ?int
    {
        return $this->saveState;
    }

    public function setSaveState(?int $saveState): self
    {
        $this->saveState = $saveState;

        return $this;
    }

    public function getButtonRetain(): ?int
    {
        return $this->buttonRetain;
    }

    public function setButtonRetain(?int $buttonRetain): self
    {
        $this->buttonRetain = $buttonRetain;

        return $this;
    }

    public function getPowerRetain(): ?int
    {
        return $this->powerRetain;
    }

    public function setPowerRetain(?int $powerRetain): self
    {
        $this->powerRetain = $powerRetain;

        return $this;
    }

    public function getPrmBaudrate(): ?int
    {
        return $this->prmBaudrate;
    }

    public function setPrmBaudrate(?int $prmBaudrate): self
    {
        $this->prmBaudrate = $prmBaudrate;

        return $this;
    }

    public function getPrmGroupTopic(): ?string
    {
        return $this->prmGroupTopic;
    }

    public function setPrmGroupTopic(?string $prmGroupTopic): self
    {
        $this->prmGroupTopic = $prmGroupTopic;

        return $this;
    }

    public function getPrmOtaUrl(): ?string
    {
        return $this->prmOtaUrl;
    }

    public function setPrmOtaUrl(?string $prmOtaUrl): self
    {
        $this->prmOtaUrl = $prmOtaUrl;

        return $this;
    }

    public function getPrmRestartReason(): ?string
    {
        return $this->prmRestartReason;
    }

    public function setPrmRestartReason(?string $prmRestartReason): self
    {
        $this->prmRestartReason = $prmRestartReason;

        return $this;
    }

    public function getPrmSleep(): ?int
    {
        return $this->prmSleep;
    }

    public function setPrmSleep(?int $prmSleep): self
    {
        $this->prmSleep = $prmSleep;

        return $this;
    }

    public function getPrmBootCount(): ?int
    {
        return $this->prmBootCount;
    }

    public function setPrmBootCount(?int $prmBootCount): self
    {
        $this->prmBootCount = $prmBootCount;

        return $this;
    }

    public function getPrmSaveCount(): ?int
    {
        return $this->prmSaveCount;
    }

    public function setPrmSaveCount(?int $prmSaveCount): self
    {
        $this->prmSaveCount = $prmSaveCount;

        return $this;
    }

    public function getPrmSaveAddress(): ?string
    {
        return $this->prmSaveAddress;
    }

    public function setPrmSaveAddress(?string $prmSaveAddress): self
    {
        $this->prmSaveAddress = $prmSaveAddress;

        return $this;
    }

    public function getFwrVersion(): ?string
    {
        return $this->fwrVersion;
    }

    public function setFwrVersion(?string $fwrVersion): self
    {
        $this->fwrVersion = $fwrVersion;

        return $this;
    }

    public function getFwrBuildDateTime(): ?string
    {
        return $this->fwrBuildDateTime;
    }

    public function setFwrBuildDateTime(?string $fwrBuildDateTime): self
    {
        $this->fwrBuildDateTime = $fwrBuildDateTime;

        return $this;
    }

    public function getFwrBoot(): ?int
    {
        return $this->fwrBoot;
    }

    public function setFwrBoot(?int $fwrBoot): self
    {
        $this->fwrBoot = $fwrBoot;

        return $this;
    }

    public function getLogSerialLog(): ?int
    {
        return $this->logSerialLog;
    }

    public function setLogSerialLog(?int $logSerialLog): self
    {
        $this->logSerialLog = $logSerialLog;

        return $this;
    }

    public function getLogWebLog(): ?int
    {
        return $this->logWebLog;
    }

    public function setLogWebLog(?int $logWebLog): self
    {
        $this->logWebLog = $logWebLog;

        return $this;
    }

    public function getLogSysLog(): ?int
    {
        return $this->logSysLog;
    }

    public function setLogSysLog(?int $logSysLog): self
    {
        $this->logSysLog = $logSysLog;

        return $this;
    }

    public function getLogLogHost(): ?string
    {
        return $this->logLogHost;
    }

    public function setLogLogHost(?string $logLogHost): self
    {
        $this->logLogHost = $logLogHost;

        return $this;
    }

    public function getLogLogPort(): ?int
    {
        return $this->logLogPort;
    }

    public function setLogLogPort(?int $logLogPort): self
    {
        $this->logLogPort = $logLogPort;

        return $this;
    }

    public function getLogTelePeriod(): ?int
    {
        return $this->logTelePeriod;
    }

    public function setLogTelePeriod(?int $logTelePeriod): self
    {
        $this->logTelePeriod = $logTelePeriod;

        return $this;
    }

    public function getNetHostname(): ?string
    {
        return $this->netHostname;
    }

    public function setNetHostname(?string $netHostname): self
    {
        $this->netHostname = $netHostname;

        return $this;
    }

    public function getNetIpAddress(): ?string
    {
        return $this->netIpAddress;
    }

    public function setNetIpAddress(?string $netIpAddress): self
    {
        $this->netIpAddress = $netIpAddress;

        return $this;
    }

    public function getNetGateway(): ?string
    {
        return $this->netGateway;
    }

    public function setNetGateway(?string $netGateway): self
    {
        $this->netGateway = $netGateway;

        return $this;
    }

    public function getNetSubnetmask(): ?string
    {
        return $this->netSubnetmask;
    }

    public function setNetSubnetmask(?string $netSubnetmask): self
    {
        $this->netSubnetmask = $netSubnetmask;

        return $this;
    }

    public function getNetDnsServer(): ?string
    {
        return $this->netDnsServer;
    }

    public function setNetDnsServer(?string $netDnsServer): self
    {
        $this->netDnsServer = $netDnsServer;

        return $this;
    }

    public function getNetMac(): ?string
    {
        return $this->netMac;
    }

    public function setNetMac(?string $netMac): self
    {
        $this->netMac = $netMac;

        return $this;
    }

    public function getNetWebserver(): ?int
    {
        return $this->netWebserver;
    }

    public function setNetWebserver(?int $netWebserver): self
    {
        $this->netWebserver = $netWebserver;

        return $this;
    }

    public function getNetWifiConfig(): ?int
    {
        return $this->netWifiConfig;
    }

    public function setNetWifiConfig(?int $netWifiConfig): self
    {
        $this->netWifiConfig = $netWifiConfig;

        return $this;
    }

    public function getMqttHost(): ?string
    {
        return $this->mqttHost;
    }

    public function setMqttHost(?string $mqttHost): self
    {
        $this->mqttHost = $mqttHost;

        return $this;
    }

    public function getMqttPort(): ?int
    {
        return $this->mqttPort;
    }

    public function setMqttPort(?int $mqttPort): self
    {
        $this->mqttPort = $mqttPort;

        return $this;
    }

    public function getMqttClientMask(): ?string
    {
        return $this->mqttClientMask;
    }

    public function setMqttClientMask(?string $mqttClientMask): self
    {
        $this->mqttClientMask = $mqttClientMask;

        return $this;
    }

    public function getMqttClient(): ?string
    {
        return $this->mqttClient;
    }

    public function setMqttClient(?string $mqttClient): self
    {
        $this->mqttClient = $mqttClient;

        return $this;
    }

    public function getMqttUser(): ?string
    {
        return $this->mqttUser;
    }

    public function setMqttUser(?string $mqttUser): self
    {
        $this->mqttUser = $mqttUser;

        return $this;
    }

    public function getMqttType(): ?int
    {
        return $this->mqttType;
    }

    public function setMqttType(?int $mqttType): self
    {
        $this->mqttType = $mqttType;

        return $this;
    }

    public function getStsTime(): ?string
    {
        return $this->stsTime;
    }

    public function setStsTime(?string $stsTime): self
    {
        $this->stsTime = $stsTime;

        return $this;
    }

    public function getStsUptime(): ?string
    {
        return $this->stsUptime;
    }

    public function setStsUptime(?string $stsUptime): self
    {
        $this->stsUptime = $stsUptime;

        return $this;
    }

    public function getStsVcc(): ?float
    {
        return $this->stsVcc;
    }

    public function setStsVcc(?float $stsVcc): self
    {
        $this->stsVcc = $stsVcc;

        return $this;
    }

    public function getStsPower(): ?int
    {
        return $this->stsPower;
    }

    public function setStsPower(?int $stsPower): self
    {
        $this->stsPower = $stsPower;

        return $this;
    }

    public function getWifiAp(): ?int
    {
        return $this->wifiAp;
    }

    public function setWifiAp(?int $wifiAp): self
    {
        $this->wifiAp = $wifiAp;

        return $this;
    }

    public function getWifiSsid(): ?string
    {
        return $this->wifiSsid;
    }

    public function setWifiSsid(?string $wifiSsid): self
    {
        $this->wifiSsid = $wifiSsid;

        return $this;
    }

    public function getWifiRssi(): ?int
    {
        return $this->wifiRssi;
    }

    public function setWifiRssi(?int $wifiRssi): self
    {
        $this->wifiRssi = $wifiRssi;

        return $this;
    }

    public function getWifiApMac(): ?string
    {
        return $this->wifiApMac;
    }

    public function setWifiApMac(?string $wifiApMac): self
    {
        $this->wifiApMac = $wifiApMac;

        return $this;
    }

    public function getStsDimmer(): ?int
    {
        return $this->stsDimmer;
    }

    public function setStsDimmer(?int $stsDimmer): self
    {
        $this->stsDimmer = $stsDimmer;

        return $this;
    }

    public function getStsColor(): ?string
    {
        return $this->stsColor;
    }

    public function setStsColor(?string $stsColor): self
    {
        $this->stsColor = $stsColor;

        return $this;
    }

    public function getStsHSBColor(): ?string
    {
        return $this->stsHSBColor;
    }

    public function setStsHSBColor(?string $stsHSBColor): self
    {
        $this->stsHSBColor = $stsHSBColor;

        return $this;
    }

    public function getStsCt(): ?int
    {
        return $this->stsCt;
    }

    public function setStsCt(?int $stsCt): self
    {
        $this->stsCt = $stsCt;

        return $this;
    }

    public function getStsScheme(): ?int
    {
        return $this->stsScheme;
    }

    public function setStsScheme(?int $stsScheme): self
    {
        $this->stsScheme = $stsScheme;

        return $this;
    }

    public function getStsFade(): ?int
    {
        return $this->stsFade;
    }

    public function setStsFade(?int $stsFade): self
    {
        $this->stsFade = $stsFade;

        return $this;
    }

    public function getStsSpeed(): ?int
    {
        return $this->stsSpeed;
    }

    public function setStsSpeed(?int $stsSpeed): self
    {
        $this->stsSpeed = $stsSpeed;

        return $this;
    }

    public function getStsLedTable(): ?bool
    {
        return $this->stsLedTable;
    }

    public function setStsLedTable(?bool $stsLedTable): self
    {
        $this->stsLedTable = $stsLedTable;

        return $this;
    }

    public function getVisible(): ?int
    {
        return $this->visible;
    }

    public function setVisible(int $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * @return Collection|DeviceGroup[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(DeviceGroup $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addDevice($this);
        }

        return $this;
    }

    public function removeGroup(DeviceGroup $group): self
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
            $group->removeDevice($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
