RestHelper
===

Для выполнения GET запроса:

```php
$responseEntity = RestHelper::get('http://api.demo.yii/v1/city');
```

Имеет 5 методов:

* `get()` - запрос методом GET
* `post()` - запрос методом POST
* `put()` - запрос методом PUT
* `del()` - запрос методом DELETE
* `sendRequest()` - отправка запроса, используя сущность запроса

все методы, кроме `sendRequest` имеют такой набор параметров:

* `uri` - ссылка
* `data` - тело для POST
* `headers` - заголовки
* `options` - опции http-клиента

Для создания кастомного запроса:

```php
$requestEntity = new RequestEntity;
$requestEntity->method = HttpMethodEnum::GET;
$requestEntity->uri = 'http://api.demo.yii/v1/city';
$responseEntity = RestHelper::sendRequest($requestEntity);
```
