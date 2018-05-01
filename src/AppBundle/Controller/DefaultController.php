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
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
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
            ->add('ethnic', TextType::class, array('required' => false))
            ->add('city', TextType::class, array('required' => false))
            ->add('birthday', DateType::class, array(
                'widget' => 'single_text',
                'required' => false,
            ))
            ->add('mobile', TextType::class, array('required' => false))
            ->add('address', TextType::class, array('required' => false))
            ->add('workYear', TextType::class, array('required' => false))
            ->add('marriage', ChoiceType::class, array(
                'choices' => array(
                    '未婚' => '未婚',
                    '已婚' => '已婚',
                    '离异' => '离异',
                    '丧偶' => '丧偶',
                )
            ))
            ->add('child', ChoiceType::class, array(
                'choices' => array(
                    '未育' => '未育',
                    '在孕' => '在孕',
                    '已育' => '已育',
                )
            ))
            ->add('plan', choiceType::class, array(
                'choices' => array(
                    '暂无' => '暂无',
                    '1年内' => '1年内',
                    '3年内' => '3年内',
                )
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

//    public function update($candidateId)
//    {
//        $candidate = $this->get('doctrine_mongodb')
//            ->getRepository('Candidates')
//            ->find($candidateId);
//
//        if (!$candidate) {
//            throw $this->createNotFoundException(
//                'No product found for id '.$candidateId
//            );
//        }
//
//        $candidate->get
//
//    }
//
}
