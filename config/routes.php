<?php

use App\Controller\DeveloperController;
use App\Controller\ProjectController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {

    $routes->add('add_project_form', '/developer/{id}/add_project/{projectId}')
        ->controller([DeveloperController::class, 'addProjectForm'])->methods(['GET']);

    $routes->add('add_project_to_developer', '/developer/{id}/add_project/{projectId}')
        ->controller([DeveloperController::class, 'addProjectToDeveloper'])->methods(['POST']);

    $routes->add('detail_project', '/project/{id}')
        ->controller([ProjectController::class, 'index']);
    $routes->add('delete_man_project', '/delete-man-project/{id}/delete_project{projectId}')
        ->controller([ProjectController::class, 'deleteMan']);

    $routes->add('developer_list', '/developer_list')
        ->controller([ProjectController::class, 'developerList']);


};