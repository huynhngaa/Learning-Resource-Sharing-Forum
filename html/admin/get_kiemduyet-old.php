<!-- <div class="modal-body"> -->

    <div class="table-responsive text-nowrap border-top">
        <table class="table">
            <thead>
                <th>#</th>
                <!-- <th>Mã</th> -->
                <th>Tiêu đề</th>
                <th style='white-space: normal'>Nội dung được cho là trùng
                    lặp </th>
                <th>#</th>
                <th>Nội dung bài viết góc</th>
                <th>Độ tương đồng</th>
            </thead>
            <tbody id="data">
                <?php
                    include "../include/conn.php";
                    require '../vendor/autoload.php';

                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Lấy dữ liệu từ request body
                        $postdata = file_get_contents("php://input");
                        $request = json_decode($postdata);

                        if (isset($request->id) && isset($request->id2) && isset($request->similarRows)) {
                            $id = $request->id;
                            echo '<input type="hidden" name="bv_ma" value="'.$id.'">';
                            $id2 = $request->id2;
                            
                            $similarRows = $request->similarRows;
                            
                            $i = 0;
                            if (!empty($similarRows)) {
                                foreach ($similarRows as $row) {
                                    // Kiểm tra phần tử có khớp với ID không
                                    if ($row->id == $id && $row->id2 == $id2) {
                                        $i++;
                                        $content1 = $row->content1;
                                        $content2 = $row->content2;
                                        
                                        // Tách từng từ trong content1 và content2
                                        $words1 = explode(" ", $content1);
                                        $words2 = explode(" ", $content2);
                                        
                                        // Tìm và làm nổi bật các từ trùng nhau
                                        foreach ($words1 as $word1) {
                                            $word1Lower = strtolower($word1);
                                            foreach ($words2 as $word2) {
                                                $word2Lower = strtolower($word2);
                                                
                                                if ($word1Lower === $word2Lower) {
                                                    $content1 = str_ireplace($word1, "<span class='highlight'>$word1</span>", $content1);
                                                    $content2 = str_ireplace($word2, "<span class='highlight'>$word2</span>", $content2);
                                                }
                                            }
                                        }
                                        
                                        echo "
                                            <tr>
                                                <td>{$row->id}</td>
                                                <td style='white-space: normal'>{$row->tieude}</td>
                                                <td style='white-space: normal'>{$content1}</td>
                                                <td>{$row->id2}</td>
                                                <td style='white-space: normal'>{$content2}</td>
                                                <td>{$row->similarity}%</td>
                                            </tr>
                                        ";
                                    }
                                }
                            }
                        }
                    }
                ?>

            </tbody>
        </table>
    <!-- </div> -->
