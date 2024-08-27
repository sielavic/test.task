<?php

use App\Controller\DeveloperController;
use App\Controller\ProjectController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {

    $routes->add('add_project_form', '/developer/{id}/add_project/{projectId}')
        ->controller([DeveloperController::class, 'addProjectForm'])->methods(['GET']);

    $routes->add('add_project_to_developer', '/developer/{id}/add_project/{projectId}')
        ->controller([DeveloperController::class, 'addProjectToDeveloper'])->methods(['POST']);

    $routes->add('detail_project', '/project/{id}/index')
        ->controller([ProjectController::class, 'index']);
    $routes->add('delete_man_project', '/delete-man-project/{id}/delete_project{projectId}')
        ->controller([ProjectController::class, 'deleteMan']);

    $routes->add('developer_list', '/developer_list')
        ->controller([ProjectController::class, 'developerList']);

    $routes->add('hire_developer', '/developer/hire/{projectId}')
        ->controller([DeveloperController::class, 'hireDeveloper'])->methods(['GET', 'POST']);

    $routes->add('fire_developer_form', '/developer/{id}/fireform')
        ->controller([DeveloperController::class, 'fireForm'])->methods(['GET']);

    $routes->add('fire_developer', '/developer/{id}/fire')
        ->controller([DeveloperController::class, 'fireDeveloper'])->methods(['GET','POST']);


    $routes->add('transfer_developer', '/developer/{id}/transfer/{projectId}')
        ->controller([DeveloperController::class, 'transferDeveloper'])->methods(['GET', 'POST']);


    $routes->add('create_project', '/project/create')
        ->controller([ProjectController::class, 'createProject'])->methods(['GET', 'POST']);

    $routes->add('close_project_form', '/project/{id}/closeform')
        ->controller([ProjectController::class, 'closeProjectForm'])->methods(['GET', 'POST']);

    $routes->add('close_project', '/project/{id}/close')
        ->controller([ProjectController::class, 'closeProject'])->methods(['GET', 'POST']);
};