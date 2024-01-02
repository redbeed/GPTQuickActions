<?php

namespace App\Api;

use OpenApi\Annotations\OpenApi as OpenApiAnnotation;
use OpenApi\Attributes\Contact;
use OpenApi\Attributes\Info;
use OpenApi\Attributes\OpenApi;
use OpenApi\Attributes\Server;

#[OpenApi(
    openapi: OpenApiAnnotation::VERSION_3_1_0,
    info: new Info(
        version: '0.0.1',
        title: 'Quick Actions for GPT',
        contact: new Contact(
            name: 'Redbeed Team',
            url: 'https://redbeed.com/contact',
            email: 'hello@redbeed.com'
        )
    ),
    servers: [
        new Server(
            url: '/api',
            description: 'Main Stateless Server',
        )
    ]
)]
class ApiDocSpecs
{
}
