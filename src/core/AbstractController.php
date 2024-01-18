<?php

declare(strict_types=1);

namespace APP\Core;

use Twig\Environment;
use Twig\Extra\Intl\IntlExtension;

/**
 * Clase abstracta que nos permite extender de ella para crear cualquier controller en nuestra aplicación.
 * Es nuestro controlador padre.
 */
abstract class AbstractController
{

    private Environment $twig;

    public function __construct()
    {
        //Estas dos líneas nos indica la documentación de Twig que debemos añadirlas para poder usarlo en cada controller
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . "/../templates");
        $this->twig = new \Twig\Environment($loader);
        $this->twig->addExtension(new IntlExtension());
        //Esta línea nos sirve para poder declarar una variable global desde PHP a TWIG
        //En este caso pasamos el nombre del servidor, pero podría ser cualquier varíable de PHP. Genéralmente se usa
        //para las cookies y datos de sesión de usuarios.
        $this->twig->addGlobal('server_name', $_SERVER['SERVER_NAME']);
    }

    /**
     * Método que simplifica el renderizado de twig que podemos usar en cualquier controller que extienda esta clase
     * abstracta. Gracias a este método reutilizamos el código en cada uno de los controladores.
     * @param string $template
     * @param array $params
     * @return void
     */
    public function render(string $template, array $params): void
    {
        $template = $this->twig->load($template);
        echo $template->render($params);
    }
}
