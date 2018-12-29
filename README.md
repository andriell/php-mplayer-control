# MyNAS

Домашний медиасервер. В его состав входят:
* Веб интерфейс для управления контентом
* Mplayer для просмотра видео и фотографий на телевизоре и пульт от него
* Торрент клиент
* Файловый сервер для локальной сети

## Установка
### Установка Centos
Для установки минимального десктопа, без которого не будет работать Mplayer необходимо:
1. Установить CentOS-7 - Minimal
2. yum groupinstall "X11"
3. yum install gnome-classic-session gnome-terminal nautilus-open-terminal control-center liberation-mono-fonts urw-fonts
4. unlink /etc/systemd/system/default.target
5. ln -sf /lib/systemd/system/graphical.target /etc/systemd/system/default.target
6. reboot

### Отключить Selinux (Не обязательно)
1. nano /etc/selinux/config
   прописать SELINUX=disabled
2. reboot

### Отключить firewall (Не обязательно)
1. systemctl disable firewalld
2. systemctl stop firewalld
3. systemctl status firewalld
4. reboot

### Установка Nginx и PHP
В официальных репозиториях Cent OS 7 пока нет сборки PHP 7.
1. curl 'https://setup.ius.io/' -o setup-ius.sh
2. bash setup-ius.sh
3. yum install nginx
4. yum install php71u-fpm php71u-cli
5. yum install ImageMagick*
6. yum install php71u-fpm php71u-cli php71u-xml php71u-mbstring php71u-gd php71u-json php71u-pecl-imagick
7. Настраиваем php и nginx
8. systemctl restart php-fpm
9. systemctl restart nginx
10. systemctl enable php-fpm
11. systemctl enable nginx

### Создаем нового пользователя - mediacenter
1. adduser mediacenter
2. passwd mediacenter
3. su - mediacenter
4. cd ~
5. mkdir .ssh
6. chmod 0700 .ssh
7. cd .ssh/
8. echo 'ssh-rsa AAAAB3N... Xw== rsa-key-20180214' >> authorized_keys
9. chmod 0600 authorized_keys


### Настраиваем Nginx
1. systemctl stop nginx
2. nano /etc/nginx/nginx.conf
```
user mediacenter mediacenter;
***
server {
    listen       80 default_server;
    listen       [::]:80 default_server;
    server_name  _;
    root         /srv/www/mediacenter/current/public;
    index  index.php index.html index.htm;
    
    # Load configuration files for the default server block.
    include /etc/nginx/default.d/*.conf;
    
    location / {
        try_files $uri $uri/ /index.php?$args;
    }
    
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        fastcgi_pass unix:/run/php-fpm/www.sock;
        include fastcgi_params;
    }
    
    location ~ /\.(ht|svn|git|idea) {
    deny all;
    }
}
```
3. chown mediacenter:mediacenter /etc/pki/nginx -R
4. chown mediacenter:mediacenter /var/lib/nginx -R
5. chown mediacenter:mediacenter /var/log/nginx -R
6. chown mediacenter:mediacenter /usr/share/nginx -R
7. mkdir /srv/www/mediacenter/current/public -p
6. chown mediacenter:mediacenter /srv/www/mediacenter -R


### Настраиваем PHP
1. systemctl stop php-fpm
2. nano /etc/php-fpm.d/www.conf
```
user = mediacenter
group = mediacenter
listen = /run/php-fpm/www.sock
listen.owner = mediacenter
listen.group = mediacenter
listen.mode = 0777
listen.acl_users = mediacenter
listen.acl_groups = mediacenter
```
3. chown mediacenter:mediacenter /var/log/php-fpm -R
4. chown mediacenter:mediacenter /var/lib/php -R
5. systemctl start php-fpm
6. systemctl start nginx

### Перенести код
1. yum install git npm
2. npm install --global gulp
3. su - mediacenter
4. cd /srv/www/mediacenter/current
5. git clone https://github.com/andriell/php-mplayer-control .
6. npm install
7. npm run production
8. php artisan key:generate
9. php artisan config:clear
10. cp config/local/users_example.php config/local/users.php
11. Изменить пароль в config/local/users.php

### Устанавливаем медиаплеер
1. yum -y install http://li.nux.ro/download/nux/dextop/el7/x86_64/nux-dextop-release-0-5.el7.nux.noarch.rpm
2. yum install mplayer eom xdotool

### Настраиваем HTTPS в Nginx (Не обязательно)
1. nano /etc/nginx/nginx.conf
2. Удаляем коментарии из секции Settings for a TLS enabled server.
3. mkdir /etc/pki/nginx/private/ -p
4. cd /etc/pki/nginx/
5. openssl genrsa -out server.key 2048
6. openssl req -new -sha256 -key server.key -out server.crt
7. mv server.key private/server.key
8. chown mediacenter:mediacenter /etc/pki/nginx -R

### Установка торрент клиента Transmission
1. yum install transmission-cli transmission-daemon
2. Что бы изменить пользователя от которого будет работать transmission
    nano /usr/lib/systemd/system/transmission-daemon.service
    Заменить User=newuser
3. systemctl start transmission-daemon.service

   systemctl stop transmission-daemon.service
4. nano /home/newuser/.config/transmission-daemon/settings.json
```
"rpc-enabled": true,
"rpc-password": "mypassword",
"rpc-username": "mysuperlogin",
"rpc-whitelist-enabled": false,
"rpc-whitelist": "0.0.0.0",
```
5. systemctl start transmission-daemon.service
6. systemctl enable transmission-daemon.service

### Установка samba
1. yum install samba
2. systemctl stop smb.service
3. cp /etc/samba/smb.conf /etc/samba/smb.conf.bak
4. nano /etc/samba/smb.conf
5. Добавляем системного пользователя в samba

   smbpasswd -a user
6. systemctl restart smb.service
7. systemctl enable smb.service

## Осталось доделать
* Сделать автослайдер удобнее
* Сортировку
* Массовое переименование
* Информацию о процессоре, оперативке, температуре
* Скачивание очень больших файлов в обход php но с контролем доступа
* Публичные ссылки
