<?php

use Ddd\Domain\DomainEventPublisher;
use Ddd\Domain\PersistDomainEventSubscriber;
use Lw\Application\Service\User\SignUpUserRequest;
use Lw\Application\Service\User\ViewBadgesRequest;
use Lw\Application\Service\User\ViewWishesRequest;
use Lw\Application\Service\Wish\UpdateWishRequest;
use Lw\Domain\Event\LoggerDomainEventSubscriber;
use Lw\Domain\Model\User\UserAlreadyExistsException;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

error_reporting(E_ALL);

require_once __DIR__.'/../../../../../../../vendor/autoload.php';

Debug::enable();

$app = \Lw\Infrastructure\Ui\Web\Silex\Application::bootstrap();

// Home
$app->get('/', function () use ($app) {
    return $app['twig']->render('layout.html.twig');
})->bind('home');

$app->match('/signup', function (Request $request) use ($app) {
    $form = $app['sign_up_form'];
    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();

        try {
            $app['sign_in_user_application_service']->execute(
                new SignUpUserRequest(
                    $data['email'],
                    $data['password']
                )
            );

            return $app->redirect($app['url_generator']->generate('signin'));
        } catch (UserAlreadyExistsException $e) {
            $form->get('email')->addError(new FormError('Email is already registered by another user'));
        } catch (\Exception $e) {
            throw $e;
            $form->addError(new FormError('There was an error, please get in touch with us'));
        }
    }

    return $app['twig']->render('signin.html.twig', [
        'form' => $form->createView(),
    ]);
})->bind('signup');

// Login
$app->match('/signin', function (Request $request) use ($app) {
    /*
     * @var Form
     */
    $form = $app['sign_in_form'];
    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();

        try {
            $userRepository = $app['user_repository'];
            $session = $app['session'];

            $service = new \Lw\Application\Service\User\LogInUserService(
                new \Lw\Infrastructure\Domain\SessionAuthentifier(
                    $userRepository,
                    $session
                )
            );

            $result = $service->execute($data['email'], $data['password']);
            if ($result) {
                return $app->redirect('/dashboard');
            }
        } catch (UserAlreadyExistsException $e) {
            $form->get('email')->addError(new FormError('Email is already registered by another user'));
        } catch (\Exception $e) {
            $form->addError(new FormError('There was an error, please get in touch with us'));
        }
    }

    return $app['twig']->render('login.html.twig', [
        'form' => $form->createView(),
    ]);
})->bind('signin');

$app->get('/signout', function () use ($app) {
    (new \Lw\Application\Service\User\LogOutUserService(
        new \Lw\Infrastructure\Domain\SessionAuthentifier(
            $app['user_repository'],
            $app['session'])
    ))->execute();

    return $app->redirect('/signin');
})->bind('signout');

$app->get('/dashboard', function () use ($app) {
    $userSecurityToken = $app['session']->get('user');
    if (!$userSecurityToken) {
        return $app->redirect('/signin');
    }

    $flashBag = $app['session']->getFlashBag();
    $messages = $flashBag->get('message');

    $response = $app['view_wishes_application_service']->execute(
        new ViewWishesRequest($userSecurityToken->id()->id())
    );

    try {
        $badges = $app['view_badges_application_service']->execute(
            new ViewBadgesRequest($userSecurityToken->id())
        );
    } catch (Exception $e) {
        $badges = [];
    }

    return $app['twig']->render(
        'dashboard.html.twig',
        [
            'wishes' => $response,
            'badges' => $badges,
            'messages' => $messages
        ]
    );
})->bind('dashboard');

$app->post('/wish/add', function (Request $request) use ($app) {
    $userSecurityToken = $app['session']->get('user');
    if (!$userSecurityToken) {
        return $app->redirect('/signin');
    }

    $userId = $userSecurityToken->id()->id();
    try {
        $app[$request->get('aggregate') ? 'add_wish_application_service_aggregate_version' : 'add_wish_application_service']
            ->execute(
                new \Lw\Application\Service\Wish\AddWishRequest(
                    $userId,
                    $request->get('email'),
                    $request->get('content')
                )
            );
        $app['session']->getFlashBag()->add('message', ['info' => 'Great!']);
    } catch (\Exception $e) {
        $app['session']->getFlashBag()->add('message', ['type' => 'danger', 'info' => $e->getMessage()]);
    }

    return $app->redirect('/dashboard');
})->bind('add-wish');

// Update wish
$app->post('/wish/update', function (Request $request) use ($app) {
    $userSecurityToken = $app['session']->get('user');
    if (!$userSecurityToken) {
        return $app->redirect('/signin');
    }

    $userId = $userSecurityToken->id()->id();
    $wishId = $request->get('id');

    $app['update_wish_application_service']
        ->execute(
            new UpdateWishRequest(
                $userId,
                $wishId,
                $request->get('email'),
                $request->get('content')
            )
        );

    return $app->redirect('/dashboard');
})->bind('update-wish');

// Delete wish
$app->get('/wish/delete/{wishId}/{aggregate}', function ($wishId, $aggregate) use ($app) {
    $userSecurityToken = $app['session']->get('user');
    if (!$userSecurityToken) {
        return $app->redirect('/signin');
    }

    $userId = $userSecurityToken->id()->id();

    try {
        $app['delete_wish_application_service'.($aggregate ? '_aggregate_version' : '')]->execute(
            new \Lw\Application\Service\Wish\DeleteWishRequest($wishId, $userId)
        );

        $app['session']->getFlashBag()->add('message', ['info' => 'Deleted!']);
    } catch (\Exception $e) {
        $app['session']->getFlashBag()->add('message', ['info' => 'Error!']);
    }

    return $app->redirect('/dashboard');
})->bind('delete-wish');

$app->get('/wish/{wishId}', function ($wishId) use ($app) {
    $userSecurityToken = $app['session']->get('user');
    if (!$userSecurityToken) {
        return $app->redirect('/signin');
    }

    $userId = $userSecurityToken->id()->id();

    $response = $app['view_wish_application_service']
        ->execute(
            new \Lw\Application\Service\Wish\ViewWishRequest(
                $wishId,
                $userId
            )
        );

    return $app['twig']->render('view-wish.html.twig', ['wish' => $response]);
})->bind('view-wish');

$app->before(function (Symfony\Component\HttpFoundation\Request $request) use ($app) {
    DomainEventPublisher::instance()->subscribe(
        new PersistDomainEventSubscriber(
            $app['event_store']
        )
    );

    DomainEventPublisher::instance()->subscribe(
        new LoggerDomainEventSubscriber()
    );
});

$app->run();
