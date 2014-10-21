<?php

use Lw\Application\Service\User\SignInUserRequest;
use Lw\Domain\Model\User\UserAlreadyExistsException;
use Symfony\Component\HttpFoundation\Request;

$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/Application.php';

$app = \Application::bootstrap();

// Home
$app->get('/', function () use ($app) {
    return $app['twig']->render('layout.html.twig');
})->bind('home');

// SigIn
$app->get('/signin', function () use ($app) {
    return $app['twig']->render('signin.html.twig');
})->bind('signin');

$app->post('/signin', function (Request $request) use ($app) {
    $app['sign_in_user_application_service']->execute(
        new SignInUserRequest(
            $request->get('email'),
            $request->get('password')
        )
    );

    return $app->redirect('/login');
});

$app->post('/users', function (Request $request) use ($app) {
    $request = json_decode($request->getContent());

    $response = ['result' => false];
    try {
        $app['sign_in_user_application_service']->execute(
            isset($request->email) ? $request->email : null,
            isset($request->password) ? $request->password : null
        );

        $response = ['result' => true];
    } catch(UserAlreadyExistsException $e) {
        $response['email'] = 'Email already taken';
    } catch(\InvalidArgumentException $e) {
        $response[$e->getMessage()] = 'Parameter mandatory';
    } catch(\Exception $e) {
        $response['_form'] = 'General error';
    }

    return $app->json($response, $response['result'] ? 200 : 500);
});

// Login
$app->get('/login', function () use ($app) {
    return $app['twig']->render('login.html.twig');
})->bind('login');

$app->post('/login', function (Request $request) use ($app) {
    $userRepository = $app['user_repository'];
    $session = $app['session'];

    $authentifier = new \Lw\Infrastructure\Domain\SessionAuthentifier($userRepository, $session);
    $service = new \Lw\Application\Service\User\LogInUserService($authentifier);
    $result = $service->execute($request->get('email'), $request->get('password'));

    return $result ? $app->redirect('/dashboard') : $app->redirect('/login');
});

// Logout
$app->get('/logout', function () use ($app) {
    $userRepository = $app['user_repository'];
    $session = $app['session'];

    $authentifier = new \Lw\Infrastructure\Domain\SessionAuthentifier($userRepository, $session);
    $service = new \Lw\Application\Service\User\LogOutUserService($authentifier);
    $result = $service->execute();
    return $app->redirect('/login');
})->bind('logout');

// Dashboard
$app->get('/wish/list', function () use ($app) {
    $userSecurityToken = $app['session']->get('user');
    if (!$userSecurityToken) {
        return $app->redirect('/login');
    }

    $userId = $userSecurityToken->id();
    $usecase = new \Lw\Application\Service\User\ViewWishesService($app['wish_repository']);
    $response = $usecase->execute($userId);

    $wishes = array_map(function($wish) {
        return [
            'id' => $wish->id()->id(),
            'email' => $wish->email(),
            'content' => $wish->content()
        ];
    }, $response);

    return $app->json($wishes);
});

$app->get('/dashboard', function () use ($app) {
    $userSecurityToken = $app['session']->get('user');
    if (!$userSecurityToken) {
        return $app->redirect('/login');
    }

    $userId = $userSecurityToken->id();
    $usecase = new \Lw\Application\Service\User\ViewWishesService($app['wish_repository']);
    $response = $usecase->execute($userId);

    return $app['twig']->render('dashboard.html.twig', ['wishes' => $response]);
})->bind('dashboard');

// Add wish
$app->post('/wish/add', function (Request $request) use ($app) {
    $userSecurityToken = $app['session']->get('user');
    if (!$userSecurityToken) {
        return $app->redirect('/login');
    }

    $userId = $userSecurityToken->id();

        try {
            $app['add_wish_application_service']
                ->execute(
                    $userId,
                    $request->get('email'),
                    $request->get('content')
                );
        } catch(\Exception $e) {
            $app['session']->getFlashBag()->add('error', $e->getMessage());
        }

    return $app->redirect('/dashboard');
})->bind('add-wish');

// Update wish
$app->post('/wish/update', function (Request $request) use ($app) {
    $userSecurityToken = $app['session']->get('user');
    if (!$userSecurityToken) {
        return $app->redirect('/login');
    }

    $userId = $userSecurityToken->id()->id();
    $wishId = $request->get('id');

    $app['update_wish_application_service']
        ->execute(
            $userId,
            $wishId,
            $request->get('email'),
            $request->get('content')
        );

    return $app->redirect('/dashboard');
})->bind('update-wish');

// Delete wish
$app->get('/wish/delete/{wishId}', function ($wishId) use ($app) {
    $userSecurityToken = $app['session']->get('user');
    if (!$userSecurityToken) {
        return $app->redirect('/login');
    }

    $userId = $userSecurityToken->id()->id();

    $result = new \stdClass();
    $result->error = false;
    $result->message = '';

    try {
        $usecase = $app['delete_wish_application_service'];
        $usecase->execute($userId, $wishId);
    } catch(\Exception $e) {
        $result->error = true;
        $result->message = $e->getMessage();
    }

    $app['session']->getFlashBag()->add('message', $result);

    return $app->redirect('/dashboard');
})->bind('delete-wish');

$app->get('/wish/{wishId}', function ($wishId) use ($app) {
    $userSecurityToken = $app['session']->get('user');
    if (!$userSecurityToken) {
        return $app->redirect('/login');
    }

    $userId = $userSecurityToken->id()->id();

    // \Lw\Application\Service\Wish\ViewWishService
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
    \Lw\Domain\DomainEventPublisher::instance()->subscribe(
        new \Lw\Domain\PersistDomainEventSubscriber(
            $app['event-repository']
        )
    );
});

// RESTful
/*
$app->post('/wish/{wishId}', function ($wishId, Request $request) use ($app) {
    $userSecurityToken = $app['session']->get('user');
    if (!$userSecurityToken) {
        return $app->redirect('/login');
    }

    $userId = $userSecurityToken->id();

    // \Lw\Application\Service\Wish\AddWishService
    $response = $app['update_wish_application_service']
        ->execute(
            $userId,
            $wishId,
            $request->get('email'),
            $request->get('content')
        );

    return $app->redirect('/dashboard');
});

$app->delete('/wish/{wishId}', function ($wishId) use ($app) {
    $userSecurityToken = $app['session']->get('user');
    if (!$userSecurityToken) {
        return $app->json(['message' => 'Not logged'], 403);
    }

    $userId = $userSecurityToken->id();
    $usecase = new \Lw\Application\Service\Wish\ViewWishService($app['wish_repository']);
    try {
        $usecase->execute($userId->id(), $wishId);
        return $app->json(['message' => 'ok']);
    } catch(\Exception $e) {
        return $app->json(['message' => $e->getMessage()], 500);
    }
});

$app->put('/wish/{wishId}', function (Request $request, $wishId) use ($app) {
    $userSecurityToken = $app['session']->get('user');
    if (!$userSecurityToken) {
        return $app->json(['message' => 'Not logged'], 403);
    }

    $userId = $userSecurityToken->id();
    $usecase = new \Lw\Application\Service\Wish\UpdateWishService($app['wish_repository']);
    try {
        $usecase->execute($userId->id(), $wishId, $request->get('email'), '');
        return $app->json(['message' => 'ok']);
    } catch(\Exception $e) {
        return $app->json(['message' => $e->getMessage()], 500);
    }
});
*/
$app->run();
