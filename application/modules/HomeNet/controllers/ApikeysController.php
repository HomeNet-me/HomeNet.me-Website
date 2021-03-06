<?php
/*
 * Copyright (c) 2011 Matthew Doll <mdoll at homenet.me>.
 *
 * This file is part of HomeNet.
 *
 * HomeNet is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * HomeNet is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with HomeNet.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @package HomeNet
 * @subpackage Apikey
 * @copyright Copyright (c) 2011 Matthew Doll <mdoll at homenet.me>.
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 */
class HomeNet_ApikeysController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->house = $this->_getParam('house');
    }

    public function indexAction()
    {
        $service = new HomeNet_Model_Apikey_Service();
        $keys = $service->getObjectsByHouseUser($this->view->house);
        //die(print_r($keys->toArray(),1));
        if (count($keys) == 0) {
            $row = $service->createApikeyForHouse($this->view->house);
            $this->view->apikey = $row->id;
        } else {
            $row = $keys[0]; 
            $this->view->apikey = $row->id;
        }
    }

    public function newAction()
    {
        // action body
    }

    public function editAction()
    {
        // action body
    }

    public function deleteAction()
    {
        // action body
    }




}









