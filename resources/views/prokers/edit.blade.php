   <!-- resources/views/prokers/edit.blade.php -->
   @extends('app')

   @section('content')
   <div class="container mt-5">
       <h2>Edit Proker</h2>
       <form action="{{ route('prokers.update', $proker->id) }}" method="POST">
           @csrf
           @method('PUT')
           <div class="form-group">
               <label for="id_club">Club:</label>
               <select class="form-control" id="id_club" name="id_club" required>
                   <option value="">Select a Club</option>
                   @foreach($clubs as $club)
                   <option value="{{ $club->id }}" {{ $proker->id_club == $club->id ? 'selected' : '' }}>{{ $club->name }}</option>
                   @endforeach
               </select>
           </div>
           <div class="form-group">
               <label for="name">Name:</label>
               <input type="text" class="form-control" id="name" name="name" value="{{ $proker->name }}" required>
           </div>
           <div class="form-group">
               <label for="document_lpj">Document LPJ:</label>
               <input type="file" class="form-control" id="document_lpj" name="document_lpj">
               @if($proker->document_lpj)
               <p>Current file: <a href="{{ asset('storage/' . $proker->document_lpj) }}" target="_blank">{{ $proker->document_lpj }}</a></p>
               @endif
           </div>
           <div class="form-group">
               <label for="budget">Budget:</label>
               <input type="number" class="form-control" id="budget" name="budget" value="{{ $proker->budget }}" required>
           </div>
           <div class="form-group">
               <label for="target_event">Target Event:</label>
               <input type="date" class="form-control" id="target_event" name="target_event" value="{{ $proker->target_event }}" required>
           </div>
           <button type="submit" class="btn btn-primary">Update</button>
       </form>
   </div>
   @endsection