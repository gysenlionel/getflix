<?php
if (isset($_GET["id"])) {
    $id = $_GET["id"];
} else {
    echo "There is a problem loading the content.";
}

// https://api.themoviedb.org/3/movie/{movie_id}?api_key=<<api_key>>&language=en-US

$key = "api_key=4080ddd8f97d6721f32f9d82aba61857";
$curl = curl_init("https://api.themoviedb.org/3/movie/" . $id . "?" . $key . "&language=en-US");
// ici il s'agit de donner le certificat mais ça ne marche pas!!
//curl_setopt($curl, CURLOPT_CAINFO, __DIR__ . DIRECTORY_SEPARATOR . 'cert.cer');
// du coup, déconseillé:
curl_setopt_array($curl, [
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 1
]);
$data = curl_exec($curl);
if ($data === false) {
    var_dump(curl_error($curl));
} else {
    if (curl_getinfo($curl, CURLINFO_HTTP_CODE) === 200) {
        $data = json_decode($data, true);
        // echo "<pre>";
        // var_dump($data);
        // echo "</pre>";
    } else {
        echo "Erreur";
    }
}
curl_close($curl);

$infos = $data;

// Trouver les youtube à partir des id
$youtube_link = "https://www.youtube.com/embed/";
$curl2 = curl_init("http://api.themoviedb.org/3/movie/" . $id . "/videos?" . $key);
// ici il s'agit de donner le certificat mais ça ne marche pas!!
//curl_setopt($curl, CURLOPT_CAINFO, __DIR__ . DIRECTORY_SEPARATOR . 'cert.cer');
// du coup, déconseillé:
curl_setopt_array($curl2, [
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 1
]);
$data2 = curl_exec($curl2);
if ($data2 === false) {
    var_dump(curl_error($curl2));
} else {
    if (curl_getinfo($curl2, CURLINFO_HTTP_CODE) === 200) {
        $data2 = json_decode($data2, true);
        // echo "<pre>";
        // var_dump($data2['results'][0]['key']);
        // echo "</pre>";
    } else {
        echo "Erreur";
    }
}
curl_close($curl2);

$video = $data2["results"];
//echo $video;
$video = $youtube_link . $video[0]["key"];
//echo $video;

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- icone onglet à placer plus tard 
    <link rel="icon" type="image/png" href="">
    -->
    <!-- Bootstrap styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- My styles -->
    <link rel="stylesheet" href="./assets/css/caroussel.css">
    <title>Movie page details</title>
    <!-- Font Rajdhani -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/styles.css">
    <!-- Flèches typo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope&display=swap" rel="stylesheet">
</head>

<body class="bg-dark text-white">

    <!-- Voici le MENU -->
    <div class="container">
        <div class="row" id="movie_details">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 mx-auto justify-content-center mt-5 mb-3">
                <img id="poster" src="https://image.tmdb.org/t/p/w300/<?= $infos['poster_path'] ?>" alt="movie_title" class="w-100 mb-4">
                <p><strong class="fs-6 fw-bold text-danger mt-4 mb-4">Trailer:</strong></p>
                <div>
                    <iframe width="560" height="315" class="w-100 h-100" id="trailer" src="<?= $video ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </iframe>
                </div>




            </div>

            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 mx-auto justify-content-center mt-5 mb-3">
                <h2 id="title" class="text-start"><?= $infos['title'] ?>
                </h2>
                <div class="synopsis mt-4 mb-4 pt-4 pb-4 border-top border-danger">
                    <h4>Synopsis</h4>
                    <p id="synopsis"><?= $infos['overview'] ?></p>
                </div>
                <div id="genres_holder" class="genres_badges d-flex flex-row align-items-start">
                    <p class="fs-6 fw-bold text-danger">Genres: </p>
                    <span class="mx-2 badge bg-light text-dark">Horror</span>
                </div>
                <p><strong class="fs-6 fw-bold text-danger">Release date: &nbsp;</strong> <?= $infos['release_date'] ?></p>
                <p><strong class="fs-6 fw-bold text-danger">Vote average: &nbsp;</strong> <?= $infos['vote_average'] ?></p>
                <!-- <p><strong class="fs-6 fw-bold text-danger">Cast:</strong> Lorem acteurs</p>
                <p><strong class="fs-6 fw-bold text-danger">Director:</strong> Lorem directeurs</p> -->
                <p><strong class="fs-6 fw-bold text-danger">Duration: &nbsp;</strong> <?= $infos['runtime'] . " minutes" ?></p>

            </div>
        </div>
    </div>

    <!-- ////////////////////////////////////////////////////////////////////////////////////////// -->
    <!--  Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <!--  Mes js -->
    <!-- <script src="./tous_les_films.js"></script> -->
</body>

</html>