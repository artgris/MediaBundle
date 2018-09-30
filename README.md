## artgris/MediaBundle - Easier Symfony Media Management

### Prerequisites

- [artgris/FileManagerBundle](https://github.com/artgris/FileManagerBundle#add-following-configuration-)
- Assets: 
    - CSS: [bootstrap](http://getbootstrap.com/) and [Font Awesome](http://fontawesome.io/)
    - JS: [jQuery](https://jquery.com/), [ninsuo/symfony-collection](https://github.com/ninsuo/symfony-collection) and [jQuery UI](https://jqueryui.com/)

![demo-gif](https://github.com/artgris/MediaBundle/raw/master/demo.gif)

### Getting Started

- Download the files:
        
        composer require artgris/media-bundle

- In `AppKernel.php` add the bundle:
        
        new Artgris\Bundle\MediaBundle\ArtgrisMediaBundle()
        
- Then, run the following command:
     
        php bin/console assets:install 
        
- In your twig template, you will then need to import the required assets:
    
    - CSS (**requires [bootstrap](http://getbootstrap.com/) and [Font Awesome](http://fontawesome.io/)**):
        
            {# Font Awesome #}
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

            <link rel="stylesheet" href="{{ asset('bundles/artgrismedia/css/media.css') }}">
            <link rel="stylesheet" href="{{ asset('bundles/artgrisfilemanager/libs/blueimp-file-upload/css/jquery.fileupload.css') }}">
            <link rel="stylesheet" href="{{ asset('bundles/artgrismedia/libs/cropperjs-1.4.1/cropper.min.css') }}">


    - JS (**requires [jQuery](https://jquery.com/), [ninsuo/symfony-collection](https://github.com/ninsuo/symfony-collection) and [jQuery UI](https://jqueryui.com/)**):
    
            {# Import jQuery: #}
            <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

            {# Import fengyuanchen/cropper (included within this bundle): #}
            <script src="/bundles/artgrismedia/libs/cropperjs-1.4.1/cropper.min.js></script>

            {# Then the default bundle's JavaScript: #}
            {% include '@ArtgrisMedia/assets/include_js.html.twig' %}

- In `routing.yml`, you will need to import the Ajax route:
        
         artgris_media:
             resource: "@ArtgrisMediaBundle/Resources/config/routing.yml"
             
- In config.yml, add the following Doctrine types:

        doctrine:
            dbal:
                types:
                    media: Artgris\Bundle\MediaBundle\Type\MediaType
                    media_collection: Artgris\Bundle\MediaBundle\Type\MediaCollectionType
                    
### Usage
    
In an entity, you can now add the new `media` types, i.e:
    
    /**
     * @var Media
     * @ORM\Column(type="media")
     */
    private $image;
    
    /**
     * @var ArrayCollection|Media[]
     * @ORM\Column(type="media_collection")
     */
    private $imageCollection;

    public function __construct()
    {
        $this->imageCollection = new ArrayCollection();
    }
    
You can bound these fields to a form using its corresponding type:

    use Artgris\Bundle\MediaBundle\Form\Type\MediaType;
    use Artgris\Bundle\MediaBundle\Form\Type\MediaCollectionType;
    
    // ... 
    
    $builder
        ->add('image', MediaType::class)
        ->add('imageCollection', MediaCollectionType::class);
    
### Options:

**MediaType:**
- `'conf' => 'yourconf'` (**required**) specifies a configuration defined in the FileManager. For more informations about media configurations, [refer to FileManagerBundle's documentation](https://github.com/artgris/FileManagerBundle#add-following-configuration-)
- `'allow_alt' => false` allows the user to specify an alt
- `'path_readonly' => false` prevents the user from manually changing the path (it only adds a "readonly" attribute to the corresponding HTML input)
- `'allow_crop' => true` allows the user to edit the image using [fengyuanchen/cropper](https://github.com/fengyuanchen/cropper)
- `'crop_options' => array` if `allow_crop` is set to `true`, allows to specify extra crop options. The default options:

        'crop_options' => [
            'display_crop_data' => true,    // will display crop box informations (x, y, width, height, and ratio if there is one)
            'allow_flip' => true,           // allows to flip the image vertically and horizontally
            'allow_rotation' => true,       // allows to rotate the image (90 degrees)
            'ratio' => false                // force a crop ratio. E.g 16/9
        ],

**MediaCollectionType:**

- `'conf' => 'yourconf'` (**required**) specifies a configuration defined in the FileManager. For more informations about media configurations, [refer to FileManagerBundle's documentation](https://github.com/artgris/FileManagerBundle#add-following-configuration-)

Some [ninsuo/symfony-collection](https://github.com/ninsuo/symfony-collection)'s options are available directly:
- `'min' => 0`
- `'max' => 100`
- `'init_with_n_elements' => 1`
- `'add_at_the_end' => true`

Like regular collections, you can edit entries options, i.e to enable alts:
        
    'entry_options' => [
        'allow_alt' => true,
        'display_file_manager' => false
    ]

### About the form HTML theme

Include bootstrap's theme
 
    {% form_theme form ':admin/includes:bootstrap_3_layout.html.twig' %}

To override the widget theme, check `Resources/views/forms/fields.html.twig`.

### Constraints example

    use Artgris\Bundle\MediaBundle\Form\Validator\Constraint as MediaAssert;

    // ...

    /**
     * @var ArrayCollection|Media[]
     * @ORM\Column(type="media_collection")
     * @MediaAssert\Count(min="2")
     * @Assert\All({@MediaAssert\Image()})
     */
    private $images;