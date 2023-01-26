<?php

namespace Reviews\Views;

use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;
use Twig\TemplateWrapper;

abstract class View {

  private string $templates_path = '/templates';
  private Environment $twig;

  private FilesystemLoader $loader;

  public function __construct(array $options = []) {
    $this->loader = new FilesystemLoader($this->templates_path);
    $this->twig = new Environment($this->loader, [
       'cache' => $this->templates_path . '/cache',
      ...$options
    ]);

    return $this;
  }

  public function render(string $template_name, array $params = []): string {
    $template = null;
    if($this->loader->exists($template_name))
    {
      try {
        $template = $this->twig->load($template_name);
      } catch (\Exception $err) {
        echo $err->getMessage();
      }
    }

    return $template->render($params);
  }
}