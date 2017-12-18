<?php

namespace Form;

use Symfony\Component\Validator\Constraints as Assert;

class LeadValidation
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="3, max="20")
     */
    public $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @Assert\NotBlank()
     */
    public $phone;
}
