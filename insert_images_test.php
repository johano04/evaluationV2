<?php
require_once 'db.php';

$images_test = [
    'img/test1.jpg',
    'img/test2.jpg',
    'img/test3.jpg',
    'img/test4.jpg',
    'img/test5.jpg',
    'img/test6.jpg',
    'img/test7.jpg',
    'img/test8.jpg',
    'img/test9.jpg',
    'img/test10.jpg'
];

$objets = $pdo->query("SELECT id_objet FROM objet")->fetchAll();

foreach ($objets as $objet) {
    $id_objet = $objet['id_objet'];
    for ($i = 0; $i < 2; $i++) {
        $img = $images_test[array_rand($images_test)];
        
        
        $stmt = $pdo->prepare("INSERT INTO images_objet (id_objet, nom_image) VALUES (?, ?)");
        $stmt->execute([$id_objet, $img]);
    }
}

echo "Insertion images test terminée.";
