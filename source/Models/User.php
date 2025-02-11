<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;
use Exception;

/**
 * Class User
 * @package source\Models
 */
class User extends DataLayer
{
  /**
   * User constructor.
   */
  public function __construct()
  {
    parent::__construct("users", ["first_name", "last_name", "email", "password"]);
  }


  /**
   * salvando usuario
   * @return boolean
   */
  public function save(): bool
  {
    /**
     * Validacoes antes de salvar
     */
    if (
      !$this->validateEmail()
      || !$this->validatePassword()
      || !parent::save()
    ) {
      return false;
    }

    return true;
  }

  /**
   * Validacao de e-mail
   * @return boolean
   */
  protected function validateEmail(): bool
  {
    if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      $this->fail = new Exception("Informe um email valido");
      return false;
    }
    $userByEmail = null;
    if (!$this->id) {
      $userByEmail = $this->find("email = :email", "email={$this->email}")->count();
    } else {
      $userByEmail = $this->find("email = :email AND id != :id", "email={$this->email}&id={$this->id}")->count();
    }

    if ($userByEmail) {
      $this->fail = new Exception("O e-mail informado ja esta em uso");
      return false;
    }
    return true;
  }

  /**
   * Validacao de senha
   * @return boolean
   */
  protected function validatePassword(): bool
  {
    if (empty($this->password) || strlen($this->password) < 5) {
      $this->fail = new Exception("Informe uma senha com pelo menos 5 caracteres!");
      return false;
    }

    if (password_get_info($this->password)["algo"]) {
      return true;
    }

    $this->password = password_hash($this->password, PASSWORD_DEFAULT);
    return true;
  }
}
