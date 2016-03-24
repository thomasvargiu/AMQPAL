#!/bin/bash

set -e

echo Installing php-amqp ...


PHP_AMQP_VERSION=$1

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
