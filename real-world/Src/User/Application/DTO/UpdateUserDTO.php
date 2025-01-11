<?php
namespace User\Application\DTO;

use Illuminate\Support\Facades\Log;

class UpdateUserDTO
{
    public ?string $username;
    public ?string $email;
    public ?string $password;
    public ?string $bio;
    public ?string $image;
    public array $flags;

    public function __construct(array $data)
    {
        //Log::debug('An informational {message}',['message' => $data]);

        $this->username = $data['username'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->password = isset($data['password']) ? bcrypt($data['password']): null;
        $this->image = $data['image'] ?? null;
        $this->bio = $data['bio'] ?? null;

        $this->flags = [
            'username' => isset($data['username']),
            'email' => isset($data['email']),
            'password' => isset($data['password']),
            'image' => isset($data['image']),
            'bio' => isset($data['bio']),
        ];
    }

    public function validate(): void
    {
        // Aquí puedes agregar lógica de validación si es necesario
        if ($this->email && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Email no válido.");
        }
        // Otras validaciones...
    }

    public function toArray(): array
    {
        $data = array();

        if( $this->flags['email'] ) {
            $data['email'] = $this->email;
        }
        if( $this->flags['username'] ) {
            $data['username'] = $this->username;
        }
        if( $this->flags['password'] ) {
            $data['password'] = $this->password;
        }
        if( $this->flags['bio'] ) {
            $data['bio'] = $this->bio;
        }
        if( $this->flags['image'] ) {
            $data['image'] = $this->image;
        }
        return $data;
    }

}
