<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Lunch;
use App\LunchUser;

class LunchMatchService
{
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
        $lunch = new Lunch();
        $lunches = $lunch->getMyLunches($myUserId, $date->toDateTimeString());

        // TODO: 日本の祝日も除外する
        for ($i = 0; $i < 10; $i++) {
            $date->addDay(1);
            if ($date->isWeekday()) {
                if (!in_array($date->toDateTimeString(), array_pluck($lunches, 'lunch_at'))) {
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
    public function getCandidates(int $myUserId, array $candidateDates)
    {
        foreach ($candidateDates as $candidateDate) {
            // 指定日に予定があるユーザー一覧
            $reservedUsers = DB::table('lunches')
                ->join('lunch_users', 'lunches.id', '=', 'lunch_users.lunch_id')
                ->where('lunches.lunch_at', '=' ,$candidateDate->toDateTimeString())
                ->select('lunch_users.user_id')
                ->get();

            // 全ユーザーから予定のあるユーザーを除外がする。残った人が候補者となる。
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
        return DB::transaction(function() use ($myUserId, $matchedLunch) {
            $result = null;

            $lunch = Lunch::create([
                'lunch_at' => $matchedLunch['date']->toDateTimeString()
            ]);

            $result = $lunch->toArray();

            $lunchUsers[] = $lunch->lunchUsers()->create([
                'lunch_id' => $lunch->id,
                'user_id' => $myUserId
            ])->toArray();
            foreach ($matchedLunch['candidates'] as $candidate) {
                $lunchUsers[] = $lunch->lunchUsers()->create([
                    'lunch_id' => $lunch->id,
                    'user_id' => $candidate->id
                ])->toArray();
            }
            $result['lunch_users'] = $lunchUsers;

            return $result;
        });
    }

    public function shuffleLunch(int $myUserId)
    {
        $baseDate = $this->calculateBaseDate();
        $candidateDates = $this->getCandidateDates($myUserId, $baseDate);
        $candidates = $this->getCandidates($myUserId, $candidateDates);
        return $this->saveLunch($myUserId, $candidates);
    }
}