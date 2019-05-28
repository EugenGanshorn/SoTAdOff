<?php

namespace App\Utils;

use GuzzleHttp\Client;
use Symfony\Component\Filesystem\Filesystem;

class GithubHelper
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var string
     */
    protected $projectDir;

    /**
     * @param string $assetName
     *
     * @return string
     */
    public function getFilePath(string $assetName): string
    {
        return sprintf(
            '%s/%s',
            $this->getFirmwareDir(),
            $assetName
        );
    }

    /**
     * @param string $language
     */
    public function downloadFirmware(string $language): void
    {
        $result = $this->getLatest();

        $neededFiles = ['sonoff-minimal.bin', sprintf('sonoff-%s.bin', strtolower($language))];
        foreach ($result['assets'] as $asset) {
            if (\in_array(strtolower($asset['name']), $neededFiles, true)) {
                $this->downloadFile($asset['name'], $asset['browser_download_url']);
            }
        }
    }

    /**
     * @return array
     */
    public function getLatest(): array
    {
        $response = $this->client->get('https://api.github.com/repos/arendst/Sonoff-Tasmota/releases/latest');

        return \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
    }

    protected function createFirmwareDir(): void
    {
        $this->filesystem->mkdir($this->getFirmwareDir());
    }

    /**
     * @return string
     */
    protected function getFirmwareDir(): string
    {
        return sprintf(
            '%s/var/firmware',
            $this->projectDir
        );
    }

    /**
     * @param string $assetName
     * @param string $downloadUrl
     */
    protected function downloadFile(string $assetName, string $downloadUrl): void
    {
        $filePath = $this->getFilePath($assetName);
        $this->createFirmwareDir();
        $this->client->get($downloadUrl, ['sink' => $filePath]);
    }

    /**
     * @required
     *
     * @param Client $client
     *
     * @return GithubHelper
     */
    public function setClient(Client $client): GithubHelper
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @required
     *
     * @param Filesystem $filesystem
     *
     * @return GithubHelper
     */
    public function setFilesystem(Filesystem $filesystem): GithubHelper
    {
        $this->filesystem = $filesystem;

        return $this;
    }

    /**
     * @required
     *
     * @param string $projectDir
     *
     * @return GithubHelper
     */
    public function setProjectDir(string $projectDir): GithubHelper
    {
        $this->projectDir = $projectDir;

        return $this;
    }
}
