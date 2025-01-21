<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
        }
        h1 {
            text-align: center;
        }
        form {
            margin-bottom: 20px;
        }
        form input, form button {
            display: block;
            margin: 10px 0;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .message {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Library Management System</h1>

        <?php
        // Initialize book array in session if not already set
        if (!isset($_SESSION['books'])) {
            $_SESSION['books'] = [];
        }

        $message = "";

        // Handle book addition
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['copies'])) {
            $title = trim($_POST['title']);
            $copies = (int)$_POST['copies'];

            if ($title !== "" && $copies > 0) {
                $found = false;

                // Check if book already exists
                foreach ($_SESSION['books'] as &$book) {
                    if ($book['title'] === $title) {
                        $book['copies'] += $copies;
                        $found = true;
                        break;
                    }
                }

                // If not found, add as a new book
                if (!$found) {
                    $_SESSION['books'][] = ['title' => $title, 'copies' => $copies];
                }

                $message = "Book added successfully!";
            } else {
                $message = "Please enter a valid title and number of copies.";
            }
        }

        // Handle book search
        $searchResults = [];
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['searchTitle'])) {
            $searchTitle = trim($_GET['searchTitle']);
            if ($searchTitle !== "") {
                foreach ($_SESSION['books'] as $book) {
                    if (stripos($book['title'], $searchTitle) !== false) {
                        $searchResults[] = $book;
                    }
                }

                if (empty($searchResults)) {
                    $message = "No books found matching the search criteria.";
                }
            }
        }
        ?>

        <!-- Form to add new books -->
        <form method="POST">
            <h2>Add a New Book</h2>
            <label for="title">Book Title:</label>
            <input type="text" id="title" name="title" placeholder="Enter book title" required>

            <label for="copies">Number of Copies:</label>
            <input type="number" id="copies" name="copies" placeholder="Enter number of copies" required>

            <button type="submit">Add Book</button>
        </form>

        <!-- Form to search for books -->
        <form method="GET">
            <h2>Search for a Book</h2>
            <label for="searchTitle">Book Title:</label>
            <input type="text" id="searchTitle" name="searchTitle" placeholder="Enter book title to search">

            <button type="submit">Search</button>
        </form>

        <!-- Table to display the catalog -->
        <h2>Book Catalog</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Available Copies</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['books'] as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['title']) ?></td>
                        <td><?= htmlspecialchars($book['copies']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Display search results if any -->
        <?php if (!empty($searchResults)): ?>
            <h2>Search Results</h2>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Available Copies</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($searchResults as $result): ?>
                        <tr>
                            <td><?= htmlspecialchars($result['title']) ?></td>
                            <td><?= htmlspecialchars($result['copies']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Placeholder for messages -->
        <div id="message" class="message">
            <?= htmlspecialchars($message) ?>
        </div>
    </div>
</body>
</html>
