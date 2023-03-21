<?php

namespace Accordous\InterClient\Tests\Unit;

use Accordous\InterClient\Services\InterService;
use Accordous\InterClient\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;

class SubscriptionTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function canCreateSubscription()
    {
        $service = new InterService(Config::get('inter.token'));

        $customer = $service->customers()->store([
            'name' => $this->faker->name,
            'cpfCnpj' => $this->faker->numerify('538.861.930-39'), // fake valid cpf
            'postalCode' => $this->faker->numerify('########'),
            'email' => $this->faker->email
        ])->json();

        $subscription = $service->subscriptions()->store([
            'customer' => $customer['id'],
            'billingType' => 'BOLETO',
            'value' => 5,
            'nextDueDate' => now()->addMonth(),
            'cycle' => 'MONTHLY',
        ])->json();

        $service->customers()->destroy($customer['id']);
        $service->subscriptions()->destroy($subscription['id']);

        $this->assertEquals('subscription', $subscription['object']);
    }

    /**
     * @test
     */
    public function canDestroySubscription()
    {
        $service = new InterService(Config::get('inter.token'));

        $customer = $service->customers()->store([
            'name' => $this->faker->name,
            'cpfCnpj' => $this->faker->numerify('538.861.930-39'), // fake valid cpf
            'postalCode' => $this->faker->numerify('########'),
            'email' => $this->faker->email
        ])->json();

        $subscription = $service->subscriptions()->store([
            'customer' => $customer['id'],
            'billingType' => 'BOLETO',
            'value' => 5,
            'nextDueDate' => now()->addMonth(),
            'cycle' => 'MONTHLY',
        ])->json();

        $service->customers()->destroy($customer['id']);
        $removed = $service->subscriptions()->destroy($subscription['id']);

        $this->assertEquals('200', $removed->getStatusCode());
    }
}
