<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatResource;
use App\Services\JournalStatService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class BookStatController extends Controller
{
    /**
     * @var JournalStatService
     */
    protected $service;

    /**
     * BookStatController constructor.
     *
     * @param JournalStatService $service
     */
    public function __construct(JournalStatService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return AnonymousResourceCollection
     */
    public function __invoke(Request $request)
    {
        try {
            $from = Carbon::createFromIsoFormat('DD-MM-YYYY', $request->input('from'))->toImmutable();
            $to = Carbon::createFromIsoFormat('DD-MM-YYYY', $request->input('to'))->toImmutable();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid period'], Response::HTTP_BAD_REQUEST);
        }

        $stat = collect($this->service->getPerBookByPeriod($from->startOfDay(), $to->endOfDay()))
            ->merge($this->service->getTotalPerMonths($from->startOfMonth(), $to->endOfMonth()))
            ->merge($this->service->getTotalPerYears())
            ->merge([['value' => $this->service->getTotalByPeriod()]]);

        return StatResource::collection($stat);
    }
}
