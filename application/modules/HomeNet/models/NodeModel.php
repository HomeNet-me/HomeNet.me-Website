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
 * @subpackage NodeModels
 * @copyright Copyright (c) 2011 Matthew Doll <mdoll at homenet.me>.
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 */
class HomeNet_Model_NodeModel implements HomeNet_Model_NodeModel_Interface {
    
    public $id = null;
    public $status;
    public $type;
    public $driver;
    public $name;
    public $description;
    public $image;
    public $max_devices;
    public $settings;
    public $created;

    public function  __construct(array $config = array()) {
        if(isset($config['data'])){
            $this->fromArray($config['data']);
        }
    }

     public function fromArray(array $array){

        $vars = get_object_vars($this);

        foreach($array as $key => $value){
            if(array_key_exists($key, $vars)){
                $this->$key = $value;
            }
        }
    }

    /**
     * @return array
     */
    public function toArray(){
        return get_object_vars($this);
    }
}
