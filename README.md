# Bono Localization Package
Want some translation in your Bono-Based Application? Use this component. Code like a boss.

# Installation
Add this package to `composer.json` require file:

```lang-js
"require": {
    "krisanalfa/bono-lang": "dev-master",
},
```

# Activate Localization Package
Put the Provider to your `bono.providers` configuration:

```lang-php
'bono.providers' => array(
    '\\Bono\\Lang\\Provider\\LangProvider',
),
```

# Configuration
Basic configuration is:

```lang-php
'bono.providers' => array(
    '\\Bono\\Lang\\Provider\\LangProvider' => array(
        'driver' => '\\Bono\\Lang\\Driver\\FileDriver',
        'lang'   => 'en',
        'debug'  => true
    ),
),
```

- `driver` is a class that responsible to get all list of registered Language and get words for each language available in list
- `lang` is active language you want to use to translate, you can use a `closure` to set the default language, see example below
- `debug` when you set this config to `false`, and you get nothing from translation, the result is empty string, but if it sets to `true`, you get some debug keyword

Using closure to determine which language is set to be default one

```lang-php
array(
    'driver' => '\\Bono\\Lang\\Driver\\FileDriver',
    'lang' => function() {
        return $_SESSION['user']['config']['lang'];
    },
    'debug' => true
),
```

# Basic FileDriver Dictionary Knowledge
You can change the location of your dictionary, by add `lang.path` in configuration files:

```lang-php
'bono.providers' => array(
    '\\Bono\\Lang\\Provider\\LangProvider' => array(
        'driver'    => '\\Bono\\Lang\\Driver\\FileDriver',
        'lang'      => 'en',
        'debug'     => true,
        'lang.path' => 'lang'
    ),
),
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

```
{{ ROOT }}
├── composer.json
└── lang
    ├── en
    │   └── list.php
    │   └── anotherList.php
    ├── id
    │   └── list.php
    │   └── extraList.php
    └── es
        └── list.php
        └── spanishList.php
```

```lang-php
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

```lang-php
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

```lang-php
$app        = \Bono\App::getInstance();
$translator = $app->translator;
$word       = $translator->translate('message');
echo $word; // output => 'Hello'
```

---

Or via alias function:

```lang-php
$word = t('message'); // 't' is an alias for 'translate' method in $translator
echo $word; // output => 'Hello'
```

---

You can append some parameter to your language. But, first, you have to confirm that your line, accept the parameter by change it to

```lang-php
// List of English dictionary
array(
    // ... snip
    'message' => 'Hello, :name',
    // ... snip
);
```

So you can append parameter to your dictionary by:

```lang-php
$app        = \Bono\App::getInstance();
$translator = $app->translator;
$word       = $translator->translate('message', array('name' => 'Alfa'));
echo $word; // output => 'Hello, Alfa'
```

---

The third argument in `translate` method is option to give translator a default translation if there's no translation in dictionary:


```lang-php
$app        = \Bono\App::getInstance();
$translator = $app->translator;
$word       = $translator->translate('n00p', array(), 'Default one');
echo $word; // output => 'Default one.'
```

---

When you want to access your nested dictionary:

```lang-php
$app        = \Bono\App::getInstance();
$translator = $app->translator;
$flash      = $translator->translate('flash.fail');
echo $flash; // output => 'Sorry, app is currently fucked up'
```

---

This package also support for choice if there's multiple message in a key, something like this:

```lang-php
// List of English dictionary
array(
    // ... snip
    'options' => 'Sagara|Xinix|Solusitama',
    // ... snip
);
```

If you want to take `Sagara` in `options` key, you can access them by:

```lang-php
$app        = \Bono\App::getInstance();
$translator = $app->translator;
$word       = $translator->choice('options', 1);
echo $word; // output => 'Sagara'
```

---

You can also put any placeholder in `choice`, something like this:

```lang-php
// List of English dictionary
array(
    // ... snip
    'options' => 'Sagara|Xinix :string|Solusitama',
    // ... snip
);
```

```lang-php
$app        = \Bono\App::getInstance();
$translator = $app->translator;
$word       = $translator->choice('options', 2, array('string' => 'Technology'));
echo $word; // output => 'Xinix Technology'
```

---

The `count` variable is automatically appended in `choice` method:

```lang-php
// List of English dictionary
array(
    // ... snip
    'options' => 'Sagara|Xinix :string|Solusitama :count',
    // ... snip
);
```

```lang-php
$app        = \Bono\App::getInstance();
$translator = $app->translator;
$word       = $translator->choice('options', 3);
echo $word; // output => 'Solusitama 3'
```

# Some other method you can access

```lang-php
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

# LICENSE
```
MIT LICENSE

Copyright (c) 2014 Krisan Alfa Timur (PT. Sagara Xinix Solusitama)

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
```
