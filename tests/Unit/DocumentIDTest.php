<?php

declare(strict_types=1);

use App\Domain\User\ValueObject\Document\DocumentID;
use Faker\Factory as Faker;

it('should to be able to create a juridicial person with company document id', function () {
    $faker = Faker::create('pt_BR');
    for ($i = 0; $i < 1000; $i++) {
        expect(DocumentID::validate($faker->cnpj()))
            ->toBeInstanceOf(DocumentID::class);
    }

});
it('should to be able to create a natural person with a brazilian valid document id', function () {
    $faker = Faker::create('pt_BR');
    for ($i = 0; $i < 1000; $i++) {
        expect(DocumentID::validate($faker->cpf()))
            ->toBeInstanceOf(DocumentID::class);
    }

});

it('throw a exception when try to generate a documentID using an invalid company document', function () {
    $document = '11.131.713/0002-09';
    expect(function () use ($document) {
        DocumentID::validate($document);
    })->toThrow(InvalidArgumentException::class, 'Document invalid!');
});
