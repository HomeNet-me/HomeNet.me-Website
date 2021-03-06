<?php

/*
 * RoomMapperDbTable.php
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
 * @subpackage Message
 * @copyright Copyright (c) 2011 Matthew Doll <mdoll at homenet.me>.
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 */
class HomeNet_Model_Message_MapperDbTable implements HomeNet_Model_Message_MapperInterface {

    protected $_table = null;

    /**
     * @return HomeNet_Model_DbTable_Rooms;
     */
    public function getTable() {
        if (is_null($this->_table)) {
            $this->_table = new HomeNet_Model_DbTable_Messages();
        }
        return $this->_table;
    }

    public function setTable($table) {
        $this->_table = $table;
    }

    public function fetchMessagesByUser($user) {

        $select = $this->getTable()->select()->where('user = ?', $user);

        return $this->getTable()->fetchAll($select);
    }

    public function fetchMessagesByHouseOrUser($house, $user) {
        $houses = array();
        if (!is_array($house)) {
            $houses[] = $house;
        } else {
            $houses = $house;
        }

        $select = $this->getTable()->select()->where('user = ?', $user)
                        ->orWhere('house in (?)', $houses)
                        ->order('id DESC')
                        ->limit(20);

        return $this->getTable()->fetchAll($select);
    }

    public function save(HomeNet_Model_Message_Interface $message) {


        if (($message instanceof HomeNet_Model_DbTableRow_Message) && ($message->isConnected())) {
            $message->save();
            return;
        } elseif (!is_null($message->id)) {
            $row = $this->getTable()->find($message->id)->current();
        } else {
            $row = $this->getTable()->createRow();
        }

        $row->fromArray($message->toArray());
       // die(debugArray($message->toArray()));
        $row->save();

        return $row;
    }

    public function delete(HomeNet_Model_Message_Interface $message) {

        if (($message instanceof HomeNet_Model_DbTableRow_Message) && ($message->isConnected())) {
            $message->delete();
            return true;
        } elseif (!is_null($message->id)) {
            $row = $this->getTable()->find($message->id)->current()->delete();
            return;
        }

        throw new HomeNet_Model_Exception('Invalid Room');
    }

}