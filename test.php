<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "POST accepté";
} else {
    echo "Méthode non autorisée";
}
?>