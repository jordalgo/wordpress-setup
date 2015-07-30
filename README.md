#A Wordpress Website and Dev Setup

This is primarily for Mac users at the moment but I'm sure it can also be used on a Windows or Linux machine with some adjustments.

## Requirements

This setup assumes that you are familiar the following:
* [Git](https://git-scm.com/)
* [Composer](https://getcomposer.org/)
* [Wpackagist](http://wpackagist.org/)
* [NPM](https://www.npmjs.com/)
* [Gulp](http://gulpjs.com/)
* [Less](http://lesscss.org/)
* [rsync](http://linux.die.net/man/1/rsync)

I'm also assuming you have ssh access to the server you will be deploying this website to. However, I'm sure there are gulp plugins for using FTP instead.

## Setup

!Important! Make sure you read the following:
* [Setting up Apache, MySQL, and PHP locally](http://jason.pureconcepts.net/2012/10/install-apache-php-mysql-mac-os-x/)
* [Installing wordpress](https://codex.wordpress.org/Installing_WordPress)

#### Clone this Repo and Setup Remotes

```bash
git clone https://github.com/jordalgo/wordpress-setup.git my-new-site
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

I usually use Sequel Pro for this but the CL is probably just as easy.

#### Add an entry in '/etc/apache2/extra/httpd-vhosts.conf'

Example:
```
<VirtualHost *:80>
    ServerName domain.com
    ServerAlias www.domain.com
    DocumentRoot "/Users/me/Sites/my-new-site"
    ErrorLog "/private/var/log/apache2/apple.com-error_log"
    CustomLog "/private/var/log/apache2/apple.com-access_log" common
    ServerAdmin web@coolestguidesontheplanet.com
</VirtualHost>
```

#### Generate Wordpress Keys and Salts for wp-config.php

You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
And then add them to the wp-config.php in the root directory.

#### Add entry or un-comment entry in '/private/etc/hosts'

```bash
#127.0.0.1 www.domain.com
```

#### Start MySQL and Apache

```bash
sudo apachectl start
```

#### Copy htaccess-local (for local development)

```bash
cp htaccess-local .htacces
```

You'll have to also run this on your server but use 'htaccess-remote'
and make sure not to add it to your git repo as this will contain sensitive
information about your database and server configuration.

#### Configure the DB

In your web browser go to 'www.domain.com/wordpress/wp-admin/install.php'.

## Development

```bash
gulp
```

## Deploying to Production

Make sure to edit your gulpfile's rsync task so that it pushes to the correct server.

```bash
gulp deploy --commit="commit message"
```

## Updating Wordpress Core and Plugins

```bash
php composer.phar update
```

You can add new plugins and update particular version of wordpress or plugins
by editing the 'composer.json' file.

## Todos
* Explore using Timber/Tig
* Add SEO Plugin
* Add Babel

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

