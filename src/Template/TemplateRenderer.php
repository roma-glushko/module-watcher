<?php

declare(strict_types=1);

namespace ModuleWatcher\Template;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extra\CssInliner\CssInlinerExtension;
use Twig\Extra\Inky\InkyExtension;
use Twig\Loader\FilesystemLoader;

/**
 *
 */
class TemplateRenderer
{
    /**
     * @var Environment
     */
    private $twigRenderer;

    public function __construct()
    {
        $loader = new FilesystemLoader('templates');

        $loader->addPath('templates/styles', 'styles');

        $this->twigRenderer = new Environment(
            $loader,
            [
                'cache' => 'tmp/twig-cache/', // todo: take from configs
            ]
        );

        $this->twigRenderer->addExtension(new InkyExtension());
        $this->twigRenderer->addExtension(new CssInlinerExtension());
    }

    /**
     * @param string $templateName
     * @param array $context
     *
     * @return string
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(string $templateName, array $context = []): string
    {
        return $this->twigRenderer->render(
            $templateName,
            $context
        );
    }
}