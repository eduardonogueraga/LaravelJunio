{{ csrf_field() }}
<div class="form-group">
    <label for="title">Titulo:</label>
    <input type="text" name="title" placeholder="Titulo" value="{{old('title', $profession->title)}}" class="form-control">
</div>