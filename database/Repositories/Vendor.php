<?php

namespace Repositories;

use Doctrine\ORM\EntityRepository;

/**
 * Vendor
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class Vendor extends EntityRepository
{
    /**
     * Get all lcoation (or a particular one) for listing on the frontend CRUD
     *
     * @see \IXP\Http\Controller\Doctrine2Frontend
     *
     *
     * @param \stdClass $feParams
     * @param int|null $id
     * @return array Array of lcoation (as associated arrays) (or single element if `$id` passed)
     */
    public function getAllForFeList( \stdClass $feParams, int $id = null )
    {
        $dql = "SELECT  v.id AS id, 
                        v.name AS name, 
                        v.shortname AS shortname, 
                        v.nagios_name AS nagios_name, 
                        v.bundle_name AS bundle_name
                FROM Entities\\Vendor v
                WHERE 1 = 1";

        if( $id ) {
            $dql .= " AND v.id = " . (int)$id;
        }

        if( isset( $feParams->listOrderBy ) ) {
            $dql .= " ORDER BY " . $feParams->listOrderBy . ' ';
            $dql .= isset( $feParams->listOrderByDir ) ? $feParams->listOrderByDir : 'ASC';
        }

        $query = $this->getEntityManager()->createQuery( $dql );

        return $query->getArrayResult();
    }
}