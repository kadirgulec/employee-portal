
@if ($getRecord()->document)
    <embed src="{{ Storage::url($getRecord()->document)  }}" width="100%" height="600"
           type="application/pdf">
@endif
