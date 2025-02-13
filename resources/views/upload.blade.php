// filepath: /D:/Developer/Projects/Backend/laravel_ci/excelData_automation/resources/views/upload.blade.php
<!DOCTYPE html>
<html>
<head>
    <title>Upload Excel Files</title>
</head>
<body>
    <form action="{{ route('excel.upload.post') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="excel1">Excel File 1:</label>
        <input type="file" name="excel1" id="excel1" required>
        <br>
        <label for="excel2">Excel File 2:</label>
        <input type="file" name="excel2" id="excel2" required>
        <br>
        <button type="submit">Upload and Merge</button>
    </form>
</body>
</html>
