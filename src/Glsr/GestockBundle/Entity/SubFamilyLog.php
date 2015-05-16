<?php

/**
 * SubFamilyLog Entité SubFamilyLog.
 *
 * PHP Version 5
 *
 * @author     Quétier Laurent <lq@dev-int.net>
 * @copyright  2014 Dev-Int GLSR
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @version    GIT: 9b742e3da5dc43ee04f077d2a276b76620667745
 *
 * @link       https://github.com/GLSR/glsr
 */
namespace Glsr\GestockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * SubFamilyLog Entité SubFamilyLog.
 *
 * PHP Version 5
 *
 * @category   Entity
 *
 * @ORM\Table(name="gs_subfamilylog")
 * @ORM\Entity(repositoryClass="Glsr\GestockBundle\Entity\SubFamilyLogRepository")
 */
class SubFamilyLog
{
    /**
     * @var int Id de la sous-famille logistique
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idSubFamLog;

    /**
     * @var string Nom de la sous-famille logistique
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string Famille logistique dont dépend la sous-famille
     *
     * @ORM\ManyToOne(targetEntity="Glsr\GestockBundle\Entity\FamilyLog")
     * @ORM\JoinColumn(nullable=false)
     */
    private $familylog;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->idSubFamLog;
    }

    /**
     * Set name.
     *
     * @param string $name Désignation
     *
     * @return SubFamilyLog
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->familylogs = new ArrayCollection();
    }

    /**
     * Add familylogs.
     *
     * @param FamilyLog $familylogs Famille logistique
     *
     * @return SubFamilyLog
     */
    public function addFamilylog(FamilyLog $familylogs)
    {
        $this->familylogs[] = $familylogs;

        return $this;
    }

    /**
     * Remove familylogs.
     *
     * @param FamilyLog $familylogs Familles logistiques
     */
    public function removeFamilylog(FamilyLog $familylogs)
    {
        $this->familylogs->removeElement($familylogs);
    }

    /**
     * Get familylogs.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFamilylogs()
    {
        return $this->familylogs;
    }

    /**
     * Set familylog.
     *
     * @param FamilyLog $familylog Famille logistique
     *
     * @return SubFamilyLog
     */
    public function setFamilylog(FamilyLog $familylog)
    {
        $this->familylog = $familylog;

        return $this;
    }

    /**
     * Get familylog.
     *
     * @return \Glsr\GestockBundle\Entity\FamilyLog
     */
    public function getFamilylog()
    {
        return $this->familylog;
    }

    /**
     * Cette méthode permet de faire "echo $subFamilyLog".
     * <p>Ainsi, pour "afficher" $subFamilyLog,
     * PHP affichera en réalité le retour de cette méthode.<br />
     * Ici, le nom, donc "echo $subFamilyLog"
     * est équivalent à "echo $subFamilyLog->getName()"</p>.
     *
     * @return string name
     */
    public function __toString()
    {
        return $this->name;
    }
}
