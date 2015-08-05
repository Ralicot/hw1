<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\OrderProductLine;
use AppBundle\Form\OrderProductLineType;

/**
 * OrderProductLine controller.
 *
 */
class OrderProductLineController extends Controller
{

    /**
     * Lists all OrderProductLine entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:OrderProductLine')->findAll();

        return $this->render('AppBundle:OrderProductLine:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new OrderProductLine entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new OrderProductLine();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('orderproductline_show', array('id' => $entity->getId())));
        }

        return $this->render('AppBundle:OrderProductLine:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a OrderProductLine entity.
     *
     * @param OrderProductLine $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(OrderProductLine $entity)
    {
        $form = $this->createForm(new OrderProductLineType(), $entity, array(
            'action' => $this->generateUrl('orderproductline_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new OrderProductLine entity.
     *
     */
    public function newAction()
    {
        $entity = new OrderProductLine();
        $form   = $this->createCreateForm($entity);

        return $this->render('AppBundle:OrderProductLine:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a OrderProductLine entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:OrderProductLine')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrderProductLine entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:OrderProductLine:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing OrderProductLine entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:OrderProductLine')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrderProductLine entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:OrderProductLine:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a OrderProductLine entity.
    *
    * @param OrderProductLine $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(OrderProductLine $entity)
    {
        $form = $this->createForm(new OrderProductLineType(), $entity, array(
            'action' => $this->generateUrl('orderproductline_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing OrderProductLine entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:OrderProductLine')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrderProductLine entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('orderproductline_edit', array('id' => $id)));
        }

        return $this->render('AppBundle:OrderProductLine:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a OrderProductLine entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:OrderProductLine')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find OrderProductLine entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('orderproductline'));
    }

    /**
     * Creates a form to delete a OrderProductLine entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('orderproductline_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
