<?php

namespace App\Http\Controllers;

use App\Http\Requests\IcalParseRequest;
use ICal\ICal;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0",
 *      title="Example API",
 *      description="Example info",
 *      @OA\Contact(name="Swagger API Team")
 *  )
 */
class IcalController extends Controller
{
    /**
     * @OA\Get(
     *     path="api/ical",
     *     tags={"ical"},
     *     summary="Get ical as json",
     *     description="Get ical",
     *     @OA\Parameter(
     *         name="url",
     *         in="query",
     *         description="Url to ical",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Start date to filter ical (default: now)",
     *         example="2024-01-01",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             format="date"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="End date to filter ical (default: 2 years in the future from NOW)",
     *         example="2024-01-01",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             format="date"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="text",
     *         in="query",
     *         description="Text to filter ical",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *      @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function parse(IcalParseRequest $request)
    {
        $ical = new ICal($request->url);

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
