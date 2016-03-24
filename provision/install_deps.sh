#!/bin/bash

set -e

echo Installing rabbitmq-c ...


#LIBRABBITMQ_VERSION=$1

cd $HOME

if [ ! -d "$HOME/rabbitmq-c" ]; then
  git clone git://github.com/alanxz/rabbitmq-c.git
else
  echo 'Using cached directory.';
  cd $HOME/rabbitmq-c
  git fetch
fi

cd $HOME/rabbitmq-c
git checkout ${LIBRABBITMQ_VERSION}

git submodule init && git submodule update
autoreconf -i && ./configure --prefix=$HOME/rabbitmq-c && make && make install



echo Installing php-amqp ...

#PHP_AMQP_VERSION=$1

cd $HOME

if [ ! -d "$HOME/php-amqp" ]; then
  git clone git://github.com/pdezwart/php-amqp.git
else
  echo 'Using cached directory.';
  cd $HOME/php-amqp
  git fetch
fi

cd $HOME/php-amqp
git checkout ${PHP_AMQP_VERSION}
phpize && ./configure && make && sudo make install
