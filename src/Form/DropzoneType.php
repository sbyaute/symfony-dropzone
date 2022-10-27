<?php

namespace Sbyaute\SymfonyDropzone\Form;

use Doctrine\ORM\EntityManagerInterface;
use Sbyaute\SymfonyDropzone\Transformer\DropzoneTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DropzoneType extends AbstractType
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    final public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        if (false === $options['multiple']) {
            $builder->add(
                'dropzone',
                EntityType::class,
                [
                    'class' => $options['class'],
                    'label' => false,
                    'required' => $options['required'],
                    'attr' => ['style' => 'display: none;']
                ]
            );
        } else {
            $builder->add(
                'dropzone',
                CollectionType::class,
                [
                    'entry_type' => HiddenType::class,
                    'label' => false,
                    'allow_add' => true,
                    'allow_delete' => true
                ]
            );
        }

        $builder->addModelTransformer(new DropzoneTransformer($this->entityManager, $options));

        parent::buildForm($builder, $options);
    }

    /**
     * @param OptionsResolver $resolver
     */
    final public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => null,
            'required' => true,
            'choice_src' => 'src',
            'multiple' => true,
            'maxFiles' => 1,
            'maxFilesize' => null,
            'uploadHandler' => null,
            'removeHandler' => null,
            'downloadHandler' => null,
            'uploadHandlerMethod' => "POST",
            'removeHandlerMethod' => "DELETE",
            'withCredentials' => 0,
            'thumbnailWidth' => 120,
            'thumbnailHeight' => 120,
            'thumbnailMethod' => "crop",
            'resizeWidth' => null,
            'resizeHeight' => null,
            'resizeMimeType' => null,
            'resizeMethod' => "contain",
            'filesizeBase' => 1024,
            'headers' => [],
            'formData' => [],
            'ignoreHiddenFiles' => true,
            'acceptedFiles' => null,
            'autoProcessQueue' => true,
            'autoQueue' => true,
            'addRemoveLinks' => true,
            'previewsContainer' => null,
            'disablePreviews' => null,
            'dictDefaultMessage' => "Drop files here to upload",
            'dictRemoveFile' => 'Supprimer', //"Remove file",
            'dictRemoveFileConfirmation' => null,
            'dictFallbackMessage' => "Your browser does not support drag'n'drop file uploads.",
            'dictFallbackText' => "Please use the fallback form below to upload your files like in the olden days.",
            'dictFileTooBig' => 'Fichier trop volumineux ({{filesize}}MiB). Taille max: {{maxFilesize}}MiB.', // "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.",
            'dictInvalidFileType' => 'Vous ne pouvez pas charger de fichier de ce type.', // "You can't upload files of this type.",
            'dictResponseError' => 'Le serveur a retourné le status code {{statusCode}}.', // "Server responded with {{statusCode}} code.",
            'dictCancelUpload' => 'Annuler', // "Cancel upload",
            'dictMaxFilesExceeded' => 'Vous ne pouvez pas charger plus de {{maxFiles}} fichiers.', // "You can not upload any more files.",
        ]);

        parent::configureOptions($resolver);
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    final public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        /** @var FormView $f */

        $f = $view->vars['form'];

        $view->vars['formName'] = $f->parent->vars['name'];
        $view->vars['id'] = $this->dashesToCamelCase($f->vars['id']);
        $view->vars['name'] = $f->vars['name'];
        $view->vars['uploadHandler'] = $options['uploadHandler'];
        $view->vars['removeHandler'] = $options['removeHandler'];
        $view->vars['downloadHandler'] = $options['downloadHandler'];

        $view->vars['files'] = null;

        if (false == $options['multiple'] and $file = $form->getData()) {
            $view->vars['files'][] = $file;
        } elseif ($form->getData()) {
            $view->vars['files'] = $form->getData();
        }


        $view->vars['class'] = $options['class'];
        $view->vars['required'] = $options['required'];
        $view->vars['multiple'] = $options['multiple'];
        $view->vars['maxFilesize'] = $options['maxFilesize'];
        $view->vars['maxFiles'] = $options['maxFiles'];
        $view->vars['uploadHandlerMethod'] = $options['uploadHandlerMethod'];
        $view->vars['removeHandlerMethod'] = $options['removeHandlerMethod'];
        $view->vars['formData'] = $options['formData'];
        $view->vars['choice_src'] = $options["choice_src"];
        $view->vars['withCredentials'] = $options['withCredentials'];
        $view->vars['thumbnailWidth'] = $options['thumbnailWidth'];
        $view->vars['thumbnailHeight'] = $options['thumbnailHeight'];
        $view->vars['thumbnailMethod'] = $options['thumbnailMethod'];
        $view->vars['resizeWidth'] = $options['resizeWidth'];
        $view->vars['resizeHeight'] = $options['resizeHeight'];
        $view->vars['resizeMimeType'] = $options['resizeMimeType'];
        $view->vars['resizeMethod'] = $options['resizeMethod'];
        $view->vars['filesizeBase'] = $options['filesizeBase'];
        $view->vars['headers'] = $options['headers'];
        $view->vars['ignoreHiddenFiles'] = $options['ignoreHiddenFiles'];
        $view->vars['acceptedFiles'] = $options['acceptedFiles'];
        $view->vars['autoProcessQueue'] = $options['autoProcessQueue'];
        $view->vars['autoQueue'] = $options['autoQueue'];
        $view->vars['addRemoveLinks'] = $options['addRemoveLinks'];
        $view->vars['previewsContainer'] = $options['previewsContainer'];
        $view->vars['disablePreviews'] = $options['disablePreviews'];
        $view->vars['dictDefaultMessage'] = $options['dictDefaultMessage'];
        $view->vars['dictRemoveFile'] = $options['dictRemoveFile'];
        $view->vars['dictRemoveFileConfirmation'] = $options['dictRemoveFileConfirmation'];
        $view->vars['dictFallbackMessage'] = $options['dictFallbackMessage'];
        $view->vars['dictFallbackText'] = $options['dictFallbackText'];
        $view->vars['dictFileTooBig'] = $options['dictFileTooBig'];
        $view->vars['dictInvalidFileType'] = $options['dictInvalidFileType'];
        $view->vars['dictResponseError'] = $options['dictResponseError'];
        $view->vars['dictCancelUpload'] = $options['dictCancelUpload'];
        $view->vars['dictMaxFilesExceeded'] = $options['dictMaxFilesExceeded'];

        parent::buildView($view, $form, $options);
    }

    private function dashesToCamelCase($string, $capitalizeFirstCharacter = false)
    {

        $str = str_replace('_', '', ucwords($string, '_'));

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }

}
