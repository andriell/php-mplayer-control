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

### Установка Nginx и PHP
В официальных репозиториях Cent OS 7 пока нет сборки PHP 7.
1. curl 'https://setup.ius.io/' -o setup-ius.sh
2. bash setup-ius.sh
3. yum install nginx
4. yum install php71u-fpm php71u-cli
5. yum install php71u-fpm php71u-cli php71u-xml php71u-mbstring php71u-gd
6. Настраиваем php и nginx
7. systemctl restart php-fpm
8. systemctl restart nginx

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

## Осталось доделать
* Сделать автослайдер удобнее
* Эмуляцию видеопрогресса на пульте, вместо реального прогресса
* Сортировку
* Массовое переименование
* Информацию о процессоре, оперативке, температуре
* Скачивание очень больших файлов в обход php но с контролем доступа
* Публичные ссылки
