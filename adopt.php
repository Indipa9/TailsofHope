<?php
// Start session
session_start();

// Include database configuration
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page
    header("Location: login.php");
    exit();
}

// Fetch pet data from the database with username of the user who posted
$sql = "SELECT pets.*, users.username 
        FROM pets 
        JOIN users ON pets.user_id = users.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pet Adoption Posts</title>
  <link rel="stylesheet" href="stylesadopt.css">
</head>
<body>
  <header>
    <h1>Pet Adoption Posts</h1>
  </header>
  <main>
    <div class="filter-container">
      <label for="pet_type">Pet Type:</label>
      <select id="pet_type" name="pet_type" required onchange="changeBreeds()">
        <option value="">All</option>
        <option value="cat">Cat</option>
        <option value="dog">Dog</option>
        <option value="rabbit">Rabbit</option>
        <option value="ferret">Ferret</option>
      </select>
      <label for="breed">Breed:</label>
      <select id="breed" name="breed" required>
        <option value="">All</option>
      </select>
      <button onclick="filterPosts()">Filter</button>
    </div>
    <div class="post-container" id="postContainer">
      <!-- Existing pet cards will be displayed here -->
      <?php
      // Check if there are pets available
      if ($result->num_rows > 0) {
          // Output data of each row
          while ($row = $result->fetch_assoc()) {
              ?>
              <div class="post" data-pet-type="<?php echo $row['pet_type']; ?>" data-breed="<?php echo $row['breed']; ?>">
                  <div class="post-image">
                      <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                  </div>
                  <div class="post-content">
                      <h2 class="post-title"><?php echo $row['name']; ?></h2>
                      <p class="post-description"><?php echo $row['description']; ?></p>
                      <p class="posted-by">Posted by: <?php echo $row['username']; ?></p>
                      <button class="adopt-btn"><a href="./ADOPTIONregistration/index.html">Adopt Now</a></button>
                  </div>
              </div>
              <?php
          }
      } else {
          echo "<p>No pets available for adoption.</p>";
      }

      // Close database connection
      $conn->close();
      ?>
    </div>
  </main>

  <footer>
    <p>&copy; 2024 TailsOfHope</p>
  </footer>

  <script>
    function changeBreeds() {
      var petType = document.getElementById("pet_type").value;
      var breedSelect = document.getElementById("breed");

      // Clear existing options
      breedSelect.innerHTML = "<option value=''>All</option>";

      // Set options based on pet type
      if (petType === "cat") {
        var catBreeds = ["Persian", "Siamese", "Maine Coon", "Bengal"];
        catBreeds.forEach(function (breed) {
          var option = document.createElement("option");
          option.value = breed;
          option.textContent = breed;
          breedSelect.appendChild(option);
        });
      } else if (petType === "dog") {
        var dogBreeds = ["Labrador Retriever", "German Shepherd", "Golden Retriever", "Bulldog"];
        dogBreeds.forEach(function (breed) {
          var option = document.createElement("option");
          option.value = breed;
          option.textContent = breed;
          breedSelect.appendChild(option);
        });
      } else if (petType === "rabbit") {
        var rabbitBreeds = ["Holland Lop", "Mini Rex", "Netherland Dwarf", "Flemish Giant"];
        rabbitBreeds.forEach(function (breed) {
          var option = document.createElement("option");
          option.value = breed;
          option.textContent = breed;
          breedSelect.appendChild(option);
        });
      } else if (petType === "ferret") {
        var ferretBreeds = ["Albino", "Sable", "Cinnamon", "Champagne"];
        ferretBreeds.forEach(function (breed) {
          var option = document.createElement("option");
          option.value = breed;
          option.textContent = breed;
          breedSelect.appendChild(option);
        });
      }
    }

    function filterPosts() {
      var petType = document.getElementById("pet_type").value;
      var breed = document.getElementById("breed").value;

      var posts = document.getElementsByClassName("post");

      // Loop through all posts
      for (var i = 0; i < posts.length; i++) {
        var post = posts[i];

        // Show all posts if both pet type and breed are set to "All"
        if (petType === "" && breed === "") {
          post.style.display = "block";
        } else {
          // Otherwise, check if the post matches the selected filters
          var postPetType = post.getAttribute("data-pet-type");
          var postBreed = post.getAttribute("data-breed");
          if ((petType === "" || postPetType === petType) && (breed === "" || postBreed === breed)) {
            post.style.display = "block"; // Show the post
          } else {
            post.style.display = "none"; // Hide the post
          }
        }
      }
    }

    // Call changeBreeds() initially to load breed options based on pet type
    changeBreeds();
  </script>
</body>
</html>
