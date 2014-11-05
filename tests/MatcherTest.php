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
 * MatcherTest
 *
 * @author felipecwb
 */
class MatcherTest extends \PHPUnit_Framework_TestCase
{
    protected function assertPreConditions()
    {
        $this->assertTrue(class_exists(__NAMESPACE__ . '\Matcher'));
    }

    public function testInstance()
    {
        $collection = new RouteCollection();
        
        $matcher = new Matcher($collection);
        
        $this->assertInstanceOf(__NAMESPACE__ . '\Matcher', $matcher);
    }

    public function testGetCollection()
    {
        $collection = new RouteCollection();
        
        $matcher = new Matcher($collection);
        $collection = $matcher->getCollection();

        $this->assertInstanceOf(__NAMESPACE__ . '\RouteCollection', $collection);
    }

    public function testMatch()
    {
        $collection = new RouteCollection();
        $collection->add(new Route('|/hello[/]?|', function () {
            echo 'Hello!';
        }));
        
        $matcher = new Matcher($collection);
        $route = $matcher->match('/hello');

        $this->assertInstanceOf(__NAMESPACE__ . '\Route', $route);
        
        $this->expectOutputString('Hello!');
        $route->call(new CallableResolver());
    }

    /**
     * @expectedException Felipecwb\Routing\Exception\RouteNotFoundException
     */
    public function testMatchNotExistsRoute()
    {
        $collection = new RouteCollection();
        
        $matcher = new Matcher($collection);
        $matcher->match('/not-found');// throw an RouteNotFoundException
    }
}
