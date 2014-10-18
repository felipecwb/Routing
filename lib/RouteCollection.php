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

/**
 * RouteCollection
 *
 * @author felipecwb
 */
class RouteCollection implements \Iterator
{
    /**
     * @var int
     */
    private $position = 0;
    /**
     * @var Route[]
     */
    private $routes = [];

    /**
     * @param Route[] $routes
     */
    public function __construct(array $routes = [])
    {
        foreach ($routes as $route) {
            $this->add($route);
        }
    }

    /**
     * Add a new Route to collection
     * @param Route $route
     */
    public function add(Route $route)
    {
        $this->routes[] = $route;
    }

    /**
     * @return Route
     */
    public function current()
    {
        return $this->routes[$this->position];
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return boolean
     */
    public function valid()
    {
        return isset($this->routes[$this->position]);
    }
}
