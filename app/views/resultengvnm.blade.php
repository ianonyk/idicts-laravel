@extends('layouts.master')
@section('title')
@parent
- Anh Việt - Nghĩa của {{ $word }}
@stop

@section('content')
@if( $defs->count() > 0 )
 <ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#words" data-toggle="tab"><strong>Định nghĩa</strong></a></li>
    <li><a href="#sentences" data-toggle="tab"><strong>Mẫu câu</strong></a></li>
  </ul>
  <div id="myTabContent" class="tab-content">
     <div class="tab-pane active" id="words">
          {{ '<i class="j_listen icon-volume-up" data-w="' . urlencode($word) . '"></i>' }}
         @foreach ($defs as $def)
                {{ $def->def }}
         @endforeach
     </div>
     <div class="tab-pane" id="sentences">
     <ul>
       @if ($sentences->count()>0)
        @foreach ($sentences as $sentence)
            {{ '<li><span class="sentenceorg">' . $sentence->seng . '</span><i class="j_listen icon-volume-up" data-w="' . urlencode(str_replace(array('.','"'),',',$sentence->seng)) . '"></i>' }}
            {{ '<ul><li><span class="sentencedes">' . $sentence->svnm . '</span></li></ul></li>'  }}
        @endforeach
         @else
        Xin lỗi iDicts không tìm thấy mẫu câu nào cho từ {{$word}}.
        @endif
     </ul>
     </div>
  </div>
@else
  @if ($suggestions)
  Có phải bạn muốn tìm <a href="http://www.idicts.com/anh-viet/{{ $suggestions[0] }}">{{$suggestions[0]}}</a> <br>
  @else
  Xin lỗi iDicts không thể tìm thấy {{$word}}.
  @endif

@endif

@stop
