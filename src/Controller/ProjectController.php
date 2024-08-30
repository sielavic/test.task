<?php


namespace App\Controller;

use App\Entity\Developer;
use App\Entity\Project;


use App\Form\CreateProjectType;
use App\Form\TransferDeveloperType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
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


    public function index($id, Request $request, ManagerRegistry $doctrine): Response
    {

        $projectRepo = $doctrine->getRepository(Project::Class);

        /** @var Project $project */
        $project = $projectRepo->find($id);

        if (!$project) {
            return new Response('Проект не найден', Response::HTTP_NOT_FOUND);
        }
        $description = $project->getTitle();
        $developers = $project->getDevelopers();


        return $this->render('project/index.html.twig', [
            'project' => $project,
            'description' => $description,
            'users' => $developers,
        ]);
    }


    public function developerList(ManagerRegistry $doctrine)
    {
        $developers = $doctrine->getRepository(Developer::class)->findAll();
        return $this->render('developer/developer_list.html.twig', [

            'users' => $developers,
        ]);
    }

    public function deleteMan($id, ManagerRegistry $doctrine, $projectId)
    {


        $user = $doctrine->getRepository(Developer::class)->find($id);

        $project = $doctrine->getRepository(Project::class)->find($projectId);

        if (!$project || $user) {
            throw $this->createNotFoundException('Пользователь или проект не найден');
        }
        $description = $project->getTitle();
        $developers = $project->getDevelopers();

        $em = $doctrine->getManager();
        $user->removeProject($project);
        $em->persist($user);
        $em->flush();

        return $this->render('project/index.html.twig', [
            'project' => $project,
            'description' => $description,
            'users' => $developers,
        ]);
    }

    public function createProject(EntityManagerInterface $entityManager, Request $request): Response
    {

        $project = new Project();
        $developers = $entityManager->getRepository(Developer::class)->findAll();

        $form = $this->createForm(CreateProjectType::class, $project, [
            'developers' => $developers
        ]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $title = $form->get('title')->getData();
            $project->setTitle($title);

            $customer = $form->get('customer')->getData();
            $project->setCustomer($customer);

            $developer = $form->get('developers')->getData();
            $project->addDeveloper($developer);

            $entityManager->persist($project);
            $entityManager->flush();


            return new Response('Проект создан');
        }

        return $this->render('project/create_project.html.twig', [
            'form_create_project' => $form->createView()
        ]);
    }


    public function closeProjectForm(EntityManagerInterface $entityManager, int $id): Response
    {
        $project = $entityManager->getRepository(Project::class)->find($id);

        if (!$project) {
            return new Response('Проект не найден', Response::HTTP_NOT_FOUND);
        }

        return $this->render('project/close_project.html.twig', [
            'project' => $project,
        ]);
    }

    public function closeProject(EntityManagerInterface $entityManager, int $id): Response
    {
        $project = $entityManager->getRepository(Project::class)->find($id);

        if (!$project) {
            return new Response('Проект не найден', Response::HTTP_NOT_FOUND);
        }


        $entityManager->remove($project);
        $entityManager->flush();

        return new Response('Проект закрыт');
    }

}