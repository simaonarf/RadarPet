<?php

namespace Tests\Acceptance\Occurrences;

use Tests\Support\AcceptanceTester;

class AccessCest
{
    public function protectedRoutesRedirectToLogin(AcceptanceTester $I): void
    {
        $I->amOnPage('/posts');

        $I->see('Entrar');
        $I->see('E-mail');
        $I->see('Senha');
        $I->see('Confirmar');
    }
}
