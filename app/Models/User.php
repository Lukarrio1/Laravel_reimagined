<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use HasApiTokens;
    // use SoftDeletes;

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

    public function updateFieldTemplate()
    {
        return [
            'id' => "<input type='hidden' value='".$this->id."' name='id'></input>",
            'name' => "<div class='mb-3'>
                    <label for='name' class='form-label'>Name</label>
                    <input
                    type='text'
                    class='form-control'
                    id='name'
                     aria-describedby='name'
                     name='name'
                     value='" . $this->name . "'>
                </div>",
            'email' => "<div class='mb-3'>
                    <label for='name' class='form-label'>Email</label>
                    <input
                    type='text'
                    class='form-control'
                    id='email'
                     aria-describedby='email'
                     name='email'
                     value='" . optional($this)->email . "'>
                </div>",
            'password' => "<div class='mb-3'>
                    <label for='name' class='form-label'>Password</label>
                    <input
                    type='password'
                    class='form-control'
                    id='password'
                     aria-describedby='password'
                     name='password'
                     value=''>
                </div>",
            'confirm_password' => "<div class='mb-3'>
                    <label for='name' class='form-label'>Confirm Password</label>
                    <input
                    type='password'
                    class='form-control'
                     id='confirm_password'
                     aria-describedby='confirm_password'
                     name='confirm_password'
                     value=''>
                </div>",
        ];
    }

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function updateUserHtml()
    {
        $this->updateHtml =collect($this->updateFieldTemplate())->join(' ');
        return $this;
    }
}
