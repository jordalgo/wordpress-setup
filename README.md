{%= themename %} Website

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

## Setup

#### Clone this Repo and Setup Remotes

```bash
git clone URL my-new-site
git remote add upstream URL
git remote set-url --push upstream no_push
git remote add origin git@bitbucket.org:jordalgo/my-new-site.git
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


####
Install the gulp, js, and css dependencies.

```bash
npm install
```

#### Step 2

Set up a local Mysql DB and add the database name in package.json

#### Step 3

```bash
grunt
```

#### Step 4

Change the root directory in MAMP

#### Step 5

Configure the DB using wordpress (wordpress/wp-admin/install.php)

### Step 6

Then add the repo to bitbucket or github and add the remote
```bash
git remote add origin git@bitbucket.org:jordalgo/{%= themename %}.git
git add -A
git commit -m "first commit"
git push -u origin --all
```

## Development

### Updating Wordpress Core and Plugins

Update the version numbers in composer.json then run 'php composer.phar update'

## Deploying to Production

```bash
grunt --deploy=true
```

or (if only FTP is available)

```bash
grunt --deploy=(repo, wordpress, php, library, top)
```

## Updating Local Development Hosts File

```bash
vim /private/etc/hosts
```

## Updraft Google Drive
Save the settings and then attempt to connect to google. Dont use the test link.

## Extra Information
Composer: https://getcomposer.org/
Adding more wp-plugins: http://wpackagist.org/
