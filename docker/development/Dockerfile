FROM node:6.7.0

ADD prepare-app.sh /prepare-app.sh

RUN apt-get update \
    && apt-get install php5 php5-sqlite -y \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === 'e115a8dc7871f15d853148a7fbac7da27d6c0030b848d9b3dc09e2a0388afed865e6a3d6b3c0fad45c48e2b5fc1196ae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /bin/composer \
    && chmod +x /prepare-app.sh /bin/composer \
    && rm -rf /var/lib/apt/lists/*

RUN groupadd -g 1000 deploy \
    && useradd -m -g 1000 -u 1000 deploy

RUN mkdir /.npm /.config /.cache /.local \
    && chown 1000:1000 -R /.npm /.config /.cache /.local