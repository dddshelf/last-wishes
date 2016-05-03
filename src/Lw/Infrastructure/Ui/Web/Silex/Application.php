<?php

namespace Lw\Infrastructure\Ui\Web\Silex;

use Ddd\Application\Service\TransactionalApplicationService;
use Ddd\Infrastructure\Application\Notification\RabbitMqMessageProducer;
use Ddd\Infrastructure\Application\Service\DoctrineSession;
use GuzzleHttp\Client;
use Lw\Application\DataTransformer\User\UserDtoDataTransformer;
use Lw\Application\Service\User\SignUpUserService;
use Lw\Application\Service\User\ViewBadgesService;
use Lw\Application\Service\User\ViewWishesService;
use Lw\Application\Service\Wish\AddWishService;
use Lw\Application\Service\Wish\DeleteWishService;
use Lw\Application\Service\Wish\AggregateVersion\DeleteWishService as DeleterWishServiceAggregateVersion;
use Lw\Application\Service\Wish\AggregateVersion\AddWishService as AddWishServiceAggregateVersion;
use Lw\Application\Service\Wish\UpdateWishService;
use Lw\Application\Service\Wish\ViewWishService;
use Lw\Domain\Model\User\User;
use Lw\Infrastructure\Domain\Model\User\DoctrineUserFactory;
use Lw\Infrastructure\Persistence\Doctrine\EntityManagerFactory;
use Lw\Infrastructure\Service\HttpUserAdapter;
use Lw\Infrastructure\Service\TranslatingUserService;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Silex\Provider\TwigServiceProvider;

