<?php

namespace Lexoffice\Models;

class Contact
{
    protected $company = [];

    protected $person = [];

    protected $isCompany = false;

    protected $contact = [];

    public function __construct()
    {
        $this->contact =[
            'roles' => 'customer',
            'version' => 0
        ];
    }

    public function isCompany()
    {
        $this->isCompany = true;

        return $this;
    }

    public function setCompanyName(string $companyName = '')
    {
        $this->company['name'] = $companyName;

        return $this;
    }

    public function setPerson(bool $isMale = false, string $firstName = '', string $lastName = '')
    {
        if($firstName === '' || $lastName === '')
        {
            throw new \ErrorException('The firstName and lastName must be provided', 422);
        }
        $this->person['salutation'] = $isMale ? 'Herr' : 'Frau';
        $this->person['lastName'] = $lastName;

        return $this;
    }

    public function getData()
    {
        if($this->isCompany)
        {
            $this->contact['company'] = $this->contact;
            $this->contact['companyContact'] = $this->person;
        }

        $this->contact['person'] = $this->person;

        return $this->contact;
    }
}