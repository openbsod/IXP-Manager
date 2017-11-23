<?php

namespace IXP\Http\Controllers;

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

use App, Cache, D2EM;

use IXP\Services\Grapher\Graph as Graph;

use Entities\{
    Customer            as CustomerEntity,
    IXP                 as IXPEntity,
    VirtualInterface    as VirtualInterfaceEntity
};


use Illuminate\View\View;


/**
 * Admin Controller
 *
 * @author     Yann Robin <yann@islandbridgenetworks.ie>
 * @author     Barry O'Donovan <barry@islandbridgenetworks.ie>
 *
 * @category   Admin
 * @copyright  Copyright (C) 2009-2017 Internet Neutral Exchange Association Company Limited By Guarantee
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL V2.0
 */
class AdminController extends Controller
{

    /**
     * Display the home page
     *
     * @return view
     */
    public function dashboard(): View
    {
        return view( 'admin/dashboard' )->with([
            'stats'                 => $this->dashboardStats(),
            'graphs'                => $this->publicPeeringGraphs(),
        ]);
    }


    /**
     * Get type counts statistics
     *
     * @return array array of statistics
     */
    private function dashboardStats()
    {
        // only do this once every 60 minutes
        if( !( $cTypes = Cache::get( 'admin_ctypes' ) ) ) {

            $cTypes['types'] = D2EM::getRepository( CustomerEntity::class )->getTypeCounts();

            $vis = D2EM::getRepository( VirtualInterfaceEntity::class )->getByLocation();

            $speeds     = [];
            $byLocation = [];
            $byLan      = [];
            $byIxp      = [];

            foreach( $vis as $vi ) {

                $location       = $vi['locationname'];
                $infrastructure = $vi['infrastructure'];

                if( !isset( $byLocation[ $location ] ) ) {
                    $byLocation[ $location ] = [];
                }

                if( !isset( $byLan[ $infrastructure ] ) ) {
                    $byLan[ $infrastructure ] = [];
                }

                if( !isset( $byIxp[ $vi['ixp'] ] ) ) {
                    $byIxp[ $vi[ 'ixp' ] ] = [];
                }

                if( !isset( $speeds[ $vi['speed'] ] ) ) {
                    $speeds[ $vi[ 'speed' ] ] = 1;
                } else {
                    $speeds[ $vi[ 'speed' ] ]++;
                }

                if( !isset( $byLocation[ $vi['locationname'] ][ $vi['speed'] ] ) ) {
                    $byLocation[ $location ][ $vi[ 'speed' ] ] = 1;
                } else {
                    $byLocation[ $location ][ $vi[ 'speed' ] ]++;
                }

                if( !isset( $byixp[ $vi['ixp'] ][ $vi['speed'] ] ) ) {
                    $byIxp[ $vi[ 'ixp' ] ][ $vi[ 'speed' ] ] = 1;
                } else {
                    $byIxp[ $vi[ 'ixp' ] ][ $vi[ 'speed' ] ]++;
                }

                if( !isset( $byLan[ $infrastructure ][ $vi['speed'] ] ) ) {
                    $byLan[ $infrastructure ][ $vi[ 'speed' ] ] = 1;
                } else {
                    $byLan[ $infrastructure ][ $vi[ 'speed' ] ]++;
                }
            }

            ksort( $speeds, SORT_NUMERIC );
            $cTypes['speeds']      = $speeds;
            $cTypes['byLocation']  = $byLocation;
            $cTypes['byLan']       = $byLan;
            $cTypes['byIxp']       = $byIxp;

            Cache::put( 'admin_ctypes', $cTypes, 3600 );
        }

        return $cTypes;
    }

    /**
     * Get public peering graphs
     *
     * @return array array of graphs
     */
    private function publicPeeringGraphs()
    {
        $grapher = App::make('IXP\Services\Grapher');

        if( !( $graphs = Cache::get( 'admin_stats' ) ) ) {
            $graphs = [];

            $graphs['ixp'] = $grapher->ixp( D2EM::getRepository(IXPEntity::class )->getDefault() )
                ->setType(     Graph::TYPE_PNG )
                ->setProtocol( Graph::PROTOCOL_ALL )
                ->setPeriod(   Graph::PERIOD_MONTH )
                ->setCategory( Graph::CATEGORY_BITS );

            foreach( D2EM::getRepository(IXPEntity::class )->getDefault()->getInfrastructures() as $inf ) {
                $graphs[ $inf->getId()] = $grapher->infrastructure( $inf )
                    ->setType(     Graph::TYPE_PNG )
                    ->setProtocol( Graph::PROTOCOL_ALL )
                    ->setPeriod(   Graph::PERIOD_MONTH )
                    ->setCategory( Graph::CATEGORY_BITS );
            }

            Cache::put( 'admin_stats', $graphs, 300 );
        }

        return $graphs;
    }

}