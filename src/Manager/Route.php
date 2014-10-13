<?php
/**
 * Opine\Manager\Route
 *
 * Copyright (c)2013, 2014 Ryan Mahoney, https://github.com/Opine-Org <ryan@virtuecenter.com>
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
namespace Opine\Manager;
use Exception;

class Route {
    private $route;

    public function __construct ($route) {
        $this->route = $route;
    }

    public function paths () {
        $this->route->get('managerController@authFilter', '/Manager', [
            '/document/{manager}' => 'managerController@add',
            '/document/{manager}/{dbURI}' => 'managerController@edit',
            '/collection/{manager}' => 'managerController@index',
            '' => 'managerController@dashboard',
            '/header' => 'managerController@header',
        ]);

        $this->route->get('/Manager', [
            '/login' => 'managerController@login',
            '/logout' => 'managerController@logout'
        ]);

        $this->route->get('/Manager/api', [     
            '/managers' => 'managerController@apiManagers',
            '/search' => 'managerController@apiSearch',
            '/collection/{manager}' => 'managerController@collection',
            '/collection/{manager}/{method}'  => 'managerController@collection',
            '/collection/{manager}/{method}/{limit}'  => 'managerController@collection',
            '/collection/{manager}/{method}/{limit}/{page}'  => 'managerController@collection',
            '/collection/{manager}/{method}/{limit}/{page}/{sort}'  => 'managerController@collection',
            '/document/{manager}' => 'managerController@form'
        ]);

        $this->route->post('managerController@authFilter', '/Manager/api', [
            '/upload/{manager}/{field}' => 'managerController@upload',
            '/sort/{manager}' => 'managerController@sort',
            '/upsert/{manager}' => 'managerController@upsert'
        ]);
    }

    public static function location () {
        return __DIR__;
    }
}

class ManagerNotFoundException extends Exception {}