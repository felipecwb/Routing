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
use Felipecwb\Routing\Resolver\Resolver;
use Felipecwb\Routing\Exception\ResolverException;

/**
 * Router
 *
 * @author felipecwb
 */
class Router
{
    /**
     * @var Matcher
     */
    private $matcher;
    /**
     * @var Resolver
     */
    private $resolver;

    /**
     * Router
     * @param Matcher $matcher
     */
    public function __construct(Matcher $matcher, Resolver $resolver)
    {
        $this->setMatcher($matcher);
        $this->setResolver($resolver);
    }

    /**
     * Caution! It's override the previous matcher if exists!
     * @param Matcher $matcher
     */
    public function setMatcher(Matcher $matcher)
    {
        $this->matcher = $matcher;
    }

    /**
     * Caution! It's override the previous matcher if exists!
     * @param Resolver $resolver
     */
    public function setResolver(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Add a route to collection
     * @param Route $route
     * @return Route The route was added
     */
    public function add($pattern, $target)
    {
        $pattern = str_replace(
            ['|', '^', '$'],
            ['\|', '\^', '\$'],
            $pattern
        );
        $route = new Route('|^' . $pattern . '$|', $target);
        $this->matcher->getCollection()->add($route);
        return $route;
    }

    /**
     * Match a Route
     * @param string $path String to be matched by pattern
     * @throws RouteNotFoundException
     * @return Route
     */
    public function match($path)
    {
        return $this->matcher->match($path);
    }

    /**
     * A shortcut to Router::match($path)->call(new Resolver())
     * 
     * @param string $path      String to be matched by pattern
     * @param string $arguments Additional arguments to target.
     * 
     * @throws RouteNotFoundException
     * @throws ResolverException
     * 
     * @return mixed 
     */
    public function dispatch($path, array $arguments = [])
    {
        return $this->match($path)->call($this->resolver, $arguments);
    }
}
