<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Lunch;
use App\LunchUser;

class LunchMatchService
{
    private $candidateDate = [];

    public function calculateBaseDate()
    {
        $now = Carbon::now();
        if ($now->hour >= 13) {
            $now->addDay(1);
        }

        $now->hour = 13;
        $now->minute = 0;
        $now->second = 0;

        return $now;
    }

    /**
     * ランチの候補日を取得する
     *
     * @param int $myUserId
     * @param Carbon $date
     * @return array
     */
    public function getCandidateDates(int $myUserId, Carbon $date)
    {
        $candidateDates = [];

        // 指定日以降の自分のランチスケジュールを取得する
        $schedules = DB::table('lunches')
            ->join('lunch_users', 'lunches.id', '=', 'lunch_users.lunch_id')
            ->where('lunch_users.user_id', '=', $myUserId)
            ->where('lunches.lunch_at', '>=' ,$date->toDateTimeString())
            ->select('lunches.lunch_at')
            ->get();


        // TODO: 日本の祝日も除外する
        for ($i = 0; $i < 30; $i++) {
            $date->addDay(1);
            if ($date->isWeekday()) {
                if (!in_array($date->toDateTimeString(), array_pluck($schedules, 'lunch_at'))) {
                    array_push($candidateDates, clone $date);
                }
            }
        }

        return $candidateDates;
    }

    /**
     * ランチ候補者を取得する
     *
     * @param int $myUserId
     * @param array $candidateDates
     * @return mixed
     */
    public function shuffleLunch(int $myUserId, array $candidateDates)
    {
        foreach ($candidateDates as $candidateDate) {
            // 指定日に予定があるユーザー一覧
            $reservedUsers = DB::table('lunches')
                ->join('lunch_users', 'lunches.id', '=', 'lunch_users.lunch_id')
                ->where('lunches.lunch_at', '=' ,$candidateDate->toDateTimeString())
                ->select('lunch_users.user_id')
                ->get();

            // ランチの候補者
            $candidateUsers = DB::table('users')
                ->where('id', '<>', $myUserId)
                ->whereNotIn('id', array_pluck($reservedUsers, 'user_id'))
                ->orderBy(DB::raw('RAND()'))
                ->take(3)
                ->get();

            if (count($candidateUsers) > 0) {
                return [
                    'date' => $candidateDate,
                    'candidates' => $candidateUsers
                ];
            }
        }
    }

    /**
     * ランチを登録する
     *
     * @param int $myUserId
     * @param array $matchedLunch
     */
    public function saveLunch(int $myUserId, array $matchedLunch)
    {
        DB::transaction(function() use ($myUserId, $matchedLunch) {
            $lunch = Lunch::create([
                'lunch_at' => $matchedLunch['date']->toDateTimeString()
            ]);

            $lunch->lunchUsers()->create([
                'lunch_id' => $lunch->id,
                'user_id' => $myUserId
            ]);
            foreach ($matchedLunch['candidates'] as $candidate) {
                $lunch->lunchUsers()->create([
                    'lunch_id' => $lunch->id,
                    'user_id' => $candidate->id
                ]);
            }

        });
    }
}