<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25.08.2015
 * Time: 16:54
 */

namespace AppBundle\Service;


class UtilService extends AbstractDoctrineAware
{
    public function getLock($lockName, $timeout){

        $sql = " SELECT GET_LOCK(`".$lockName."`,". $timeout.");";

        $stmt = $this->doctrine->getEntityManager()->getConnection()->prepare($sql);

        return $stmt;
    }
}