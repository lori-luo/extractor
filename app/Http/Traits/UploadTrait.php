<?php

namespace App\Http\Traits;

use App\Models\Upload;
use App\Models\FileLanguage;
use App\Models\SearchLanguage;

trait UploadTrait
{

    public function lang_clicked($id, $val)
    {
        $lang = FileLanguage::find($id);
        $lang_selected = ($val ? true : false);

        $lang->selected = $lang_selected;
        $lang->save();
    }


    public function insert_file_languages(Upload $file, $languages)
    {
        $file->languages()->delete();
        foreach ($languages as $language) {
            $lang = $this->get_code_lang(strtolower($language));
            $file->languages()->create([
                'code' => $language,
                'language' => $lang,
                'selected' => ($lang == 'English' || $lang == 'Chinese' ? true : false)
            ]);

            SearchLanguage::firstOrCreate([
                'code' => $language,
                'language' => $lang,
                'selected' => ($lang == 'English' || $lang == 'Chinese' ? true : false)
            ]);
        }
    }

    public function get_code_lang($code)
    {
        $codes = [
            'ab' => 'Abkhazian',
            'aa' => 'Afar',
            'af' => 'Afrikaans',
            'ak' => 'Akan',
            'sq' => 'Albanian',
            'am' => 'Amharic',
            'ar' => 'Arabic',
            'an' => 'Aragonese',
            'hy' => 'Armenian',
            'as' => 'Assamese',
            'av' => 'Avaric',
            'ae' => 'Avestan',
            'ay' => 'Aymara',
            'az' => 'Azerbaijani',
            'bm' => 'Bambara',
            'ba' => 'Bashkir',
            'eu' => 'Basque',
            'be' => 'Belarusian',
            'bn' => 'Bengali',
            'bh' => 'Bihari languages',
            'bi' => 'Bislama',
            'bs' => 'Bosnian',
            'br' => 'Breton',
            'bg' => 'Bulgarian',
            'my' => 'Burmese',
            'ca' => 'Catalan, Valencian',
            'km' => 'Central Khmer',
            'ch' => 'Chamorro',
            'ce' => 'Chechen',
            'ny' => 'Chichewa, Chewa, Nyanja',
            'zh' => 'Chinese',
            'cu' => 'Church Slavonic, Old Bulgarian, Old Church Slavonic',
            'cv' => 'Chuvash',
            'kw' => 'Cornish',
            'co' => 'Corsican',
            'cr' => 'Cree',
            'hr' => 'Croatian',
            'cs' => 'Czech',
            'da' => 'Danish',
            'dv' => 'Divehi, Dhivehi, Maldivian',
            'nl' => 'Dutch, Flemish',
            'dz' => 'Dzongkha',
            'en' => 'English',
            'eo' => 'Esperanto',
            'et' => 'Estonian',
            'ee' => 'Ewe',
            'fo' => 'Faroese',
            'fj' => 'Fijian',
            'fi' => 'Finnish',
            'fr' => 'French',
            'ff' => 'Fulah',
            'gd' => 'Gaelic, Scottish Gaelic',
            'gl' => 'Galician',
            'lg' => 'Ganda',
            'ka' => 'Georgian',
            'de' => 'German',
            'ki' => 'Gikuyu, Kikuyu',
            'el' => 'Greek (Modern)',
            'kl' => 'Greenlandic, Kalaallisut',
            'gn' => 'Guarani',
            'gu' => 'Gujarati',
            'ht' => 'Haitian, Haitian Creole',
            'ha' => 'Hausa',
            'he' => 'Hebrew',
            'hz' => 'Herero',
            'hi' => 'Hindi',
            'ho' => 'Hiri Motu',
            'hu' => 'Hungarian',
            'is' => 'Icelandic',
            'io' => 'Ido',
            'ig' => 'Igbo',
            'id' => 'Indonesian',
            'ia' => 'Interlingua (International Auxiliary Language Association)',
            'ie' => 'Interlingue',
            'iu' => 'Inuktitut',
            'ik' => 'Inupiaq',
            'ga' => 'Irish',
            'it' => 'Italian',
            'ja' => 'Japanese',
            'jv' => 'Javanese',
            'kn' => 'Kannada',
            'kr' => 'Kanuri',
            'ks' => 'Kashmiri',
            'kk' => 'Kazakh',
            'rw' => 'Kinyarwanda',
            'kv' => 'Komi',
            'kg' => 'Kongo',
            'ko' => 'Korean',
            'kj' => 'Kwanyama, Kuanyama',
            'ku' => 'Kurdish',
            'ky' => 'Kyrgyz',
            'lo' => 'Lao',
            'la' => 'Latin',
            'lv' => 'Latvian',
            'lb' => 'Letzeburgesch, Luxembourgish',
            'li' => 'Limburgish, Limburgan, Limburger',
            'ln' => 'Lingala',
            'lt' => 'Lithuanian',
            'lu' => 'Luba-Katanga',
            'mk' => 'Macedonian',
            'mg' => 'Malagasy',
            'ms' => 'Malay',
            'ml' => 'Malayalam',
            'mt' => 'Maltese',
            'gv' => 'Manx',
            'mi' => 'Maori',
            'mr' => 'Marathi',
            'mh' => 'Marshallese',
            'ro' => 'Moldovan, Moldavian, Romanian',
            'mn' => 'Mongolian',
            'na' => 'Nauru',
            'nv' => 'Navajo, Navaho',
            'nd' => 'Northern Ndebele',
            'ng' => 'Ndonga',
            'ne' => 'Nepali',
            'se' => 'Northern Sami',
            'no' => 'Norwegian',
            'nb' => 'Norwegian Bokmål',
            'nn' => 'Norwegian Nynorsk',
            'ii' => 'Nuosu, Sichuan Yi',
            'oc' => 'Occitan (post 1500)',
            'oj' => 'Ojibwa',
            'or' => 'Oriya',
            'om' => 'Oromo',
            'os' => 'Ossetian, Ossetic',
            'pi' => 'Pali',
            'pa' => 'Panjabi, Punjabi',
            'ps' => 'Pashto, Pushto',
            'fa' => 'Persian',
            'pl' => 'Polish',
            'pt' => 'Portuguese',
            'qu' => 'Quechua',
            'rm' => 'Romansh',
            'rn' => 'Rundi',
            'ru' => 'Russian',
            'sm' => 'Samoan',
            'sg' => 'Sango',
            'sa' => 'Sanskrit',
            'sc' => 'Sardinian',
            'sr' => 'Serbian',
            'sn' => 'Shona',
            'sd' => 'Sindhi',
            'si' => 'Sinhala, Sinhalese',
            'sk' => 'Slovak',
            'sl' => 'Slovenian',
            'so' => 'Somali',
            'st' => 'Sotho, Southern',
            'nr' => 'South Ndebele',
            'es' => 'Spanish, Castilian',
            'sh' => 'Serbo-Croatian',
            'su' => 'Sundanese',
            'sw' => 'Swahili',
            'ss' => 'Swati',
            'sv' => 'Swedish',
            'tl' => 'Tagalog',
            'ty' => 'Tahitian',
            'tg' => 'Tajik',
            'ta' => 'Tamil',
            'tt' => 'Tatar',
            'te' => 'Telugu',
            'th' => 'Thai',
            'bo' => 'Tibetan',
            'ti' => 'Tigrinya',
            'to' => 'Tonga (Tonga Islands)',
            'ts' => 'Tsonga',
            'tn' => 'Tswana',
            'tr' => 'Turkish',
            'tk' => 'Turkmen',
            'tw' => 'Twi',
            'ug' => 'Uighur, Uyghur',
            'uk' => 'Ukrainian',
            'ur' => 'Urdu',
            'uz' => 'Uzbek',
            've' => 'Venda',
            'vi' => 'Vietnamese',
            'vo' => 'Volap_k',
            'wa' => 'Walloon',
            'cy' => 'Welsh',
            'fy' => 'Western Frisian',
            'wo' => 'Wolof',
            'xh' => 'Xhosa',
            'yi' => 'Yiddish',
            'yo' => 'Yoruba',
            'za' => 'Zhuang, Chuang',
            'zu' => 'Zulu'
        ];

        return (isset($codes[$code]) ? $codes[$code] : '');
    }

