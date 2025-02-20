$sql = "SELECT shows.id, shows.show_date, shows.show_time, 
               movies.title AS movie_title, movies.poster, movies.release_date, movies.duration,
               theaters.name AS theater_name
        FROM shows
        JOIN movies ON shows.movie_id = movies.id
        JOIN theaters ON shows.theater_id = theaters.id
        ORDER BY shows.show_date, shows.show_time";

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $movie = $result->fetch_assoc();
}

$result = $conn->query($sql);
?>