<?php

namespace AppBundle\Controller;

use CoolBundle\Document\Candidates;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $candidates = new Candidates();

        $form = $this->createFormBuilder($candidates)
            ->add('name', TextType::class)
            ->add('sex', ChoiceType::class, array(
                'choices' => array(
                    '男' => '男',
                    '女' => '女',
                ),
            ))
            ->add('birthday', DateType::class, array(
                'widget' => 'single_text',
            ))
            ->add('save', SubmitType::class, array('label' => '提交'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $candidates = $form->getData();

            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->persist($candidates);
            $dm->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('default/new.html.twig', array('form' => $form->createView(),
            ));
    }

}
