hola {{ $user->name }}
has cambiado tu correo electrónico. Por favor verifica la nueva dirección usando el siguiente enlace:

{{ route('verify', $user->verification_token) }}