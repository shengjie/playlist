<?php

namespace Component\DependencyInjection;

/**
 * an simple dependency injection container
 */
class Container {

  private $settings;

  private $services;

  public function __construct(array $definitions, array $settings) {
    $this->definitions = $definitions;
    $this->settings = $settings;
  }

  public function get(string $serviceId) {
    if (!isset($this->services[$serviceId])) {
      $this->services[$serviceId] = $this->build($serviceId);
    }

    return $this->services[$serviceId];
  }

  public function getSetting(string $setting) {
    return $this->settings[$setting];
  }

  private function build(string $serviceId) {
    if (!isset($this->definitions[$serviceId])) {
      throw new \LogicException('Service '.$serviceId.' is not defined!');
    }

    $serviceDefinition = $this->definitions[$serviceId];
    // handle autowire
    if (true === $serviceDefinition) {
      // autowire
      // todo : following section can uses apcu cache to increase speed as reflection is slow
      $refClass = new \ReflectionClass($serviceId);
      $arguments = [];
      $refCtor = $refClass->getConstructor();
      if (null !== $refCtor) {
        foreach($refCtor->getParameters() as $parameter) {
          $arguments[] = (string) $parameter->getType();
        }
      }

      return new $serviceId(...array_map([$this, 'get'], $arguments));
    }

    // handle customized service generation
    if (is_callable($serviceDefinition)) {
      return \call_user_func($serviceDefinition, $this);
    }

    // handle configured service generation
    if (is_array($serviceDefinition)) {
      $arguments = $serviceDefinition['arguments'];
      return new $serviceId(...$arguments);
    }

    // handle alias
    if (is_string($serviceDefinition)) {
      return $this->get($serviceDefinition);
    }
  }
}
