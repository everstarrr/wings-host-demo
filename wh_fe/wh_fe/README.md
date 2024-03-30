<div id="banner" style="overflow: hidden;justify-content:space-around;" align="center">

<div class="" style="max-width: 50%; max-height: 50%; display: inline-block;">
    <a href="https://wingshost.ru" target="_blank">
        <img src="https://wingshost.ru/favicon.ico" width="100" alt="WINGS Logo">
</a>
</div>
<div class="" style="max-width: 50%; max-height: 50%; display: inline-block;">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</div>
</div>

<h1 align="center">WINGS HOST</h1>

---

### WINGS HOST - хостинг игровых и выделенных серверов

А это - проект его сайта и личного кабинета

---

## Deployment

To deploy application to production use only main branch.
Docker installation preferred, however u can use manual installation guide to
deploy app. Both installation guides present here.

---

## Docker installation guide

### installing docker

```shell
# install docker from stable channel
curl -sSL https://get.docker.com/ | CHANNEL=stable bash
```

---

### enable docker on system boot

```shell
# execute this command as root to start docker on system boot
systemctl enable --now docker
```

---

### enable swap

```shell
# create swap file
# this command will create 4 GB swap file
fallocate -l 4G /swapfile
```

---

### make swap file secure

```shell
chmod 600 /swapfile
```

---

### define this file as swap for system and activate it

```shell
# define file as swap
mkswap /swapfile

# activate swap
swapon /swapfile
```

---

### enable swap on system boot

```shell
# create fstab backup
cp /etc/fstab /etc/fstab.backup

# add swap to fstab to enable it on system boot
echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
```

---

## installation method 1: portainer

### install portainer

```shell
# create docker volume for portainer data
docker volume create portainer_data

# create portainer container
# if u want to enable edge server features, add -p 8000:8000 \
docker run -d \
 -p 9443:9443 \
 --name portainer \
 --restart=always \
 -v /var/run/docker.sock:/var/run/docker.sock \
 -v portainer_data:/data \
 portainer/portainer-ce:latest
```

---

### log in using web browser, navigate to server_ip:9443, (ex. 127.0.0.1:9443)

### configure login, password, and other params on portainer web ui, note that connection is not secure.

### navigate to Home > Environments > Local (Docker) > Stacks > Add stack

### name stack way u like, select Web editor, paste this docker compose configuration:

