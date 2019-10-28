FROM debian:10-slim

WORKDIR /root
COPY . .

RUN apt update
RUN apt upgrade
RUN apt -y install lsb-release apt-transport-https ca-certificates wget
RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php7.x.list
RUN apt update
RUN apt -y install php7.4 apache2 composer

RUN composer install --no-dev
EXPOSE 8000
CMD [ "php", "-S", "0.0.0.0:8000" ]