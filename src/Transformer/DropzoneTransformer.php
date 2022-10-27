<?php
namespace Sbyaute\SymfonyDropzone\Transformer;

use App\Entity\File;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DropzoneTransformer implements DataTransformerInterface
{


    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, array $options)
    {
        $this->entityManager = $entityManager;
        $this->options = $options;
    }

    public function transform($value)
    {

    }

    public function reverseTransform($value)
    {

        if(!isset($value['dropzone'])){
            return null;
        }

        if ($this->options['maxFiles'] === 1) {
            return $this->entityManager->getRepository($this->options['class'])->findOneBy(['id' => $value['dropzone']]);
        }

        return $this->entityManager->getRepository($this->options['class'])->findBy(['id' => $value['dropzone']]);
    }
}
