<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\ProductAcquisition;
use AppBundle\Form\ProductAcquisitionType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProductAcquisition controller.
 *
 */
class ProductAcquisitionController extends Controller
{

    /**
     * Lists all ProductAcquisition entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:ProductAcquisition')->findAll();

        return $this->render('AppBundle:ProductAcquisition:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new ProductAcquisition entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ProductAcquisition();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('productacquisition_show', array('id' => $entity->getId())));
        }

        return $this->render('AppBundle:ProductAcquisition:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ProductAcquisition entity.
     *
     * @param ProductAcquisition $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ProductAcquisition $entity)
    {
        $form = $this->createForm(new ProductAcquisitionType(), $entity, array(
            'action' => $this->generateUrl('productacquisition_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ProductAcquisition entity.
     *
     */
    public function newAction()
    {
        $entity = new ProductAcquisition();
        $form   = $this->createCreateForm($entity);

        return $this->render('AppBundle:ProductAcquisition:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ProductAcquisition entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ProductAcquisition')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProductAcquisition entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:ProductAcquisition:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     *
     * Display  a product's acquisition history
     *
     */

    public function showproAction($product)
    {

        $criteria = array('product' => $product);
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:ProductAcquisition')->findBy($criteria);

        if (!$entities) {
            return new Response('<h1>nada</h1>');
        }

        $deleteForm = $this->createDeleteForm($product);

        return $this->render('AppBundle:ProductAcquisition:index.html.twig', array(
            'entities'      => $entities,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ProductAcquisition entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ProductAcquisition')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProductAcquisition entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:ProductAcquisition:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ProductAcquisition entity.
    *
    * @param ProductAcquisition $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ProductAcquisition $entity)
    {
        $form = $this->createForm(new ProductAcquisitionType(), $entity, array(
            'action' => $this->generateUrl('productacquisition_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ProductAcquisition entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ProductAcquisition')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProductAcquisition entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('productacquisition_edit', array('id' => $id)));
        }

        return $this->render('AppBundle:ProductAcquisition:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ProductAcquisition entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:ProductAcquisition')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ProductAcquisition entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('productacquisition'));
    }

    /**
     * Creates a form to delete a ProductAcquisition entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('productacquisition_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
