<?php

namespace App\Filters;

use App\User;

    Class ThreadFilters extends Filters
    {

        protected $filters = ['by'];

        
        /**
         * Filter query by given username
         *
         * @param string $username
         * @return mixed
         */
        protected function by($username)
        {
            // sets user to username
            $user = User::where('name', $username)->firstOrFail();

            //returns the builder where the user id = user var requested
            return $this->builder->where('user_id', $user->id);
        }
    }

?>
