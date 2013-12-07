<?php


Route::get('/', function(){
    return View::make('home')->with("selectDict", "anh-viet");;
});

Route::get('/anh-viet/{word}', function($word = null){
    $word = trim($word);
    $data['word'] = $word;
    $data['defs'] = Engvnm::where('name','=',$word)->get();
    if ($data['defs']->count()==0){
        $data['defs'] = Engvnm::where('name','=',singularize($word))->get();
    }
    $data['sentences'] = Avsentence::where('seng','LIKE','% '. $word .' %')->get();
    if ($data['defs']->count() == 0 ){
        $json = file_get_contents('http://www.idicts.com/api/v1/suggesteng/' . $word);
        $data['suggestions'] = json_decode($json);
    }
     return View::make('resultengvnm',$data)->with("selectDict", "anh-viet");
});
Route::get('viet-anh/{word}',function($word = null){
   $word = trim($word);
   $data['word'] = $word;
   $data['defs'] = Vnmeng::where('name','=',$word)->get();
   $data['sentences'] = Avsentence::where('svnm','LIKE','% '.$word.' %')->get();
   return View::make('resultvnmeng',$data)->with("selectDict", "viet-anh");
});


Route::get('/mp3/{word}.mp3',function($word = null){
	$word = trim($word);
    if(!empty($word)){

        $mp3 = file_get_contents("http://translate.google.com/translate_tts?tl=en&q={$word}");
        $response = Response::make($mp3, 200);
        $response->header("Content-Type", "audio/mpeg");
        $response->header('Content-Disposition', 'inline; filename="' . $word . '.mp3"');
        $response->header('X-Pad', 'avoid browser bug');
        $response->header('Cache-Control', 'no-cache');
        return $response;
    }
});

/*
|--------------------------------------------------------------------------
| API returning suggested words for certain dictionaries
|--------------------------------------------------------------------------
| Return Json suggestedwords, currently using by Jquery to display autocomplete
|
|
*/


Route::get('/api/v1/suggesteng/{word}',function($word = null){
    if(strlen($word) >= 2){
    $word = trim($word);
    $suggestwords = Engvnm::where('name','LIKE',"$word%")->take(30)->lists('name');
    if(count($suggestwords)==0){
        $word = singularize($word);
        $suggestwords = Engvnm::where('name','LIKE',$word . "%")->take(10)->lists('name');
        if(count($suggestwords==0)){
            $pspell_link = pspell_new("en");
            if(!pspell_check($pspell_link,$word)){
                $words = pspell_suggest($pspell_link,$word);
                $word = $words[0];
                $suggestwords = Engvnm::where('name','LIKE',$word . "%")->take(10)->lists('name');
            }
        }
        }
    }
    return Response::json($suggestwords,200);
});
Route::get('/api/v1/suggestvnm/{word}',function($word = null){
    if(strlen($word)>=2){
        $word = trim($word);
        $suggestwords = Vnmeng::where('name','LIKE',"$word%")->take(30)->lists('name');
        return Response::json($suggestwords,200);
    }
});


/*
|--------------------------------------------------------------------------
| API returning pure HTML
|--------------------------------------------------------------------------
|
| Those APIS route returns pure HTML
| Currently using by HoangCao for Android Application
|
|
*/

Route::get('/api/v1/engvnm/{word}',function($word = null){
   $word = trim($word);
   $defs = Engvnm::where('name','=',$word)->get();
   if ($defs->count()==0){
       $word = singularize($word);
       $defs = Engvnm::where('name','=',$word)->get();
   }
   if($defs->count()>0) return $defs[0]->def;
   else return "Không tìm thấy kết quả cho $word";

});

Route::get('/api/v1/engvnms/{word}',function($word = null){
   $word = trim($word);
   $sentences = Avsentence::where('seng','LIKE','% '. $word .' %')->get();
   if ($sentences->count()==0){
        $sentences = Avsentence::where('seng','LIKE','% '. singularize($word) .' %')->get();
   }

    $data = "";
    if($sentences->count()>0){
        foreach($sentences as $sentence){
            $data .= '<li>' . $sentence->seng ;
            $data .= '<ul><li>' . $sentence->svnm . '</li></ul></li>';
        }
    }
    else {
        $data = "Không có mẫu câu được tìm thấy cho từ $word";
    }
    return $data;

});


/*
|--------------------------------------------------------------------------
| Singularize Function
|--------------------------------------------------------------------------
|
| Singularzie the word if plural detected

*/



function singularize($word)
    {
        $singular = array (
        '/(quiz)zes$/i' => '\1',
        '/(matr)ices$/i' => '\1ix',
        '/(vert|ind)ices$/i' => '\1ex',
        '/^(ox)en/i' => '\1',
        '/(alias|status)es$/i' => '\1',
        '/([octop|vir])i$/i' => '\1us',
        '/(cris|ax|test)es$/i' => '\1is',
        '/(shoe)s$/i' => '\1',
        '/(o)es$/i' => '\1',
        '/(bus)es$/i' => '\1',
        '/([m|l])ice$/i' => '\1ouse',
        '/(x|ch|ss|sh)es$/i' => '\1',
        '/(m)ovies$/i' => '\1ovie',
        '/(s)eries$/i' => '\1eries',
        '/([^aeiouy]|qu)ies$/i' => '\1y',
        '/([lr])ves$/i' => '\1f',
        '/(tive)s$/i' => '\1',
        '/(hive)s$/i' => '\1',
        '/([^f])ves$/i' => '\1fe',
        '/(^analy)ses$/i' => '\1sis',
        '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\1\2sis',
        '/([ti])a$/i' => '\1um',
        '/(n)ews$/i' => '\1ews',
        '/s$/i' => '',
        );

        $uncountable = array('equipment', 'information', 'rice', 'money', 'species', 'series', 'fish', 'sheep');

        $irregular = array(
        'person' => 'people',
        'man' => 'men',
        'child' => 'children',
        'sex' => 'sexes',
        'move' => 'moves');

        $lowercased_word = strtolower($word);
        foreach ($uncountable as $_uncountable){
            if(substr($lowercased_word,(-1*strlen($_uncountable))) == $_uncountable){
                return $word;
            }
        }

        foreach ($irregular as $_plural=> $_singular){
            if (preg_match('/('.$_singular.')$/i', $word, $arr)) {
                return preg_replace('/('.$_singular.')$/i', substr($arr[0],0,1).substr($_plural,1), $word);
            }
        }

        foreach ($singular as $rule => $replacement) {
            if (preg_match($rule, $word)) {
                return preg_replace($rule, $replacement, $word);
            }
        }

        return $word;
    }