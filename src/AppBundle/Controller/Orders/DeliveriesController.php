<?php
/**
 * DeliveriesController controller des livraisons.
 *
 * PHP Version 5
 *
 * @author    Quétier Laurent <lq@dev-int.net>
 * @copyright 2014 Dev-Int GLSR
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @version GIT: <git_id>
 *
 * @link      https://github.com/Dev-Int/glsr
 */
namespace AppBundle\Controller\Orders;

use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\Orders\AbstractOrdersController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use AppBundle\Entity\Orders\Orders;
use AppBundle\Form\Type\Orders\OrdersEditType;

/**
 * Deliveries controller.
 *
 * @Route("/deliveries")
 */
class DeliveriesController extends AbstractOrdersController
{
    /**
     * Lists all Orders entities.
     *
     * @Route("/", name="deliveries")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $etm = $this->getDoctrine()->getManager();
        $item = $this->container->getParameter('knp_paginator.page_range');
        $qbd = $etm->getRepository('AppBundle:Orders\Orders')->createQueryBuilder('o');
        $qbd->where('o.delivdate <= ' . date('Y-m-d'));
        $qbd->andWhere('o.status = 1');
        
        $paginator = $this->get('knp_paginator')->paginate($qbd, $request->query->get('page', 1), $item);
        return array(
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Orders entity.
     *
     * @Route("/{id}/show", name="deliveries_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Orders $orders)
    {
        return array(
            'orders' => $orders,
        );
    }

    /**
     * Displays a form to edit an existing Orders entity.
     *
     * @Route("/admin/{id}/edit", name="deliveries_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(Orders $orders)
    {
        $return = $this->abstractEditAction($orders, 'deliveries', OrdersEditType::class);

        return $return;
    }

    /**
     * Edits an existing Orders entity.
     *
     * @Route("/admin/{id}/update", name="deliveries_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("AppBundle:Orders/Deliveries:edit.html.twig")
     */
    public function updateAction(Orders $orders, Request $request)
    {
        $return = $this->abstractUpdateAction(
            $orders,
            $request,
            'deliveries',
            OrdersEditType::class
        );

        return $return;
    }

    /**
     * Print the current delivery.<br />Creating a `PDF` file for viewing on paper
     *
     * @Route("/{id}/print/", name="deliveries_print", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     *
     * @param \AppBundle\Entity\Orders\Orders $orders Order item to print
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function printAction(Orders $orders)
    {
        $return = $this->abstractPrintAction($orders, 'Deliveries');
        
        return $return;
    }
}