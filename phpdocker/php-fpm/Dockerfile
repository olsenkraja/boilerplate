FROM phpdockerio/php74-fpm:latest
WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive


# Install git
RUN apt-get update \
    && apt-get -y --no-install-recommends install git php7.4-mysql php-redis \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
