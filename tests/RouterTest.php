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
 * RouterTest
 *
 * @author felipecwb
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{
    protected $collection,
              $resolver,
              $matcher;

    public function assertPreConditions()
    {
        $this->assertTrue(class_exists(__NAMESPACE__ . '\Router'));
    }

    protected function setUp()
    {
        $this->collection = new RouteCollection();
        $this->matcher    = new Matcher($this->collection);
        $this->resolver   = new CallableResolver();
    }

    protected function tearDown()
    {
        $this->collection = null;
        $this->matcher    = null;
        $this->resolver   = null;
    }

    public function testInstance()
    {
        $router = new Router($this->matcher, $this->resolver);

        $this->assertInstanceOf(__NAMESPACE__ . '\Router', $router);
    }

    public function testStaticCreateWithoutParameters()
    {
        $router = Router::create();

        $this->assertInstanceOf(__NAMESPACE__ . '\Router', $router);
    }

    public function testStaticCreateWithParameters()
    {
        $mockResolver = $this->getMock(
            __NAMESPACE__ . '\Resolver\Resolver',
            ['handle']
        );

        $router = Router::create([
            'matcher' => $this->matcher,
            'resolver' => $mockResolver
        ]);

        $this->assertInstanceOf(__NAMESPACE__ . '\Router', $router);
    }

    public function testAdd()
    {
        $router = new Router($this->matcher, $this->resolver);

        $r1 = $router->add('/home(.*)', function () {
            echo "Welcome!";
        });
        $r2 = $router->add('/hello(?:/(\w+))?', function ($n = 'World') {
            $n = ' ' . $n;
            echo "Hello{$n}!";
        });

        $this->assertInstanceOf(__NAMESPACE__ . '\Route', $r1);
        $this->assertInstanceOf(__NAMESPACE__ . '\Route', $r2);
        $this->assertAttributeInstanceOf(
            __NAMESPACE__ . '\Matcher', 'matcher', $router
        );
    }

    /**
     * @dataProvider providerTestFind
     */
    public function testMatch($path)
    {
        $router = new Router($this->matcher, $this->resolver);
        $router->add('/home[/]?', function () {return '/home';});
        $router->add('/about[/]?', function () {return '/about';});
        $router->add('/contact[/]?', function () {return '/contact';});

        $route = $router->match($path);

        $this->assertEquals($path, $route->call(new CallableResolver()));
    }

    public function testMatchWithSameDelimitador()
    {
        $router = new Router($this->matcher, $this->resolver);
        $router->add('home|room', function () {
            return 'My Room in Home!';
        });

        $result = $router->match('home|room');
        $this->assertInstanceOf(__NAMESPACE__ . '\Route', $result);
    }

    public function testMatchWithRegexModifier()
    {
        $router = new Router($this->matcher, $this->resolver);
        $router->add('house^kitchen', function () {
            return 'My Kitchen in House!';
        });
        $router->add('house$garage', function () {
            return 'My Garage in House!';
        });

        $result = $router->match('house^kitchen');
        $this->assertInstanceOf(__NAMESPACE__ . '\Route', $result);
        $result = $router->match('house$garage');
        $this->assertInstanceOf(__NAMESPACE__ . '\Route', $result);
    }

    public function testDispatch()
    {
        $router = new Router($this->matcher, $this->resolver);
        $router->add('/home[/]?', function () {return 'Home!';});

        $result = $router->dispatch('/home');
        $this->assertEquals('Home!', $result);
        $result = $router->dispatch('/home/');
        $this->assertEquals('Home!', $result);
    }

    public function testDispatchWithArguments()
    {
        $router = new Router($this->matcher, $this->resolver);
        $router->add('/hello', function ($name) {return "Hello {$name}!";});

        $result = $router->dispatch('/hello', ['Felipe']);
        $this->assertEquals('Hello Felipe!', $result);
        $result = $router->dispatch('/hello', ['Jhon']);
        $this->assertEquals('Hello Jhon!', $result);
    }

    /**
     * @expectedException Felipecwb\Routing\Exception\ResolverException
     */
    public function testDispatchThrowsResolverException()
    {
        $router = new Router($this->matcher, $this->resolver);
        $router->add('/home', 'target-not-valid-to-resolver');

        $router->dispatch('/home');
    }

    /**
     * @expectedException Felipecwb\Routing\Exception\RouteNotFoundException
     */
    public function testDispatchThrowsRouteNotFoundException()
    {
        $router = new Router($this->matcher, $this->resolver);

        $router->dispatch('/not-found');
    }

    public function testMatchWithArgument()
    {
        $router = new Router($this->matcher, $this->resolver);

        $router->add('/name[s]?(?:/(\d+))?', function ($id = null) {
            $names = ['Felipe', 'Peter', 'Mary'];
            return $id === null
                ? $names
                : $names[(int) $id - 1];
        });
        $router->add('/about(?:/(\w+))?', function ($m) {
            return "About {$m}!";
        })->setRules(new Rules\ConcreteRules(false));

        $resolver = new CallableResolver();

        $route = $router->match('/names');
        $this->assertEquals(['Felipe', 'Peter', 'Mary'], $route->call($resolver));

        $route = $router->match('/name/1');
        $this->assertEquals('Felipe', $route->call($resolver));

        $route = $router->match('/name/2');
        $this->assertEquals('Peter', $route->call($resolver));

        $route = $router->match('/name/3');
        $this->assertEquals('Mary', $route->call($resolver));
    }

    /**
     * @dataProvider providerTestFind
     * @expectedException Felipecwb\Routing\Exception\RouteNotFoundException
     */
    public function testMatchWithInvalidRules($path)
    {
        $InvalidRules = new Rules\ConcreteRules(false);
        
        $router = new Router($this->matcher, $this->resolver);

        $router->add('/home', function () {return '/home';})
            ->setRules($InvalidRules);

        $router->add('/about', function () {return '/about';})
            ->setRules($InvalidRules);

        $router->add('/contact', function () {return '/contact';})
            ->setRules($InvalidRules);

        $route = $router->match($path);

        $this->assertEquals($path, $route->call(new CallableResolver()));
    }

    public function providerTestFind()
    {
        return [
            ['/home'],
            ['/about'],
            ['/contact'],
        ];
    }
}
