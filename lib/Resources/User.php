<?php

namespace Shoprunback\Resources;

class User extends ApiObject
{
    public $first_name;
    public $last_name;
    public $email;
    public $company_id;
    public $created_at;
    public $owner;
    public $pending;
    public $manager;
    public $auth_token;

    public static function getApiUrlResource()
    {
        return 'me';
    }

    public static function getApiUrlReference()
    {
        return 'id';
    }

    public function save($noId = true)
    {
        parent::save(true);
    }

    public function display()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}