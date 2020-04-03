<?php

namespace App\Controller;
use App\Form\StatusType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Time;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class TimeController extends AbstractController
{
    private $entityManager;   
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/time", name="time")
     */
    public function index(Request $request)
    {
		$forma = new Time();
        $form = $this->createFormBuilder($forma)
            ->add('logon', SubmitType::class, ['label' => 'Pradėti darbą'])
            ->getForm();
			
        //$form = $this->createForm(StatusType::class);
		$form->handleRequest($request);
		if ($form->getClickedButton() && 'logon' === $form->getClickedButton()->getName()) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
		$data = date("Y-m-d H:i");
        $id = $user->getId();

		echo "$id ir $data";
		
		 //return $this->redirectToRoute('home');
		}

			
        $timeRow = $this->entityManager->getRepository(Time::class)->findAll();
		
        return $this->render('time/index.html.twig', [
            'timeRow' => $timeRow,
            'form' => $form->createView(),
        ]);
    }
}
