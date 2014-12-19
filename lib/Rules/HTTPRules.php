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

namespace Felipecwb\Routing\Rules;

use Symfony\Component\HttpFoundation\Request;

/**
 * HTTPRules
 *
 * @author felipecwb
 */
class HTTPRules implements Rules
{
    /**
     * @var boolean
     */
    private $valid = true;
    /**
     * @var Request
     */
    private $request;

    /**
     * HTTPRules
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param string $host
     * @return HTTPRules
     */
    public function isHost($host)
    {
        $this->addBooleanRule(
            $this->request->getHost() === $host
        );
        return $this;
    }

    /**
     * Verify a header parameter is defined.
     * @param string $header Parameter name of Header
     * @return HTTPRules
     */
    public function hasHeader($key)
    {
        $this->addBooleanRule(
            $this->request->headers->has($key)
        );
        return $this;
    }

    /**
     * Verify a header value.
     * @param string $header Parameter name of Header
     * @param string $value Value that header must have.
     * @return HTTPRules
     */
    public function assertHeader($header, $value)
    {
        $this->addBooleanRule(
            $this->request->headers->get($header) === $value
        );
        return $this;
    }

    /**
     * Verify if parameter is defined.
     * @param string $key
     * @return HTTPRules
     */
    public function hasParameter($key)
    {
        $this->addBooleanRule(
            $this->request->request->has($key)
        );
        return $this;
    }

    /**
     * Verify if parameter have value.
     * @param string $key
     * @return HTTPRules
     */
    public function assertParameter($key, $value)
    {
        $this->addBooleanRule(
            $this->request->get($key) === $value
        );
        return $this;
    }

    /**
     * @param string $method
     * @return HTTPRules
     */
    public function isMethod($method)
    {
        $this->addBooleanRule(
            $this->request->isMethod($method)
        );
        return $this;
    }

    /**
     * @return HTTPRules
     */
    public function isXmlHttpRequest()
    {
        $this->addBooleanRule($this->request->isXmlHttpRequest());
        return $this;
    }

    protected function addBooleanRule($value)
    {
        if ($this->valid) {
            $this->valid = (boolean) $value;
        }
        return $this;
    }

    public function isValid()
    {
        return $this->valid;
    }
}
