<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.02.17
 * Time: 15:47
 */

namespace rollun\permission\Auth\Adapter\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use rollun\api\Api\Google\Client\Web;
use rollun\permission\Auth\Adapter\OpenID;
use rollun\permission\Auth\Adapter\OpenIDAdapter;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class OpenIDAdapterFactory implements FactoryInterface
{

    const KEY_OPENID_ADAPTER = 'openIdAdapter';

    const KEY_ADAPTER_CONFIG = 'config';

    const KEY_AC_REALM = 'realm';

    const DEFAULT_REALM = 'RollunService';

    const KEY_RESOLVER = 'resolver';

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $adapterConfig = [];
        $config = $container->get('config');
        $adapterConfig = isset($config[static::KEY_OPENID_ADAPTER][static::KEY_ADAPTER_CONFIG]) ?
            $config[static::KEY_OPENID_ADAPTER][static::KEY_ADAPTER_CONFIG] :
            [static::KEY_AC_REALM => static::DEFAULT_REALM];

        $openIdAdapter = new OpenID($adapterConfig);

        $webClient = $container->get(Web::class);
        $openIdAdapter->setWebClient($webClient);

        if (isset($factoryConfig[static::KEY_RESOLVER]) &&
            $container->has($factoryConfig[static::KEY_RESOLVER])
        ) {
            $basicResolver = $container->get($factoryConfig[static::KEY_RESOLVER]);
            $openIdAdapter->setResolver($basicResolver);
        }
    }
}