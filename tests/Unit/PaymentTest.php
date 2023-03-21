<?php

namespace Accordous\InterClient\Tests\Unit;

use Accordous\InterClient\Services\InterService;
use Accordous\InterClient\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;

class PaymentTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function canCreatePayment()
    {
        $service = new InterService(Config::get('inter.token'));

        $customer = $service->customers()->store([
            'name' => $this->faker->name,
            'cpfCnpj' => $this->faker->numerify('538.861.930-39'), // fake valid cpf
            'postalCode' => $this->faker->numerify('########'),
            'email' => $this->faker->email
        ])->json();

        $payment = $service->payments()->store([
            'customer' => $customer['id'],
            'billingType' => 'BOLETO',
            'value' => 5,
            'dueDate' => now()
        ])->json();

        $service->customers()->destroy($customer['id']);
        $service->payments()->destroy($payment['id']);

        $this->assertEquals('payment', $payment['object']);
    }

    /**
     * @test
     */
    public function canDestroyPayment()
    {
        $service = new InterService(Config::get('inter.token'));

        $customer = $service->customers()->store([
            'name' => $this->faker->name,
            'cpfCnpj' => $this->faker->numerify('538.861.930-39'), // fake valid cpf
            'postalCode' => $this->faker->numerify('########'),
            'email' => $this->faker->email
        ])->json();

        $payment = $service->payments()->store([
            'customer' => $customer['id'],
            'billingType' => 'BOLETO',
            'value' => 5,
            'dueDate' => now()
        ])->json();

        $service->customers()->destroy($customer['id']);
        $removed = $service->payments()->destroy($payment['id']);

        $this->assertEquals('200', $removed->getStatusCode());
    }
}
