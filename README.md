# jwt-php-server-test

To install dependencies:

```
composer install
```

To start the server: 

```
php -S 0.0.0.0:8000
```

To run the client: 

```
php client.php
```

To generate new keys:

```
openssl genpkey -algorithm RSA -out rsa_private.pem -pkeyopt rsa_keygen_bits:4096
openssl rsa -in rsa_private.pem -pubout -out rsa_public.pem
```