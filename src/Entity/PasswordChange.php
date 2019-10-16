<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class PasswordChange
{
    private $oldPassword;

    /**
     * @Assert\Length(min=8,minMessage="Votre mot de passe doit contenir 2 caracteres minimum")
     */
    private $newPassword;

    /**
     * @Assert\EqualTo(propertyPath="newPassword", message="Les mots de passe ne sont pas identiques...")
     */
    public $passwordConfirm;

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getpasswordConfirm(): ?string
    {
        return $this->passwordConfirm;
    }

    public function setpasswordConfirm(string $passwordConfirm): self
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }
}
