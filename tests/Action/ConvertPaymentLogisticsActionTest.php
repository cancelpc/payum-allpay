<?php

use Mockery as m;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;
use PayumTW\Allpay\Action\ConvertPaymentLogisticsAction;

class ConvertPaymentLogisticsActionTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_convert()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $action = new ConvertPaymentLogisticsAction();
        $request = m::mock(Convert::class);
        $payment = m::mock(PaymentInterface::class);
        $model = new ArrayObject();

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $request
            ->shouldReceive('getSource')->twice()->andReturn($payment)
            ->shouldReceive('getTo')->once()->andReturn('array');

        $payment
            ->shouldReceive('getDetails')->once()->andReturn([])
            ->shouldReceive('getNumber')->once()->andReturn('fooNumber')
            ->shouldReceive('getClientEmail')->once()->andReturn('fooClientEmail')
            ->shouldReceive('getTotalAmount')->once()->andReturn(0)
            ->shouldReceive('getDescription')->once()->andReturn('fooDescription');
        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        $request->shouldReceive('setResult')->once()->andReturnUsing(function ($data) {
            $this->assertSame([
                'MerchantTradeNo' => strtoupper('fooNumber'),
                'ReceiverEmail'   => 'fooClientEmail',
                'GoodsAmount'     => 0,
                'TradeDesc'       => 'fooDescription',
            ], $data);
        });

        $action->execute($request);
    }
}