```yaml
version: "3.8"
x-common:
    database: &db-env
        # Do not remove the "&db-password" from the end of the line below, it is important
        # for site functionality.
        MYSQL_PASSWORD: &db-password ${DB_PASSWORD}
        MYSQL_ROOT_PASSWORD: ${DB_PASSWORD}
        MYSQL_DATABASE: &db-database ${DB_DATABASE}
        MYSQL_USER: &db-user ${DB_USERNAME}
    wh: &wh-env
        APP_ENV: ${APP_ENV}
        APP_KEY: ${APP_KEY}
        PANEL_APP_KEY: ${PANEL_APP_KEY}
        APP_TIMEZONE: ${APP_TIMEZONE}
        APP_DEBUG: ${APP_DEBUG}
        APP_URL: ${APP_URL}
        APP_LOCALE: ${APP_LOCALE}
        DB_DATABASE: *db-user
        DB_USERNAME: *db-database
        DB_PASSWORD: *db-password
        PDB_HOST: ${PDB_HOST}
        PDB_PORT: ${PDB_PORT}
        PDB_DATABASE: ${PDB_DATABASE}
        PDB_USERNAME: ${PDB_USERNAME}
        PDB_PASSWORD: ${PDB_PASSWORD}
        PAY_URL: ${PAY_URL}
        PAY_API_KEY: ${PAY_API_KEY}
        PANEL_URL: ${PANEL_URL}
        PANEL_API_APP_KEY: ${PANEL_API_APP_KEY}
        PANEL_API_CLIENT_KEY: ${PANEL_API_CLIENT_KEY}
        SESSION_LIFETIME: ${SESSION_LIFETIME}

        VIRTUAL_HOST: ${VIRTUAL_HOST}
        VIRTUAL_PORT: ${VIRTUAL_PORT}
        VIRTUAL_PROTO: ${VIRTUAL_PROTO}

    mail: &wh-mail-env
        MAIL_DRIVER: "smtp"
        MAIL_HOST: "smtp.example.com"
        MAIL_PORT: "25"
        MAIL_USERNAME: "null"
        MAIL_PASSWORD: "null"
        MAIL_ENCRYPTION: "tls"
        MAIL_FROM_ADDRESS: "no-reply@example.com"
        MAIL_FROM_NAME: "${APP_NAME}"
        MAIL_EHLO_DOMAIN: "example.com"

    s3: &wh-s3-env
        AWS_ACCESS_KEY_ID: "null"
        AWS_SECRET_ACCESS_KEY: "null"
        AWS_DEFAULT_REGION: "us-east-1"
        AWS_BUCKET: "null"
        AWS_USE_PATH_STYLE_ENDPOINT: "false"

#
# ------------------------------------------------------------------------------------------
# DANGER ZONE BELOW
#
# The remainder of this file likely does not need to be changed. Please only make modifications
# below if you understand what you are doing.
#
services:
    database:
        image: mariadb:lts
        restart: always
        command: --default-authentication-plugin=mysql_native_password
        ports:
            - "3316:3306"
        volumes:
            - wh_database:/var/lib/mysql
        environment:
            <<: *db-env
    pma:
        image: phpmyadmin
        restart: always
        ports:
            - "3180:80"
        links:
            - database
        environment:
            PMA_HOST: database
        depends_on:
            - database
    cache:
        image: redis:alpine
        restart: always
    wh:
        image: ghcr.io/wings-n/wh:latest
        restart: always

        links:
            - database
            - cache
        volumes:
            - wh_var:/app/var/
            - wh_nginx:/etc/nginx/http.d/
            - wh_ssl:/etc/ssl/
            - wh_logs:/app/storage/logs
        environment:
            <<: [*wh-env, *wh-mail-env, *wh-s3-env]
            APP_NAME: "WINGS HOST"
            DB_CONNECTION: "mysql"
            DB_HOST: database
            DB_PORT: "3306"
            BROADCAST_DRIVER: "log"
            CACHE_DRIVER: "redis"
            REDIS_HOST: cache
            FILESYSTEM_DISK: "local"
            QUEUE_CONNECTION: "redis"
            SESSION_DRIVER: "redis"
            USE_SSL: true

volumes:
    wh_var:
        name: "wh_var"
    wh_nginx:
        name: "wh_nginx"
    wh_ssl:
        name: "wh_ssl"
    wh_logs:
        name: "wh_logs"
    wh_database:
        name: "wh_database"

networks:
    default:
        ipam:
            config:
                - subnet: 172.20.0.0/16
```

---

### find environment variables section, select advanced mode or load from .env file

### paste .env file, configure it way u need

### usually empty env looks like this

```dotenv
APP_ENV=production
APP_KEY=  # app key here
PANEL_APP_KEY=  # panel app key here
APP_TIMEZONE=Europe/Moscow
APP_DEBUG=false
APP_URL=https://wingshost.ru
APP_LOCALE=ru
DB_DATABASE=wh
DB_USERNAME=wh
DB_PASSWORD=  # db password here, it will used on database deployment
PDB_HOST=172.21.0.3  # panel db network address, here example of docker hosted panel
PDB_PORT=3306
PDB_DATABASE=wh_panel
PDB_USERNAME=wh_panel
PDB_PASSWORD=  # panel db password
PAY_URL=https://pay.wingshost.ru
PAY_API_KEY=  # wings pay api key
PANEL_URL=https://panel.wingshost.ru
PANEL_API_APP_KEY=  # panel api app key
PANEL_API_CLIENT_KEY=  # panel api client key
SESSION_LIFETIME=43200
MAIL_DRIVER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=25
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@example.com"
MAIL_FROM_NAME="${APP_NAME}"
MAIL_EHLO_DOMAIN=example.com
AWS_ACCESS_KEY_ID=null
AWS_SECRET_ACCESS_KEY=null
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=null
AWS_USE_PATH_STYLE_ENDPOINT=false
VIRTUAL_HOST=wingshost.ru
VIRTUAL_PORT=443
VIRTUAL_PROTO=https
```

---

### press Deploy the stack button, and after docker image pullings and other things will be completed, app will be deployed

