<?php

namespace App\Components\Classes\Chat;

use App\Http\Controllers\API\ChatController;

class ChatRoutes
{
    public static function routes($attributes = [])
    {
        if(app()->routesAreCached())
            return;

        if(!isset($attributes['middleware']))
            $attributes['middleware'] = [];
        if(!is_array($attributes['middleware']))
            $attributes['middleware'] = [$attributes['middleware']];

        $attributes['middleware'][] = 'auth:api';
        $attributes['middleware'][] = 'signup.done';
        $attributes['prefix'] = 'api/';

        app('router')->group($attributes, function ($router) {

            $controller = '\\' . ChatController::class;

            $router->get('chats', $controller . '@chats');
//            $router->post('chats', $controller . '@create');
            $router->get('chats/pet/{pet}', $controller . '@pet');
            $router->get('chats/{room}', $controller . '@roomMessages');
            $router->post('chats/{room}', $controller . '@send');
            $router->delete('chats/{room}', $controller . '@destroy');
            $router->post('chats/to/read', $controller . '@read');
            $router->get('chats/is/read', $controller . '@isRead');
            $router->post('chats/to/chat-search', $controller . '@searchChat');
            $router->get('chats/{room}/{message}/like', $controller . '@likeMessage');

        });
    }
}