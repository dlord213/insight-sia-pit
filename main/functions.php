<?php

function fetchMovieDetails($title)
{
  $OMDB_API_KEY = '9f990c3d';
  $url = 'https://www.omdbapi.com/';

  $params = array(
    'apikey' => $OMDB_API_KEY,
    't' => $title,
    'plot' => 'full'
  );

  $ch = curl_init();
  curl_setopt_array($ch, array(
    CURLOPT_URL => $url . '?' . http_build_query($params),
    CURLOPT_RETURNTRANSFER => true,
  ));

  $response = curl_exec($ch);

  if ($response === false) {
    echo 'Failed to fetch data from OMDB API: ' . curl_error($ch);
    return 404;
  }

  $data = json_decode($response, true);

  curl_close($ch);

  return $data;
}

function fetchID($title)
{
  $TMDB_API_KEY = '3570438566d34e0e6ea4f6ce2ee11a1a';
  $curl = curl_init();

  $params = array(
    'api_key' => $TMDB_API_KEY,
    'query' => $title,
    'language' => 'en-US',
    'page' => 1
  );

  curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.themoviedb.org/3/search/movie?" . http_build_query($params),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
      "Authorization: sha512-29nCESlEMQ43rbnuZslK1v1AKoF+ae1bfSULCNH1eJTEM2L4qw8J9nJ08emzj4/rqoc+QT2Iazse43U1SeERoA==?oIx4",
      "accept: application/json"
    ],
  ]);

  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    $data = json_decode($response, true);
    if (isset($data['results']) && !empty($data['results'])) {
      $firstResult = $data['results'][0];

      $movieID = $firstResult['id'];
      return $movieID;
    }
  }
}

function fetchReviews($id)
{
  $TMDB_API_KEY = '3570438566d34e0e6ea4f6ce2ee11a1a';

  $curl = curl_init();

  curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.themoviedb.org/3/movie/$id/reviews?language=en-US&page=1&api_key=$TMDB_API_KEY",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
      "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIzNTcwNDM4NTY2ZDM0ZTBlNmVhNGY2Y2UyZWUxMWExYSIsInN1YiI6IjY0YWNmNmYxYjY4NmI5MDEwZTBkODZlMCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.0PhLJDk7NX4W-zPzr2jz8GWXt2WlVHWnQa08mJ_oIx4",
      "accept: application/json"
    ],
  ]);

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    $data = json_decode($response, true);
    return $data;
  }
}
