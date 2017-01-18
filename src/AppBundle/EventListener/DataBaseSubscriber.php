<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Common\EventSubscriber;
use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\File;
use AppBundle\Services\FileUploadManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class DataBaseSubscriber implements EventSubscriber
{
    private $uploader;
    private $encoder;

    public function __construct(FileUploadManager $uploader, UserPasswordEncoder $encoder)
    {
        $this->uploader = $uploader;
        $this->encoder = $encoder;
    }

    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
            'postLoad',
        );
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Post) {
            $this->uploadFile($entity);
        }

        if ($entity instanceof User) {
            $this->encodePassword($entity);
        }


    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Post) {
            $this->uploadFile($entity);
        }

        if ($entity instanceof User) {
            $this->encodePassword($entity);
        }
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Post) {
            $fileName = $entity->getImage();
            $entity->setImage(new File('images/'.$fileName));
        }


    }

    private function uploadFile($entity)
    {
        $file = $entity->getImage();

        if (!($file instanceof UploadedFile || $file instanceof File )) {
            $entity->setImage('c-3.jpg');
        }else{
            $fileName = $this->uploader->upload($file);
            $entity->setImage($fileName);
        }


    }


    private function encodePassword($entity)
    {
        $password = $entity->getPassword();
        $encoded = $this->encoder->encodePassword($entity, $password);
        $entity->setPassword($encoded);
    }


}
