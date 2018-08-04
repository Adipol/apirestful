hola {{ $user->name }}
Gracias por crear una cuenta. Por favor verifica usando el siguiente enlace:

{{ route('verify', $user->verification_token) }}