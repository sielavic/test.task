<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Entity\Project;
use App\Form\DeveloperType;
use App\Form\TransferDeveloperType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    public function hireDeveloper($projectId, ManagerRegistry $doctrine, Request $request): Response
    {

        $em = $doctrine->getManager();

        $developer = new Developer();
        $project_repo = $doctrine->getRepository(Project::Class);


        $form = $this->createForm(DeveloperType::class, $developer);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Project $project */
            $project = $project_repo->find($projectId);
            $developer->addProject($project);
            $project_repo = $doctrine->getRepository(Project::Class);

            /** @var Project $project */
            $project = $project_repo->find($projectId);
            $developer->addProject($project);
            $description = $project->getTitle();
            $developers = $project->getDevelopers();

            $em = $doctrine->getManager();
            $em->persist($developer);
            $em->flush();


            return $this->render('project/index.html.twig', [
                'project' => $project,
                'description' => $description,
                'users' => $developers,
            ]);
        }

        return $this->render('developer/hire.html.twig', [
            'form_developer' => $form->createView()
        ]);


    }

    public function fireForm(EntityManagerInterface $entityManager, int $id): Response
    {
        $developer = $entityManager->getRepository(Developer::class)->find($id);

        if (!$developer) {
            return new Response('Разработчик не найден', Response::HTTP_NOT_FOUND);
        }

        return $this->render('developer/fire.html.twig', [
            'developer' => $developer,
        ]);
    }

    public function fireDeveloper(EntityManagerInterface $entityManager, int $id): Response
    {
        $developer = $entityManager->getRepository(Developer::class)->find($id);

        if (!$developer) {
            return new Response('Разработчик уволен', Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($developer);
        $entityManager->flush();

        return new Response('Разработчик уволен');
    }

    public function transferDeveloper(EntityManagerInterface $entityManager, int $id, int $projectId, Request $request): Response
    {
        $developer = $entityManager->getRepository(Developer::class)->find($id);
        $project = $entityManager->getRepository(Project::class)->find($projectId);
        $projects = $entityManager->getRepository(Project::class)->findAll();

        $projectsDeveloper = $developer->getProjects();
        foreach ($projectsDeveloper as $projectDeveloper) {
            $projectDeveloper->removeDeveloper($developer);
        }

        if (!$developer || !$project) {
            return new Response('Разработчик или проект не найден', Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(TransferDeveloperType::class, $developer, [
            'projects' => $projects
        ]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $project = $form->get('projects')->getData();
            $project->addDeveloper($developer);

            $position = $form->get('position')->getData();
            $developer->setPosition($position);

            $entityManager->persist($developer);
            $entityManager->flush();


            return new Response('Разработчик переведен на новый проект');
        }

        return $this->render('developer/transfer.html.twig', [
            'form_transfer_developer' => $form->createView()
        ]);
    }


}