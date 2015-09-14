<?php

/**
 * ArticleController controller de l'entité Article.
 *
 * PHP Version 5
 *
 * @author     Quétier Laurent <lq@dev-int.net>
 * @copyright  2014 Dev-Int GLSR
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @version    GIT: a4408b1f9fc87a1f93911d80e8421fef1bd96cab
 *
 * @link       https://github.com/GLSR/glsr
 */
namespace Glsr\GestockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Glsr\GestockBundle\Entity\Article;
use Glsr\GestockBundle\Entity\Supplier;
use Glsr\GestockBundle\Form\ArticleType;
use Glsr\GestockBundle\Form\ArticleReassignType;

/**
 * class ArticleController.
 *
 * @category   Controller
 */
class ArticleController extends Controller
{
    /**
     * Affiche la liste des articles (pagination).
     *
     * @param int $page numéro de page
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page)
    {
        // On récupère le nombre d'article par page
        // depuis un paramètre du conteneur
        // cf app/config/parameters.yml
        $nbPerPage = $this->container->getParameter('glsr.nb_per_page');

        $etm = $this->getDoctrine()->getManager();
        $articles = $etm
            ->getRepository('GlsrGestockBundle:Article')
            ->getArticles($nbPerPage, $page);

        return $this->render(
            'GlsrGestockBundle:Gestock/Article:index.html.twig',
            array(
                'articles' => $articles,
                'page'     => $page,
                'nb_page'  => ceil(count($articles) / $nbPerPage) ?: 1,
            )
        );
    }

    /**
     * Ajoute un article.
     *
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @throws AccessDeniedException
     */
    public function addShowAction()
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $article = new Article();

        // On crée le formulaire grâce à l'ArticleType
        $form = $this->createForm(new ArticleType(), $article);

        return $this->render(
            'GlsrGestockBundle:Gestock/Article:add.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Ajoute un article.
     *
     * @param Request $request objet requète
     *
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     * @throws AccessDeniedException
     */
    public function addProcessAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $article = new Article();
        // On crée le formulaire grâce à l'ArticleType
        $form = $this->createForm(new ArticleType(), $article);

        // On fait le lien Requête <-> Formulaire
        $form->submit($request);

        // On vérifie que les valeurs rentrées sont correctes
        if ($form->isValid()) {
            // On enregistre l'objet $article dans la base de données
            $etm = $this->getDoctrine()->getManager();
            $etm->persist($article);
            $etm->flush();
            $url = $this->generateUrl(
                'glstock_art_show',
                array('name' => $article->getName())
            );
            $message = "glsr.gestock.article.create.ok";
        } else {
            $url = $this->generateUrl('glstock_art_add');
            $message = "glsr.gestock.article.create.no";
        }
        // On définit un message flash
        $this->get('session')->getFlashBag()->add('info', $message);
        // On redirige vers la page de visualisation
        //  de l'article nouvellement créé
        return $this->redirect($url);
    }
    
