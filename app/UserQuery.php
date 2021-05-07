<?php

namespace App;

class UserQuery extends QueryBuilder
{
    public function findByEmail($email)
    {
        return $this->where(compact('email'))->first();
    }

    public function withLastLogin()
    {
        $subselect = Login::select('logins.created_at')
            ->whereColumn('logins.user_id', 'users.id')
            ->latest() // orderByDesc('created_at')
            ->limit(1);

        return $this->addSubquery($subselect, 'last_login_at');
    }

    public function withTwitter()
    {
        $subselect = UserProfile::select('user_profiles.twitter')
            ->whereColumn('user_profiles.user_id', 'users.id')
            ->limit(1);

        return $this->addSubquery($subselect, 'twitter');
    }

    /**
     * @param $subselect
     * @return UserQuery
     */
    public function addSubquery($subselect, $as): UserQuery
    {
        return $this->addSelect([
            $as => $subselect, //El alias tiene que declararse como alias en las rules del filtro
        ]);
    }
}
