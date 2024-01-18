# Utiliza la imagen base php:8.1.0-apache
FROM php:8.1.0-apache

# Define el argumento DEBIAN_FRONTEND para confirmaciones no interactivas
ENV DEBIAN_FRONTEND=noninteractive

# Instala las extensiones de PHP para MySQL
RUN docker-php-ext-install mysqli

# Actualiza el sistema y luego instala libzip-dev y zlib1g-dev
RUN apt-get update && apt-get upgrade -y && apt-get install -y libzip-dev zlib1g-dev

# Instala la extensión zip para PHP
RUN docker-php-ext-install zip

# Instala la extensión intl para PHP
RUN apt-get install -y libicu-dev && docker-php-ext-install intl

# Instala la extensión pdo para PHP
RUN docker-php-ext-install pdo pdo_mysql

# Habilita el módulo rewrite en Apache
RUN a2enmod rewrite

# Copia el contenido de tu sitio web a /var/www/html del contenedor
COPY www/ /var/www/html

#Instala GIT
RUN apt-get update && apt-get install -y git

#Descargamos el proyecto
#RUN git clone https://github.com/fdiaz-alonso/AEV2-HectorGonzalezMora.git

# Establece el puerto 80 como el puerto de escucha
EXPOSE 80

# Instala composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

# Define el directorio de trabajo
WORKDIR /var/www/html/public

# Comando para iniciar el servidor Apache
CMD ["apache2-foreground"]

# Actualiza la configuración de Apache para apuntar al nuevo directorio de trabajo
RUN sed -i 's#/var/www/html#/var/www/html/public#' /etc/apache2/sites-available/000-default.conf

# Configura el ServerName 
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf


