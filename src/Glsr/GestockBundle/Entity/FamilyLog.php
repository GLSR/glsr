<?php

/**
 * FamilyLog Entité FamilyLog
 * 
 * PHP Version 5
 * 
 * @author     Quétier Laurent <lq@dev-int.net>
 * @copyright  2014 Dev-Int GLSR
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    GIT: 9b742e3da5dc43ee04f077d2a276b76620667745
 * @link       https://github.com/GLSR/glsr
 */

namespace Glsr\GestockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FamilyLog Entité FamilyLog
 * 
 * @category   Entity
 * @package    Gestock
 * @subpackage Settings
 *
 * @ORM\Table(name="gs_familylog")
 * @ORM\Entity(repositoryClass="Glsr\GestockBundle\Entity\FamilyLogRepository")
 */
class FamilyLog
{
    /**
     * @var integer $id Id de la famille logistique
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idFamLog;

    /**
     * @var string $name Nom de la famille logistique
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->idFamLog;
    }

    /**
     * Set name
     *
     * @param string $name Désignation
     * 
     * @return FamilyLog
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Cette méthode permet de faire "echo $familyLog".
     * <p>Ainsi, pour "afficher" $familyLog, 
     * PHP affichera en réalité le retour de cette méthode.<br />
     * Ici, le nom, donc "echo $familyLog" 
     * est équivalent à "echo $familyLog->getName()"</p>
     * 
     * @return string name
     */
    public function __toString()
    {
        return $this->name;
    }
}