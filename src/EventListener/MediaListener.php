<?php

namespace App\EventListener;

use App\Entity\Media;
use App\Service\ImageOptimizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class MediaListener
{
    public function __construct(
        private ParameterBagInterface $params,
        private EntityManagerInterface $entityManager,
        private UploaderHelper $helper,
        private ImageOptimizer $imageOptimizer,
    ) {
    }

    public function onVichUploaderPostUpload(Event $event)
    {
        $media = $event->getObject();

        if ($media instanceof Media) {
            $filesystem = new Filesystem();

            $widthList = [
                '100w' => 100,
                '150w' => 150,
                '300w' => 300,
            ];

            foreach ($widthList as $rKey => $rWidth) {
                $rootProjectPath = getcwd();
                $originalFilePath = $this->helper->asset(
                    $media,
                    'originalFile',
                );
                $filePathParts = pathinfo($rootProjectPath . $originalFilePath);
                $tmpStoragePath = $this->params->get('tmp_storage_path');

                $targetFileName = "{$filePathParts['filename']}_{$rKey}.{$filePathParts['extension']}";
                $tmpTargetFilePath = "{$rootProjectPath}{$tmpStoragePath}/{$targetFileName}";

                $filesystem->copy(
                    $rootProjectPath . $originalFilePath,
                    $tmpTargetFilePath,
                    true,
                );

                $this->imageOptimizer->widthResize($tmpTargetFilePath, $rWidth);

                if ($rKey === '100w') {
                    $media->imageFile100w = new UploadedFile(
                        $tmpTargetFilePath,
                        $targetFileName,
                    );
                } elseif ($rKey === '150w') {
                    $media->imageFile150w = new UploadedFile(
                        $tmpTargetFilePath,
                        $targetFileName,
                    );
                } elseif ($rKey === '300w') {
                    $media->imageFile300w = new UploadedFile(
                        $tmpTargetFilePath,
                        $targetFileName,
                    );
                }

                // @unlink($tmpTargetFilePath);
            }
        }
    }
}
