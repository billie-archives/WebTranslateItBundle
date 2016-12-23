[![Build Status](https://travis-ci.org/ozean12/WebTranslateItBundle.svg?branch=master)](https://travis-ci.org/ozean12/WebTranslateItBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ozean12/WebTranslateItBundle/badges/quality-score.png?b=add-travis)](https://scrutinizer-ci.com/g/ozean12/WebTranslateItBundle/?branch=add-travis)

[![Latest Stable Version](https://poser.pugx.org/ozean12/webtranslateit/v/stable.png)](https://packagist.org/packages/ozean12/webtranslateit)
[![Total Downloads](https://poser.pugx.org/ozean12/webtranslateit/downloads.png)](https://packagist.org/packages/ozean12/webtranslateit)

# Ozean12WebTranslateItBundle
A Symfony 2 / Symfony 3 bundle which allows you to integrate the [WebTranslateIt](https://webtranslateit.com) translation service.
## Installation
##### 1. Require the bundle:
```bash
composer require ozean12/webtranslateit
```
##### 2. Set it up:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(), // if not already enabled
        new EightPoints\Bundle\GuzzleBundle\GuzzleBundle(), // if not already enabled
        new JMS\SerializerBundle\JMSSerializerBundle(), // if not already enabled
        new Ozean12\WebTranslateItBundle\Ozean12WebTranslateItBundle(),
    ];
    
    // ...
}
```

```yaml
# app/config/config.yml

ozean12_web_translate_it:
  # your project read key
  read_key: "%webtranslateit_read_key%"
  
  # path to where download your translations,
  # ex: '%kernel.root_dir%/../src/Acme/DemoBundle/Resources/translations'
  pull_path: "%webtranslateit_pull_path%" 

```
## Usage
To update the translations, run:
- For Symfony < 2.8: `app/console ozean12:webtranslateit:pull`
- For Symfony 2.8 / 3: `bin/console ozean12:webtranslateit:pull`
- Or a shorthand notation: `bin/console o:w:p`

> NB: To check the command progress, add `-v` modifier (`app/console o:w:p -v` or `bin/console o:w:p -v`)

## Credits
[Ozean12](http://ozean12.com)
