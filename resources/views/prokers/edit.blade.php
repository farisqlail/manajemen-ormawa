   <!-- resources/views/prokers/edit.blade.php -->
   @extends('app')

   @section('content')
   <div class="container mt-5">
       <h2>Edit Proker</h2>
       <form action="{{ route('prokers.update', $proker->id) }}" method="POST" enctype="multipart/form-data">
           @csrf
           @method('PUT')
           @if($proker->status_laporan == '' && $proker->status !== 'approved')
           <input type="text" class="form-control" id="status" name="status" value="pending" hidden>
           <input type="text" class="form-control" id="status_laporan" name="status_laporan" value="" hidden>
           @else
           <input type="text" class="form-control" id="status" name="status" value="approved" hidden>
           <input type="text" class="form-control" id="status_laporan" name="status_laporan" value="pending" hidden>
           @endif
           <div class="row">
               <div class="col">
                   <div class="form-group">
                       <label for="club_id">Oramawa</label>
                       <input type="text" class="form-control" id="club_id" value="{{ Auth::user()->club->name ?? 'N/A' }}" readonly>
                       <input type="hidden" name="id_club" value="{{ Auth::user()->id_club }}">
                   </div>
               </div>
               <div class="col">
                   <div class="form-group">
                       <label for="name">Name:</label>
                       <input type="text" class="form-control" id="name" name="name" value="{{ $proker->name }}" required>
                   </div>
               </div>
           </div>
           <div class="row">
               <div class="col">
                   <div class="form-group">
                       <label for="budget">Budget:</label>
                       <input type="number" class="form-control" id="budget" name="budget" value="{{ $proker->budget }}" required>
                   </div>
               </div>
               <div class="col">
                   <div class="form-group">
                       <label for="target_event">Target Event:</label>
                       <input type="date" class="form-control" id="target_event" name="target_event" value="{{ $proker->target_event }}" required>
                   </div>
               </div>
           </div>
           <div class="form-group">
               <label for="description">Proposal</label>
               <textarea class="form-control" id="proposal" name="proposal" required>{!! $proker->proposal !!}</textarea>
           </div>
           @if($proker->status == 'approved')
           <div class="form-group">
               <label for="laporan">Laporan</label>
               <textarea class="form-control" id="laporan" name="laporan" required>{!! $proker->laporan !!}</textarea>
           </div>
           @endif
           <button type="submit" class="btn btn-primary">Update</button>
       </form>
   </div>

   <!-- CKEditor CDN -->
   <script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
   <script>
       CKEDITOR.replace('proposal');
       CKEDITOR.replace('laporan');
   </script>
   @endsection