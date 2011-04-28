<?php
namespace project\config;

class Config extends \nano\core\config\Config {
	
	public static $autoloads = array(
		'Twig' => array(
			'include_path' => 'lib/Twig-1.0.0-RC1/lib/Twig/Autoloader.php',
			'class_name' => 'Twig_Autoloader',
			'call_function_name' => 'register',
			'call_function_parameters' => array(),
			'call_type' => 'static',
			'config_class_name' => 'project\lib\twig\Twig',
			'config_call_function_name' => 'configure',
			'config_call_function_parameters' => array(),
			'config_call_type' => 'static'
		),
		'Swift' => array(
			'include_path' => 'lib/Swift/lib/classes/Swift.php',
			'class_name' => 'Swift',
			'call_function_name' => 'registerAutoload',
			'call_function_parameters' => array(),
			'call_type' => 'static',
			'config_class_name' => 'project\lib\swift\Swift',
			'config_call_function_name' => 'configure',
			'config_call_function_parameters' => array(),
			'config_call_type' => 'static'
		)
	);
	
	public static $language = array(
		'ca_ES' => array(
			'name' => 'Catalan',
			'encoding' => 'UTF-8'
		),
		'cs_CZ' => array(
			'name' => 'Czech',
			'encoding' => 'UTF-8'
		),
		'cy_GB' => array(
			'name' => 'Welsh',
			'encoding' => 'UTF-8'
		),
		'da_DK' => array(
			'name' => 'Danish',
			'encoding' => 'UTF-8'
		),
		'de_DE' => array(
			'name' => 'German',
			'encoding' => 'UTF-8'
		),
		'eu_ES' => array(
			'name' => 'Basque',
			'encoding' => 'UTF-8'
		),
		'en_PI' => array(
			'name' => 'English (Pirate)',
			'encoding' => 'UTF-8'
		),
		'en_UD' => array(
			'name' => 'English (Upside Down)',
			'encoding' => 'UTF-8'
		),
		'ck_US' => array(
			'name' => 'Cherokee',
			'encoding' => 'UTF-8'
		),
		'en_US' => array(
			'name' => 'English (US)',
			'encoding' => 'UTF-8'
		),
		'es_LA' => array(
			'name' => 'Spanish',
			'encoding' => 'UTF-8'
		),
		'es_CL' => array(
			'name' => 'Spanish (Chile)',
			'encoding' => 'UTF-8'
		),
		'es_CO' => array(
			'name' => 'Spanish (Colombia)',
			'encoding' => 'UTF-8'
		),
		'es_ES' => array(
			'name' => 'Spanish (Spain)',
			'encoding' => 'UTF-8'
		),
		'es_MX' => array(
			'name' => 'Spanish (Mexico)',
			'encoding' => 'UTF-8'
		),
		'es_VE' => array(
			'name' => 'Spanish (Venezuela)',
			'encoding' => 'UTF-8'
		),
		'fb_FI' => array(
			'name' => 'Finnish (test)',
			'encoding' => 'UTF-8'
		),
		'fi_FI' => array(
			'name' => 'Finnish',
			'encoding' => 'UTF-8'
		),
		'fr_FR' => array(
			'name' => 'French (France)',
			'encoding' => 'UTF-8'
		),
		'gl_ES' => array(
			'name' => 'Galician',
			'encoding' => 'UTF-8'
		),
		'hu_HU' => array(
			'name' => 'Hungarian',
			'encoding' => 'UTF-8'
		),
		'it_IT' => array(
			'name' => 'Italian',
			'encoding' => 'UTF-8'
		),
		'ja_JP' => array(
			'name' => 'Japanese',
			'encoding' => 'UTF-8'
		),
		'ko_KR' => array(
			'name' => 'Korean',
			'encoding' => 'UTF-8'
		),
		'nb_NO' => array(
			'name' => 'Norwegian (bokmal)',
			'encoding' => 'UTF-8'
		),
		'nn_NO' => array(
			'name' => 'Norwegian (nynorsk)',
			'encoding' => 'UTF-8'
		),
		'nl_NL' => array(
			'name' => 'Dutch',
			'encoding' => 'UTF-8'
		),
		'pl_PL' => array(
			'name' => 'Polish',
			'encoding' => 'UTF-8'
		),
		'pt_BR' => array(
			'name' => 'Portuguese (Brazil)',
			'encoding' => 'UTF-8'
		),
		'pt_PT' => array(
			'name' => 'Portuguese (Portugal)',
			'encoding' => 'UTF-8'
		),
		'ro_RO' => array(
			'name' => 'Romanian',
			'encoding' => 'UTF-8'
		),
		'ru_RU' => array(
			'name' => 'Russian',
			'encoding' => 'UTF-8'
		),
		'sk_SK' => array(
			'name' => 'Slovak',
			'encoding' => 'UTF-8'
		),
		'sl_SI' => array(
			'name' => 'Slovenian',
			'encoding' => 'UTF-8'
		),
		'sv_SE' => array(
			'name' => 'Swedish',
			'encoding' => 'UTF-8'
		),
		'th_TH' => array(
			'name' => 'Thai',
			'encoding' => 'UTF-8'
		),
		'tr_TR' => array(
			'name' => 'Turkish',
			'encoding' => 'UTF-8'
		),
		'ku_TR' => array(
			'name' => 'Kurdish',
			'encoding' => 'UTF-8'
		),
		'zh_CN' => array(
			'name' => 'Simplified Chinese (China)',
			'encoding' => 'UTF-8'
		),
		'zh_HK' => array(
			'name' => 'Traditional Chinese (Hong Kong)',
			'encoding' => 'UTF-8'
		),
		'zh_TW' => array(
			'name' => 'Traditional Chinese (Taiwan)',
			'encoding' => 'UTF-8'
		),
		'fb_LT' => array(
			'name' => 'Leet Speak',
			'encoding' => 'UTF-8'
		),
		'af_ZA' => array(
			'name' => 'Afrikaans',
			'encoding' => 'UTF-8'
		),
		'sq_AL' => array(
			'name' => 'Albanian',
			'encoding' => 'UTF-8'
		),
		'hy_AM' => array(
			'name' => 'Armenian',
			'encoding' => 'UTF-8'
		),
		'az_AZ' => array(
			'name' => 'Azeri',
			'encoding' => 'UTF-8'
		),
		'be_BY' => array(
			'name' => 'Belarusian',
			'encoding' => 'UTF-8'
		),
		'bn_IN' => array(
			'name' => 'Bengali',
			'encoding' => 'UTF-8'
		),
		'bs_BA' => array(
			'name' => 'Bosnian',
			'encoding' => 'UTF-8'
		),
		'bg_BG' => array(
			'name' => 'Bulgarian',
			'encoding' => 'UTF-8'
		),
		'hr_HR' => array(
			'name' => 'Croatian',
			'encoding' => 'UTF-8'
		),
		'nl_BE' => array(
			'name' => 'Dutch (België)',
			'encoding' => 'UTF-8'
		),
		'en_GB' => array(
			'name' => 'English (UK)',
			'encoding' => 'UTF-8'
		),
		'eo_EO' => array(
			'name' => 'Esperanto',
			'encoding' => 'UTF-8'
		),
		'et_EE' => array(
			'name' => 'Estonian',
			'encoding' => 'UTF-8'
		),
		'fo_FO' => array(
			'name' => 'Faroese',
			'encoding' => 'UTF-8'
		),
		'fr_CA' => array(
			'name' => 'French (Canada)',
			'encoding' => 'UTF-8'
		),
		'ka_GE' => array(
			'name' => 'Georgian',
			'encoding' => 'UTF-8'
		),
		'el_GR' => array(
			'name' => 'Greek',
			'encoding' => 'UTF-8'
		),
		'gu_IN' => array(
			'name' => 'Gujarati',
			'encoding' => 'UTF-8'
		),
		'hi_IN' => array(
			'name' => 'Hindi',
			'encoding' => 'UTF-8'
		),
		'is_IS' => array(
			'name' => 'Icelandic',
			'encoding' => 'UTF-8'
		),
		'id_ID' => array(
			'name' => 'Indonesian',
			'encoding' => 'UTF-8'
		),
		'ga_IE' => array(
			'name' => 'Irish',
			'encoding' => 'UTF-8'
		),
		'jv_ID' => array(
			'name' => 'Javanese',
			'encoding' => 'UTF-8'
		),
		'kn_IN' => array(
			'name' => 'Kannada',
			'encoding' => 'UTF-8'
		),
		'kk_KZ' => array(
			'name' => 'Kazakh',
			'encoding' => 'UTF-8'
		),
		'la_VA' => array(
			'name' => 'Latin',
			'encoding' => 'UTF-8'
		),
		'lv_LV' => array(
			'name' => 'Latvian',
			'encoding' => 'UTF-8'
		),
		'li_NL' => array(
			'name' => 'Limburgish',
			'encoding' => 'UTF-8'
		),
		'lt_LT' => array(
			'name' => 'Lithuanian',
			'encoding' => 'UTF-8'
		),
		'mk_MK' => array(
			'name' => 'Macedonian',
			'encoding' => 'UTF-8'
		),
		'mg_MG' => array(
			'name' => 'Malagasy',
			'encoding' => 'UTF-8'
		),
		'ms_MY' => array(
			'name' => 'Malay',
			'encoding' => 'UTF-8'
		),
		'mt_MT' => array(
			'name' => 'Maltese',
			'encoding' => 'UTF-8'
		),
		'mr_IN' => array(
			'name' => 'Marathi',
			'encoding' => 'UTF-8'
		),
		'mn_MN' => array(
			'name' => 'Mongolian',
			'encoding' => 'UTF-8'
		),
		'ne_NP' => array(
			'name' => 'Nepali',
			'encoding' => 'UTF-8'
		),
		'pa_IN' => array(
			'name' => 'Punjabi',
			'encoding' => 'UTF-8'
		),
		'rm_CH' => array(
			'name' => 'Romansh',
			'encoding' => 'UTF-8'
		),
		'sa_IN' => array(
			'name' => 'Sanskrit',
			'encoding' => 'UTF-8'
		),
		'sr_RS' => array(
			'name' => 'Serbian',
			'encoding' => 'UTF-8'
		),
		'so_SO' => array(
			'name' => 'Somali',
			'encoding' => 'UTF-8'
		),
		'sw_KE' => array(
			'name' => 'Swahili',
			'encoding' => 'UTF-8'
		),
		'tl_PH' => array(
			'name' => 'Filipino',
			'encoding' => 'UTF-8'
		),
		'ta_IN' => array(
			'name' => 'Tamil',
			'encoding' => 'UTF-8'
		),
		'tt_RU' => array(
			'name' => 'Tatar',
			'encoding' => 'UTF-8'
		),
		'te_IN' => array(
			'name' => 'Telugu',
			'encoding' => 'UTF-8'
		),
		'ml_IN' => array(
			'name' => 'Malayalam',
			'encoding' => 'UTF-8'
		),
		'uk_UA' => array(
			'name' => 'Ukrainian',
			'encoding' => 'UTF-8'
		),
		'uz_UZ' => array(
			'name' => 'Uzbek',
			'encoding' => 'UTF-8'
		),
		'vi_VN' => array(
			'name' => 'Vietnamese',
			'encoding' => 'UTF-8'
		),
		'xh_ZA' => array(
			'name' => 'Xhosa',
			'encoding' => 'UTF-8'
		),
		'zu_ZA' => array(
			'name' => 'Zulu',
			'encoding' => 'UTF-8'
		),
		'km_KH' => array(
			'name' => 'Khmer',
			'encoding' => 'UTF-8'
		),
		'tg_TJ' => array(
			'name' => 'Tajik',
			'encoding' => 'UTF-8'
		),
		'ar_AR' => array(
			'name' => 'Arabic',
			'encoding' => 'UTF-8'
		),
		'he_IL' => array(
			'name' => 'Hebrew',
			'encoding' => 'UTF-8'
		),
		'ur_PK' => array(
			'name' => 'Urdu',
			'encoding' => 'UTF-8'
		),
		'fa_IR' => array(
			'name' => 'Persian',
			'encoding' => 'UTF-8'
		),
		'sy_SY' => array(
			'name' => 'Syriac',
			'encoding' => 'UTF-8'
		),
		'yi_DE' => array(
			'name' => 'Yiddish',
			'encoding' => 'UTF-8'
		),
		'gn_PY' => array(
			'name' => 'Guaraní',
			'encoding' => 'UTF-8'
		),
		'qu_PE' => array(
			'name' => 'Quechua',
			'encoding' => 'UTF-8'
		),
		'ay_BO' => array(
			'name' => 'Aymara',
			'encoding' => 'UTF-8'
		),
		'se_NO' => array(
			'name' => 'Northern Sámi',
			'encoding' => 'UTF-8'
		),
		'ps_AF' => array(
			'name' => 'Pashto',
			'encoding' => 'UTF-8'
		),
		'tl_ST' => array(
			'name' => 'Klingon',
			'encoding' => 'UTF-8'
		)
	);
	
}
