<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Order;
use AppBundle\Entity\OrderProductLine;
use AppBundle\Entity\ProductSale;
use AppBundle\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\Document\DocumentService;

/**
 * Order controller.
 *
 */
class OrderController extends Controller
{

    /**
     * Lists all Order entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Order')->findAll();

        return $this->render('AppBundle:Order:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new Order.
     *
     */
    public function createAction(Request $request) {
        $postOrder = $request->get('appbundle_order');
        $quantities = $request->get('quantity');
        /* @var $orderService \AppBundle\Service\OrderService */
        $orderService = $this->get(\AppBundle\Service\OrderService::ID);
        $customerId = $postOrder['customer'];
        $productLines = array();
        foreach ($postOrder['productLines'] as $productSaleId => $value) {
            $productLines[] = array(
                'id' => $productSaleId,
                'quantity' => $quantities[$productSaleId]
            );
        }
        $orderId = $orderService->createOrder($customerId, $productLines);
        return $this->redirect(
            $this->generateUrl('order_show', array('id' => $orderId))
        );
    }

    /**
     * Creates a form to create a Order entity.
     *
     * @param Order $entity The entity
     *
     * @return Form The form
     */
    private function createCreateForm(Order $entity)
    {
        $form = $this->createForm(new OrderType(), $entity, array(
            'action' => $this->generateUrl('order_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Order entity.
     *
     */
    public function newAction()
    {
        $entity = new Order();
        $form = $this->createCreateForm($entity);

        return $this->render('AppBundle:Order:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Order entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Order')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Order entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:Order:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Order entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Order')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Order entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:Order:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Order entity.
     *
     * @param Order $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(Order $entity)
    {
        $form = $this->createForm(new OrderType(), $entity, array(
            'action' => $this->generateUrl('order_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Order entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $postOrder = $request->get('appbundle_order');
        $quantities = $request->get('quantity');

        $order = $entityManager->getRepository(Order::REPOSITORY)->find($id);

        if (!$order) {
            throw $this->createNotFoundException('Unable to find Order entity.');
        }

        $order->setCustomer(
                $entityManager
                        ->getRepository(Customer::REPOSITORY)
                        ->find($postOrder['customer'])
        );

        foreach ($order->getProductLines() as $productLine) {
            $entityManager->remove($productLine);
        }

        foreach ($postOrder['productLines'] as $productSaleId => $value) {
            $productLine = new OrderProductLine();
            $productLine->setProductSale(
                    $entityManager
                            ->getRepository(ProductSale::REPOSITORY)
                            ->find($productSaleId)
            );
            $productLine->setQuantity($quantities[$productSaleId]);
            $entityManager->persist($productLine);
            $order->addProductLine($productLine);
        }

        $entityManager->persist($order);
        $entityManager->flush();

        return $this->redirect(
                        $this->generateUrl('order_show', array('id' => $order->getId()))
        );
    }

    /**
     * Deletes a Order entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Order')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Order entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('order'));
    }

    /**
     * Creates a form to delete a Order entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('order_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

    public function viewInvoiceAction($orderId) {
        /* @var $documentService DocumentService */
        $documentService = $this->get(DocumentService::ID);
        $html = $documentService->getInvoiceHtml($orderId);
        return new \Symfony\Component\HttpFoundation\Response($html);
    }

}
