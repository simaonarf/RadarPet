<?php

namespace Tests\Acceptance\Occurrences;

use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class OccurrenceCreateCest extends BaseAcceptanceCest
{
    public function createOccurrenceSuccessfully(AcceptanceTester $I): void
    {
        // 1) LOGIN DO USUÁRIO COMUM
        $I->amOnPage('/login');

        $I->fillField('input[name="user[email]"]', 'user@demo.com');
        $I->fillField('input[name="user[password]"]', 'user123');

        $I->click('Confirmar');

        $I->see('Login realizado com sucesso');

        // 2) IR PARA A LISTA DE POSTS (já populados pelo seeder)
        $I->amOnPage('/posts');

        // Usa um título de post que já aparece na lista /posts
        $tituloPost = 'Post para Teste de Ocorrência';

        $I->see($tituloPost);

        // Clica no post para ir para a tela de detalhes (/posts/{id}/show)
        $I->click($tituloPost);

        // Garante que está na tela do post e que o formulário de ocorrência aparece
        $I->see('Registrar Ocorrência');

        // 3) REGISTRAR A OCORRÊNCIA NESTE POST
        $I->fillField('input[name="location"]', 'Rua Central, próximo à praça');
        $I->fillField('textarea[name="description"]', 'Vi esse cãozinho aqui no bairro.');

        $I->click('Registrar Ocorrência');

        // 4) Validar mensagem de sucesso
        $I->see('Ocorrência registrada com sucesso!');

        // 5) Validar dados listados na seção de ocorrências
        $I->see('Ocorrências registradas');
        $I->see('Rua Central, próximo à praça');
        $I->see('Vi esse cãozinho aqui no bairro.');
    }
}
