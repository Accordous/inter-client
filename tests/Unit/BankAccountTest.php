<?php

namespace Accordous\InterClient\Tests\Unit;

use Accordous\InterClient\Enums\BankAccountType;
use Accordous\InterClient\Services\InterService;
use Accordous\InterClient\Tests\TestCase;
use Accordous\InterClient\ValueObject\Bank;
use Accordous\InterClient\ValueObject\BankAccount;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;

class BankAccountTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function canCreateInterBankAccounts()
    {
        $service = new InterService(Config::get('inter.token'));

        $bank = new Bank($this->faker->numerify('###'));

        $bankAccount = new BankAccount(
            $bank,
            $this->faker->name,
            $this->faker->name,
            $this->faker->date,
            $this->faker->numerify('###########'),
            $this->faker->numerify('###'),
            $this->faker->numerify('######'),
            $this->faker->numerify('#'),
            $this->faker->randomElement([BankAccountType::CONTA_CORRENTE, BankAccountType::CONTA_POUPANCA])
        );

        $response = $service->bankAccounts()->store([
            'bank' => $bank->code,
            'accountName' => $bankAccount->accountName,
            'name' => $bankAccount->ownerName,
            'cpfCnpj' => $bankAccount->cpfCnpj,
            'agency' => $bankAccount->agency,
            'account' => $bankAccount->account,
            'accountDigit' => $bankAccount->accountDigit,
            'bankAccountType' => $bankAccount->bankAccountType,
            'thirdPartyAccount' => $this->faker->boolean,
        ]);

        $this->assertEquals('200', $response->getStatusCode());
    }
}
