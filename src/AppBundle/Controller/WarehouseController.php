<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Warehouse;
use AppBundle\Form\WarehouseType;

/**
 * Warehouse controller.
 *
 */
class WarehouseController extends Controller
{

    /**
     * Lists all Warehouse entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Warehouse')->findAll();

        return $this->render('AppBundle:Warehouse:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Warehouse entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Warehouse();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('warehouse_show', array('id' => $entity->getId())));
        }

        return $this->render('AppBundle:Warehouse:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Warehouse entity.
     *
     * @param Warehouse $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Warehouse $entity)
    {
        $form = $this->createForm(new WarehouseType(), $entity, array(
            'action' => $this->generateUrl('warehouse_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Warehouse entity.
     *
     */
    public function newAction()
    {
        $entity = new Warehouse();
        $form   = $this->createCreateForm($entity);

        return $this->render('AppBundle:Warehouse:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Warehouse entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Warehouse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Warehouse entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:Warehouse:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Warehouse entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Warehouse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Warehouse entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:Warehouse:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Warehouse entity.
    *
    * @param Warehouse $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Warehouse $entity)
    {
        $form = $this->createForm(new WarehouseType(), $entity, array(
            'action' => $this->generateUrl('warehouse_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Warehouse entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Warehouse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Warehouse entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('warehouse_edit', array('id' => $id)));
        }

        return $this->render('AppBundle:Warehouse:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Warehouse entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Warehouse')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Warehouse entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('warehouse'));
    }

    /**
     * Creates a form to delete a Warehouse entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('warehouse_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
