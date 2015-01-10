<?php

namespace Lw\Infrastructure\Ui\Web\Silex;

use Ddd\Application\Service\TransactionalApplicationService;
use Ddd\Infrastructure\Application\Service\DoctrineSession;
use Lw\Application\Service\User\SignInUserService;
use Lw\Application\Service\User\ViewWishesService;
use Lw\Application\Service\Wish\AddWishService;
use Lw\Application\Service\Wish\DeleteWishService;
use Lw\Application\Service\Wish\UpdateWishService;
use Lw\Application\Service\Wish\ViewWishService;
use Lw\Domain\Model\User\User;
use Lw\Infrastructure\Domain\Model\User\DoctrineUserFactory;
use Lw\Infrastructure\Persistence\Doctrine\EntityManagerFactory;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;

class Application
{
    public static function bootstrap()
    {
        $app = new \Silex\Application();

        $app['debug'] = true;

        $app['em'] = $app->share(function() {
            return (new EntityManagerFactory())->build();
        });

        $app['em_session'] = $app->share(function($app) {
            return new DoctrineSession($app['em']);
        });

        $app['user_repository'] = $app->share(function($app) {
            return $app['em']->getRepository('Lw\Infrastructure\Domain\Model\User\DoctrineUser');
        });

        $app['wish_repository'] = $app->share(function($app) {
            return $app['em']->getRepository('Lw\Infrastructure\Domain\Model\Wish\DoctrineWishEmail');
        });

        $app['event_repository'] = $app->share(function($app) {
            return $app['em']->getRepository('Lw\Domain\Model\Event\StoredEvent');
        });

        $app['user_factory'] = $app->share(function() {
            return new DoctrineUserFactory();
        });

        $app['view_wishes_application_service'] = $app->share(function($app) {
            return new ViewWishesService(
                $app['wish_repository']
            );
        });

        $app['view_wish_application_service'] = $app->share(function($app) {
            return new ViewWishService(
                $app['user_repository'],
                $app['wish_repository']
            );
        });

        $app['add_wish_application_service'] = $app->share(function($app) {
            return new TransactionalApplicationService(
                new AddWishService(
                    $app['user_repository'],
                    $app['wish_repository']
                ),
                $app['em_session']
            );
        });

        $app['update_wish_application_service'] = $app->share(function($app) {
            return new TransactionalApplicationService(
                new UpdateWishService(
                    $app['user_repository'],
                    $app['wish_repository']
                ),
                $app['em_session']
            );
        });

        $app['delete_wish_application_service'] = $app->share(function($app) {
            return new TransactionalApplicationService(
                new DeleteWishService(
                    $app['user_repository'],
                    $app['wish_repository']
                ),
                $app['em_session']
            );
        });

        $app['sign_in_user_application_service'] = $app->share(function($app) {
            return new TransactionalApplicationService(
                new SignInUserService(
                    $app['user_repository'],
                    $app['user_factory']
                ),
                $app['em_session']
            );
        });

        $app->register(new \Silex\Provider\SessionServiceProvider());
        $app->register(new \Silex\Provider\UrlGeneratorServiceProvider());
        $app->register(new \Silex\Provider\FormServiceProvider());
        $app->register(new \Silex\Provider\TranslationServiceProvider());
        $app->register(
            new \Silex\Provider\TwigServiceProvider(),
            array(
                'twig.path' => __DIR__.'/../../Twig/Views',
            )
        );

        $app['sign_in_form'] = $app->share(function($app) {
            return $app['form.factory']
                ->createBuilder('form')
                ->add('email', 'email', ['max_length' => User::MAX_LENGTH_EMAIL, 'attr' => ['class' => 'form-control'], 'label' => 'Email'])
                ->add('password', 'password', ['max_length' => User::MAX_LENGTH_PASSWORD, 'attr' => ['class' => 'form-control'], 'label' => 'Password'])
                ->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary btn-lg btn-block'], 'label' => 'Sign in'])
                ->getForm();
        });

        return $app;
    }
}
