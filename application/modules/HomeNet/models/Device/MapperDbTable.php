<?php

/*
 * DeviceMapperDbTable.php
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
 * @subpackage Device
 * @copyright Copyright (c) 2011 Matthew Doll <mdoll at homenet.me>.
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 */
class HomeNet_Model_Device_MapperDbTable implements HomeNet_Model_Device_MapperInterface {

    protected $_table = null;

    /**
     * @return HomeNet_Model_DbTable_Devices;
     */
    public function getTable() {
        if (is_null($this->_table)) {
            $this->_table = new HomeNet_Model_DbTable_Devices();
        }
        return $this->_table;
    }

    public function setTable($table) {
        $this->_table = $table;
    }

     protected function _getDriver($subdevice){

        if(empty($subdevice->driver)){
            throw new HomeNet_Model_Exception('Missing Subdevice Driver');
        }

        if(!class_exists($subdevice->driver)){
            throw new HomeNet_Model_Exception('Subdevice Driver '.$subdevice->driver.' Doesn\'t Exist');
        }

        return new $subdevice->driver(array('data' => $subdevice->toArray()));
    }

    protected function _getDrivers($subdevices){
        $objects = array();
        foreach($subdevices as $subdevice){
            $objects[] = $this->_getDriver($subdevice);
        }

        return $objects;
    }




//    public function fetchRowById($id) {
//        return $this->getTable()->find($id)->current();
//    }
//
//    public function fetchDeviceById($id) {
//
//        $select = $this->getTable()->select(Zend_Db_Table::SELECT_WITH_FROM_PART);
//        $select->setIntegrityCheck(false)
//                ->where('id = ?', $id)
//                ->join('homenet_device_models', 'homenet_device_models.id = homenet_devices.model', array('driver'))
//                ->limit(1);
//
//        return $this->getTable()->fetchRow($select);
//    }
//
//    public function fetchDeviceByNodePosition($node, $position) {
//        $select = $this->getTable()->select()->where('node = ?', $node)
//                        ->where('position = ?', $position);
//        $rows = $this->getTable()->fetchAll($select);
//        if ($rows->count() > 1) {
//            throw new Zend_Exception('Duplicate Items in database');
//        }
//
//        return $rows->current();
//    }

     public function fetchObjectById($id) {
        $select = $this->getTable()->select(Zend_Db_Table::SELECT_WITH_FROM_PART);
        $select->setIntegrityCheck(false)
                ->where('homenet_devices.id = ?', $id)
                ->join('homenet_device_models', 'homenet_device_models.id = homenet_devices.model', array('driver', 'name AS modelName'))
                ->limit(1);

        $row = $this->getTable()->fetchRow($select);

        if(empty($row)){
            return array();
        }

        return $this->_getDriver($row);
    }


    public function fetchObjectByNodePosition($node, $position) {
        $select = $this->getTable()->select(Zend_Db_Table::SELECT_WITH_FROM_PART);
        $select->setIntegrityCheck(false)
                ->where('node = ?', $node)
                ->where('position = ?', $position)
                ->join('homenet_device_models', 'homenet_device_models.id = homenet_devices.model', array('driver', 'name AS modelName'))
                ->limit(1);

        $row = $this->getTable()->fetchRow($select);

        if(empty($row)){
            return array();
        }

        return $this->_getDriver($row);
    }

//    public function fetchDevicesByNode($node) {
//        $select = $this->getTable()->select()->where('node = ?', $node)
//                        ->order('position ASC');
//
//        return $this->getTable()->fetchAll($select);
//    }

    public function fetchObjectsByNode($node) {
        $select = $this->getTable()->select(Zend_Db_Table::SELECT_WITH_FROM_PART);
        $select->setIntegrityCheck(false)
                ->where('node = ?', $node)
                ->join('homenet_device_models', 'homenet_device_models.id = homenet_devices.model', array('driver', 'name AS modelName'))
                ->order('position ASC');

        $rows = $this->getTable()->fetchAll($select);

        if(empty($rows)){
            return array();
        }

        return $this->_getDrivers($rows);
    }

    public function fetchObjectByHouseNodeDevice($house, $node, $device) {
        $select = $this->getTable()->select(Zend_Db_Table::SELECT_WITH_FROM_PART);
        $select->setIntegrityCheck(false)
                ->where('homenet_devices.position = ?', $device)
                ->join('homenet_nodes', 'homenet_nodes.id = homenet_devices.node', array('house'))
                ->where('homenet_nodes.node = ?', $node)
                ->where('homenet_nodes.house = ?', $house)
                ->join('homenet_device_models', 'homenet_device_models.id = homenet_devices.model', array('driver', 'name AS modelName'))
                ->limit(1);


        $row = $this->getTable()->fetchRow($select);

        if(empty($row)){
            return array();
        }

        return $this->_getDriver($row);
    }

    public function fetchDeviceByIdWithNode($id) {
        $select = $this->getTable()->select(Zend_Db_Table::SELECT_WITH_FROM_PART);
        $select->setIntegrityCheck(false)
                ->from(null, array('homenet_devices.position AS device'))
                ->where('homenet_devices.id = ?', $id)
                ->join('homenet_nodes', 'homenet_nodes.id = homenet_devices.node', array('id', 'node', 'uplink'))
                ->limit(1);

        return $this->getTable()->fetchRow($select);
    }

    public function save(HomeNet_Model_Device_Interface $device) {

        if (($device instanceof HomeNet_Model_DbTableRow_Device) && ($device->isConnected())) {
            $device->save();
            return;
        } elseif (!is_null($device->id)) {
            $row = $this->getTable()->find($device->id)->current();
        } else {
            $row = $this->getTable()->createRow();
        }

        $row->fromArray($device->toArray());
        //die(debugArray($row));
        $row->save();

        return $row;
    }

    public function delete(HomeNet_Model_Device_Interface $device) {

        if (($device instanceof HomeNet_Model_DbTableRow_Device) && ($device->isConnected())) {
            $device->delete();
            return true;
        } elseif (!is_null($device->id)) {
            $row = $this->getTable()->find($device->id)->current()->delete();
            return;
        }

        throw new HomeNet_Model_Exception('Invalid Device');
    }

}