Symfony Kickstarter Edition
===========================

Welcome to the Symfony Kickstarter Edition - a fully-functional Symfony2
application that you can be used as a skeleton for a new applications.

This document contains information on what's required to use this Kickstarter,
what it contains, and how to get set it up.

[![Build Status](https://secure.travis-ci.org/bicpi/symfony-kickstarter.png)](http://travis-ci.org/bicpi/symfony-kickstarter)

### Requirements

* PHP 5.5+
* Some installation instructions assume an Ubuntu based system
* Composer, bower, phpunit, uglifycss, uglifyjs

### Features

* Common Symfony2, Bundles and Git configuration
* Integration and demonstration of 3rd party software:
    * Bower
    * Travis
    * FOSUserBundle
    * KnpPaginatorbundle
    * DoctrineFixturesBundle
    * FOSJsRoutingBundle
    * JMSDiExtraBundle
    * JMSDiSecurityBundle
    * PhpExcel
    * HtmlConverterBundle
    * jQuery
    * jQueryUI
    * holder.js
    * Assetic
    * Bootstrap3
* Concept demos:
    * Multilingual registration form
    * Fixtures
    * Admin area
    * Excel Export
    * Send Emails including automatically generated plain text part
    * User impersonation
    * HTTP cache and ESI
    * Security and Voters
    * Ajax
    * Pagination with sorting
    * Authentication/Authorization
    * User profile
    * Custom Error Pages
    * Functional tests
    * Miscellaneous tricks and helpers

### Install vendors

Install vendors with Composer:

    $ composer install

Enter basic configuration during install process, e.g. database credentials.

### Set permissions

Set correct permissions for cache and log folder using ACLs:

    $ ./fix-perms.sh

### Setup Virtual Host

    $ sudo cp app/config/sample.vhost /etc/apache2/sites-available/acme-symfony-kickstarter.conf
    $ sudo nano /etc/apache2/sites-available/acme-symfony-kickstarter.conf # do custom changes
    $ sudo a2ensite acme-symfony-kickstarter
    $ sudo apache2ctl -t
    $ sudo service apache2 graceful

### Check Config

Check configuration on console:

   $ php app/check.php

Check configuration on webserver via http://symfony-kickstarter.devs/config.php

### Setup Database

    $ app/console doctrine:database:create
    $ app/console doctrine:schema:create
    $ app/console doctrine:fixtures:load

### Assetic File Watcher

If needed, activate Assetic file watcher for development:

    $ app/console assetic:dump --watch --force

Dump your assets for production:

    $ app/console assetic:dump --env=prod
