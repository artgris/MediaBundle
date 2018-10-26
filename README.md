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
        
```twig
{# Font Awesome #}
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" href="{{ asset('bundles/artgrismedia/css/media.css') }}">
<link rel="stylesheet" href="{{ asset('bundles/artgrisfilemanager/libs/blueimp-file-upload/css/jquery.fileupload.css') }}">
<link rel="stylesheet" href="{{ asset('bundles/artgrismedia/libs/cropperjs-1.4.1/cropper.min.css') }}">
```


- JS (**requires [jQuery](https://jquery.com/), [ninsuo/symfony-collection](https://github.com/ninsuo/symfony-collection) and [jQuery UI](https://jqueryui.com/)**):

```twig
{# Import jQuery: #}
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

{# Import fengyuanchen/cropper (included within this bundle): #}
<script src="/bundles/artgrismedia/libs/cropperjs-1.4.1/cropper.min.js></script>

{# Then the default bundle's JavaScript: #}
{% include '@ArtgrisMedia/assets/include_js.html.twig' %}
```

- In `routing.yml`, you will need to import the Ajax route:
```yaml  
 artgris_media:
     resource: "@ArtgrisMediaBundle/Resources/config/routing.yml"
```

### Usage
    
In an entity, add the path attributes as string.
You can also use doctrine's types such as `simple_array`, `array`, `json` for collections.
    
```php

use Artgris\Bundle\MediaBundle\Form\Validator\Constraint as MediaAssert; // optionnal, to force image files

// ...

/**
 * @var string
 * @ORM\Column(type="string")
 * @Assert\NotNull()
 */
private $image;

/**
 * @var Collection|string[]
 * @ORM\Column(type="simple_array", nullable=true)
 * @MediaAssert\Image()
 */
private $gallery;
```
    
Then, use a form builder and assigne the `MediaType` class for a single file, or the `MediaCollectionType` for multiple files.

```php
use Artgris\Bundle\MediaBundle\Form\Type\MediaType;
use Artgris\Bundle\MediaBundle\Form\Type\MediaCollectionType;

// ... 

$builder
    ->add('image', MediaType::class, [
        'conf' => 'default'
    ])
    ->add('gallery', MediaCollectionType::class, [
        'conf' => 'default'
    ]);
```
    
### Options:

**MediaType:**
- `'conf' => 'yourconf'` (**required**) specifies a configuration defined in the FileManager. For more informations about media configurations, [refer to FileManagerBundle's documentation](https://github.com/artgris/FileManagerBundle#add-following-configuration-)
- `'readonly' => false` prevents the user from manually changing the path (it only adds a "readonly" attribute to the corresponding HTML input)
- `'allow_crop' => true` allows the user to edit the image using [fengyuanchen/cropper](https://github.com/fengyuanchen/cropper)
- `'crop_options' => array` if `allow_crop` is set to `true`, allows to specify extra crop options. The default options:

```php
'crop_options' => [
    'display_crop_data' => true,    // will display crop box informations (x, y, width, height, and ratio if there is one)
    'allow_flip' => true,           // allows to flip the image vertically and horizontally
    'allow_rotation' => true,       // allows to rotate the image (90 degrees)
    'ratio' => false                // force a crop ratio. E.g 16/9
],
```

**MediaCollectionType:**

- `'conf' => 'yourconf'` (**required**) specifies a configuration defined in the FileManager. For more informations about media configurations, [refer to FileManagerBundle's documentation](https://github.com/artgris/FileManagerBundle#add-following-configuration-)

Some [ninsuo/symfony-collection](https://github.com/ninsuo/symfony-collection)'s options are available directly:
- `'min' => 0`
- `'max' => 100`
- `'init_with_n_elements' => 1`
- `'add_at_the_end' => true`

Like regular collections, you can edit entries options, i.e to enable alts:

```php
'entry_options' => [
    'display_file_manager' => false
]
```

### Gregwar Image Bundle Integration

This bundle relies on [Gregwar/ImageBundle](https://github.com/Gregwar/ImageBundle) to crop, mirror and scale images.

If you need to manually crop image in twig (if they are too large for example), instead of using the `image` and `web_image` functions, you should `gImage`, which works the same as `image` but improves compatibility.

E.g:

```twig
{{ gImage(news.image).zoomCrop(100, 100) }}
```
