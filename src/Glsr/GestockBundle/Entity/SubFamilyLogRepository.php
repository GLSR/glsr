<?php

/**
 * SubFamilyLogRepository Entité SubFamilyLog
 * 
 * PHP Version 5
 * 
 * @category   Entity
 * @package    Gestock
 * @subpackage Settings
 * @author     Quétier Laurent <lq@dev-int.net>
 * @copyright  2014 Dev-Int GLSR
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    GIT: e939362e5ab05d032699320b78b015b171c4cc9e
 * @link       https://github.com/GLSR/glsr
 */

namespace Glsr\GestockBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SubFamilyLogRepository Entité SubFamilyLog
 * 
 * @category   Entity
 * @package    Gestock
 * @subpackage Settings
 * @author     Quétier Laurent <lq@dev-int.net>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       https://github.com/GLSR/glsr
 */
class SubFamilyLogRepository extends EntityRepository
{
    /**
     * getFromFamilyLog Renvoie la liste des sous-famille logistique attachées 
     * à la famille logistique passée en paramètre
     * 
     * @param integer $idFamLog id de la famille logistique
     * 
     * @return array query result
     */
    public function getFromFamilyLog($idFamLog)
    {
        $query = $this->_em->createQuery(
            "SELECT sf FROM GlsrGestockBundle:subFamilyLog sf "
            . "WHERE sf.familylog = :id"
        );
        $query->setParameter('id', $idFamLog);

        return $query->getResult();
    }
}
