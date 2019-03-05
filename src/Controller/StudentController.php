<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class StudentController extends AbstractController
{
    /**
     * @Route("/student", name="student_index")
     * @IsGranted("ROLE_USER")
     */
    public function index()
    {
        return $this->render('student/index.html.twig', []);
    }
}
