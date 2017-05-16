<?php
/**
 * Created by PhpStorm.
 * User: hpenny
 * Date: 10/05/17
 * Time: 5:05 PM
 */

namespace Hmp\KumaExtraBundle\Form\DataTransformer;


use Kunstmaan\MediaBundle\Entity\Media;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadToMediaTransformer implements DataTransformerInterface
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function transform($media)
    {
        return $media;
    }

    public function reverseTransform($uploadedFile)
    {
        /** @var UploadedFile $uploadedFile */
        if($uploadedFile) {
//            $media = $this->container->get('kunstmaan_media.media_creator_service')->createFile($uploadedFile->getRealPath(), 1);
//            return $media;
            $mediaManager = $this->container->get('kunstmaan_media.media_manager');
            $handler      = $mediaManager->getHandlerForType('file');
            $media        = new Media();
            $helper       = $handler->getFormHelper($media);

            $helper->setFile($uploadedFile);
            $media = $helper->getMedia();
            return $media;

        }
        return $uploadedFile;
    }
}