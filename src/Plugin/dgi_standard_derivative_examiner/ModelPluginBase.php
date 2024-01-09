<?php

namespace Drupal\dgi_standard_derivative_examiner\Plugin\dgi_standard_derivative_examiner;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\dgi_standard_derivative_examiner\ModelInterface;
use Drupal\dgi_standard_derivative_examiner\TargetPluginManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base model plugin.
 */
abstract class ModelPluginBase extends PluginBase implements ContainerFactoryPluginInterface, ModelInterface {

  /**
   * Target plugin manager service.
   *
   * @var \Drupal\dgi_standard_derivative_examiner\TargetPluginManagerInterface
   */
  protected TargetPluginManagerInterface $targetPluginManager;

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = new static($configuration, $plugin_id, $plugin_definition);

    $instance->targetPluginManager = $container->get("plugin.manager.dgi_standard_derivative_examiner.target.{$plugin_id}");

    return $instance;
  }

  /**
   * {@inheritDoc}
   */
  public function getModelUri() : string {
    return $this->pluginDefinition['uri'];
  }

  /**
   * Get targets for the given model.
   *
   * @return \Drupal\dgi_standard_derivative_examiner\TargetInterface[]
   *   An array of targets.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   *   If the plugin could not be created.
   */
  public function getDerivativeTargets() : array {
    $targets = [];

    foreach (array_keys($this->targetPluginManager->getDefinitions()) as $id) {
      $targets[$id] = $this->targetPluginManager->createInstance($id);
    }

    return $targets;
  }

}
