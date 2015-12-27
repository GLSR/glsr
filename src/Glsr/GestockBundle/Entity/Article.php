<?php

/**
 * Entité Article.
 *
 * PHP Version 5
 *
 * @author     Quétier Laurent <lq@dev-int.net>
 * @copyright  2014 Dev-Int GLSR
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @version    0.1.0
 *
 * @link       https://github.com/Dev-Int/glsr
 */
namespace Glsr\GestockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Glsr\GestockBundle\Entity\Supplier;
use Glsr\GestockBundle\Entity\UnitStorage;
use Glsr\GestockBundle\Entity\ZoneStorage;
use Glsr\GestockBundle\Entity\FamilyLog;
use Glsr\GestockBundle\Entity\SubFamilyLog;

/**
 * Article.
 *
 * @category   Entity
 *
 * @ORM\Table(name="gs_article")
 * @ORM\Entity(repositoryClass="Glsr\GestockBundle\Entity\ArticleRepository")
 * @UniqueEntity(fields="name", message="Ce nom d'article est déjà utilisé.")
 * @ORM\HasLifecycleCallbacks()
 */
class Article
{
    /**
     * @var int Id de l'article
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idArt;

    /**
     * @var string intitulé de l'article
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="'^\w+[^/]'",
     *     message="L'intitulé ne peut contenir que des lettres,
     *     chiffres et _ ou -"
     * )
     */
    private $name;

    /**
     * @var string Nom du fournisseur
     *
     * @ORM\ManyToOne(targetEntity="Glsr\GestockBundle\Entity\Supplier")
     */
    private $supplier;

    /**
     * @var string Unité de stockage
     *
     * @ORM\ManyToOne(targetEntity="Glsr\GestockBundle\Entity\UnitStorage")
     */
    private $unit_storage;

    /**
     * @var decimal Conditionement (quantité)
     *
     * @ORM\Column(name="packaging", type="decimal", precision=7, scale=3)
     * @Assert\Type(type="numeric",
     * message="La valeur {{ value }} n'est pas un type {{ type }} valide.")
     */
    private $packaging;

    /**
     * @var decimal prix de l'article
     *
     * @ORM\Column(name="price", type="decimal", precision=7, scale=3)
     * @Assert\Type(type="numeric",
     * message="La valeur {{ value }} n'est pas un type {{ type }} valide.")
     */
    private $price;

    /**
     * @var decimal Quantité en stock
     *
     * @ORM\Column(name="quantity", type="decimal", precision=7, scale=3)
     * @Assert\Type(type="numeric",
     * message="La valeur {{ value }} n'est pas un type {{ type }} valide.")
     */
    private $quantity;

    /**
     * @var decimal Stock minimum
     *
     * @ORM\Column(name="minstock", type="decimal", precision=7, scale=3)
     * @Assert\Type(type="numeric",
     * message="La valeur {{ value }} n'est pas un type {{ type }} valide.")
     */
    private $minstock;

    /**
     * @var string Zone(s) de stockage
     *
     * @ORM\ManyToMany(targetEntity="Glsr\GestockBundle\Entity\ZoneStorage")
     * @ORM\JoinTable(name="gs_article_zonestorage")
     * @Assert\NotBlank()
     */
    private $zone_storages;

    /**
     * @var string Famille logistique
     *
     * @ORM\ManyToOne(targetEntity="Glsr\GestockBundle\Entity\FamilyLog")
     * @Assert\NotBlank()
     */
    private $family_log;

    /**
     * @var string Sous-famille logistique
     *
     * @ORM\ManyToOne(targetEntity="Glsr\GestockBundle\Entity\SubFamilyLog")
     * @Assert\NotBlank()
     */
    private $sub_family_log;

    /**
     * @var bool Activé/Désactivé
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->zone_storages = new ArrayCollection();
        $this->active = true;
        $this->quantity = 0.000;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->idArt;
    }

    /**
     * Set name.
     *
     * @param string $name Nom de l'article
     *
     * @return Article
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
     * Set packaging.
     *
     * @param string $packaging Conditionnement (quantité)
     *
     * @return Article
     */
    public function setPackaging($packaging)
    {
        $this->packaging = $packaging;

        return $this;
    }

    /**
     * Get packaging.
     *
     * @return string
     */
    public function getPackaging()
    {
        return $this->packaging;
    }

    /**
     * Set price.
     *
     * @param string $price prix de l'article
     *
     * @return Article
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set quantity.
     *
     * @param string $quantity quantité en stock
     *
     * @return Article
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set minstock.
     *
     * @param string $minstock stock minimum
     *
     * @return Article
     */
    public function setMinstock($minstock)
    {
        $this->minstock = $minstock;

        return $this;
    }

    /**
     * Get minstock.
     *
     * @return string
     */
    public function getMinstock()
    {
        return $this->minstock;
    }

    /**
     * Set supplier.
     *
     * @param Supplier $supplier Fournisseur de l'article
     *
     * @return Article
     */
    public function setSupplier(Supplier $supplier = null)
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * Get supplier.
     *
     * @return Supplier
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * Set unit_storage.
     *
     * @param UnitStorage $unitStorage Unité de stockage
     *
     * @return Article
     */
    public function setUnitStorage(UnitStorage $unitStorage = null)
    {
        $this->unit_storage = $unitStorage;

        return $this;
    }

    /**
     * Get unit_storage.
     *
     * @return UnitStorage
     */
    public function getUnitStorage()
    {
        return $this->unit_storage;
    }

    /**
     * Add zone_storages.
     *
     * @param \Glsr\GestockBundle\Entity\ZoneStorage
     * $zoneStorages Zone(s) de stockage
     *
     * @return Article
     */
    public function addZoneStorage(ZoneStorage $zoneStorages)
    {
        $this->zone_storages[] = $zoneStorages;

        return $this;
    }

    /**
     * Remove zone_storages.
     *
     * @param ZoneStorage $zoneStorages Zone de stockage à supprimer
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function removeZoneStorage(ZoneStorage $zoneStorages)
    {
        $this->zone_storages->removeElement($zoneStorages);
    }

    /**
     * Get zone_storages.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getZoneStorages()
    {
        return $this->zone_storages;
    }

    /**
     * Set family_log.
     *
     * @param FamilyLog $familyLog Famille Logistique
     *
     * @return Article
     */
    public function setFamilyLog(FamilyLog $familyLog = null)
    {
        $this->family_log = $familyLog;

        return $this;
    }

    /**
     * Get family_log.
     *
     * @return FamilyLog
     */
    public function getFamilyLog()
    {
        return $this->family_log;
    }

    /**
     * Set sub_family_log.
     *
     * @param SubFamilyLog $subFamilyLog Sous-famille logistique
     *
     * @return Article
     */
    public function setSubFamilyLog(SubFamilyLog $subFamilyLog = null)
    {
        $this->sub_family_log = $subFamilyLog;

        return $this;
    }

    /**
     * Get sub_family_log.
     *
     * @return SubFamilyLog
     */
    public function getSubFamilyLog()
    {
        return $this->sub_family_log;
    }

    /**
     * Set active.
     *
     * @param bool $active Activé/Désactivé
     *
     * @return Article
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Cette méthode permet de faire "echo $article".
     * <p>Ainsi, pour "afficher" $article,
     * PHP affichera en réalité le retour de cette méthode.<br />
     * Ici, le nom, donc "echo $article"
     * est équivalent à "echo $article->getName()".</p>
     *
     * @return string name
     */
    public function __toString()
    {
        return $this->name;
    }
}
