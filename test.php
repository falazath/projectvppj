<!DOCTYPE html>
<html>

<head>
  <title>How to use Toastr Alerts in PHP? - Elevenstech Web Tutorials</title>
  <link href="./dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="./dist/css/bootstrap.css" rel="stylesheet">
  <script src="./dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="./style.css">
  <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.slim.min.js"></script>
</head>

<body class="bg-white">
  <button type="button" class="btn btn-primary" id="liveToastBtn">Show live toast</button>

  <div class="toast align-items-center text-bg-primary border-0" id="liveToast" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="d-flex">
    <div class="toast-body">
      Hello, world! This is a toast message.
    </div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
</div>

  <script>
    const toastTrigger = document.getElementById('liveToastBtn')
    const toastLiveExample = document.getElementById('liveToast')

    if (toastTrigger) {
      const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
      toastTrigger.addEventListener('click', () => {
        toastBootstrap.show()
      })
    }
  </script>


</body>

</html>