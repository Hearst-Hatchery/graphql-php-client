<?php

namespace GraphQLClient\Tests\Traits;

use GraphQLClient\Traits\Response as ResponseTrait;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Codeception\Util\ReflectionHelper;

class ResponseTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testResponseGetsHandled()
    {
        $response = $this->getMockBuilder(ResponseTrait::class)->setMockClassName('myResponse')->setMethods(['getResponse'])->getMockForTrait();
        $response->expects($this->any())
            ->method('getResponse')
            ->will($this->returnValue(new GuzzleResponse(200, [], \GuzzleHttp\Psr7\stream_for('foo'))));

        verify(ReflectionHelper::invokePrivateMethod($response, 'handleResponse'))->equals('foo');
    }

    /**
     * @expectedException \Http\Client\Exception\TransferException
     */
    public function testExceptionIsThrown()
    {
        $response = $this->getMockBuilder(ResponseTrait::class)->setMethods(['getResponse'])->getMockForTrait();

        \Codeception\Stub::update($response, [
            'getResponse' => false,
        ]);

        ReflectionHelper::invokePrivateMethod($response, 'handleResponse');
    }

    public function testGetResponseReturnsResponses()
    {
        $response = $this->getMockBuilder(ResponseTrait::class)->getMockForTrait();

        \Codeception\Stub::update($response, [
            'response' => false,
        ]);

        verify($response->getResponse())->false();

        \Codeception\Stub::update($response, [
            'response' => new GuzzleResponse(200),
        ]);

        verify($response->getResponse())->isInstanceOf(ResponseInterface::class);
    }
}