FROM centos:6

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN yum update -y

RUN yum install -y httpd unzip php php-devel php-mbstring php-pdo php-mysql php-pecl-apc php-gd php-xml php-mcrypt php-gd \
  && sed -i -e '$adate.timezone = "Asia\/Tokyo"' /etc/php.ini \
  && sed -i -e '$ainclude_path = "/var/www/lib/"' /etc/php.ini

COPY vhost.conf /etc/httpd/conf.d/vhost.conf

# COPY Smarty.tar.gz /usr/local/src/Smarty.tar.gz
# COPY Zend.tar.gz /usr/local/src/Zend.tar.gz

# RUN mkdir /var/www/lib \
#   && tar xzvf /usr/local/src/Smarty.tar.gz -C /var/www/lib \
#   && tar xzvf /usr/local/src/Zend.tar.gz -C /var/www/lib \
#   && rm /usr/local/src/Smarty.tar.gz \
#   && rm /usr/local/src/Zend.tar.gz

CMD ["apachectl", "-D", "FOREGROUND"]
