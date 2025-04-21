<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UserDto
{
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(['user:write'])]
    private string $email;

    #[Assert\NotBlank]
    #[Groups(['user:write'])]
    private string $firstname;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    #[Groups(['user:write'])]
    private string $password;
    
    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }

    public function getFirstname(): string { return $this->firstname; }
    public function setFirstname(string $firstname): self { $this->firstname = $firstname; return $this; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): self { $this->password = $password; return $this; }
}
