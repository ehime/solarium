<?php
/**
 * Copyright 2011 Bas de Nooijer. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this listof conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDER AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * The views and conclusions contained in the software and documentation are
 * those of the authors and should not be interpreted as representing official
 * policies, either expressed or implied, of the copyright holder.
 */

class Solarium_Client_RequestTest extends PHPUnit_Framework_TestCase
{

    protected $_request;

    protected $_options = array(
        'host' => '127.0.0.1',
        'port' => 80,
        'path' => '/solr',
        'core' => null,
    );

    protected function _getRequest($options, $class = 'Solarium_Client_Request')
    {
        $query = new Solarium_Query;
        $query->setPath('/mypath');

        return new $class($options, $query);
    }

    public function testGetUrl()
    {
        $this->assertEquals(
            'http://127.0.0.1:80/solr/mypath?',
            $this->_getRequest($this->_options)->getUrl()
        );
    }

    public function testGetUrlWithCore()
    {
        $options = $this->_options;
        $options['core'] = 'core0';

        $this->assertEquals(
            'http://127.0.0.1:80/solr/core0/mypath?',
            $this->_getRequest($options)->getUrl()
        );
    }

    public function testBoolAttrib()
    {
       $this->assertEquals(
           ' name="false"',
           $this->_getRequest($this->_options)->boolAttrib('name', false)
        );
    }

    public function testBoolAttribNoValue()
    {
       $this->assertEquals(
            '',
           $this->_getRequest($this->_options)->boolAttrib('name', null)
        );
    }

    public function testAttrib()
    {
       $this->assertEquals(
           ' name="myvalue"',
           $this->_getRequest($this->_options)->attrib('name', 'myvalue')
        );
    }

    public function testAttribNoValue()
    {
       $this->assertEquals(
           '',
           $this->_getRequest($this->_options)->attrib('name', null)
        );
    }

    public function testGetUrlWithParams()
    {
        $this->assertEquals(
            'http://127.0.0.1:80/solr/mypath?wt=json&fq=category%3A1&fq=published%3Atrue',
            $this->_getRequest($this->_options, 'TestRequest')->getUrl()
        );
    }

    public function testGetPostData()
    {
        $this->assertEquals(
            '<data>xyz</data>',
            $this->_getRequest($this->_options, 'TestRequest')->getPostdata()
        );
    }
    
}

class TestRequest extends Solarium_Client_Request
{
    protected function _init()
    {
        $this->_postData = '<data>xyz</data>';
        $this->_params = array(
            'wt' => 'json',
            'fq' => array('category:1','published:true')
        );
    }
}