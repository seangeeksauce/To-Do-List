<?php
namespace ToDoList\EventListener;

use Symfony\Component\HttpFoundation\Response;

class ExceptionListener {
    public function onKernelController($event) {
        echo 'On COntroller Actions!';

    }

    public function onKernelView($event) {
        echo 'On View!';
    }
}