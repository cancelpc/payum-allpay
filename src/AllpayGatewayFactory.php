<?php

namespace PayumTW\Allpay;

use Payum\Core\GatewayFactory;
use PayumTW\Allpay\Action\SyncAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use PayumTW\Allpay\Action\CancelAction;
use PayumTW\Allpay\Action\NotifyAction;
use PayumTW\Allpay\Action\RefundAction;
use PayumTW\Allpay\Action\StatusAction;
use PayumTW\Allpay\Action\CaptureAction;
use PayumTW\Allpay\Action\ConvertPaymentAction;
use PayumTW\Allpay\Action\Api\CancelTransactionAction;
use PayumTW\Allpay\Action\Api\CreateTransactionAction;
use PayumTW\Allpay\Action\Api\RefundTransactionAction;
use PayumTW\Allpay\Action\Api\GetTransactionDataAction;

class AllpayGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritdoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'allpay',
            'payum.factory_title' => 'Allpay',
            'payum.action.capture' => new CaptureAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.refund' => new RefundAction(),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.sync' => new SyncAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),

            'payum.action.api.create_transaction' => new CreateTransactionAction(),
            'payum.action.api.refund_transaction' => new RefundTransactionAction(),
            'payum.action.api.cancel_transaction' => new CancelTransactionAction(),
            'payum.action.api.get_transaction_data' => new GetTransactionDataAction(),
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = [
                'MerchantID' => '2000132',
                'HashKey' => '5294y06JbISpM5x9',
                'HashIV' => 'v77hoKGq4kWxNNIS',
                'sandbox' => true,
            ];

            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = ['MerchantID', 'HashKey', 'HashIV'];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                return new Api((array) $config, $config['payum.http_client'], $config['httplug.message_factory']);
            };
        }
    }
}