    /**
     * Modification d'un article.
     *
     * @param Article $article article à modifier
     * @param Request $request objet requète
     *
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @throws AccessDeniedException
     */
    public function editShowAction(Article $article, Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        // On crée le formulaire grâce à l'ArticleType
        $form = $this->createForm(new ArticleType(), $article);

        return $this->render(
            'GlsrGestockBundle:Gestock/Article:edit.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Modification d'un article.
     *
     * @param Article $article article à modifier
     * @param Request $request objet requète
     *
     * @return Symfony\Component\HttpFoundation\Response|
     *   Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editProcessAction(Article $article, Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        // On crée le formulaire grâce à l'ArticleType
        $form = $this->createForm(new ArticleType(), $article);

        // On fait le lien Requête <-> Formulaire
        $form->submit($request);

        // On vérifie que les valeurs rentrées sont correctes
        if ($form->isValid()) {
            // On enregistre l'objet $article dans la base de données
            $etm = $this->getDoctrine()->getManager();
            $etm->persist($article);
            $etm->flush();
            $url = $this->generateUrl(
                'glstock_art_show',
                array('name' => $article->getName())
            );
            $message = "glsr.gestock.article.edit.ok";
        } else {
            $url = $this->generateUrl(
                'glstock_art_edit',
                array('name' => $article->getName())
            );
            $message = "glsr.gestock.article.create.no";
        }
        // On définit un message flash
        $this->get('session')->getFlashBag()->add('info', $message);
        // On redirige vers la page de visualisation
        //  de l'article nouvellement créé
        return $this->redirect($url);
    }

    /**
     * Supprime (désactive) un article.
     *
     * @param Article $article article à désactiver
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function deleteShowAction(Article $article)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $form = $this->createFormBuilder()->getForm();

        return $this->render(
            'GlsrGestockBundle:Gestock/Article:delete.html.twig',
            array(
                'article' => $article,
                'form'    => $form->createView(),
                )
        );
    }

    /**
     * Supprime (désactive) un article.
     *
     * @param Article $article
     * @param Request $request objet requète
     *
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws AccessDeniedException
     */
    public function deleteProcessAction(Article $article, Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $form = $this->createFormBuilder()->getForm();

        $form->submit($request);

        if ($form->isValid()) {
            // On supprime l'article
            $etm = $this->getDoctrine()->getManager();
            //On modifie l'état actif de l'article
            $article->setActive(0);
            $etm->persist($article);
            $etm->flush();

            $this->get('session')
                ->getFlashBag()
                ->add('info', 'glsr.gestock.article.delete.ok');

            // Puis on redirige vers l'accueil
            return $this->redirect($this->generateUrl('glstock_home'));
        } else {
            $this->get('session')
                ->getFlashBag()
                ->add('info', 'glsr.gestock.article.delete.no');
        }
    }
    
    /**
     * Affiche un article.
     *
     * @param \Glsr\GestockBundle\Entity\Article $article article à afficher
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Article $article)
    {
        return $this->render(
            'GlsrGestockBundle:Gestock/Article:article.html.twig',
            array(
                'article' => $article,
            )
        );
    }

    /**
     * Réassignation d'articles à un autre fournisseur
     *   que celui passé en paramètre. (Form)
     *
     * @param Supplier $supplier Fournisseur
     *   dont les articles doivent être réaffectés
     *
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @throws AccessDeniedException
     */
    public function reassignShowAction(Supplier $supplier)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        // Récupérer la liste des articles à reaffecter
        $articles = $this->getDoctrine()->getManager()
            ->getRepository('GlsrGestockBundle:Article')
            ->getArticleFromSupplier($supplier->getId());

        $form = $this->createForm(new ArticleReassignType(), $articles);

        return $this->render(
            'GlsrGestockBundle:Gestock/Article:reassign.html.twig',
            array(
                'form' => $form->createView(),
                'articles' => $articles,
                'supname' => $supplier->getName(),
                'supid' => $supplier->getId(),
            )
        );
    }
    
    /**
     * Réassignation d'articles à un autre fournisseur
     *   que celui passé en paramètre. (Process)
     *
     * @param Supplier $supplier Fournisseur
     *   dont les articles doivent être réaffectés
     * @param Request $request objet requète
     *
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws AccessDeniedException
     */
    public function reassignProcessAction(Supplier $supplier, Request $request)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        // Récupérer la liste des articles à reaffecter
        $articles = $this->getDoctrine()->getManager()
            ->getRepository('GlsrGestockBundle:Article')
            ->getArticleFromSupplier($supplier->getId());

        $form = $this->createForm(new ArticleReassignType(), $articles);

        // On fait le lien Requête <-> Formulaire
        $form->submit($request);
        $datas = $form;

        $newArticles = new Article();
        $newSupplier = new Supplier();
        $etm = $this->getDoctrine()->getManager();

        foreach ($datas as $data) {
            $input = explode('-', $data->getName());
            list($inputName, $articleId) = $input;
            $inputData = $data->getViewData();
            if ($inputName === 'supplier') {
                $newArticles = $etm->getRepository('GlsrGestockBundle:Article')
                    ->find($articleId);
                $newSupplier = $etm->getRepository('GlsrGestockBundle:Supplier')
                    ->find($inputData);
                //On modifie le fournisseur de l'article
                $newArticles->setSupplier($newSupplier);
                // On enregistre l'objet $article dans la base de données
                $etm->persist($newArticles);
                $etm->flush();
            }
        }
        return $this->redirect(
            $this->generateUrl(
                'glstock_suppli_del',
                array('id' => $supplier->getId())
            )
        );
    }
}
