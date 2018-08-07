<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\User;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    //puede introducir un objeto o array en nuestro caso sera objeto User
    public function transform(User $user)
    {
        return [
            'identificador'      => (int)$user->id,
            'nombre'             => (string)$user->name,
            'correo'             => (string)$user->email,
            'esVerificado'       => (int)$user->verified,
            'esAdministrador'    => ($user->admin === 'true'),
            'fechaCreacion'      => (string)$user->created_at,
            'fechaActualizacion' => (string)$user->updated_at,
            'fechaEliminacion'   => isset($user->updated_at) ? (string) $user->deleted_at : null,
        ];
    }
}