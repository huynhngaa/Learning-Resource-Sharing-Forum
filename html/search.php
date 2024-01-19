<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search with Ajax</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <h2>Search with Ajax</h2>
    <input type="text" id="searchInput" placeholder="Enter keyword">
    <div id="searchResult">

<h3>cuộc sống này</h3>

    </div>
    <script>
    $(document).ready(function() {
        function showLoader() {
            $("#loader").show();
        }

        function hideLoader() {
            $("#loader").hide();
        }

        $("#searchInput").on("input", function() {
            var keyword = $(this).val();

            clearTimeout(timeout);

            timeout = setTimeout(function() {
                $.ajax({
                    type: "GET",
                    url: "search-tm.php",
                    data: { noidung: keyword },
                    beforeSend: function() {
                        showLoader(); // Hiển thị loader trước khi gửi yêu cầu Ajax
                    },
                    success: function(response) {
                        $("#searchResult").html(response);
                        hideLoader(); // Ẩn loader sau khi nhận kết quả
                    }
                });
            }, 500);
        });
    });
</script>


</body>
</html>
