<?php

namespace Tests\Acceptance\Occurrences;

use Tests\Support\AcceptanceTester;
use Tests\Acceptance\BaseAcceptanceCest;

class OccurrenceDeleteCest extends BaseAcceptanceCest
{
    public function deleteOccurrenceSuccessfully(AcceptanceTester $I): void
    {
        $I->amOnPage('/login');
        $I->fillField('input[name="user[email]"]', 'user@demo.com');
        $I->fillField('input[name="user[password]"]', 'user123');
        $I->click('Confirmar');
        $I->see('Login realizado com sucesso');

        $I->amOnPage('/posts');
        $tituloPost = 'Post para Teste de Ocorrência';
        $I->see($tituloPost);
        $I->click($tituloPost);

        if (str_contains($I->grabPageSource(), 'Remover minha ocorrência')) {
            $I->click('Remover minha ocorrência');
            $I->see('Ocorrência removida!');
        }

        $I->see('Registrar Ocorrência');

        $I->fillField('input[name="location"]', 'Rua Central - Remoção');
        $I->fillField('textarea[name="description"]', 'Ocorrência para remover via teste.');

        $I->click('Registrar Ocorrência');

        $I->see('Ocorrência registrada com sucesso!');
        $I->see('Ocorrências registradas');
        $I->see('Ocorrência para remover via teste.');

        $I->click('Remover minha ocorrência');

        $I->see('Ocorrência removida!');
        $I->dontSee('Ocorrência para remover via teste.');
        $I->dontSee('Rua Central - Remoção');
    }
}
