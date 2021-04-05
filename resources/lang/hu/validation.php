<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used
	| by the validator class. Some of the rules contain multiple versions,
	| such as the size (max, min, between) rules. These versions are used
	| for different input types such as strings and files.
	|
	| These language lines may be easily changed to provide custom error
	| messages in your application. Error messages for custom validation
	| rules may also be added to this file.
	|
	*/

	'accepted'       => 'A(z) :attribute el kell legyen fogadva.',
	'active_url'     => 'A :attribute nem valós URL.',
	'after'          => 'A :attribute :date utáni dátum kell legyen.',
	'alpha'          => 'A(z) :attribute csak betűket tartalmazhat.',
	'alpha_dash'     => 'A(z) :attribute betűket, számokat és kötőjeleket tartalmazhat.',
	'alpha_num'      => 'A(z) :attribute csak betűket és számokat tartalmazhat.',
    'array'          => 'The :attribute tömb kell legyen.',
	'before'         => 'A :attribute :date előtti dátum kell legyen.',
	'between'        => [
		'numeric' => 'A(z) :attribute :min - :max közötti érték kell legyen.',
		'file'    => 'A(z) :attribute :min - :max kilobyte között kell legyen.',
		'string'  => 'A(z) :attribute :min - :max karakterhossz között kell legyen',
        'array'   => 'The :attribute  :min and :max tételek között kell legyen.',
	],
    'boolean'              => 'The :attribute field must be true or false.',
	'confirmed'      => 'A(z) :attribute megerősítése nem egyezett meg.',
    'date'           => 'The :attribute is not a valid date.',
    'date_format'    => 'The :attribute does not match the format :format.',
	'different'      => 'A(z) :attribute és :other különböző kell legyen.',
    'digits'         => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions'     => 'The :attribute has invalid image dimensions.',
    'distinct'       => 'The :attribute field has a duplicate value.',
	'email'          => 'A(z) :attribute formátuma nem megfelelő.',
	'exists'         => 'A(z) választott :attribute nem megfelelő.',
    'file'                 => 'The :attribute must be a file.',
    'filled'               => 'The :attribute field is required.',
	'image'          => 'A(z) :attribute kép kell legyen.',
	'in'             => 'A(z) választott :attribute nem megfelelő.',
    'in_array'             => 'The :attribute field does not exist in :other.',
	'integer'        => 'A :attribute szám kell legyen.',
	'ip'             => 'A :attribute valós IP cím kell legyen.',
	'match'          => 'A(z) :attribute formátuma nem megfelelő.',
    'json'                 => 'The :attribute must be a valid JSON string.',
	'max'            => [
		'numeric' => 'A :attribute kevesebb kell legyen, mint :max.',
		'file'    => 'A :attribute kevesebb kell legyen :max kilobytenál.',
		'string'  => 'A :attribute kevesebb karakterből kell álljon, mint :max.',
        'array'   => 'The :attribute may not have more than :max items.',
	],
	'mimes'          => 'A :attribute az alábbi tipusokból való kell legyen :values.',
    'mimetypes'            => 'The :attribute must be a file of type: :values.',
	'min'            => [
		'numeric' => 'A :attribute legalább :min kell legyen.',
		'file'    => 'A :attribute legalább :min kilobyte kell legyen.',
		'string'  => 'A :attribute legalább :min karakter hosszú kell legyen.',
        'array'   => 'The :attribute must have at least :min items.',
	],
	'not_in'         => 'A választott :attribute nem megfelelő.',
	'numeric'        => 'A :attribute szám kell legyen.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
	'required'       => 'A(z) :attribute megadása kötelező.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
	'same'           => 'A :attribute és a :other muszáj hogy megegyezzen.',
	'size'           => [
		'numeric' => 'A(z) :attribute :size kell legyen.',
		'file'    => 'A(z) :attribute :size kilobyteos kell legyen.',
		'string'  => 'A(z) :attribute :size karakteres kell legyen.',
        'array'   => 'The :attribute tartalmaznia kell :size tételt.',
	],
    'string'               => 'A(z) :attribute szöveg kell legyen.',
    'timezone'             => 'The :attribute must be a valid zone.',
	'unique'         => 'A(z) :attribute már foglalt.',
    'uploaded'             => 'A(z) :attribute feltöltése nem sikerült.',
	'url'            => 'A(z) :attribute formátuma nem megfelelő.',

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention 'attribute_rule' to name the lines. This helps keep your
	| custom validation clean and tidy.
	|
	| So, say you want to use a custom validation message when validating that
	| the 'email' attribute is unique. Just add 'email_unique' to this array
	| with your custom message. The Validator will handle the rest!
	|
	*/

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

	/*
	|--------------------------------------------------------------------------
	| Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as 'E-Mail Address' instead
	| of 'email'. Your users will thank you.
	|
	| The Validator class will automatically search this array of lines it
	| is attempting to replace the :attribute place-holder in messages.
	| It's pretty slick. We think you'll like it.
	|
	*/

    'attributes' => [],

]; 
