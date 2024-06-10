<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function __construct(
        private User $model
    ) {
        //
    }
    
    public function getFirstByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }
}
