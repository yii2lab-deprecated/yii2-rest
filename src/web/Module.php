<?php

namespace yii2lab\rest\web;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\web\Application;
use yii\web\ForbiddenHttpException;
use yii2lab\domain\helpers\DomainHelper;

/**
 * Class Module
 *
 * @property \yii2lab\rest\web\storages\Storage $storage
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @event models\RequestEvent an event raised before sending request.
     */
    const EVENT_ON_REQUEST = 'onRequest';
    /**
     * @event models\ResponseEvent an event raised after sending request and after obtaining response.
     */
    const EVENT_ON_RESPONSE = 'onResponse';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'yii2lab\rest\web\controllers';
    /**
     * @inheritdoc
     */
    public $defaultRoute = 'request';
    /**
     * @var array the list of IPs that are allowed to access this module.
     * Each array element represents a single IP filter which can be either an IP address
     * or an address with wildcard (e.g. 192.168.0.*) to represent a network segment.
     * The default value is `['127.0.0.1', '::1']`, which means the module can only be accessed
     * by localhost.
     */
    //public $allowedIPs = ['127.0.0.1', '::1'];
	public $allowedIPs = ['*'];
    /**
     * @var string base request URL.
     */
    public $baseUrl;
    /**
     * @var array http client object configuration.
     */
    public $clientConfig = 'yii\httpclient\Client';
    /**
     * @var array
     */
    public $formatters = [
        'application/json' => 'yii2lab\rest\web\formatters\JsonFormatter',
        'application/xml' => 'yii2lab\rest\web\formatters\XmlFormatter',
        'text/html' => 'yii2lab\rest\web\formatters\HtmlFormatter',
    ];
	
	public function init() {
		DomainHelper::forgeDomains([
			'rest' => 'yii2lab\rest\domain\Domain',
		]);
		parent::init();
	}
    
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof Application) {
            $app->getUrlManager()->addRules([
                $this->id . '/<tag:[0-9a-f]+>' => $this->id . '/request/create',
                $this->id => $this->id . '/request/create',
                $this->id . '/<controller:\w+>/<action:\w+>/<tag:[0-9a-f]+>' => $this->id . '/<controller>/<action>',
                $this->id . '/<controller:\w+>/<action:\w+>' => $this->id . '/<controller>/<action>',
            ], false);
        } else {
            throw new InvalidConfigException('Can use for web application only.');
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (Yii::$app instanceof Application && !$this->checkAccess()) {
            throw new ForbiddenHttpException('You are not allowed to access this page.');
        }

        $this->resetGlobalSettings();

        return true;
    }

    /**
     * Resets potentially incompatible global settings done in app config.
     */
    protected function resetGlobalSettings()
    {
        if (Yii::$app instanceof Application) {
            Yii::$app->assetManager->bundles = [];
        }
    }

    /**
     * @return boolean whether the module can be accessed by the current user
     */
    protected function checkAccess()
    {
        $ip = Yii::$app->getRequest()->getUserIP();
        foreach ($this->allowedIPs as $filter) {
            if (
                $filter === '*' || $filter === $ip ||
                (
                    ($pos = strpos($filter, '*')) !== false &&
                    !strncmp($ip, $filter, $pos)
                )
            ) {
                return true;
            }
        }
        Yii::warning(
            'Access to REST Client is denied due to IP address restriction. The requested IP is ' . $ip,
            __METHOD__
        );

        return false;
    }

}
