<?php
namespace <%PKGCLASS%>\Entity\Account;

trait AccountTypeTrait
{
    {% if accountTypes %}
    {% for accountType in accountTypes %}
    const ACCOUNT_TYPE_<%accountType|upper%> = <%loop.index0%>;
    {% endfor %}

    /**
     * @ORM\Column(type="integer")
     */
    private $accountType;

    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;
    }

    public function getAccountType()
    {
        return $this->accountType;
    }

    {% for accountType in accountTypes %}
    public function is<%accountType|capitalize%>Account()
    {
        return $this->getAccountType() == self::ACCOUNT_TYPE_<%accountType|upper%>;
    }
    {% endfor %}
    {% endif %}
}