<?php 
require 'vendor/autoload.php';

use MongoDB\Client;

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$database = $mongoClient->Test;
$collection_tachtu = $database->tachtunoidung;
$collection = $database->chimucnoidung;
$collection_tachtu_tieude = $database->tachtutieude;
$collection_chimuc_tieude = $database->chimuctieude;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "luanvan";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Không kết nối: " . $conn->connect_error);
} 
mysqli_set_charset($conn, 'UTF8');
include "include/head.php";
  $collectionquery = $database->selectCollection('tachtuquery');
  $query = [];

  $document = $collectionquery->findOne($query);
  $wordForms = $document['wordForms'];
  $finalString = '';

  foreach ($wordForms as $wordForm) {
      $finalString .= $wordForm . ' ';
  }

  echo strtolower(trim($finalString));
  $truyvan_terms = explode(' ', $finalString);
  $pipeline = [
      [
          '$group' => [
              '_id' => '$doc_id',
              'tong' => [
                  '$sum' => [
                      '$size' => ['$split' => ['$wordForm', ' ']]
                  ],
              ],
          ],
      ],
  ];

  // Execute the aggregation pipeline

  $tong_tu_result = $collection_tachtu->aggregate($pipeline);

  $tong_tu_by_doc_id = [];
  // Iterate through the results and store them in the $tong_tu_by_doc_id array
  foreach ($tong_tu_result as $document) {
      $doc_id = $document['_id'];
      $tong_tu = $document['tong'];
      $tong_tu_by_doc_id[$doc_id] = $tong_tu;
  }

  // Execute the query to find all documents in the collection
  $result = $collection->find();

  $tf_results = [];
  $unique_doc_ids = [];
  $doc_count_by_word = [];

  // Iterate over the result cursor to get all documents
  foreach ($result as $document) {
      // Access the fields in the document as needed
      $word = $document['word'];
      $doc = iterator_to_array($document['doc']); // Convert BSONArray to PHP array

      // Process the document data and calculate TF for each term
      foreach ($doc as $term) {
          $doc_id = $term['doc_id'];
          $count = $term['count'];
          $totalWords = $tong_tu_by_doc_id[$doc_id];
          $tf = $count / $totalWords;

          // Store the results in the array
          $tf_results[] = [
              'word' => $word,
              'doc_id' => $doc_id,
              'count' => $count,
              'tf' => $tf,
          ];

          // Count the occurrences of doc_id for each word
          if (!isset($doc_count_by_word[$word])) {
              $doc_count_by_word[$word] = 1;
          } else {
              $doc_count_by_word[$word]++;
          }

          if (!in_array($doc_id, $unique_doc_ids)) {
              $unique_doc_ids[] = $doc_id;
          }
      }
  }

  // Sort the results array by doc_id
  usort($tf_results, function ($a, $b) {
      return $a['doc_id'] - $b['doc_id'];
  });


  // Initialize an array to store TF-IDF values in a tabular format
  $table = [];

  foreach ($truyvan_terms as $term) {
      // Check if the term exists in the IDF table
      if (isset($doc_count_by_word[$term])) {
          $idf = 1 + log(count($unique_doc_ids) / $doc_count_by_word[$term]);

          // Calculate TF-IDF for each document
          foreach ($tf_results as $result) {
              if ($result['word'] === $term) {
                  $tf_idf = $result['tf'] * $idf;

                  // Store the TF-IDF value in the table
                  $table[$term][$result['doc_id']] = $tf_idf;
              }
          }
      } else {
          // If the term is not found in the IDF table, set TF-IDF to 0 for all documents
          foreach ($unique_doc_ids as $doc_id) {
              $table[$term][$doc_id] = 0;
          }
      }
  }


  // Count the occurrences of each term in the query
  $term_count = array_count_values($truyvan_terms);

  // Calculate TF for each term
  $tf_results = [];
  $total_terms = count($truyvan_terms);

  foreach ($term_count as $term => $count) {
      $tf = $count / $total_terms;

      $tf_results[] = [
          'term' => $term,
          'count' => $count,
          'tf' => $tf,
      ];
  }




  // Function to calculate the dot product of two vectors
  function dotProduct($vector1, $vector2)
  {
      $result = 0;
      foreach ($vector1 as $key => $value) {
          $result += $value * $vector2[$key];
      }
      return $result;
  }

  // Calculate the dot product for each document
  $dotProducts = [];
  foreach ($unique_doc_ids as $doc_id) {
      $queryVector = array_column($table, $doc_id);
      $docVector = array_column($table, $doc_id);

      $dotProducts[$doc_id] = dotProduct($queryVector, $docVector);
  }


  // Calculate the length of TF-IDF vectors for each document
  $vectorLengths = [];

  foreach ($unique_doc_ids as $doc_id) {
      $vector = array_column($table, $doc_id);

      // Calculate the square of each component and sum them up
      $sumOfSquares = array_reduce($vector, function ($carry, $value) {
          return $carry + ($value ** 2);
      }, 0);

      // Take the square root of the sum to get the vector length
      $length = sqrt($sumOfSquares);

      // Store the length in the array
      $vectorLengths[$doc_id] = $length;
  }

  // Initialize a variable to store the sum of squared TF-IDF values
  $tf_idf_sum_of_squares = 0;

  foreach ($tf_results as $result) {
      // Check if the term exists in the IDF table
      if (isset($doc_count_by_word[$result['term']])) {
          $idf = 1 + log(count($unique_doc_ids) / $doc_count_by_word[$result['term']]);
          $tf_idf = $result['tf'] * $idf; // Calculate TF-IDF
          $tf_idf_sum_of_squares += $tf_idf * $tf_idf; // Add the square of TF-IDF to the sum
      }
  }

  // Calculate the length (magnitude) of the TF-IDF vector
  $tf_idf_length = sqrt($tf_idf_sum_of_squares);


  // Calculate the product of TF-IDF length and document length for each document
  $tf_idf_lengths_product = [];

  foreach ($vectorLengths as $doc_id => $length) {
      // Get the TF-IDF length for the current document
      $tf_idf_length_for_doc = $tf_idf_length;

      // Calculate the product of TF-IDF length and document length
      $product = $length * $tf_idf_length_for_doc;

      // Store the product in the array
      $tf_idf_lengths_product[$doc_id] = $product;
  }

  $cosineSimilarities = [];

  foreach ($unique_doc_ids as $doc_id) {
      if ($tf_idf_lengths_product[$doc_id] != 0) {
          $cosineSimilarities[$doc_id] = $dotProducts[$doc_id] / $tf_idf_lengths_product[$doc_id];
      } else {
          $cosineSimilarities[$doc_id] = 0;
      }
  }

  arsort($cosineSimilarities);


    $topResults = [];
    $count = 0;

    foreach ($cosineSimilarities as $doc_id => $cosineSimilarity) {
        if ($cosineSimilarity > 0) {
            $sql = "SELECT bv.*, nd.*, dm_ten, mh_ten, kl_ten, CURRENT_TIMESTAMP(), COUNT(bl.bl_ma) AS slbl
            FROM bai_viet bv
            LEFT JOIN binh_luan bl ON bv.bv_ma = bl.bv_ma
            JOIN nguoi_dung nd ON bv.nd_username = nd.nd_username
            JOIN danh_muc dm ON bv.dm_ma = dm.dm_ma
            JOIN mon_hoc mh ON dm.mh_ma = mh.mh_ma
            JOIN khoi_lop kl ON kl.kl_ma = mh.kl_ma
            LEFT JOIN kiem_duyet kd ON kd.bv_ma = bv.bv_ma
            WHERE kd.tt_ma = 1 AND bv.bv_ma = $doc_id
            GROUP BY bv.bv_ma";

            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_array($result)) {
                $currentTimestamp = strtotime($row['bv_ngaydang']);
                $current_time = strtotime($row['CURRENT_TIMESTAMP()']);
                $timeDifference = $current_time - $currentTimestamp;

                if ($timeDifference < 60) {
                    $timeAgo = 'Vừa xong';
                } elseif ($timeDifference < 3600) {
                    $minutesAgo = floor($timeDifference / 60);
                    $timeAgo = $minutesAgo . ' phút';
                } elseif ($timeDifference < 86400) {
                    $hoursAgo = floor($timeDifference / 3600);
                    $timeAgo = $hoursAgo . ' giờ';
                } else {
                    $daysAgo = floor($timeDifference / 86400);
                    $timeAgo = $daysAgo . ' ngày';
                }

                $topResults[] = [
                    'cosineSimilarity' => $cosineSimilarity,
                    'row' => $row,
                    'timeAgo' => $timeAgo,
                ];

                $count++;
            }

            if ($count >= 5) {
                break;
            }
        }
    }

    usort($topResults, function ($a, $b) {
        return $b['cosineSimilarity'] <=> $a['cosineSimilarity'];
    });

    if (!empty($topResults)) {
        foreach ($topResults as $result) {
            $cosineSimilarity = $result['cosineSimilarity'];
            $row = $result['row'];
            $timeAgo = $result['timeAgo'];
?>

            <div class="col-md-12 col-12 mb-3">
         
                <a href="chitietbv.php?id=<?php echo $row['bv_ma'] ?>">
                    <div class="card">
                        <div class="card-body">
                        <div class="d-flex">

<div class="flex-shrink-0">
  <img src="../assets/img/avatars/<?php echo $row['nd_hinh'] ?>" alt="google" class="me-3 rounded-circle" height="40" />
</div>
<div class="flex-grow-1 row">
  <div class="col-9 mb-sm-0 mb-2">
    <h6 class="mb-0 text-dark"><?php echo $row['bv_tieude'] ?></h6>
    <small> <span class="badge bg-label-primary">#<?php echo $row['dm_ten'] ?></span></small>
    <small> <span class="badge bg-label-primary">#<?php echo $row['mh_ten'] ?></span></small>
    <small> <span class="badge bg-label-primary">#<?php echo $row['kl_ten'] ?></span></small>
    <!-- <small class="text-muted"><?php echo $row['nd_hoten'] ?></small> -->
    <br>
  </div>
  <div class="col-1">
    <small> <i class="fa-solid fa-eye"></i> <?php echo $row['bv_luotxem'] ?></small>
    <br>
    <small> <i class="fa-solid fa-comment"> </i> <?php echo $row['slbl'] ?></small>
  </div>
  <div class="col-2 text-end">
    <small class="">
      <?php echo $timeAgo ?> </small> <br>
    <small class="text-muted">
      <?php echo $row['nd_hoten'] ?></small>
  </div>
</div>
</div>
                        </div>
                    </div>
                </a>
            </div>



<?php
        }
    } else {
        echo "Không có kết quả";
    }
    ?>