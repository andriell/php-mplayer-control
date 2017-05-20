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
3. yum install gnome-classic-session gnome-terminal nautilus-open-terminal control-center liberation-mono-fonts
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
5. yum install php71u-fpm php71u-cli php71u-xml php71u-mbstring php71u-gd php71u-json
6. Настраиваем php и nginx
7. systemctl restart php-fpm
8. systemctl restart nginx
9. systemctl enable php-fpm
10. systemctl enable nginx

### Настраиваем nginx
1. nano /etc/nginx/nginx.conf
2. Удаляем коментарии из секции Settings for a TLS enabled server.
3. mkdir /etc/pki/nginx/
   mkdir /etc/pki/nginx/private/
4. cd /etc/pki/nginx/
5. openssl genrsa -out server.key 2048
6. openssl req -new -sha256 -key private.key -out server.crt
7. mv server.key private/server.key
8. chown nginx:nginx /etc/pki/nginx/
   chown nginx:nginx /etc/pki/nginx/*
   
nano /etc/php-fpm.d/www.conf
listen = /run/php-fpm/www.sock
listen.acl_users = nginx


### Установка торрент клиента Transmission
1. yum install transmission-cli transmission-daemon
2. Что бы изменить пользователя от которого будет работать transmission
    nano /usr/lib/systemd/system/transmission-daemon.service
    Заменить User=newuser
3. systemctl start transmission-daemon.service
   systemctl stop transmission-daemon.service
4. nano /home/newuser/.config/transmission-daemon/settings.json
    "rpc-enabled": true,
    "rpc-password": "mypassword",
    "rpc-username": "mysuperlogin",
    "rpc-whitelist-enabled": false,
    "rpc-whitelist": "0.0.0.0",
5. systemctl start transmission-daemon.service
6. systemctl enable transmission-daemon.service

### Устанавливаем медиаплеер
1. yum -y install http://li.nux.ro/download/nux/dextop/el7/x86_64/nux-dextop-release-0-5.el7.nux.noarch.rpm
2. yum install mplayer eom xdotool

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
