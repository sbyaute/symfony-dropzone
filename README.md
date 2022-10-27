# Symfony DropzoneType

Étend le composant SymfonyForm. Ajoute le nouveau type de formulaire DropzoneType.
## Installation

`composer require sbyaute/symfony-dropzone`


### Assets
Ajoutez la bibliothèque dropzone à votre projet dans le template
```js
{% block stylesheets %}
    {{ parent() }}
    <link rel="stylesheet" href="{{ asset('assets/dropzone/style/dropzone.css') }}">
    <style>
        .dropzone {
        min-height:25px;
        padding:5px 5px 5px
    }
        .dz-button {
        width:270px;
    }
    </style>
{% endblock %}

{% block javascripts %}
    {{ parent() }}
    <script src="{{ asset('assets/dropzone/js/dropzone-min.js') }}"></script>
{% endblock %}```

## Usage
```php
public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
{ 

    // fichiers is OneToMany
    $builder->add('fichiers', DropzoneType::class, [
        'class' => Fichier::class,
        'maxFilesize' => 8,  // Taille max du fichier en MB
        'maxFiles' => 6,        // Nb max de fichiers téléchargeables
        'uploadHandler' => 'uploadHandler', // route pour le chargement
        'removeHandler' => 'removeHandler', // route pour la suppression
        'downloadHandler' => 'downloadHandler', // route pour le téléchargement
        'previewsContainer' => true,        // Si true, sort la previsu de la dropzone (pas d'image)
        'choice_src' => 'src',              // Champ de l'entité contenant le nom du fichier sur le serveur
        'acceptedFiles' => '',        // Liste des types de fichiers autorisés ('.zip, .doc, .xls')
        'dictDefaultMessage' => 'Cliquez ou glissez/déposez vos fichiers ici ...',
        'dictRemoveFileConfirmation' => 'Etes-vous sûr ?', // active la confirmation de suppression
        'required' => false,
    ]);
}
```



## Exemples route uploadHandler/removeHandler 

```php
/**
     * @Route("/uploadhandler", name="uploadHandler")
     */
    public function uploadhandler(Request $request, ImageUploader $uploader) { 
        $doc = $uploader->upload($request->files->get('file'));  
        $file = new File(); 
        $file->setSrc($doc['src']);
        ...

        $this->getDoctrine()->getManager()->persist($file);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse($file);
    }


    /**
     * @Route("/removeHandler/{id}", name="removeHandler")
     */
    public function removeHandler(Request $request,File $file = null) {
        $this->getDoctrine()->getManager()->remove($file);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(true);
    }

```




## Options

Name | Default | Description  |
--- | --- | --- |
class | null | File Entity
choice_src | "src" | property that contains src
uploadHandler | null | Symfony route name for upload | 
removeHandler | null | Symfony route name for remove | 
multiple | true | Set to false if you have a one to one relationship | 
maxFiles  |  1 | If not null defines how many files this Dropzone handles.   | 
addRemoveLinks  |  true | If true, this will add a link to every file preview to remove or cancel (if already uploading) the file. | 
headers  |  [] | An optional object to send additional headers to the server. Headers is array. Eg:   ['Authorization' => 'Bearer XXXXXX']  |
formData | [] |Additional data that will be sent to FormData. Eg:   ['key' => 'value']  |
uploadHandlerMethod | "POST" | Can be changed to "PUT" if necessary. |
removeHandlerMethod | "DELETE" | Can be changed to "POST" if necessary. |
withCredentials | 0 | Will be set on the XHRequest. | 
thumbnailWidth | 120 | If null, the ratio of the image will be used to calculate it. | 
thumbnailHeight | 120 | The same as thumbnailWidth. If both are null, images will not be resized. | 
thumbnailMethod | "crop" | How the images should be scaled down in case both, thumbnailWidth and thumbnailHeight are provided. Can be either contain or crop. | 
resizeWidth | null  | If set, images will be resized to these dimensions before being **uploaded**. If only one, resizeWidth **or** resizeHeight is provided, the original aspect ratio of the file will be preserved.  | 
resizeHeight | null  |  See resizeWidth.  | 
resizeMimeType | null  |  The mime type of the resized image (before it gets uploaded to the server). If null the original mime type will be used. To force jpeg, for example, use image/jpeg. See resizeWidth for more information.  | 
resizeMethod |  "contain" |  How the images should be scaled down in case both, resizeWidth and resizeHeight are provided. Can be either contain or crop. | 
filesizeBase  |  1024 |  -  |
ignoreHiddenFiles  |  true |  Whether hidden files in directories should be ignored. | 
acceptedFiles  |  null |  Eg.: image/*,application/pdf,.psd | 
autoProcessQueue  |  true |  If false, files will be added to the queue but the queue will not be processed automatically. This can be useful if you need some additional user input before sending files (or if you want want all files sent at once). If you're ready to send the file simply call myDropzone.processQueue(). | 
autoQueue  |  true |  If false, files added to the dropzone will not be queued by default. You'll have to call enqueueFile(file) manually. |
previewsContainer  |  null | Defines where to display the file previews – if null the Dropzone element itself is used. Can be a CSS selector. | 
 

## License MIT