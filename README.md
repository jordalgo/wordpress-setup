A Wordpress Website: by (Jordan Rome)[http://www.jordanrome.com]

- update the .htaccess file
- update the wp-config file in wp-content
- add virtual host config in /etc/apache2/extra/httpd-vhosts.conf

## Setup

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

#### Set up a local Mysql DB and add the database name in site-config.json

#### Add entry or un-comment entry in '/private/etc/hosts'

#### Start MAMP

#### Configure the DB using wordpress/wp-admin/install.php

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

## Extra Information

Composer: https://getcomposer.org/
Adding more wp-plugins: http://wpackagist.org/

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

