<!DOCTYPE html>
<html>
<head>
    <title>Import Employees</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Import Employees</h2>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="file">Choose Excel File</label>
            <input type="file" name="file" class="form-control" id="file">
            @if ($errors->has('file'))
                <span class="text-danger">{{ $errors->first('file') }}</span>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Import</button>
    </form>
</div>
</body>
</html>
