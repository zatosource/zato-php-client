# zato-php-client
This client provides developers an easy way to invoke zato services using zato public api,
it can easily be extended to provide support for other public api resources.

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

Then, install!

```sh
$ php composer.phar install
```

Usage
-----

Using the zato php api client is easy:

First set your public api password in zato webadmin security (https://zato.io/docs/web-admin/security/basic-auth.html)

Then use the client as follows:
``` php
<?php

require dirname(__DIR__).'/../vendor/autoload.php';
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
