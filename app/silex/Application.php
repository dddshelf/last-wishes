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
            return $app['em']->getRepository('Lw\Domain\Model\Wish\WishEmail');
        });

        $app['event-repository'] = $app->share(function($app) {
            return $app['em']->getRepository('Lw\Domain\Model\Event');
        });

        $app['tx-use-case-factory'] = $app->share(function($app) {
            return new \Lw\Application\UseCase\TransactionalUseCaseFactory(
                new \Cyoa\Infrastructure\Persistence\Doctrine\Session($app['em'])
            );
        });

        $app->register(new Silex\Provider\SessionServiceProvider());
        $app->register(new Silex\Provider\UrlGeneratorServiceProvider());
        $app->register(new Silex\Provider\FormServiceProvider());
        $app->register(
            new Silex\Provider\TwigServiceProvider(),
            array(
                'twig.path' => __DIR__.'/../../src/Lw/Infrastructure/Ui/Twig/Views',
            )
        );

        return $app;
    }
}
