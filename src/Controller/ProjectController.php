<?php


namespace App\Controller;

use App\Entity\Developer;
use App\Entity\Project;


use Doctrine\Common\Collections\ArrayCollection;
use SplFileInfo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



use Doctrine\Common\Collections\Criteria;

use Doctrine\Persistence\ManagerRegistry;


class ProjectController extends AbstractController
{


    public function index($id,Request $request, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $project_repo = $doctrine->getRepository(Project::Class);

        /** @var Project $project */
        $project = $project_repo->find($id);
        $description = $project->getTitle();
        $developers = $project->getDevelopers();



        return $this->render('project/index.html.twig',[
            'project' => $project,
            'description' => $description,
            'users' => $developers,
        ]);
    }


    public function developerList(ManagerRegistry $doctrine)
    {
        $developers = $doctrine->getRepository(Developer::class)->findAll();
        return $this->render('developer/developer_list.html.twig',[

            'users' => $developers,
        ]);
    }

    public function deleteMan($id, ManagerRegistry $doctrine, $projectId){


        $user = $doctrine->getRepository(Developer::class)->find($id);

        $project = $doctrine->getRepository(Project::class)->find($projectId);

        if (!$project) {
            throw $this->createNotFoundException('Пользователь не найден');
        }
        $description = $project->getTitle();
        $developers = $project->getDevelopers();

        $em = $doctrine->getManager();
        $user->removeProject($project);
        $em->persist($user);
        $em->flush();

        return $this->render('project/index.html.twig',[
            'project' => $project,
            'description' => $description,
            'users' => $developers,
        ]);
    }





}