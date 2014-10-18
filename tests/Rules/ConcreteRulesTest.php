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

/**
 * ConcreteRulesTest
 *
 * @author felipecwb
 */
class ConcreteRulesTest extends \PHPUnit_Framework_TestCase
{

    public function assertPreConditions()
    {
        $this->assertTrue(class_exists(__NAMESPACE__ . '\ConcreteRules'));
    }

    public function testInstance()
    {
        $rules = new ConcreteRules();

        $this->assertInstanceOf(__NAMESPACE__ . '\Rules', $rules);
        $this->assertInstanceOf(__NAMESPACE__ . '\ConcreteRules', $rules);
    }

    public function testIsValid()
    {
        $rules = new ConcreteRules();
        $this->assertTrue($rules->isValid());
    }

    public function testNotIsValid()
    {
        $rules = new ConcreteRules(false);
        $this->assertFalse($rules->isValid());
    }

    public function testSequenceIsValid()
    {
        $rules = new ConcreteRules();
        $rules->addRule(true)
            ->addRule(true)
            ->addRule(true);

        $this->assertTrue($rules->isValid());
    }

    public function testSequenceHaveAInvalidRule()
    {
        $rules = new ConcreteRules();
        $rules->addRule(true)
            ->addRule(false); // false rule

        $this->assertFalse($rules->isValid());
    }

    public function testInvalidRuleRemainInvalidAfterOfValidRule()
    {
        $rules = new ConcreteRules();
        $this->assertTrue($rules->isValid());
        $rules->addRule(false);
        $this->assertFalse($rules->isValid());
        $rules->addRule(true);
        // if remain false
        $this->assertFalse($rules->isValid());
    }
}