<br />

<br />

<br />

---

## Manual installation guide

---

### Installing dependencies (OS debian)

```shell
apt -y install software-properties-common curl apt-transport-https ca-certificates gnupg gnupg2 lsb-release ca-certificates

# install php repo
echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/sury-php.list
wget -qO - https://packages.sury.org/php/apt.gpg | sudo apt-key add -

# install redis repo
curl -fsSL https://packages.redis.io/gpg | sudo gpg --dearmor -o /usr/share/keyrings/redis-archive-keyring.gpg
echo "deb [signed-by=/usr/share/keyrings/redis-archive-keyring.gpg] https://packages.redis.io/deb $(lsb_release -cs) main" | sudo tee /etc/apt/sources.list.d/redis.list

# install mariadb repo
curl -sS https://downloads.mariadb.com/MariaDB/mariadb_repo_setup | sudo bash

# update repos list
apt update

# install dependencies from apt
apt -y install php8.1 php8.1-{common,cli,gd,mysql,mbstring,bcmath,xml,fpm,curl,zip} mariadb-server nginx tar unzip git redis-server

# install composer
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

---

### Download files

To continue installation download project files to server in any way you like.
Here's an example with gh cli

#### Install gh cli

```shell
type -p curl >/dev/null || (sudo apt update && sudo apt install curl -y)
curl -fsSL https://cli.github.com/packages/githubcli-archive-keyring.gpg | sudo dd of=/usr/share/keyrings/githubcli-archive-keyring.gpg \
&& sudo chmod go+r /usr/share/keyrings/githubcli-archive-keyring.gpg \
&& echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/githubcli-archive-keyring.gpg] https://cli.github.com/packages stable main" | sudo tee /etc/apt/sources.list.d/github-cli.list > /dev/null \
&& sudo apt update \
&& sudo apt install gh -y
```

#### Authorize & clone repo

```shell
# auth
gh auth login

# before auth, clone repo
mkdir -p /var/www/vendor/wh/
cd /var/www/vendor/wh/
gh repo clone WINGS-N/wh

# set permissions
chmod -R 755 storage/* bootstrap/cache/
```

---

### Create database and user for it

```shell
mariadb -u root

# create user
# change !PASSWORD! to your password and remember it
CREATE USER 'wh'@'127.0.0.1' IDENTIFIED BY '!PASSWORD!';

# create database
CREATE DATABASE wh;

# grant permissions
GRANT ALL PRIVILEGES ON wh.* TO 'wh'@'127.0.0.1' WITH GRANT OPTION;

# exit
exit
```

---

### Configure app

```shell
# copy example env file
cp .env.example .env

# install dependencies
composer install --optimize-autoloader

# generate app key
# only run the command below if you are installing wh for
# the first time and do not have any wh data in the database.
php artisan key:generate --force

# back-up encryption (APP_KEY) from .env, store it in safe place
# not just on your server

# open configuration file in way you like, here's an example with nano
nano .env
# now you can configure app
```

---

### Run database migrations

```shell
php artisan migrate --force
```

---

### Set permissions

```shell
chown -Rv www-data:www-data /var/www/vendor/wh/*
```

---

### Setup scheduler and queue workers

#### crontab configuration

```shell
# open crontab
# press enter if it asks you about editor
crontab -e

# add following lines to the end of opened file
* * * * * php /var/www/vendor/wh/artisan schedule:run >> /dev/null 2>&1
```

#### create queue worker

```shell
# create file
nano /etc/systemd/system/whq.service

# add following content to it:

# WINGS HOST Queue Worker File
# ----------------------------------

[Unit]
Description=WINGS HOST Queue Worker
After=redis-server.service

[Service]
# On some systems the user and group might be different.
# Some systems use `apache` or `nginx` as the user and group.
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/vendor/wh/artisan queue:work --queue=high,standard,low --sleep=3 --tries=3
StartLimitInterval=180
StartLimitBurst=30
RestartSec=5s

[Install]
WantedBy=multi-user.target
```

---

### Enable redis server and queue worker

```shell
# enable redis server
sudo systemctl enable --now redis-server

# enable queue worker
sudo systemctl enable --now whq.service
```

---

### Configure web server (nginx)
