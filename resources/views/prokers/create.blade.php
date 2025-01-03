   <!-- resources/views/prokers/create.blade.php -->
   @extends('app')

   @section('content')
   <div class="container mt-5">
       @if(session('error'))
       <div class="alert alert-danger">
           {{ session('error') }}
       </div>
       @endif
       <h2>Ajukan Proker</h2>
       <form action="{{ route('prokers.store') }}" method="POST" enctype="multipart/form-data">
           @csrf
           <div class="form-group">
               <label for="club_id">Oramawa</label>
               <input type="text" class="form-control" id="club_id" value="{{ Auth::user()->club->name ?? 'N/A' }}" readonly>
               <input type="hidden" name="id_club" value="{{ Auth::user()->id_club }}">
           </div>
           <div class="form-group">
               <label for="name">Nama Proker</label>
               <input type="text" class="form-control" id="name" name="name" required>
           </div>
           <div class="form-group">
               <label for="document_lpj">Dokumen LPJ</label>
               <input type="file" class="form-control" id="document_lpj" name="document_lpj" required>
           </div>
           <div class="form-group">
               <label for="budget">Budget</label>
               <input type="number" class="form-control" id="budget" name="budget" required>
           </div>
           <div class="form-group">
               <label for="target_event">Target Proker</label>
               <input type="date" class="form-control" id="target_event" name="target_event" required>
           </div>
           <button type="submit" class="btn btn-primary">Ajukan</button>
       </form>
   </div>
   @endsection