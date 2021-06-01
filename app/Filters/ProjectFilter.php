<?php


namespace App\Filters;



use DB;
use Illuminate\Support\Carbon;


class ProjectFilter extends QueryFilter
{

    public function rules(): array
    {
       return [
           'search' => 'filled',
           'status' => 'in:finished,ongoing',
           'deadline' => 'in:current,expired',
           'to' => 'date_format:d/m/Y',
           'from' => 'date_format:d/m/Y',
           'budget' => 'numeric|min:1|max:10',
           'teams' => 'numeric',
           'workers' => 'numeric',
           ];
    }

    public function workers($query, $workers)
    {
        //Esta query es el equivalente a un withcount pero con otro inner join para meter los usuarios de cada team
        $subquery = (DB::table("teams")
                        ->select(DB::raw("COUNT(*) as teams_count"))
                        ->join("project_team","project_team.team_id","=","teams.id")
                        ->join("users","users.team_id","=","teams.id")
                        ->whereColumn('project_team.project_id', 'projects.id')); //Importante escribirlo exactamente

        return $query->where(DB::raw("({$subquery->toSql()})"), $workers);
    }

    public function teams($query, $teams)
    {
        $query->withCount('teams')->having('teams_count', $teams);
    }

    public function budget($query, $budget)
    {
        return $query->where('budget', '<=', $budget*1000);
    }

    public function to($query, $date, $operator = '<=')
    {
        $this->dateRange($date, $query, $operator);
    }

    public function from($query, $date, $operator = '>=')
    {
        $this->dateRange($date, $query, $operator);
    }

    public function deadline($query, $deadline, $operator = '>=')
    {
        if($deadline == 'expired'){
            $operator = '<=';
        }
        return $query->whereDate('finish_date', $operator, now());

    }

    public function status($query, $status)
    {
        return $query->where('status', $status == 'finished');
    }

    public function search($query, $search)
    {
        return $query->where(function ($query) use ($search){
            return $query->where('title', 'like', "%$search%")
                ->orWhere('about', 'like',  "%$search%" )
                ->orWhereHas('teams', function ($query) use ($search){
                   return $query->where('name', 'like', "%$search%")
                       ->where('is_head_team', 1); //Desde el pivote
                });
        });
    }

    /**
     * @param $date
     * @param $query
     * @param $operator
     */
    public function dateRange($date, $query, $operator): void
    {
        $date = Carbon::createFromFormat('d/m/Y', $date);
        $query->whereDate('finish_date', $operator, $date);
    }
}