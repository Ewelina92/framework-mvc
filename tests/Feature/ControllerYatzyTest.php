<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Http\Controllers\YatzyController;
use Illuminate\Http\Request;

/**
 * Test cases for the controller Yatzy.
 */
class ControllerYatzyTest extends TestCase
{
    /**
     * Try to create the controller class.
     */
    public function testCreateTheControllerClass()
    {
        $controller = new YatzyController();
        $this->assertInstanceOf("App\Http\Controllers\YatzyController", $controller);
    }

    /**
     * Check that the controller returns a response.
     */
    public function testControllerReturnsResponse()
    {
        $controller = new YatzyController();

        $mockRequest = $this->createMock(Request::class);
        $res = $controller->show($mockRequest);
        $this->assertEquals("yatzy-welcome", $res->name());
    }
}
