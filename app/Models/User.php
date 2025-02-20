<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Student;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function isAdmin()
    {
        return $this->roles()->where('name', 'admin')->orWhere('name', 'super admin')->exists();
    }
    public function isSekretaris()
    {
        return $this->roles()->whereName('sekretariat')->exists();
    }
    public function isSantriBaru()
    {
        return $this->roles()->whereName('santri_baru')->exists();
    }
    public function isManager()
    {
        if ($this->isAdmin() or $this->isSekretaris()) {
            return true;
        }
        return false;
    }
    public function isTamu()
    {
        return $this->roles()->where('name', 'tamu')->exists();
    }
    public function student()
    {
        return $this->hasOne(Student::class);
    }
}
