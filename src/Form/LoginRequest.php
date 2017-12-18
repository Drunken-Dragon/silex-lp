<?php

namespace Form;

use Symfony\Component\Validator\Constraints as Assert;

class LoginRequest
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="20")
     */
    public $login;

    /**
     * @Assert\NotBlank()
     */
    public $password;
}
