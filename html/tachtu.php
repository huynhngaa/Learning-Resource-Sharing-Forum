<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Run Python from PHP</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <button type="button" id="runPythonBtn" class="btn btn-outline-primary">tach từ</button>
    <script>
        $(document).ready(function() {
            $("#runPythonBtn").click(function() {
                $.ajax({
                    url: "run_python.php",
                    type: "POST",
                    success: function(response) {
                        console.log(response);
                        // Xử lý kết quả nếu cần
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>
</body>
</html>
