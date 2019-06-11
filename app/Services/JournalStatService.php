<?php


namespace App\Services;


use App\Journal;
use Carbon\CarbonInterface;

class JournalStatService
{
    /**
     * @param CarbonInterface $from
     * @param CarbonInterface $to
     * @return array
     */
    public function getPerBookByPeriod(CarbonInterface $from, CarbonInterface $to): array
    {
        return Journal::with('book')
            ->selectRaw("DATE_FORMAT(created_at,'%Y-%m') as date, book_id, count(*) as value")
            ->filterByDate($from, $to)
            ->groupBy(['date', 'book_id'])
            ->orderBy('date')
            ->get()
            ->map(function($item) {
                return [
                    'date' => $item->date,
                    'title' => $item->book->title,
                    'value' => $item->value,
                ];
            })
            ->toArray();
    }

    /**
     * @param CarbonInterface $from
     * @param CarbonInterface $to
     * @return array
     */
    public function getTotalPerMonths(CarbonInterface $from, CarbonInterface $to): array
    {
        return Journal::selectRaw("DATE_FORMAT(created_at,'%Y-%m') as date, count(*) as value")
            ->filterByDate($from, $to)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function($item) {
                return [
                    'date' => $item->date,
                    'value' => $item->value,
                ];
            })
            ->toArray();
    }

    /**
     * @return array
     */
    public function getTotalPerYears(): array
    {
        return Journal::selectRaw('YEAR(created_at) as date, count(*) as value')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function($item) {
                return [
                    'date' => $item->date,
                    'value' => $item->value,
                ];
            })
            ->toArray();
    }

    /**
     * @param CarbonInterface|null $from
     * @param CarbonInterface|null $to
     * @return int
     */
    public function getTotalByPeriod(?CarbonInterface $from = null, ?CarbonInterface $to = null): int
    {
        return (int) Journal::filterByDate($from, $to)->count();
    }
}
