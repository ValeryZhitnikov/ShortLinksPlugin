<?php

namespace ShortLinks\Interfaces;

interface RegisterFields {
	public static function addMetaBox(): void;
	public static function renderMetaBox($post): void;
	public static function saveMetaBox($post_id): void;
}