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
        $this->route->get('managerController@authFilter', 
            '/Manager', [
                ''                                                  => 'managerController@dashboard',
                '/section/{section}'                                => 'managerController@dashboard',
                '/header'                                           => 'managerController@header',
                '/item/{manager}'                                   => 'managerController@add',
                '/item/{manager}/{dbURI}'                           => 'managerController@edit',
                '/index/{manager}'                                  => 'managerController@index'
        ]);

        $this->route->get(
            '/Manager', [
                '/login'                                            => 'managerController@login',
                '/logout'                                           => 'managerController@logout'
        ]);

        $this->route->get('managerController@authFilter',
            '/Manager/api', [     
                '/managers'                                         => 'managerApiController@managers',
                '/search'                                           => 'managerApiController@search',
                '/index/{manager}'                                  => 'managerApiController@collection',
                '/index/{manager}/{method}'                         => 'managerApiController@collection',
                '/index/{manager}/{method}/{limit}'                 => 'managerApiController@collection',
                '/index/{manager}/{method}/{limit}/{page}'          => 'managerApiController@collection',
                '/index/{manager}/{method}/{limit}/{page}/{sort}'   => 'managerApiController@collection',
                '/document/{manager}'                               => 'managerApiController@form'
        ]);

        $this->route->post('managerController@authFilter', 
            '/Manager/api', [
                '/upload/{manager}/{field}'                         => 'managerApiController@upload',
                '/sort'                                             => 'managerApiController@sort',
                '/upsert/{manager}'                                 => 'managerApiController@upsert'
        ]);

        $this->route->delete('managerController@authFilter',
            '/Manager/api', [
                '/{dbURI}'                                          => 'managerApiController@delete'
        ]);
    }

    public static function location () {
        return __DIR__;
    }
}