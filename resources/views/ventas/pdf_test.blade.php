<!DOCTYPE html>
<html>

<head>
  <title>{{ $nameFile }}</title>
  <script type="text/javascript" src="{{ asset('plugins/print/print.js') }}"></script>
  <style>
      * {
        padding: 0;
        margin:0;
      }
  </style>
  <script type="text/javascript">
    let path = "{{ $pathJS }}";
    window.onload = function() {
      printJS(path)
    }
  </script>
</head>

<body>
  <embed data-temp="{{ $path }}" src="{{ $path }}" id="pdfDocument" width="100%" height="500px" />
</body>

</html>