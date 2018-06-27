<?php

namespace App\Controller;

use App\Utils\GithubHelper;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DownloadController extends Controller
{
    /**
     * @var GithubHelper
     */
    protected $githubHelper;

    /**
     * @var Filesystem
     */
    protected $filesytem;

    /**
     * @Route("/download/{filename}", requirements={"filename" = "sonoff-(minimal|[A-Z]{2}).bin"}, name="download")
     *
     * @param string $filename
     *
     * @return BinaryFileResponse
     */
    public function index(string $filename): BinaryFileResponse
    {
        $filePath = $this->githubHelper->getFilePath($filename);
        if (!$this->filesytem->exists($filePath)) {
            throw $this->createNotFoundException(sprintf('File %s does not exists', $filename));
        }

        return $this->file($filePath, $filename);
    }

    /**
     * @required
     *
     * @param GithubHelper $githubHelper
     *
     * @return DownloadController
     */
    public function setGithubHelper(GithubHelper $githubHelper): DownloadController
    {
        $this->githubHelper = $githubHelper;

        return $this;
    }

    /**
     * @required
     *
     * @param Filesystem $filesytem
     *
     * @return DownloadController
     */
    public function setFilesytem(Filesystem $filesytem): DownloadController
    {
        $this->filesytem = $filesytem;

        return $this;
    }
}
