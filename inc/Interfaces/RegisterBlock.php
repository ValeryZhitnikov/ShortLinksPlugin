<?php

namespace ShortLinks\Interfaces;

interface RegisterBlock {
	public static function getBlockSettings(): array;
	public static function renderCallback(): void;
}