class Application
{
    public static function bootstrap()
    {
        $app = new \Silex\Application();

        $app['debug'] = true;
        $app['gamify_host'] = '127.0.0.1';
        $app['gamify_port'] = '8000';

        $app['em'] = $app->share(function ($app) {
            return (new EntityManagerFactory())->build($app['db']);
        });

        $app->register(new DoctrineServiceProvider(), array(
            'db.options' => array(
                'driver' => 'pdo_sqlite',
                'path' => __DIR__.'/../../../../../../db.sqlite',
            ),
        ));

        $app['exchange_name'] = 'last-will';

        $app['tx_session'] = $app->share(function ($app) {
            return new DoctrineSession($app['em']);
        });

        $app['user_repository'] = $app->share(function ($app) {
            return $app['em']->getRepository('Lw\Domain\Model\User\User');
        });

        $app['wish_repository'] = $app->share(function ($app) {
            return $app['em']->getRepository('Lw\Domain\Model\Wish\Wish');
        });

        $app['event_store'] = $app->share(function ($app) {
            return $app['em']->getRepository('Ddd\Domain\Event\StoredEvent');
        });

        $app['message_tracker'] = $app->share(function ($app) {
            return $app['em']->getRepository('Ddd\Domain\Event\PublishedMessage');
        });

        $app['message_producer'] = $app->share(function () {
            return new RabbitMqMessageProducer(
                new AMQPStreamConnection('localhost', 5672, 'guest', 'guest')
            );
        });

        $app['user_factory'] = $app->share(function () {
            return new DoctrineUserFactory();
        });

        $app['view_wishes_application_service'] = $app->share(function ($app) {
            return new ViewWishesService(
                $app['wish_repository']
            );
        });

        $app['view_wish_application_service'] = $app->share(function ($app) {
            return new ViewWishService(
                $app['user_repository'],
                $app['wish_repository']
            );
        });

        $app['add_wish_application_service'] = $app->share(function ($app) {
            return new TransactionalApplicationService(
                new AddWishService(
                    $app['user_repository'],
                    $app['wish_repository']
                ),
                $app['tx_session']
            );
        });

        $app['add_wish_application_service_aggregate_version'] = $app->share(function ($app) {
            return new TransactionalApplicationService(
                new AddWishServiceAggregateVersion(
                    $app['user_repository']
                ),
                $app['tx_session']
            );
        });

        $app['update_wish_application_service'] = $app->share(function ($app) {
            return new TransactionalApplicationService(
                new UpdateWishService(
                    $app['user_repository'],
                    $app['wish_repository']
                ),
                $app['tx_session']
            );
        });

        $app['update_wish_application_service_aggregate_version'] = $app->share(function ($app) {
            return new TransactionalApplicationService(
                new UpdateWishServiceAggregateVersion(
                    $app['user_repository']
                ),
                $app['tx_session']
            );
        });

        $app['delete_wish_application_service'] = $app->share(function ($app) {
            return new TransactionalApplicationService(
                new DeleteWishService(
                    $app['user_repository'],
                    $app['wish_repository']
                ),
                $app['tx_session']
            );
        });

        $app['delete_wish_application_service_aggregate_version'] = $app->share(function ($app) {
            return new TransactionalApplicationService(
                new DeleterWishServiceAggregateVersion(
                    $app['user_repository']
                ),
                $app['tx_session']
            );
        });

        $app['sign_in_user_application_service'] = $app->share(function ($app) {
            return new TransactionalApplicationService(
                new SignUpUserService(
                    $app['user_repository'],
                    new UserDtoDataTransformer()
                ),
                $app['tx_session']
            );
        });

        $app['gamify_guzzle_client'] = $app->share(function ($app) {
            return new Client([
                'base_uri' => sprintf('http://%s:%d/api/', $app['gamify_host'], $app['gamify_port']),
            ]);
        });

        $app['http_user_adapter'] = $app->share(function ($app) {
            return new HttpUserAdapter($app['gamify_guzzle_client']);
        });

        $app['user_adapter'] = $app->share(function ($app) {
            return $app['http_user_adapter'];
        });

        $app['translating_user_service'] = $app->share(function ($app) {
            return new TranslatingUserService($app['user_adapter']);
        });

        $app['view_badges_application_service'] = $app->share(function ($app) {
            return new ViewBadgesService($app['translating_user_service']);
        });

        $app->register(new \Silex\Provider\SessionServiceProvider());
        $app->register(new \Silex\Provider\UrlGeneratorServiceProvider());
        $app->register(new \Silex\Provider\FormServiceProvider());
        $app->register(new \Silex\Provider\TranslationServiceProvider());
        $app->register(new MonologServiceProvider(), [
            'monolog.logfile' => __DIR__.'/var/logs/silex_'.(($app['debug']) ? 'dev' : 'prod').'.log',
            'monolog.name' => 'last_whises',
        ]);

        $app->register(
            new TwigServiceProvider(),
            array(
                'twig.path' => __DIR__.'/../../Twig/Views',
            )
        );

        $app->register(new Provider\HttpFragmentServiceProvider());
        $app->register(new Provider\ServiceControllerServiceProvider());

        $app->register(new Provider\WebProfilerServiceProvider(), array(
            'profiler.cache_dir' => __DIR__.'/../cache/profiler',
            'profiler.mount_prefix' => '/_profiler', // this is the default
        ));

        $app->register(new \Sorien\Provider\DoctrineProfilerServiceProvider());

        $app['sign_up_form'] = $app->share(function ($app) {
            return $app['form.factory']
                ->createBuilder('form', null, [
                    'attr' => ['autocomplete' => 'off'],
                ])
                ->add('email', 'email', ['attr' => ['maxlength' => User::MAX_LENGTH_EMAIL, 'class' => 'form-control'], 'label' => 'Email'])
                ->add('password', 'password', ['attr' => ['maxlength' => User::MAX_LENGTH_PASSWORD, 'class' => 'form-control'], 'label' => 'Password'])
                ->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary btn-lg btn-block'], 'label' => 'Sign up'])
                ->getForm();
        });

        $app['sign_in_form'] = $app->share(function ($app) {
            return $app['form.factory']
                ->createBuilder('form', null, [
                    'attr' => ['autocomplete' => 'off'],
                ])
                ->add('email', 'email', ['attr' => ['maxlength' => User::MAX_LENGTH_EMAIL, 'class' => 'form-control'], 'label' => 'Email'])
                ->add('password', 'password', ['attr' => ['maxlength' => User::MAX_LENGTH_PASSWORD, 'class' => 'form-control'], 'label' => 'Password'])
                ->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary btn-lg btn-block'], 'label' => 'Sign in'])
                ->getForm();
        });

        return $app;
    }
}
