Bono Localization Package

#Activate Localization Package
Put the Provider to your `bono.providers` configuration:

```php
'bono.providers' => array(
    '\\Norm\\Provider\\NormProvider',
    '\\Bono\\Lang\\Provider\\LangProvider',
),
```

#Configuration
Basic configuration is:

```php
array(
    'driver' => '\\Bono\\Lang\\Driver\\FileDriver',
    'lang' => 'en',
    'debug' => true
),
```

- `driver` is a class that responsible to get all list of registered Language and get words for each language available in list
- `lang` is active language you want to use to translate
- `debug` when you set this config to `false`, and you get nothing from translation, the result is empty string, but if it sets to `true`, you get some debug keyword

#Basic FileDriver Dictionary Knowledge
You can change the list of your dictionary, by rewrite your config to something like this:

```php
array(
    'lang' => array(
        'driver' => '\\Bono\\Lang\\Driver\\FileDriver',
        'lang' => 'en',
        'debug' => true,
        'lang.path' => 'lang',
    ),
)
```

So in your **root project**, you should create a `lang` folder , and put your dictionary inside that folder.

```
{{ ROOT }}
├── composer.json
└── lang
    ├── en
    │   └── list.php
    │   └── anotherList.php
    └── id
        └── list.php
        └── extraList.php
```

When you want Spanish language exists in your repository, make an `es` folder in `lang` folder. The structure of your dictionary should be like this:

```php
return array(
    'full_name'  => 'Full Name',
    'birth_date' => 'Birth Date',
    'message'    => 'Hello',
    'flash'      => array(
        'fail'    => 'Sorry, app is currently fucked up', // nested content
        'success' => 'Hurray, that is that I am talking about', // yet another nested content
    ),
);
```

#Example dictionary
Let's say this is our dictionary

```php
return array(
    'full_name'  => 'Full Name',
    'birth_date' => 'Birth Date',
    'message'    => 'Hello',
    'flash'      => array(
        'fail'    => 'Sorry, app is currently fucked up', // nested content
        'success' => 'Hurray, that is that I am talking about', // yet another nested content
    ),
);
```

#Usage (Based on example dictionary)
You can access translation from application container context, such as:

```php
$app        = \Bono\App::getInstance();
$translator = $app->translator;
$word       = $translator->translate('message');
echo $word; // output => 'Hello'
```

Or via alias function:

```php
$word = t('message'); // 't' is alias for 'translate' method in $translator
echo $word; // output => 'Hello'
```

You can append some parameter to your language. But, first, you have to confirm that your line, accept the parameter by change it to

```php
// List of English dictionary
array(
    // ... snip
    'message' => 'Hello, :name',
    // ... snip
);
```

So you can append parameter to your dictionary by:

```php
$app        = \Bono\App::getInstance();
$translator = $app->translator;
$word       = $translator->translate('message', array('name' => 'Alfa'));
echo $word; // output => 'Hello, Alfa'
```

When you want to access your nested dictionary:

```php
$app        = \Bono\App::getInstance();
$translator = $app->translator;
$flash      = $translator->translate('flash.fail');
echo $flash; // output => 'Sorry, app is currently fucked up'
```

#Some other method you can access

```php
$app        = \Bono\App::getInstance();
$translator = $app->translator;

// Get current active driver
$translator->getDriver();

// Get current active language
$translator->getLanguage();

// Set Spanish to our current active language
$translator->setLanguage('es');

// Determine if we have Spanish language in our repository
$translator->hasLanguage('es');
```
