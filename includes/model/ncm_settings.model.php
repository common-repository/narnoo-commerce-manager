<?php

/*
* This is a settings model. Manage all admin setting
*/

if( !class_exists ( 'NCM_DB_settings' ) ) {



class NCM_DB_settings{







	function __construct(){







	}







    function ncm_get_settings_tab() {



        return array(



            'general'  => __('General', NCM_txt_domain),



            /* 'tax'      => __('Tax', NCM_txt_domain), */



            'checkout' => __('Checkout', NCM_txt_domain),



            'emails'   => __('Emails', NCM_txt_domain),



        );



    }







    function ncm_default_general_option() {



        $ncm_default_option = array(



            'ncm_narnoo_api_mode' => '',



            'ncm_narnoo_bookable_products' => '',



            'ncm_narnoo_display_calendar' => '',
            


            'ncm_narnoo_availability_modal_window' => '',



            'ncm_setting_store_address' => '',



            'ncm_setting_store_address_2' => '',



            'ncm_setting_store_city' => '',



            'ncm_setting_default_country' => '',



            'ncm_setting_store_postcode' => '',



            'ncm_setting_allowed_countries' => 'all',



            'ncm_setting_all_except_countries' => '',



            'ncm_setting_specific_allowed_countries' => '',



            /* 'ncm_setting_ship_to_countries' => '',



            'ncm_setting_specific_ship_to_countries' => '', */



            'ncm_setting_default_customer_address' => 'geolocation',



            /* 'ncm_setting_calc_taxes' => '', */



            'ncm_setting_demo_store' => '',



            'ncm_setting_notice_islive_false' => __('Please select your preferred travel date(s) from the calendar below. We will contact you as soon as possible to confirm your booking. If your booking is for travel within 48 hours or is dependent on other travel plans, please call or contact us to confirm availability before proceeding.', NCM_txt_domain),



            'ncm_setting_demo_store_notice' => __('This is a demo store for testing purposes-no orders shall be fulfilled.', NCM_txt_domain),



            'ncm_setting_currency' => 'USD',



            'ncm_setting_currency_pos' => 'left',



            'ncm_setting_price_thousand_sep' => ',',



            'ncm_setting_price_decimal_sep' => '.',



            'ncm_setting_price_num_decimals' => '2',



        );



        return $ncm_default_option;



    }    







    function ncm_currency() {



        $currency = array(



            'AED' => __( 'United Arab Emirates dirham', NCM_txt_domain ),



            'AFN' => __( 'Afghan afghani', NCM_txt_domain ),



            'ALL' => __( 'Albanian lek', NCM_txt_domain ),



            'AMD' => __( 'Armenian dram', NCM_txt_domain ),



            'ANG' => __( 'Netherlands Antillean guilder', NCM_txt_domain ),



            'AOA' => __( 'Angolan kwanza', NCM_txt_domain ),



            'ARS' => __( 'Argentine peso', NCM_txt_domain ),



            'AUD' => __( 'Australian dollar', NCM_txt_domain ),



            'AWG' => __( 'Aruban florin', NCM_txt_domain ),



            'AZN' => __( 'Azerbaijani manat', NCM_txt_domain ),



            'BAM' => __( 'Bosnia and Herzegovina convertible mark', NCM_txt_domain ),



            'BBD' => __( 'Barbadian dollar', NCM_txt_domain ),



            'BDT' => __( 'Bangladeshi taka', NCM_txt_domain ),



            'BGN' => __( 'Bulgarian lev', NCM_txt_domain ),



            'BHD' => __( 'Bahraini dinar', NCM_txt_domain ),



            'BIF' => __( 'Burundian franc', NCM_txt_domain ),



            'BMD' => __( 'Bermudian dollar', NCM_txt_domain ),



            'BND' => __( 'Brunei dollar', NCM_txt_domain ),



            'BOB' => __( 'Bolivian boliviano', NCM_txt_domain ),



            'BRL' => __( 'Brazilian real', NCM_txt_domain ),



            'BSD' => __( 'Bahamian dollar', NCM_txt_domain ),



            'BTC' => __( 'Bitcoin', NCM_txt_domain ),



            'BTN' => __( 'Bhutanese ngultrum', NCM_txt_domain ),



            'BWP' => __( 'Botswana pula', NCM_txt_domain ),



            'BYR' => __( 'Belarusian ruble (old)', NCM_txt_domain ),



            'BYN' => __( 'Belarusian ruble', NCM_txt_domain ),



            'BZD' => __( 'Belize dollar', NCM_txt_domain ),



            'CAD' => __( 'Canadian dollar', NCM_txt_domain ),



            'CDF' => __( 'Congolese franc', NCM_txt_domain ),



            'CHF' => __( 'Swiss franc', NCM_txt_domain ),



            'CLP' => __( 'Chilean peso', NCM_txt_domain ),



            'CNY' => __( 'Chinese yuan', NCM_txt_domain ),



            'COP' => __( 'Colombian peso', NCM_txt_domain ),



            'CRC' => __( 'Costa Rican col&oacute;n', NCM_txt_domain ),



            'CUC' => __( 'Cuban convertible peso', NCM_txt_domain ),



            'CUP' => __( 'Cuban peso', NCM_txt_domain ),



            'CVE' => __( 'Cape Verdean escudo', NCM_txt_domain ),



            'CZK' => __( 'Czech koruna', NCM_txt_domain ),



            'DJF' => __( 'Djiboutian franc', NCM_txt_domain ),



            'DKK' => __( 'Danish krone', NCM_txt_domain ),



            'DOP' => __( 'Dominican peso', NCM_txt_domain ),



            'DZD' => __( 'Algerian dinar', NCM_txt_domain ),



            'EGP' => __( 'Egyptian pound', NCM_txt_domain ),



            'ERN' => __( 'Eritrean nakfa', NCM_txt_domain ),



            'ETB' => __( 'Ethiopian birr', NCM_txt_domain ),



            'EUR' => __( 'Euro', NCM_txt_domain ),



            'FJD' => __( 'Fijian dollar', NCM_txt_domain ),



            'FKP' => __( 'Falkland Islands pound', NCM_txt_domain ),



            'GBP' => __( 'Pound sterling', NCM_txt_domain ),



            'GEL' => __( 'Georgian lari', NCM_txt_domain ),



            'GGP' => __( 'Guernsey pound', NCM_txt_domain ),



            'GHS' => __( 'Ghana cedi', NCM_txt_domain ),



            'GIP' => __( 'Gibraltar pound', NCM_txt_domain ),



            'GMD' => __( 'Gambian dalasi', NCM_txt_domain ),



            'GNF' => __( 'Guinean franc', NCM_txt_domain ),



            'GTQ' => __( 'Guatemalan quetzal', NCM_txt_domain ),



            'GYD' => __( 'Guyanese dollar', NCM_txt_domain ),



            'HKD' => __( 'Hong Kong dollar', NCM_txt_domain ),



            'HNL' => __( 'Honduran lempira', NCM_txt_domain ),



            'HRK' => __( 'Croatian kuna', NCM_txt_domain ),



            'HTG' => __( 'Haitian gourde', NCM_txt_domain ),



            'HUF' => __( 'Hungarian forint', NCM_txt_domain ),



            'IDR' => __( 'Indonesian rupiah', NCM_txt_domain ),



            'ILS' => __( 'Israeli new shekel', NCM_txt_domain ),



            'IMP' => __( 'Manx pound', NCM_txt_domain ),



            'INR' => __( 'Indian rupee', NCM_txt_domain ),



            'IQD' => __( 'Iraqi dinar', NCM_txt_domain ),



            'IRR' => __( 'Iranian rial', NCM_txt_domain ),



            'IRT' => __( 'Iranian toman', NCM_txt_domain ),



            'ISK' => __( 'Icelandic kr&oacute;na', NCM_txt_domain ),



            'JEP' => __( 'Jersey pound', NCM_txt_domain ),



            'JMD' => __( 'Jamaican dollar', NCM_txt_domain ),



            'JOD' => __( 'Jordanian dinar', NCM_txt_domain ),



            'JPY' => __( 'Japanese yen', NCM_txt_domain ),



            'KES' => __( 'Kenyan shilling', NCM_txt_domain ),



            'KGS' => __( 'Kyrgyzstani som', NCM_txt_domain ),



            'KHR' => __( 'Cambodian riel', NCM_txt_domain ),



            'KMF' => __( 'Comorian franc', NCM_txt_domain ),



            'KPW' => __( 'North Korean won', NCM_txt_domain ),



            'KRW' => __( 'South Korean won', NCM_txt_domain ),



            'KWD' => __( 'Kuwaiti dinar', NCM_txt_domain ),



            'KYD' => __( 'Cayman Islands dollar', NCM_txt_domain ),



            'KZT' => __( 'Kazakhstani tenge', NCM_txt_domain ),



            'LAK' => __( 'Lao kip', NCM_txt_domain ),



            'LBP' => __( 'Lebanese pound', NCM_txt_domain ),



            'LKR' => __( 'Sri Lankan rupee', NCM_txt_domain ),



            'LRD' => __( 'Liberian dollar', NCM_txt_domain ),



            'LSL' => __( 'Lesotho loti', NCM_txt_domain ),



            'LYD' => __( 'Libyan dinar', NCM_txt_domain ),



            'MAD' => __( 'Moroccan dirham', NCM_txt_domain ),



            'MDL' => __( 'Moldovan leu', NCM_txt_domain ),



            'MGA' => __( 'Malagasy ariary', NCM_txt_domain ),



            'MKD' => __( 'Macedonian denar', NCM_txt_domain ),



            'MMK' => __( 'Burmese kyat', NCM_txt_domain ),



            'MNT' => __( 'Mongolian t&ouml;gr&ouml;g', NCM_txt_domain ),



            'MOP' => __( 'Macanese pataca', NCM_txt_domain ),



            'MRO' => __( 'Mauritanian ouguiya', NCM_txt_domain ),



            'MUR' => __( 'Mauritian rupee', NCM_txt_domain ),



            'MVR' => __( 'Maldivian rufiyaa', NCM_txt_domain ),



            'MWK' => __( 'Malawian kwacha', NCM_txt_domain ),



            'MXN' => __( 'Mexican peso', NCM_txt_domain ),



            'MYR' => __( 'Malaysian ringgit', NCM_txt_domain ),



            'MZN' => __( 'Mozambican metical', NCM_txt_domain ),



            'NAD' => __( 'Namibian dollar', NCM_txt_domain ),



            'NGN' => __( 'Nigerian naira', NCM_txt_domain ),



            'NIO' => __( 'Nicaraguan c&oacute;rdoba', NCM_txt_domain ),



            'NOK' => __( 'Norwegian krone', NCM_txt_domain ),



            'NPR' => __( 'Nepalese rupee', NCM_txt_domain ),



            'NZD' => __( 'New Zealand dollar', NCM_txt_domain ),



            'OMR' => __( 'Omani rial', NCM_txt_domain ),



            'PAB' => __( 'Panamanian balboa', NCM_txt_domain ),



            'PEN' => __( 'Peruvian nuevo sol', NCM_txt_domain ),



            'PGK' => __( 'Papua New Guinean kina', NCM_txt_domain ),



            'PHP' => __( 'Philippine peso', NCM_txt_domain ),



            'PKR' => __( 'Pakistani rupee', NCM_txt_domain ),



            'PLN' => __( 'Polish z&#x142;oty', NCM_txt_domain ),



            'PRB' => __( 'Transnistrian ruble', NCM_txt_domain ),



            'PYG' => __( 'Paraguayan guaran&iacute;', NCM_txt_domain ),



            'QAR' => __( 'Qatari riyal', NCM_txt_domain ),



            'RON' => __( 'Romanian leu', NCM_txt_domain ),



            'RSD' => __( 'Serbian dinar', NCM_txt_domain ),



            'RUB' => __( 'Russian ruble', NCM_txt_domain ),



            'RWF' => __( 'Rwandan franc', NCM_txt_domain ),



            'SAR' => __( 'Saudi riyal', NCM_txt_domain ),



            'SBD' => __( 'Solomon Islands dollar', NCM_txt_domain ),



            'SCR' => __( 'Seychellois rupee', NCM_txt_domain ),



            'SDG' => __( 'Sudanese pound', NCM_txt_domain ),



            'SEK' => __( 'Swedish krona', NCM_txt_domain ),



            'SGD' => __( 'Singapore dollar', NCM_txt_domain ),



            'SHP' => __( 'Saint Helena pound', NCM_txt_domain ),



            'SLL' => __( 'Sierra Leonean leone', NCM_txt_domain ),



            'SOS' => __( 'Somali shilling', NCM_txt_domain ),



            'SRD' => __( 'Surinamese dollar', NCM_txt_domain ),



            'SSP' => __( 'South Sudanese pound', NCM_txt_domain ),



            'STD' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', NCM_txt_domain ),



            'SYP' => __( 'Syrian pound', NCM_txt_domain ),



            'SZL' => __( 'Swazi lilangeni', NCM_txt_domain ),



            'THB' => __( 'Thai baht', NCM_txt_domain ),



            'TJS' => __( 'Tajikistani somoni', NCM_txt_domain ),



            'TMT' => __( 'Turkmenistan manat', NCM_txt_domain ),



            'TND' => __( 'Tunisian dinar', NCM_txt_domain ),



            'TOP' => __( 'Tongan pa&#x2bb;anga', NCM_txt_domain ),



            'TRY' => __( 'Turkish lira', NCM_txt_domain ),



            'TTD' => __( 'Trinidad and Tobago dollar', NCM_txt_domain ),



            'TWD' => __( 'New Taiwan dollar', NCM_txt_domain ),



            'TZS' => __( 'Tanzanian shilling', NCM_txt_domain ),



            'UAH' => __( 'Ukrainian hryvnia', NCM_txt_domain ),



            'UGX' => __( 'Ugandan shilling', NCM_txt_domain ),



            'USD' => __( 'United States dollar', NCM_txt_domain ),



            'UYU' => __( 'Uruguayan peso', NCM_txt_domain ),



            'UZS' => __( 'Uzbekistani som', NCM_txt_domain ),



            'VEF' => __( 'Venezuelan bol&iacute;var', NCM_txt_domain ),



            'VND' => __( 'Vietnamese &#x111;&#x1ed3;ng', NCM_txt_domain ),



            'VUV' => __( 'Vanuatu vatu', NCM_txt_domain ),



            'WST' => __( 'Samoan t&#x101;l&#x101;', NCM_txt_domain ),



            'XAF' => __( 'Central African CFA franc', NCM_txt_domain ),



            'XCD' => __( 'East Caribbean dollar', NCM_txt_domain ),



            'XOF' => __( 'West African CFA franc', NCM_txt_domain ),



            'XPF' => __( 'CFP franc', NCM_txt_domain ),



            'YER' => __( 'Yemeni rial', NCM_txt_domain ),



            'ZAR' => __( 'South African rand', NCM_txt_domain ),



            'ZMW' => __( 'Zambian kwacha', NCM_txt_domain ),



        );



        return array_unique($currency);



    }







    function ncm_currency_symbol() {



        $symbols = array(



            'AED' => '&#x62f;.&#x625;',



            'AFN' => '&#x60b;',



            'ALL' => 'L',



            'AMD' => 'AMD',



            'ANG' => '&fnof;',



            'AOA' => 'Kz',



            'ARS' => '&#36;',



            'AUD' => '&#36;',



            'AWG' => 'Afl.',



            'AZN' => 'AZN',



            'BAM' => 'KM',



            'BBD' => '&#36;',



            'BDT' => '&#2547;&nbsp;',



            'BGN' => '&#1083;&#1074;.',



            'BHD' => '.&#x62f;.&#x628;',



            'BIF' => 'Fr',



            'BMD' => '&#36;',



            'BND' => '&#36;',



            'BOB' => 'Bs.',



            'BRL' => '&#82;&#36;',



            'BSD' => '&#36;',



            'BTC' => '&#3647;',



            'BTN' => 'Nu.',



            'BWP' => 'P',



            'BYR' => 'Br',



            'BYN' => 'Br',



            'BZD' => '&#36;',



            'CAD' => '&#36;',



            'CDF' => 'Fr',



            'CHF' => '&#67;&#72;&#70;',



            'CLP' => '&#36;',



            'CNY' => '&yen;',



            'COP' => '&#36;',



            'CRC' => '&#x20a1;',



            'CUC' => '&#36;',



            'CUP' => '&#36;',



            'CVE' => '&#36;',



            'CZK' => '&#75;&#269;',



            'DJF' => 'Fr',



            'DKK' => 'DKK',



            'DOP' => 'RD&#36;',



            'DZD' => '&#x62f;.&#x62c;',



            'EGP' => 'EGP',



            'ERN' => 'Nfk',



            'ETB' => 'Br',



            'EUR' => '&euro;',



            'FJD' => '&#36;',



            'FKP' => '&pound;',



            'GBP' => '&pound;',



            'GEL' => '&#x10da;',



            'GGP' => '&pound;',



            'GHS' => '&#x20b5;',



            'GIP' => '&pound;',



            'GMD' => 'D',



            'GNF' => 'Fr',



            'GTQ' => 'Q',



            'GYD' => '&#36;',



            'HKD' => '&#36;',



            'HNL' => 'L',



            'HRK' => 'Kn',



            'HTG' => 'G',



            'HUF' => '&#70;&#116;',



            'IDR' => 'Rp',



            'ILS' => '&#8362;',



            'IMP' => '&pound;',



            'INR' => '&#8377;',



            'IQD' => '&#x639;.&#x62f;',



            'IRR' => '&#xfdfc;',



            'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',



            'ISK' => 'kr.',



            'JEP' => '&pound;',



            'JMD' => '&#36;',



            'JOD' => '&#x62f;.&#x627;',



            'JPY' => '&yen;',



            'KES' => 'KSh',



            'KGS' => '&#x441;&#x43e;&#x43c;',



            'KHR' => '&#x17db;',



            'KMF' => 'Fr',



            'KPW' => '&#x20a9;',



            'KRW' => '&#8361;',



            'KWD' => '&#x62f;.&#x643;',



            'KYD' => '&#36;',



            'KZT' => 'KZT',



            'LAK' => '&#8365;',



            'LBP' => '&#x644;.&#x644;',



            'LKR' => '&#xdbb;&#xdd4;',



            'LRD' => '&#36;',



            'LSL' => 'L',



            'LYD' => '&#x644;.&#x62f;',



            'MAD' => '&#x62f;.&#x645;.',



            'MDL' => 'MDL',



            'MGA' => 'Ar',



            'MKD' => '&#x434;&#x435;&#x43d;',



            'MMK' => 'Ks',



            'MNT' => '&#x20ae;',



            'MOP' => 'P',



            'MRO' => 'UM',



            'MUR' => '&#x20a8;',



            'MVR' => '.&#x783;',



            'MWK' => 'MK',



            'MXN' => '&#36;',



            'MYR' => '&#82;&#77;',



            'MZN' => 'MT',



            'NAD' => '&#36;',



            'NGN' => '&#8358;',



            'NIO' => 'C&#36;',



            'NOK' => '&#107;&#114;',



            'NPR' => '&#8360;',



            'NZD' => '&#36;',



            'OMR' => '&#x631;.&#x639;.',



            'PAB' => 'B/.',



            'PEN' => 'S/.',



            'PGK' => 'K',



            'PHP' => '&#8369;',



            'PKR' => '&#8360;',



            'PLN' => '&#122;&#322;',



            'PRB' => '&#x440;.',



            'PYG' => '&#8370;',



            'QAR' => '&#x631;.&#x642;',



            'RMB' => '&yen;',



            'RON' => 'lei',



            'RSD' => '&#x434;&#x438;&#x43d;.',



            'RUB' => '&#8381;',



            'RWF' => 'Fr',



            'SAR' => '&#x631;.&#x633;',



            'SBD' => '&#36;',



            'SCR' => '&#x20a8;',



            'SDG' => '&#x62c;.&#x633;.',



            'SEK' => '&#107;&#114;',



            'SGD' => '&#36;',



            'SHP' => '&pound;',



            'SLL' => 'Le',



            'SOS' => 'Sh',



            'SRD' => '&#36;',



            'SSP' => '&pound;',



            'STD' => 'Db',



            'SYP' => '&#x644;.&#x633;',



            'SZL' => 'L',



            'THB' => '&#3647;',



            'TJS' => '&#x405;&#x41c;',



            'TMT' => 'm',



            'TND' => '&#x62f;.&#x62a;',



            'TOP' => 'T&#36;',



            'TRY' => '&#8378;',



            'TTD' => '&#36;',



            'TWD' => '&#78;&#84;&#36;',



            'TZS' => 'Sh',



            'UAH' => '&#8372;',



            'UGX' => 'UGX',



            'USD' => '&#36;',



            'UYU' => '&#36;',



            'UZS' => 'UZS',



            'VEF' => 'Bs F',



            'VND' => '&#8363;',



            'VUV' => 'Vt',



            'WST' => 'T',



            'XAF' => 'CFA',



            'XCD' => '&#36;',



            'XOF' => 'CFA',



            'XPF' => 'Fr',



            'YER' => '&#xfdfc;',



            'ZAR' => '&#82;',



            'ZMW' => 'ZK',



        );



        return $symbols;



    }







	function ncm_country() {



		$country = array(				



			"Aland Islands" => __('Aland Islands', NCM_txt_domain),



			"Afghanistan" => __('Afghanistan', NCM_txt_domain),



			"Albania" => __('Albania', NCM_txt_domain),



			"Algeria" => __('Algeria', NCM_txt_domain),



			"American Samoa" => __('American Samoa', NCM_txt_domain),



			"Andorra" => __('Andorra', NCM_txt_domain),



			"Angola" => __('Angola', NCM_txt_domain),



			"Anguilla" => __('Anguilla', NCM_txt_domain),



			"Antarctica" => __('Antarctica', NCM_txt_domain),



			"Antigua and Barbuda" => __('Antigua and Barbuda', NCM_txt_domain),



			"Argentina" => __('Argentina', NCM_txt_domain),



			"Armenia" => __('Armenia', NCM_txt_domain),



			"Aruba" => __('Aruba', NCM_txt_domain),



			"Australia" => __('Australia', NCM_txt_domain),



			"Austria" => __('Austria', NCM_txt_domain),



			"Azerbaijan" => __('Azerbaijan', NCM_txt_domain),



			"Bahamas" => __('Bahamas', NCM_txt_domain),



			"Bahrain" => __('Bahrain', NCM_txt_domain),



			"Bangladesh" => __('Bangladesh', NCM_txt_domain),



			"Barbados" => __('Barbados', NCM_txt_domain),



			"Belarus" => __('Belarus', NCM_txt_domain),



			"Belau" => __('Belau', NCM_txt_domain),



			"Belgium" => __('Belgium', NCM_txt_domain),



			"Belize" => __('Belize', NCM_txt_domain),



			"Benin" => __('Benin', NCM_txt_domain),



			"Bermuda" => __('Bermuda', NCM_txt_domain),



			"Bhutan" => __('Bhutan', NCM_txt_domain),



			"Bolivia" => __('Bolivia', NCM_txt_domain),



			"Bonaire Saint Eustatius and Saba" => __('Bonaire Saint Eustatius and Saba', NCM_txt_domain),



			"Bosnia and Herzegovina" => __('Bosnia and Herzegovina', NCM_txt_domain),



			"Botswana" => __('Botswana', NCM_txt_domain),



			"Bouvet Island" => __('Bouvet Island', NCM_txt_domain),



			"Brazil" => __('Brazil', NCM_txt_domain),



			"British Indian Ocean Territory" => __('British Indian Ocean Territory', NCM_txt_domain),



			"British Virgin Islands" => __('British Virgin Islands', NCM_txt_domain),



			"Brunei" => __('Brunei', NCM_txt_domain),



			"Bulgaria" => __('Bulgaria', NCM_txt_domain),



			"Burkina Faso" => __('Burkina Faso', NCM_txt_domain),



			"Burundi" => __('Burundi', NCM_txt_domain),



			"Cambodia" => __('Cambodia', NCM_txt_domain),



			"Cameroon" => __('Cameroon', NCM_txt_domain),



			"Canada" => __('Canada', NCM_txt_domain),



			"Cape Verde" => __('Cape Verde', NCM_txt_domain),



			"Cayman Islands" => __('Cayman Islands', NCM_txt_domain),



			"Central African Republic" => __('Central African Republic', NCM_txt_domain),



			"Chad" => __('Chad', NCM_txt_domain),



			"Chile" => __('Chile', NCM_txt_domain),



			"China" => __('China', NCM_txt_domain),



			"Christmas Island" => __('Christmas Island', NCM_txt_domain),



			"Cocos (Keeling) Islands" => __('Cocos (Keeling) Islands', NCM_txt_domain),



			"Colombia" => __('Colombia', NCM_txt_domain),



			"Comoros" => __('Comoros', NCM_txt_domain),



			"Congo (Brazzaville)" => __('Congo (Brazzaville)', NCM_txt_domain),



			"Congo (Kinshasa)" => __('Congo (Kinshasa)', NCM_txt_domain),



			"Cook Islands" => __('Cook Islands', NCM_txt_domain),



			"Costa Rica" => __('Costa Rica', NCM_txt_domain),



			"Croatia" => __('Croatia', NCM_txt_domain),



			"Cuba" => __('Cuba', NCM_txt_domain),



			"Curaçao" => __('Curaçao', NCM_txt_domain),



			"Cyprus" => __('Cyprus', NCM_txt_domain),



			"Czech Republic" => __('Czech Republic', NCM_txt_domain),



			"Denmark" => __('Denmark', NCM_txt_domain),



			"Djibouti" => __('Djibouti', NCM_txt_domain),



			"Dominica" => __('Dominica', NCM_txt_domain),



			"Dominican Republic" => __('Dominican Republic', NCM_txt_domain),



			"Ecuador" => __('Ecuador', NCM_txt_domain),



			"Egypt" => __('Egypt', NCM_txt_domain),



			"El Salvador" => __('El Salvador', NCM_txt_domain),



			"Equatorial Guinea" => __('Equatorial Guinea', NCM_txt_domain),



			"Eritrea" => __('Eritrea', NCM_txt_domain),



			"Estonia" => __('Estonia', NCM_txt_domain),



			"Ethiopia" => __('Ethiopia', NCM_txt_domain),



			"Falkland Islands" => __('Falkland Islands', NCM_txt_domain),



			"Faroe Islands" => __('Faroe Islands', NCM_txt_domain),



			"Fiji" => __('Fiji', NCM_txt_domain),



			"Finland" => __('Finland', NCM_txt_domain),



			"France" => __('France', NCM_txt_domain),



			"French Guiana" => __('French Guiana', NCM_txt_domain),



			"French Polynesia" => __('French Polynesia', NCM_txt_domain),



			"French Southern Territories" => __('French Southern Territories', NCM_txt_domain),



			"Gabon" => __('Gabon', NCM_txt_domain),



			"Gambia" => __('Gambia', NCM_txt_domain),



			"Georgia" => __('Georgia', NCM_txt_domain),



			"Germany" => __('Germany', NCM_txt_domain),



			"Ghana" => __('Ghana', NCM_txt_domain),



			"Gibraltar" => __('Gibraltar', NCM_txt_domain),



 			"Greece" => __('Greece', NCM_txt_domain),



			"Greenland" => __('Greenland', NCM_txt_domain),



			"Grenada" => __('Grenada', NCM_txt_domain),



			"Guadeloupe" => __('Guadeloupe', NCM_txt_domain),



			"Guam" => __('Guam', NCM_txt_domain),



			"Guatemala" => __('Guatemala', NCM_txt_domain),



			"Guernsey" => __('Guernsey', NCM_txt_domain),



			"Guinea" => __('Guinea', NCM_txt_domain),



			"Guinea-Bissau" => __('Guinea-Bissau', NCM_txt_domain),



			"Guyana" => __('Guyana', NCM_txt_domain),



			"Haiti" => __('Haiti', NCM_txt_domain),



			"Heard Island and McDonald Islands" => __('Heard Island and McDonald Islands', NCM_txt_domain),



			"Honduras" => __('Honduras', NCM_txt_domain),



			"Hong Kong" => __('Hong Kong', NCM_txt_domain),



			"Hungary" => __('Hungary', NCM_txt_domain),



			"Iceland" => __('Iceland', NCM_txt_domain),



			"India" => __('India', NCM_txt_domain),



			"Indonesia" => __('Indonesia', NCM_txt_domain),



			"Iran" => __('Iran', NCM_txt_domain),



			"Iraq" => __('Iraq', NCM_txt_domain),



			"Ireland" => __('Ireland', NCM_txt_domain),



			"Isle of Man" => __('Isle of Man', NCM_txt_domain),



			"Israel" => __('Israel', NCM_txt_domain),



			"Italy" => __('Italy', NCM_txt_domain),



			"Ivory Coast" => __('Ivory Coast', NCM_txt_domain),



			"Jamaica" => __('Jamaica', NCM_txt_domain),



			"Japan" => __('Japan', NCM_txt_domain),



			"Jersey" => __('Jersey', NCM_txt_domain),



			"Jordan" => __('Jordan', NCM_txt_domain),



			"Kazakhstan" => __('Kazakhstan', NCM_txt_domain),



			"Kenya" => __('Kenya', NCM_txt_domain),



			"Kiribati" => __('Kiribati', NCM_txt_domain),



			"Kuwait" => __('Kuwait', NCM_txt_domain),



			"Kyrgyzstan" => __('Kyrgyzstan', NCM_txt_domain),



			"Laos" => __('Laos', NCM_txt_domain),



			"Latvia" => __('Latvia', NCM_txt_domain),



			"Lebanon" => __('Lebanon', NCM_txt_domain),



			"Lesotho" => __('Lesotho', NCM_txt_domain),



			"Liberia" => __('Liberia', NCM_txt_domain),



			"Libya" => __('Libya', NCM_txt_domain),



			"Liechtenstein" => __('Liechtenstein', NCM_txt_domain),



			"Lithuania" => __('Lithuania', NCM_txt_domain),



			"Luxembourg" => __('Luxembourg', NCM_txt_domain),



			"Macao S.A.R., China" => __('Macao S.A.R., China', NCM_txt_domain),



			"Macedonia" => __('Macedonia', NCM_txt_domain),



			"Madagascar" => __('Madagascar', NCM_txt_domain),



			"Malawi" => __('Malawi', NCM_txt_domain),



			"Malaysia" => __('Malaysia', NCM_txt_domain),



			"Maldives" => __('Maldives', NCM_txt_domain),



			"Mali" => __('Mali', NCM_txt_domain),



			"Malta" => __('Malta', NCM_txt_domain),



			"Marshall Islands" => __('Marshall Islands', NCM_txt_domain),



			"Martinique" => __('Martinique', NCM_txt_domain),



			"Mauritania" => __('Mauritania', NCM_txt_domain),



			"Mauritius" => __('Mauritius', NCM_txt_domain),



			"Mayotte" => __('Mayotte', NCM_txt_domain),



			"Mexico" => __('Mexico', NCM_txt_domain),



			"Micronesia" => __('Micronesia', NCM_txt_domain),



			"Moldova" => __('Moldova', NCM_txt_domain),



			"Monaco" => __('Monaco', NCM_txt_domain),



			"Mongolia" => __('Mongolia', NCM_txt_domain),



			"Montenegro" => __('Montenegro', NCM_txt_domain),



			"Montserrat" => __('Montserrat', NCM_txt_domain),



			"Morocco" => __('Morocco', NCM_txt_domain),



			"Mozambique" => __('Mozambique', NCM_txt_domain),



			"Myanmar" => __('Myanmar', NCM_txt_domain),



			"Namibia" => __('Namibia', NCM_txt_domain),



			"Nauru" => __('Nauru', NCM_txt_domain),



			"Nepal" => __('Nepal', NCM_txt_domain),



			"Netherlands" => __('Netherlands', NCM_txt_domain),



			"New Caledonia" => __('New Caledonia', NCM_txt_domain),



			"New Zealand" => __('New Zealand', NCM_txt_domain),



			"Nicaragua" => __('Nicaragua', NCM_txt_domain),



			"Niger" => __('Niger', NCM_txt_domain),



			"Nigeria" => __('Nigeria', NCM_txt_domain),



			"Niue" => __('Niue', NCM_txt_domain),



			"Norfolk Island" => __('Norfolk Island', NCM_txt_domain),



			"North Korea" => __('North Korea', NCM_txt_domain),



			"Northern Mariana Islands" => __('Northern Mariana Islands', NCM_txt_domain),



			"Norway" => __('Norway', NCM_txt_domain),



			"Oman" => __('Oman', NCM_txt_domain),



			"Pakistan" => __('Pakistan', NCM_txt_domain),



			"Palestinian Territory" => __('Palestinian Territory', NCM_txt_domain),



			"Panama" => __('Panama', NCM_txt_domain),



			"Papua New Guinea" => __('Papua New Guinea', NCM_txt_domain),



			"Paraguay" => __('Paraguay', NCM_txt_domain),



			"Paru" => __('Paru', NCM_txt_domain),



			"Philippines" => __('Philippines', NCM_txt_domain),



			"Pitcairn" => __('Pitcairn', NCM_txt_domain),



			"Poland" => __('Poland', NCM_txt_domain),



			"Portugal" => __('Portugal', NCM_txt_domain),



			"Puerto Rico" => __('Puerto Rico', NCM_txt_domain),



			"Qatar" => __('Qatar', NCM_txt_domain),



			"Reunion" => __('Reunion', NCM_txt_domain),



			"Romania" => __('Romania', NCM_txt_domain),



			"Russia" => __('Russia', NCM_txt_domain),



			"Rwanda" => __('Rwanda', NCM_txt_domain),



			"São Tomé and Príncipe" => __('São Tomé and Príncipe', NCM_txt_domain),



			"Saint Barthélemy" => __('Saint Barthélemy', NCM_txt_domain),



			"Saint Helena" => __('Saint Helena', NCM_txt_domain),



			"Saint Kitts and Nevis" => __('Saint Kitts and Nevis', NCM_txt_domain),



			"Saint Lucia" => __('Saint Lucia', NCM_txt_domain),



			"Saint Martin (Dutch part)" => __('Saint Martin (Dutch part)', NCM_txt_domain),



			"Saint Martin (French part)" => __('Saint Martin (French part)', NCM_txt_domain),



			"Saint Pierre and Miquelon" => __('Saint Pierre and Miquelon', NCM_txt_domain),



			"Saint Vincent and the Grenadines" => __('Saint Vincent and the Grenadines', NCM_txt_domain),



			"Samoa" => __('Samoa', NCM_txt_domain),



			"San Marino" => __('San Marino', NCM_txt_domain),



			"Saudi Arabia" => __('Saudi Arabia', NCM_txt_domain),



			"Senegal" => __('Senegal', NCM_txt_domain),



			"Serbia" => __('Serbia', NCM_txt_domain),



			"Seychelles" => __('Seychelles', NCM_txt_domain),



			"Sierra Leone" => __('Sierra Leone', NCM_txt_domain),



			"Singapore" => __('Singapore', NCM_txt_domain),



			"Slovakia" => __('Slovakia', NCM_txt_domain),



			"Slovenia" => __('Slovenia', NCM_txt_domain),



			"Solomon Islands" => __('Solomon Islands', NCM_txt_domain),



			"Somalia" => __('Somalia', NCM_txt_domain),



			"South Africa" => __('South Africa', NCM_txt_domain),



			"South Georgia/Sandwich Islands" => __('South Georgia/Sandwich Islands', NCM_txt_domain),



			"South Korea" => __('South Korea', NCM_txt_domain),



			"South Sudan" => __('South Sudan', NCM_txt_domain),



			"South Sudan" => __('South Sudan', NCM_txt_domain),



			"Sri Lanka" => __('Sri Lanka', NCM_txt_domain),



			"Sudan" => __('Sudan', NCM_txt_domain),



			"Suriname" => __('Suriname', NCM_txt_domain),



			"Svalbard and Jan Mayen" => __('Svalbard and Jan Mayen', NCM_txt_domain),



			"Swaziland" => __('Swaziland', NCM_txt_domain),



			"Sweden" => __('Sweden', NCM_txt_domain),



			"Switzerland" => __('Switzerland', NCM_txt_domain),



			"Syria" => __('Syria', NCM_txt_domain),



			"Taiwan" => __('Taiwan', NCM_txt_domain),



			"Tajikistan" => __('Tajikistan', NCM_txt_domain),



			"Tanzania" => __('Tanzania', NCM_txt_domain),



			"Thailand" => __('Thailand', NCM_txt_domain),



			"Timor-Leste" => __('Timor-Leste', NCM_txt_domain),



			"Togo" => __('Togo', NCM_txt_domain),



			"Tokelau" => __('Tokelau', NCM_txt_domain),



			"Tonga" => __('Tonga', NCM_txt_domain),



			"Trinidad and Tobago" => __('Trinidad and Tobago', NCM_txt_domain),



			"Tunisia" => __('Tunisia', NCM_txt_domain),



			"Turkey" => __('Turkey', NCM_txt_domain),



			"Turkmenistan" => __('Turkmenistan', NCM_txt_domain),



			"Turks and Caicos Islands" => __('Turks and Caicos Islands', NCM_txt_domain),



			"Tuvalu" => __('Tuvalu', NCM_txt_domain),



			"Uganda" => __('Uganda', NCM_txt_domain),



			"Ukraine" => __('Ukraine', NCM_txt_domain),



			"United Arab Emirates" => __('United Arab Emirates', NCM_txt_domain),



			"United Kingdom (UK)" => __('United Kingdom (UK)', NCM_txt_domain),



			"United States (US)" => __('United States (US)', NCM_txt_domain),



			"United States (US) Minor Outlying Islands" => __('United States (US) Minor Outlying Islands',NCM_txt_domain),



			"United States (US) Virgin Islands" => __('United States (US) Virgin Islands', NCM_txt_domain),



			"Uruguay" => __('Uruguay', NCM_txt_domain),



			"Uzbekistan" => __('Uzbekistan', NCM_txt_domain),



			"Vanuatu" => __('Vanuatu', NCM_txt_domain),



			"Vatican" => __('Vatican', NCM_txt_domain),



			"Venezuela" => __('Venezuela', NCM_txt_domain),



			"Vietnam" => __('Vietnam', NCM_txt_domain),



			"Wallis and Futuna" => __('Wallis and Futuna', NCM_txt_domain),



			"Western Sahara" => __('Western Sahara', NCM_txt_domain),



			"Yemen" => __('Yemen', NCM_txt_domain),



			"Zambia" => __('Zambia', NCM_txt_domain),



			"Zimbabwe" => __('Zimbabwe', NCM_txt_domain),



		);



        return $country;



	}







    function ncm_state() {



        $state = array(



            'AO' => array(



                'BGO' => __( 'Bengo', NCM_txt_domain ),



                'BLU' => __( 'Benguela', NCM_txt_domain ),



                'BIE' => __( 'Bié', NCM_txt_domain ),



                'CAB' => __( 'Cabinda', NCM_txt_domain ),



                'CNN' => __( 'Cunene', NCM_txt_domain ),



                'HUA' => __( 'Huambo', NCM_txt_domain ),



                'HUI' => __( 'Huíla', NCM_txt_domain ),



                'CCU' => __( 'Kuando Kubango', NCM_txt_domain ),



                'CNO' => __( 'Kwanza-Norte', NCM_txt_domain ),



                'CUS' => __( 'Kwanza-Sul', NCM_txt_domain ),



                'LUA' => __( 'Luanda', NCM_txt_domain ),



                'LNO' => __( 'Lunda-Norte', NCM_txt_domain ),



                'LSU' => __( 'Lunda-Sul', NCM_txt_domain ),



                'MAL' => __( 'Malanje', NCM_txt_domain ),



                'MOX' => __( 'Moxico', NCM_txt_domain ),



                'NAM' => __( 'Namibe', NCM_txt_domain ),



                'UIG' => __( 'Uíge', NCM_txt_domain ),



                'ZAI' => __( 'Zaire', NCM_txt_domain ),



            ),



            'AR' => array(



                'C' => __( 'Ciudad Aut&oacute;noma de Buenos Aires', NCM_txt_domain ),



                'B' => __( 'Buenos Aires', NCM_txt_domain ),



                'K' => __( 'Catamarca', NCM_txt_domain ),



                'H' => __( 'Chaco', NCM_txt_domain ),



                'U' => __( 'Chubut', NCM_txt_domain ),



                'X' => __( 'C&oacute;rdoba', NCM_txt_domain ),



                'W' => __( 'Corrientes', NCM_txt_domain ),



                'E' => __( 'Entre R&iacute;os', NCM_txt_domain ),



                'P' => __( 'Formosa', NCM_txt_domain ),



                'Y' => __( 'Jujuy', NCM_txt_domain ),



                'L' => __( 'La Pampa', NCM_txt_domain ),



                'F' => __( 'La Rioja', NCM_txt_domain ),



                'M' => __( 'Mendoza', NCM_txt_domain ),



                'N' => __( 'Misiones', NCM_txt_domain ),



                'Q' => __( 'Neuqu&eacute;n', NCM_txt_domain ),



                'R' => __( 'R&iacute;o Negro', NCM_txt_domain ),



                'A' => __( 'Salta', NCM_txt_domain ),



                'J' => __( 'San Juan', NCM_txt_domain ),



                'D' => __( 'San Luis', NCM_txt_domain ),



                'Z' => __( 'Santa Cruz', NCM_txt_domain ),



                'S' => __( 'Santa Fe', NCM_txt_domain ),



                'G' => __( 'Santiago del Estero', NCM_txt_domain ),



                'V' => __( 'Tierra del Fuego', NCM_txt_domain ),



                'T' => __( 'Tucum&aacute;n', NCM_txt_domain ),



            ),



            'AU' => array(



                'ACT' => __( 'Australian Capital Territory', NCM_txt_domain ),



                'NSW' => __( 'New South Wales', NCM_txt_domain ),



                'NT'  => __( 'Northern Territory', NCM_txt_domain ),



                'QLD' => __( 'Queensland', NCM_txt_domain ),



                'SA'  => __( 'South Australia', NCM_txt_domain ),



                'TAS' => __( 'Tasmania', NCM_txt_domain ),



                'VIC' => __( 'Victoria', NCM_txt_domain ),



                'WA'  => __( 'Western Australia', NCM_txt_domain ),



            ),



            'BD' => array(



                'BAG'  => __( 'Bagerhat', NCM_txt_domain ),



                'BAN'  => __( 'Bandarban', NCM_txt_domain ),



                'BAR'  => __( 'Barguna', NCM_txt_domain ),



                'BARI' => __( 'Barisal', NCM_txt_domain ),



                'BHO'  => __( 'Bhola', NCM_txt_domain ),



                'BOG'  => __( 'Bogra', NCM_txt_domain ),



                'BRA'  => __( 'Brahmanbaria', NCM_txt_domain ),



                'CHA'  => __( 'Chandpur', NCM_txt_domain ),



                'CHI'  => __( 'Chittagong', NCM_txt_domain ),



                'CHU'  => __( 'Chuadanga', NCM_txt_domain ),



                'COM'  => __( 'Comilla', NCM_txt_domain ),



                'COX'  => __( "Cox's Bazar", NCM_txt_domain ),



                'DHA'  => __( 'Dhaka', NCM_txt_domain ),



                'DIN'  => __( 'Dinajpur', NCM_txt_domain ),



                'FAR'  => __( 'Faridpur ', NCM_txt_domain ),



                'FEN'  => __( 'Feni', NCM_txt_domain ),



                'GAI'  => __( 'Gaibandha', NCM_txt_domain ),



                'GAZI' => __( 'Gazipur', NCM_txt_domain ),



                'GOP'  => __( 'Gopalganj', NCM_txt_domain ),



                'HAB'  => __( 'Habiganj', NCM_txt_domain ),



                'JAM'  => __( 'Jamalpur', NCM_txt_domain ),



                'JES'  => __( 'Jessore', NCM_txt_domain ),



                'JHA'  => __( 'Jhalokati', NCM_txt_domain ),



                'JHE'  => __( 'Jhenaidah', NCM_txt_domain ),



                'JOY'  => __( 'Joypurhat', NCM_txt_domain ),



                'KHA'  => __( 'Khagrachhari', NCM_txt_domain ),



                'KHU'  => __( 'Khulna', NCM_txt_domain ),



                'KIS'  => __( 'Kishoreganj', NCM_txt_domain ),



                'KUR'  => __( 'Kurigram', NCM_txt_domain ),



                'KUS'  => __( 'Kushtia', NCM_txt_domain ),



                'LAK'  => __( 'Lakshmipur', NCM_txt_domain ),



                'LAL'  => __( 'Lalmonirhat', NCM_txt_domain ),



                'MAD'  => __( 'Madaripur', NCM_txt_domain ),



                'MAG'  => __( 'Magura', NCM_txt_domain ),



                'MAN'  => __( 'Manikganj ', NCM_txt_domain ),



                'MEH'  => __( 'Meherpur', NCM_txt_domain ),



                'MOU'  => __( 'Moulvibazar', NCM_txt_domain ),



                'MUN'  => __( 'Munshiganj', NCM_txt_domain ),



                'MYM'  => __( 'Mymensingh', NCM_txt_domain ),



                'NAO'  => __( 'Naogaon', NCM_txt_domain ),



                'NAR'  => __( 'Narail', NCM_txt_domain ),



                'NARG' => __( 'Narayanganj', NCM_txt_domain ),



                'NARD' => __( 'Narsingdi', NCM_txt_domain ),



                'NAT'  => __( 'Natore', NCM_txt_domain ),



                'NAW'  => __( 'Nawabganj', NCM_txt_domain ),



                'NET'  => __( 'Netrakona', NCM_txt_domain ),



                'NIL'  => __( 'Nilphamari', NCM_txt_domain ),



                'NOA'  => __( 'Noakhali', NCM_txt_domain ),



                'PAB'  => __( 'Pabna', NCM_txt_domain ),



                'PAN'  => __( 'Panchagarh', NCM_txt_domain ),



                'PAT'  => __( 'Patuakhali', NCM_txt_domain ),



                'PIR'  => __( 'Pirojpur', NCM_txt_domain ),



                'RAJB' => __( 'Rajbari', NCM_txt_domain ),



                'RAJ'  => __( 'Rajshahi', NCM_txt_domain ),



                'RAN'  => __( 'Rangamati', NCM_txt_domain ),



                'RANP' => __( 'Rangpur', NCM_txt_domain ),



                'SAT'  => __( 'Satkhira', NCM_txt_domain ),



                'SHA'  => __( 'Shariatpur', NCM_txt_domain ),



                'SHE'  => __( 'Sherpur', NCM_txt_domain ),



                'SIR'  => __( 'Sirajganj', NCM_txt_domain ),



                'SUN'  => __( 'Sunamganj', NCM_txt_domain ),



                'SYL'  => __( 'Sylhet', NCM_txt_domain ),



                'TAN'  => __( 'Tangail', NCM_txt_domain ),



                'THA'  => __( 'Thakurgaon', NCM_txt_domain ),



            ),



            'BG' => array(



                'BG-01' => __( 'Blagoevgrad', NCM_txt_domain ),



                'BG-02' => __( 'Burgas', NCM_txt_domain ),



                'BG-08' => __( 'Dobrich', NCM_txt_domain ),



                'BG-07' => __( 'Gabrovo', NCM_txt_domain ),



                'BG-26' => __( 'Haskovo', NCM_txt_domain ),



                'BG-09' => __( 'Kardzhali', NCM_txt_domain ),



                'BG-10' => __( 'Kyustendil', NCM_txt_domain ),



                'BG-11' => __( 'Lovech', NCM_txt_domain ),



                'BG-12' => __( 'Montana', NCM_txt_domain ),



                'BG-13' => __( 'Pazardzhik', NCM_txt_domain ),



                'BG-14' => __( 'Pernik', NCM_txt_domain ),



                'BG-15' => __( 'Pleven', NCM_txt_domain ),



                'BG-16' => __( 'Plovdiv', NCM_txt_domain ),



                'BG-17' => __( 'Razgrad', NCM_txt_domain ),



                'BG-18' => __( 'Ruse', NCM_txt_domain ),



                'BG-27' => __( 'Shumen', NCM_txt_domain ),



                'BG-19' => __( 'Silistra', NCM_txt_domain ),



                'BG-20' => __( 'Sliven', NCM_txt_domain ),



                'BG-21' => __( 'Smolyan', NCM_txt_domain ),



                'BG-23' => __( 'Sofia', NCM_txt_domain ),



                'BG-22' => __( 'Sofia-Grad', NCM_txt_domain ),



                'BG-24' => __( 'Stara Zagora', NCM_txt_domain ),



                'BG-25' => __( 'Targovishte', NCM_txt_domain ),



                'BG-03' => __( 'Varna', NCM_txt_domain ),



                'BG-04' => __( 'Veliko Tarnovo', NCM_txt_domain ),



                'BG-05' => __( 'Vidin', NCM_txt_domain ),



                'BG-06' => __( 'Vratsa', NCM_txt_domain ),



                'BG-28' => __( 'Yambol', NCM_txt_domain ),



            ),



            'BO' => array(



                'B' => __( 'Chuquisaca', NCM_txt_domain ),



                'H' => __( 'Beni', NCM_txt_domain ),



                'C' => __( 'Cochabamba', NCM_txt_domain ),



                'L' => __( 'La Paz', NCM_txt_domain ),



                'O' => __( 'Oruro', NCM_txt_domain ),



                'N' => __( 'Pando', NCM_txt_domain ),



                'P' => __( 'Potosí', NCM_txt_domain ),



                'S' => __( 'Santa Cruz', NCM_txt_domain ),



                'T' => __( 'Tarija', NCM_txt_domain ),



            ),



            'BR' => array(



                'AC' => __( 'Acre', NCM_txt_domain ),



                'AL' => __( 'Alagoas', NCM_txt_domain ),



                'AP' => __( 'Amap&aacute;', NCM_txt_domain ),



                'AM' => __( 'Amazonas', NCM_txt_domain ),



                'BA' => __( 'Bahia', NCM_txt_domain ),



                'CE' => __( 'Cear&aacute;', NCM_txt_domain ),



                'DF' => __( 'Distrito Federal', NCM_txt_domain ),



                'ES' => __( 'Esp&iacute;rito Santo', NCM_txt_domain ),



                'GO' => __( 'Goi&aacute;s', NCM_txt_domain ),



                'MA' => __( 'Maranh&atilde;o', NCM_txt_domain ),



                'MT' => __( 'Mato Grosso', NCM_txt_domain ),



                'MS' => __( 'Mato Grosso do Sul', NCM_txt_domain ),



                'MG' => __( 'Minas Gerais', NCM_txt_domain ),



                'PA' => __( 'Par&aacute;', NCM_txt_domain ),



                'PB' => __( 'Para&iacute;ba', NCM_txt_domain ),



                'PR' => __( 'Paran&aacute;', NCM_txt_domain ),



                'PE' => __( 'Pernambuco', NCM_txt_domain ),



                'PI' => __( 'Piau&iacute;', NCM_txt_domain ),



                'RJ' => __( 'Rio de Janeiro', NCM_txt_domain ),



                'RN' => __( 'Rio Grande do Norte', NCM_txt_domain ),



                'RS' => __( 'Rio Grande do Sul', NCM_txt_domain ),



                'RO' => __( 'Rond&ocirc;nia', NCM_txt_domain ),



                'RR' => __( 'Roraima', NCM_txt_domain ),



                'SC' => __( 'Santa Catarina', NCM_txt_domain ),



                'SP' => __( 'S&atilde;o Paulo', NCM_txt_domain ),



                'SE' => __( 'Sergipe', NCM_txt_domain ),



                'TO' => __( 'Tocantins', NCM_txt_domain ),



            ),



            'CA' => array(



                'AB' => __( 'Alberta', NCM_txt_domain ),



                'BC' => __( 'British Columbia', NCM_txt_domain ),



                'MB' => __( 'Manitoba', NCM_txt_domain ),



                'NB' => __( 'New Brunswick', NCM_txt_domain ),



                'NL' => __( 'Newfoundland and Labrador', NCM_txt_domain ),



                'NT' => __( 'Northwest Territories', NCM_txt_domain ),



                'NS' => __( 'Nova Scotia', NCM_txt_domain ),



                'NU' => __( 'Nunavut', NCM_txt_domain ),



                'ON' => __( 'Ontario', NCM_txt_domain ),



                'PE' => __( 'Prince Edward Island', NCM_txt_domain ),



                'QC' => __( 'Quebec', NCM_txt_domain ),



                'SK' => __( 'Saskatchewan', NCM_txt_domain ),



                'YT' => __( 'Yukon Territory', NCM_txt_domain ),



            ),



            'CH' => array(



                'AG' => __( 'Aargau', NCM_txt_domain ),



                'AR' => __( 'Appenzell Ausserrhoden', NCM_txt_domain ),



                'AI' => __( 'Appenzell Innerrhoden', NCM_txt_domain ),



                'BL' => __( 'Basel-Landschaft', NCM_txt_domain ),



                'BS' => __( 'Basel-Stadt', NCM_txt_domain ),



                'BE' => __( 'Bern', NCM_txt_domain ),



                'FR' => __( 'Fribourg', NCM_txt_domain ),



                'GE' => __( 'Geneva', NCM_txt_domain ),



                'GL' => __( 'Glarus', NCM_txt_domain ),



                'GR' => __( 'Graub&uuml;nden', NCM_txt_domain ),



                'JU' => __( 'Jura', NCM_txt_domain ),



                'LU' => __( 'Luzern', NCM_txt_domain ),



                'NE' => __( 'Neuch&acirc;tel', NCM_txt_domain ),



                'NW' => __( 'Nidwalden', NCM_txt_domain ),



                'OW' => __( 'Obwalden', NCM_txt_domain ),



                'SH' => __( 'Schaffhausen', NCM_txt_domain ),



                'SZ' => __( 'Schwyz', NCM_txt_domain ),



                'SO' => __( 'Solothurn', NCM_txt_domain ),



                'SG' => __( 'St. Gallen', NCM_txt_domain ),



                'TG' => __( 'Thurgau', NCM_txt_domain ),



                'TI' => __( 'Ticino', NCM_txt_domain ),



                'UR' => __( 'Uri', NCM_txt_domain ),



                'VS' => __( 'Valais', NCM_txt_domain ),



                'VD' => __( 'Vaud', NCM_txt_domain ),



                'ZG' => __( 'Zug', NCM_txt_domain ),



                'ZH' => __( 'Z&uuml;rich', NCM_txt_domain ),



            ),



            'CN' => array(



                'CN1'  => __( 'Yunnan / &#20113;&#21335;', NCM_txt_domain ),



                'CN2'  => __( 'Beijing / &#21271;&#20140;', NCM_txt_domain ),



                'CN3'  => __( 'Tianjin / &#22825;&#27941;', NCM_txt_domain ),



                'CN4'  => __( 'Hebei / &#27827;&#21271;', NCM_txt_domain ),



                'CN5'  => __( 'Shanxi / &#23665;&#35199;', NCM_txt_domain ),



                'CN6'  => __( 'Inner Mongolia / &#20839;&#33945;&#21476;', NCM_txt_domain ),



                'CN7'  => __( 'Liaoning / &#36797;&#23425;', NCM_txt_domain ),



                'CN8'  => __( 'Jilin / &#21513;&#26519;', NCM_txt_domain ),



                'CN9'  => __( 'Heilongjiang / &#40657;&#40857;&#27743;', NCM_txt_domain ),



                'CN10' => __( 'Shanghai / &#19978;&#28023;', NCM_txt_domain ),



                'CN11' => __( 'Jiangsu / &#27743;&#33487;', NCM_txt_domain ),



                'CN12' => __( 'Zhejiang / &#27993;&#27743;', NCM_txt_domain ),



                'CN13' => __( 'Anhui / &#23433;&#24509;', NCM_txt_domain ),



                'CN14' => __( 'Fujian / &#31119;&#24314;', NCM_txt_domain ),



                'CN15' => __( 'Jiangxi / &#27743;&#35199;', NCM_txt_domain ),



                'CN16' => __( 'Shandong / &#23665;&#19996;', NCM_txt_domain ),



                'CN17' => __( 'Henan / &#27827;&#21335;', NCM_txt_domain ),



                'CN18' => __( 'Hubei / &#28246;&#21271;', NCM_txt_domain ),



                'CN19' => __( 'Hunan / &#28246;&#21335;', NCM_txt_domain ),



                'CN20' => __( 'Guangdong / &#24191;&#19996;', NCM_txt_domain ),



                'CN21' => __( 'Guangxi Zhuang / &#24191;&#35199;&#22766;&#26063;', NCM_txt_domain ),



                'CN22' => __( 'Hainan / &#28023;&#21335;', NCM_txt_domain ),



                'CN23' => __( 'Chongqing / &#37325;&#24198;', NCM_txt_domain ),



                'CN24' => __( 'Sichuan / &#22235;&#24029;', NCM_txt_domain ),



                'CN25' => __( 'Guizhou / &#36149;&#24030;', NCM_txt_domain ),



                'CN26' => __( 'Shaanxi / &#38485;&#35199;', NCM_txt_domain ),



                'CN27' => __( 'Gansu / &#29976;&#32899;', NCM_txt_domain ),



                'CN28' => __( 'Qinghai / &#38738;&#28023;', NCM_txt_domain ),



                'CN29' => __( 'Ningxia Hui / &#23425;&#22799;', NCM_txt_domain ),



                'CN30' => __( 'Macau / &#28595;&#38376;', NCM_txt_domain ),



                'CN31' => __( 'Tibet / &#35199;&#34255;', NCM_txt_domain ),



                'CN32' => __( 'Xinjiang / &#26032;&#30086;', NCM_txt_domain ),



            ),



            'ES' => array(



                'C'  => __( 'A Coru&ntilde;a', NCM_txt_domain ),



                'VI' => __( 'Araba/&Aacute;lava', NCM_txt_domain ),



                'AB' => __( 'Albacete', NCM_txt_domain ),



                'A'  => __( 'Alicante', NCM_txt_domain ),



                'AL' => __( 'Almer&iacute;a', NCM_txt_domain ),



                'O'  => __( 'Asturias', NCM_txt_domain ),



                'AV' => __( '&Aacute;vila', NCM_txt_domain ),



                'BA' => __( 'Badajoz', NCM_txt_domain ),



                'PM' => __( 'Baleares', NCM_txt_domain ),



                'B'  => __( 'Barcelona', NCM_txt_domain ),



                'BU' => __( 'Burgos', NCM_txt_domain ),



                'CC' => __( 'C&aacute;ceres', NCM_txt_domain ),



                'CA' => __( 'C&aacute;diz', NCM_txt_domain ),



                'S'  => __( 'Cantabria', NCM_txt_domain ),



                'CS' => __( 'Castell&oacute;n', NCM_txt_domain ),



                'CE' => __( 'Ceuta', NCM_txt_domain ),



                'CR' => __( 'Ciudad Real', NCM_txt_domain ),



                'CO' => __( 'C&oacute;rdoba', NCM_txt_domain ),



                'CU' => __( 'Cuenca', NCM_txt_domain ),



                'GI' => __( 'Girona', NCM_txt_domain ),



                'GR' => __( 'Granada', NCM_txt_domain ),



                'GU' => __( 'Guadalajara', NCM_txt_domain ),



                'SS' => __( 'Gipuzkoa', NCM_txt_domain ),



                'H'  => __( 'Huelva', NCM_txt_domain ),



                'HU' => __( 'Huesca', NCM_txt_domain ),



                'J'  => __( 'Ja&eacute;n', NCM_txt_domain ),



                'LO' => __( 'La Rioja', NCM_txt_domain ),



                'GC' => __( 'Las Palmas', NCM_txt_domain ),



                'LE' => __( 'Le&oacute;n', NCM_txt_domain ),



                'L'  => __( 'Lleida', NCM_txt_domain ),



                'LU' => __( 'Lugo', NCM_txt_domain ),



                'M'  => __( 'Madrid', NCM_txt_domain ),



                'MA' => __( 'M&aacute;laga', NCM_txt_domain ),



                'ML' => __( 'Melilla', NCM_txt_domain ),



                'MU' => __( 'Murcia', NCM_txt_domain ),



                'NA' => __( 'Navarra', NCM_txt_domain ),



                'OR' => __( 'Ourense', NCM_txt_domain ),



                'P'  => __( 'Palencia', NCM_txt_domain ),



                'PO' => __( 'Pontevedra', NCM_txt_domain ),



                'SA' => __( 'Salamanca', NCM_txt_domain ),



                'TF' => __( 'Santa Cruz de Tenerife', NCM_txt_domain ),



                'SG' => __( 'Segovia', NCM_txt_domain ),



                'SE' => __( 'Sevilla', NCM_txt_domain ),



                'SO' => __( 'Soria', NCM_txt_domain ),



                'T'  => __( 'Tarragona', NCM_txt_domain ),



                'TE' => __( 'Teruel', NCM_txt_domain ),



                'TO' => __( 'Toledo', NCM_txt_domain ),



                'V'  => __( 'Valencia', NCM_txt_domain ),



                'VA' => __( 'Valladolid', NCM_txt_domain ),



                'BI' => __( 'Bizkaia', NCM_txt_domain ),



                'ZA' => __( 'Zamora', NCM_txt_domain ),



                'Z'  => __( 'Zaragoza', NCM_txt_domain ),



            ),



            'GR' => array(



                'I' => __( 'Αττική', NCM_txt_domain ),



                'A' => __( 'Ανατολική Μακεδονία και Θράκη', NCM_txt_domain ),



                'B' => __( 'Κεντρική Μακεδονία', NCM_txt_domain ),



                'C' => __( 'Δυτική Μακεδονία', NCM_txt_domain ),



                'D' => __( 'Ήπειρος', NCM_txt_domain ),



                'E' => __( 'Θεσσαλία', NCM_txt_domain ),



                'F' => __( 'Ιόνιοι Νήσοι', NCM_txt_domain ),



                'G' => __( 'Δυτική Ελλάδα', NCM_txt_domain ),



                'H' => __( 'Στερεά Ελλάδα', NCM_txt_domain ),



                'J' => __( 'Πελοπόννησος', NCM_txt_domain ),



                'K' => __( 'Βόρειο Αιγαίο', NCM_txt_domain ),



                'L' => __( 'Νότιο Αιγαίο', NCM_txt_domain ),



                'M' => __( 'Κρήτη', NCM_txt_domain ),



            ),



            'HK' => array(



                'HONG KONG'       => __( 'Hong Kong Island', NCM_txt_domain ),



                'KOWLOON'         => __( 'Kowloon', NCM_txt_domain ),



                'NEW TERRITORIES' => __( 'New Territories', NCM_txt_domain ),



            ),



            'HU' => array(



                'BK' => __( 'Bács-Kiskun', NCM_txt_domain ),



                'BE' => __( 'Békés', NCM_txt_domain ),



                'BA' => __( 'Baranya', NCM_txt_domain ),



                'BZ' => __( 'Borsod-Abaúj-Zemplén', NCM_txt_domain ),



                'BU' => __( 'Budapest', NCM_txt_domain ),



                'CS' => __( 'Csongrád', NCM_txt_domain ),



                'FE' => __( 'Fejér', NCM_txt_domain ),



                'GS' => __( 'Győr-Moson-Sopron', NCM_txt_domain ),



                'HB' => __( 'Hajdú-Bihar', NCM_txt_domain ),



                'HE' => __( 'Heves', NCM_txt_domain ),



                'JN' => __( 'Jász-Nagykun-Szolnok', NCM_txt_domain ),



                'KE' => __( 'Komárom-Esztergom', NCM_txt_domain ),



                'NO' => __( 'Nógrád', NCM_txt_domain ),



                'PE' => __( 'Pest', NCM_txt_domain ),



                'SO' => __( 'Somogy', NCM_txt_domain ),



                'SZ' => __( 'Szabolcs-Szatmár-Bereg', NCM_txt_domain ),



                'TO' => __( 'Tolna', NCM_txt_domain ),



                'VA' => __( 'Vas', NCM_txt_domain ),



                'VE' => __( 'Veszprém', NCM_txt_domain ),



                'ZA' => __( 'Zala', NCM_txt_domain ),



            ),



            'ID' => array(



                'AC' => __( 'Daerah Istimewa Aceh', NCM_txt_domain ),



                'SU' => __( 'Sumatera Utara', NCM_txt_domain ),



                'SB' => __( 'Sumatera Barat', NCM_txt_domain ),



                'RI' => __( 'Riau', NCM_txt_domain ),



                'KR' => __( 'Kepulauan Riau', NCM_txt_domain ),



                'JA' => __( 'Jambi', NCM_txt_domain ),



                'SS' => __( 'Sumatera Selatan', NCM_txt_domain ),



                'BB' => __( 'Bangka Belitung', NCM_txt_domain ),



                'BE' => __( 'Bengkulu', NCM_txt_domain ),



                'LA' => __( 'Lampung', NCM_txt_domain ),



                'JK' => __( 'DKI Jakarta', NCM_txt_domain ),



                'JB' => __( 'Jawa Barat', NCM_txt_domain ),



                'BT' => __( 'Banten', NCM_txt_domain ),



                'JT' => __( 'Jawa Tengah', NCM_txt_domain ),



                'JI' => __( 'Jawa Timur', NCM_txt_domain ),



                'YO' => __( 'Daerah Istimewa Yogyakarta', NCM_txt_domain ),



                'BA' => __( 'Bali', NCM_txt_domain ),



                'NB' => __( 'Nusa Tenggara Barat', NCM_txt_domain ),



                'NT' => __( 'Nusa Tenggara Timur', NCM_txt_domain ),



                'KB' => __( 'Kalimantan Barat', NCM_txt_domain ),



                'KT' => __( 'Kalimantan Tengah', NCM_txt_domain ),



                'KI' => __( 'Kalimantan Timur', NCM_txt_domain ),



                'KS' => __( 'Kalimantan Selatan', NCM_txt_domain ),



                'KU' => __( 'Kalimantan Utara', NCM_txt_domain ),



                'SA' => __( 'Sulawesi Utara', NCM_txt_domain ),



                'ST' => __( 'Sulawesi Tengah', NCM_txt_domain ),



                'SG' => __( 'Sulawesi Tenggara', NCM_txt_domain ),



                'SR' => __( 'Sulawesi Barat', NCM_txt_domain ),



                'SN' => __( 'Sulawesi Selatan', NCM_txt_domain ),



                'GO' => __( 'Gorontalo', NCM_txt_domain ),



                'MA' => __( 'Maluku', NCM_txt_domain ),



                'MU' => __( 'Maluku Utara', NCM_txt_domain ),



                'PA' => __( 'Papua', NCM_txt_domain ),



                'PB' => __( 'Papua Barat', NCM_txt_domain ),



            ),



            'IE' => array(



                'CE' => __( 'Clare', NCM_txt_domain ),



                'CK' => __( 'Cork', NCM_txt_domain ),



                'CN' => __( 'Cavan', NCM_txt_domain ),



                'CW' => __( 'Carlow', NCM_txt_domain ),



                'DL' => __( 'Donegal', NCM_txt_domain ),



                'DN' => __( 'Dublin', NCM_txt_domain ),



                'GY' => __( 'Galway', NCM_txt_domain ),



                'KE' => __( 'Kildare', NCM_txt_domain ),



                'KK' => __( 'Kilkenny', NCM_txt_domain ),



                'KY' => __( 'Kerry', NCM_txt_domain ),



                'LD' => __( 'Longford', NCM_txt_domain ),



                'LH' => __( 'Louth', NCM_txt_domain ),



                'LK' => __( 'Limerick', NCM_txt_domain ),



                'LM' => __( 'Leitrim', NCM_txt_domain ),



                'LS' => __( 'Laois', NCM_txt_domain ),



                'MH' => __( 'Meath', NCM_txt_domain ),



                'MN' => __( 'Monaghan', NCM_txt_domain ),



                'MO' => __( 'Mayo', NCM_txt_domain ),



                'OY' => __( 'Offaly', NCM_txt_domain ),



                'RN' => __( 'Roscommon', NCM_txt_domain ),



                'SO' => __( 'Sligo', NCM_txt_domain ),



                'TY' => __( 'Tipperary', NCM_txt_domain ),



                'WD' => __( 'Waterford', NCM_txt_domain ),



                'WH' => __( 'Westmeath', NCM_txt_domain ),



                'WW' => __( 'Wicklow', NCM_txt_domain ),



                'WX' => __( 'Wexford', NCM_txt_domain ),



            ),



            'IN' => array(



                'AP' => __( 'Andhra Pradesh', NCM_txt_domain ),



                'AR' => __( 'Arunachal Pradesh', NCM_txt_domain ),



                'AS' => __( 'Assam', NCM_txt_domain ),



                'BR' => __( 'Bihar', NCM_txt_domain ),



                'CT' => __( 'Chhattisgarh', NCM_txt_domain ),



                'GA' => __( 'Goa', NCM_txt_domain ),



                'GJ' => __( 'Gujarat', NCM_txt_domain ),



                'HR' => __( 'Haryana', NCM_txt_domain ),



                'HP' => __( 'Himachal Pradesh', NCM_txt_domain ),



                'JK' => __( 'Jammu and Kashmir', NCM_txt_domain ),



                'JH' => __( 'Jharkhand', NCM_txt_domain ),



                'KA' => __( 'Karnataka', NCM_txt_domain ),



                'KL' => __( 'Kerala', NCM_txt_domain ),



                'MP' => __( 'Madhya Pradesh', NCM_txt_domain ),



                'MH' => __( 'Maharashtra', NCM_txt_domain ),



                'MN' => __( 'Manipur', NCM_txt_domain ),



                'ML' => __( 'Meghalaya', NCM_txt_domain ),



                'MZ' => __( 'Mizoram', NCM_txt_domain ),



                'NL' => __( 'Nagaland', NCM_txt_domain ),



                'OR' => __( 'Orissa', NCM_txt_domain ),



                'PB' => __( 'Punjab', NCM_txt_domain ),



                'RJ' => __( 'Rajasthan', NCM_txt_domain ),



                'SK' => __( 'Sikkim', NCM_txt_domain ),



                'TN' => __( 'Tamil Nadu', NCM_txt_domain ),



                'TS' => __( 'Telangana', NCM_txt_domain ),



                'TR' => __( 'Tripura', NCM_txt_domain ),



                'UK' => __( 'Uttarakhand', NCM_txt_domain ),



                'UP' => __( 'Uttar Pradesh', NCM_txt_domain ),



                'WB' => __( 'West Bengal', NCM_txt_domain ),



                'AN' => __( 'Andaman and Nicobar Islands', NCM_txt_domain ),



                'CH' => __( 'Chandigarh', NCM_txt_domain ),



                'DN' => __( 'Dadra and Nagar Haveli', NCM_txt_domain ),



                'DD' => __( 'Daman and Diu', NCM_txt_domain ),



                'DL' => __( 'Delhi', NCM_txt_domain ),



                'LD' => __( 'Lakshadeep', NCM_txt_domain ),



                'PY' => __( 'Pondicherry (Puducherry)', NCM_txt_domain ),



            ),



            'IR' => array(



                'KHZ' => __( 'Khuzestan  (خوزستان)', NCM_txt_domain ),



                'THR' => __( 'Tehran  (تهران)', NCM_txt_domain ),



                'ILM' => __( 'Ilaam (ایلام)', NCM_txt_domain ),



                'BHR' => __( 'Bushehr (بوشهر)', NCM_txt_domain ),



                'ADL' => __( 'Ardabil (اردبیل)', NCM_txt_domain ),



                'ESF' => __( 'Isfahan (اصفهان)', NCM_txt_domain ),



                'YZD' => __( 'Yazd (یزد)', NCM_txt_domain ),



                'KRH' => __( 'Kermanshah (کرمانشاه)', NCM_txt_domain ),



                'KRN' => __( 'Kerman (کرمان)', NCM_txt_domain ),



                'HDN' => __( 'Hamadan (همدان)', NCM_txt_domain ),



                'GZN' => __( 'Ghazvin (قزوین)', NCM_txt_domain ),



                'ZJN' => __( 'Zanjan (زنجان)', NCM_txt_domain ),



                'LRS' => __( 'Luristan (لرستان)', NCM_txt_domain ),



                'ABZ' => __( 'Alborz (البرز)', NCM_txt_domain ),



                'EAZ' => __( 'East Azarbaijan (آذربایجان شرقی)', NCM_txt_domain ),



                'WAZ' => __( 'West Azarbaijan (آذربایجان غربی)', NCM_txt_domain ),



                'CHB' => __( 'Chaharmahal and Bakhtiari (چهارمحال و بختیاری)', NCM_txt_domain ),



                'SKH' => __( 'South Khorasan (خراسان جنوبی)', NCM_txt_domain ),



                'RKH' => __( 'Razavi Khorasan (خراسان رضوی)', NCM_txt_domain ),



                'NKH' => __( 'North Khorasan (خراسان جنوبی)', NCM_txt_domain ),



                'SMN' => __( 'Semnan (سمنان)', NCM_txt_domain ),



                'FRS' => __( 'Fars (فارس)', NCM_txt_domain ),



                'QHM' => __( 'Qom (قم)', NCM_txt_domain ),



                'KRD' => __( 'Kurdistan / کردستان)', NCM_txt_domain ),



                'KBD' => __( 'Kohgiluyeh and BoyerAhmad (کهگیلوییه و بویراحمد)', NCM_txt_domain ),



                'GLS' => __( 'Golestan (گلستان)', NCM_txt_domain ),



                'GIL' => __( 'Gilan (گیلان)', NCM_txt_domain ),



                'MZN' => __( 'Mazandaran (مازندران)', NCM_txt_domain ),



                'MKZ' => __( 'Markazi (مرکزی)', NCM_txt_domain ),



                'HRZ' => __( 'Hormozgan (هرمزگان)', NCM_txt_domain ),



                'SBN' => __( 'Sistan and Baluchestan (سیستان و بلوچستان)', NCM_txt_domain ),



            ),



            'IT' => array(



                'AG' => __( 'Agrigento', NCM_txt_domain ),



                'AL' => __( 'Alessandria', NCM_txt_domain ),



                'AN' => __( 'Ancona', NCM_txt_domain ),



                'AO' => __( 'Aosta', NCM_txt_domain ),



                'AR' => __( 'Arezzo', NCM_txt_domain ),



                'AP' => __( 'Ascoli Piceno', NCM_txt_domain ),



                'AT' => __( 'Asti', NCM_txt_domain ),



                'AV' => __( 'Avellino', NCM_txt_domain ),



                'BA' => __( 'Bari', NCM_txt_domain ),



                'BT' => __( 'Barletta-Andria-Trani', NCM_txt_domain ),



                'BL' => __( 'Belluno', NCM_txt_domain ),



                'BN' => __( 'Benevento', NCM_txt_domain ),



                'BG' => __( 'Bergamo', NCM_txt_domain ),



                'BI' => __( 'Biella', NCM_txt_domain ),



                'BO' => __( 'Bologna', NCM_txt_domain ),



                'BZ' => __( 'Bolzano', NCM_txt_domain ),



                'BS' => __( 'Brescia', NCM_txt_domain ),



                'BR' => __( 'Brindisi', NCM_txt_domain ),



                'CA' => __( 'Cagliari', NCM_txt_domain ),



                'CL' => __( 'Caltanissetta', NCM_txt_domain ),



                'CB' => __( 'Campobasso', NCM_txt_domain ),



                'CI' => __( 'Carbonia-Iglesias', NCM_txt_domain ),



                'CE' => __( 'Caserta', NCM_txt_domain ),



                'CT' => __( 'Catania', NCM_txt_domain ),



                'CZ' => __( 'Catanzaro', NCM_txt_domain ),



                'CH' => __( 'Chieti', NCM_txt_domain ),



                'CO' => __( 'Como', NCM_txt_domain ),



                'CS' => __( 'Cosenza', NCM_txt_domain ),



                'CR' => __( 'Cremona', NCM_txt_domain ),



                'KR' => __( 'Crotone', NCM_txt_domain ),



                'CN' => __( 'Cuneo', NCM_txt_domain ),



                'EN' => __( 'Enna', NCM_txt_domain ),



                'FM' => __( 'Fermo', NCM_txt_domain ),



                'FE' => __( 'Ferrara', NCM_txt_domain ),



                'FI' => __( 'Firenze', NCM_txt_domain ),



                'FG' => __( 'Foggia', NCM_txt_domain ),



                'FC' => __( 'Forlì-Cesena', NCM_txt_domain ),



                'FR' => __( 'Frosinone', NCM_txt_domain ),



                'GE' => __( 'Genova', NCM_txt_domain ),



                'GO' => __( 'Gorizia', NCM_txt_domain ),



                'GR' => __( 'Grosseto', NCM_txt_domain ),



                'IM' => __( 'Imperia', NCM_txt_domain ),



                'IS' => __( 'Isernia', NCM_txt_domain ),



                'SP' => __( 'La Spezia', NCM_txt_domain ),



                'AQ' => __( "L'Aquila", NCM_txt_domain ),



                'LT' => __( 'Latina', NCM_txt_domain ),



                'LE' => __( 'Lecce', NCM_txt_domain ),



                'LC' => __( 'Lecco', NCM_txt_domain ),



                'LI' => __( 'Livorno', NCM_txt_domain ),



                'LO' => __( 'Lodi', NCM_txt_domain ),



                'LU' => __( 'Lucca', NCM_txt_domain ),



                'MC' => __( 'Macerata', NCM_txt_domain ),



                'MN' => __( 'Mantova', NCM_txt_domain ),



                'MS' => __( 'Massa-Carrara', NCM_txt_domain ),



                'MT' => __( 'Matera', NCM_txt_domain ),



                'ME' => __( 'Messina', NCM_txt_domain ),



                'MI' => __( 'Milano', NCM_txt_domain ),



                'MO' => __( 'Modena', NCM_txt_domain ),



                'MB' => __( 'Monza e della Brianza', NCM_txt_domain ),



                'NA' => __( 'Napoli', NCM_txt_domain ),



                'NO' => __( 'Novara', NCM_txt_domain ),



                'NU' => __( 'Nuoro', NCM_txt_domain ),



                'OT' => __( 'Olbia-Tempio', NCM_txt_domain ),



                'OR' => __( 'Oristano', NCM_txt_domain ),



                'PD' => __( 'Padova', NCM_txt_domain ),



                'PA' => __( 'Palermo', NCM_txt_domain ),



                'PR' => __( 'Parma', NCM_txt_domain ),



                'PV' => __( 'Pavia', NCM_txt_domain ),



                'PG' => __( 'Perugia', NCM_txt_domain ),



                'PU' => __( 'Pesaro e Urbino', NCM_txt_domain ),



                'PE' => __( 'Pescara', NCM_txt_domain ),



                'PC' => __( 'Piacenza', NCM_txt_domain ),



                'PI' => __( 'Pisa', NCM_txt_domain ),



                'PT' => __( 'Pistoia', NCM_txt_domain ),



                'PN' => __( 'Pordenone', NCM_txt_domain ),



                'PZ' => __( 'Potenza', NCM_txt_domain ),



                'PO' => __( 'Prato', NCM_txt_domain ),



                'RG' => __( 'Ragusa', NCM_txt_domain ),



                'RA' => __( 'Ravenna', NCM_txt_domain ),



                'RC' => __( 'Reggio Calabria', NCM_txt_domain ),



                'RE' => __( 'Reggio Emilia', NCM_txt_domain ),



                'RI' => __( 'Rieti', NCM_txt_domain ),



                'RN' => __( 'Rimini', NCM_txt_domain ),



                'RM' => __( 'Roma', NCM_txt_domain ),



                'RO' => __( 'Rovigo', NCM_txt_domain ),



                'SA' => __( 'Salerno', NCM_txt_domain ),



                'VS' => __( 'Medio Campidano', NCM_txt_domain ),



                'SS' => __( 'Sassari', NCM_txt_domain ),



                'SV' => __( 'Savona', NCM_txt_domain ),



                'SI' => __( 'Siena', NCM_txt_domain ),



                'SR' => __( 'Siracusa', NCM_txt_domain ),



                'SO' => __( 'Sondrio', NCM_txt_domain ),



                'TA' => __( 'Taranto', NCM_txt_domain ),



                'TE' => __( 'Teramo', NCM_txt_domain ),



                'TR' => __( 'Terni', NCM_txt_domain ),



                'TO' => __( 'Torino', NCM_txt_domain ),



                'OG' => __( 'Ogliastra', NCM_txt_domain ),



                'TP' => __( 'Trapani', NCM_txt_domain ),



                'TN' => __( 'Trento', NCM_txt_domain ),



                'TV' => __( 'Treviso', NCM_txt_domain ),



                'TS' => __( 'Trieste', NCM_txt_domain ),



                'UD' => __( 'Udine', NCM_txt_domain ),



                'VA' => __( 'Varese', NCM_txt_domain ),



                'VE' => __( 'Venezia', NCM_txt_domain ),



                'VB' => __( 'Verbano-Cusio-Ossola', NCM_txt_domain ),



                'VC' => __( 'Vercelli', NCM_txt_domain ),



                'VR' => __( 'Verona', NCM_txt_domain ),



                'VV' => __( 'Vibo Valentia', NCM_txt_domain ),



                'VI' => __( 'Vicenza', NCM_txt_domain ),



                'VT' => __( 'Viterbo', NCM_txt_domain ),



            ),



            'JP' => array(



                'JP01' => __( 'Hokkaido-dō', NCM_txt_domain ),



                'JP02' => __( 'Aomori-ken', NCM_txt_domain ),



                'JP03' => __( 'Iwate-ken', NCM_txt_domain ),



                'JP04' => __( 'Miyagi-ken', NCM_txt_domain ),



                'JP05' => __( 'Akita-ken', NCM_txt_domain ),



                'JP06' => __( 'Yamagata-ken', NCM_txt_domain ),



                'JP07' => __( 'Fukushima-ken', NCM_txt_domain ),



                'JP08' => __( 'Ibaraki-ken', NCM_txt_domain ),



                'JP09' => __( 'Tochigi-ken', NCM_txt_domain ),



                'JP10' => __( 'Gunma-ken', NCM_txt_domain ),



                'JP11' => __( 'Saitama-ken', NCM_txt_domain ),



                'JP12' => __( 'Chiba-ken', NCM_txt_domain ),



                'JP13' => __( 'Tokyo-to', NCM_txt_domain ),



                'JP14' => __( 'Kanagawa-ken', NCM_txt_domain ),



                'JP15' => __( 'Niigata-ken', NCM_txt_domain ),



                'JP16' => __( 'Toyama-ken', NCM_txt_domain ),



                'JP17' => __( 'Ishikawa-ken', NCM_txt_domain ),



                'JP18' => __( 'Fukui-ken', NCM_txt_domain ),



                'JP19' => __( 'Yamanashi-ken', NCM_txt_domain ),



                'JP20' => __( 'Nagano-ken', NCM_txt_domain ),



                'JP21' => __( 'Gifu-ken', NCM_txt_domain ),



                'JP22' => __( 'Shizuoka-ken', NCM_txt_domain ),



                'JP23' => __( 'Aichi-ken', NCM_txt_domain ),



                'JP24' => __( 'Mie-ken', NCM_txt_domain ),



                'JP25' => __( 'Shiga-ken', NCM_txt_domain ),



                'JP26' => __( 'Kyoto-fu', NCM_txt_domain ),



                'JP27' => __( 'Osaka-fu', NCM_txt_domain ),



                'JP28' => __( 'Hyogo-ken', NCM_txt_domain ),



                'JP29' => __( 'Nara-ken', NCM_txt_domain ),



                'JP30' => __( 'Wakayama-ken', NCM_txt_domain ),



                'JP31' => __( 'Tottori-ken', NCM_txt_domain ),



                'JP32' => __( 'Shimane-ken', NCM_txt_domain ),



                'JP33' => __( 'Okayama-ken', NCM_txt_domain ),



                'JP34' => __( 'Hiroshima-ken', NCM_txt_domain ),



                'JP35' => __( 'Yamaguchi-ken', NCM_txt_domain ),



                'JP36' => __( 'Tokushima-ken', NCM_txt_domain ),



                'JP37' => __( 'Kagawa-ken', NCM_txt_domain ),



                'JP38' => __( 'Ehime-ken', NCM_txt_domain ),



                'JP39' => __( 'Kochi-ken', NCM_txt_domain ),



                'JP40' => __( 'Fukuoka-ken', NCM_txt_domain ),



                'JP41' => __( 'Saga-ken', NCM_txt_domain ),



                'JP42' => __( 'Nagasaki-ken', NCM_txt_domain ),



                'JP43' => __( 'Kumamoto-ken', NCM_txt_domain ),



                'JP44' => __( 'Oita-ken', NCM_txt_domain ),



                'JP45' => __( 'Miyazaki-ken', NCM_txt_domain ),



                'JP46' => __( 'Kagoshima-ken', NCM_txt_domain ),



                'JP47' => __( 'Okinawa-ken', NCM_txt_domain ),



            ),



            'MX' => array(



                'DF' => __( 'Ciudad de M&eacute;xico', NCM_txt_domain ),



                'JA' => __( 'Jalisco', NCM_txt_domain ),



                'NL' => __( 'Nuevo Le&oacute;n', NCM_txt_domain ),



                'AG' => __( 'Aguascalientes', NCM_txt_domain ),



                'BC' => __( 'Baja California', NCM_txt_domain ),



                'BS' => __( 'Baja California Sur', NCM_txt_domain ),



                'CM' => __( 'Campeche', NCM_txt_domain ),



                'CS' => __( 'Chiapas', NCM_txt_domain ),



                'CH' => __( 'Chihuahua', NCM_txt_domain ),



                'CO' => __( 'Coahuila', NCM_txt_domain ),



                'CL' => __( 'Colima', NCM_txt_domain ),



                'DG' => __( 'Durango', NCM_txt_domain ),



                'GT' => __( 'Guanajuato', NCM_txt_domain ),



                'GR' => __( 'Guerrero', NCM_txt_domain ),



                'HG' => __( 'Hidalgo', NCM_txt_domain ),



                'MX' => __( 'Estado de M&eacute;xico', NCM_txt_domain ),



                'MI' => __( 'Michoac&aacute;n', NCM_txt_domain ),



                'MO' => __( 'Morelos', NCM_txt_domain ),



                'NA' => __( 'Nayarit', NCM_txt_domain ),



                'OA' => __( 'Oaxaca', NCM_txt_domain ),



                'PU' => __( 'Puebla', NCM_txt_domain ),



                'QT' => __( 'Quer&eacute;taro', NCM_txt_domain ),



                'QR' => __( 'Quintana Roo', NCM_txt_domain ),



                'SL' => __( 'San Luis Potos&iacute;', NCM_txt_domain ),



                'SI' => __( 'Sinaloa', NCM_txt_domain ),



                'SO' => __( 'Sonora', NCM_txt_domain ),



                'TB' => __( 'Tabasco', NCM_txt_domain ),



                'TM' => __( 'Tamaulipas', NCM_txt_domain ),



                'TL' => __( 'Tlaxcala', NCM_txt_domain ),



                'VE' => __( 'Veracruz', NCM_txt_domain ),



                'YU' => __( 'Yucat&aacute;n', NCM_txt_domain ),



                'ZA' => __( 'Zacatecas', NCM_txt_domain ),



            ),



            'MY' => array(



                'JHR' => __( 'Johor', NCM_txt_domain ),



                'KDH' => __( 'Kedah', NCM_txt_domain ),



                'KTN' => __( 'Kelantan', NCM_txt_domain ),



                'LBN' => __( 'Labuan', NCM_txt_domain ),



                'MLK' => __( 'Malacca (Melaka)', NCM_txt_domain ),



                'NSN' => __( 'Negeri Sembilan', NCM_txt_domain ),



                'PHG' => __( 'Pahang', NCM_txt_domain ),



                'PNG' => __( 'Penang (Pulau Pinang)', NCM_txt_domain ),



                'PRK' => __( 'Perak', NCM_txt_domain ),



                'PLS' => __( 'Perlis', NCM_txt_domain ),



                'SBH' => __( 'Sabah', NCM_txt_domain ),



                'SWK' => __( 'Sarawak', NCM_txt_domain ),



                'SGR' => __( 'Selangor', NCM_txt_domain ),



                'TRG' => __( 'Terengganu', NCM_txt_domain ),



                'PJY' => __( 'Putrajaya', NCM_txt_domain ),



                'KUL' => __( 'Kuala Lumpur', NCM_txt_domain ),



            ),



            'NG' => array(



                'AB' => __( 'Abia', NCM_txt_domain ),



                'FC' => __( 'Abuja', NCM_txt_domain ),



                'AD' => __( 'Adamawa', NCM_txt_domain ),



                'AK' => __( 'Akwa Ibom', NCM_txt_domain ),



                'AN' => __( 'Anambra', NCM_txt_domain ),



                'BA' => __( 'Bauchi', NCM_txt_domain ),



                'BY' => __( 'Bayelsa', NCM_txt_domain ),



                'BE' => __( 'Benue', NCM_txt_domain ),



                'BO' => __( 'Borno', NCM_txt_domain ),



                'CR' => __( 'Cross River', NCM_txt_domain ),



                'DE' => __( 'Delta', NCM_txt_domain ),



                'EB' => __( 'Ebonyi', NCM_txt_domain ),



                'ED' => __( 'Edo', NCM_txt_domain ),



                'EK' => __( 'Ekiti', NCM_txt_domain ),



                'EN' => __( 'Enugu', NCM_txt_domain ),



                'GO' => __( 'Gombe', NCM_txt_domain ),



                'IM' => __( 'Imo', NCM_txt_domain ),



                'JI' => __( 'Jigawa', NCM_txt_domain ),



                'KD' => __( 'Kaduna', NCM_txt_domain ),



                'KN' => __( 'Kano', NCM_txt_domain ),



                'KT' => __( 'Katsina', NCM_txt_domain ),



                'KE' => __( 'Kebbi', NCM_txt_domain ),



                'KO' => __( 'Kogi', NCM_txt_domain ),



                'KW' => __( 'Kwara', NCM_txt_domain ),



                'LA' => __( 'Lagos', NCM_txt_domain ),



                'NA' => __( 'Nasarawa', NCM_txt_domain ),



                'NI' => __( 'Niger', NCM_txt_domain ),



                'OG' => __( 'Ogun', NCM_txt_domain ),



                'ON' => __( 'Ondo', NCM_txt_domain ),



                'OS' => __( 'Osun', NCM_txt_domain ),



                'OY' => __( 'Oyo', NCM_txt_domain ),



                'PL' => __( 'Plateau', NCM_txt_domain ),



                'RI' => __( 'Rivers', NCM_txt_domain ),



                'SO' => __( 'Sokoto', NCM_txt_domain ),



                'TA' => __( 'Taraba', NCM_txt_domain ),



                'YO' => __( 'Yobe', NCM_txt_domain ),



                'ZA' => __( 'Zamfara', NCM_txt_domain ),



            ),



            'NP' => array(



                'BAG' => __( 'Bagmati', NCM_txt_domain ),



                'BHE' => __( 'Bheri', NCM_txt_domain ),



                'DHA' => __( 'Dhaulagiri', NCM_txt_domain ),



                'GAN' => __( 'Gandaki', NCM_txt_domain ),



                'JAN' => __( 'Janakpur', NCM_txt_domain ),



                'KAR' => __( 'Karnali', NCM_txt_domain ),



                'KOS' => __( 'Koshi', NCM_txt_domain ),



                'LUM' => __( 'Lumbini', NCM_txt_domain ),



                'MAH' => __( 'Mahakali', NCM_txt_domain ),



                'MEC' => __( 'Mechi', NCM_txt_domain ),



                'NAR' => __( 'Narayani', NCM_txt_domain ),



                'RAP' => __( 'Rapti', NCM_txt_domain ),



                'SAG' => __( 'Sagarmatha', NCM_txt_domain ),



                'SET' => __( 'Seti', NCM_txt_domain ),



            ),



            'NZ' => array(



                'NL' => __( 'Northland', NCM_txt_domain ),



                'AK' => __( 'Auckland', NCM_txt_domain ),



                'WA' => __( 'Waikato', NCM_txt_domain ),



                'BP' => __( 'Bay of Plenty', NCM_txt_domain ),



                'TK' => __( 'Taranaki', NCM_txt_domain ),



                'GI' => __( 'Gisborne', NCM_txt_domain ),



                'HB' => __( 'Hawke&rsquo;s Bay', NCM_txt_domain ),



                'MW' => __( 'Manawatu-Wanganui', NCM_txt_domain ),



                'WE' => __( 'Wellington', NCM_txt_domain ),



                'NS' => __( 'Nelson', NCM_txt_domain ),



                'MB' => __( 'Marlborough', NCM_txt_domain ),



                'TM' => __( 'Tasman', NCM_txt_domain ),



                'WC' => __( 'West Coast', NCM_txt_domain ),



                'CT' => __( 'Canterbury', NCM_txt_domain ),



                'OT' => __( 'Otago', NCM_txt_domain ),



                'SL' => __( 'Southland', NCM_txt_domain ),



            ),



            'PE' => array(



                'CAL' => __( 'El Callao', NCM_txt_domain ),



                'LMA' => __( 'Municipalidad Metropolitana de Lima', NCM_txt_domain ),



                'AMA' => __( 'Amazonas', NCM_txt_domain ),



                'ANC' => __( 'Ancash', NCM_txt_domain ),



                'APU' => __( 'Apur&iacute;mac', NCM_txt_domain ),



                'ARE' => __( 'Arequipa', NCM_txt_domain ),



                'AYA' => __( 'Ayacucho', NCM_txt_domain ),



                'CAJ' => __( 'Cajamarca', NCM_txt_domain ),



                'CUS' => __( 'Cusco', NCM_txt_domain ),



                'HUV' => __( 'Huancavelica', NCM_txt_domain ),



                'HUC' => __( 'Hu&aacute;nuco', NCM_txt_domain ),



                'ICA' => __( 'Ica', NCM_txt_domain ),



                'JUN' => __( 'Jun&iacute;n', NCM_txt_domain ),



                'LAL' => __( 'La Libertad', NCM_txt_domain ),



                'LAM' => __( 'Lambayeque', NCM_txt_domain ),



                'LIM' => __( 'Lima', NCM_txt_domain ),



                'LOR' => __( 'Loreto', NCM_txt_domain ),



                'MDD' => __( 'Madre de Dios', NCM_txt_domain ),



                'MOQ' => __( 'Moquegua', NCM_txt_domain ),



                'PAS' => __( 'Pasco', NCM_txt_domain ),



                'PIU' => __( 'Piura', NCM_txt_domain ),



                'PUN' => __( 'Puno', NCM_txt_domain ),



                'SAM' => __( 'San Mart&iacute;n', NCM_txt_domain ),



                'TAC' => __( 'Tacna', NCM_txt_domain ),



                'TUM' => __( 'Tumbes', NCM_txt_domain ),



                'UCA' => __( 'Ucayali', NCM_txt_domain ),



            ),



            'PH' => array(



                'ABR' => __( 'Abra', NCM_txt_domain ),



                'AGN' => __( 'Agusan del Norte', NCM_txt_domain ),



                'AGS' => __( 'Agusan del Sur', NCM_txt_domain ),



                'AKL' => __( 'Aklan', NCM_txt_domain ),



                'ALB' => __( 'Albay', NCM_txt_domain ),



                'ANT' => __( 'Antique', NCM_txt_domain ),



                'APA' => __( 'Apayao', NCM_txt_domain ),



                'AUR' => __( 'Aurora', NCM_txt_domain ),



                'BAS' => __( 'Basilan', NCM_txt_domain ),



                'BAN' => __( 'Bataan', NCM_txt_domain ),



                'BTN' => __( 'Batanes', NCM_txt_domain ),



                'BTG' => __( 'Batangas', NCM_txt_domain ),



                'BEN' => __( 'Benguet', NCM_txt_domain ),



                'BIL' => __( 'Biliran', NCM_txt_domain ),



                'BOH' => __( 'Bohol', NCM_txt_domain ),



                'BUK' => __( 'Bukidnon', NCM_txt_domain ),



                'BUL' => __( 'Bulacan', NCM_txt_domain ),



                'CAG' => __( 'Cagayan', NCM_txt_domain ),



                'CAN' => __( 'Camarines Norte', NCM_txt_domain ),



                'CAS' => __( 'Camarines Sur', NCM_txt_domain ),



                'CAM' => __( 'Camiguin', NCM_txt_domain ),



                'CAP' => __( 'Capiz', NCM_txt_domain ),



                'CAT' => __( 'Catanduanes', NCM_txt_domain ),



                'CAV' => __( 'Cavite', NCM_txt_domain ),



                'CEB' => __( 'Cebu', NCM_txt_domain ),



                'COM' => __( 'Compostela Valley', NCM_txt_domain ),



                'NCO' => __( 'Cotabato', NCM_txt_domain ),



                'DAV' => __( 'Davao del Norte', NCM_txt_domain ),



                'DAS' => __( 'Davao del Sur', NCM_txt_domain ),



                'DAC' => __( 'Davao Occidental', NCM_txt_domain ), // TODO: Needs to be updated when ISO code is assigned



                'DAO' => __( 'Davao Oriental', NCM_txt_domain ),



                'DIN' => __( 'Dinagat Islands', NCM_txt_domain ),



                'EAS' => __( 'Eastern Samar', NCM_txt_domain ),



                'GUI' => __( 'Guimaras', NCM_txt_domain ),



                'IFU' => __( 'Ifugao', NCM_txt_domain ),



                'ILN' => __( 'Ilocos Norte', NCM_txt_domain ),



                'ILS' => __( 'Ilocos Sur', NCM_txt_domain ),



                'ILI' => __( 'Iloilo', NCM_txt_domain ),



                'ISA' => __( 'Isabela', NCM_txt_domain ),



                'KAL' => __( 'Kalinga', NCM_txt_domain ),



                'LUN' => __( 'La Union', NCM_txt_domain ),



                'LAG' => __( 'Laguna', NCM_txt_domain ),



                'LAN' => __( 'Lanao del Norte', NCM_txt_domain ),



                'LAS' => __( 'Lanao del Sur', NCM_txt_domain ),



                'LEY' => __( 'Leyte', NCM_txt_domain ),



                'MAG' => __( 'Maguindanao', NCM_txt_domain ),



                'MAD' => __( 'Marinduque', NCM_txt_domain ),



                'MAS' => __( 'Masbate', NCM_txt_domain ),



                'MSC' => __( 'Misamis Occidental', NCM_txt_domain ),



                'MSR' => __( 'Misamis Oriental', NCM_txt_domain ),



                'MOU' => __( 'Mountain Province', NCM_txt_domain ),



                'NEC' => __( 'Negros Occidental', NCM_txt_domain ),



                'NER' => __( 'Negros Oriental', NCM_txt_domain ),



                'NSA' => __( 'Northern Samar', NCM_txt_domain ),



                'NUE' => __( 'Nueva Ecija', NCM_txt_domain ),



                'NUV' => __( 'Nueva Vizcaya', NCM_txt_domain ),



                'MDC' => __( 'Occidental Mindoro', NCM_txt_domain ),



                'MDR' => __( 'Oriental Mindoro', NCM_txt_domain ),



                'PLW' => __( 'Palawan', NCM_txt_domain ),



                'PAM' => __( 'Pampanga', NCM_txt_domain ),



                'PAN' => __( 'Pangasinan', NCM_txt_domain ),



                'QUE' => __( 'Quezon', NCM_txt_domain ),



                'QUI' => __( 'Quirino', NCM_txt_domain ),



                'RIZ' => __( 'Rizal', NCM_txt_domain ),



                'ROM' => __( 'Romblon', NCM_txt_domain ),



                'WSA' => __( 'Samar', NCM_txt_domain ),



                'SAR' => __( 'Sarangani', NCM_txt_domain ),



                'SIQ' => __( 'Siquijor', NCM_txt_domain ),



                'SOR' => __( 'Sorsogon', NCM_txt_domain ),



                'SCO' => __( 'South Cotabato', NCM_txt_domain ),



                'SLE' => __( 'Southern Leyte', NCM_txt_domain ),



                'SUK' => __( 'Sultan Kudarat', NCM_txt_domain ),



                'SLU' => __( 'Sulu', NCM_txt_domain ),



                'SUN' => __( 'Surigao del Norte', NCM_txt_domain ),



                'SUR' => __( 'Surigao del Sur', NCM_txt_domain ),



                'TAR' => __( 'Tarlac', NCM_txt_domain ),



                'TAW' => __( 'Tawi-Tawi', NCM_txt_domain ),



                'ZMB' => __( 'Zambales', NCM_txt_domain ),



                'ZAN' => __( 'Zamboanga del Norte', NCM_txt_domain ),



                'ZAS' => __( 'Zamboanga del Sur', NCM_txt_domain ),



                'ZSI' => __( 'Zamboanga Sibugay', NCM_txt_domain ),



                '00'  => __( 'Metro Manila', NCM_txt_domain ),



            ),



            'PK' => array(



                'JK' => __( 'Azad Kashmir', NCM_txt_domain ),



                'BA' => __( 'Balochistan', NCM_txt_domain ),



                'TA' => __( 'FATA', NCM_txt_domain ),



                'GB' => __( 'Gilgit Baltistan', NCM_txt_domain ),



                'IS' => __( 'Islamabad Capital Territory', NCM_txt_domain ),



                'KP' => __( 'Khyber Pakhtunkhwa', NCM_txt_domain ),



                'PB' => __( 'Punjab', NCM_txt_domain ),



                'SD' => __( 'Sindh', NCM_txt_domain ),



            ),



            'RO' => array(



                'AB' => __( 'Alba' , NCM_txt_domain ),



                'AR' => __( 'Arad' , NCM_txt_domain ),



                'AG' => __( 'Arges' , NCM_txt_domain ),



                'BC' => __( 'Bacau' , NCM_txt_domain ),



                'BH' => __( 'Bihor' , NCM_txt_domain ),



                'BN' => __( 'Bistrita-Nasaud' , NCM_txt_domain ),



                'BT' => __( 'Botosani' , NCM_txt_domain ),



                'BR' => __( 'Braila' , NCM_txt_domain ),



                'BV' => __( 'Brasov' , NCM_txt_domain ),



                'B'  => __( 'Bucuresti' , NCM_txt_domain ),



                'BZ' => __( 'Buzau' , NCM_txt_domain ),



                'CL' => __( 'Calarasi' , NCM_txt_domain ),



                'CS' => __( 'Caras-Severin' , NCM_txt_domain ),



                'CJ' => __( 'Cluj' , NCM_txt_domain ),



                'CT' => __( 'Constanta' , NCM_txt_domain ),



                'CV' => __( 'Covasna' , NCM_txt_domain ),



                'DB' => __( 'Dambovita' , NCM_txt_domain ),



                'DJ' => __( 'Dolj' , NCM_txt_domain ),



                'GL' => __( 'Galati' , NCM_txt_domain ),



                'GR' => __( 'Giurgiu' , NCM_txt_domain ),



                'GJ' => __( 'Gorj' , NCM_txt_domain ),



                'HR' => __( 'Harghita' , NCM_txt_domain ),



                'HD' => __( 'Hunedoara' , NCM_txt_domain ),



                'IL' => __( 'Ialomita' , NCM_txt_domain ),



                'IS' => __( 'Iasi' , NCM_txt_domain ),



                'IF' => __( 'Ilfov' , NCM_txt_domain ),



                'MM' => __( 'Maramures' , NCM_txt_domain ),



                'MH' => __( 'Mehedinti' , NCM_txt_domain ),



                'MS' => __( 'Mures' , NCM_txt_domain ),



                'NT' => __( 'Neamt' , NCM_txt_domain ),



                'OT' => __( 'Olt' , NCM_txt_domain ),



                'PH' => __( 'Prahova' , NCM_txt_domain ),



                'SJ' => __( 'Salaj' , NCM_txt_domain ),



                'SM' => __( 'Satu Mare' , NCM_txt_domain ),



                'SB' => __( 'Sibiu' , NCM_txt_domain ),



                'SV' => __( 'Suceava' , NCM_txt_domain ),



                'TR' => __( 'Teleorman' , NCM_txt_domain ),



                'TM' => __( 'Timis' , NCM_txt_domain ),



                'TL' => __( 'Tulcea' , NCM_txt_domain ),



                'VL' => __( 'Valcea' , NCM_txt_domain ),



                'VS' => __( 'Vaslui' , NCM_txt_domain ),



                'VN' => __( 'Vrancea' , NCM_txt_domain ),



            ),



            'TH' => array(



                'TH-37' => __( 'Amnat Charoen (&#3629;&#3635;&#3609;&#3634;&#3592;&#3648;&#3592;&#3619;&#3636;&#3597;)', NCM_txt_domain ),



                'TH-15' => __( 'Ang Thong (&#3629;&#3656;&#3634;&#3591;&#3607;&#3629;&#3591;)', NCM_txt_domain ),



                'TH-14' => __( 'Ayutthaya (&#3614;&#3619;&#3632;&#3609;&#3588;&#3619;&#3624;&#3619;&#3637;&#3629;&#3618;&#3640;&#3608;&#3618;&#3634;)', NCM_txt_domain ),



                'TH-10' => __( 'Bangkok (&#3585;&#3619;&#3640;&#3591;&#3648;&#3607;&#3614;&#3617;&#3627;&#3634;&#3609;&#3588;&#3619;)', NCM_txt_domain ),



                'TH-38' => __( 'Bueng Kan (&#3610;&#3638;&#3591;&#3585;&#3634;&#3628;)', NCM_txt_domain ),



                'TH-31' => __( 'Buri Ram (&#3610;&#3640;&#3619;&#3637;&#3619;&#3633;&#3617;&#3618;&#3660;)', NCM_txt_domain ),



                'TH-24' => __( 'Chachoengsao (&#3593;&#3632;&#3648;&#3594;&#3636;&#3591;&#3648;&#3607;&#3619;&#3634;)', NCM_txt_domain ),



                'TH-18' => __( 'Chai Nat (&#3594;&#3633;&#3618;&#3609;&#3634;&#3607;)', NCM_txt_domain ),



                'TH-36' => __( 'Chaiyaphum (&#3594;&#3633;&#3618;&#3616;&#3641;&#3617;&#3636;)', NCM_txt_domain ),



                'TH-22' => __( 'Chanthaburi (&#3592;&#3633;&#3609;&#3607;&#3610;&#3640;&#3619;&#3637;)', NCM_txt_domain ),



                'TH-50' => __( 'Chiang Mai (&#3648;&#3594;&#3637;&#3618;&#3591;&#3651;&#3627;&#3617;&#3656;)', NCM_txt_domain ),



                'TH-57' => __( 'Chiang Rai (&#3648;&#3594;&#3637;&#3618;&#3591;&#3619;&#3634;&#3618;)', NCM_txt_domain ),



                'TH-20' => __( 'Chonburi (&#3594;&#3621;&#3610;&#3640;&#3619;&#3637;)', NCM_txt_domain ),



                'TH-86' => __( 'Chumphon (&#3594;&#3640;&#3617;&#3614;&#3619;)', NCM_txt_domain ),



                'TH-46' => __( 'Kalasin (&#3585;&#3634;&#3628;&#3626;&#3636;&#3609;&#3608;&#3640;&#3660;)', NCM_txt_domain ),



                'TH-62' => __( 'Kamphaeng Phet (&#3585;&#3635;&#3649;&#3614;&#3591;&#3648;&#3614;&#3594;&#3619;)', NCM_txt_domain ),



                'TH-71' => __( 'Kanchanaburi (&#3585;&#3634;&#3597;&#3592;&#3609;&#3610;&#3640;&#3619;&#3637;)', NCM_txt_domain ),



                'TH-40' => __( 'Khon Kaen (&#3586;&#3629;&#3609;&#3649;&#3585;&#3656;&#3609;)', NCM_txt_domain ),



                'TH-81' => __( 'Krabi (&#3585;&#3619;&#3632;&#3610;&#3637;&#3656;)', NCM_txt_domain ),



                'TH-52' => __( 'Lampang (&#3621;&#3635;&#3611;&#3634;&#3591;)', NCM_txt_domain ),



                'TH-51' => __( 'Lamphun (&#3621;&#3635;&#3614;&#3641;&#3609;)', NCM_txt_domain ),



                'TH-42' => __( 'Loei (&#3648;&#3621;&#3618;)', NCM_txt_domain ),



                'TH-16' => __( 'Lopburi (&#3621;&#3614;&#3610;&#3640;&#3619;&#3637;)', NCM_txt_domain ),



                'TH-58' => __( 'Mae Hong Son (&#3649;&#3617;&#3656;&#3630;&#3656;&#3629;&#3591;&#3626;&#3629;&#3609;)', NCM_txt_domain ),



                'TH-44' => __( 'Maha Sarakham (&#3617;&#3627;&#3634;&#3626;&#3634;&#3619;&#3588;&#3634;&#3617;)', NCM_txt_domain ),



                'TH-49' => __( 'Mukdahan (&#3617;&#3640;&#3585;&#3604;&#3634;&#3627;&#3634;&#3619;)', NCM_txt_domain ),



                'TH-26' => __( 'Nakhon Nayok (&#3609;&#3588;&#3619;&#3609;&#3634;&#3618;&#3585;)', NCM_txt_domain ),



                'TH-73' => __( 'Nakhon Pathom (&#3609;&#3588;&#3619;&#3611;&#3600;&#3617;)', NCM_txt_domain ),



                'TH-48' => __( 'Nakhon Phanom (&#3609;&#3588;&#3619;&#3614;&#3609;&#3617;)', NCM_txt_domain ),



                'TH-30' => __( 'Nakhon Ratchasima (&#3609;&#3588;&#3619;&#3619;&#3634;&#3594;&#3626;&#3637;&#3617;&#3634;)', NCM_txt_domain ),



                'TH-60' => __( 'Nakhon Sawan (&#3609;&#3588;&#3619;&#3626;&#3623;&#3619;&#3619;&#3588;&#3660;)', NCM_txt_domain ),



                'TH-80' => __( 'Nakhon Si Thammarat (&#3609;&#3588;&#3619;&#3624;&#3619;&#3637;&#3608;&#3619;&#3619;&#3617;&#3619;&#3634;&#3594;)', NCM_txt_domain ),



                'TH-55' => __( 'Nan (&#3609;&#3656;&#3634;&#3609;)', NCM_txt_domain ),



                'TH-96' => __( 'Narathiwat (&#3609;&#3619;&#3634;&#3608;&#3636;&#3623;&#3634;&#3626;)', NCM_txt_domain ),



                'TH-39' => __( 'Nong Bua Lam Phu (&#3627;&#3609;&#3629;&#3591;&#3610;&#3633;&#3623;&#3621;&#3635;&#3616;&#3641;)', NCM_txt_domain ),



                'TH-43' => __( 'Nong Khai (&#3627;&#3609;&#3629;&#3591;&#3588;&#3634;&#3618;)', NCM_txt_domain ),



                'TH-12' => __( 'Nonthaburi (&#3609;&#3609;&#3607;&#3610;&#3640;&#3619;&#3637;)', NCM_txt_domain ),



                'TH-13' => __( 'Pathum Thani (&#3611;&#3607;&#3640;&#3617;&#3608;&#3634;&#3609;&#3637;)', NCM_txt_domain ),



                'TH-94' => __( 'Pattani (&#3611;&#3633;&#3605;&#3605;&#3634;&#3609;&#3637;)', NCM_txt_domain ),



                'TH-82' => __( 'Phang Nga (&#3614;&#3633;&#3591;&#3591;&#3634;)', NCM_txt_domain ),



                'TH-93' => __( 'Phatthalung (&#3614;&#3633;&#3607;&#3621;&#3640;&#3591;)', NCM_txt_domain ),



                'TH-56' => __( 'Phayao (&#3614;&#3632;&#3648;&#3618;&#3634;)', NCM_txt_domain ),



                'TH-67' => __( 'Phetchabun (&#3648;&#3614;&#3594;&#3619;&#3610;&#3641;&#3619;&#3603;&#3660;)', NCM_txt_domain ),



                'TH-76' => __( 'Phetchaburi (&#3648;&#3614;&#3594;&#3619;&#3610;&#3640;&#3619;&#3637;)', NCM_txt_domain ),



                'TH-66' => __( 'Phichit (&#3614;&#3636;&#3592;&#3636;&#3605;&#3619;)', NCM_txt_domain ),



                'TH-65' => __( 'Phitsanulok (&#3614;&#3636;&#3625;&#3603;&#3640;&#3650;&#3621;&#3585;)', NCM_txt_domain ),



                'TH-54' => __( 'Phrae (&#3649;&#3614;&#3619;&#3656;)', NCM_txt_domain ),



                'TH-83' => __( 'Phuket (&#3616;&#3641;&#3648;&#3585;&#3655;&#3605;)', NCM_txt_domain ),



                'TH-25' => __( 'Prachin Buri (&#3611;&#3619;&#3634;&#3592;&#3637;&#3609;&#3610;&#3640;&#3619;&#3637;)', NCM_txt_domain ),



                'TH-77' => __( 'Prachuap Khiri Khan (&#3611;&#3619;&#3632;&#3592;&#3623;&#3610;&#3588;&#3637;&#3619;&#3637;&#3586;&#3633;&#3609;&#3608;&#3660;)', NCM_txt_domain ),



                'TH-85' => __( 'Ranong (&#3619;&#3632;&#3609;&#3629;&#3591;)', NCM_txt_domain ),



                'TH-70' => __( 'Ratchaburi (&#3619;&#3634;&#3594;&#3610;&#3640;&#3619;&#3637;)', NCM_txt_domain ),



                'TH-21' => __( 'Rayong (&#3619;&#3632;&#3618;&#3629;&#3591;)', NCM_txt_domain ),



                'TH-45' => __( 'Roi Et (&#3619;&#3657;&#3629;&#3618;&#3648;&#3629;&#3655;&#3604;)', NCM_txt_domain ),



                'TH-27' => __( 'Sa Kaeo (&#3626;&#3619;&#3632;&#3649;&#3585;&#3657;&#3623;)', NCM_txt_domain ),



                'TH-47' => __( 'Sakon Nakhon (&#3626;&#3585;&#3621;&#3609;&#3588;&#3619;)', NCM_txt_domain ),



                'TH-11' => __( 'Samut Prakan (&#3626;&#3617;&#3640;&#3607;&#3619;&#3611;&#3619;&#3634;&#3585;&#3634;&#3619;)', NCM_txt_domain ),



                'TH-74' => __( 'Samut Sakhon (&#3626;&#3617;&#3640;&#3607;&#3619;&#3626;&#3634;&#3588;&#3619;)', NCM_txt_domain ),



                'TH-75' => __( 'Samut Songkhram (&#3626;&#3617;&#3640;&#3607;&#3619;&#3626;&#3591;&#3588;&#3619;&#3634;&#3617;)', NCM_txt_domain ),



                'TH-19' => __( 'Saraburi (&#3626;&#3619;&#3632;&#3610;&#3640;&#3619;&#3637;)', NCM_txt_domain ),



                'TH-91' => __( 'Satun (&#3626;&#3605;&#3641;&#3621;)', NCM_txt_domain ),



                'TH-17' => __( 'Sing Buri (&#3626;&#3636;&#3591;&#3627;&#3660;&#3610;&#3640;&#3619;&#3637;)', NCM_txt_domain ),



                'TH-33' => __( 'Sisaket (&#3624;&#3619;&#3637;&#3626;&#3632;&#3648;&#3585;&#3625;)', NCM_txt_domain ),



                'TH-90' => __( 'Songkhla (&#3626;&#3591;&#3586;&#3621;&#3634;)', NCM_txt_domain ),



                'TH-64' => __( 'Sukhothai (&#3626;&#3640;&#3650;&#3586;&#3607;&#3633;&#3618;)', NCM_txt_domain ),



                'TH-72' => __( 'Suphan Buri (&#3626;&#3640;&#3614;&#3619;&#3619;&#3603;&#3610;&#3640;&#3619;&#3637;)', NCM_txt_domain ),



                'TH-84' => __( 'Surat Thani (&#3626;&#3640;&#3619;&#3634;&#3625;&#3598;&#3619;&#3660;&#3608;&#3634;&#3609;&#3637;)', NCM_txt_domain ),



                'TH-32' => __( 'Surin (&#3626;&#3640;&#3619;&#3636;&#3609;&#3607;&#3619;&#3660;)', NCM_txt_domain ),



                'TH-63' => __( 'Tak (&#3605;&#3634;&#3585;)', NCM_txt_domain ),



                'TH-92' => __( 'Trang (&#3605;&#3619;&#3633;&#3591;)', NCM_txt_domain ),



                'TH-23' => __( 'Trat (&#3605;&#3619;&#3634;&#3604;)', NCM_txt_domain ),



                'TH-34' => __( 'Ubon Ratchathani (&#3629;&#3640;&#3610;&#3621;&#3619;&#3634;&#3594;&#3608;&#3634;&#3609;&#3637;)', NCM_txt_domain ),



                'TH-41' => __( 'Udon Thani (&#3629;&#3640;&#3604;&#3619;&#3608;&#3634;&#3609;&#3637;)', NCM_txt_domain ),



                'TH-61' => __( 'Uthai Thani (&#3629;&#3640;&#3607;&#3633;&#3618;&#3608;&#3634;&#3609;&#3637;)', NCM_txt_domain ),



                'TH-53' => __( 'Uttaradit (&#3629;&#3640;&#3605;&#3619;&#3604;&#3636;&#3605;&#3606;&#3660;)', NCM_txt_domain ),



                'TH-95' => __( 'Yala (&#3618;&#3632;&#3621;&#3634;)', NCM_txt_domain ),



                'TH-35' => __( 'Yasothon (&#3618;&#3650;&#3626;&#3608;&#3619;)', NCM_txt_domain ),



            ),



            'TR' => array(



                'TR01' => __( 'Adana', NCM_txt_domain ),



                'TR02' => __( 'Ad&#305;yaman', NCM_txt_domain ),



                'TR03' => __( 'Afyon', NCM_txt_domain ),



                'TR04' => __( 'A&#287;r&#305;', NCM_txt_domain ),



                'TR05' => __( 'Amasya', NCM_txt_domain ),



                'TR06' => __( 'Ankara', NCM_txt_domain ),



                'TR07' => __( 'Antalya', NCM_txt_domain ),



                'TR08' => __( 'Artvin', NCM_txt_domain ),



                'TR09' => __( 'Ayd&#305;n', NCM_txt_domain ),



                'TR10' => __( 'Bal&#305;kesir', NCM_txt_domain ),



                'TR11' => __( 'Bilecik', NCM_txt_domain ),



                'TR12' => __( 'Bing&#246;l', NCM_txt_domain ),



                'TR13' => __( 'Bitlis', NCM_txt_domain ),



                'TR14' => __( 'Bolu', NCM_txt_domain ),



                'TR15' => __( 'Burdur', NCM_txt_domain ),



                'TR16' => __( 'Bursa', NCM_txt_domain ),



                'TR17' => __( '&#199;anakkale', NCM_txt_domain ),



                'TR18' => __( '&#199;ank&#305;r&#305;', NCM_txt_domain ),



                'TR19' => __( '&#199;orum', NCM_txt_domain ),



                'TR20' => __( 'Denizli', NCM_txt_domain ),



                'TR21' => __( 'Diyarbak&#305;r', NCM_txt_domain ),



                'TR22' => __( 'Edirne', NCM_txt_domain ),



                'TR23' => __( 'Elaz&#305;&#287;', NCM_txt_domain ),



                'TR24' => __( 'Erzincan', NCM_txt_domain ),



                'TR25' => __( 'Erzurum', NCM_txt_domain ),



                'TR26' => __( 'Eski&#351;ehir', NCM_txt_domain ),



                'TR27' => __( 'Gaziantep', NCM_txt_domain ),



                'TR28' => __( 'Giresun', NCM_txt_domain ),



                'TR29' => __( 'G&#252;m&#252;&#351;hane', NCM_txt_domain ),



                'TR30' => __( 'Hakkari', NCM_txt_domain ),



                'TR31' => __( 'Hatay', NCM_txt_domain ),



                'TR32' => __( 'Isparta', NCM_txt_domain ),



                'TR33' => __( '&#304;&#231;el', NCM_txt_domain ),



                'TR34' => __( '&#304;stanbul', NCM_txt_domain ),



                'TR35' => __( '&#304;zmir', NCM_txt_domain ),



                'TR36' => __( 'Kars', NCM_txt_domain ),



                'TR37' => __( 'Kastamonu', NCM_txt_domain ),



                'TR38' => __( 'Kayseri', NCM_txt_domain ),



                'TR39' => __( 'K&#305;rklareli', NCM_txt_domain ),



                'TR40' => __( 'K&#305;r&#351;ehir', NCM_txt_domain ),



                'TR41' => __( 'Kocaeli', NCM_txt_domain ),



                'TR42' => __( 'Konya', NCM_txt_domain ),



                'TR43' => __( 'K&#252;tahya', NCM_txt_domain ),



                'TR44' => __( 'Malatya', NCM_txt_domain ),



                'TR45' => __( 'Manisa', NCM_txt_domain ),



                'TR46' => __( 'Kahramanmara&#351;', NCM_txt_domain ),



                'TR47' => __( 'Mardin', NCM_txt_domain ),



                'TR48' => __( 'Mu&#287;la', NCM_txt_domain ),



                'TR49' => __( 'Mu&#351;', NCM_txt_domain ),



                'TR50' => __( 'Nev&#351;ehir', NCM_txt_domain ),



                'TR51' => __( 'Ni&#287;de', NCM_txt_domain ),



                'TR52' => __( 'Ordu', NCM_txt_domain ),



                'TR53' => __( 'Rize', NCM_txt_domain ),



                'TR54' => __( 'Sakarya', NCM_txt_domain ),



                'TR55' => __( 'Samsun', NCM_txt_domain ),



                'TR56' => __( 'Siirt', NCM_txt_domain ),



                'TR57' => __( 'Sinop', NCM_txt_domain ),



                'TR58' => __( 'Sivas', NCM_txt_domain ),



                'TR59' => __( 'Tekirda&#287;', NCM_txt_domain ),



                'TR60' => __( 'Tokat', NCM_txt_domain ),



                'TR61' => __( 'Trabzon', NCM_txt_domain ),



                'TR62' => __( 'Tunceli', NCM_txt_domain ),



                'TR63' => __( '&#350;anl&#305;urfa', NCM_txt_domain ),



                'TR64' => __( 'U&#351;ak', NCM_txt_domain ),



                'TR65' => __( 'Van', NCM_txt_domain ),



                'TR66' => __( 'Yozgat', NCM_txt_domain ),



                'TR67' => __( 'Zonguldak', NCM_txt_domain ),



                'TR68' => __( 'Aksaray', NCM_txt_domain ),



                'TR69' => __( 'Bayburt', NCM_txt_domain ),



                'TR70' => __( 'Karaman', NCM_txt_domain ),



                'TR71' => __( 'K&#305;r&#305;kkale', NCM_txt_domain ),



                'TR72' => __( 'Batman', NCM_txt_domain ),



                'TR73' => __( '&#350;&#305;rnak', NCM_txt_domain ),



                'TR74' => __( 'Bart&#305;n', NCM_txt_domain ),



                'TR75' => __( 'Ardahan', NCM_txt_domain ),



                'TR76' => __( 'I&#287;d&#305;r', NCM_txt_domain ),



                'TR77' => __( 'Yalova', NCM_txt_domain ),



                'TR78' => __( 'Karab&#252;k', NCM_txt_domain ),



                'TR79' => __( 'Kilis', NCM_txt_domain ),



                'TR80' => __( 'Osmaniye', NCM_txt_domain ),



                'TR81' => __( 'D&#252;zce', NCM_txt_domain ),



            ),



            'US' => array(



                'AL' => __( 'Alabama', NCM_txt_domain ),



                'AK' => __( 'Alaska', NCM_txt_domain ),



                'AZ' => __( 'Arizona', NCM_txt_domain ),



                'AR' => __( 'Arkansas', NCM_txt_domain ),



                'CA' => __( 'California', NCM_txt_domain ),



                'CO' => __( 'Colorado', NCM_txt_domain ),



                'CT' => __( 'Connecticut', NCM_txt_domain ),



                'DE' => __( 'Delaware', NCM_txt_domain ),



                'DC' => __( 'District Of Columbia', NCM_txt_domain ),



                'FL' => __( 'Florida', NCM_txt_domain ),



                'GA' => _x( 'Georgia', 'US state of Georgia', NCM_txt_domain ),



                'HI' => __( 'Hawaii', NCM_txt_domain ),



                'ID' => __( 'Idaho', NCM_txt_domain ),



                'IL' => __( 'Illinois', NCM_txt_domain ),



                'IN' => __( 'Indiana', NCM_txt_domain ),



                'IA' => __( 'Iowa', NCM_txt_domain ),



                'KS' => __( 'Kansas', NCM_txt_domain ),



                'KY' => __( 'Kentucky', NCM_txt_domain ),



                'LA' => __( 'Louisiana', NCM_txt_domain ),



                'ME' => __( 'Maine', NCM_txt_domain ),



                'MD' => __( 'Maryland', NCM_txt_domain ),



                'MA' => __( 'Massachusetts', NCM_txt_domain ),



                'MI' => __( 'Michigan', NCM_txt_domain ),



                'MN' => __( 'Minnesota', NCM_txt_domain ),



                'MS' => __( 'Mississippi', NCM_txt_domain ),



                'MO' => __( 'Missouri', NCM_txt_domain ),



                'MT' => __( 'Montana', NCM_txt_domain ),



                'NE' => __( 'Nebraska', NCM_txt_domain ),



                'NV' => __( 'Nevada', NCM_txt_domain ),



                'NH' => __( 'New Hampshire', NCM_txt_domain ),



                'NJ' => __( 'New Jersey', NCM_txt_domain ),



                'NM' => __( 'New Mexico', NCM_txt_domain ),



                'NY' => __( 'New York', NCM_txt_domain ),



                'NC' => __( 'North Carolina', NCM_txt_domain ),



                'ND' => __( 'North Dakota', NCM_txt_domain ),



                'OH' => __( 'Ohio', NCM_txt_domain ),



                'OK' => __( 'Oklahoma', NCM_txt_domain ),



                'OR' => __( 'Oregon', NCM_txt_domain ),



                'PA' => __( 'Pennsylvania', NCM_txt_domain ),



                'RI' => __( 'Rhode Island', NCM_txt_domain ),



                'SC' => __( 'South Carolina', NCM_txt_domain ),



                'SD' => __( 'South Dakota', NCM_txt_domain ),



                'TN' => __( 'Tennessee', NCM_txt_domain ),



                'TX' => __( 'Texas', NCM_txt_domain ),



                'UT' => __( 'Utah', NCM_txt_domain ),



                'VT' => __( 'Vermont', NCM_txt_domain ),



                'VA' => __( 'Virginia', NCM_txt_domain ),



                'WA' => __( 'Washington', NCM_txt_domain ),



                'WV' => __( 'West Virginia', NCM_txt_domain ),



                'WI' => __( 'Wisconsin', NCM_txt_domain ),



                'WY' => __( 'Wyoming', NCM_txt_domain ),



                'AA' => __( 'Armed Forces (AA)', NCM_txt_domain ),



                'AE' => __( 'Armed Forces (AE)', NCM_txt_domain ),



                'AP' => __( 'Armed Forces (AP)', NCM_txt_domain ),



            ),



            'ZA' => array(



                'EC'  => __( 'Eastern Cape', NCM_txt_domain ),



                'FS'  => __( 'Free State', NCM_txt_domain ),



                'GP'  => __( 'Gauteng', NCM_txt_domain ),



                'KZN' => __( 'KwaZulu-Natal', NCM_txt_domain ),



                'LP'  => __( 'Limpopo', NCM_txt_domain ),



                'MP'  => __( 'Mpumalanga', NCM_txt_domain ),



                'NC'  => __( 'Northern Cape', NCM_txt_domain ),



                'NW'  => __( 'North West', NCM_txt_domain ),



                'WC'  => __( 'Western Cape', NCM_txt_domain ),



            ),



        );



        return $state;



    }







}



}



