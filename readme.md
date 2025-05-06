# Manual de despliegue


## Requisitos de despliegue

Esta web se despliega mediante docker, lo primero será instalar docker.

## Instrucciones de despliegue

Antes de lanzar el docker compose es importante que las carpetas esten con la siguiente distribución

#### 

* mariadb
    * Dockerfile
* nginx
    * default.conf
* www
    * index.php
* docker-compose.yml

#### El contenido de los ficheros es:

Dockerfile

```
FROM mariadb:noble

RUN apt-get update && \
    apt-get install -y mariadb-client && \
    ln -s /usr/bin/mariadb /usr/bin/mysql

```

Default.conf

```
server {
    listen 80;
    server_name proyectodaw.com;

    root /var/www/html;
    index index.php;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME /var/www/html$fastcgi_script_name;
    }
}

```

Dockercompose
```
version: '3'

services:
  

  php:
    image: php:8.2-fpm
    volumes:
      - ./www:/var/www/html
    networks:
      - redproyecto
    command: bash -c "docker-php-ext-install pdo_mysql && php-fpm"

  mariadb:
    build: ./mariadb
    environment:
      MYSQL_ROOT_PASSWORD: rootpassword
      MYSQL_USER: user
      MYSQL_PASSWORD: password
    volumes:
      - db_data:/var/lib/mysql
    ports:
      - "3306:3306"
    networks:
      - redproyecto

  nginx:
    image: nginx:stable-perl
    ports:
      - "80:80"
    volumes:
      - ./www:/var/www/html
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - php
    networks:
      - redproyecto

volumes:
  db_data: 
    driver: local

networks:
  redproyecto:
    driver: bridge
```


#### Para desplegarlo deberemos ir a la carpeta donde esta la aplicación, entonces ejecutamos:

```
docker-compose up --build -d
```

Con esto quedaran creados los contenedores y tendrán conexión entre ellos.