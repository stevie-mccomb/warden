<?php

namespace Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Stevie\Warden\Traits\HasRoles;

class User extends Model implements Authenticatable
{
    use HasRoles;

    public int $id = 0;
    public string $password = 'secret';
    public string $remember_token = 'example';

    public function getAuthIdentifier() { return $this->id; }
    public function getAuthIdentifierName() { return 'id'; }
    public function getAuthPasswordName() { return 'password'; }
    public function getAuthPassword() { return $this->password; }
    public function getRememberToken() { return $this->remember_token; }
    public function getRememberTokenName() { return 'remember_token'; }
    public function setRememberToken($value) { $this->remember_token = $value; }
}
