<?php

namespace Reviews\Classes\Data;

class DataMap {
	private array $fields = ['id' => array('type' => 'int')];
	private array $options = ['id' => array('AUTO_INCREMENT', 'PRIMARY_KEY')];

	public static function getFields(): array {
		return $this->fields;
	}

	private string $name;

	public static function getName(): string {
		return $this->name;
	}

	public function setName($name): static {
		$this->name = name;
	}

	public function defineField($name, $type, $options = [], $reference = []): static {
		$this->fields[$name] = ['type' => $type, 'ref' => $reference];
		$this->options[$name] = $options;
	}
} 
