# PHP API client for Zato services
This clients offers developers an easy
way to invoke Zato services

To get started, install composer in your project:

```sh
$ curl -s https://getcomposer.org/installer | php
```

Next, add a composer.json file containing the following:

```js
}
    "require": {
        "zato/api-client": "dev-master"
    }
}
```

Alternatively you can use `composer require` in your project

```sh
/opt/local/bin/composer/composer require zato/api-client
```


Then, install!

```sh
$ php composer.phar install
```

Usage
-----

Using the Zato PHP API client is easy:

First, create a new set of HTTP Basic Auth credentials (username: php.client) (https://zato.io/docs/web-admin/security/basic-auth.html)

Then use the client as follows:
``` php
<?php

require 'vendor/autoload.php';

use zato\ZatoClient;

$config = array(
    'user' => 'pubapi',
    'pass' => 'yourpassword',
    'hostname' => 'your_zato_host',
    'port' => '11223');

$client = new ZatoClient($config);

// What are you sending to the zato service you are about to invoke
$payload = array('customers' => array(
	'name' => 'jon oliver',
	'name' => 'monica geller',
	'name' => 'nelson bigetti'));

// params are the same as the zato service, 
// only difference is that we do automatic enconde/decode of your objects
$params = array('name' => 'my-awesome-service', 
				'payload' => $payload

// Result from zato is returned as object
$serviceResult = $client->serviceInvoke($params);
var_dump($serviceResult);
```
