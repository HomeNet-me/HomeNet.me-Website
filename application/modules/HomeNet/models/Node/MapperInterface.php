<?php

/*
 * NodeMapperInterface.php
 *
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
 * along with HomeNet.  If not, see <http ://www.gnu.org/licenses/>.
 */

/**
 * @package HomeNet
 * @subpackage Node
 * @copyright Copyright (c) 2011 Matthew Doll <mdoll at homenet.me>.
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 */
interface HomeNet_Model_Node_MapperInterface {

    public function fetchObjectById($id);

    public function fetchObjectsByHouse($house);

    public function fetchObjectByHouseNode($house,$node);

    public function fetchNextIdByHouse($house);

    public function fetchInternetIdsByHouse($house);

    public function save(HomeNet_Model_Node_Interface $room);

    public function delete(HomeNet_Model_Node_Interface $room);
}