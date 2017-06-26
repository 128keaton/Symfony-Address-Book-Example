<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Person;
use AppBundle\Form\PersonType;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Person');
        $people = $repository->findAll();
        return $this->render('default/index.html.twig', array('myArray' => $people));
    }

    /**
     * @Route("/new")
     */
    public function newAction(Request $request, EntityManagerInterface $em)
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($person);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'new/create.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/edit")
     */
    public function editAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Person')->find($id);
        if (!$person) {
            throw $this->createNotFoundException(
                'No person found for id ' . $id
            );
        }
        $form = $this->createForm(PersonType::class, $person);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'edit/index.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/destroy_person")
     */
    public function deletePersonAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $params = $request->request->all();
        $id = $params["id"];
        $repository = $em->getRepository('AppBundle:Person');
        $person = $repository->find($id);

        if ($person != null){
            $em->remove($person);
            $em->flush();
        }

        $people = $repository->findAll();
        return $this->render('default/index.html.twig', array('myArray' => $people));
    }


    /**
     * @Route("/search")
     */
    public function searchAction(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createFormBuilder(null)
            ->add('searchTerm', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Search'))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $searchTerm = $form["searchTerm"]->getData();
            $repository = $em->getRepository('AppBundle:Person');
            $result = $repository->createQueryBuilder('person')
                ->select('m')
                ->from('AppBundle:Person', 'm')
                ->andWhere('MATCH_AGAINST (m.firstName, m.lastName, m.phoneNumber, :field) > 0')
                ->setParameter('field', $searchTerm)
                ->getQuery()
                ->getResult();
            return $this->render('default/index.html.twig', array('myArray' => $result));
        }
        return $this->render('search/new.html.twig', array(
            'form' => $form->createView(),
        ));



    }

}
