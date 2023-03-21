<?php

namespace Accordous\InterClient\Tests\Unit;

use Accordous\InterClient\Services\InterService;
use Accordous\InterClient\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;

class TransferTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function canCreateInterTransfer()
    {
        $this->markTestSkipped();

        $service = new InterService(Config::get('inter.token'));

        $transfer = $service->transfers()->store([
            'value' => 1,
            'walletId' => $walletId = $this->faker->numerify('######'),
        ]);

        $this->assertEquals($walletId, $transfer['walletId']);
    }
}
