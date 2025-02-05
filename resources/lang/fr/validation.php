<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'=> 'Le champ :attribute doit être accepté.',
    'accepted_if'=> 'Le champ :attribute doit être accepté quand :other a la valeur :value.',
    'active_url'=> 'Ce n\'est pas une URL valide',
    'after'=> 'La date doit être postérieure a la :date.',
    'after_or_equal'=> 'La date doit être postérieure ou égale a la :date.',
    'alpha'=> 'Le champ :attribute doit contenir uniquement des lettres',
    'alpha_dash'=> 'Le champ :attribute doit contenir uniquement des lettres, des chiffres et des tirets.',
    'alpha_num'=> 'Le champ :attribute doit contenir uniquement des chiffres et des lettres.',
    'array' => 'Le champ :attribute doit être un tableau.',
    'attached'=> 'Le champ :attribute est déjà attaché.',
    'before' => 'Le champ :attribute doit être une date antérieure a la :date.',
    'before_or_equal' => 'Le champ :attribute doit être une date antérieure ou égale a la :date.',
    'between.array'=> 'Le tableau doit contenir entre :min et :max éléments.',
    'between.file'=> 'La taille du fichier doit être comprise entre :min et :max kilo-octets.',
    'between.numeric'=> 'La valeur doit être comprise entre :min et :max.',
    'between.string'=> 'Le texte doit contenir entre :min et :max caractères.',
    'boolean'=> 'Le champ :attribute doit être vrai ou faux.',
    'confirmed'=> 'Le champ de confirmation ne correspond pas.',
    'date'=> 'Ce n\'est pas une date valide.',
    'date_equals'=> 'La date doit être égale à :date.',
    'date_format'=> 'Le champ :attribute ne correspond pas au format :format.',
    'declined'=> 'Cette valeur doit être déclinée.',
    'declined_if'=> 'Cette valeur doit être déclinée quand :other a la valeur :value.',
    'different'=> 'Cette valeur doit être différente de :other.',
    'digits'=> 'Le champ :attribute doit contenir :digits chiffres.',
    'digits_between'=> 'Le champ :attribute doit contenir entre :min et :max chiffres.',
    'dimensions'=> 'La taille de l\'image n\'est pas conforme.',
    'distinct'=> 'Le champ :attribute a une valeur en double.',
    'doesnt_end_with'=> 'This field may not end with one of the following: :values.',
    'doesnt_start_with'=> 'This field may not start with one of the following: :values.',
    'email'=> 'Le champ :attribute doit être une adresse e-mail valide.',
    'ends_with'=> 'Le champ :attribute doit se terminer par une des valeurs suivantes : :values',
    'enum'=> 'Le champ :attribute selectionné est invalide.',
    'exists'=> 'Le champ :attribute sélectionné est invalide.',
    'file'=> 'Le champ :attribute doit être un fichier.',
    'filled'=> 'Le champ :attribute doit avoir une valeur.',
    'gt.array'=> 'Le tableau doit contenir plus de :value éléments.',
    'gt.file'=> 'La taille du fichier doit être supérieure à :value kilo-octets.',
    'gt.numeric'=> 'La valeur doit être supérieure à :value.',
    'gt.string'=> 'Le texte doit contenir plus de :value caractères.',
    'gte.array'=> 'Le tableau doit contenir au moins :value éléments.',
    'gte.file'=> 'La taille du fichier doit être supérieure ou égale à :value kilo-octets.',
    'gte.numeric'=> 'La valeur doit être supérieure ou égale à :value.',
    'gte.string'=> 'Le texte doit contenir au moins :value caractères.',
    'image'=> 'Le champ :attribute doit être une image.',
    'in'=> 'Le champ :attribute est invalide.',
    'in_array'=> 'Le champ :attribute n\'existe pas dans :other.',
    'float' => 'Le champ :attribute doit être un décimal ou un entier.',
    'integer'=> 'Le champ :attribute doit être un entier.',
    'ip'=> 'Le champ :attribute doit être une adresse IP valide.',
    'ipv4'=> 'Le champ :attribute doit être une adresse IPv4 valide.',
    'ipv6'=> 'Le champ :attribute doit être une adresse IPv6 valide.',
    'json'=> 'Le champ :attribute doit être un document JSON valide.',
    'lt.array'=> 'Le tableau doit contenir moins de :value éléments.',
    'lt.file'=> 'La taille du fichier doit être inférieure à :value kilo-octets.',
    'lt.numeric'=> 'La valeur doit être inférieure à :value.',
    'lt.string'=> 'Le texte doit contenir moins de :value caractères.',
    'lte.array'=> 'Le tableau doit contenir au plus :value éléments.',
    'lte.file'=> 'La taille du fichier doit être inférieure ou égale à :value kilo-octets.',
    'lte.numeric'=> 'La valeur doit être inférieure ou égale à :value.',
    'lte.string'=> 'Le texte doit contenir au plus :value caractères.',
    'mac_address'=> 'La valeur doit être une adresse MAC valide.',
    'max.array'=> 'Le tableau ne peut contenir plus de :max éléments.',
    'max.file'=> 'La taille du fichier ne peut pas dépasser :max kilo-octets.',
    'max.numeric'=> 'La valeur ne peut être supérieure à :max.',
    'max.string'=> 'Le texte ne peut contenir plus de :max caractères.',
    'max_digits'=> 'This field must not have more than :max digits.',
    'mimes'=> 'Le fichier doit être de type : :values.',
    'mimetypes'=> 'Le fichier doit être de type : :values.',
    'min.array'=> 'Le tableau doit contenir au moins :min éléments.',
    'min.file'=> 'La taille du fichier doit être supérieure ou égale à :min kilo-octets.',
    'min.numeric'=> 'La valeur doit être supérieure ou égale à :min.',
    'min.string'=> 'Le texte doit contenir au moins :min caractères.',
    'min_digits'=> 'This field must have at least :min digits.',
    'multiple_of'=> 'La valeur doit être un multiple de :value',
    'not_in'=> 'Le champ sélectionné n\'est pas valide.',
    'not_regex'=> 'Le format du champ n\'est pas valide.',
    'numeric'=> 'Le champ :attribute doit contenir un nombre.',
    'password.letters'=> 'Le mot de passe doit contenir au moins une lettre.',
    'password.mixed'=> 'Le mot de passe doit contenir au moins une majuscule et une minuscule.',
    'password.numbers'=> 'Le mot de passe doit contenir au moins un chiffre.',
    'password.symbols'=> 'Le mot de passe doit contenir au moins un symbole.',
    'password.uncompromised'=> 'Le mot de passe est apparue dans une fuite de données. Veuillez choisir une valeur différente.',
    'present'=> 'Le champ :attribute doit être présent.',
    'prohibited'=> 'Le champ :attribute est interdit',
    'prohibited_if'=> 'Le champ :attribute est interdit quand :other a la valeur :value.',
    'prohibited_unless'=> 'Le champ :attribute est interdit à moins que :other ait l\'une des valeurs :values.',
    'prohibits'=> 'Le champ :attribute interdit :other d\'être présent.',
    'regex'=> 'Le format du champ est invalide.',
    'relatable'=> 'Le champ :attribute n\'est sans doute pas associé avec cette donnée.',
    'required'=> 'Le champ :attribute est obligatoire.',
    'required_array_keys'=> 'Le champ :attribute doit contenir des entrées pour : :values.',
    'required_if'=> 'Le champ :attribute est obligatoire quand la valeur de :other est :value.',
    'required_unless'=> 'Le champ :attribute est obligatoire sauf si :other est :values.',
    'required_with'=> 'Le champ :attribute est obligatoire quand :values est présent.',
    'required_with_all'=> 'Le champ :attribute est obligatoire quand :values sont présents.',
    'required_without'=> 'Le champ :attribute est obligatoire quand :values n\'est pas présent.',
    'required_without_all'=> 'Le champ :attribute est requis quand aucun de :values n\'est présent.',
    'same'=> 'Le champ :attribute doit être identique à :other.',
    'size.array'=> 'Le tableau doit contenir :size éléments.',
    'size.file'=> 'La taille du fichier doit être de :size kilo-octets.',
    'size.numeric'=> 'La valeur doit être :size.',
    'size.string'=> 'Le texte doit contenir :size caractères.',
    'starts_with'=> 'Le champ :attribute doit commencer avec une des valeurs suivantes : :values',
    'string'=> 'Le champ :attribute doit être une chaîne de caractères.',
    'timezone'=> 'Le champ :attribute doit être un fuseau horaire valide.',
    'unique'=> 'La valeur est déjà utilisée.',
    'uploaded'=> 'Le fichier n\'a pu être téléversé.',
    'url'=> 'Le format de l\'URL n\'est pas valide.',
    'uuid'=> 'Le champ :attribute doit être un UUID valide',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as 'E-Mail Address' instead
    | of 'email'. This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
