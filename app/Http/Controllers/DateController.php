<?php

namespace App\Http\Controllers;

use OpenApi\Attributes\Get;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class DateController extends Controller
{

    #[Get(
        path: '/date',
        operationId: 'date',
        description: 'Get current date, optionally subtract or add seconds',
        summary: 'Get current date as json',
        tags: ['current-date'],
        parameters: [
            new Parameter(
                name: 'sub',
                description: 'Subtract seconds from current date',
                in: 'query',
                required: false,
                schema: new Schema(
                    type: 'integer',
                )
            ),
            new Parameter(
                name: 'add',
                description: 'Add seconds to current date',
                in: 'query',
                required: false,
                schema: new Schema(
                    type: 'integer',
                )
            ),
        ],
        responses: [
            new Response(
                response: 200,
                description: 'successful operation'
            )
        ]
    )]
    public function __invoke()
    {
        $date = now();

        if (request()->has('sub')) {
            $date->subSeconds(request()->get('sub'));
        }

        if (request()->has('add')) {
            $date->addSeconds(request()->get('add'));
        }

        return response()->json([
            'date' => $date->format('Y-m-d H:i:s T'),
        ]);
    }
}
