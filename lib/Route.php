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

use Felipecwb\Routing\Rules\Rules;
use Felipecwb\Routing\Rules\ConcreteRules;
use Felipecwb\Routing\Resolver\Resolver;

/**
 * Route
 *
 * @author felipecwb
 */
class Route
{
    /**
     * Regex pattern
     * @var string
     */
    private $pattern;
    /**
     * Whatever can be called
     * @var callable
     */
    private $target;
    /**
     * Arguments of target
     * @var array
     */
    private $arguments = [];
    /**
     * Rules to this Route
     * @var Rules|null
     */
    private $rules;

    /**
     * Route
     *
     * @param string $pattern  A regex to be matched.
     * @param mixed  $target   All thing that can be called.
     */
    public function __construct($pattern, $target)
    {
        if (! is_string($pattern)) {
            throw new \InvalidArgumentException('$pattern must be string!');
        }
        $this->pattern  = $pattern;
        $this->target   = $target;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @return callable
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @return Rules
     */
    public function getRules()
    {
        if ($this->rules === null) {
            return $this->rules = new ConcreteRules(true);
        }
        return $this->rules;
    }

    /**
     * @param array $arguments
     * @return Route
     */
    public function setArguments(array $arguments = [])
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * @param Rules $rules
     * @return Route
     */
    public function setRules(Rules $rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * @param  Resolver $resolver  Resolver to treat the target
     * @param  array    $arguments Additional arguments to target.
     * @return mixed
     */
    public function call(Resolver $resolver, array $arguments = [])
    {
        $arguments = array_merge($this->getArguments(), $arguments);
        return $resolver->handle($this->getTarget(), $arguments);
    }
}
