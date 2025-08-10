<?php

namespace ShortLinks\Entities\ShortLink;

abstract class Constants {

	public const string ENTITY_LABEL = 'shortlink';
	public const string ENTITY_NAME = 'Short Links';
	public const string SINGULAR_NAME = 'Short Link';
	public const string MENU_ICON = 'dashicons-admin-links';
	public const string TARGET_URL_META_FIELD = '_target_url';
	public const string CLOSE_META_FIELD = '_close_link';
	public const string CLOSE_DATE_META_FIELD = '_close_link_date';
	public const string SHOW_LINK_SHORTCODE_NAME = 'shortlink_form';
}