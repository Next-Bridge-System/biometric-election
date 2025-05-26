<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\AdminResetPasswordNotification;
use OwenIt\Auditing\Contracts\Auditable;
class Admin extends Authenticatable implements Auditable
{
    use Notifiable;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['name','email','phone','status','password','bar_id','is_super','default_password_changed'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'admin_permissions');
    }

    public function hasPermission($permission) {
        return (bool) $this->permissions->where('slug', $permission)->count();
    }

    public function getAdminPermissions() {
        $perm = '';
        foreach ($this->permissions as $permission) {
            $perm .=$permission->name .", ";
        }
        return rtrim(trim($perm), ',');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }

    public function bar()
    {
        return $this->belongsTo(Bar::class);
    }
}
