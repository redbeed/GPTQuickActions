<?php

namespace App\Http\Controllers;

use App\Http\Requests\IcalParseRequest;
use ICal\ICal;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class IcalController extends Controller
{
    #[Get(
        path: '/ical',
        operationId: 'ical.parse',
        description: 'Get ical',
        summary: 'Get ical as json',
        tags: ['ical'],
        parameters: [
            new Parameter(
                name: 'urls',
                description: 'Url to ical',
                in: 'query',
                required: true,
                schema: new Schema(
                    type: 'array',
                    items: new Items(
                        type: 'string'
                    )
                )
            ),
            new Parameter(
                name: 'start_date',
                description: 'Start date to filter ical (default: now)',
                in: 'query',
                required: false,
                schema: new Schema(
                    type: 'string',
                    format: 'date'
                )
            ),
            new Parameter(
                name: 'end_date',
                description: 'End date to filter ical (default: 2 years in the future from NOW)',
                in: 'query',
                required: false,
                schema: new Schema(
                    type: 'string',
                    format: 'date'
                )
            ),
        ],
        responses: [
            new Response(
                response: 200,
                description: 'successful operation'
            ),
            new Response(
                response: 400,
                description: 'Bad request'
            )
        ]
    )]
    public function parse(IcalParseRequest $request)
    {
        $ical = new ICal($request->urls);

        if (empty($request->start_date) && empty($request->end_date) && empty($request->text)) {
            return response()->json($ical->events());
        }

        // always filter by date (default: now until 2 years in the future)
        $events = $ical->eventsFromRange($request->start_date, $request->end_date);

        // Filter by text if provided
        if (!empty($request->text)) {
            $events = array_filter($events, function ($event) use ($request) {
                return str_contains($event->summary, $request->text) || str_contains($event->description,
                        $request->text) || str_contains($event->location, $request->text);
            });
        }

        return response()->json(array_values($events));
    }
}
