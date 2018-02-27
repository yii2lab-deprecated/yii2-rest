RestHelper
===

Для выполнения GET запроса:

```php
$responseEntity = RestHelper::get('http://api.demo.yii/v4/city');
```

Поддерживаются методы:

* GET
* POST
* PUT
* DELETE

Для создания кастомного запроса:

```php
$requestEntity = new RequestEntity;
$requestEntity->method = HttpMethodEnum::GET;
$requestEntity->uri = 'http://api.qr.yii/v4/city';
$responseEntity = RestHelper::sendRequest($requestEntity);
```

