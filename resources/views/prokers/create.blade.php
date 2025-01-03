   <!-- resources/views/prokers/create.blade.php -->
   @extends('app')

   @section('content')
   <div class="container mt-5">
       <h2>Ajukan Proker</h2>
       <form action="{{ route('prokers.store') }}" method="POST">
           @csrf
           <div class="form-group">
               <label for="id_club">Ormawa</label>
               <select class="form-control" id="id_club" name="id_club" required>
                   <option value="">Select a Club</option>
                   @foreach($clubs as $club)
                   <option value="{{ $club->id }}">{{ $club->name }}</option>
                   @endforeach
               </select>
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