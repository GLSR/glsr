<?php

/**
 * Article Entité Article
 * 
 * PHP Version 5
 * 
 * @author     Quétier Laurent <lq@dev-int.net>
 * @copyright  2014 Dev-Int GLSR
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    GIT: 66c30ad5658ae2ccc5f74e6258fa4716d852caf9
 * @link       https://github.com/GLSR/glsr
 */

namespace Glsr\GestockBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ArticleRepository
 * 
 * @category   Entity
 * @package    Gestock
 * @subpackage Article
 */
class ArticleRepository extends EntityRepository
{
    /**
     * getArticles Affiche les articles actifs, avec une pagination
     * 
     * @param integer $nbPerPage Nombre d'article par page
     * @param integer $page      Numéro de la page en cours
     * 
     * @return \Doctrine\ORM\Tools\Pagination\Paginator Objet Paginator
     * @throws \InvalidArgumentException
     */
    public function getArticles($nbPerPage, $page)
    {
        // On ne sait pas combien de pages il y a
        // Mais on sait qu'une page doit être supérieure ou égale à 1
        // Bien sûr pour le moment on ne se sert pas (encore !) de cette variable
        if ($page < 1) {
            // On déclenche une exception InvalidArgumentException
            // Cela va afficher la page d'erreur 404
            // On pourra la personnaliser plus tard
            throw new \InvalidArgumentException(
                'l\'argument $page ne peut être inférieur à 1 (valeur : "' .
                $page . '").'
            );
        }
        
        $query = $this->createQueryBuilder('a')
            ->leftjoin('a.supplier', 's')
            ->addSelect('s')
            ->where('a.active = 1')
            ->orderBy('a.name', 'ASC')
            ->getQuery();
        
        // On définit l'article à partir duquel commencer la liste
        $query->setFirstResult(($page - 1) * $nbPerPage)
            ->setMaxResults($nbPerPage); // Ainsi que le nombre d'article à afficher
        
        // Et enfin, on retourne l'objet
        // Paginator correspondant à la requête construite
        return new Paginator($query);
    }
    
    /**
     * getArticleFromSupplier Renvoi les article du fournisseur en paramètre
     * 
     * @param integer $supplier Supplier_id
     * 
     * @return array Query result
     */
    public function getArticleFromSupplier($supplier)
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.active = 1')
            ->where('a.supplier = :id')
            ->setParameter('id', $supplier)
            ->orderBy('a.name', 'ASC')
            ->getQuery();
        
        return $query->getResult();
    }
}