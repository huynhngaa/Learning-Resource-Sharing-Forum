<?php 
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

$pipeline_noidung = [ 
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
$tong_tu_by_doc_id_noidung = [];
$tong_tu_result_noidung = $collection_tachtu_noidung->aggregate($pipeline_noidung);

foreach ($tong_tu_result_noidung as $document) {
    $doc_id_noidung = $document['_id'];
    $tong_tu_noidung = $document['tong'];
    $tong_tu_by_doc_id_noidung[$doc_id_noidung] = $tong_tu_noidung;
}

$result_noidung = $collection_chimuc_noidung->find();

$tf_results_noidung = [];
$unique_doc_ids_noidung = [];
$doc_count_by_word_noidung = [];

foreach ($result_noidung as $document) {
    $word_noidung = $document['word'];
    $doc_noidung = iterator_to_array($document['doc']);

    foreach ($doc_noidung as $term_noidung) {
        $doc_id_noidung = $term_noidung['doc_id'];
        $count_noidung = $term_noidung['count'];
        $totalWords_noidung = $tong_tu_by_doc_id_noidung[$doc_id_noidung];
        $tf_noidung = $count_noidung / $totalWords_noidung;

        $tf_results_noidung[] = [
            'word' => $word_noidung,
            'doc_id' => $doc_id_noidung,
            'count' => $count_noidung,
            'tf' => $tf_noidung,
        ];

        if (!isset($doc_count_by_word_noidung[$word_noidung])) {
            $doc_count_by_word_noidung[$word_noidung] = 1;
        } else {
            $doc_count_by_word_noidung[$word_noidung]++;
        }

        if (!in_array($doc_id_noidung, $unique_doc_ids_noidung)) {
            $unique_doc_ids_noidung[] = $doc_id_noidung;
        }
    }
}

usort($tf_results_noidung, function ($a, $b) {
    return $a['doc_id'] - $b['doc_id'];
});

$table_noidung = [];

foreach ($truyvan_terms as $term_noidung) {
    if (isset($doc_count_by_word_noidung[$term_noidung])) {
        $idf_noidung = 1 + log(count($unique_doc_ids_noidung) / $doc_count_by_word_noidung[$term_noidung]);
        foreach ($tf_results_noidung as $result_noidung) {
            if ($result_noidung['word'] === $term_noidung) {
                $tf_idf_noidung = $result_noidung['tf'] * $idf_noidung;
                $table_noidung[$term_noidung][$result_noidung['doc_id']] = $tf_idf_noidung;
            }
        }
    } else {
        foreach ($unique_doc_ids_noidung as $doc_id_noidung) {
            $table_noidung[$term_noidung][$doc_id_noidung] = 0;
        }
    }
}

$term_count_noidung = array_count_values($truyvan_terms);

$tf_results_noidung = [];
$total_terms_noidung = count($truyvan_terms);

foreach ($term_count_noidung as $term_noidung => $count_noidung) {
    $tf_noidung = $count_noidung / $total_terms_noidung;

    $tf_results_noidung[] = [
        'term' => $term_noidung,
        'count' => $count_noidung,
        'tf' => $tf_noidung,
    ];
}

function dotProduct_noidung($vector1, $vector2)
{
    $result_noidung = 0;
    foreach ($vector1 as $key_noidung => $value_noidung) {
        $result_noidung += $value_noidung * $vector2[$key_noidung];
    }
    return $result_noidung;
}

$dotProducts_noidung = [];
foreach ($unique_doc_ids_noidung as $doc_id_noidung) {
    $queryVector_noidung = array_column($table_noidung, $doc_id_noidung);
    $docVector_noidung = array_column($table_noidung, $doc_id_noidung);

    $dotProducts_noidung[$doc_id_noidung] = dotProduct_noidung($queryVector_noidung, $docVector_noidung);
}

$vectorLengths_noidung = [];

foreach ($unique_doc_ids_noidung as $doc_id_noidung) {
    $vector_noidung = array_column($table_noidung, $doc_id_noidung);
    $sumOfSquares_noidung = array_reduce($vector_noidung, function ($carry_noidung, $value_noidung) {
        return $carry_noidung + ($value_noidung ** 2);
    }, 0);
    $length_noidung = sqrt($sumOfSquares_noidung);
    $vectorLengths_noidung[$doc_id_noidung] = $length_noidung;
}

$tf_idf_sum_of_squares_noidung = 0;

foreach ($tf_results_noidung as $result_noidung) {
    if (isset($doc_count_by_word_noidung[$result_noidung['term']])) {
        $idf_noidung = 1 + log(count($unique_doc_ids_noidung) / $doc_count_by_word_noidung[$result_noidung['term']]);
        $tf_idf_noidung = $result_noidung['tf'] * $idf_noidung; 
        $tf_idf_sum_of_squares_noidung += $tf_idf_noidung * $tf_idf_noidung; 
    }
}

$tf_idf_length_noidung = sqrt($tf_idf_sum_of_squares_noidung);
$tf_idf_lengths_product_noidung = [];

foreach ($vectorLengths_noidung as $doc_id_noidung => $length_noidung) {
    $tf_idf_length_for_doc_noidung = $tf_idf_length_noidung;
    $product_noidung = $length_noidung * $tf_idf_length_for_doc_noidung;
    $tf_idf_lengths_product_noidung[$doc_id_noidung] = $product_noidung;
}

$cosineSimilarities_noidung = [];

foreach ($unique_doc_ids_noidung as $doc_id_noidung) {
    if ($tf_idf_lengths_product_noidung[$doc_id_noidung] != 0) {
        $cosineSimilarities_noidung[$doc_id_noidung] = $dotProducts_noidung[$doc_id_noidung] / $tf_idf_lengths_product_noidung[$doc_id_noidung];
    } else {
        $cosineSimilarities_noidung[$doc_id_noidung] = 0;
    }
}

arsort($cosineSimilarities_noidung);





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


$tong_tu_by_doc_id = [];
$tong_tu_result = $collection_tachtu_tieude->aggregate($pipeline);


foreach ($tong_tu_result as $document) {
    $doc_id = $document['_id'];
    $tong_tu = $document['tong'];
    $tong_tu_by_doc_id[$doc_id] = $tong_tu;
}

$result = $collection->find();

$tf_results = [];
$unique_doc_ids = [];
$doc_count_by_word = [];


foreach ($result as $document) {
    $word = $document['word'];
    $doc = iterator_to_array($document['doc']);

    foreach ($doc as $term) {
        $doc_id = $term['doc_id'];
        $count = $term['count'];
        $totalWords = $tong_tu_by_doc_id[$doc_id];
        $tf = $count / $totalWords;

        $tf_results[] = [
            'word' => $word,
            'doc_id' => $doc_id,
            'count' => $count,
            'tf' => $tf,
        ];


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


usort($tf_results, function ($a, $b) {
    return $a['doc_id'] - $b['doc_id'];
});

$table = [];

foreach ($truyvan_terms as $term) {
    if (isset($doc_count_by_word[$term])) {
        $idf = 1 + log(count($unique_doc_ids) / $doc_count_by_word[$term]);
        foreach ($tf_results as $result) {
            if ($result['word'] === $term) {
                $tf_idf = $result['tf'] * $idf;
                $table[$term][$result['doc_id']] = $tf_idf;
            }
        }
    } else {
        
        foreach ($unique_doc_ids as $doc_id) {
            $table[$term][$doc_id] = 0;
        }
    }
}


$term_count = array_count_values($truyvan_terms);

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


function dotProduct($vector1, $vector2)
{
    $result = 0;
    foreach ($vector1 as $key => $value) {
        $result += $value * $vector2[$key];
    }
    return $result;
}

$dotProducts = [];
foreach ($unique_doc_ids as $doc_id) {
    $queryVector = array_column($table, $doc_id);
    $docVector = array_column($table, $doc_id);

    $dotProducts[$doc_id] = dotProduct($queryVector, $docVector);
}

$vectorLengths = [];

foreach ($unique_doc_ids as $doc_id) {
    $vector = array_column($table, $doc_id);
    $sumOfSquares = array_reduce($vector, function ($carry, $value) {
        return $carry + ($value ** 2);
    }, 0);
    $length = sqrt($sumOfSquares);
    $vectorLengths[$doc_id] = $length;
}

$tf_idf_sum_of_squares = 0;

foreach ($tf_results as $result) {
    if (isset($doc_count_by_word[$result['term']])) {
        $idf = 1 + log(count($unique_doc_ids) / $doc_count_by_word[$result['term']]);
        $tf_idf = $result['tf'] * $idf; 
        $tf_idf_sum_of_squares += $tf_idf * $tf_idf; 
    }
}


$tf_idf_length = sqrt($tf_idf_sum_of_squares);
$tf_idf_lengths_product = [];

foreach ($vectorLengths as $doc_id => $length) {
    $tf_idf_length_for_doc = $tf_idf_length;
    $product = $length * $tf_idf_length_for_doc;
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

// arsort($cosineSimilarities);
// print_r($cosineSimilarities); echo '<br>';
// print_r( $cosineSimilarities_noidung);  echo '<br>';


$resultArray = [];

foreach ($cosineSimilarities as $key => $value) {
    if (isset($cosineSimilarities_noidung[$key])) {
        $resultArray[$key] = max($value, $cosineSimilarities_noidung[$key]);
    } else {
        $resultArray[$key] = $value;
    }
}

foreach ($cosineSimilarities_noidung as $key => $value) {
    if (!isset($resultArray[$key])) {
        $resultArray[$key] = $value;
    }
}

// In ra kết quả
arsort($resultArray);
// print_r($resultArray
?>
