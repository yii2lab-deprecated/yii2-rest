RestHelper
===

Для выполнения GET запроса:

```php
$responseEntity = RestHelper::get('http://api.demo.yii/v1/city');
```

Есть методы:

* get
* post
* put
* delete

все они имеют такой набор параметров:

* `uri` - ссылка
* `data` - тело для POST
* `headers` - заголовки

Для создания кастомного запроса:

```php
$requestEntity = new RequestEntity;
$requestEntity->method = HttpMethodEnum::GET;
$requestEntity->uri = 'http://api.demo.yii/v1/city';
$responseEntity = RestHelper::sendRequest($requestEntity);
```

Сущность `RequestEntity` имеет поля:

* `method` - метод запроса
* `uri` - ссылка
* `data` - тело для POST
* `headers` - заголовки

