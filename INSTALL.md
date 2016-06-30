Installation
============

I'll show my detailed installation process on OS X, any linux distribution should be similar.

Table of Contents
--------------------

 - [Requirements](#requirements)
 - [Install Nginx](#install-nginx)
 - [Install PHP with intl extension](#install-php-with-intl-extension)
 - [Install MySQL](#install-mysql)
 - [Install Composer](#install-composer)
 - [Install NPM](#install-npm)
 - [Install Imagemagick](#install-imagemagick)
 - [Install Memcached](#install-memcached)
 - [Install Dependencies](#install-dependencies)
 - [Nginx Configuration](#nginx-configuration)
 - [PHP Configuration](#php-configuration)
 - [Database Schema](#database-schema)
 - [Configurations](#configurations)

## Requirements

 - **PHP:** >=5.4.0
 - **MySQL:**
 - **NPM**
 - **Composer**
 - **Apache/Nginx/Any webserver supports PHP**
 - **Windows/Unix/Linux/OS X/Any System supports things above-mentioned**

## Steps

First I installed a bunch of things...

### Install Nginx

I use [Homebrew][] to install nginx, it's pretty easy

```bash
brew install nginx
```

### Install PHP with intl extension

Again, with `homebrew`. Note that you need the `intl` extension for i18n of the project.

```bash
brew tap homebrew/php
brew install php70 php70-intl
```

### Install MySQL

As you might have guessed, with `homebrew` again.

```bash
brew tap homebrew/versions
brew install mysql56
```

### Install Composer

```bash
brew install composer
```

### Install NPM

```bash
brew install npm
```

### Install Imagemagick

```bash
brew install imagemagick
```

### Install Memcached

```bash
brew install memcached
```

Then we come to the configurations and preparations. I'll skip the details of configuring `nginx` and `php` to make them work together. There are lots of tutorials on the internet...

### Install Dependencies

Go to the root directory of the project, and run this(you may add the `--no-dev` parameter in a production environment): 

```bash
composer install [--no-dev]
```

When it's installing, we can go and do some configuration work.

### Nginx Configuration

Make sure you have something like this with your webserver, i.e. you have to redirect most requests to the application. **The document root should be the `web` directory in the project.**

```nginx
...
root /path/to/the/project/web;
...
location / {
    # Redirect everything that isn't a real file to index.php
    try_files $uri $uri/ /index.php?$args;
}
...
```

### PHP Configuration

With the php-fpm configuration, make sure the following is configured appropriately to make less compiler work(might be different):

```ini
env[PATH] = /usr/local/bin:/usr/bin:/bin
```

### Database Schema

Run `schema.sql` to create the database for the app, and assign privileges to a user.
If you want to enable cache of the submodule `visualcube`, also run the `db_schema.sql` in that submodule(which is located in the `web` directory).

### Configurations

To protect some private credentials, it is highly recommended to have a 'local copy' of some config files. In this project, there are two.

 - One is `config/web/request.php`, copy it to `request.local.php` and add a random string to `cookieValidationKey`;
 - The other one is `config/common/db.php`, copy it to `db.local.php`, it has the credentials for the database, so configure it according to your own situation.

#### Visualcube Configurations

To improve performance and speed up image loading, it is highly recommended to enable db cache for visualcube.

 1. Create the table with `db_schema.sql`;
 2. Copy `web/visualcube/visualcube_config.php` to `web/visualcube/visualcube_config.local.php`;
 3. Change the settings about db cache at the end of file, enable it and configure the db info;
 4. If you are running an online service, it's also recommended to install a pruning cron job to reduce the db size, namely `web/visualcube/visualcube_dbprune.sh`.

Now it should work properly. There might be some problems I haven't mentioned above, but just try to resolve it by your self :)


[Homebrew]: http://brew.sh