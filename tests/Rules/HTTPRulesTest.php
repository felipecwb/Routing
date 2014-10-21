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
 * Description of HTTPRulesTest
 *
 * @author felipecwb
 */
class HTTPRulesTest extends \PHPUnit_Framework_TestCase
{
    public function assertPreConditions()
    {
        $this->assertTrue(class_exists(__NAMESPACE__ . '\HTTPRules'));
    }

    public function testInstance()
    {
        $rules = new HTTPRules(new Request());

        $this->assertInstanceOf(__NAMESPACE__ . '\Rules', $rules);
        $this->assertInstanceOf(__NAMESPACE__ . '\HTTPRules', $rules);
    }

    public function testIsHost()
    {
        $request = Request::create('http://devtest.org/');
        
        $rules = new HTTPRules($request);
        $rules->isHost('devtest.org');
        
        $this->assertEquals('devtest.org', $request->getHost());
        $this->assertTrue($rules->isValid());
    }

    public function testIsHostFail()
    {
        $request = Request::create('http://nodevtest.org/');
        
        $rules = new HTTPRules($request);
        $rules->isHost('devtest.org');
        
        $this->assertNotEquals('devtest.org', $request->getHost());
        $this->assertFalse($rules->isValid());
    }

    public function testHasHeader()
    {
        $request = new Request();
        $request->headers->set('test-api-key', 'FFFFTTTTTTTWWWWWWWW');
        
        $rules = new HTTPRules($request);
        $rules->hasHeader('test-api-key');
        
        $this->assertEquals('FFFFTTTTTTTWWWWWWWW', $request->headers->get('test-api-key'));
        $this->assertTrue($rules->isValid());
    }

    public function testHasNoHeader()
    {
        $request = new Request();
        
        $rules = new HTTPRules($request);
        $rules->hasHeader('test-api-key');
        
        $this->assertEquals(null, $request->headers->get('test-api-key'));
        $this->assertFalse($rules->isValid());
    }

    public function testAssertHeader()
    {
        $request = new Request();
        $request->headers->set('test-api-key', 'FFFFTTTTTTTWWWWWWWW');
        
        $rules = new HTTPRules($request);
        $rules->assertHeader('test-api-key', 'FFFFTTTTTTTWWWWWWWW');
        
        $this->assertEquals('FFFFTTTTTTTWWWWWWWW', $request->headers->get('test-api-key'));
        $this->assertTrue($rules->isValid());
    }

    public function testNotAssertHeader()
    {
        $request = new Request();
        $request->headers->set('test-api-key', 'FFFF');
        
        $rules = new HTTPRules($request);
        $rules->assertHeader('test-api-key', 'FFFFTTTTTTTWWWWWWWW');
        
        $this->assertNotEquals('FFFFTTTTTTTWWWWWWWW', $request->headers->get('test-api-key'));
        $this->assertFalse($rules->isValid());
    }

    public function testHasParameter()
    {
        $request = new Request();
        $request->request->set('uid', '20');
        
        $rules = new HTTPRules($request);
        $rules->hasParameter('uid');
        
        $this->assertEquals('20', $request->get('uid'));
        $this->assertTrue($rules->isValid());
    }

    public function testHasNoParameter()
    {
        $request = new Request();
        
        $rules = new HTTPRules($request);
        $rules->hasParameter('uid');
        
        $this->assertEquals(null, $request->get('uid'));
        $this->assertFalse($rules->isValid());
    }

    public function testAssertParameter()
    {
        $request = new Request();
        $request->request->set('uid', '123');
        
        $rules = new HTTPRules($request);
        $rules->assertParameter('uid', '123');
        
        $this->assertEquals('123', $request->request->get('uid'));
        $this->assertTrue($rules->isValid());
    }

    public function testNotAssertParameter()
    {
        $request = new Request();
        $request->request->set('uid', '');
        
        $rules = new HTTPRules($request);
        $rules->assertParameter('uid', '123');
        
        $this->assertNotEquals('123', $request->get('uid'));
        $this->assertFalse($rules->isValid());
    }

    public function testIsMethod()
    {
        // get
        $request = Request::create('http://devtest.org/', 'GET');
        $rules = new HTTPRules($request);
        $rules->isMethod('get');

        $this->assertTrue($rules->isValid());
        // post
        $request = Request::create('http://devtest.org/', 'POST');
        $rules = new HTTPRules($request);
        $rules->isMethod('post');

        $this->assertTrue($rules->isValid());
        // put
        $request = Request::create('http://devtest.org/id', 'PUT');
        $rules = new HTTPRules($request);
        $rules->isMethod('put');

        $this->assertTrue($rules->isValid());
        // delete
        $request = Request::create('http://devtest.org/id', 'DELETE');
        $rules = new HTTPRules($request);
        $rules->isMethod('delete');

        $this->assertTrue($rules->isValid());
    }

    public function testIsMethodFails()
    {
        // get
        $request = Request::create('http://devtest.org/', 'GET');
        $rules = new HTTPRules($request);
        $rules->isMethod('post');

        $this->assertFalse($rules->isValid());
        // post
        $request = Request::create('http://devtest.org/', 'POST');
        $rules = new HTTPRules($request);
        $rules->isMethod('put');

        $this->assertFalse($rules->isValid());
        // trace
        $request = Request::create('http://devtest.org/', 'TRACE');
        $rules = new HTTPRules($request);
        $rules->isMethod('get');

        $this->assertFalse($rules->isValid());
    }

    public function testIsXmlHttpRequest()
    {
        $request = new Request();
        $rules = new HTTPRules($request);
        $rules->isXmlHttpRequest();
        $this->assertFalse($rules->isValid());

        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $rules = new HTTPRules($request);
        $rules->isXmlHttpRequest();
        $this->assertTrue($rules->isValid());

        $request->headers->remove('X-Requested-With');
        $rules = new HTTPRules($request);
        $rules->isXmlHttpRequest();
        $this->assertFalse($rules->isValid());
    }
}
