<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

## Requisitos previos

Para poder correr la aplicación, necesitas instalar lo siguiente:

- php7.2-fpm php-7.2-pgsql y php7.2-zip
    - sudo apt-get install -y php7.2-fpm php7.2-pgsql php7.2-zip
- nginx
    - sudo apt-get install -y nginx
- composer
    - [Composer](getcomposer.org)
- postgresql (con docker)
    - docker run --name cont_psql12 -e POSTGRES_PASSWORD=postgres -d postgres:12
    - Para ejecutar la consola de postgres ejecutar el siguiente comando:
        - docker exec -it cont_psql12 psql -h localhost -U postgres
    

## Instalación

Una vez descargadas las dependencias, se debe:

 - Crear la BD
    - psql -h localhost -U postgres || (para docker) docker exec -it cont_psql12 psql -h localhost -U postgres
    - create database apitest;
    - \q
 - Ubicarse en la ruta del proyecto y descargar las dependencias del proyecto mediante composer
    - sudo chmod -Rf 777 storage
    - sudo chmod -Rf 777 bootstrap/cache
    - cp .env.example .env
        - Editar el archivo .env generado con las conexiones correspondientes para:
            - DB_CONNECTION=pgsql
            - DB_HOST=[ip de la bd]
            - DB_PORT=5432
            - DB_DATABASE=apitest
            - DB_USERNAME=postgres
            - DB_PASSWORD=[clave del usuario postgres]
    -  composer install
 - php artisan key:generate
 - php artisan migrate
 - php artisan passport:install --force
 - Registrar su FootballData_API_KEY en el archivo .env

## Configuración NGINX
```
server {
        listen 80;
        charset utf-8;
        server_name [your-server-name];
        root [your-path];
        access_log [your-storage-logs-path]/nginx-access.log;
        error_log [your-storage-logs-path]/nginx-error.log;
        index index.php;

        #server_tokens off;

        location / {
                try_files $uri /index.php$is_args$args;
        }

        location ~ \.php$ {
                fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
                fastcgi_index index.php;
                fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                fastcgi_read_timeout 300;
                include /etc/nginx/fastcgi_params;
        }
}
```

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## Crontab, Endpoints y uso en postman

Los comandos a ejecutar en el crontab son:

-  php artisan football:competitions
-  php artisan football:teams (aun por hacer, api restricted for payment)
-  php artisan football:players (aun por hacer, api restricted for payment)

**Los endpoints disponibles son:**
- register
- login
- competitions
- competitions/id

Y estos los pueden ver a detalle en la colección postman que adjunto en el proyecto: __APIPLAYERS.postman_collection.json__
Esta colección pueden importarla a su aplicación postman y confirmar sus valores, incluuído el registro de usuario para que pueda acceder a los endpoints.
 

## Configuración crontab
```
crontab -e
Escribir:
* * * * * [project-path]/php artisan football:competitions
Guardar y salir 

```

## Datos de contacto

Gracias por la consideración y disculpen la demora, hubieron varias entrevistas a las que estuve yendo, y me quitaron tiempo, 
para resolver el ejercicio, con lo que el día de hoy tuve que hacer el ejercicio.
 - Email: augusto.caceres.puma@gmail.com
 - Telf.: 945615408
 - Augusto Franklin Cáceres Puma
