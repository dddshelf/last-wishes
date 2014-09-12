<?php

use Lw\Infrastructure\Persistence\Doctrine\EntityManagerFactory;

class Application
{
    public static function bootstrap()
    {
        $app = new Silex\Application();

        $app['debug'] = true;

        $app['em'] = $app->share(function() {
            return (new EntityManagerFactory())->build();
        });

        $app['user_repository'] = $app->share(function($app) {
            return $app['em']->getRepository('Lw\Domain\Model\User\User');
        });

        $app['wish_repository'] = $app->share(function($app) {
            return $app['em']->getRepository('Lw\Domain\Model\Wish\Wish');
        });

        $app['event-repository'] = $app->share(function($app) {
            return $app['em']->getRepository('Cyoa\Domain\Event');
        });

        $app['tx-use-case-factory'] = $app->share(function($app) {
            return new \Cyoa\Application\UseCase\TransactionalUseCaseFactory(
                new \Cyoa\Infrastructure\Persistence\Doctrine\Session($app['em'])
            );
        });

        $app->register(new Silex\Provider\SessionServiceProvider());
        /*
        $app->register(
            new Silex\Provider\SecurityServiceProvider(),
            array(
                'security.firewalls' => array(
                    'default' => array(
                        'pattern' => '^/',
                        'anonymous' => true,
                        'oauth' => array(
                            //'login_path' => '/auth/{service}',
                            //'callback_path' => '/auth/{service}/callback',
                            //'check_path' => '/auth/{service}/check',
                            'failure_path' => '/login',
                            'with_csrf' => false
                        ),
                        'logout' => array(
                            'logout_path' => '/logout',
                            'with_csrf' => false
                        ),
                        'users' => new Gigablah\Silex\OAuth\Security\User\Provider\OAuthInMemoryUserProvider()
                    )
                ),
                'security.access_rules' => array(
                    array('^/auth', 'ROLE_USER')
                )
            )
        );
        */

        $app->register(new Silex\Provider\UrlGeneratorServiceProvider());
        $app->register(new Silex\Provider\FormServiceProvider());
        $app->register(
            new Silex\Provider\TwigServiceProvider(),
            array(
                'twig.path' => __DIR__.'/../../src/Lw/Infrastructure/Ui/Twig/Views',
            )
        );

        /*
        $app->register(
            new Gigablah\Silex\OAuth\OAuthServiceProvider(),
            array(
                'oauth.services' => array(
                    'twitter' => array(
                        'key' => 'SN184Oj2jKWMaMJl9TregXtKU',
                        'secret' => '7ngY5V2iNHZJj3AGBk2li5vsBI8wRvdPbgJZgqQYSfovTpto9e',
                        'scope' => array(),
                        'user_endpoint' => 'https://api.twitter.com/1.1/account/verify_credentials.json'
                    )
                )
            )
        );
        */

        /*
        $app['twig'] = $app->share(
            $app->extend(
                'twig',
                function($twig, $app) {
                    $services = array_keys($app['oauth.services']);
                    $twig->addGlobal(
                        'login_paths',
                        array_map(function ($service) use ($app) {
                            return $app['url_generator']->generate('_auth_service', array(
                                    'service' => $service,
                                    // '_csrf_token' => $app['form.csrf_provider']->generateCsrfToken('oauth')
                                ));
                            }, array_combine($services, $services)));

            $twig->addGlobal(
                'logout_path',
                $app['url_generator']->generate('logout', array(
                        // '_csrf_token' => $app['form.csrf_provider']->generateCsrfToken('logout')
                    ))
            );

            return $twig;
        }));
        */

        $app->before(function (Symfony\Component\HttpFoundation\Request $request) use ($app) {
            /*
            //$token = $app['security']->getToken();
            $app['user'] = null;

            if ($token && !$app['security.trust_resolver']->isAnonymous($token)) {
                $app['user'] = $token->getUser();
            }

            \Cyoa\Application\DomainEventPublisher::getInstance()->subscribe(
                new \Cyoa\Application\PersistDomainEventSubscriber(
                    $app['event-repository']
                )
            );
            */
        });

        return $app;
    }
}
