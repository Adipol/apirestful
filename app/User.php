<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Transformers\UserTransformer;

class User extends Authenticatable
{
    //Notifiable es un trait
    use Notifiable,SoftDeletes;

    protected $table = 'users';
    protected $dates = ['deleted_at'];

    const USUARIO_VERIFICADO    = '1';
    const USUARIO_NO_VERIFICADO = '0';

    const USUARIO_ADMINISTRADOR = 'true';
    const USUARIO_REGULAR       = 'false';

    public $transformer = UserTransformer::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];   
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    //mutador
    public function setNameAttribute($valor)
    {
        $this->attributes['name']= strtolower($valor);
    }

    public function setEmailAttribute($valor)
    {
        $this->attributes['email']= strtolower($valor);
    }

    //ascensor
    public function getNameAttribute($valor)
    {
        //convierte la primera letra de la oracion en mayuscula
        //return ucfirst($valor);
        //convierte la primera letra de cada oracion en mayuscula
        return ucwords($valor);
    }



    public function esVerificado()
    {
        return $this->verified == User::USUARIO_VERIFICADO;
    }

    public function esAdministrador()
    {
        return $this->admin == User::USUARIO_ADMINISTRADOR;
    }

    public static function generarVerificationToken()
    {
        return str_random(40);
    }
}
