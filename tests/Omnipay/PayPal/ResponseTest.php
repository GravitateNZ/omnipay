<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayPal;

use Omnipay\TestCase;

class ResponseTest extends TestCase
{
    public function testConstruct()
    {
        // response should decode URL format data
        $response = new Response('example=value&foo=bar');
        $this->assertEquals(array('example' => 'value', 'foo' => 'bar'), $response->getData());
    }

    public function testExpressPurchaseSuccess()
    {
        $httpResponse = $this->getMockResponse('ExpressPurchaseSuccess.txt');
        $response = new Response($httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('EC-42721413K79637829', $response->getExpressRedirectToken());
        $this->assertNull($response->getGatewayReference());
        $this->assertNull($response->getMessage());
    }

    public function testExpressPurchaseFailure()
    {
        $httpResponse = $this->getMockResponse('ExpressPurchaseFailure.txt');
        $response = new Response($httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getExpressRedirectToken());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('This transaction cannot be processed. The amount to be charged is zero.', $response->getMessage());
    }

    public function testProPurchaseSuccess()
    {
        $httpResponse = $this->getMockResponse('ProPurchaseSuccess.txt');
        $response = new Response($httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('96U93778BD657313D', $response->getGatewayReference());
        $this->assertNull($response->getMessage());
    }

    public function testProPurchaseFailure()
    {
        $httpResponse = $this->getMockResponse('ProPurchaseFailure.txt');
        $response = new Response($httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('This transaction cannot be processed. Please enter a valid credit card expiration year.', $response->getMessage());
    }
}
