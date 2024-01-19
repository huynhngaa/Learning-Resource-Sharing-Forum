<?php
require 'vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$database = $mongoClient->Test;
$tachtuCollection = $database->tachtunoidung;
$tachtutieudeCollection = $database->tachtutieude;
$chimuc2Collection = $database->chimucnoidung;
$chimuctieudeCollection = $database->chimuctieude;
$tachtu = $database->tachtu;
$chimuc = $database->chimuc;

$chimuc->drop();
$chimuctieudeCollection->drop();
$chimuc2Collection->drop();

$invertedIndex = [];
$cursor = $tachtu->find([], ['projection' => ['wordForm' => 1, 'doc_id' => 1]]);
foreach ($cursor as $document) {
    $wordForm = strtolower($document['wordForm']); // Convert to lowercase
    $docId = $document['doc_id'];

    if (!isset($invertedIndex[$wordForm])) {
        $invertedIndex[$wordForm] = [];
    }

    $found = false;
    foreach ($invertedIndex[$wordForm] as &$entry) {
        if ($entry['doc_id'] == $docId) {
            $entry['count']++;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $invertedIndex[$wordForm][] = [
            'doc_id' => $docId,
            'count' => 1
        ];
    }
}

foreach ($invertedIndex as $word => $docEntries) {
    $existingEntry = $chimuc->findOne(['word' => $word]);
    if ($existingEntry) {
        foreach ($docEntries as $newEntry) {
            $docId = $newEntry['doc_id'];
            $count = $newEntry['count'];

            $found = false;
            foreach ($existingEntry['doc'] as &$existingDocEntry) {
                if ($existingDocEntry['doc_id'] == $docId) {
                    $existingDocEntry['count'] += $count;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $existingEntry['doc'][] = [
                    'doc_id' => $docId,
                    'count' => $count
                ];
            }
        }
        $chimuc->updateOne(['word' => $word], ['$set' => ['doc' => $existingEntry['doc']]]);
    } else {
        $chimuc->insertOne([
            'word' => $word,
            'doc' => $docEntries
        ]);
    }
}

$invertedIndexNoidung = [];
$cursorNoidung = $tachtuCollection->find([], ['projection' => ['wordForm' => 1, 'doc_id' => 1]]);

foreach ($cursorNoidung as $document) {
    $wordForm = strtolower($document['wordForm']); // Convert to lowercase
    $docId = $document['doc_id'];

    if (!isset($invertedIndexNoidung[$wordForm])) {
        $invertedIndexNoidung[$wordForm] = [];
    }

    $found = false;
    foreach ($invertedIndexNoidung[$wordForm] as &$entry) {
        if ($entry['doc_id'] == $docId) {
            $entry['count']++;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $invertedIndexNoidung[$wordForm][] = [
            'doc_id' => $docId,
            'count' => 1
        ];
    }
}

foreach ($invertedIndexNoidung as $word => $docEntries) {
    $existingEntry = $chimuc2Collection->findOne(['word' => $word]);
    if ($existingEntry) {
        foreach ($docEntries as $newEntry) {
            $docId = $newEntry['doc_id'];
            $count = $newEntry['count'];

            $found = false;
            foreach ($existingEntry['doc'] as &$existingDocEntry) {
                if ($existingDocEntry['doc_id'] == $docId) {
                    $existingDocEntry['count'] += $count;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $existingEntry['doc'][] = [
                    'doc_id' => $docId,
                    'count' => $count
                ];
            }
        }
        $chimuc2Collection->updateOne(['word' => $word], ['$set' => ['doc' => $existingEntry['doc']]]);
    } else {
        $chimuc2Collection->insertOne([
            'word' => $word,
            'doc' => $docEntries
        ]);
    }
}

$invertedIndexTieude = [];
$cursorTieude = $tachtutieudeCollection->find([], ['projection' => ['wordForm' => 1, 'doc_id' => 1]]);

foreach ($cursorTieude as $document) {
    $wordForm = strtolower($document['wordForm']); // Convert to lowercase
    $docId = $document['doc_id'];

    if (!isset($invertedIndexTieude[$wordForm])) {
        $invertedIndexTieude[$wordForm] = [];
    }

    $found = false;
    foreach ($invertedIndexTieude[$wordForm] as &$entry) {
        if ($entry['doc_id'] == $docId) {
            $entry['count']++;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $invertedIndexTieude[$wordForm][] = [
            'doc_id' => $docId,
            'count' => 1
        ];
    }
}

foreach ($invertedIndexTieude as $word => $docEntries) {
    $existingEntry = $chimuctieudeCollection->findOne(['word' => $word]);
    if ($existingEntry) {
        foreach ($docEntries as $newEntry) {
            $docId = $newEntry['doc_id'];
            $count = $newEntry['count'];

            $found = false;
            foreach ($existingEntry['doc'] as &$existingDocEntry) {
                if ($existingDocEntry['doc_id'] == $docId) {
                    $existingDocEntry['count'] += $count;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $existingEntry['doc'][] = [
                    'doc_id' => $docId,
                    'count' => $count
                ];
            }
        }
        $chimuctieudeCollection->updateOne(['word' => $word], ['$set' => ['doc' => $existingEntry['doc']]]);
    } else {
        $chimuctieudeCollection->insertOne([
            'word' => $word,
            'doc' => $docEntries
        ]);
    }
}

$collation = ['locale' => 'vi', 'strength' => 1];
$cursorNoidung = $chimuc2Collection->find([], ['sort' => ['word' => 1], 'collation' => $collation]);
echo "<h2>Chimuc Noidung</h2>";
echo "<table border='1'>
        <tr>
            <th>Word</th>
            <th>Document Entries</th>
        </tr>";

// Iterate through the cursor and display data in the table
foreach ($cursorNoidung as $document) {
    echo "<tr>
            <td>{$document['word']}</td>
            <td>";
    foreach ($document['doc'] as $docEntry) {
        echo "Doc ID: {$docEntry['doc_id']}, Count: {$docEntry['count']}<br>";
    }

    echo "</td></tr>";
}
echo "</table>";

$cursorTieude = $chimuctieudeCollection->find([], ['sort' => ['word' => 1], 'collation' => $collation]);
echo "<h2>Chimuc Tieude</h2>";
echo "<table border='1'>
        <tr>
            <th>Word</th>
            <th>Document Entries</th>
        </tr>";

// Iterate through the cursor and display data in the table
foreach ($cursorTieude as $document) {
    echo "<tr>
            <td>{$document['word']}</td>
            <td>";
    foreach ($document['doc'] as $docEntry) {
        echo "Doc ID: {$docEntry['doc_id']}, Count: {$docEntry['count']}<br>";
    }

    echo "</td></tr>";
}
echo "</table>";

?>
