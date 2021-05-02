<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Http\Controllers\Game21Controller;
use Illuminate\Http\Request;

/**
 * Test cases for the controller Game21.
 */
class ControllerGame21Test extends TestCase
{
    /**
     * Try to create the controller class.
     */
    public function testCreateTheControllerClass()
    {
        $controller = new Game21Controller();
        $this->assertInstanceOf("App\Http\Controllers\Game21Controller", $controller);
    }

    /**
     * Check that the controller returns a response.
     */
    public function testControllerReturnsResponse()
    {
        $controller = new Game21Controller();

        $mockRequest = $this->createMock(Request::class);
        $res = $controller->show($mockRequest);
        $this->assertEquals("game21", $res->name());
    }
}
