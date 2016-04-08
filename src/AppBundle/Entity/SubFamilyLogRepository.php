<?php

/**
 * Entité SubFamilyLog.
 *
 * PHP Version 5
 *
 * @author     Quétier Laurent <lq@dev-int.net>
 * @copyright  2014 Dev-Int GLSR
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @version    since 1.0.0
 *
 * @link       https://github.com/Dev-Int/glsr
 */
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SubFamilyLogRepository Entité SubFamilyLog.
 *
 * @category   Entity
 */
class SubFamilyLogRepository extends EntityRepository
{
    /**
     * Renvoie la liste des sous-famille logistique attachées
     * à la famille logistique passée en paramètre.
     *
     * @param int $idFamLog id de la famille logistique
     *
     * @return array query result
     */
    public function getFromFamilyLog($idFamLog)
    {
        $query = $this->_em->createQuery(
            'SELECT sf FROM AppBundle:subFamilyLog sf '
            .'WHERE sf.familylogs = :id'
        );
        $query->setParameter('id', $idFamLog);

        return $query->getResult();
    }
}
