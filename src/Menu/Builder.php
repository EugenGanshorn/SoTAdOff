<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\RequestStack;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Home', ['route' => 'homepage']);

        return $menu;
    }

    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->createItem(
            'root',
            [
                'childrenAttributes' => [
                    'class' => 'navbar-nav mr-auto',
                ],
            ]
        );

        $menu->addChild('Home', ['route' => 'index']);
        $menu->addChild('Admin', ['route' => 'admin']);

        return $menu;
    }
}
