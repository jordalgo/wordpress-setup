A Wordpress Website

## Setup

This setup assumes that you are familiar the following:
* [Git](https://git-scm.com/)
* [Composer](https://getcomposer.org/)
* [Wpackagist](http://wpackagist.org/)
* [NPM](https://www.npmjs.com/)
* [Gulp](http://gulpjs.com/)
* [Less](http://lesscss.org/)

[Here are instructions](http://jason.pureconcepts.net/2012/10/install-apache-php-mysql-mac-os-x/) on setting up Apache, MySQL and PHP on a local machine

#### Clone this Repo and Setup Remotes

```bash
git clone git@bitbucket.org:jordalgo/wordpress_site.git my-new-site
git remote add upstream git@bitbucket.org:jordalgo/wordpress_site.git
git remote set-url --push upstream no_push
git remote set-url origin git@bitbucket.org:jordalgo/my-new-site.git
git add -A
git commit -m "first commit"
git push -u origin --all
```

#### Download Wordpress and Plugins

```bash
php composer.phar install
```

OR

```bash
php composer.phar update
```

#### Download gulp, js, and css dependencies.

```bash
npm install
```

#### Set up a local Mysql DB

#### Add an entry in '/etc/apache2/extra/httpd-vhosts.conf'

#### Generate Wordpress Keys and Salts for wp-config.php

You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}

Sample:
```
<VirtualHost *:80>
    ServerName domain.com
    ServerAlias www.domain.com
    DocumentRoot "/Users/jordalgo/Sites/my-new-site"
    ErrorLog "/private/var/log/apache2/apple.com-error_log"
    CustomLog "/private/var/log/apache2/apple.com-access_log" common
    ServerAdmin web@coolestguidesontheplanet.com
    SetEnv DB_NAME db-name
    SetEnv DB_USER root
    SetEnv DB_PASSWORD db-pw
    SetEnv DB_HOST 127.0.0.1
</VirtualHost>
```

#### Set up db locally

#### Add entry or un-comment entry in '/private/etc/hosts'

#### Start MySQL and Apache

```bash
sudo apachectl start
```

#### Configure the DB using wordpress/wp-admin/install.php

#### Edit htaccess-remote and change to .htaccess after first upload

## Development

```bash
gulp
```

## Deploying to Production

```bash
gulp deploy --commit="commit message"
```

## Updating Wordpress Core and Plugins

Update the version numbers in composer.json then run 'php composer.phar update'

## Todos
* Explore using Timber/Tig
* Add SEO Plugin

## Extra Information


Developed by Jordan Rome
'www.jordanrome.com'
jordan@jordanrome.com

Theme Based on the Bones (Responsive Edition) Wordpress Theme
Developed by Eddie Machado
'http://themble.com/bones'
eddie@themble.com

And the wordpress install based on
David Winter's Article
'http://davidwinter.me/articles/2012/04/09/install-and-manage-wordpress-with-git/'

