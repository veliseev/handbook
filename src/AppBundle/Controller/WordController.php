<?php

namespace AppBundle\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Word;
use AppBundle\Form\WordType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Word controller.
 *
 * @Route("/")
 */
class WordController extends Controller
{

    /**
     * Searches all Word entities.
     *
     * @Route("/", name="word")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $search = $request->get('word', null);
        $words = array();

        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();

            // Remove everything that is not a letter and digit.
            $search = preg_replace('/[^\w0-9]/u', '', $search);
            $words = $em->getRepository('AppBundle:Word')->findWords($search);
        }

        if ($request->isXmlHttpRequest()) {
            $serializer = new Serializer(array(new ObjectNormalizer()), array(new JsonEncoder()));

            $words = $serializer->serialize($words, 'json');

            return new JsonResponse($words);
        }

        return array(
            'words' => $words,
            'search'=> $search
        );
    }

    /**
     * Lists all Word entities.
     *
     * @Route("/list/{page}", defaults={"page" = 1}, requirements={"page": "\d+"}, name="word_list")
     * @Method("GET")
     * @Template()
     */
    public function listAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('AppBundle:Word')->getFindAllQuery();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            $this->container->getParameter('appbundle_word_count_per_page')
        );

        return array(
            'pagination' => $pagination
        );
    }
    /**
     * Creates a new Word entity.
     *
     * @Route("/word/create", name="word_create")
     * @Method("POST")
     * @Template("AppBundle:Word:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Word();
        $form = $this->createCreateForm($entity);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('word_list'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Word entity.
     *
     * @param Word $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Word $entity)
    {
        $form = $this->createForm(new WordType(), $entity, array(
            'action' => $this->generateUrl('word_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Word entity.
     *
     * @Route("/new", name="word_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Word();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Word entity.
     *
     * @Route("/{id}", name="word_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Word')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Word entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Word entity.
     *
     * @Route("/edit/{slug}", name="word_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Word')->findOneBy(array('slug' => $slug));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Word entity.');
        }

        $entity->setSynonym(strip_tags($entity->getSynonym()));

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($entity->getId());

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Word entity.
    *
    * @param Word $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Word $entity)
    {
        $form = $this->createForm(new WordType(), $entity, array(
            'action' => $this->generateUrl('word_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Word entity.
     *
     * @Route("/word/update/{id}", name="word_update")
     * @Method("POST")
     * @Template("AppBundle:Word:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Word')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Word entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('word_edit', array('slug' => $entity->getSlug())));
        }

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Word entity.
     *
     * @Route("/delete/{id}", name="word_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {
        //$form = $this->createDeleteForm($id);
        //$form->handleRequest($request);
        //if ($form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Word')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Word entity.');
        }

            $em->remove($entity);
            $em->flush();
        //}

        return $this->redirect($this->generateUrl('word_list'));
    }

    /**
     * Creates a form to delete a Word entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('word_delete', array('id' => $id)))
            ->setMethod('GET')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
