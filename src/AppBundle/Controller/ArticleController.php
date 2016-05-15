<?php
/**
 * ArticleController controller des articles.
 *
 * PHP Version 5
 *
 * @author    Quétier Laurent <lq@dev-int.net>
 * @copyright 2014 Dev-Int GLSR
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @version since 1.0.0
 *
 * @link      https://github.com/Dev-Int/glsr
 */
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Article;
use AppBundle\Entity\Supplier;
use AppBundle\Form\Type\ArticleType;
use AppBundle\Form\Type\ArticleReassignType;

/**
 * Article controller.
 *
 * @category Controller
 *
 * @Route("/articles")
 */
class ArticleController extends AbstractController
{
    /**
     * Lists all Article entities.
     *
     * @Route("/", name="articles")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('AppBundle:Article')->getArticles();
        $this->addQueryBuilderSort($qb, 'article');
        $paginator = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 5);
        
        return array(
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Article entity.
     *
     * @Route("/{slug}/show", name="articles_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Article $article)
    {
        $deleteForm = $this->createDeleteForm($article->getId(), 'articles_delete');

        return array(
            'article' => $article,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Article entity.
     *
     * @Route("/admin/new", name="articles_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $article = new Article();
        $form = $this->createForm(new ArticleType(), $article);

        return array(
            'article' => $article,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Article entity.
     *
     * @Route("/create", name="articles_create")
     * @Method("POST")
     * @Template("AppBundle:Article:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(new ArticleType(), $article);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('articles_show', array('slug' => $article->getSlug()));
        }

        return array(
            'article' => $article,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Article entity.
     *
     * @Route("/admin/{slug}/edit", name="articles_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Article $article)
    {
        $editForm = $this->createForm(new ArticleType(), $article, array(
            'action' => $this->generateUrl('articles_update', array('slug' => $article->getSlug())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($article->getId(), 'articles_delete');

        return array(
            'article' => $article,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Article entity.
     *
     * @Route("/{slug}/update", name="articles_update")
     * @Method("PUT")
     * @Template("AppBundle:Article:edit.html.twig")
     */
    public function updateAction(Article $article, Request $request)
    {
        $editForm = $this->createForm(new ArticleType(), $article, array(
            'action' => $this->generateUrl('articles_update', array('slug' => $article->getSlug())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('articles_edit', array('slug' => $article->getSlug()));
        }
        $deleteForm = $this->createDeleteForm($article->getId(), 'articles_delete');

        return array(
            'article' => $article,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Réassigner les articles d'un fournisseur.
     *
     * @param Supplier $supplier Fournisseur à réassigner
     * @Route("/{slug}/reassign", name="articles_reassign")
     * @Method("GET")
     * @Template()
     */
    public function reassignAction(Supplier $supplier)
    {
        $em = $this->getDoctrine()->getManager();
        $suppliers = $em->getRepository('AppBundle:Supplier')->getSupplierForReassign($supplier);
        $articles = $em->getRepository('AppBundle:Article')
            ->getArticleFromSupplier($supplier->getId());

        $reassign_form = $this->createForm(
            new ArticleReassignType(),
            $articles,
            array(
                'action' => $this->generateUrl('articles_change', array('slug' => $supplier->getSlug())),
                'method' => 'PUT',
            )
        );

        return array(
            'reassign_form' => $reassign_form->createView(),
            'suppliers' => $suppliers,
            'articles' => $articles
        );
    }

    /**
     * Creates a new Article entity.
     *
     * @Route("/{slug}/change", name="articles_change")
     * @Method("PUT")
     * @Template("AppBundle:Article:reassign.html.twig")
     */
    public function changeAction(Request $request, Supplier $supplier)
    {
        $em = $this->getDoctrine()->getManager();
        $suppliers = $em->getRepository('AppBundle:Supplier')->getSupplierForReassign($supplier);
        $articles = $em->getRepository('AppBundle:Article')->getArticleFromSupplier($supplier->getId());
        $newArticles = new Article();
        $newSupplier = new Supplier();

        $reassign_form = $this->createForm(
            new ArticleReassignType(),
            $articles,
            array(
                'action' => $this->generateUrl('articles_change', array('slug' => $supplier->getSlug())),
                'method' => 'PUT',
            )
        );
        $datas = $reassign_form->handleRequest($request);

        foreach ($datas as $data) {
            $input = explode('-', $data->getName());
            list($inputName, $articleId) = $input;
            $inputData = $data->getViewData();
            if ($inputName === 'supplier') {
                $newArticles = $em->getRepository('AppBundle:Article')->find($articleId);
                $newSupplier = $em->getRepository('AppBundle:Supplier')->find($inputData);
                //On modifie le fournisseur de l'article
                $newArticles->setSupplier($newSupplier);
                // On enregistre l'objet $article dans la base de donnÃ©es
                $em->persist($newArticles);
            }
            $em->flush();
            return $this->redirectToRoute('articles');
        }

        return array(
            'reassign_form' => $reassign_form->createView(),
            'suppliers' => $suppliers,
            'articles' => $articles
        );
    }

    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="articles_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('article', $field, $type);

        return $this->redirect($this->generateUrl('articles'));
    }
    /**
     * Deletes a Article entity.
     *
     * @Route("/admin/{id}/delete", name="articles_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Article $article, Request $request)
    {
        $form = $this->createDeleteForm($article->getId(), 'articles_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $article->setActive(false);
            $em->persist($article);
            $em->flush();
        }

        return $this->redirectToRoute('articles');
    }
}
