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

use Felipecwb\Routing\Exception\RouteNotFoundException;

/**
 * Matcher
 *
 * @author felipecwb
 */
class Matcher
{
    /**
     * @var RouteCollection
     */
    private $collection;

    /**
     * Matcher
     * @param RouteCollection $collection
     */
    public function __construct(RouteCollection $collection)
    {
        $this->setCollection($collection);
    }

    /**
     * Caution! It's override the previous collection if exists!
     * @param RouteCollection $collection
     */
    public function setCollection(RouteCollection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @return RouteCollection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * find a router by matched path
     * @param string $path String to be matched by pattern
     * @return Route
     * @throws RouteNotFoundException
     */
    public function match($path)
    {
        $found = null;

        foreach ($this->collection as $route) {
            if (
                $route->getRules()->isValid()
                && preg_match($route->getPattern(), $path, $matches)
            ) {
                array_shift($matches);
                $route->setArguments($matches);
                $found = $route;
                break;
            }
        }

        if ($found === null) {
            throw new RouteNotFoundException("{$path} not found!");
        }

        return $found;
    }
}
