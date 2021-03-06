<?php
/* 
 * Arduino.php
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
 * Description of Arduino
 *
 * @author Matthew Doll <mdoll at homenet.me>
 */
class HomeNet_Model_Subdevice_Led extends HomeNet_Model_Subdevice_Abstract {


    public function hasControls() {
        return true;
    }



    /**
     * Build the Actions that this subdevice can perform
     */
    public function buildControls() {
        $this->addControl(
            'on',
            'submit',
            array('label' => 'On'),
            'packet',
            array(
                'device'     => $this->device,
                'command'    => HomeNet_Model_Packet::ON,
                'payload'    => $this->position
            )
        );
        $this->addControl(
            'off',
            'submit',
            array('label' => 'Off'), 
            'packet',
            array(
                'device'    => $this->device,
                'command'   => HomeNet_Model_Packet::OFF,
                'payload'   => $this->position
            )
        );
    }
}