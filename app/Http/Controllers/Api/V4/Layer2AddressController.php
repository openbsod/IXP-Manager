<?php

namespace IXP\Http\Controllers\Api\V4;

/*
 * Copyright (C) 2009-2017 Internet Neutral Exchange Association Company Limited By Guarantee.
 * All Rights Reserved.
 *
 * This file is part of IXP Manager.
 *
 * IXP Manager is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation, version v2.0 of the License.
 *
 * IXP Manager is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License v2.0
 * along with IXP Manager.  If not, see:
 *
 * http://www.gnu.org/licenses/gpl-2.0.html
 */

use D2EM;

use Entities\{
    Layer2Address as Layer2AddressEntity,
    VlanInterface as VlanInterfaceEntity
};

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Layer2Address API Controller
 * @author     Barry O'Donovan <barry@islandbridgenetworks.ie>
 * @author     Yann Robin <yann@islandbridgenetworks.ie>
 * @copyright  Copyright (C) 2009-2017 Internet Neutral Exchange Association Company Limited By Guarantee
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL V2.0
 */
class Layer2AddressController extends Controller {

    /**
     * Add a mac address to a VLAN Interface
     *
     * @param   Request $request instance of the current HTTP request
     * @return  JsonResponse
     */
    public function add( Request $request ): JsonResponse {
        /** @var VlanInterfaceEntity $vli */
        if( !( $vli = D2EM::getRepository( VlanInterfaceEntity::class )->find( $request->input( 'vliid' ) ) ) ) {
            return abort( '404' );
        }

        $mac = preg_replace( "/[^a-f0-9]/i", '' , strtolower( $request->input( 'mac', '' ) ) );
        if( strlen( $mac ) !== 12 ) {
            return response()->json( [ 'success' => false, 'message' => 'Invalid or missing MAC addresses' ] );
        }

        if( D2EM::getRepository( Layer2AddressEntity::class )->existsInVlan( $mac, $vli->getVlan()->getId() ) ) {
            return response()->json( [ 'success' => false, 'message' => 'The MAC address already exists within the VLAN' ] );
        }

        $l2a = new Layer2AddressEntity();
        $l2a->setMac( $mac )
            ->setVlanInterface( $vli )
            ->setCreatedAt( new \DateTime );

        D2EM::persist( $l2a );
        D2EM::flush();

        return response()->json( [ 'success' => true, 'message' => 'The MAC address has been added successfully.' ] );
    }

    /**
     * Get the layer2Interface detail
     *
     * @param   int $id ID of the Layer2Interface
     * @return  JsonResponse
     */
    public function detail( int $id ): JsonResponse{
        if( !( $l2a = D2EM::getRepository(Layer2AddressEntity::class)->find( $id ) ) ) {
            return abort( '404' );
        }
        /** @var Layer2AddressEntity $l2a */
        return response()->json( $l2a->jsonArray() );
    }

    /**
     * Delete a mac address from a Vlan Interface
     *
     * @param   int $id ID of the Layer2Address
     * @return  JsonResponse
     */
    public function delete( int $id ): JsonResponse{
        /** @var Layer2AddressEntity $l2a */
        if( !( $l2a = D2EM::getRepository( Layer2AddressEntity::class )->find( $id ) ) ) {
            return abort( '404' );
        }

        $l2a->getVlanInterface()->removeLayer2Address( $l2a );
        D2EM::remove( $l2a );
        D2EM::flush();

        return response()->json( [ 'success' => true, 'message' => 'The MAC address has been deleted.' ] );
    }

}
