<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="./dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="./dist/css/bootstrap.css" rel="stylesheet">
  <script src="./dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="./style.css">
</head>
<style>
  .upload-btn-wrapper {
    position: relative;
    overflow: hidden;
    display: inline-block;
  }
  
  
  
  .upload-btn-wrapper input[type=file] {
    font-size: 100px;
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
  }
</style>
<body>
<div class="upload-btn-wrapper">
  <button type="button" class="btn btn-dark">Upload a file</button>
  <input type="file" name="myfile" />
</div>
</body>

</html>