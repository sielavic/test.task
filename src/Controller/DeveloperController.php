<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;



class DeveloperController extends AbstractController
{
    public function addProjectForm(EntityManagerInterface $entityManager, int $id, int $projectId): Response
    {
        $developer = $entityManager->getRepository(Developer::class)->find($id);
        $project = $entityManager->getRepository(Project::class)->find($projectId);

        if (!$developer || !$project) {
            return new Response('Разработчик или проект не найден', Response::HTTP_NOT_FOUND);
        }


        return $this->render('developer/add_project.html.twig', [
            'developer' => $developer,
            'project' => $project,
        ]);
    }

    public function addProjectToDeveloper(EntityManagerInterface $entityManager, int $id, int $projectId): Response
    {
        $developer = $entityManager->getRepository(Developer::class)->find($id);
        $project = $entityManager->getRepository(Project::class)->find($projectId);

        if (!$developer || !$project) {
            return new Response('Разработчик или проект не найден', Response::HTTP_NOT_FOUND);
        }

        $developer->addProject($project);
        $entityManager->flush();

        return new Response('Проект добавлен к разработчику');
    }

}