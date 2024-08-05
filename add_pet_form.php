<?php
session_start();

// Assuming $post_id is set somewhere in your application logic before rendering the form
// For demonstration purpose, let's assume it's set to a static value
$post_id = 123; // Replace this with your actual value or logic to obtain the post_id

// Store the post_id in a session variable
$_SESSION['post_id'] = $post_id;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Pet</title>
</head>
<body>
    <h2>Add a New Pet</h2>
    <form action="add_pet.php" method="POST" enctype="multipart/form-data">
        <!-- Add a hidden input field for post_id -->
        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
        
        <label for="pet_type">Pet Type</label><br>
        <select id="pet_type" name="pet_type" required onchange="changeBreeds()">
            <option value="">Select Pet Type</option>
            <option value="cat">Cat</option>
            <option value="dog">Dog</option>
            <option value="rabbit">Rabbit</option>
            <option value="ferret">Ferret</option>
        </select><br>

        <label for="breed">Breed</label><br>
        <select id="breed" name="breed" required>
            <option value="">Select Breed</option>
        </select><br>

        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br>
        
        <label for="age">Age:</label><br>
        <input type="number" id="age" name="age" required><br>
        
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" required></textarea><br>

        <label for="image">Image:</label><br>
        <input type="file" id="image" name="image" accept="image/*" required><br>
        
        <input type="submit" value="Submit">
    </form>

    <script>
        function changeBreeds() {
            var petTypeSelect = document.getElementById("pet_type");
            var breedSelect = document.getElementById("breed");
            var petType = petTypeSelect.value;

            // Clear existing options
            breedSelect.innerHTML = "<option value=''>Select Breed</option>";

            // Set options based on pet type
            if (petType === "cat") {
                var catBreeds = ["Persian", "Siamese", "Maine Coon", "Bengal"];
                catBreeds.forEach(function(breed) {
                    var option = document.createElement("option");
                    option.value = breed;
                    option.textContent = breed;
                    breedSelect.appendChild(option);
                });
            } else if (petType === "dog") {
                var dogBreeds = ["Labrador Retriever", "German Shepherd", "Golden Retriever", "Bulldog"];
                dogBreeds.forEach(function(breed) {
                    var option = document.createElement("option");
                    option.value = breed;
                    option.textContent = breed;
                    breedSelect.appendChild(option);
                });
            } else if (petType === "rabbit") {
                var rabbitBreeds = ["Holland Lop", "Mini Rex", "Netherland Dwarf", "Flemish Giant"];
                rabbitBreeds.forEach(function(breed) {
                    var option = document.createElement("option");
                    option.value = breed;
                    option.textContent = breed;
                    breedSelect.appendChild(option);
                });
            } else if (petType === "ferret") {
                var ferretBreeds = ["Albino", "Sable", "Cinnamon", "Champagne"];
                ferretBreeds.forEach(function(breed) {
                    var option = document.createElement("option");
                    option.value = breed;
                    option.textContent = breed;
                    breedSelect.appendChild(option);
                });
            }
        }
    </script>
</body>
</html>