    public function is_subject_medical($subjects) // accepts json
    {


        $valid_subjects = [

            'Medicine',

            //----Medicine
            'Dentistry',
            'Dermatology',
            'Gynecology and obstetrics',
            'Homeopathy',
            'Internal medicine',
            'Infectious and parasitic diseases',
            'Medical emergencies. Critical care. Intensive care. First aid',
            'Neoplasms. Tumors. Oncology. Including cancer and carcinogens',
            'Neurosciences. Biological psychiatry. Neuropsychiatry',
            'Neurology. Diseases of the nervous system',
            'Psychiatry',
            'Therapeutics. Psychotherapy',
            'Special situations and conditions',
            'Arctic medicine. Tropical medicine',
            'Geriatrics',
            'Industrial medicine. Industrial hygiene',
            'Sports medicine',
            'Specialties of internal medicine',
            'Diseases of the blood and blood-forming organs',
            'Diseases of the circulatory (Cardiovascular) system',
            'Diseases of the digestive system. Gastroenterology',
            'Diseases of the endocrine glands. Clinical endocrinology',
            'Diseases of the genitourinary system. Urology',
            'Diseases of the musculoskeletal system',
            'Diseases of the respiratory system',
            'Immunologic diseases. Allergy',
            'Nutritional diseases. Deficiency diseases',

            'Medicine (General)',
            'Computer applications to medicine. Medical informatics',
            'General works',
            'History of medicine. Medical expeditions',
            'Medical philosophy. Medical ethics',
            'Medical physics. Medical radiology. Nuclear medicine',
            'Medical technology',

            'Medical',
            'Nursing',
            'Ophthalmology',
            'Other systems of medicine',

            'Chiropractic',
            'Mental healing',
            'Miscellaneous systems and treatments',
            'Osteopathy',

            'Optics',
            'Otorhinolaryngology',
            'Pathology',
            'Pediatrics',
            'Pharmacy and materia medica',
            'Public aspects of medicine',
            'Toxicology. Poisons',
            'Anesthesiology',
            'Orthopedic surgery',
            'Therapeutics. Pharmacology',
            'Philosophy. Psychology.', //no found
            'Aesthetics',
            'Psychology',
            'Consciousness. Cognition',

            'Science',
            'Biology (General)',
            'Ecology',
            'Genetics',
            'Life',
            'Reproduction',

            'Chemistry',
            'Analytical chemistry',
            'Organic chemistry',
            'Biochemistry',
            'Human anatomy',
            'Microbiology',
            'Microbial ecology',

            'Physiology',
            'Biochemistry',
            'Neurophysiology and neuropsychology',

            'Zoology'


        ];



        $subjects = json_decode($subjects);
        foreach ($subjects as $subject) {
            if (in_array($subject->term, $valid_subjects)) {
                return true;
            }
        }

        return false;
    }
}
