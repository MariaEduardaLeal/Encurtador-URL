# Mantemos a sua base de elite (PHP 8.4)
FROM hyperf/hyperf:8.4-alpine-v3.21-swoole
LABEL maintainer="Eduarda <software.engineer>" version="1.0" license="MIT" app.name="Hyperf"

##
# ---------- env settings ----------
##
ARG timezone

# Alterado para DEV e SCAN_CACHEABLE para false
ENV TIMEZONE=${timezone:-"America/Sao_Paulo"} \
    APP_ENV=dev \
    SCAN_CACHEABLE=(false)

# update e instalação do Postgres
RUN set -ex \
    # Instala a extensão PDO do Postgres direto do repositório Alpine
    && apk add --no-cache postgresql-dev php84-pdo_pgsql \
    && php -v \
    && php -m \
    && php --ri swoole \
    #  ---------- some config ----------
    && cd /etc/php84 \
    # - config PHP
    && { \
        echo "upload_max_filesize=128M"; \
        echo "post_max_size=128M"; \
        echo "memory_limit=1G"; \
        echo "date.timezone=${TIMEZONE}"; \
    } | tee conf.d/99_overrides.ini \
    # - config timezone
    && ln -sf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone \
    # ---------- clear works ----------
    && rm -rf /var/cache/apk/* /tmp/* /usr/share/man \
    && echo -e "\033[42;37m Build Completed :).\033[0m\n"

WORKDIR /opt/www

COPY . /opt/www

# Removido o --no-dev para podermos ter ferramentas de teste e Watcher
RUN composer install -o

EXPOSE 9501

# O ENTRYPOINT foi removido pois o docker-compose agora dita o comando