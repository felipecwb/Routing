<?php

/*
 * The MIT License
 *
 * Copyright 2014 felipecwb.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Felipecwb\Routing;

use Felipecwb\Routing\Resolver\CallableResolver;

/**
 * RouteTest
 *
 * @author felipecwb
 */
class RouteTest extends \PHPUnit_Framework_TestCase
{
    protected function assertPreConditions()
    {
        $this->assertTrue(class_exists(__NAMESPACE__ . '\Route'));
    }

    public function testInstance()
    {
        $route = new Route('#/#', function () {});

        $this->assertInstanceOf(__NAMESPACE__ . '\Route', $route);
    }

    public function testSetAndGetRules()
    {
        $rulesMock = $this->getMock(__NAMESPACE__ . '\Rules\Rules', ['isValid']);

        $route = new Route('#/#', function () {});
        $route->setRules($rulesMock);

        $this->assertInstanceOf(__NAMESPACE__ . '\Rules\Rules', $route->getRules());
    }

    public function testGetRulesWithoutSetRules()
    {
        $route = new Route('#/#', function () {});

        $this->assertInstanceOf(__NAMESPACE__ . '\Rules\Rules', $route->getRules());
        $this->assertInstanceOf(__NAMESPACE__ . '\Rules\ConcreteRules', $route->getRules());
    }

    public function testCallTarget()
    {
        $this->expectOutputString("It's Work!");

        $route = new Route('#/#', function () {
            echo "It's Work!";
        });
        $route->call(new CallableResolver());
    }

    public function testCallTargetWithArguments()
    {
        $name = "Felipe";
        $this->expectOutputString("Hello {$name}!");

        $route = new Route('#/hello/([a-z])#', function ($n) {
            echo "Hello {$n}!";
        });
        $route->setArguments([
            $name
        ]);
        $route->call(new CallableResolver());
    }

    public function testCallTargetWithNArguments()
    {
        $name     = "Felipe";
        $nickname = "felipecwb";
        $this->expectOutputString("Hello {$name} ({$nickname})!");

        $route = new Route('#^/hello/([a-z])/([a-z0-9])$#i', function ($n1, $n2) {
            echo "Hello {$n1} ({$n2})!";
        });
        $route->setArguments([
            $name,
            $nickname
        ]);
        $route->call(new CallableResolver());
    }

    public function testCallTargetWithMoreArguments()
    {
        $name     = "Felipe";
        $nickname = "felipecwb";
        $extraStr = 'hahahahahahah!';
        $this->expectOutputString("Hello {$name} ({$nickname})! {$extraStr}");

        $route = new Route('#^/hello/([a-z])/([a-z0-9])$#i', function ($n1, $n2, $extraStr) {
            echo "Hello {$n1} ({$n2})! {$extraStr}";
        });
        $route->setArguments([
            $name,
            $nickname
        ]);
        $route->call(
            new CallableResolver(),
            [$extraStr]
        );
    }

    public function testCallTargetWithReturn()
    {
        $route = new Route('#/upper/(.*)#i', function ($str) {
            return strtoupper($str);
        });
        $route->setArguments(["testing"]);
        $result = $route->call(new CallableResolver());

        $this->assertEquals("TESTING", $result);
    }
}
