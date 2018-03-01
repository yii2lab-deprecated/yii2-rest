BaseRestRepository
===
## Основное

Базовый репозиторий.
Обеспечивает доступ к сторонним API посредством rest-запросов.

## Поля

Хранилище меет поля:

* `baseUrl` - базовый URL
* `headers` - заголовки
* `options` - опции http-клиента

При каждом запросе происходит:

* слияние `baseUrl` и `url`
* слияние заголовков и опций

Например, можно установить заголовок `Authorization` с токеном, и все запросы будут авторизованны.

## Конфигурация

Конфигурация хранилища выглядит так:

```php
'repositories' => [
	'user' => [
		'driver' => 'rest',
		'baseUrl' => 'http://api.example.com/v1/user',
		'headers' => [
			'user-agent' => 'Awesome-Octocat-App',
		],
	],
],
```

## Методы

Набор и интерфейс методов идентичен [RestHelper](https://github.com/yii2lab/yii2-rest/blob/master/guide/ru/helper-rest.md),
за исключением того, что в хранилище они не статичные и защищенные.

## Пример

```php
class UserRepository extends BaseRestRepository {

public function login($login, $password) {
		$responseEntity = $this->post('auth', [
			'login' => $login,
			'password' => $password,
		]);
		return $this->forgeEntity($responseEntity->data, LoginEntity::class);
	}
	
	public function loginAlt($login, $password) {
		$requestEntity = new RequestEntity;
		$requestEntity->method = HttpMethodEnum::POST;
		$requestEntity->uri = 'auth';
		$requestEntity->data = [
			'login' => $login,
			'password' => $password,
		];
		$responseEntity =  $this->sendRequest($requestEntity);
		return $this->forgeEntity($responseEntity->data, LoginEntity::class);
	}
	
}
```

В примере, авторизация реализована двумя способами, они идентичны по функционалу.
