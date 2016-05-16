<?php
/**
 * ZoneStorageController controller des zones de stockage.
 *
 * PHP Version 5
 *
 * @author    Quétier Laurent <lq@dev-int.net>
 * @copyright 2014 Dev-Int GLSR
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @version   since 1.0.0
 *
 * @link      https://github.com/Dev-Int/glsr
 */
namespace AppBundle\Controller\Settings\Divers;

use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\ZoneStorage;
use AppBundle\Form\Type\ZoneStorageType;

/**
 * ZoneStorage controller.
 *
 * @category Controller
 *
 * @Route("/admin/settings/divers/zonestorage")
 */
class ZoneStorageController extends AbstractController
{
    /**
     * Lists all ZoneStorage entities.
     *
     * @Route("/", name="zonestorage")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $etm = $this->getDoctrine()->getManager();
        $entities = $etm->getRepository('AppBundle:ZoneStorage')->findAll();
        
        return array(
            'entities'  => $entities,
        );
    }

    /**
     * Finds and displays a ZoneStorage entity.
     *
     * @Route("/{slug}/show", name="zonestorage_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(ZoneStorage $zonestorage)
    {
        $deleteForm = $this->createDeleteForm($zonestorage->getId(), 'zonestorage_delete');

        return array(
            'zonestorage' => $zonestorage,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new ZoneStorage entity.
     *
     * @Route("/new", name="zonestorage_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $zonestorage = new ZoneStorage();
        $form = $this->createForm(new ZoneStorageType(), $zonestorage);

        return array(
            'zonestorage' => $zonestorage,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new ZoneStorage entity.
     *
     * @Route("/create", name="zonestorage_create")
     * @Method("POST")
     * @Template("AppBundle:Settings/Divers/ZoneStorage:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $url = '';
        $zonestorage = new ZoneStorage();
        $form = $this->createForm(new ZoneStorageType(), $zonestorage);
        if ($form->handleRequest($request)->isValid()) {
            $etm = $this->getDoctrine()->getManager();
            $etm->persist($zonestorage);
            $etm->flush();

            if ($form->get('save')->isSubmitted()) {
                $url = $this->redirectToRoute('zonestorage_show', array('slug' => $zonestorage->getSlug()));
            } elseif ($form->get('addmore')->isSubmitted()) {
                $this->addFlash('info', 'gestock.settings.add_ok');
                $url = $this->redirect($this->generateUrl('zonestorage_new'));
            }
            return $url;
        }

        return array(
            'zonestorage' => $zonestorage,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ZoneStorage entity.
     *
     * @Route("/{slug}/edit", name="zonestorage_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(ZoneStorage $zonestorage)
    {
        $editForm = $this->createForm(new ZoneStorageType(), $zonestorage, array(
            'action' => $this->generateUrl('zonestorage_update', array('slug' => $zonestorage->getSlug())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($zonestorage->getId(), 'zonestorage_delete');

        return array(
            'zonestorage' => $zonestorage,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing ZoneStorage entity.
     *
     * @Route("/{slug}/update", name="zonestorage_update")
     * @Method("PUT")
     * @Template("AppBundle:Settings/Divers/ZoneStorage:edit.html.twig")
     */
    public function updateAction(ZoneStorage $zonestorage, Request $request)
    {
        $editForm = $this->createForm(new ZoneStorageType(), $zonestorage, array(
            'action' => $this->generateUrl('zonestorage_update', array('slug' => $zonestorage->getSlug())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('info', 'gestock.settings.edit_ok');

            return $this->redirectToRoute('zonestorage_edit', array('slug' => $zonestorage->getSlug()));
        }
        $deleteForm = $this->createDeleteForm($zonestorage->getId(), 'zonestorage_delete');

        return array(
            'zonestorage' => $zonestorage,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a ZoneStorage entity.
     *
     * @Route("/{id}/delete", name="zonestorage_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(ZoneStorage $zonestorage, Request $request)
    {
        $form = $this->createDeleteForm($zonestorage->getId(), 'zonestorage_delete');
        if ($form->handleRequest($request)->isValid()) {
            $etm = $this->getDoctrine()->getManager();
            $etm->remove($zonestorage);
            $etm->flush();
        }

        return $this->redirectToRoute('zonestorage');
    }
}
