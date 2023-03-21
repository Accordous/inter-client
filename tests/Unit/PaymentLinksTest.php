<?php

namespace Accordous\InterClient\Tests\Unit;

use Accordous\InterClient\Enums\BillingType;
use Accordous\InterClient\Enums\ChargeType;
use Accordous\InterClient\Services\InterService;
use Accordous\InterClient\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;

class PaymentLinksTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function canCreatePaymentLinks()
    {
        $service = new InterService(Config::get('inter.token'));

        $paymentLink = $service->paymentLinks()->store([
            'name' => $name = $this->faker->name,
            'billingType' => BillingType::CREDIT_CARD,
            'chargeType' => ChargeType::RECURRENT,
        ])->json();

        $service->paymentLinks()->destroy($paymentLink['id']);

        $this->assertEquals($name, $paymentLink['name']);
    }

    /**
     * @test
     */
    public function canRestorePaymentLinks()
    {
        $service = new InterService(Config::get('inter.token'));

        $paymentLink = $service->paymentLinks()->store([
            'name' => $name = $this->faker->name,
            'billingType' => BillingType::CREDIT_CARD,
            'chargeType' => ChargeType::RECURRENT,
        ])->json();

        $service->paymentLinks()->destroy($paymentLink['id'])->json();

        $restored = $service->paymentLinks()->restore($paymentLink['id'])->json();

        $this->assertEquals(false, $restored['deleted']);
    }
}
