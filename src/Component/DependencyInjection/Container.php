<?php

namespace Component\DependencyInjection;

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
      //autowire
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

    // handle customized
    if (is_callable($serviceDefinition)) {
      return \call_user_func($serviceDefinition, $this);
    }

    // handle alias
    if (is_string($serviceDefinition)) {
      return $this->get($serviceDefinition);
    }
  }
}
